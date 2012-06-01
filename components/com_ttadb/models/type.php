<?php
/**
 * Type Model for CCK Component
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

/**
 * attributes Model
 */
class TtaDbModelType extends JModel {
	
	const SORT_INSERT = 0, SORT_ASC = 1, SORT_DESC = 2;
	
	/**
	 * Type data array
	 *
	 * @var array
	 */
	private $_data;
	
	/**
	 * Type Attributes
	 * 
	 * @var array
	 */
	private $_attrs;
	
	/**
	 * Attribute id int
	 * 
	 * @var int
	 */
	private $_id = null;
	
	function __construct() {
		parent::__construct();
		if (in_array(JRequest::getVar('task'), array('addType', 'editType'))) {
			$array = JRequest::getVar('cid',  null, '', 'array');
			$this->setId($array[0]);
		}
	}

	/**
	 * Method to set the attribute identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id) {
		// Set id and wipe data
		if ($id != $this->_id) {
			$this->_id	 	= abs(intval($id));
			$this->_data 	= $this->getData();
		}
	}
	
	/**
	 * Get the attribute ID.
	 * 
	 * @return int
	 */
	function getId() {
		return $this->_id;
	}
	
	/**
	 * Returns the query to get all the attribute data.
	 * 
	 * @access protected
	 * @return string The query to be used to retrieve the rows from the database
	 */
	protected function _buildQuery() {
		$tType =& $this->getTable();
		return "SELECT t.`id`, t.`label`, t.`sort` FROM `$tType->_tbl` t WHERE t.`id` = {$this->getID()}";
	}
 
	/**
	 * Retrieves the attribute data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_data) && $this->getId() > 0) {
			$this->_data = $this->_getList($this->_buildQuery());
			$this->_data = $this->_data[0];
			$this->_data->showInMenu = $this->_data->sortAlpha && true; 
			if ($this->_data->id > 0) {
				$this->_data->attrs 	 = $this->getAttrs($this->getID());
			}
		} else if (JRequest::getWord('task') == 'addType') {
			$this->_data = new stdClass;
			$this->_data->id		= 0;
			$this->_data->label		= '';
			$this->_data->sortAlpha	= false;
			$this->_data->attrs		= array();
		}
		
		return $this->_data;
	}

	
	/**
	 * Returns an array of objects with each object corresponding to a choice.
	 * 
	 * @return array	Array of Objects
	 */
	function getAttrs($typeId) {
		if (!isset($this->_attrs[$typeId])) {
			$typeId = intval($typeId);
			if ($typeId < 0) {
				return array();
			}
			$this->_attrs[$typeId] =& self::getInstance('Attrs', 'TtaDbModel')->getAttrsOf($typeId, false, false);
		}
		return $this->_attrs[$typeId];
	}
	
	/**
	 * Stores the attribute data stored in teh $_POST array.
	 * 
	 * @return bool
	 */
	function store() {
		$data = JRequest::get('post');
		// Bind the form fields to the hello table
		if (!isset($data) || !is_array($data)) {
			$this->setError('No type to save!');
			return false;
		}
		
		$row 		=& $this->getTable();
		$data['id'] = $data['cid'];
		if (!$row->bind($data) || !$row->check() || !$row->store()) {
			JError::raiseWarning(500, $row->getError());
			return false;
		}
		if (isset($data['nojs'])) {
			return true;
		}
		if (isset($data['cid']) && $data['cid'] == 0) {
			$tAttr		= $this->getTable('Attr');
			if (!$tAttr->bind(array('typeId' => $row->id, 'adminLabel' => $row->label)) || !$tAttr->check() || !$tAttr->store()) {
				JError::raiseWarning(500, $tAttr->getError());
				return false;
			}
		}
		$tAttrOf	= $this->getTable('AttrOf');
		if (!isset($data['attrs'])) {
			$data['attrs'] = array();
		} else if (!is_array($data['attrs'])) {
			$data['attrs'] = json_decode($data['attrs']);
		}
		$ids = array();
		foreach ($data['attrs'] as $attr) {
			if (($attr->attrOfId > 0 && $attr->attrOfTypeId != $row->id) || $attr->attrId == 0) {
				continue;
			}
			if ($attr->attrOfId > 0) {
				$tAttrOf->load($attr->attrOfId);
				if ($tAttrOf->typeId != $attr->attrOfTypeId) {
					JError::raiseWarning(500, 'The type is different from the type stored in the database for ' . $attr->adminLabel . '.');	
					return false;
				}
			}
			$attrOf	= array('id' => $data['cid'] <= 0 ? 0 : $attr->attrOfId, 'attrId' => $attr->attrId,
				'typeId' => $row->id, 'ordering' => $attr->ordering, 'flags' => $attr->flags,
				'inputLabel' => $attr->inputLabel, 'displayLabel' => $attr->displayLabel, 'sort' => $attr->sort,
				'compareLabel' => $attr->compareLabel, 'prefix' => $attr->prefix, 'suffix' => $attr->suffix);
			$tAttrOf->reset();
			if (!$tAttrOf->bind($attrOf) || !$tAttrOf->check() || !$tAttrOf->store()) {
				JError::raiseWarning(500, $tAttrOf->getError());
				return false;
			}
			$ids[] = $tAttrOf->id;
		}
		$this->getDBO()->execute("DELETE FROM `$tAttrOf->_tbl` WHERE typeId = $row->id AND id NOT IN (" . implode(',', $ids) . ')');
		return true;
	}
	
	/**
	 * Deletes the attributes corresponding to the id in the $_POST['cid'] array.
	 * 
	 * @return bool
	 */
	function delete() {
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$db	=& $this->getDBO();
		$t	=& $this->getTable('Type');
		$a	= $db->nameQuote($this->getTable('Attr')->_tbl);
		$ao	= $db->nameQuote($this->getTable('AttrOf')->_tbl);
		$v	= $db->nameQuote($this->getTable('Value')->_tbl);
		$i	= $db->nameQuote($this->getTable('Item')->_tbl);
		foreach ($cids as $cid) {
			$cid = intval($cid);
			$sql = "DELETE a, ao, i, v FROM $a AS a LEFT JOIN $ao AS `ao` LEFT JOIN $i AS `i` LEFT JOIN $v AS `v` ON a.typeId = `i`.typeId ON `i`.id = v.itemId ON i.typeId = ao.typeId WHERE a.`typeId` = $cid AND IFNULL(i.id, 1)";
			if (!$db->execute($sql) && !$t->delete($cid)) {
				JError::raiseWarning(500, $row->getError());
				return false;
			}
			$t->delete($cid);
		}
		
		return true;
	}

	function getIdByName($name) {
		$t		= $this->getDBO()->nameQuote($this->getTable('Type')->_tbl);
		$name	= $this->getDBO()->quote($name); 
		$id		= $this->_getList("SELECT `id` FROM $t WHERE `label` = $name");
		return isset($id[0]) ? $id[0]->id : 0;
	}
}