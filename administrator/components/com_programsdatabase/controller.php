<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
/**
 * Programs Database Component Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ProgramsDatabaseController extends JController {
    
	function __construct() {
		parent::__construct();
		
	//	$this->registerTask('addProgram', 'editProgram');
		$this->registerTask('addQuestion', 'editQuestion');
	}
	
	function listPrograms() {
		JRequest::setVar('view', 'programs');
		parent::display();
	}
	
	function listQuestions() {
    	JRequest::setVar('view', 'questions');
    	parent::display();
    }
	function addProgram() {
		JRequest::setVar('hidemainmenu', 1);
		unset($_SESSION['cidtemp']);
		// Due to the pagination in the Questions model these need to be set before it's created.
		JRequest::setVar('limit', PHP_INT_MAX);
		JRequest::setVar('limitstart', 0);
 		$view =& $this->getView('Program', 'html');
		$view->setModel($this->getModel('Program'), true);
 		$view->setModel($this->getModel('Questions'));
 		$view->display();
	}
    
	function editProgram() {
		JRequest::setVar('hidemainmenu', 1);
		
		// Due to the pagination in the Questions model these need to be set before it's created.
		JRequest::setVar('limit', PHP_INT_MAX);
		JRequest::setVar('limitstart', 0);
 		$view =& $this->getView('Program', 'html');
		$view->setModel($this->getModel('Program'), true);
 		$view->setModel($this->getModel('Questions'));
 		$view->display();
	}
    
	function saveProgram() {
		$model = $this->getModel('Program');
		if ($model->store() === true) {
	$this->setRedirect(COM_URI . '&task=listPrograms', JText::_('Program Saved!'));
		} else {
			$this->editProgram();
		}
	}
	
	function removeProgram() {
		$model = $this->getModel('program');
		$msg = !$model->delete()
			? JText::_('Error: One or More Initiative(s) Could not be deleted')
			: JText::_('Initiative(s) deleted!');
		$this->setRedirect(COM_URI . '&task=listPrograms', $msg);
	}
	
	function publish() {
		$msg	= null;
		$tPrograms =& $this->getModel('Programs')->getTable('Programs');
		if ($tPrograms->publish(JRequest::getVar('cid', 0, '', 'array'))) {
			$msg = 'Initiative(s) published.';
		}
		$this->listPrograms();
		#$this->setRedirect(COM_URI . 'task=listPrograms', $msg);
	}
	
	function unpublish() {
		$msg		= null;
		$tPrograms	=& $this->getModel('Programs')->getTable('Programs');
		
		if ($tPrograms->publish(JRequest::getVar('cid', 0, '', 'array'), 2)) {
			$msg = 'Initiative(s) unpublished.';
		}
		$this->listPrograms();
	}
	
	function cancelProgramEdit() {
		$this->setRedirect(COM_URI . '&task=listPrograms', JText::_('Operation Cancelled'));
	}
	
	function editQuestion() {
		JRequest::setVar('view', 'question');
		JRequest::setVar('hidemainmenu', 1);
 
		parent::display();
	}
    
	function saveQuestion() {
		$model = $this->getModel('question');

		$ret = $model->store();
		if ($ret === true) {
			$this->setRedirect(COM_URI . '&task=listQuestions', JText::_('Question Saved!'));
			return;
		} else {
			$this->editQuestion();
		}
	}
	
	function removeQuestion() {
		$model = $this->getModel('Question');
		if (!$model->delete()) {
			$msg = JText::_('Error: One or More questions Could not be Deleted');
		} else {
			$msg = JText::_('Question(s) Deleted');
		}

		$this->setRedirect(COM_URI . '&task=listQuestions', $msg);
	}
	
	function cancelQuestionEdit() {
		$this->setRedirect(COM_URI . '&task=listQuestions', JText::_('Operation Cancelled'));
	}
	
	function back() {
		$this->setRedirect(COM_URI, JText::_('Operation Cancelled'));
	}
} ?>