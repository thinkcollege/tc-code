<?php
/**
 * Questions table class
 * 
 */
 
// No direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 * Questions Table class
 *
 */
class TableQuestions extends JTable {

	/**
     * Primary Key
     *
     * @var int
     */
    public $id = null;
 
    /**
     * Type ID for question.  To differentiate between Text, Numbers, Multiple Choice and Choose all that apply.
     * 
     * @var int
     */
    public $typeId = null;
 
 	/**
     * Text to display when filling out the survey.
     * 
     * @var string
     */
    public $inputLabel = null;
 
 	/**
     * Text to display when shown in program profile.
     * 
     * @var string
     */
    public $displayLabel = null;
    
    /**
     * Test to display when shown in the comparison table.
     * 
     * @var string
     */
    public $compareLabel = null;
 
    /**
     * The Grouping of a question.  MAY get removed.
     * 
     * @var string
     */
    public $grouping = null;
    
    /**
     * The validation used for a type of question
     * @var int
     */
    public $validation = null;
    
    /**
     * The sorting position of the question.
     * @var int
     */
    public $ordering = null;

    /**
     * Is this question required
     * @var bool
     */
    public $required = null;
    
    /**
     * Whether the question should be included in the comparison chart.
     * @var bool
     */
    public $compare = null;
    
    /**
     * Whether the question is for internal use only and should not be displayed to the public.
     * @var bool
     */
    public $internal = null;
 
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {
        parent::__construct('#___programsdb_questions', 'id', $db);
    }
    
    function check() {
    	if ($this->id < 0) {
    		$this->setError(JText::_('Invalid id.'));
    		return false;
    	}
		$this->_db->setQuery('SELECT 1 FROM `#___programsdb_types` t WHERE t.`ID` = ' . intval($this->typeId));
		if ($this->_db->loadResult() != 1) {
			$this->setError(JText::_('Please choose the type of quesiton.'));
			return false;
		}
		
		if (!trim($this->inputLabel) && !trim($this->displayLabel)) {
			$this->setError('Please enter text for at least the "Shown in Survey."');
			return false;
		}
		
		if ($this->typeId == 3 || $this->typeId == 4 || $this->typeId == 6) {
			$this->validation = V_INT;
		} else if ($this->typeId == 1) {
			$this->validation = V_TEXT;
		}
		if (intval($this->validation) == 0 || intval($this->validation) > V_URL) {
			$this->setError(JText::_('Invalid Validation.'));
			return false;
		}
		$this->required	= $this->required	&& 1;
		$this->compare	= $this->compare	&& 1;
		$this->internal = $this->internal	&& 1;
    	
		return true;
	}
	
	function delete($oid = null) {
		if ($oid == null && $this->{$this->_tbl_key} == 0) {
			return false;
		} else if ($oid == null) {
			$oid = $this->{$this->_tbl_key};
		}
		$oid = intval($oid);
		$tAnswers	= self::getInstance('Answers', 'Table');
		$tChoices	= self::getInstance('Choices', 'Table');
		$db			= $this->getDBO();
		
		$sql = "DELETE FROM `$tAnswers->_tbl` WHERE QuestionID = $oid;\n"
			 . "DELETE FROM `$tChoices->_tbl` WHERE QuestionID = $oid;\n"
			 . "UPDATE `$this->_tbl` t1, `$this->_tbl` t2 SET t1.`ordering` = t1.`ordering` - 1 WHERE t1.`ordering` > t2.`ordering` AND t2.id = $oid";
		$db->setQuery($sql);
		if (!$db->queryBatch()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		return parent::delete($oid);
	}
}