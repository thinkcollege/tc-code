<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link       http://docs.joomla.org/Category:Development
 * @license    GNU/GPL
 */
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
 
class ProgramsDatabaseViewProgram extends JView {
    
	function display($tpl = null) {
    	JHTML::stylesheet('style.css', 'administrator/components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		
    	$text	= JRequest::getVar('task') == 'add' ? JText::_('New') : JText::_('Edit');
		JToolBarHelper::title(JText::_('Programs Database - Program') . ": <small><small>[$text]</small></small>");
		JToolBarHelper::save('saveProgram', 'Save');
		if (JRequest::getVar('task') == 'addProgram')  {
			JToolBarHelper::cancel();
		} else { // for existing items the button is renamed `close`
			JToolBarHelper::cancel('cancelProgramEdit', 'Close');
		}
		$program	=& $this->get('Data');
		$questions	=& $this->get('Data', 'questions');
		$model = $this->getModel('Program');
		$rankings = $model->getRankingIDs();
		$this->assignRef('rankings',		$rankings);
		$this->assignRef('program',		$program);
		$this->assignRef('questions',	$questions);
		parent::display($tpl);
    }
    
    /*function display($tpl = null) {
    	$model = $this->getModel();
    	switch ($this->getLayout()) {
        	case 'default':
    			$errs = $model->getErrors();
        		if (count($errs) > 0) {
	        		$this->assign('errors', '<ul class="error"><li>'.implode('</li><li>', $errs) .'</li></ul>');
        		}
        		break;
    		case 'results':
				$next = $model->getNext();
    			$url = $model->getSearchUrl();
        		$summary = 'You are viewing results ' . ($next + 1) . ' through '
						 . ($next + 25 < $model->getTotal() ? $next + 25 : $model->getTotal()) 
						 . ' of ' . $model->getTotal() . '. ';
				$this->assignRef( 'summary', $summary );
				if ($next > 0) {
					$prevUrl = $url . ($next > 25 ? '&next=' . ($next - 25) : '');
					$this->assignRef('prev', $prevUrl);
				}
				if ($next + 25 < $model->getTotal()) {
					$nextUrl = $url . '&next=' . ($next + 25);
					$this->assignRef('next',$nextUrl);
				}
			break;
    	}
    	
    	parent::display($tpl);
    }*/
}
?>