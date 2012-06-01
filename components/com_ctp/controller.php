<?php
/**
 * Hello World default controller
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

jimport('joomla.application.component.controller');

/**
 * Hello World Component Controller
 *
 * @package		HelloWorld
 */
class CtpController extends JController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		parent::display();
	}
	function checklist()
	{ 
		$view = $this->getView('ctp', 'html');
		$view->setLayout('checklist', 'html');
			$view->assignRef('checklist', $checklist);
		$view->display();
	}
function results() {
	 $document =& JFactory::getDocument();
        $viewType        = $document->getType();
			$view =& $this->getView('results', $viewType);
 			$view->display();
 	
	}
	
}
?>
