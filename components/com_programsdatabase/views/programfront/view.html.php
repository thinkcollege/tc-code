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
 
class ProgramsDatabaseViewProgramfront extends JView {
    
	function display($tpl = null) {
    	JHTML::stylesheet('style.css', 'components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		
    	$text	= JRequest::getVar('task') == 'addfront' ? JText::_('New') : JText::_('Edit');
		$program	=& $this->get('Data');
		$questions	=& $this->get('Data', 'questions');
		$this->assignRef('program',		$program);
		$this->assignRef('questions',	$questions);	$model = $this->getModel('Program');
		$model = $this->getModel('Programfront');
		$rankings = $model->getRankingIDs();
		$this->assignRef('rankings',		$rankings);
	// Hack **  This hack loads content plugins so the glossary will work on program component content
			ob_start();
		parent::display($tpl);
$output = ob_get_contents();
ob_end_clean();

echo JHTML::_('content.prepare', $output);

// end hack
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