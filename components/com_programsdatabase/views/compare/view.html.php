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
 
class ProgramsDatabaseViewCompare extends JView {
    
	function display($tpl = null) {
    	JHTML::stylesheet('style.css', 'components/com_programsdatabase/views/' . $this->getName() . '/tmpl/');
		$cids		= JRequest::getVar('cid', 0, 'array');
    	$model		= $this->getModel('program');
    	$programs	= array();
    	$questions	=& $this->get('Data', 'questions');
		foreach ($cids as $i => $cid) {
    		$model->setId($cid);
    		$p = $model->getData();
    		$programs[] = $p;
    		if ($i > 2) {
    			break;
    		}
    	}
    	
		$this->assignRef('programs', $programs);
		$this->assignRef('questions', $questions);
		parent::display($tpl);
    }
} ?>