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
class ProgramsDatabaseModelQuestion extends JModel {
	
	/**
	 * Question data array
	 *
	 * @var array
	 */
	private $_data;
	
	/**
	 * Question Types array
	 * 
	 * @var array
	 */
	private $_types;
	
	/**
	 * Question Choices array
	 * 
	 * @var array
	 */
	private $_choices;
	
	/**
	 * Question id int
	 * 
	 * @var int
	 */
	private $_id = null;
	
	/**
	 * Max question number, used for sorting purposes.
	 * @var int
	 */
	private $_max = null;
	
	private $_groupings = null;
 	
	function __construct() {
		parent::__construct();
		$array = JRequest::getVar('cid',  null, '', 'array');
		$this->setId($array[0]);
	}

	/**
	 * Method to set the Question identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id) {
		// Set id and wipe data
		if ($id != $this->_id) {
			$this->_id	 = abs(intval($id));
			$this->_data = null;
			$this->_choices = null;
		}
	}
	
	/**
	 * Get the Question ID.
	 * 
	 * @return int
	 */
	function getId() {
		return $this->_id;
	}
	
	/**
	 * Returns the query to get all the question data.
	 * 
	 * @access protected
	 * @return string The query to be used to retrieve the rows from the database
	 */
	protected function _buildQuery() {
		$tQuestions =& $this->getTable('Questions');
		
		return "SELECT q.`id`, q.`typeId`, q.`displayLabel`, q.`inputLabel`, q.`compareLabel`, q.`grouping`, q.`validation`, q.`ordering`, q.`required`, q.`compare`, q.`internal`
				  FROM `$tQuestions->_tbl` q WHERE q.id = {$this->getID()}";
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
			$this->_data = $this->_data[0];
			$this->_data->choices = $this->getChoices();
		}
 		return $this->_data;
	}

	/**
	 * Gets the Types of questions.
	 * 
	 * @return array	Array of Objects.
	 */
	function getTypes() {
		if ($this->_types == null) {
			$tTypes =& $this->getTable('Types');
			$query = "SELECT id, label FROM `$tTypes->_tbl` ORDER BY id";
			$this->_types = $this->_getList($query);
		}
		return $this->_types;
	}
	
	/**
	 * Returns an array of objects with each object corresponding to a choice.
	 * 
	 * @return array	Array of Objects
	 */
	function getChoices() {
		if ($this->_choices == null) {
			$tChoices =& $this->getTable('Choices');
			$query = "SELECT id, value, label FROM `$tChoices->_tbl` WHERE `QuestionID` = {$this->getID()} ORDER BY Value";
			$this->_choices = $this->_getList($query);
		}
		return $this->_choices;
	}
	
	/**
	 * Queries the database to find the highest question number and returns that number.
	 * 
	 * @return int
	 */
	function getMaxQuestionNumber() {
		if (!$this->_max) {
			$tQuestions =& $this->getTable('Questions');
			$query = "SELECT MAX(ordering) AS `Num` FROM `$tQuestions->_tbl`";
			$this->_max = $this->_getList($query);
			$this->_max = intval($this->_max[0]->Num);
		}
		
		return $this->_max;
	}
	
	
	function getGroupings() {
		if (!$this->_grouping) {
			$tQuestions =& $this->getTable('Questions');
			$query = "SELECT grouping, MIN(ordering) AS `ordering` FROM `$tQuestions->_tbl` WHERE `grouping` <> '' GROUP BY `grouping` ORDER BY `ordering`";
			$this->_grouping = $this->_getList($query);
		}
		
		return $this->_grouping;
	}
	
	/**
	 * Stores the question data stored in teh $_POST array.
	 * 
	 * @return bool
	 */
	function store() {
		$data = JRequest::get('post');
		// Bind the form fields to the hello table
		if (!isset($data) || !is_array($data)) {
			$this->setError('No Question to save!');
			return false;
		}
		
		$row =& $this->getTable('Questions');
		$data['id'] = $data['cid'];
		if (!$row->bind($data) || !$row->check() || !$row->store()) {
			JError::raiseWarning(500, $row->getError());
			return false;
		}
		if ($this->getID() == 0) {
			$this->_max++;
		}
		
		if (isset($data['newOrdering']) && intval($data['newOrdering']) > 0 && isset($data['ordering']) && intval($data['ordering']) > 0) {
			for ($old = abs($data['ordering']), $new = abs($data['newOrdering']); $old != $new; $old += ($old < $new ? 1 : -1)) {
				$row->move($old < $new ? 1 : -1);
			}
		}
		if (!isset($data['choices']) || $row->typeId < 3) {
			$data['choices'] = array();
		} else if (!is_array($data['choices'])) {
			$data['choices'] = array($data['choices']);
		}
		foreach ($data['choices'] as $choice) { $choice['label'] = trim($choice['label']);
			if ($choice['id'] == 0 && $choice['label'] == '') {
				continue;
			}
			$choice['questionId']	= $row->id;
			$tChoice				= $this->getTable('Choices');
			if (isset($choice['delete'])) {
				if (!$tChoice->delete($choice['id'])) {
					JError::raiseWarning(500, $tChoice->getError());
					return false;
				}
			} else if (!$tChoice->bind($choice) || !$tChoice->check() || !$tChoice->store()) {
				JError::raiseWarning(500, $tChoice->getError());
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Deletes the questions corresponding to the id in the $_POST['cid'] array.
	 * 
	 * @return bool
	 */
	function delete() {
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$row =& $this->getTable('Questions');

		foreach ($cids as $cid) {
			if (!$row->delete($cid)) {
				JError::raiseWarning(500, $row->getError());
				return false;
			}
		}
		return true;
	}
}