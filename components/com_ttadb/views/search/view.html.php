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
class TtaDbViewSearch extends TtaDbView {
    
	function display($tpl = null) {
		JHTML::stylesheet('style.css', 'components/' . JRequest::getWord('option') . '/views/' . $this->getName() . '/tmpl/');
		
		$results =& $this->get('Data');
		$this->assignRef('results', $results);
		
		$pagination =& $this->get('Pagination');
		$this->assignRef('pager', $pagination);
		parent::display($tpl);
    }

	function getSearchTerms() {
		$mAttr	= $this->getModel('Attrs');
		$attrs	= $this->getAttrOfIds(JRequest::get('request'));
		$terms	= '';
		foreach ($attrs as $attrOfId => $vals) {
			if (!preg_match('/^a\d+$/', $attrOfId) || empty($vals[0])) {
				continue;
			}
			$a	 = $mAttr->getAttrOfById(substr($attrOfId, 1), true, false);
			$sep	= '';
			$term	= '';
			if ($a->validddation == V_DATE) {
				$term = ($vals[0][0] == '+' ? 'after ' : ($vals[0][0] == '-' ? 'before ' : '')) . date('m/d/Y', strtotime(substr($vals[0], 1)));
			} else if ($a->typeId < 3) {
				$term = "containing \"{$vals[0]}\"";
			} else if ($a->typeId > 99) {
				foreach (explode('||', $a->choices) as $choice) {
					$pos = strpos($choice, '$$');
					$id = abs(substr($choice, 0, $pos));
					if ((is_array($vals) && in_array($id, $vals)) || $id == $vals) {
						$term .= $sep . substr($choice, $pos + 2);
						$sep = ', ';
					}
				}
			}
			if (!empty($term)) {
				$terms .= "<li><strong>{$a->displayLabel}</strong> $term</li>";
			}
		}
		if (!empty($terms)) {
			$updated = JRequest::getVar('updated');
			if ($updated) {
				$terms .= '<li>for items updated on ' . ($updated[0][0] == '+' ? 'or after' : ($updated[0][0] == '-' ? 'or before' : '')) . date(' m/d/Y', strtotime(substr($updated[0], 1))) . '</li>';
			}
			print "Search for: <ul>$terms</ul>";
		}
	}
	
	function getAttrOfIds($filters) {
		$attrs = array();
		foreach ($filters as $filter => $val) {
			if (!preg_match('/^a?\d+$/', $filter)) {
				continue;
			}
			$tmp = each($val);
			if (is_array($val[0])) {
				$val = $val[0];
			}
			if ($tmp[0][0] == 'a') {
				$attrs = array_merge($attrs, $this->getAttrOfIds($val));
			} else if (is_array($val)) {
				$attrs[$filter] = $val;
			} else {
				$attrs[$filter][] = abs($val[0]);
			}
		}
		return $attrs;
	}
} ?>