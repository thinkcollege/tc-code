  <?php
/**
 * Progarm Model for ThinkCollege Programs Database
 * 
 */-
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
/**
 * Program Model
 *
 */
class ProgramsDatabaseModelprogram extends JModel {
	
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
	
	/**
	 * An array containing quesion ID's that have errors.
	 * @var array
	 */
	static private $_errorIDs = array();
 	
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
	}
	
	function getId() {
		return $this->_id;
	}
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery() {
		$tAnswers	= $this->getTable('Answers');
		$tChoices	= $this->getTable('Choices');
		$tPrograms	= $this->getTable('Programs');
		
		$sep = ",\n";
		$query = "SELECT p.`id`";
		$questions = self::getInstance('questions', 'ProgramsDatabaseModel')->getData();
		foreach ($questions as $q) {
			$query .= "{$sep}GROUP_CONCAT(";
			if ($q->typeId == 3) {
				$query .= "(SELECT Label FROM `$tChoices->_tbl` WHERE QuestionID = $q->id AND a.`QuestionID` = `QuestionID` AND `Value` = a.`Answer`)";
			} else if ($q->typeId == 4) {
				$query .= "(SELECT GROUP_CONCAT(`Label` SEPARATOR '||') FROM `$tChoices->_tbl` WHERE `QuestionID` = $q->id AND a.`QuestionID` = `QuestionID` AND `Value` & a.`Answer`)";
			} else {
				$query .= "IF(a.`QuestionID` = $q->id, a.`Answer`, '')";
			}
			
			$query .= " SEPARATOR '') AS `q$q->id`";
		}
		$query .= "	FROM `$tPrograms->_tbl` p LEFT JOIN `$tAnswers->_tbl` a ON p.id = a.ProgramID WHERE p.id = {$this->getID()} AND p.`published` = 1 GROUP BY p.id";
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
			if ($this->getDBO()->getErrorMsg() || count($this->_data) == 0) {
				return false;
			} else {
				$this->_data = $this->_data[0];
			}
		}
 
		return $this->_data;
	}
	
	public function getErrorIDs() {
		return self::$_errorIDs;
	}

	
	function store() {
		$ans		= array('id' => 0, 'programId' => 0, 'questionId' => 0, 'answer' => '');
		$data		= JRequest::get('post');
		$questions	= self::getInstance('Questions', 'ProgramsDatabaseModel')->getData();
		$answers	= array();
		$answer		=& $this->getTable('Answers');
	
		$error = false;
		foreach ($questions as $q) {
			$ans['answer']		= isset($data->{'q'  $q->id}) ? $data["q$q->id"] : null;
			$ans['questionId']	= $q->id;
			if (!$answer->bind($ans) || !$answer->check()) {
				JError::raiseWarning(500, $answer->getError());
				$error = true;
				self::$_errorIDs[] = $q->id;
			} else {
				$answers[] = $ans;
			}
		}
		if (!$error) {
			$program	= $this->getTable('Programs');
			$aProgram	= array('id' => 0, 'published' => 0);
			if (!$program->bind($aProgram) || !$program->check() || !$program->store()) {
				JError::raiseWarning(500, $program->getError());
				$error = true;
			} else {
				foreach ($answers as $a) {
					$a['programId'] = $program->id;
					if (!$answer->bind($a) || !$answer->check() || !$answer->store()) {
						JError::raiseWarning(600, $answer->getError());
						self::$_errorIDs[] = $a['questionId'];
						$error = true;
					}
				}
			}
		}
		return !$error;
	}
}