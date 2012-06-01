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
class TtaDbViewTtaDb extends TtaDbView {
	
	function display($tpl = null) {
		JHTML::stylesheet('style.css', 'components/com_ttadb/views/' . $this->getName() . '/tmpl/');
		parent::display($tpl);
	}
} ?>