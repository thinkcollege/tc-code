<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

/**
 * HTML View class for the CCK Component's type view.
 *
 * @package    CCK
 */
class TtaDbViewType extends JView {
	function display($tpl = null) {
		JHTML::stylesheet('style.css', 'administrator/components/' . strtolower(JRequest::getWord('option')) . '/views/' . $this->getName() . '/tmpl/');
		JHTML::script('funcs.js', 'administrator/components/' . strtolower(JRequest::getWord('option')) . '/views/' . $this->getName() . '/tmpl/', true);
		$isNew		 = JRequest::getVar('task') == 'addType';
		$text 		 = $isNew ? JText::_('New') : JText::_('Edit');
		$type		 =& $this->get('Data');
		
		$allAttrs	 =& $this->getModel('Attrs')->getAttrsOf(0, false, false);
		$type->attrs = array_merge($type->attrs, $allAttrs);
		
		JToolBarHelper::title(JText::_('Type') . ": <small><small>[$text]</small></small>");
		JToolBarHelper::save('saveType', 'Save');
		JToolBarHelper::cancel('cancelTypeEdit', $isNew ? 'Cancel' : 'Close');
		
		$this->assignRef('data',	$type);
		parent::display($tpl);
	}

	function printJSConstant($label, $value) {
		print "<!--[if IE]><script language=\"VBScript\">Const $label = $value</script><![endif]-->"
			. "<!--[if ! IE]> --><script type=\"text/javascript\" defer=\"defer\">const $label = $value;</script><!-- <![endif]-->";
	}
} ?>