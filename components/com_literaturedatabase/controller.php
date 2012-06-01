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
class LiteraturedatabaseController extends JController {
    function display() {
    	$model = $this->getModel('search');
    	$view = $this->getView('search', 'html');
    	$view->setModel($model, true);
    	$view->setLayout('default');
    	$view->display();
    }
    
    function search() {
    	$model = $this->getModel('search');
    	$view = $this->getView('search', 'html');
    	
    	if (!isset($_GET['all']) && !JRequest::getVar('contributor') && !JRequest::getVar('type')
    		&& !JRequest::getVar('audience') && !JRequest::getVar('subject') && !JRequest::getVar('year')) {
			$this->setRedirect('/?option=com_literaturedatabase&view=search');
			return;
		}
		$results = $model->search();
		if (count($results) == 0) {
			$this->setRedirect('/?option=com_literaturedatabase&view=search', 'No Results for that criteria.', 'error');
			return;
		}
		$view->setModel($model, true);
    	$view->setLayout('results');
    	$view->assignRef('results', $results);
    	$view->display();
    }
    
    function literature() {
    	$id = JRequest::getInt('id', 0);
    	if ($id < 1) {
    		$this->setRedirect('./?option=com_literaturedatabase&view=search');
    		return;
    	}
    	$lit = new Literature($id);
    	if ($lit->getLitID() != $id) {
    		$this->setRedirect('./?option=com_literaturedatabase&view=search');
    		return;
    	}
    	$model = $this->getModel('search');
    	$view = $this->getView('search', 'html');
    	$view->setModel($model, true);
    	$view->setLayout('literature');
    	$view->assignRef('lit', $lit);
    	$view->display();
    }
}
?>