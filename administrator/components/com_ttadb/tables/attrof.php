<?php
/**
 * attributes table class
 * 
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * attributes Table class
 *
 */
class TableAttrOf extends JTable {

	/**
	 * When adding a new flag edit the following:
	 * 1.) views/type/tmpl/funcs.js:	details()	[add new checkbox and label]
	 * 2.) views/type/tmpl/funcs.js:				[add new line to body of file in <constant> = window['<constant>'] form]
	 * 3.) views/type/tmpl/default.php:				[add new key/value pair to the strings array]
	 * 4.) views/type/tmpl/default.php:				[add new $this->printJSConstant() call]	
	 * @var unknown_type
	 */
	const FLAG_REQUIRED = 1, FLAG_INTERNAL = 2, FLAG_SUMMARY = 4, FLAG_MULTIPLE = 8,
		FLAG_MERGE = 16, FLAG_NEW = 32, FLAG_TITLE = 64, FLAG_GROUP = 128,
		FLAG_AS_LIST = 256;
	
	/**
     * Primary Key
     *
     * @var int
     */
    public $id = null;
 
    /**
     * Type ID for attribute. This is the type attribute is a part of.  This is not the type the attribute is. 
     * 
     * @var int
     */
    public $typeId = null;
    
    /**
     * Attribute ID for attribute 
     * @var unknown_type
     */
    public $attrId = null;
 
 	/**
     * The sorting position of the attribute in this type.
     * 
     * @var int
     */
    public $ordering = null;

	/**
	 * Sort position of attribute in the type.  The lower the number the earlier in the ORDER BY clause the attribute appears.
	 *
	 * @var int
	 */
	public $sort = null;
    /**
     * Flags for the attribute when assoicated with this type.
     * 
     * @var int
     */
    public $flags = null;
    
   	/**
     * Text to display when filling out the survey.
     * 
     * @var string
     */
    public $inputLabel = null;
 
 	/**
     * Text to display when shown in item profile.
     * 
     * @var string
     */
    public $displayLabel = null;
    
    /**
     * Test to display when shown in the comparison table.
     * 
     * @var string
     */
    public $compareLabel = null;
 
	public $prefix = null;
    
    public $suffix = null;
    
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__ttadb_attr_of', 'id', $db);
	}
	
	static public function getFlags() {
		return array(self::FLAG_REQUIRED, self::FLAG_INTERNAL, self::FLAG_SUMMARY, self::FLAG_MULTIPLE,
			self::FLAG_MERGE, self::FLAG_NEW, self::FLAG_TITLE, self::FLAG_GROUP);
	}
	
	function check() {
		if ($this->id < 0) {
			$this->setError(JText::_('Invalid id.'));
			return false;
		} else if ($this->id > 0) {
			$this->typeId = null;
			$this->attrId = null;
		}
		$tType = $this->getInstance('Type', 'Table');
		if ($this->typeId < 0) {
			$this->setError('Invalid type id.');
			return false;
		} else if ($this->typeId !== null) {
			$this->_db->setQuery("SELECT 1 FROM `{$tType->_tbl}` t WHERE t.`ID` = " . intval($this->typeId));
			if ($this->_db->loadResult() != 1) {
				$this->setError(JText::_('Invalid Type ID when associating the attribute.'));
				return false;
			}
		}
		$tAttr = $this->getInstance('Attr', 'Table');
		if ($this->attrId < 0) {
			$this->setError('Bad attribute ID when associating attribute to type.');
			return false;
		} else if ($this->attrId > 0) {
			$this->_db->setQuery("SELECT 1 FROM `{$tAttr->_tbl}` a WHERE a.`id` = " . intval($this->attrId));
			if ($this->_db->loadResult() != 1) {
				$this->setError(JText::_('Type ID not found in database for attrOf Value.'));
				return false;
			}
		}
		
		if ($this->ordering == 0) {
			$this->setError('Ordering set to 0. for ' . $this->attrId);
			return false;
		}
		if ($this->sort < 0) {
			$this->setError('Invalid sort position for attribute.');
			return false;
		}
		if ($this->inputLabel !== null && !trim($this->inputLabel) && $this->displayLabel !== null && !trim($this->displayLabel)) {
			$this->setError('Please enter text for at least the "Shown in Survey."');
			return false;
		}
		if ($this->flags !== null) {
			if (preg_match('/^[0-9]+$/', $this->flags)) {
				$this->flags = intval($this->flags);
				$flags = 0;
				// Prevent no existant flags from being set.
				foreach ($this->getFlags() as $flag) {
					$flags += $this->flags & $flag;
				}
				$this->flags = $flags;
			} else {
				$this->setError('Invalid value for flags');
				return false;
			}
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
		
		$sql = #"DELETE FROM `$tValue->_tbl` WHERE attrId = $oid;\n"
			 #. "DELETE FROM `$tChoice->_tbl` WHERE attrId = $oid;\n"
			  "UPDATE `$this->_tbl` t1, `$this->_tbl` t2 SET t1.`ordering` = t1.`ordering` - 1 WHERE t1.`ordering` > t2.`ordering` AND t2.`id` = $oid";
		$db->setQuery($sql);
		if (!$db->queryBatch()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		return parent::delete($oid);
	}
}