<?php
/**
 * Types table class
 * 
 */
 
// No direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 * Tupes Table class
 *
 */
class TableTypes extends JTable {

	/**
     * Primary Key
     *
     * @var int
     */
    public $id = null;
 
    /**
     * Label for question type.
     * 
     * @var int
     */
    public $label = null;
 
 	 /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#___programsdb_types', 'id', $db);
    }
    
    function check() {
    	return false;
	}
}