<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
 
/**
 * HTML View class for the CCK Component's Import view.
 *
 * @package    CCK
 */
class TtaDbViewImport extends JView {
	function display($tpl = null) {
    	JToolBarHelper::title(JText::_('TTA Database - Import'), 'generic.png');
		JToolBarHelper::save('importItems', 'Import');
		JToolBarHelper::cancel('cancelImport', 'Back');
		
		parent::display($tpl);
	}
}