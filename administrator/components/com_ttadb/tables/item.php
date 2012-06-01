<?php
/**
 * Item table class
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Item Table class
 */
class TableItem extends JTable {
	
	public $id = null;
	
	public $typeId = null;
	
	public $published = null;
	
	private $_oneStar = null;
	private $_twoStar = null;
	private $_threeStar = null;
	private $_fourStar = null;
	private $_fiveStar = null;
	
	public $rating = null;
	
	public function __construct(&$db) {
		parent::__construct('#__ttadb_item', 'id', $db);
	}
	
	public function check() {
		if ($this->id === null || ($this->id . '') != (intval($this->id) . '')) {
			$this->setError('Invalid ID');
			return false;
		} else {
			$this->id = intval($this->id);
		}
		if ($this->typeId !== null) {
			if ($this->typeId <= 0) {
				$this->setError('Invalid Type');
				return false;
			} else {
				$this->typeId = intval($this->typeId);
			}
		}
		$this->published = $this->published !== null ? $this->published : null;
		if ($this->rating != null && ($this->rating < 1 || $this->rating > 5)) {
			$this->setError('Invalid item rating.');
			return false;
		}
		return true;
	}
	
	public function store() {
		$rating = $this->rating;
		$this->rating = null;
		if (!parent::store()) {
			return false;
		}
		if ($rating === null) {
			return true;
		}
		switch (intval($rating)) {
			case 1:	$col = 'oneStar';	break;
			case 2:	$col = 'twoStar';	break;
			case 3:	$col = 'threeStar';	break;
			case 4: $col = 'fourStar';	break;
			case 5: $col = 'fiveStar';	break;
		}
		
		$db =& $this->getDBO();
		$db->setQuery('UPDATE ' . $db->nameQuote($this->_tbl) . " SET `$col` = `$col` + 1, `rating` = FORMAT((`oneStar` + (`twoStar` * 2) + (`threeStar` * 3) + (`fourStar` * 4) + (`fiveStar` * 5))/(`oneStar` + `twoStar` + `threeStar` + `fourStar` + `fiveStar`), 2) WHERE `$this->_key` = " . $this->{$this->_key});
		$ret = $db->query();
		return $ret !== false ? true : false;
	}
}