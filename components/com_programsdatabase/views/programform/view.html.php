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

class programsdatabaseViewProgramform extends JView {
	function display($tpl = null) {
			$model		= $this->getModel('programsdatabase');
			
		JHTML::stylesheet('style.css', 'components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		JHTML::script('jquery-1.3.2.min.js','modules/mod_slider/js/');
		JHTML::script('initialize.js','components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		JHTML::script('formToWizard.js','components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		$statelist = $model->getStates();
		$this->assignRef('states', $statelist);
		parent::display($tpl);
	}
} ?>