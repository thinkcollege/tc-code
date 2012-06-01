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
class TtaDbViewItem extends JView {

	function display($tpl = null) { 
		$item	=& $this->get('Data');
    	JHTML::stylesheet('style.css', 'administrator/components/com_ttadb/views/' . $this->getName() . '/tmpl/');
		
    	$text	= JRequest::getVar('task') == 'addItem' ? JText::_('New') : JText::_('Edit');
$titletext	= JRequest::getVar('task') == 'addItem' ? JText::_('TTA Database - New Item') : JText::_('TTA Database - <small>'.$item[0]->_title. '</small>');
		JToolBarHelper::title($titletext . ": <small><small>[$text]</small></small>");
		JToolBarHelper::save('saveItem', 'Save');
		JToolBarHelper::cancel('cancelItemEdit', 'Close');
		
		$attrs	=& $this->get('Data', 'attrs');
		$this->assignRef('item',	$item);
		$this->assignRef('attrs',	$attrs);
		parent::display($tpl);
	}
	
	function displayAttr($a, $data, $label = '') {
		$id			= "a$a->attrOfId";
		$derived	= $label && 1;
		$multiple	= ($a->flags & TableAttrOf::FLAG_MULTIPLE) != 0;
		if (!empty($a->attrs) && ($a->flags & TableAttrOf::FLAG_MERGE) == 0) {
			if (!empty($a->inputLabel)) {
				print "<fieldset><legend>$a->inputLabel</legend>";
			}
			for ($i = 0; $i < ($multiple ? max(5, count($data->{'a' . $a->attrOfId}) + 1) : 1); $i++) {
				if ($i > 0) {
					print "<div style=\"clear:both;\"></div>";
				}
				foreach ($a->attrs as $a2) {
					print '<div class="dAttr">';
					$this->displayAttr($a2, $data->{$id}[$i], $label . ($label ? "[$id][$i]" : "{$id}[$i]"));
					print "</div>";
				}
			}
			if (!empty($a->inputLabel)) {
				print "</fieldset>";
			}
			return;
		}
		if (!$derived) {
			print "<p>";
		}
		$sep	= "";
		$max	= $multiple && !$derived && ($a->flags & TableAttrOf::FLAG_MERGE) == 0 ? max(5, count($data->$id) + 1) : 1;
		for ($i = 0; $i < $max; $i++) {
			$name	= $a->flags & TableAttrOf::FLAG_MERGE ? $label . ($label ? "[$id][]" : "{$id}[]") : $label . ($label ? "[$id][$i]" : "{$id}[$i]");
			if (is_object($data)) {
				$val	= $a->flags & TableAttrOf::FLAG_MERGE ? $data->{$id} : $data->{$id}[$i];
			} else {
				$val	= $a->flags & TableAttrOf::FLAG_MERGE ? $data[$id] : $data[$id][$i];
			} 
			print "$sep<label for=\"$name\">$a->inputLabel</label><br />";
			if (!$derived && $a->typeId == 1) { 
				print "<textarea name=\"$name\" id=\"$name\" rows=\"2\" cols=\"40\">$val</textarea>" . (!$derived ? '</p>' : '');
			} else if (!$derived && $a->typeId == 2) {
				if ($a->validation == V_DATE) {
					if($val) { $val = date('m/d/Y', intval($val)); } else { $val = date('m/d/Y'); }
				}
				print "<input type=\"text\" name=\"$name\" id=\"$name\" value=\"$val\" size=\"45\" />" . (!$derived ? '</p>' : '');
			} else if (!$derived && $a->typeId == 5) {
				print "<input type=\"file\" id=\"$name\" name=\"$name\" /> Current: <a href=\"./components/"
					. strtolower(JRequest::getWord('option')) . "/files/$val\">$val</a> "
					. "<input type=\"checkbox\" id=\"$name-d\" name=\"{$name}[delete]\" value=\"1\" /> "
					. "<label for=\"$name-d\">Remove</label>" . (!$derived ? '</p>' : '');
			} else {
				#print "<pre>choices:$a->choices</pre>";
				$choices	= explode('||', $a->choices);
				if (!is_array($val)) {
					$val = array(intval($val));
				}
				print $multiple ? "<ul class=\"choices\">" : "<select id=\"$name\" name=\"$name\"><option value=\"\"></option>";
				foreach ($choices as &$c) {
					$c		= explode('$$', $c);
					$cVal	= $c[0];
					$cLabel	= $c[1]; 
					if ($multiple) {
						$checked = in_array($cVal, $val) ? 'checked="checked" ' : '';
						print "<li><input type=\"checkbox\" name=\"{$name}\" id=\"$name-$cVal\" value=\"$cVal\" $checked/> <label for=\"$name-$cVal\">$cLabel</label></li>";
					} else {
						$selected = in_array($cVal, $val) ? ' selected="selected"' : ''; 
						print "<option value=\"$cVal\"$selected>$cLabel</option>";
					}
				}
				print $multiple ? "</ul>" : "</select>";
			}
			$sep = "<br />";
		}
		if (!$derived) {
			print "</p>";
		}
	
	}
} ?>