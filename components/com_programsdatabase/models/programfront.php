<?php
/**
 * Progarm Model for ThinkCollege Programs Database
 * 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
/**
 * Program Model
 *
 */
class ProgramsDatabaseModelprogramfront extends JModel {
	
	/**
	 * Program data array
	 *
	 * @var array
	 */
	private $_data;
	
	/**
	 * Program id int
	 * 
	 * @var int
	 */
	private $_id;
	
	private $_answerID = array();
	
	function __construct() {
		parent::__construct();
		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId($array[0]);
	}

	/**
	 * Method to set the program identifier
	 *
	 * @access	public
	 * @param	int program identifier
	 * @return	void
	 */
	function setId($id) {
		// Set id and wipe data
		$this->_id	 = intval($id);
		$this->_data = null;
		$this->_answerID = array();
	}
	
	function getId() {
		return $this->_id;
	}
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery() {
		$tChoices	= $this->getTable('Choices');
		$tAnswers	= $this->getTable('Answers');
		$tPrograms	= $this->getTable('Programs');
		
		$sep = ",\n";
		$query = "SELECT p.`id`, p.`published`";
		$questions = self::getInstance('questions', 'ProgramsDatabaseModel')->getData();
		foreach ($questions as $q) {
			$query .= "{$sep}GROUP_CONCAT(";
			if ($q->typeId == 3) {
				$query .= "(SELECT Label FROM `$tChoices->_tbl` WHERE QuestionID = $q->id AND a.`QuestionID` = `QuestionID` AND `Value` = a.`Answer`)";
			} else if ($q->typeId == 4) {
				$query .= "(SELECT GROUP_CONCAT(Label SEPARATOR '||') FROM `$tChoices->_tbl` WHERE `QuestionID` = $q->id AND a.`QuestionID` = `QuestionID` AND `Value` & a.`Answer`)";
			} else {
				$query .= "IF(a.`QuestionID` = $q->id, a.`Answer`, '')";
			}
			
			$query .= " SEPARATOR '') AS `q$q->id`";
		}
		$query .= "	FROM `$tPrograms->_tbl` p LEFT JOIN `$tAnswers->_tbl` a ON p.id = a.ProgramID WHERE p.id = {$this->getID()} AND p.published != 2 GROUP BY p.id";
		#print "<!-- query:" . str_replace('#_', 'jos', $query) . " -->";
		return $query;
	}
 
	/**
	 * Retrieves the program data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		// Lets load the data if it doesn't already exist
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query);
			$this->_data = $this->_data[0];
			if ($this->getDBO()->getErrorMsg()) {
				return JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
			}
		}
 
		return $this->_data;
	}

	
	function getAnswerIDs() {
		if (empty($this->_answerID)) {
			$this->_answerID = array();
			$tAnswers	= $this->getTable('Answers');
			$query		= "SELECT id, questionID FROM $tAnswers->_tbl WHERE ProgramID = {$this->getId()} ORDER BY QuestionID";

			$ids = $this->_getList($query);
			if ($this->getDBO()->getErrorMsg()) {
				JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
				return false;
			}
			foreach ($ids as $row) {
				$this->_answerID[$row->questionID] = $row->id;
			}
		}
 		return $this->_answerID;
	}
		function getRankingIDs() {
			$this->_RankingID = array();
			$tQuestions	= $this->getTable('Questions');
			$query		= "SELECT id FROM $tQuestions->_tbl WHERE typeId = 6";

			$ids = $this->_getList($query);
		
			if ($this->getDBO()->getErrorMsg()) {
				JError::raiseWarning(500, $this->getDBO()->getErrorMsg());
				return false;
			}
			foreach ($ids as $row) {
				$this->_RankingID[$row->id] = $row->id;
			}
 		return $this->_RankingID;
	}
	
	function store() {
		$tProgram 	= $this->getTable('Programs');
		$id			= JRequest::getVar('cid', 0, '', 'int');
		$published	= jRequest::getVar('published', 0, '', 'int');
		
	
		if (!$tProgram->bind(array('id' => $id, 'published' => $published, 'lastUpdated' => date('Y-m-d H:i:s'))) || !$tProgram->check() || !$tProgram->store()) {
			JError::raiseWarning(500, JText::_('Failed to save program.'));
			return false;
		} else if ($this->getId() == 0) {
					session_start();
			$_SESSION['cidtemp']=$tProgram->id;
			$this->setId($tProgram->id);
		}
		
		// Due to the pagination in the Questions model these need to be set before it's created.
		JRequest::setVar('limit', PHP_INT_MAX);
		JRequest::setVar('limitstart', 0);
 		$questions	= self::getInstance('Questions', 'ProgramsDatabaseModel')->getData();
		$answerIDs	= $this->getAnswerIDs();
		
			if (count($_FILES) > 0) {
			$post	= JRequest::get('post');
			$files	= array();
			foreach (JRequest::get('files') as $key => $file) { 
				$files[$key] = array();
				if (!is_array($file['name'])) { 
					$files[$key][] = $file;
					continue;
				}
				for ($i = 0; $i < count($file['name']); $i++) { 
					$tmp = array();
					foreach ($file as $attr => $val) { 
						$tmp[$attr] = $val[$i];
						
					}
					if (isset($post[$key][$i])) { 
						
						$tmp = array_merge($post[$key][$i], $tmp);
						
						unset($post[$key][$i]);
				}
		
				$files[$key][$i] = $tmp;
		
		
				}
			}
			
			$answers	= array_merge_recursive($post, $files);
			
			$_FILES = array();
		
		} else {
			
		$answers	= JRequest::get('post'); }
		
	
		$ans		= array('id' => 0, 'programId' => $tProgram->id, 'questionId' => 0);
		if ($answerIDs === false) {
			JError::raiseWarning(500, JText::_('failed to get Answer IDs.'));
			return false;
		}	$checkbounce = 0;
		foreach ($questions as $q) { 
			$row				=& $this->getTable('Answers');
			$ans['id']			= isset($answerIDs[$q->id]) ? intval($answerIDs[$q->id]) : 0;
			$ans['answer']		= isset($answers["q$q->id"]) ? $answers["q$q->id"] : null;
			$ans['questionId']	= $q->id;
			
			if (!$row->bind($ans) || !$row->check() || !$row->store()) {
				JError::raiseWarning(500, $row->getError());
					
				$checkbounce = 1;
		
				
			}
				if (($ans['id'] > 0) && ($answers["q$q->id"]["delete"] == 1) && ($q->typeId == 7)) {
				
				//	$row->_delete = true;
					$row->delete($ans['id']); }
		}
		if ($checkbounce == 1) return false;
		return true;
	}
	
	function delete() {
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$row =& $this->getTable('Programs');

		foreach ($cids as $cid) {
			if (!$row->delete($cid)) {
				$this->setError($row->getErrorMsg());
				return false;
			}
		}
		return true;
	}
	
}