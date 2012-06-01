<?php defined('_JEXEC') or die('Restricted access'); $user =& JFactory::getUser();
if(isset($this->program->id)) unset($_SESSION['cidtemp']);
	$rankem ="";
foreach ($this->rankings as $k => $v) {
	$rankem .= "rankem($v,5); \n"; }
	
$js = 'function validator() {
// copyright 2002 Kent Rauch

 // global declaration
 badrank = false;


 ' . $rankem . '

 if (badrank) {
  // this is a "phony submit" for testing purposes
 
 // document.write("Correct the duplicate ranking.");
 }

}

// ---------------------------------------------------------
// Validate ranking questions: each value used exactly once.

function rankem(question, q_size) {


 var aLert1 = "";
 var aLert2 = "";

 // supports up to 26 items to be ranked -- extend this array to increase
 var cal = "a.b.c.d.e.f.g.h.i.j.k.l.m.n";

 cal = cal.split(".");

 var a = 0;

 var irate = "rink" + question;

 eval(irate + " = new Object();");

 var myrate = "";

 for (var x = 0; x < q_size; ++x) { 
  myrate = "q"+question+ "-" + cal[x];

  eval(irate + "[" + x + "] = document.getElementById(\'" + myrate + "\').selectedIndex");

  if (eval(irate + "[" + x + "]")) {
   ++a;
   for (var y = 0; y < x; ++y) {
    if (eval(irate + "[" + y + "]") == eval(irate + "[" + x + "]")) {
     aLert1 = "Ranking question: please use each ranking only once.\n";
 
    }
   }
  }
 }

 

 var aLert = aLert1 + aLert2;

 if (aLert) {
  alert(aLert);
  badrank = true;
 }


}
';
$document = &JFactory::getDocument();

$document->addScriptDeclaration($js);
	if ($user->guest) {
		echo "<p>&nbsp;</p><p>&nbsp;</p></p><p>You need to log in to edit this form.  Click on the \"Log In here\" link at the bottom of this page.</p><p>&nbsp;</p><p>&nbsp;</p>";
	} else { $task = JRequest::getVar('task');
		echo $task == 'add' ? "<h2>Contribute a new program</h2>" : "<h2> Edit your program, " . $this->program->q9 . "</h2>";
		?><jdoc:include type="message" />
<form action="./index.php?option=com_programsdatabase" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php 
$group	= '';
foreach ($this->questions as $q) {
	$id		= "q$q->id";
	$val	= JRequest::getVar($id, $this->program->$id);
	if ($group != $q->grouping) {
		if ($group != '') {
			print "</fieldset>";
		}
		if ($q->grouping != '') {
			print "<fieldset><legend>$q->grouping</legend>";
		}
		$group = $q->grouping;
	}
	
	if ($q->typeId != 3 && $q->typeId != 4) {
		print "<p id=\"$id-p\">";
	}
	if ($q->inputLabel) {
		print "<label class=\"primary\" for=\"$id\">$q->inputLabel</label>";
	}
	switch ($q->typeId) {
		case 1:	print "<textarea name=\"$id\" id=\"$id\" rows=\"2\" cols=\"40\">$val</textarea></p>"; break;
		case 2:	print "<input type=\"text\" name=\"$id\" id=\"$id\" value=\"$val\" /></p>";			  break;
		case 3:
		case 4:
			print "<ul class=\"choices\" id=\"$id\">";
			$choices	= explode('||', $q->choices);
			if (is_array($val)) { // Posted Value
				$temp = 0;
				foreach ($val as $v) {
					$temp += $v;
				}
				$val = $temp;
			} else if (JRequest::getVar('task') != 'save' && JRequest::getVar('task') != 'savenew') {
				$val = explode('||', $val);
			} else if (is_numeric($val)) {
				$val = intval($val);
			}
			$cVal		= 1;
			foreach ($choices as $c) { // testing... print "Val: "; print_r($val);  print " Cval: " . $cVal; print " C: " .$c;
				$type 	 = $q->typeId == 3 ? 'radio' : 'checkbox';
				$checked = ($q->typeId == 3 && $cVal == $val) || ($q->typeId == 4 && !is_array($val) && ($cVal & $val) == $cVal) || (is_array($val) && in_array($c, $val)) ? 'checked="checked" ' : '';
				print "<li><input type=\"$type\" name=\"$id" . ($q->typeId == 4 ? '[]' : '') . "\" id=\"$id-$cVal\" value=\"$cVal\" $checked/> <label for=\"$id-$cVal\">$c</label></li>";
				$cVal = $q->typeId == 3 ? $cVal + 1 : $cVal << 1;
			}
			print "</ul>";
			break;
			
			case 5: print "<br /><select size=\"1\" name=\"$id\" id=\"$id\">";
	$options =  '<option value="0">All</option>
	  <option>AB</option><option>BC</option><option value="AK">AK</option>
	<option value="AL">AL</option>
	<option value="AR">AR</option>
	<option value="AZ">AZ</option>
	<option value="CA">CA</option>
	<option value="CO">CO</option>
	<option value="CT">CT</option>
	<option value="DC">DC</option>
	<option value="DE">DE</option>
	<option value="FL">FL</option>
	<option value="GA">GA</option>
	<option value="HI">HI</option>
	<option value="IA">IA</option>
	<option value="ID">ID</option>
	<option value="IL">IL</option>
	<option value="IN">IN</option>
	<option value="KS">KS</option>
	<option value="KY">KY</option>
	<option value="LA">LA</option>
	<option value="MA">MA</option>
	<option value="MD">MD</option>
	<option value="ME">ME</option>
	<option value="MI">MI</option>
	<option value="MN">MN</option>
	<option value="MO">MO</option>
	<option value="MS">MS</option>
	<option value="MT">MT</option>
	<option value="NC">NC</option>
	<option value="ND">ND</option>
	<option value="NE">NE</option>
	<option value="NH">NH</option>
	<option value="NJ">NJ</option>
	<option value="NM">NM</option>
	<option value="NV">NV</option>
	<option value="NY">NY</option>
	<option value="OH">OH</option>
	<option value="OK">OK</option>
	<option value="OR">OR</option>
	<option value="PA">PA</option>
	<option value="RI">RI</option>
	<option value="SC">SC</option>
	<option value="SD">SD</option>
	<option value="TN">TN</option>
	<option value="TX">TX</option>
	<option value="UT">UT</option>
	<option value="VA">VA</option>
	<option value="VT">VT</option>
	<option value="WA">WA</option>
	<option value="WI">WI</option>
	<option value="WV">WV</option>
	<option value="WY">WY</option></select>';
	$selopt = str_replace('value="' . $val .'"', 'value="' . $val .'" selected="selected"',$options);
	print $selopt;
	break;
			
	
		case 6:		
		print "<ul class=\"choices\" id=\"$id\">";
			$choices	= explode('||', $q->choices);
			if (is_array($val)) { 
				$val = $val;
				
			
			} else if (JRequest::getVar('task') != 'saveProgram') {
			$val = explode('||', $val);
			} else if (!is_array($val)) {
				$val = explode('||', $val);
			} 
			
$xx = 97;
			$cVal		= chr($xx);
			
			$num = 1;	// print_r($val);
			foreach ($choices as $c) {  
			
			print "<li><select name=\"" . $id ."[]\" id=\"$id-$cVal\"><option value=\"\"></option><option value=\"1: $c\""; print (in_array( "1: $c", $val)) ? ' selected="selected"' : ''; print ">1</option><option value=\"2: $c\""; print (in_array( "2: $c", $val)) ?' selected="selected"' : ''; print ">2</option><option value=\"3: $c\""; print (in_array( "3: $c", $val)) ?' selected="selected"' : ''; print ">3</option></select>  <label for=\"$id-$cVal\">$c</label></li>";
			$xx ++;
			$cVal		= chr($xx);
			 }
			print "</ul>";
		
		break;	
			case 7:	
			print "<br /><br /><input type=\"file\" id=\"$id\" name=\"$id\" />"; if($val) print " Current: <a href=\"/administrator/components/"
					. strtolower(JRequest::getWord('option')) . "/files/".strtolower($val)."\">$val</a> "
					. "<input type=\"checkbox\" id=\"$id-d\" name=\"{$id}[delete]\" value=\"1\" /> "
					. "<label for=\"$name-d\">Remove</label></p>";	
		break;	
	}
} ?>
<?php echo JHTML::_('form.token'); ?>
<p style="color: #7E1B19"><strong>Thank you for completing the survey. Please check to make sure your answers are correct and you’ve answered every question that applies to your program. Then hit the "submit" button below.</strong><br /><input style="margin-top: 12px" type="submit" value="Submit"  onClick="validator();" /></p>
 <input type="hidden" name="option" value="com_programsdatabase" />
<?php if(isset($this->program->id)): ?>

 <input type="hidden" name="cid" value="<?php echo $this->program->id; ?>" /><?php else: ?>
<input type="hidden" name="cid" value="<?php echo $_SESSION['cidtemp']; ?>" /><?php unset($_SESSION['cidtemp']);
endif; ?>
<?php  if ($task == "addfront"): ?>
 <input type="hidden" name="task" value="save" /><?php else: ?><input type="hidden" name="task" value="savenew" /><?php endif ?>
</form> <?php } ?>