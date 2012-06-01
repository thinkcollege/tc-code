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
class TtaDbViewTypes extends JView {
	function display($tpl = null) {
		JToolBarHelper::title(JText::_('TTA Database - Types'), 'generic.png');
		JToolBarHelper::addNewX('addType', 'New');
		JToolBarHelper::editListX('editType', 'Edit');
		JToolBarHelper::deleteList('Are you sure you want to remove these type(s)?', 'removeType', 'Delete');
		JToolBarHelper::back('Back', './?option=' . strtolower(JRequest::getWord('option')));
		
		// Get data from the model
		$types =& $this->get('Data');
		$this->assignRef('types', $types);
		
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	}
} ?>