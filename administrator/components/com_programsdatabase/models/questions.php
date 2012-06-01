<?php
/**
 * Questions Model for ThinkCollege Programs Database
 * 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
/**
 * Questions Model
 *
 */
class ProgramsDatabaseModelQuestions extends JModel {
	
	/**
	 * Questions data array
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
		$tChoices	=& $this->getTable('Choices');
		$tQuestions =& $this->getTable();
		$tTypes		=& $this->getTable('Types');
		
		return "SELECT q.`id`, q.`inputLabel`, q.`displayLabel`, q.typeId, q.grouping, q.validation,
						q.ordering, IF(t.`ID` IS NOT NULL, t.`Label`, '') AS `Type`,
						(SELECT GROUP_CONCAT(`Label` ORDER BY `Value` SEPARATOR '||') FROM `$tChoices->_tbl` WHERE QuestionID = q.id) AS `choices`
				  FROM `$tQuestions->_tbl` q LEFT JOIN `$tTypes->_tbl` t ON q.`TypeID` = t.`ID`
				 WHERE t.`ID` > 0 OR t.`ID` IS NULL ORDER BY q.ordering";
	}
 
	/**
	 * Retrieves the Question data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			if ($this->getDBO()->getErrorMsg()) {
				return JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
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
} ?>