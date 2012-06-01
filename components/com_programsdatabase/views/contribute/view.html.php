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
 
class ProgramsDatabaseViewContribute extends JView {
    
	function display($tpl = null) {
    	JHTML::stylesheet('style.css', 'components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		JRequest::setVar('cid', 0, 'method', true);
    	$program	=& $this->get('Data');
		$questions	=& $this->get('Data', 'questions');
		$errorIDs	=& $this->get('ErrorIDs');
			$model = $this->getModel('Programfront');
		$rankings = $model->getRankingIDs();
		$this->assignRef('rankings',		$rankings);
		$this->assignRef('program',		$program);
		$this->assignRef('questions',	$questions);
		$this->assignRef('errorIDs',	$errorIDs);
	// Hack **  This hack loads content plugins so the glossary will work on program component content
			ob_start();
		parent::display($tpl);
$output = ob_get_contents();
ob_end_clean();

echo JHTML::_('content.prepare', $output);

// end hack
    }
} ?>