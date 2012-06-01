<?php
/**
 * Programs table class
 * 
 */
 
// No direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 * Programs Table class
 *
 */
class TablePrograms extends JTable {
	
	public $id = null;
	
	public $published = null;
	public $lastUpdated =  null;
	
	public function __construct(&$db) {
		parent::__construct('#___programsdb_programs', 'id', $db);
	}
	
	public function check() {
		if (($this->id . '') != (intval($this->id) . '')) {
			$this->setError('Invalid ID');
			return false;
		}
		$this->published = $this->published && 1;
		$this->lastUpdated = date('Y-m-d H:i:s');
		return true;
	}
}