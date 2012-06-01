<?php
/**
 * attributes table class
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * attributes Table class
 */
class TableAttr extends JTable {

	/**
     * Primary Key
     *
     * @var int
     */
    public $id = null;
 
    /**
     * Type ID for attribute.  To differentiate between Text, Numbers, Multiple Choice and Choose all that apply.
     * 
     * @var int
     */
    public $typeId = null;
    
    	/**
     * Test Shown in the Administrator interace for the attribute.
     * 
     * @var int
     */
    public $adminLabel = null;
 
    /**
     * The validation used for a type of attribute
     * @var int
     */
    public $validation = null;
    
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) {
        parent::__construct('#__ttadb_attr', 'id', $db);
    }
    
    function check() {
    	if ($this->id < 0) {
    		$this->setError(JText::_('Invalid id.'));
    		return false;
    	}
    	$tType = $this->getInstance('Type', 'Table');
		if ($this->typeId !== null) {
    		$this->_db->setQuery("SELECT 1 FROM `{$tType->_tbl}` t WHERE t.`ID` = " . intval($this->typeId));
			if ($this->_db->loadResult() != 1) {
				$this->setError(JText::_('Please choose the type of attribute.'));
				return false;
			}
			if ($this->typeId == 3 || $this->typeId == 4) {
				$this->validation = V_INT;
			} else if ($this->typeId == 1) {
				$this->validation = V_TEXT;
			}
		}
		if ($this->validation !== null) {
			if (intval($this->validation) == 0 || intval($this->validation) > V_DATE) {
				$this->setError(JText::_('Invalid Validation.'));
				return false;
			}
		}
		
		if (empty($this->adminLabel)) {
			$this->setError(JText::_('Please privde an Admin Label.'));
			return false;
		}
    	
		return true;
	}
	
	function delete($oid = null) {
		if ($oid == null && $this->{$this->_tbl_key} == 0) {
			return false;
		} else if ($oid == null) {
			$oid = $this->{$this->_tbl_key};
		}
		$oid = intval($oid);
		$tValue	 = self::getInstance('Value', 'Table');
		$db		 = $this->getDBO();
		
		$sql = "DELETE FROM `$tValue->_tbl` WHERE attrId = $oid;\n";
			 #. "UPDATE `$this->_tbl` t1, `$this->_tbl` t2 SET t1.`ordering` = t1.`ordering` - 1 WHERE t1.`ordering` > t2.`ordering` AND t2.id = $oid";
		$db->setQuery($sql);
		if (!$db->queryBatch()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		return parent::delete($oid);
	}
}