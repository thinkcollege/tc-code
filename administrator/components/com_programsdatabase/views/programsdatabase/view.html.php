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

class programsdatabaseViewprogramsdatabase extends JView {
	function display($tpl = null) {
		JToolBarHelper::title( JText::_( 'Programs Database' ), 'generic.png' );
		
		parent::display($tpl);
	}
} ?>