<?php
/**
 * Progarms Model for ThinkCollege Programs Database
 * 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
/**
 * Programs Model
 *
 */
class ProgramsDatabaseModelPrograms extends JModel {
	
	/**
	 * Programs data array
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
	function _buildQuery() {
		
			
		$filter_order = JRequest::getCmd('filter_order');
	$filter_order_Dir = JRequest::getCmd('filter_order_Dir');
 

		$tAnswers	= $this->getTable('Answers');
		$tPrograms	= $this->getTable('Programs');
	
			if (!$filter_order_Dir) {
			$filter_order_Dir = 'ASC';
		}	if (!$filter_order) {
			$filter_order = 'published';
		}
		$order = ' ORDER BY '. $filter_order .' '. $filter_order_Dir . ', `State`, `City`, `org`, `org2`';
		return "SELECT p.`id`,
					   GROUP_CONCAT(IF(a.`QuestionID` = 38 AND a.`Answer` <> '', a.`Answer`, '') SEPARATOR '') AS `org`,
					   GROUP_CONCAT(IF(a.`QuestionID` = 4 AND a.`Answer` <> '', a.`Answer`, '') SEPARATOR '') AS `org2`,
					   GROUP_CONCAT(IF(a.`QuestionID` = 9, a.`Answer` , '') SEPARATOR '') AS `Program`,
					   GROUP_CONCAT(IF(a.`QuestionID` = 12, a.`Answer` , '') SEPARATOR '') AS `City`,
					   GROUP_CONCAT(IF(a.`QuestionID` = 13, a.`Answer` , '') SEPARATOR '') AS `State`, p.`published` AS `published`, p.`lastUpdated` AS `Updated`
				  FROM `$tPrograms->_tbl` p LEFT JOIN `$tAnswers->_tbl` a ON p.id = a.`ProgramID`
				 WHERE a.`QuestionID` IN (4, 9, 12, 13, 38)
			  GROUP BY p.`id`
			 $order";
			
	}
 
	/**
	 * Retrieves the programs data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			if ($this->getDBO()->getErrorMsg()) {
				JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
				return false;
			}
		}
		return $this->_data;
	}
	
	function getTotal() {
		// Load the content if it doesn't already exist
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);    
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
}