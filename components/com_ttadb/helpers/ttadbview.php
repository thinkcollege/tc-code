<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class TtaDbView extends JView {
	
	const ADD_FORM = 1, SUMMARY = 2, FULL = 3, SELECT = 4, RADIO = 5, CHECKBOX = 6;
	
	function displayTypeChoicesAs($displayType, $path, $multiple = false, $size = 10) {
		$multiple = !!$multiple;
		$size = $multiple ? ($size > 0 ? $size : 10) : 1;
		switch ($displayType) {
			case self::SELECT:
				if (empty($path)) {
					break;
				}
				$parts		= explode('-', $path);
				$attrId		= abs(substr(array_pop($parts), 1));
				$attr		= $this->getModel('Attrs')->getAttrOfById($attrId);
				$var		= 'a' . $attr->attrOfId;
				$item		= new stdClass;
				$item->$var = JRequest::getVar($var, array(array()));
				$label		= !empty($parts[0]) ? $parts[0] : '';
				for ($i = 1; $i < count($parts); $i++) {
					$label .= "[{$parts[$i]}]";
				}
				$this->displayAttrInput($attr, $item, $label);
		}
	}
	
	function addItemForm($typeId, $item) {
		$attrs = $this->getModel('Attrs')->getAttrsOf($typeId);
		foreach ($attrs as $attr) {
			if (($attr->flags & TableAttrOf::FLAG_INTERNAL) == 0) {
				$this->displayAttrInput($attr, null);
			}
		}
	}
	
	function displayItem($item, $displayType = self::FULL) {
		$attrs = $this->getModel('Attrs')->getAttrsOf($item->typeId);
		if ($displayType == self::SUMMARY && !empty($item->_title)) {
		//	print JHTML::link(JRoute::_('&task=item&cid=' . $item->id), (!LIVE && JRequest::getVar('showAll') ? 'ID:' . $item->id . ' ' : '') . $item->_title, array('class' => '_title')) . ' ';
		// Hack **  These links were getting written incorrectly
		print JHTML::link('index.php?option=com_ttadb&task=item&typeId='.$item->typeId.'&cid=' . $item->id, (!LIVE && JRequest::getVar('showAll') ? 'ID:' . $item->id . ' ' : '') . $item->_title, array('class' => '_title')) . ' '; 
		
		} else if ($displayType == self::SUMMARY) {
			$tmp = each($item);
			print JHTML::link(JRoute::_('&task=item&cid=' . $item->id), (!LIVE && JRequest::getVar('showAll') ? 'ID:' . $item->id . ' ' : '') . $tep[1], array('class', $tmp[0])) . ' ';
		} else {
			if (empty($item->_title)) {
				$tmp = each($item);
				$title = $tmp[1];
			} else {
				$title = $item->_title;
			}
			print "<h1>{$item->_title}</h1>";
		}
		foreach ($attrs as $a) {
			if ($displayType == self::SUMMARY && ($a->flags & TableAttrOf::FLAG_SUMMARY) == 0) {
				continue;
			}
			$this->displayAttr($a, $item, $displayType);
		}
if ($displayType == self::SUMMARY) {
			if ($item->rating != 0): ?><span class="floatright stardiv">

<span id="rating_<?php $rating = $item->rating;
$pageid = $item->id;
echo $pageid; 

 ?>">
<span class="star_1"><img src="/components/com_ttadb/views/item/images/star_blank.png" alt="" <?php if($rating > 0) { echo"class='hover'"; } ?> /></span>
<span class="star_2"><img src="/components/com_ttadb/views/item/images/star_blank.png" alt="" <?php if($rating > 1.5) { echo"class='hover'"; } ?> /></span>
<span class="star_3"><img src="/components/com_ttadb/views/item/images/star_blank.png" alt="" <?php if($rating > 2.5) { echo"class='hover'"; } ?> /></span>
<span class="star_4"><img src="/components/com_ttadb/views/item/images/star_blank.png" alt="" <?php if($rating > 3.5) { echo"class='hover'"; } ?> /></span>
<span class="star_5"><img src="/components/com_ttadb/views/item/images/star_blank.png" alt="" <?php if($rating > 4.5) { echo"class='hover'"; } ?> /></span>
</span>
</span><?php endif;
		}
	}
	
	function displayAttr($a, $data, $displayType, $label = '') {
		$id			= "a$a->attrOfId";
		$derived	= $label && 1;
		$multiple	= ($a->flags & TableAttrOf::FLAG_MULTIPLE) != 0;
		$label		.= $label ? '-' : '';
		if (($displayType == self::SUMMARY && ($a->flags & TableAttrOf::FLAG_SUMMARY) == 0) ||
			($a->flags & TableAttrOf::FLAG_TITLE) || empty($data->{$id})) {
			return;
		}
		if (!empty($a->attrs) && ($a->flags & TableAttrOf::FLAG_MERGE) == 0) {
			if (!empty($a->inputLabel)) {
				print "<fieldset class=\"$id\"><legend>$a->inputLabel</legend>";
			}
			for ($i = 0; $i < count($data->{$id}); $i++) {
				if ($i > 0) {
					print "<div style=\"clear:both;\"></div>";
				}
				foreach ($a->attrs as $a2) {
					if ($displayType == self::SUMMARY && ($a2->flags & TableAttrOf::FLAG_SUMMARY) == 0) {
						continue;
					}
					print "<div class=\"dAttr $label$id\">";
					$this->displayAttr($a2, $data->{$id}[$i], $displayType, "$label$id-$i");
					print "</div>";
				}
			}
			if (!empty($a->inputLabel)) {
				print "</fieldset>";
			}
			return;
		}
		if ($multiple && $a->flags & TableAttrOf::FLAG_AS_LIST) {
			print "<ul class=\"$id\">";
		} else if ($displayType == self::SUMMARY) {
			print "<span class=\"$id $label$id\">";
		} else {
			print "<p class=\"$id $label$id\">";
		}
		if ($displayType != self::SUMMARY || $derived) {
			print "$sep<strong>$a->displayLabel</strong><br />";
		}
		$sep	= "";
		$count = is_object($data) ? count($data->$id) : count($data[$id]);
		for ($i = 0; $i < $count; $i++) {
			$val	= is_object($data) ? $data->{$id}[$i] : $data[$id][$i];
			if (!$derived && $a->typeId == 1) { 
				print $val . (!$derived ? '</p>' : '');
			} else if (!$derived && $a->typeId == 2) {
				if ($a->validation == V_DATE) {
					$val = date('m/d/Y', intval($val));
				}
				print $val . (!$derived ? '</p>' : '');
			} else if (!$derived && $a->typeId == 5) {
				print "<p><a href=\"/administrator/components/"
					. strtolower(JRequest::getWord('option')) . 
"/files/". strtolower($val) . "\">$val</a> " . (!$derived ? '</p>' : 
'');
			} else if ($a->flags & TableAttrOf::FLAG_AS_LIST) {
				print "<li>" . substr($data->{$id}[$i], strpos($data->{$id}[$i], '$$') + 2) . "</li>";
			} else {
				$choices = explode('||', $a->choices);
				foreach ($choices as $c) { 
					if (strpos($c, $val . '$$') === 0) {
						// Hack ** 
						// 	$filter = str_replace('-0-', '][', $label) . "a".$a->attrOfId."][0]=".$val;
						$filter = str_replace('-0-', '][', $label) . "a".$a->attrOfId."[0]=".$val;
						$first = substr($label, 0, strpos($label, '-'));
					
						// $filter = str_replace($first . ']', $first, $filter);
						$filter = str_replace($first . '', $first, $filter);
						print $sep2 . JHTML::link(JRoute::_("&task=search&cid=&") . '/?' . $filter, (!LIVE && JRequest::getVar('showAll') ? "ID:$item->id " : '') . str_replace($val . '$$', '', $c), array('class' => "$label$id"));
						$sep2 = ', ';
					}
				}
				$sep2 = ', ';
			}
			$sep = empty($sep) ? "<br />" : $sep;
		}
		if ($multiple && $a->flags & TableAttrOf::FLAG_AS_LIST) {
			print "</ul>";
		} else if ($displayType == self::SUMMARY) {
			print "</span>";
		} else {
			print "</p>";
		}
    }
	
	function displayAttrInput($a, $data, $label = '') {
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
					$this->displayAttrInput($a2, $data->{$id}[$i], $label . ($label ? "[$id][$i]" : "{$id}[$i]"));
					print "</div>";
				}
			}
			if (!empty($a->inputLabel)) {
				print "</fieldset>";
			}
			return;
		}
		if (!$multiple && !$derived) {
			print "<p class=\"$label" . ($label ? '-' : '') . "$id\">";
		}
		$sep	= "";
		$max	= $multiple && !$derived && $a->typeId < 100 ? max(2, count($vals) + 1) : 1;
		for ($i = 0; $i < $max; $i++) {
			$name	= $label . ($label ? "[$id][$i]" : "{$id}[$i]");
			$val	= is_object($data->{$id}[$i]) ? $data->{$id}[$i]->id : $data->{$id}[$i];
			if ($a->typeId != 7) print "$sep<label for=\"$name\">$a->inputLabel</label><br />";
			if (!$derived && $a->typeId == 1) { 
				print "<textarea name=\"$mame\" id=\"$name\" rows=\"4\" cols=\"45\">$val</textarea>" . (!$derived ? '</p>' : '');
			} else if (!$derived && $a->typeId == 2) {
				if ($a->validation == V_DATE) {
					$val = date('m/d/Y', intval($val));
				}
				print "<input type=\"text\" name=\"$name\" id=\"$name\" value=\"$val\" size=\"45\" />" . (!$derived ? '</p>' : '');
			} else if (!$derived && $a->typeId == 5) {
				print "<input type=\"file\" id=\"$name\" name=\"$name\" /> Current: <a href=\"./components/"
					. strtolower(JRequest::getWord('option')) . "/files/$val\">$val</a> "
					. "<input type=\"checkbox\" id=\"$name-d\" name=\"{$name}[delete] value=\"1\" /> "
					. "<label for=\"$name-d\">Remove</label>" . (!$derived ? '</p>' : '');
			} else if (!$derived && $a->typeId == 6) {
				print "<input type=\"checkbox\" id=\"$name\" name=\"{$name}\" value=\"No\" /> "
					. "<label for=\"$name\">Don't e-mail me</label>" . (!$derived ? '</p>' : '');
			} else if (!$derived && $a->typeId == 7) {
				print "<input type=\"hidden\" name=\"$name\" value=\"" . date('Y-m-d') . "\" />" . (!$derived ? '</p>' : '');
			} else {
				$choices	= explode('||', $a->choices);
				if (is_numeric($val)) {
					$val = intval($val);
				}
			// Hack ** 	print $multiple ? "<ul class=\"choices\">" : "<select id=\"$name\" name=\"$name\"><option value=\"\"></option>";
				print $multiple && ($a->attrOfId != 63) ? "<ul class=\"choices\">" : "<select id=\"$name\" name=\"$name\"><option value=\"\"></option>";
				foreach ($choices as &$c) {
					$c		= explode('$$', $c);
					$cVal	= $c[0];
					$cLabel	= $c[1]; 
					// Hack ** if ($multiple) {
					if ($multiple && ($a->attrOfId != 63)) {
						$checked = is_array($val) && in_array($cVal, $val) ? 'checked="checked" ' : '';
			// Hack ** --removed multi-select
				//	print "<li><input type=\"checkbox\" name=\"{$name}[]\" id=\"$name-$cVal\" value=\"$cVal\" $checked/> <label for=\"$name-$cVal\">$cLabel</label></li>";
						print "<li><input type=\"radio\" name=\"{$name}\" id=\"$name-$cVal\" value=\"$cVal\" $checked/> <label for=\"$name-$cVal\">$cLabel</label></li>";
					} else {
						$selected = $cVal == $val ? ' selected="selected"' : ''; 
						print "<option value=\"$cVal\"$selected>$cLabel</option>";
					}
				}
				// Hack ** print $multiple ? "</ul>" : "</select>";
				print $multiple && ($a->attrOfId != 63) && ($a->attrOfId != 59) ? "</ul>" : "</select>";
			}
			$sep = "<br />";
		}
    }

	function getTypeIdByName($type) {
		return $this->getModel('Type')->getIdByName($type);
	}
} ?>
