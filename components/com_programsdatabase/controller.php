<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link       http://docs.joomla.org/Category:Development
 * @license    GNU/GPL
 */
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
/**
 * Hello World Component Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ProgramsDatabaseController extends JController {
    
		function search() {
		$results = $this->getModel('Search')->getData();
		
			JRequest::setVar('view', 'search');
		
		parent::display();
	}
	
	function program() {
		if (!$this->getModel('Program')->getData()) {
			JError::raiseWarning(100, JText::_('Progam does not exist.'));
			parent::display();
		} else { $document =& JFactory::getDocument();
        $viewType        = $document->getType();
			$view =& $this->getView('program', $viewType);
 			$view->setModel($this->getModel('Program'), true);
 			$view->setModel($this->getModel('Questions'));
 			$view->display();
 		}
	}
	
	function searchform() {
		
			$view =& $this->getView('programform', 'html');
 			$view->setModel($this->getModel('Program'), true);
 			$view->setModel($this->getModel('Programsdatabase'));
 			$view->display();
 		
	}
	
	function compare() {
		$view =& $this->getView('compare', 'html');
 		$view->setModel($this->getModel('Program'), true);
 		$view->setModel($this->getModel('Questions'));
 		$view->display();
 	}
 	
 	function add() {
		unset($_SESSION['cidtemp']);
		JRequest::setVar('cid', 0, 'method', true);
		$this->getModel('Program')->setId(0);
		
		$view =& $this->getView('Programfront', 'html');
 		$view->setModel($this->getModel('Programfront'), true);
 		$view->setModel($this->getModel('Questions'));
 		$view->display();
	}
 	
 	function addfront() {	
	//	$this->getModel('Programfront')->setId(0);
		
		$view =& $this->getView('Programfront', 'html');
 		$view->setModel($this->getModel('Programfront'), true);
 		$view->setModel($this->getModel('Questions'));
 		$view->display();
	}
    
	function save() {
	
		$model = $this->getModel('Programfront');
		
		
		$ret = $model->store();
		if ($ret === true) {
			#$this->setRedirect(COM_URI, JText::_('Program Saved!'));
			JError::raiseNotice(100, 'Thank you for your contribution!');
			parent::display();
		} else {
			$this->addfront();
		}
	}
    
	function savenew() {
	
		$model = $this->getModel('Programfront');
		
	
		$ret = $model->store();
		if ($ret === true) {
			#$this->setRedirect(COM_URI, JText::_('Program Saved!'));
			JError::raiseNotice(100, 'Thank you for your contribution!');
			parent::display();
		} else {
			$this->addfront();
		}
	}
    
	function editfront() {
		
		$model = $this->getModel('Programfront');
		$model->setId(0);
		
		$ret = $model->store();
		if ($ret === true) {
			$this->setRedirect(COM_URI, JText::_('Program Saved!'));
			
			parent::display();
		} else {
		$this->add();
		}
	}
	
} ?>