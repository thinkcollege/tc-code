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
		
    	JHTML::stylesheet('style.css', 'components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		
    	$program	=& $this->get('Data');
		$questions	=& $this->get('Data', 'questions');
		
		$this->assignRef('program', $program);
		$this->assignRef('questions', $questions);
// Hack **  This hack loads content plugins so the glossary will work on program component content
			ob_start();
		parent::display($tpl);
$output = ob_get_contents();
ob_end_clean();

echo JHTML::_('content.prepare', $output);

// end hack
    }
} ?>