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
class TtaDbController extends JController {
    
/* Hack **  --to get e-mail address to save
	function display() {
		if (!JRequest::getInt('tta-id')) {
			setcookie('tta-id', 500, 1924923600, '/', $_SERVER['HTTP_HOST'], false, true);
		}
		if (JRequest::getInt('tta-id', 0, 'cookie') > 0) {
			$this->login();
			return;
		}
		$view =& $this->getView('ttadb', 'html');
		$view->setModel($this->getModel('Type'), true);
		$view->setModel($this->getModel('Item'));
		$view->setModel($this->getModel('Attrs'));
		$view->display();
	}
	
	function login() {
		$model = $this->getModel('Item');
		$ttaId = JRequest::getInt('tta-id', 0, 'cookie');
		if ($ttaId > 0 || $model->store()) {
			$ret = setcookie('tta-id', $ttaId ? $ttaId : $model->getId(), 1924923600, '/', $_SERVER['HTTP_HOST'], false, true);
			$view = $this->getView('login', 'html');
			$view->setModel($this->getModel('Items'), true);
			$view->setModel($this->getModel('Attrs'));
			$view->setModel($this->getModel('Type'));
			$view->display();
		} else {
			JError::raiseWarning(500, "Failed to register e-mail address!");
			$this->display();
		}
	}
*/

function display() {
		
		if (JRequest::getInt('tta-id', 0, 'cookie') > 0) {
				$view = $this->getView('login', 'html');
			$view->setModel($this->getModel('Items'), true);
			$view->setModel($this->getModel('Attrs'));
			$view->setModel($this->getModel('Type'));
			$view->display();
		} 
		else{
		$view =& $this->getView('ttadb', 'html');
		$view->setModel($this->getModel('Type'), true);
		$view->setModel($this->getModel('Item'));
		$view->setModel($this->getModel('Attrs'));
		$view->display();
	}
	}
	
	function login() {
		$model = $this->getModel('Item');
		$ttaId = JRequest::getInt('tta-id', 0, 'cookie');
		$test = JRequest::get('post');
		if (!empty($test['a61'][0]) && ($ttaId < 500) && $model->store()) {
			$ret = setcookie('tta-id',500, 1924923600, '/', $_SERVER['HTTP_HOST'], false, true);
			$view = $this->getView('login', 'html');
			$view->setModel($this->getModel('Items'), true);
			$view->setModel($this->getModel('Attrs'));
			$view->setModel($this->getModel('Type'));
			$view->display();
			
		} else {
			JError::raiseWarning(500, "Failed to register e-mail address!");
			$this->display();
		}
	}
// end hack


	function search() {
		if (false && JRequest::getInt('tta-id', 0, 'cookie') == 0) {
			$this->display();
			return;
		}
		$results = $this->getModel('Items')->getData();
		if (true || count($results) > 0) {
			$view = $this->getView('search', 'html');
			$view->setModel($this->getModel('Items'), true);
			$view->setModel($this->getModel('Attrs'));
			$view->setModel($this->getModel('Type'));
			$view->display();
		} else {
			JError::raiseWarning(500, 'No results for the search terms.');
			$this->display();
		}
	}
	
	function item() {
		if (JRequest::getInt('tta-id', 0, 'cookie') == 0) {
			$this->display();
			return;
		}
		if (!$this->getModel('Item')->getData()) {
			JError::raiseWarning(100, JText::_('Item does not exist.'));
			$this->display();
		} else {
			$view =& $this->getView('Item', 'html');
 			$view->setModel($this->getModel('Item'), true);
 			$view->setModel($this->getModel('Attrs'));
 			$view->display();
 		}
	}
	
	function compare() {
		if (JRequest::getInt('tta-id') == 0) {
			$this->display();
			return;
		}
		$view =& $this->getView('compare', 'html');
 		$view->setModel($this->getModel('Item'), true);
 		$view->setModel($this->getModel('Attrs'));
 		$view->display();
 	}
 	
 	function add() {
		if (JRequest::getInt('tta-id') == 0) {
			$this->display();
			return;
		}
		JRequest::setVar('cid', 0, 'method', true);
		$this->getModel('Item')->setId(0);
		
		$view =& $this->getView('Contribute', 'html');
 		$view->setModel($this->getModel('Item'), true);
 		$view->setModel($this->getModel('Attrs'));
		$view->setModel($this->getModel('Type'));
 		$view->display();
	}
    
	function save() {
		if (JRequest::getInt('tta-id') == 0) {
			$this->display();
			return;
		}
		JRequest::setVar('cid', 0, 'method', true);
		$model = $this->getModel('Item');
		$model->setId(0);
		
		$ret = $model->store();
		if ($ret === true) {
			#$this->setRedirect(COM_URI, JText::_('Program Saved!'));
			JError::raiseNotice(100, 'Thank you for your contirbution!');
		// Hack **  changed redirect view
		//	parent::display();
		$view =& $this->getView('Contribute', 'html');
 		$view->setModel($this->getModel('Item'), true);
 		$view->setModel($this->getModel('Attrs'));
		$view->setModel($this->getModel('Type'));
 		$view->display();
		} else {
			$this->add();
		}
	}
} ?>