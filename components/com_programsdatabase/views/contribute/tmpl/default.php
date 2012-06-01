<?php defined('_JEXEC') or die('Restricted access'); 
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
?>
<div class="programsdatabase">
<form   action="./index.php?option=com_programsdatabase&task=save" method="post" enctype="multipart/form-data">
<h1>Contribute a Program to the Database</h1>
<jdoc:include type="message" />
<?php
foreach ($this->questions as $q) {
	$id		= "q$q->id";
	$val	= JRequest::getVar($id, '');
	
	print "<a name=\"q$q->id\"></a><p class=\"answer" . (in_array($q->id, $this->errorIDs) ? ' error' : '')
		. "\"><label for=\"$id\">$q->inputLabel</label>";
	switch ($q->typeId) {
		case 1:	print "<br /><textarea name=\"$id\" id=\"$id\" row=\"5\" cols=\"40\">$val</textarea></p>";	break;
		case 2:	print "<br /><input type=\"text\" name=\"$id\" id=\"$id\" value=\"$val\" /></p>";			break;
		case 3: 
		case 4:
			$val = !is_array($val) ? array($val) : $val;
			$choices	= explode('||', $q->choices);
			$cVal		= 1;
			print "</p><ul>";
			foreach ($choices as $c) {
				$type 	 = $q->typeId == 3 ? 'radio' : 'checkbox';
				$checked = in_array($cVal, $val) ? 'checked="checked" ' : '';
				print "<li><input type=\"$type\" name=\"$id" . ($q->typeId == 4 ? '[]' : '') . "\" id=\"$id-$cVal\" value=\"$cVal\" $checked/> <label for=\"$id-$cVal\">$c</label></li>";
				$cVal = $q->typeId == 3 ? $cVal + 1 : $cVal << 1;
			}
			print "</ul>";
			break;
			case 5: print "<br /><select size=\"1\" name=\"$id\" id=\"$id\">
	 <option value=\"0\">All</option>
	  <option>AB</option><option>BC</option><option value=\"AK\">AK</option>
	<option value=\"AL\">AL</option>
	<option value=\"AR\">AR</option>
	<option value=\"AZ\">AZ</option>
	<option value=\"CA\">CA</option>
	<option value=\"CO\">CO</option>
	<option value=\"CT\">CT</option>
	<option value=\"DC\">DC</option>
	<option value=\"DE\">DE</option>
	<option value=\"FL\">FL</option>
	<option value=\"GA\">GA</option>
	<option value=\"HI\">HI</option>
	<option value=\"IA\">IA</option>
	<option value=\"ID\">ID</option>
	<option value=\"IL\">IL</option>
	<option value=\"IN\">IN</option>
	<option value=\"KS\">KS</option>
	<option value=\"KY\">KY</option>
	<option value=\"LA\">LA</option>
	<option value=\"MA\">MA</option>
	<option value=\"MD\">MD</option>
	<option value=\"ME\">ME</option>
	<option value=\"MI\">MI</option>
	<option value=\"MN\">MN</option>
	<option value=\"MO\">MO</option>
	<option value=\"MS\">MS</option>
	<option value=\"MT\">MT</option>
	<option value=\"NC\">NC</option>
	<option value=\"ND\">ND</option>
	<option value=\"NE\">NE</option>
	<option value=\"NH\">NH</option>
	<option value=\"NJ\">NJ</option>
	<option value=\"NM\">NM</option>
	<option value=\"NV\">NV</option>
	<option value=\"NY\">NY</option>
	<option value=\"OH\">OH</option>
	<option value=\"OK\">OK</option>
	<option value=\"OR\">OR</option>
	<option value=\"PA\">PA</option>
	<option value=\"RI\">RI</option>
	<option value=\"SC\">SC</option>
	<option value=\"SD\">SD</option>
	<option value=\"TN\">TN</option>
	<option value=\"TX\">TX</option>
	<option value=\"UT\">UT</option>
	<option value=\"VA\">VA</option>
	<option value=\"VT\">VT</option>
	<option value=\"WA\">WA</option>
	<option value=\"WI\">WI</option>
	<option value=\"WV\">WV</option>
	<option value=\"WY\">WY</option>";
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
			print "<br /><br /><input type=\"file\" id=\"$id\" name=\"$id\" /> ";
		break;	
	}
} ?>
 <p><input type="submit" value="Contribute" onClick="validator();" /></p>
<?php echo JHTML::_('form.token'); ?>
 <input type="hidden" name="id" value="0" />
</form>
</div>