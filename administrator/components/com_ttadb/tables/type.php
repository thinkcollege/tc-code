<?php
/**
 * Types table class
 * 
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Type Table class
 *
 */
class TableType extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	public $id = null;
 
	/**
	 * Label for attr type.
	 * 
	 * @var int
	 */
	public $label = null;
 
	/**
	 * Whether or not to show the type in the Admin menu.  If non-zero then the type will be shown in the menu.
	 * 
	 * @var boolean
	 */
	public $sort = null;
	
 	 /**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__ttadb_type', 'id', $db);
	}
	
	function check() {
		if (trim($this->label) == '') {
			$this->setError('Please enter a name for the type.');
			return false;
		}
		$this->sort = $this->sort !== null ? intval($this->sort) : null;
		
		return true;
	}
}