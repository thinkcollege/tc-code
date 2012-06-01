<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link       http://docs.joomla.org/Category:Development
 * @license    GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */

class TtaDbViewLogin extends TtaDBView {

	function display($tpl = null) {
		JHTML::stylesheet('style.css', 'components/com_ttadb/views/' . $this->getName() . '/tmpl/');
		
		$results =& $this->get('Data');
		$this->assignRef('results', $results);
		
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);
		parent::display($tpl);
    }
} ?>