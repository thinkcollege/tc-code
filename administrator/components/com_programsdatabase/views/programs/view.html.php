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
 
class ProgramsDatabaseViewPrograms extends JView {
    
	function display($tpl = null) {
    	#JHTML::stylesheet('style.css', 'components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		
		JToolBarHelper::title(JText::_('Programs Database - Programs'), 'generic.png');
    	JToolBarHelper::addNewX('addProgram', 'Add');
 		JToolBarHelper::editListX('editProgram', 'Edit');
		JToolBarHelper::publish();
		JToolBarHelper::unpublish();
		JToolBarHelper::deleteList('initiative(s)', 'removeProgram', 'Delete');
		JToolBarHelper::back('Back', COM_URI);
		
		$items =& $this->get('Data');
		$this->assignRef('items', $items);
		
	$this->sortDirection = JRequest::getCmd('filter_order_Dir');
	$this->sortColumn = JRequest::getCmd('filter_order');
		
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
    }
} ?>