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
class TtaDbViewItems extends JView {
    
	function display($tpl = null) {
    	JToolBarHelper::title(JText::_('TTA Database - Items'), 'generic.png');
    	JToolBarHelper::addNewX('addItem', 'Add');
 		JToolBarHelper::editListX('editItem', 'Edit');
		JToolBarHelper::publish();
		JToolBarHelper::unpublish();
		JToolBarHelper::deleteList('Delete selected item(s)?  This action cannot be undone.', 'removeItem', 'Delete');
		JToolBarHelper::back('Back', './?option=' . strtolower(JRequest::getWord('option')));
		$typeId = JRequest::getVar('typeId');
		if($typeId == 112)	JToolBarHelper::custom("extract", "send.png","send.png", "Download Users", false);
		$items	=& $this->get('Data');
		$attrs	=& $this->get('Data', 'Attrs');
		$cr = new stdClass;
		$cr->typeId = JRequest::getInt('typeId');
		$choices = $this->getModel('Attrs')->getChoices($cr);
		$this->assignRef('items', $items);
		$this->assignRef('attrs', $attrs);
		$this->assignRef('choices', $choices);
		
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
    }
	
	function getHeaders(&$attrs) {
    	$cols .= '';
		if ($attrs instanceof JException) {
			print "<p>{$attrs->getMessage()}</p>";
		}
    	foreach ($attrs as $i => &$a) {
    		if (($a->flags & TableAttrOf::FLAG_SUMMARY) == 0) {
    			unset($attrs[$i]);
    			continue;
    		}
    		if (($a->flags & TableAttrOf::FLAG_MERGE) == 0 && !empty($a->attrs)) {
    			$cols .= $this->getHeaders($a->attrs);
    		} else {
    			$cols .= "<th>$a->inputLabel</th>";
    		}
    	}
    	return $cols;
    }
	
	function displayRow(&$row, &$attrs, $depth = 0) {
		if (isset($row->_title) && $depth == 0) {
			print "<td><a href=\"./?option=" . strtolower(JRequest::getWord('option'))
				. "&task=editItem&typeId=" . JRequest::getInt('typeId')
				. "&cid[]={$row->id}\">$row->_title</a></td>";
			$link = true;
		}
		foreach ($attrs as &$a) {
			if (($a->flags & TableAttrOf::FLAG_SUMMARY) == 0) {
				continue;
			}
			$col = "a$a->attrOfId";
			if (($a->flags & TableAttrOf::FLAG_MERGE) == 0 && !empty($a->attrs)) {
				$row->$col = isset($row->$col) ? $row->$col : null;
				$this->displayRow($row->$col, $a->attrs, $depth + 1);
			} else {
				$sep = '';
				$val = '';
				for ($i = 0; $i < count($row->$col); $i++) {
					$val = $row->{$col}[$i];
					if ($a->flags & TableAttrOf::FLAG_MERGE) {
						foreach(explode('||', $a->choices) as $choice) {
							$pos = strpos($choice, "$val$$");
							if ($pos === 0) {
								$val = substr($choice, strlen($val) + 2);
								break;
							}
						}
						if ($pos === false) {
							$val = '';
						}
					} else if ($a->validation == V_DATE) {
						$val = date('m/d/Y', intval($val));
					}
					$sep = !empty($val) ? ($a->flags & TableAttrOf::FLAG_AS_LIST ? '</li><li>' : ', ') : '';
				}
				if (!isset($link) && (empty($val) || ($a->flags & TableAttrOf::FLAG_AS_LIST) == 0)) {
					print "<td><a href=\"./?option=" . strtolower(JRequest::getWord('option'))
						. "&task=editItem&typeId=" . JRequest::getInt('typeId')
						. "&cid[]={$row->id}\">" . (empty($val) ? '(none)' : $val) . "</a></td>";
					$link = true;
				} else {
					print empty($val) || ($a->flags & TableAttrOf::FLAG_AS_LIST) == 0 ? "<td>$val</td>" : "<td><ul><li>$val</li></td>";
				}
			}
		}
    }
} ?>