<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="./" method="post" name="adminForm" id="adminForm">
 <div id="preview"><h2>Survey Preview</h2><div id="sPreview"></div><h2>Profile Preview</h2><div id="pPreview"></div></div>
 <div class="col100">
  <fieldset class="adminform">
   <legend><?php echo JText::_('Basic Information'); ?></legend>
   <p><label for="type">Type:</label>
      <select name="typeId" id="type" onchange="javascript:updateChoices(event); preview(event);">
      <?php
      	$this->question->typeId = JRequest::getVar('typeId', $this->question->typeId, '', 'int');
      	foreach ($this->types as $type) {
      		print "<option value=\"$type->id\"" . ($this->question->typeId == $type->id ? ' selected="selected"' : '' ) . ">$type->label</option>";
      	}?>
      </select></p> 
   <p><label for="inputLabel">Text Shown in Survey:</label><br />
      <textarea name="inputLabel" id="inputLabel" rows="4" cols="38" onblur="javascript:preview(event);"><?php echo JRequest::getVar('inputLabel', $this->question->inputLabel); ?></textarea></p>
   <p><label for="displayLabel">Text Shown in Profile:</label><br />
      <textarea name="displayLabel" id="displayLabel" rows="4" cols="38" onblur="javascript:preview(event);"><?php echo JRequest::getVar('displayLabel', $this->question->displayLabel); ?></textarea></p>
   <p><label for="compareLabel">Text Shown in Comparison Chart:</label><br />
      <textarea name="compareLabel" id="compareLabel" rows="4" cols="38" onblur="javascript:preview(event);"><?php echo jRequest::getVar('compareLabel', $this->question->compareLabel); ?></textarea></p>
   <p><input type="checkbox" id="required" name="required" value="1<?php echo JRequest::getVar('required', $this->question->required, 'int') ? '" checked="checked' : ''; ?>" /> <label for="required">This question is required.</label></p>
   <p><input type="checkbox" id="compare" name="compare" value="1<?php echo JRequest::getVar('compare', $this->question->compare, 'int') ? '" checked="checked' : ''; ?>" /> <label for="compare">Show this question in the comparison chart.</label></p>
   <p><input type="checkbox" id="internal" name="internal" value="1<?php echo JRequest::getVar('internal', $this->question->internal, 'int') ? '" checked="checked' : ''; ?>" /> <label for="internal">For internal use only.</label></p>
   <p><label for="newOrdering">Position:</label>
      <input type="hidden" name="ordering" value="<?php echo $this->question->ordering; ?>" />
      <select id="newOrdering" name="newOrdering">
      <?php
      	$this->question->ordering = JRequest::getVar('newOrdering', $this->question->ordering, '', 'int');
      	for ($i = 1; $i <= $this->maxQ; $i++) {
			print "<option" . ($this->question->ordering == $i ? ' selected="selected"': '') .">$i</option>";
		}
		if ($this->question->id == 0) {
			print "<option selected=\"selected\">$i</option>";
		}
	  ?></select></p>
  </fieldset>
  <fieldset>
  <legend>Grouping:</legend>
  <?php $grouping = JRequest::getVar('grouping', $this->question->grouping, 'post'); ?>
  <p><label for="g-new">New:</label> <input type="text" id="g-new" name="grouping" value="<?php echo !in_array($grouping, $this->groupings) ? $grouping : ''; ?>" /></p>
   <?php
	if (is_array($this->groupings) && count($this->groupings) > 0) {
		print '<ul style="list-style:none;">';
		foreach ($this->groupings as $group) {
			print "<li><input type=\"radio\" id=\"g-$group->ordering\" name=\"grouping\" value=\"$group->grouping\" "
				. ($group->grouping == $grouping ? 'checked="checked" ' : '')
				. "/> <label for=\"g-$group->ordering\">$group->grouping</label></li>";
		}
		print "</ul>";
	} ?>
  
  </fieldset>
  <fieldset id="validation">
  <?php $this->question->validation = JRequest::getVar('validation', $this->question->validation, 'post', 'int'); ?>
   <legend>Validation:</legend>
   <p>Select the type of validation for this question.  If the question is not a spcific piece of information like a phone number or address then you do not need to select any type of validation.</p>
   
<input type="radio" id="v0" name="validation"
	value="<?php echo V_TEXT . ($this->question->validation == V_TEXT ? '" checked="checked' : ''); ?>" />
   <label for="v0">None</label><br />
   <input type="radio" id="v1" name="validation"
	value="<?php echo V_INT . ($this->question->validation == V_INT ? '" checked="checked' : ''); ?>" />
   <label for="v1">Integer, e.g. 123</label><br />
   <input type="radio" id="v2" name="validation"
	value="<?php echo V_FLOAT . ($this->question->validation == V_FLOAT ? '" checked="checked' : ''); ?>" />
   <label for="v2">Decimal, e.g. 123.95</label><br />
   <input type="radio" id="v3" name="validation"
	value="<?php echo V_EMAIL . ($this->question->validation == V_EMAIL ? '" checked="checked' : ''); ?>" />
   <label for="v3">Email address</label><br />
   <input type="radio" id="v4" name="validation"
	value="<?php echo V_PHONE . ($this->question->validation == V_PHONE ? '" checked="checked' : ''); ?>" />
   <label for="v4">Phone Number</label><br />
   <input type="radio" id="v9" name="validation"
	value="<?php echo V_URL . ($this->question->validation == V_URL ? '" checked="checked' : ''); ?>" />
   <label for="v4">Website Address (URL)</label><br />
   <input type="radio" id="v5" name="validation"
	value="<?php echo V_NAME . ($this->question->validation == V_NAME ? '" checked="checked' : ''); ?>" />
   <label for="v5">Name, Title, etc. E.g., John Doe, ACME Company.</label><br />
   <input type="radio" id="v6" name="validation"
	value="<?php echo V_ADDR . ($this->question->validation == V_ADDR ? '" checked="checked' : ''); ?>" />
   <label for="v6">Street Address or City</label><br />
   <input type="radio" id="v7" name="validation"
	value="<?php echo V_STATE . ($this->question->validation == V_STATE ? '" checked="checked' : ''); ?>" />
   <label for="v7">State</label><br />
   <input type="radio" id="v8" name="validation"
	value="<?php echo V_ZIP . ($this->question->validation == V_ZIP ? '" checked="checked' : ''); ?>" />
   <label for="v8">Zip Code</label>
  </fieldset>
  <fieldset id="choices">
   <legend>Choices</legend>
   <ol>
    <?php
    $choices = JRequest::getVar('choices', $this->question->choices, 'post', 'array');
	$max	 = count($tchoices) > 14 ? count($choices) + 1 : 14;
	$choice	 = reset($choices);
	
	for ($i = 0; $i < $max; $i++) {
		if (is_array($choice)) {
			$choice = JArrayHelper::toObject($choice);
		} else if (!isset($choice->id) || !$choice->id) {
			$choice->label = '';
			$choice->id = 0;
		} 
		print "<li><input type=\"text\" id=\"c$i\" name=\"choices[$i][label]\" value=\"$choice->label\" size=\"60\" onblur=\"javascript:preview(event);\" />"
			. "<input type=\"hidden\" name=\"choices[$i][id]\" value=\"$choice->id\" />"
			. ($choice->label ? " <input type=\"checkbox\" name=\"choices[$i][delete] id=\"choice{$i}delete\" value=\"1\" /> <label for=\"choice{$i}delete\">Delete</label>" : '')
			. "</li>"; 
		$choice = next($choices);
	} ?>
   </ol>
  </fieldset>


 </div>
 <input type="hidden" name="option" value="com_programsdatabase" />
 <input type="hidden" name="cid" value="<?php echo $this->question->id; ?>" />
 <input type="hidden" name="task" value="" />
</form>
<script type="text/javascript">
	function updateChoices(e) {
		var elm = (typeof e.srcElement) != 'undefined' ? e.srcElement : e.target, choices = document.getElementById('choices'), validation = document.getElementById('validation');
		choices.style.display = (elm.options[elm.selectedIndex].value < 3 ) ? 'none' : 'block';
		validation.style.display = elm.options[elm.selectedIndex].value != 2 ? 'none' : 'block'; 
	}
 	window.onload = function () { updateChoices({'srcElement':document.getElementById('type')}); preview({}); };
 	function preview(event) {
		var /*elm = event ? (event.srcElement ? event.srcElement : event.target) : window.event.srcElement,*/ type = document.getElementById('type'),
			inputLabel = document.getElementById('inputLabel').value, displayLabel = document.getElementById('displayLabel').value, survey = '', profile = '',
			sPreview = document.getElementById('sPreview'), pPreview = document.getElementById('pPreview'), choices =  document.getElementById('choices');
		type = type.options[type.selectedIndex].value, choice = null;
		if (type < 3) {
			sPreview.innerHTML = '<p>' + inputLabel + '<br />' + (type == 1 ? '<textarea id="ans" rows="2" cols="30" onkeyup="javascript:updatePreview(event);"></textarea>' : '<input type="text" id="ans" value=""  onkeyup="javascript:updatePreview(event);" />') + '</p>';
			//pPreview.inerHTML = '<p><strong>' + displayLabel + '</strong><br />Lorem Episum</p>';
		} else if (type == 3 || type == 4) {
			survey = '<p class="answer">' + inputLabel + '</p><ul>';			
			
			choice = document.getElementById('c0');
			for (var i = 1; choice !== null; i++) {
				if (choice && choice.value) {
					survey += '<li><input type="' + (type == 3 ? 'radio' : 'checkbox') + '" name="c" id="c-' + (i - 1) + '" value="" onclick="javascript:updatePreview(event);" /> <label for="c-' + (i - 1) + '">' + choice.value + '</label></li>';
				}
				choice = document.getElementById('c' + i);
			}
			sPreview.innerHTML = survey;
		}		
		 else if (type == 6) { 
			survey = '<p class="answer">' + inputLabel + '</p><ul>';			
			
			choice = document.getElementById('c0');
			
			for (var i = 1; choice !== null; i++) { 
				if (choice && choice.value) {
					survey += '<li><select name="c" id="c-' + (i - 1) + '" value="" onclick="javascript:updatePreview(event);" /><option></option><option>1</option><option>2</option><option>3</option></select> <label for="c-' + (i - 1) + '">' + choice.value + '</label></li>';
				}
				
				
				choice = document.getElementById('c' + i);
				
			}
				else if (type == 7) {
			sPreview.innerHTML = '<p>' + inputLabel + '<br />' +  '<input type="file" id="ans" value=""  onkeyup="javascript:updatePreview(event);" />') + '</p>';
			//pPreview.inerHTML = '<p><strong>' + displayLabel + '</strong><br />Lorem Episum</p>';
		}
		
			sPreview.innerHTML = survey;
		}		
 	}
 	function updatePreview(event) {
 	 	var sPreview = document.getElementById('sPreview'), pPreview = document.getElementById('pPreview'), type = document.getElementById('type');

		if (type.value == 1 || type.value == 2) {
			var ans = document.getElementById('ans').value;
			if (ans) {
				pPreview.innerHTML = '<p><strong>' + document.getElementById('inputLabel').value + '</strong><br />' + ans + '</p>'; 
			}
			return true;
		}
 	 	if (typeof sPreview.childNodes == 'undefined') {
			return false;
		}
		if (sPreview.childNodes[1].nodeName == 'ul' || sPreview.childNodes[1].nodeName == 'UL') {
			var list = sPreview.childNodes[1].childNodes, answer = '', multi = document.getElementById('type').selectedIndex == 2;
			for (var i in list) {
				if (parseInt(i, 10) == NaN || i == 'length' || i == 'item') {
					continue;
				}
				var input = list[i].childNodes[0], label = list[i].childNodes[2].innerHTML;
				if (input.checked) {
					if (multi) {
						answer = label;
						break;
					}
					answer += '<li>' + label + '</li>';
				}
			}
			pPreview.innerHTML = answer ? '<p>' + document.getElementById('displayLabel').value + (multi ? '<br />' + answer + '</p>' : '</p><ul>' + answer + '</ul>') : '';
			return true;
		}
 	}
</script>