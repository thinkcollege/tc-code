<?php
/**
 * Attributes Model for the Content Creation Kit
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );

/**
 * Attributes Model
 */
class TtaDbModelAttrs extends JModel {
	
	static private $choices = array();
	
	/**
	 * Attributes data array
	 *
	 * @var array
	 */
	private $_data;
	
	private $_total = null;
	
	private $_pagination = null;
	
	function __construct() {
		parent::__construct();
 		if (JRequest::getVar('task') == 'listAttrs') {
			global $mainframe, $option;
 
			// Get pagination request variables
			$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
			// In case limit has been changed, adjust it
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
			$this->setState('limit', $limit);
			$this->setState('limitstart', $limitstart);
 		}
	}

	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery($typeId) {
		$a	=& $this->getDBO()->nameQuote($this->getTable('Attr')->_tbl);
		$t	=& $this->getDBO()->nameQuote($this->getTable('Type')->_tbl);
		$ao	=& $this->getDBO()->nameQuote($this->getTable('AttrOf')->_tbl);
		
		$typeId = intval($typeId);
		if ($typeId > 0) {
			$where	= "ao.`typeId` = $typeId";
			$join	= '';
			$order	= 'ao.`ordering`';
		} else if ($typeId == 0) {
			$where	= '1';
			$join	= " AND ao.`typeId` = 0";
			$order	= 'a.`adminLabel`';
		} else {
			JError::raiseWarning(500, 'Bad Type when getting attributes.');
			#print "<!-- back trace:"; debug_print_backtrace(); print '-->';
			return 'SELECT 1';
		}
		$sql = "SELECT a.`id`, a.`typeId`, a.`adminLabel`, `a`.`validation`, `ao`.`id` AS `attrOfId`,
			 		ao.`inputLabel`, ao.`displayLabel`, ao.`compareLabel`, ao.`ordering`, `t`.`sort` AS `tSort`,
			 		ao.`flags`, `ao`.`typeId` AS `attrOfTypeId`, `ao`.`prefix`, `ao`.`suffix`, `ao`.`sort`
			   FROM $a a LEFT JOIN $ao ao ON a.`id` = ao.`attrId`$join
		  LEFT JOIN $t t ON t.`id` = `a`.`typeId`
			  WHERE $where ORDER BY $order";
		#print $typeId == 0 ? "<pre>sql:$sql</pre>" : '';
		return $sql;
	}
 
	/**
	 * Retrieves the attribute data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_data)) {
			$data = $this->getAttrsOf(JRequest::getInt('typeId', 0));
			if (!is_array($data)) {
				return $data;
			}
			$this->_data	= $data;
			$this->_total	= JRequest::getInt('typeId', 0) > 0 ? 1 : count($this->_data);
		}
		return $this->_data;
	}
	
	function getAttrsOf($typeId, $choices = true, $children = true, $depth = 0) {
		$typeId	= intval($typeId);
		$rows	=& $this->_getList($this->_buildQuery($typeId));
		
		if ($this->getDBO()->getErrorMsg()) {
			return JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
		}
		$attrs = array();
		for ($i = 0; $i < count($rows); $i++) {
			$attr =& $rows[$i];
			if ($typeId > 0) {
				$attrs[$attr->attrOfId] = $attr;
			} else {
				$attrs[] = $attr;
			}
			if ($children && $attr->typeId > 99) {
				$attr->attrs =& $this->getAttrsOf($attr->typeId, $choices);
				foreach ($attr->attrs as &$a) {
					$a->parent =& $attr;
				}
			}
			
			if ($choices && $attr->typeId > 99) {
				$attr->choices = $this->getChoices($attr);
			}
		}
		if ($this->getDBO()->getErrorMsg()) {
			return JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
		}
		return $attrs;
	}
	
	function getAttrOfById($aoId, $choices = true, $children = true) {
		$a		= $this->getDBO()->nameQuote($this->getTable('Attr')->_tbl);
		$ao		= $this->getDBO()->nameQuote($this->getTable('AttrOf')->_tbl);
		$aoId	= abs(intval($aoId));
		$sql = "SELECT	a.`id`, a.`typeId`, ao.`id` AS `attrOfId`, ao.`typeId` AS `attrOfTypeId`, ao.`inputLabel`,
						ao.`displayLabel`, ao.`compareLabel`, a.`validation`, ao.`ordering`, ao.`flags`
				  FROM	$a AS `a` LEFT JOIN $ao `ao` ON `ao`.`attrId` = `a`.`id`
				 WHERE	ao.`id` = $aoId";
		#print "<pre>$sql</pre>";
		$data 			= $this->_getList($sql);
		if (count($data) == 0) {
			return null;
		}
		$attr 			= $data[0];
		if ($children && $attr->typeId > 99 && ($attr->flags & TableAttrOf::FLAG_MERGE) == 0) {
			$attr->attrs =& $this->getAttrsOf($attr->typeId, $choices);
			foreach ($attr->attrs as &$a) {
				$a->parent =& $attr;
			}
		}
		if ($choices && $attr->typeId > 99) {
			$attr->choices = $this->getChoices($attr);
		}
		return $attr;
	}
	
	function getChoices(&$attr) {
		if (!empty(self::$choices[$attr->typeId])) {
			return self::$choices[$attr->typeId];
		}
		$sep = '';
		$choices = '';
		$items = self::getInstance('Items', 'TtaDbModel')->getData($attr->typeId, false);
		if (empty($attr->attrs)) {
			$attr->attrs = $this->getAttrsOf($attr->typeId);
			if (count($attr->attrs) == 0) {
				return '';
			}
		}
		foreach ($items as $item) {
			$choice = '';
			foreach ($attr->attrs as $a) {
				$id		= "a$a->attrOfId";
				if (($a->flags & TableAttrOf::FLAG_SUMMARY) == 0 || empty($item->$id)) {
					continue;
				}
				$dud	.= $z->prefix . $a->suffix;
				if ($a->typeId > 99) {
					$vals = $this->getChoices($a);
					$aVals = explode('||', $vals);
					foreach ($item->$id as $join) {
						foreach ($aVals as $v) {
							if (strpos($v, "$join$$") === 0) {
								$choice .= $a->prefix . str_replace("$join$$", '', $v) . $a->suffix;
								break;
							}
						}
					}
				} else {
					$choice .= $a->prefix . implode('', $item->$id) . $a->suffix;
				}
			}
			if ($dud != $choice) {
				$choices .= $sep . $item->id . '$$' . $choice;
				$sep = '||';
			}
		}
		self::$choices[$attr->typeId] = $choices;
		return $choices;
	}
	
	protected function generateDerivedChoices($attr, $depth = 0) { 
		$sql	= "";	//  Return value
		$sep 	= '';	//	Separator between columns
		$dud	= '';	//	String to hold the prefixes and suffixes to compare the concatenated values against.
		$depth	= intval($depth);
		$db		=& $this->getDBO();
		$v		= $db->nameQuote($this->getTable('Value')->_tbl);
		$attrs	= $attr->typeId > 99 ? $this->getAttrsOf($attr->typeId, 1) : array($attr);
		#if ($attr->typeId == 111) print "<pre>num:" . count($attrs) . "</pre>";
		foreach ($attrs as &$a) {
			// Skip attributes that are not in the summary.
			if (($a->flags & TableAttrOf::FLAG_SUMMARY) == 0) {
				continue;
			}
			$clause	= '';	//	SQL clause
			$sql	.= $sep;
			$prefix	= '';
			$suffix	= '';
			if (!empty($a->prefix)) {
				$prefix = $db->quote($a->prefix) . ', ';
				$dud	.= substr($db->quote($a->prefix), 1, -1);
			}
			if (!empty($a->suffix)) {
				$suffix	= ", {$db->quote($a->suffix)}";
				$dud	.= ', ' . substr($db->quote($a->suffix), 1, -1);
			}
			if ($a->typeId >= 100) {
				$d = $depth + 1;
				$clause	.= "GROUP_CONCAT((SELECT {$this->generateDerivedChoices($a, $d)} FROM $v `v$d-$a->typeId`"
						 . " WHERE `v$d-$a->typeId`.`itemId` = `v$depth" . ($depth ? "-$attr->typeId" : '') . "`.`value`) SEPARATOR '')";
			} else if ($a->typeId < 100) {
				$t = $depth ? "`v$depth-$attr->typeId`" : "`v0`";
				$clause .= "GROUP_CONCAT(IF($t.`attrOfId` = $a->attrOfId AND $t.`Value` <> '', $t.`value`, NULL) SEPARATOR '')";
			}
			$sql .= "IFNULL(CONCAT(" . (!empty($prefix) || !empty($suffix) ? "$prefix $clause $suffix" : $clause) . "), '')";
			$sep = ",\n";
		} 
		if (!empty($clause)) {
			$sql = "IF(CONCAT($sql) <> '$dud', "
				 . ($depth == 0 ? "CONVERT(CONCAT(i0.id, '$$', $sql) USING utf8), NULL)" : "CONCAT($sql), '')");
		}
		#if ($attr->typeId == 111) print "<pre>clause:$sql</pre><br />";
		return $sql;
	}

	function getTotal() {
		// Load the content if it doesn't already exist
		if (empty($this->_total)) {
			$this->getData();
		}
		return $this->_total;
	}
	
	function getPagination() {
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

	function getTypeIdFromPath($path) {
		if (empty($path)) {
			return null;
		}
		$parts	= explode('-', $path);
		$num	= count($parts) - 1;
		$a		= $this->getDBO()->nameQuote($this->getTable('Attr')->_tbl);
		$ao		= $this->getDBO()->nameQuote($this->getTable('AttrOf')->_tbl);
		$name	= $this->getDBO()->quote($name);
		$sql	= "SELECT `a$num`.`typeId` FROM $ao AS `ao0`";
		$sep	= "";
		$where	= "";
		for ($i = 0; $i <= $num; $i++) {
			$sql	.= "$sep LEFT JOIN $a AS `a$i` ON `ao$i`.`attrId` = `a$i`.`id`";
			$where	.= ($sep ? ' AND ' : '') . "`ao$i`.`id` = " . abs(substr($parts[$i], 1));
			$sep = " LEFT JOIN $ao AS `ao" . ($i + 1) ."` ON `ao" . ($i + 1) . "`.`typeId` = `a$i`.`typeId`";
		}
		print "<pre>" . str_replace('#__', 'jos_', $sql) . " WHERE $where</pre>";
		$attrOfId = $this->_getList("$sql WHERE $name");
		return isset($attrOfId[0]) ? $this->getAttrOfById($attrOfId[0]->id) : null;
	}	
}