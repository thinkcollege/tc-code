<?php
/**
 * Types Model for CCK Component
 * 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );
 
/**
 * attributes Model
 *
 */
class TtaDbModelTypes extends JModel {
	
	/**
	 * attributes data array
	 *
	 * @var array
	 */
	private $_data;
	
	private $_total = null;
	
	private $_pagination = null;
	
	function __construct() {
		parent::__construct();
 
		global $mainframe, $option;
 
		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery($derivedOnly = true) {
		$t		=& $this->getTable('Type')->_tbl;
		
		if ($derivedOnly) {
			$where = 't.`id` > 99';
			$order = 't.label';
		} else {
			$where = '1';
			$order = 't.`id`';
		}
		return "SELECT t.`id`, t.`label`, t.`sort` FROM `$t` t WHERE $where ORDER BY $order";
	}
 
	/**
	 * Retrieves the attribute data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_data)) {
			if (JRequest::getVar('task') == 'listTypes') {
				$data = $this->_getList($this->_buildQuery(),
					$this->getState('limitstart'),
					$this->getState('limit'));
			} else {
				$data = $this->_getList($this->_buildQuery());
			}
			if ($this->getDBO()->getErrorMsg()) {
				return JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
			}
			$this->_total = count($data);
			$this->_data = $data;
		}
 		return $this->_data;
	}
	
	function getAllData() {
		if (empty($this->_data)) {
			$this->_data = $this->_getList($this->_buildQuery(false));
			
			#$this->_total = count($this->_data);
			if ($this->getDBO()->getErrorMsg()) {
				return JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
			}
		}
 		return $this->_data;
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
} ?>