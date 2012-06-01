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
class TtaDbViewContribute extends TtaDbView {
    
	function display($tpl = null) {
    	JHTML::stylesheet('style.css', 'components/' . JRequest::getWord('option') . '/views/' . $this->getName() . '/tmpl/');
		JRequest::setVar('cid', 0);
    	$item	=& $this->get('Data');
		
		$this->assignRef('item', $program);
		parent::display($tpl);
    }
} ?>