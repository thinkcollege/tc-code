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
class TableChoices extends JTable {

	/**
     * Primary Key
     *
     * @var int
     */
    public $id = null;
 
    /**
     * Question ID
     * 
     * @var int
     */
    public $questionId = null;
 
 	/**
     * Internal database value for choice.
     * 
     * @var int
     */
    public $value = null;
 
 	/**
     * Display text for choice.
     * 
     * @var int
     */
    public $label = null;
    public $subchoice = null;
    
    static public $_next = null;
    
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct( &$db ) {
        parent::__construct('#___programsdb_choices', 'id', $db);
    }
    
    function bind($from, $ignore = array()) {
    	if (!parent::bind($from, $ignore)) {
    		return false;
    	}
    	
    	$qModel = JModel::getInstance('Question', 'ProgramsDatabaseModel');
    	$qModel->setId($this->questionId);
    	$q		= $qModel->getData();
    	if ($qModel == null || $q == null) {
    		$this->setError('Could not determine question type.  Cannot modify choices.');
    		return false;
    	}
    	
    	if ($q->typeId != 3 && $q->typeId != 4 && $q->typeId != 6) {
    		$this->setError('Choices are not allowed for this question type.');
    		return false;
    	}
    	
    	$db =& $this->getDBO();
    	if ($this->id > 0) {
    		$db->setQuery("SELECT IFNULL(MAX(`Value`), 1) FROM `$this->_tbl` WHERE `id` = " . intval($this->id));
			$this->value = intval($db->loadResult());
    	} else if ($this->id == 0) {
    		if (self::$_next == null) {
    			$db->setQuery("SELECT IFNULL(MAX(`Value`) " . (($q->typeId == 4 || $q->typeId == 6) ? '<<' : '+') . " 1, 1) FROM `$this->_tbl` WHERE `questionId` = " . intval($this->questionId));
				self::$_next = intval($db->loadResult());
    		}
    		$this->value = self::$_next;
    		self::$_next = ($q->typeId == 4 || $q->typeId == 6) ? self::$_next << 1 : self::$_next + 1;
		}
    	if ($db->getErrorMsg()) {
			$this->setError($db->errorMsg());
			return false;
		}
		$i++;
		return true;
    }
    
	function check() {
		If ($this->questionId == 0) {
			$this->setError('This Choice must be associated with a question.');
			return false;
		}
		if ($this->id > 0 && $this->label == '') {
			$this->setError('Every choice must have a label.');
			return false;
		} else if ($this->label == '') {
			$this->label = $this->id = $this->questionId = null;
		}
		return true;
	}
	
    public function delete($oid) {
    	if (!$this->load($oid)) {
    		return false;
    	} else if ($oid <= 0 || $this->value === null) {
    		$this->setError('Failed to load choice.');
    		return false;
    	}
    	$qModel		= JModel::getInstance('Question', 'ProgramsDatabaseModel');
    	$qModel->setId($this->questionId);
    	$question	= $qModel->getData();
    	$tAnswers	= self::getInstance('Answers', 'Table');
		$db			=& $this->getDBO();
		
		if ($question->typeId == 3) {
    		$sql = "UPDATE `$tAnswers->_tbl` SET Answer = `Answer` - 0 WHERE `Answer` = $this->value AND `QuestionID` = $question->id;\n";
		} else if ($question->typeId == 4) {
			$db->setQuery("SELECT `id`, `answer` FROM `$tAnswers->_tbl` WHERE `QuestionID` = $question->id");
			$answers = $db->loadObjectList();
			$sql	 = '';
			foreach ($answers as $ans) {
				if ($ans->answer < $this->value) {
					continue;
				}
				$j = $ans->answer &~ $this->value;
				for ($k = $this->value; $k < $i; $k *= 2) {
					$j += $ans->answer & ($k * 2) ? -$k : 0;
				}
				$sql .= "UPDATE `$tAnswers->_tbl` a SET `answer` = $j WHERE `id` = $ans->id;\n"; 
			}
		}
    	$sql .= "UPDATE `$this->_tbl` SET Value = Value " . ($question->typeId == 3 ? '-' : '>>') . '1'
    		 . " WHERE Value > $this->value AND `QuestionID` = $question->id;\n";
    	$db->setQuery($sql);
    	if (!$db->queryBatch()) {
    		$this->setError($db->getErrorMsg());
    		return false;
    	}
    	return parent::delete($oid);
    }
}