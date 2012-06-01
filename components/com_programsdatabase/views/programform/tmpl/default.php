<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$document =& JFactory::getDocument();
$document->addCustomTag( '<script type="text/javascript">
jQuery(document).ready(function() {   
 jQuery(\'.stateName\').click(function(){
   jQuery("#state").val(jQuery(this).attr(\'id\'));

});
});</script>' );
 $document->addCustomTag( '<script type="text/javascript">jQuery(document).ready(function(){
            jQuery("#SignupForm").formToWizard({ submitButton: \'SaveAccount\' })
       
/*    jQuery(function()
    {
    jQuery(\'#dual\').click(function()
    {
    jQuery(\'#res1\').attr(\'disabled\', jQuery(\'#dual\').is(\':checked\'));
    });
    }); */


jQuery(\'#dual\').bind(\'click\', function () {
   

    if (jQuery(this).attr(\'checked\')) {
        jQuery(\'#res1\').attr(\'disabled\', true);
 jQuery(\'#res1Label\').addClass(\'greyOut\');
jQuery(\'.caveat\').css(\'display\', \'inline\');
    } else {
        jQuery(\'#res1\').removeAttr(\'disabled\');
jQuery(\'#res1Label\').removeClass(\'greyOut\');
jQuery(\'.caveat\').css(\'display\', \'none\');
    }
});


 });

       
       
        window.onload=function(){
            var selO = document.getElementsByTagName(\'select\');
            var optionsArray = new Array(); //this will be an array of arrays.  Each row contains the options from each select list
            for(i=0; i < selO.length; i++){
                optionsArray[i] = new Array();
                for(j=1; j < selO[i].length; j++){
                    optionsArray[i].push(selO[i].options[j].value);
                }
            }

            //output the contents of optionsArray for testing purposes
           
             var   stateId = \'\';
            for(i=0; i < optionsArray.length; i++){
                for(j=0; j < optionsArray[i].length; j++){
             	var eaState =  optionsArray[i][j];
                  
                  stateId =  document.getElementById(eaState);
                   if (stateId != null) { stateId.addClass("blue");}
                   
                  
                }
              
            }
          
        }

</script>' );
function generateCheckbox($var, $label) {
	return "<input type=\"checkbox\" id=\"$var\" name=\"$var\" value=\"1\" /> <label for=\"$var\">$label</label>";
} ?>
<div class="programsdatabase">
 
 <form class="search" action="<?php echo JRoute::_('index.php?task=search'); ?>" method="p" enctype="application/x-www-form-urlencoded" id="SignupForm">

  <jdoc:include type="message" />
<fieldset>
            <legend>Where?</legend>
<h4>Click on the name of the state where you want to go to college, or select it from the dropdown list.</h4>
  <p><span class="instructions">Or, select "All" states for more results. (State names in grey mean that we have no information on programs in those states.)</span><br /><br />

	
	<label for="state">State:</label>
		<select size="1" name="state" id="state">
	  <option value="0">All</option>
	  <?php foreach ($this->states as $s) { print "<option value=\"$s->state\">$s->statename</option>"; } ?>
	 </select>

  </p>
<div id="mapholder"><img src="/components/com_programsdatabase/views/programform/tmpl/images/thinkcollege_state_map.gif" /><div class="stateName" id="AL"></div>
<div class="stateName" id="AK"></div>
<div class="stateName" id="AZ"></div><div class="stateName" id="AR"></div><div class="stateName" id="CA"></div><div class="stateName" id="CO"></div><div class="stateName" id="CT"></div><div class="stateName" id="DE"></div><div class="stateName" id="FL"></div><div class="stateName" id="GA"></div><div class="stateName" id="HI"></div><div class="stateName" id="ID"></div><div class="stateName" id="IL"></div><div class="stateName" id="IN"></div><div class="stateName" id="IA"></div><div class="stateName" id="KS"></div><div class="stateName" id="KE"></div><div class="stateName" id="LA"></div>
<div class="stateName" id="ME"></div>
<div class="stateName" id="MD"></div><div class="stateName" id="MA"></div><div class="stateName" id="MI"></div><div class="stateName" id="MN"></div><div class="stateName" id="MS"></div><div class="stateName" id="MO"></div><div class="stateName" id="MT"></div><div class="stateName" id="NE"></div><div class="stateName" id="NV"></div><div class="stateName" id="NH"></div><div class="stateName" id="NJ"></div>
<div class="stateName" id="NM"></div><div class="stateName" id="NY"></div><div class="stateName" id="NC"></div><div class="stateName" id="ND"></div><div class="stateName" id="OH"></div>
<div class="stateName" id="OK"></div><div class="stateName" id="OR"></div><div class="stateName" id="PA"></div><div class="stateName" id="RI"></div><div class="stateName" id="SC"></div><div class="stateName" id="SD"></div>
<div class="stateName" id="TN"></div><div class="stateName" id="TX"></div><div class="stateName" id="UT"></div><div class="stateName" id="VT"></div><div class="stateName" id="VA"></div><div class="stateName" id="WA"></div>
<div class="stateName" id="WV"></div><div class="stateName" id="WI"></div><div class="stateName" id="WY"></div>
<div class="stateName" id="DC"></div></div><p id="step0commands2" class="heightfix2"></p>
 </fieldset>
	<fieldset>
            <legend>What kind of school?</legend>
            <span class="instructions">Check the type of school you want to go to. (Select one, or select none to get more results.)</span>
           <ul class="no-bullets">
	 <li><input type="checkbox" id="2year" name="2year" value="1" /> <label for="2year">Two year college</label></li>
	 <li><input type="checkbox" id="4year" name="4year" value="1" /> <label for="4year">Four year college or university</label></li>
	 <li><input type="checkbox" id="techSchool" name="techSchool" value="1" /> <label for="techSchool">Technical/Trade School</label></li>
  </ul><p id="step1commands2" class="heightfix2"></p>
        </fieldset>
        <fieldset>
            <legend>Attending During or After High School?</legend>
            <ul class="no-bullets">
<span class="instructions">Check one:</span>
	 <li><input type="checkbox" id="dual" name="dual" value="1" /> <label for="dual">I'll go while I'm still in high school</label></li>

	 <li><input type="checkbox" id="adult" name="adult" value="1" /> <label for="adult">I'll be done with high school when I go to college</label></li>

	 <li><input type="checkbox" id="noneof" name="noneof" value="1" /> <label for="noneof">Not sure</label></li>
	
  </ul><p id="step2commands2" class="heightfix2"></p>
        </fieldset>
<fieldset>
            <legend>Live on campus or off?</legend>
<span class="instructions">Check one:</span>
            <ul class="no-bullets">
	
	  <li><input type="radio" id="res1" name="res" value="1" /> <label for="res1" id="res1Label">I want to live on campus</label> <span class="caveat instructions">"On campus" not available, since you indicated you would go to college while still in high school.</span></li>
	<li><input type="radio" id="res2" name="res" value="0" /> <label for="res2">I'll live at home or make other arrangements.</label></li>
	<li><input type="radio" id="res3" name="res" value="0" /> <label for="res2">Not sure.</label></li>
	
  </ul><p id="step3commands2" class="heightfix2"></p>
        </fieldset>
        <p id="submitP">
            <input id="SaveAccount" type="submit" value="Show me my options" />
        </p>
	
  
 </form>

</div>
