<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link       http://docs.joomla.org/Category:Development
 * @license    GNU/GPL
 */

// no direct access

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */

class TtaDbViewTtaDb extends JView {
	function display($tpl = null) {
		JToolBarHelper::title(JText::_('Training and Technical Assistance Database'), 'generic.png');
		
		$types = $this->get('Data');
		$this->assignRef('types', $types);
		
		parent::display($tpl);
	}
} ?>