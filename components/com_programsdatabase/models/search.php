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
class ProgramsDatabaseModelSearch extends JModel {
	
	/**
	 * Search results data array
	 *
	 * @var array
	 */
	private $_results;
	
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
		$tPrograms	= $this->getTable('Programs');
		$tAnswers	= $this->getTable('Answers');
		$state		= preg_replace('/[^A-Z]+/', '', strtoupper(JRequest::getVar('state', '')));
		$twoYear	= intval(JRequest::getVar('2year',		0));
		$fourYear	= intval(JRequest::getVar('4year',		0));
		$tech		= intval(JRequest::getVar('techSchool',	0));
		$dual		= intval(JRequest::getVar('dual',		0));
		$adult		= intval(JRequest::getVar('adult',		0));
		$res		= intval(JRequest::getVar('res',		0));
		$where		= '';
		$join 		= '';
		$j			= 1;
		
		if ($state) {
			$where	.= "AND a$j.`QuestionID` = 13 AND a$j.`Answer` = " . $this->getDBO()->quote($state) . " ";
			$join	.= "LEFT JOIN `$tAnswers->_tbl` a$j ON a$j.ProgramID = p.id ";
			$j++;
		}
		if ($twoYear) {
			$where	.= "AND a$j.`QuestionID` = 18 AND a$j.`Answer` IN (3, 4) ";
			$join	.= "LEFT JOIN `$tAnswers->_tbl` a$j ON a$j.ProgramID = p.id ";
			$j++;
		}
		if ($fourYear) {
			$where .= "AND a$j.`QuestionID` = 18 AND a$j.`Answer` IN (1, 2) ";
			$join	.= "LEFT JOIN `$tAnswers->_tbl` a$j ON a$j.ProgramID = p.id ";
			$j++;
		}
		if ($tech) {
			$where .= "AND a$j.`QuestionID` = 18 AND a$j.`Answer` = 5 ";
			$join	.= "LEFT JOIN `$tAnswers->_tbl` a$j ON a$j.ProgramID = p.id ";
			$j++;
		}
		if ($dual) {
			$where .= "AND a$j.`QuestionID` = 22 AND a$j.`Answer` IN (1, 3) ";
			$join	.= "LEFT JOIN `$tAnswers->_tbl` a$j ON a$j.ProgramID = p.id ";
			$j++;
		}
		if ($adult) {
			$where .= "AND a$j.`QuestionID` = 22 AND a$j.`Answer` IN (2, 3) ";
			$join	.= "LEFT JOIN `$tAnswers->_tbl` a$j ON a$j.ProgramID = p.id ";
			$j++;
		}
		if ($res) {
			$where .= "AND a$j.`QuestionID` = 34 AND a$j.`Answer` = 1";
			$join	.= "LEFT JOIN `$tAnswers->_tbl` a$j ON a$j.ProgramID = p.id ";
		}
		return "SELECT MAX(p.`id`) AS `id`, GROUP_CONCAT(IF(a.`QuestionID` = 38, a.`Answer`, '') SEPARATOR '') AS `org`,
					   GROUP_CONCAT(IF(a.`QuestionID` = 4, a.`Answer`, '') SEPARATOR '') AS `org2`,
					   GROUP_CONCAT(IF(a.`QuestionID` = 9, a.`Answer` , '') SEPARATOR '') AS `program`,
					   GROUP_CONCAT(IF(a.`QuestionID` = 12, a.`Answer` , '') SEPARATOR '') AS `city`,
					   GROUP_CONCAT(IF(a.`QuestionID` = 13, a.`Answer` , '') SEPARATOR '') AS `state` 
				  FROM `$tPrograms->_tbl` p LEFT JOIN `$tAnswers->_tbl` a ON p.id = a.`ProgramID` $join
			 	 WHERE p.`Published` = 1 $where
			  GROUP BY p.`id`
			  ORDER BY `State`, `City`, `org`, `org2`, `Program`";
	}
 
	/**
	 * Retrieves the programs data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_results)) {
			$query = $this->_buildQuery();
			$this->_results = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			if ($this->getDBO()->getErrorMsg()) {
				JError::raiseWarning(600, 'There was an error querying the database.');
				$this->_results = null;
			} else if (count($this->_results) == 0) {
				JError::raiseWarning(500, 'No results were found.  Search again.');
				$this->_results = null;
			}
		}
 
		return $this->_results;
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