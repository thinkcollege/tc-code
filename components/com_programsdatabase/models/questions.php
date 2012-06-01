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
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery() {
		$tChoices	=& $this->getTable('Choices');
		$tQuestions =& $this->getTable();
		$tTypes		=& $this->getTable('Types');
		
		return "SELECT q.`id`, q.`inputLabel`, q.`displayLabel`, q.`compareLabel`, q.`typeId`,
						q.`grouping`, q.validation, q.`ordering`, IF(t.`ID` IS NOT NULL, t.`Label`, '') AS `Type`,
						(SELECT GROUP_CONCAT(`Label` ORDER BY `Value` SEPARATOR '||') FROM `$tChoices->_tbl` WHERE QuestionID = q.id ORDER BY Value) AS `choices`,
						q.required, q.compare
				  FROM `$tQuestions->_tbl` q LEFT JOIN `$tTypes->_tbl` t ON q.`TypeID` = t.`ID`
				 WHERE (t.`ID` > 0 OR t.`ID` IS NULL) AND q.`internal` = 0 ORDER BY q.ordering";
	}
 
	/**
	 * Retrieves the Question data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query);
			if ($this->getDBO()->getErrorMsg()) {
				return JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
			}
		}
 		return $this->_data;
	}

} ?>