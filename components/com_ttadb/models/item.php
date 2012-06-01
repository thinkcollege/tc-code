<?php
/**
 * Progarm Model for ThinkCollege items Database
 * 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

/**
 * item Model
 *
 */
class TtaDbModelItem extends JModel {
	
	const STORE_IGNORE_DUPLICATE = 1;
	
	/**
	 * item data array
	 *
	 * @var array
	 */
	private $_data = null;
	private $_Stardata = null;
	
	/**
	 * item id int
	 * 
	 * @var int
	 */
	private $_id = 0;
	
	private $previousferences = 0;
	
	function __construct() {
		parent::__construct();
		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId($array[0]);
	}

	/**
	 * Method to set the item identifier
	 *
	 * @access	public
	 * @param	int item identifier
	 * @return	void
	 */
	function setId($id) {
		// Set id and wipe data
		$this->_id	 = intval($id);
		$this->_data = null;
	} 
	
	function getId() {
		return $this->_id;
	}
	
	/**
	 * Retrieves the item data
	 * @return array Array of objects containing the data from the database
	 */
	function &getData() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_data) && !empty($this->_id)) {
			$this->_data = self::getInstance('Items', 'TtaDbModel')->getData();
		} else if (empty($this->_id)) {
			$data = new stdClass;
			$data->id = 0;
			$data->typeId = 0;
			$data->published = 0;
			return $data;
		}
		return $this->_data;
	}
	
	protected function getValueIDs($attrs, $itemId = 0) {
		if (!is_array($attrs)) {
			return false;
		}
		$ids	= array();
		$v		= $this->getDBO()->nameQuote($this->getTable('Value')->_tbl);
		$itemId	= $itemId == 0 ? $this->getId() : abs(intval($itemId));
		
		$rows = $this->_getList("SELECT `id`, `attrOfId`, `count`, `value` FROM $v WHERE `itemId` = $itemId ORDER BY `attrOfId`, `count`");
		if ($this->getDBO()->getErrorMsg()) {
			JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
			return false;
		}
		foreach ($rows as $row) {
			$obj	 = new stdClass;
			$obj->id = intval($row->id);
			if ($attrs[$row->attrOfId]->typeId > 99 && ($attrs[$row->attrOfId]->flags & TableAttrOf::FLAG_MERGE) == 0) {
				$obj->attrs =& $this->getValueIDs($attrs[$row->attrOfId]->attrs, $row->value);
			}
			if (!isset($ids[$row->attrOfId])) {
				$ids[$row->attrOfId] = array($row->count => $obj);
			} else {
				$ids[$row->attrOfId][$row->count] = $obj;
			}
		}
 		#print "<pre>row:" . print_r($row, true) . "ids:" . print_r($ids, true) . "</pre>";
		return $ids;
	}
	
	function store() {
		$trans		= array();
		$files		= array();
		$tItem	 	= $this->getTable();
		$typeId		= JRequest::getVar('typeId', 0, '', 'int');
		
		if (!$tItem->bind(array('id' => JRequest::getInt('cid'), 'typeId' => $typeId, 'published' => 0)) || !$tItem->check()) {
			JError::raiseWarning(500, JText::_('Failed to save item.'));
			return false;
		}
		$trans[]	=& $tItem;
		$attrs		=& self::getInstance('Attrs', 'TtaDbModel')->getAttrsOf($typeId);
		$ids		=& $this->getValueIDs($attrs);
		if ($ids === false) {
			JError::raiseWarning(500, JText::_('Failed to get value IDs.'));
			return false;
		}
		
		// Setup $values because array_merge_recursive does not work the way we need it to.
		if (count($_FILES) > 0) {
			$post	= JRequest::get('post');
			$files	= array();
			foreach (JRequest::get('files') as $key => $file) {
				$files[$key] = array();
				if (!is_array($file['name'])) {
					$files[$key][] = $file;
					continue;
				}
				for ($i = 0; $i < count($file['name']); $i++) {
					$tmp = array();
					foreach ($file as $attr => $val) {
						$tmp[$attr] = $val[$i];
					}
					if (isset($post[$key][$i])) {
						$tmp = array_merge($post[$key][$i], $tmp);
						unset($post[$key][$i]);
					}
					$files[$key][$i] = $tmp;
				}
			}
			$values	= array_merge_recursive($post, $files);
			$_FILES = array();
		} else {
			$values	= JRequest::get('post');
		}
		$value	= array('id' => 0, 'itemId' => 0, 'attrOfId' => 0);
		$ret	= true;
		foreach ($attrs as $a) {
			if ($a->typeId > 99 && ($a->flags & TableAttrOf::FLAG_MERGE) == 0) {	// If the type is not merged than when edit one type that includeds other types than a new item of the "sub" type can be created.
				for ($i = 0; $i < ($a->flags & TableAttrOf::FLAG_MULTIPLE ? count($values["a$a->attrOfId"]) : 1); $i++) {
					if (is_array($values["a$a->attrOfId"][$i])) {
						$_POST = array();
						$_REQUEST = array();
						JRequest::setVar("a$a->attrOfId", $values["a$a->attrOfId"][$i], 'post');
						JRequest::setVar('cid', $ids[$a->attrOf][$i], 'post');
						JRequest::setVar('typeId', $a->typeId, 'post');
						JRequest::setVar('published', $published, 'post');
						if (!$this->store()) {
							JRequest::set($values, 'post', true);
							$ret = false;
							break;
						}
						$id = JRequest::getInt('cid', 0, 'post');
						if ($id == 0) {
							continue;
						}
					} else {
						$id = abs($values["a$a->attrOfId"][$i]);
					}
					$row				=& $this->getTable('Value');
					$value['id']		= isset($ids[$a->attrOfId][$i]) ? intval($ids[$a->attrOfId][$i]->id) : 0;
					$value['value']		= $id;
					$value['count']		= $i;
					$value['attrOfId']	= $a->attrOfId;
					if (!$row->bind($value) || !$row->check()) {
						JError::raiseWarning(500, $row->getError());
						$ret = false;
						break;
					}
					$trans[] =& $row;
				
				}
				if (!$ret) {
					break;
				}
				JRequest::set($values, 'post', true);
				continue;
			}
			for ($i = 0; $i < count($values["a{$a->attrOfId}"]); $i++) {
				unset($value['value']);
				$row				=& $this->getTable('Value');
				$value['id']		= isset($ids[$a->attrOfId][$i]) ? intval($ids[$a->attrOfId][$i]->id) : 0;
				$value['value']		= isset($values["a$a->attrOfId"][$i]) ? $values["a$a->attrOfId"][$i] : null;
				$value['count']		= $i;
				$value['attrOfId']	= $a->attrOfId;
				if ($value['id'] > 0 && is_array($value['value']) && !empty($value['value']['delete'])) {
					$row->_delete = true;
				} else if (empty($value['value']) && !$a->required) {
					continue;
				} else if (!$row->bind($value) || !$row->check()) {
					JError::raiseWarning(500, $row->getError());
					$ret = false;
					break;
				}
				$trans[] =& $row;
				if ($a->typeId == 5) {
					$files[] =& $row;
				}
			}
		}
		if (count($trans) == 1) {
			return true;
		}
		
		if ($trans[0]->id == 0) {
			#print "Looking for pre-existing item:<br />";
			$id = $this->findItemId($trans);
			if ($id > 0) {
				JRequest::setVar('cid', $id, 'post');
				$this->setId($id);
				return true;	// Because an itemID was found we don't need to store this.
			}
		}
		if ($ret) {
			for ($i = 0; $i < count($trans); $i++) {
				if ($i > 0) {
					$trans[$i]->itemId = $trans[0]->id;
				}
				if (!empty($trans[$i]->_delete)) {
					if (!$trans[$i]->delete()) {
						JError::raiseWarning(500, $trans[$i]->getError());
						$ret = false;
						break;
					}
				} else if (!$trans[$i]->store()) {
					JError::raiseWarning(500, 'Fialed to store data ' . $trans[$i]->id . ' with value "' . $trans[$i]->value . '" with error: ' . $trans[$i]->getError());
					#print "<p style=\"color:red;\">Fialed to store data {$trans[$i]->id} with value {$trans[$i]->value} with error: {$trans[$i]->getError()}</p>";
					$ret = false;
					break;
				}
			}
		}
		if (!$ret) {
			for ($i = 0; $i < count($files); $i++) {
				if (!empty($files[$i]->_delete)) {
					$files[$i]->delete();
				}
			}
		}
		JRequest::set($values, 'post');
		JRequest::setVar('cid', $trans[0]->id, 'post');
		return $ret;
	}
	
	function findItemId(&$trans) {
		if (!is_array($trans)) {
			return 0;
		} else if (isset($trans[0]->id) && $trans[0]->id > 0) {
			return $trans[0]->id;
		}
		$i		= $this->getDBO()->nameQuote($this->getTable('Item')->_tbl);
		$v		= $this->getDBO()->nameQuote($this->getTable('Value')->_tbl);
		$sep	= '';
		$sql	= "SELECT DISTINCT IFNULL(v.`itemId`, 0) AS `id` FROM $v AS `v`\n"
				. "LEFT JOIN $i i ON i.id = v.itemId ";
		$where	= "i.typeId = {$trans[0]->typeId} AND (";
		foreach ($trans as $j => &$t) {
			if ($j == 0) {
				continue;
			}
			$val	= $this->getDBO()->quote($t->value ? $t->value : 0);
			#$sql	.= "LEFT JOIN $v AS `v$j`ON v.`itemId` = `v$j`.`itemId`\n";
			$where	.= "$sep(`v`.`attrOfId` = $t->attrOfId AND `v`.`value` = $val)";
			$sep 	 = ' OR ';
		}
		$sql .= "WHERE $where GROUP BY i.`id` HAVING COUNT(v.`id`) = " . (count($trans) - 1);
		
		#print "<pre>sql:" . str_replace('#__', 'jos_', $sql) . "</pre>";
		$rs = $this->_getList($sql);
		if (count($rs) == 1) {
			return $rs[0]->id;
		}
		return 0;
	}
	
	function delete() {
		$db		=& $this->getDBO();
		$cids	= JRequest::getVar('cid', array(0), 'post', 'array');
		$row	=& $this->getTable();
		$v		= $db->nameQuote($this->getTable('Value')->_tbl);
		foreach ($cids as $cid) {
			$cid = intval($cid);
			print "DELETE FROM $v WHERE `itemId` = $cid<br />";
			if (!$db->execute("DELETE FROM $v WHERE `itemId` = $cid") || !$row->delete($cid)) {
				$this->setError($row->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	function setPreference($pref, $val) {
		$val = !!$val;
		switch ($pref) {
			case self::STORE_IGNORE_DUPLICATE:	$this->preferences ^= $this->preferences % 2 ? !$val : $val;	break;
		}
	}
	
function _buildStarQuery() {
		$pageids = JRequest::getVar( 'cid', 0, '', 'array' );
		$pageid = $pageids[0];
;
		$query ="SELECT * FROM #__ttadb_item WHERE id = '".$pageid."'";
		return $query;
}
	function getStarData() {	
$rating = JRequest::getVar('rating');	


		if (empty($this->_Stardata)) { 
		$query = $this->_buildStarQuery();

			$this->_Stardata = $this->_getList($query);
	}
		return $this->_Stardata;
		}
		
		function sendStar() {
		$carryover = $this->_buildStarQuery();
	
		foreach($carryover as $datum){
		if($rating > 5 || $rating < 1) {
		echo"Rating can't be below 1 or more than 5";
	}
	
	elseif(isset($_COOKIE['rated'.$id])) {
		echo"<div class='highlight'>Already Voted!</div>";
	}
	else {

		setcookie("rated".$id, $id, time()+60*60*24*365);

		$total_ratings = $datum->total_ratings;
		$total_rating = $datum->total_rating;
		$current_rating = $datum->rating;

		$new_total_rating = $total_rating + $rating;
		$new_total_ratings = $total_ratings + 1;
		$new_rating = $new_total_rating / $new_total_ratings;
		

		// Lets run the queries. 
	$query1 = "UPDATE #__ttadb_item SET total_rating = '".$new_total_rating."' WHERE id = '".$id."'";
			$this->_db->setQuery($query1, 0, 1);
			$query2 = "UPDATE #__ttadb_item SET rating = '".$new_rating."' WHERE id = '".$id."'";
			$this->_db->setQuery($query2, 0, 1);
			$query3 = "UPDATE #__ttadb_item SET total_ratings = '".$new_total_ratings."' WHERE id = '".$id."'";
			$this->_db->setQuery($query3, 0, 1);
		echo"<div class='highlight'>Vote Recorded!</div>";

	}
		
		}	
		}
		
		
}