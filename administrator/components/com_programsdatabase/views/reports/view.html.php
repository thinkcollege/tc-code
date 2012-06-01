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
    	JToolBarHelper::title(JText::_('Programs Database - Programs'), 'generic.png');
    	JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
 
		// Get data from the model
		$items =& $this->get('Data');
		$this->assignRef('items', $items);
		parent::display($tpl);
    }
} ?>