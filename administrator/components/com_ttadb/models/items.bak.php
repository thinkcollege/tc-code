<?php
/**
 * Items Model for CCK component
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access!');
jimport('joomla.application.component.model');

/**
 * items Model
 */
class TtaDBModelItems extends JModel {
	
	static private $tCount = null;
	
	private $_query = '';
	
	static private $items = array();
	/**
	 * items data array
	 *
	 * @var array
	 */
	private $_data;
	
	private $_total = null;
	
	private $_pagination = null;
	
	function __construct() {
		parent::__construct();
 		
		$this->getTable('AttrOf');
		global $mainframe, $option;

		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit',		$limit);
		$this->setState('limitstart',	$limitstart);
	}	
  
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	protected function _buildQuery($typeId, $limit) {
		$attr 			= new stdClass;
		$attr->id		= 0;
		$attr->typeId	= $typeId ? abs($typeId) : JRequest::getInt('typeId');
		$attr->flags	= TableAttrOf::FLAG_SUMMARY;
		$clauses		= $this->_generateDerivedAttrs($attr);
		if ($clauses === false) {
			return "SELECT 1";
		}
		$t		= "`t{$attr->typeId}-1`";
		$title	= !empty($clauses['title']) ? ", CONCAT({$clauses['title']}) AS `_title`" : '';
		$ids	= JRequest::getVar('cid');
		if ($limit && is_array($ids)) {
			$num = count($ids);
			if ($num == 1) {
				$clauses['where'] .= " AND $t.`id` = " . intval($ids[0]);
			} else if ($num > 1) {
				$where = '';
				$sep = '';
				for ($i = 0; $i < $num; $i++) {
					if (intval($ids[$i]) >= 0) {
						$where .= $sep . intval($ids[$i]);
						$sep = ',';
					}
				}
				$clauses['where'] .= !empty($where) ? " AND $t.`id` IN ($where) " : '';
			}
		}
		$query	= " SELECT $t.`id` {$clauses['cols']} $title, $t.`typeId`, $t.`published`\n FROM {$clauses['tables']} WHERE 1 {$clauses['where']} " . (!empty($clauses['order']) ? " ORDER BY {$clauses['order']}" : '');
		#if ($attr->typeId == 111) print "<pre>query:" . str_replace('#__', 'jos_', $query) . "</pre>";
		return $query;
	}

	protected function _generateDerivedAttrs($attr, $data = array(), $table = '', $label = '', $summary = false) {
		static $tItem = '', $tAttrOf = '', $tValue = '';
		if (empty($attr->typeId)) {
			return false;
		}
		if (func_num_args() == 1) {
			$tItem = $this->getDBO()->nameQuote($this->getTable('Item')->_tbl);
			$tValue = $this->getDBO()->nameQuote($this->getTable('Value')->_tbl);
			$tAttrOf = $this->getDBO()->nameQuote($this->getTable('AttrOf')->_tbl);
			self::$tCount = new stdClass;
			self::getInstance('Type', 'TtaDbModel');	// required for sorting constants.
		}
		$type	= "t$attr->typeId";
		self::$tCount->$type = isset(self::$tCount->$type) ? self::$tCount->$type + 1 : 1;
		$ao		= "`$type-" . self::$tCount->$type . "ao`";
		$i		= "`$type-" . self::$tCount->$type . "i`";
		$v		= "`$type-" . self::$tCount->$type . "v`";
		$t		= "`$type-" . self::$tCount->$type . "`";
		$return = array('tables' => '', 'cols' => '', 'where' => '', 'order' => '', 'title' => '');
		$sql	= (func_num_args() == 1 ? '' : "\nLEFT JOIN") . " (SELECT $i.id";
		$data	= func_num_args() == 1 ? JRequest::get('request') : $data;
		$data	= is_array($data) ? $data : array($data);
		if ($attr->typeId > 99 && empty($attr->attrs)) {
			$attr->attrs = self::getInstance('attrs', 'TtaDbModel')->getAttrsOf($attr->typeId);
		} else if (empty($attr->attrs)) {
			$tmp = $attr;
			$attr = new stdclass;
			$attr->attrs = array($tmp);
		}
		
		foreach ($attr->attrs as $a) {
			if (JFactory::getUser()->get('id') == 0 && ($a->flags & FableAttrOf::FLAG_INTERNAL)) {
				continue;
			}
			$col = "a$a->attrOfId";
			if ($a->typeId > 99) {
				$arr	= $this->_generateDerivedAttrs($a, isset($data[$col]) ? $data[$col] : array(), $t, "$label{$col}-", $summary || ($a->flags & TableAttrOf::FLAG_MERGE));
				$return['tables']	.= $arr['tables'];
				$return['where']	.= $arr['where'];
				// if the type is to be merged than just return the id instead of the derived value. The value will be obtain from the attr's choices.
				$return['cols']		.= $a->flags & TableAttrOf::FLAG_MERGE	? ", $t.`$label$col`" : $arr['cols'];	// Else just add the returned columns to the main column  output.
				$return['order']	.= ($arr['order'] ? ($return['order'] ? ', ' : '') . $arr['order'] : '');
				$return['title']	.= $a->flags & TableAttrOf::FLAG_TITLE ? $arr['title'] : '';
			}
			$prefix = $summary && !empty($a->prefix) ? $this->getDBO()->quote($a->prefix) . ', ' : '';
			$suffix	= $summary && !empty($a->suffix) ? ", " . $this->getDBO()->quote($a->suffix) : '';
			$val	= $prefix || $suffix ? "CONCAT(" . ($a->typeId > 99 ? "$i.`id`, '$$', " : "") . "$prefix $v.`value` $suffix)" : "$v.`value`";
			$sql   .= ",\n\tGROUP_CONCAT(IF($v.`attrOfId` = $a->attrOfId, $val, '') SEPARATOR '') AS `$label$col`";
			
			if (func_num_args() == 1 || ($a->flags & TableAttrOf::FLAG_SUMMARY)) {
				$return['cols'] .= $a->flags & TableAttrOf::FLAG_MERGE ? "" : ", $t.`$label$col`";
			}
			if (isset($data[$col])) {
				$vals = '';
				$data[$col] = is_array($data[$col]) ? $data[$col] : array($data[$col]);
				foreach ($data[$col] as $key => $val) {
					if (is_numeric($key) && !empty($val)) {
						$vals .= $sep . $this->getDBO()->quote($val);
						$sep = ', ';
					}
				}
				if (!empty($vals)) {
					$return['where'] .= " AND $t.$col IN ($vals)";
				}
			}
			if ($a->sort > 0) {
				if ($a->tSort == TtaDBModelType::SORT_INSERT) {
					$sql .= ", $v.`count` AS `$label{$col}count`";
					$return['order'] .= (!empty($return['order']) ? ',' : '') . "$t.`$label{$col}count`";
				} else {
					$return['order'] .= (!empty($return['order']) ? ',' : '') . "$t.`$label$col` = '', $t.`$label$col`" . ($a->tSort == TtaDbModelType::SORT_DESC ? ' DESC' : '');
				}
			}
			if ($a->flags & TableAttrOf::FLAG_TITLE) {
				$return['title'] .= (!empty($return['title']) ? ', ' : '') . "$t.`$label$col`";
			}
		}
		
		$sql .= (func_num_args() == 1 ? ", $v.`count`, $i.`typeId`, $i.`published`" : '') . "\nFROM $tItem $i LEFT JOIN $tValue $v ON $i.`id` = $v.`itemId` "
			  . "WHERE $i.`typeId` = $attr->typeId "
			  . (JFactory::getUser()->get('id') == 0 ? "AND $i.`published` = 1 " : '')
			  . " GROUP BY $i.id, $v.`count` ORDER BY $v.`count`) AS $t"
			  . (func_num_args() > 1 ? "ON $t.`id` = $table.`" . substr($label, 0, -1). '`' : '');
		$return['tables'] = $sql . $return['tables'];
		if (func_num_args() == 1) {
			$return['order'] .= (!empty($return['order']) ? ', ' : '' ) . "$t.`id`, $t.`count`";
		}
		return $return;
	}
 
	/**
	 * Retrieves the items data
	 * @return array Array of objects containing the data from the database
	 */
	function getData($typeId = null, $limit = true) {
		// Lets load the data if it doesn't already exist
		$typeId		= $typeId ? abs($typeId) : JRequest::getInt('typeId', 0);
		if (empty(self::$items[$typeId])) {
			$results	= $this->_getList($this->_buildQuery($typeId, $limit));
			if ($this->getDBO()->getErrorMsg()) {
				JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
				return false;
			}
			$ret	= array();
			$cid	= 0;
			$rows 	= 0;
			$offset = 0;
			#if ($results[0]->typeId == 110) 
			#	print "<pre>" . print_r($results, true);
			for ($i = 0; $i < count($results); $i++) {
				$row =& $results[$i];
				if ($rows < $this->getState('limitstart')) {
					if ($cid != $row->id) {
						$rows++;
					}
					continue;
				}
				if ($cid != $row->id) {
					if ($limit && $rows == ($this->getState('limitstart') + $this->getState('limit'))) {
						return $ret;
					}
					$data	= new stdClass; 
					$ret[]	= $data;
					$cid	= $row->id;
					$rows++;
					$Offset = $i;
				}
				$data->id		 = $row->id;
				$data->published = !empty($data->published) || $row->published;
				if (!empty($row->_title)) {
					$data->_title 	 = $row->_title;
				}
				$data->typeId	= empty($data->typeId) ? $row->typeId : (isset($data->typeId) ? $data->typeId : 0);
				$dOffset		= $i - $offset;
				
				foreach ($row as $label => $datum) {
					if (in_array($label, array('id', 'published', '_title', 'typeId')) || $datum === null) {
						continue;
					}
					$parts	= explode('-', $label);
					if (count($parts) == 1 && is_array($data->{$parts[0]})) {
						$parts[0] .= '-ids';
						if (!isset($data->{$parts[0]})) {
							$data->{$parts[0]} = array();
						}
					}
					$attr	=& $data->{$parts[0]};
					for ($j = 1; $j < count($parts); $j++) {
						if (!isset($attr[$dOffset])) {
							$attr[$dOffset] = array();
						}
						if (!isset($attr[$dOffset][$parts[$j]])) {
							$attr[$dOffset][$parts[$j]] = array();
						}
						$attr =& $attr[$dOffset][$parts[$j]];
					}
					$attr[] = $datum;
				}
			}
			self::$items[$typeId] = $rows == 1 ? $ret[0] : $ret;
		}
		return self::$items[$typeId];
	}
	
	function getTotal() {
		return $this->_getListCount($this->_buildQuery(JRequest::getVar('typeId', 0)));
	}
	
	function getPagination() {
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
}