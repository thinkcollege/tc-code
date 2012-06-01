<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
/**
 * HTML View class for the ProgramsDatabase Component's Question controller
 *
 * @package    ProgramsDatabase
 */
 
class ProgramsDatabaseViewQuestions extends JView {
    function display($tpl = null) {
    	JToolBarHelper::title(JText::_('Programs Database - Questions'), 'generic.png');
		JToolBarHelper::addNewX('addQuestion', 'New');
		JToolBarHelper::editListX('editQuestion', 'Edit');
		JToolBarHelper::deleteList('question(s)', 'removeQuestion', 'Delete');
		JToolBarHelper::back('Back', COM_URI);
		
		// Get data from the model
		$items =& $this->get('Data');
		$this->assignRef('items', $items);
		
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
    }
} ?>