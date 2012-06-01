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
class TtaDbViewItem extends TtadbView {
    
	function display($tpl = null) {
    	JHTML::stylesheet('style.css', 'components/' . JRequest::getWord('option') . '/views/' . $this->getName() . '/tmpl/');
		JHTML::script('jquery-1.3.2.min.js','components/com_ttadb/views/item/js/');
		JHTML::script('fivestar.js','components/com_ttadb/views/item/js/');
    	$item	=& $this->get('Data');
    	    	$starItem	=& $this->get('StarData');
		$attrs	=& $this->get('Data', 'attrs');
		$model		= $this->getModel('item');
		$this->assignRef('item', $item);
		$this->assignRef('model', $model);
		$this->assignRef('attrs', $attrs);
		parent::display($tpl);
    }
} ?>