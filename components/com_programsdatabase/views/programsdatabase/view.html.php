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
		JHTML::stylesheet('style.css', 'components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
			$db = JFactory::getDBO();
			$db->setQuery('SELECT COUNT(*)  FROM #___programsdb_programs WHERE published = 1');
			$countPrograms = $db->loadResult();
			$this->assignRef('countPrograms',	$countPrograms);
		$this->assignRef('states', $this->get('states'));
		parent::display($tpl);
		
	}
} ?>