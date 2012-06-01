<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
/**
 * HTML View class for the ProgramsDatabase Component's Question controller
 *
 * @package    ProgramsDatabase
 */
 
class ProgramsDatabaseViewQuestion extends JView {
    function display($tpl = null) {
    	JHTML::stylesheet('style.css', 'administrator/components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		$isNew		= JRequest::getVar('task') == 'addQuestion';
		$text 		= $isNew ? JText::_('New') : JText::_('Edit');
		$types		=& $this->get('Types');
		$maxQ		=& $this->get('MaxQuestionNumber');
		$groupings	=& $this->get('Groupings');
		$question	=& $this->get('Data');
		
		JToolBarHelper::title(JText::_('Question') . ": <small><small>[$text]</small></small>");
		JToolBarHelper::save('saveQuestion', 'Save');
		JToolBarHelper::cancel('cancelQuestionEdit', $isNew ? 'Cancel' : 'Close');
		
		if ($isNew) {
			$question->ordering = $maxQ + 1;
		}
		
		$this->assignRef('types',		$types);
		$this->assignRef('maxQ',		$maxQ);
		$this->assignRef('groupings',	$groupings);
		$this->assignRef('question',	$question);
		parent::display($tpl);
    }
} ?>