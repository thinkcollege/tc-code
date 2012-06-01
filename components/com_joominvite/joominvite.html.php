<?php

/**

* @version $Id: joominvite.php 

* @package JoomInvite

* @copyright Copyright (C) 2008 Anikendra Das Choudhury. All rights reserved.

* Credit: Yves Christian

* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL

*

*/



class HTML_JoomInvite{

	

	function front($option){

	global $mosConfig_live_site;

	

	$imgpath=$mosConfig_live_site.'/components/'.$option.'/images/';

	

		$footer = "<div align=\"center\" style ='font-size:xx-small; font-weight: bold; valign:bottom;'>Powered by <a href=\"http://www.barakbangla.com\">JoomInvites</a></div>";

		$link = "index.php?option=$option";

		?>

	<script language = "Javascript">

/**

 * DHTML email validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)

 * Credit : Yves Christian

 */



function echeck(str) {



		var at="@"

		var dot="."

		var lat=str.indexOf(at)

		var lstr=str.length

		var ldot=str.indexOf(dot)

		

		if (str.indexOf(at)==-1){
		   alert("Invalid E-mail Address")
		   return false
		}



		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){

		   alert("<?php echo _INVALID_EMAIL?>")

		   return false

		}



		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){

		    alert("<?php echo _INVALID_EMAIL?>")

		    return false

		}



		 if (str.indexOf(at,(lat+1))!=-1){

		    alert("<?php echo _INVALID_EMAIL?>")

		    return false

		 }



		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){

		    alert("<?php echo _INVALID_EMAIL?>")

		    return false

		 }



		 if (str.indexOf(dot,(lat+2))==-1){

		    alert("<?php echo _INVALID_EMAIL?>")

		    return false

		 }

		

		 if (str.indexOf(" ")!=-1){

		    alert("<?php echo _INVALID_EMAIL?>")

		    return false

		 }



 		 return true					

	}





function ValidateForm(){



	var emailID=document.loginForm.email_box;

	

	if ((emailID.value==null)||(emailID.value=="")){

		alert("<?php echo _NO_EMAIL?>");

		emailID.focus();

		return false;

	}

	if (echeck(emailID.value)==false){

		emailID.value="";

		emailID.focus();

		return false;

	}

	

	if(document.loginForm.password_box.value =='') {

		alert("<?php echo _NO_PASS?>");

		return false;

	}

	

	var chks = document.getElementsByName('provider_box');

	var hasChecked = false;

	return true

 }

</script>



<style type="text/css">

				<!--

				td.importin {

							border-left-width: 1px; 

							border-right: 1px solid #D8D8D8; 

							border-top-width: 1px; 

							border-bottom:1px solid #D8D8D8;

							border-top:1px solid #D8D8D8;

							border-left: 1px solid #D8D8D8;

							text-align:left;

							

						}

						

				table.importin2 td.importin2 {

							background-color:#CCCCCC

							border-left-width: 1px; 

							border-right: 1px solid #333; 

							border-top-width: 1px; 

							border-bottom:1px solid #333;

							border-top:1px solid #333;

							border-left: 1px solid #333;

							text-align:left;

							

						}

						

				

				-->

		</style>

		

		<div align="center">

<form action="<?php echo sefRelToAbs($link);?>" method="POST"  name="loginForm" onSubmit="return ValidateForm()">

	<br  />&nbsp;

	<table border="0" align="center" cellpadding="2" cellspacing="0" width="550">

	<tr>

			<td colspan='3' bgcolor="#F2F2F2" align="center"> <b><font face="Arial" size="4" color="#333333">

			<div align="center"> <?php echo _INTRO?><br />&nbsp;</div>

							</font></b>

			</td>

	  <tr>

	  <td colspan="3" align="center" class="importin"><font face="Arial" size="2" color="#333333" ><div align="center"><br /><?php echo _INSTRUCT ?>

	    &nbsp; </div></font> </td>

	  </tr>

	  <tr valign="middle" height="40">

	  <td width="200"  class="importin"><?php echo _USERNAME?> </td>

	  	<td colspan="2"  class="importin"><input class='thTextbox' type='text' name='email_box' value='' size="40"></td>

	  </tr>

	  <tr  valign="middle" height="40px">

	  <td class="importin" ><?php echo _PASS?> </td>

	      <td colspan="2"  class="importin"><input class='thTextbox' type='password' name='password_box' value='' size="40"></td>

	  </tr>

	  <tr>

	  <td valign="top" class="importin"><?php echo _PROVIDER?> </td>

	      <td colspan="2" valign="top" class="importin">

		  	<table width="270" border="0" cellspacing="5" cellpadding="5" class="importin2">
		  	
<tr><td><select class='thSelect' name='provider_box'><option value=''></option><option disabled>Email Providers</option><option value='aol'>AOL</option>
<option value='fastmail'>FastMail</option><option value='gmail'>GMail</option><option value='gmx_net'>GMX.net</option>
<option value='hotmail'>Live/Hotmail</option><option value='indiatimes'>IndiaTimes</option><option value='katamail'>KataMail</option>
<option value='libero'>Libero</option><option value='lycos'>Lycos</option><option value='mail_com'>Mail.com</option>
<option value='mail_ru'>Mail.ru</option><option value='operamail'>OperaMail</option><option value='rambler'>Rambler</option>
<option value='rediff'>Rediff</option><option value='yahoo'>Yahoo!</option><option value='yandex'>Yandex</option>
<option disabled>Social Networks</option><option value='facebook'>Facebook</option><option value='flickr'>Flickr</option>
<option value='flixster'>Flixster</option><option value='friendster'>Friendster</option><option value='hi5'>Hi5</option>
<option value='lastfm'>Last.fm</option><option value='linkedin'>LinkedIn</option><option value='myspace'>MySpace</option>
<option value='orkut'>Orkut</option><option value='twitter'>Twitter</option></select></td></tr>

</table>	  			

	    </td>

	  </tr>

	  <tr>

	  	  <td colspan="3" align="center"> 

		  <div align="center"><br />&nbsp;

		  <input type="submit" name="submit" value="      Import My Contacts     " /> <br />&nbsp;<br />&nbsp;

		  </div>

		  </td>

	  </tr> 
	  <tr>

	  	 <td colspan="3" align="center"><small>We won't store your password.</small></td>

	  </tr>    
	  </table>
	  	<input type="hidden" name="option" value="<?php echo $option;?>">
		<input type="hidden" name="task" value="fetch">
	</form>
	</div>
	<div align="center">
	  <table>
	  <td><img src="<?php echo $imgpath; ?>/gmail.png" alt="Gmail" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/yahoo.png" alt="Yahoo" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/hotmail.png" alt="Hotmail" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/aol.png" alt="Aol" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/operamail.png" alt="OperaMail" style="float:right" /></td>
	  </tr>   
	  <tr>
	  <td><img src="<?php echo $imgpath; ?>/rediff.png" alt="Rediffmail" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/katamail.png" alt="KataMail" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/facebook.png" alt="Facebook" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/orkut.png" alt="Orkut" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/myspace.png" alt="MySpace" style="float:right" /></td>
	  </tr>   
	  <tr>
	  <td><img src="<?php echo $imgpath; ?>/fastmail.png" alt="FastMail" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/flickr.png" alt="Flickr" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/flixster.png" alt="Flixster" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/friendster.png" alt="Friendster" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/libero.png" alt="Libero" style="float:right" /></td>
	  </tr>   
	  <tr>
	  <td><img src="<?php echo $imgpath; ?>/lastfm.png" alt="Lastfm" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/lycos.png" alt="Lycos" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/hi5.png" alt="Hi5" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/twitter.png" alt="Twitter" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/yandex.png" alt="Yendex" style="float:right" /></td>
	  </tr>   
	  <tr>
	  <td><img src="<?php echo $imgpath; ?>/indiatimes.png" alt="IndiaTimes" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/gmx.png" alt="Gmx" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/mail_com.png" alt="mail_com" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/mail_ru.png" alt="mail_ru" style="float:right" /></td>
	  <td><img src="<?php echo $imgpath; ?>/linkedin.png" alt="linkedIn" style="float:right" /></td>
	  </tr>   
	  <tr><td align="center" colspan="5"><img src="<?php echo $imgpath; ?>/rambler.png" alt="rambler"></td></tr>
  </table>
</div>
	<?php

	echo $footer;

	}

	

	function display($option,&$rows,$from_email,$name,$contents){		

		$footer = "<div align=\"center\" style ='font-size:xx-small; font-weight: bold; valign:bottom;'>Powered by <a href=\"http://www.barakbangla.com\">JoomInvite</a></div>";

		global $mainframe,$mosConfig_live_site,$my;

		$link = "index.php?option=$option";		

		$total=count( $rows );

		

		?>

		<script language="javascript" src="<?php echo $mosConfig_live_site?>/includes/js/joomla.javascript.js" type="text/javascript">	</script>

		

		<script language="javascript" type="text/javascript">

			

			function checkall(thestate){

				var el_collection = document.forms['adminForm'].elements['cid[]'];

				for (var c=0;c<el_collection.length;c++)

				el_collection[c].checked=thestate

				}





		</script>

	

	<script language="javascript">

function  checkvalidate() {

	var chks = document.getElementsByName('cid[]');

	var hasChecked = false;

	for (var i = 0; i < chks.length; i++)

	{

		if (chks[i].checked)

		{

		hasChecked = true;

		break;

		}

	}

	

	

	if (hasChecked == false)

	{

	alert("<?php echo _NO_SELECT?>");

	return false;

	}

	

	if (document.adminForm.name.value == '')

	 {

	 	alert("<?php echo _NO_NAME?>");

		return false;

	 }

	 

	 if (document.adminForm.msg.value == '')

	 {

	 	alert("<?php echo _NO_MESG?>");

		return false;

	 }

	

	document.adminForm.submit();

}

</script>

	

		

		<form action="<?php echo sefRelToAbs($link); ?>" method="post" name="adminForm" id="adminForm"   >

		<style type="text/css">

				<!--

				td.importin {

							border-left-width: 1px; 

							border-right: 1px solid #D8D8D8; 

							border-top-width: 1px; 

							border-bottom:1px solid #D8D8D8;

							border-top:1px solid #D8D8D8;

							text-align:left;

							height:25px;

						}

						

				

				-->

		</style>

		<br  />&nbsp;

		<br  />&nbsp;

		<div align="center">

		

		<table  cellspacing='0' cellpadding='0' style="border: 1px solid #D8D8D8" width="550">

		<tr>

			<td colspan='3' bgcolor="#F2F2F2">

				<table width="100%" cellpadding="0" cellspacing="0">

					<tr>

						<td bgcolor="#F2F2F2" height="27" width="30%">

							<p align="center"><font face="Arial" style="font-size: 9pt">

								&nbsp;<a href="javascript:checkall(true)">Check All</a> &nbsp; | &nbsp; 

								<a href="javascript:checkall(false)">Uncheck All</a> &nbsp;&nbsp;&nbsp; 

								</font></p>

						</td>

						<td bgcolor="#F2F2F2" height="27" width="70%">

							<b><font face="Arial" size="2" color="#333333">

								<div  align="center"> <?php echo _INVITE_CONTACTS;?> </div>

							</font></b></td>

					</tr>

				</table>

			</td>

		</tr>

		

		<tr>

			<td height="25" colspan="3">

				<div align="center">

					<table cellpadding="0" cellspacing="0" width="100%" id="table4"  style="border-top: 1px solid #D8D8D8; ">

						

		<?php

		for($i=0,$n=count( $rows ); $i < $n; $i++){

			$row = &$rows[$i];

			

		?>

		<tr >

		<td width="17" bgcolor="#FFFFFF" class="importin">

			

		<?php echo $i?>

			

		</td>

		<td bgcolor="#FFFFFF" width="17" class="importin" >

			

				<font face="Arial" size="2" color="#333333" >

				<input type="checkbox"  name="cid[]" value="<?php echo $row->id; ?>" checked="checked"  />

		

				

			

		</td>

		<td bgcolor="#FFFFFF" width="212" class="importin" style="padding-left:5px; margin-left:5px;" >

			

				<font face="Arial" size="2" color="#333333">

		<?php echo $row->invitee_name;?>

				</font>

			

		</td>

		<td bgcolor="#FFFFFF" class="importin" style="padding-left:5px; margin-left:5px;">

			

				<font face="Arial" size="2" color="#333333">

		<?php echo $row->invitee_email;?>

			</font>

		

		</td>

		</tr>	

		<?php

		}

		?>

		</table>

		</div>

		</td>

		</tr>

		</table>

		<br />

		<table>

		<tr>

		<td>

		<?php echo _YOUR_NAME; ?> <br />

		

		</td>

		<td>

		<input type="text" name="name" value="<?php echo $name?>" />

		</td>

		</tr>

		<tr>

		<td>

		<?php echo _MSG?>

		</td>

		<?php $invi_text = stripslashes($row->msg); ?><br />&nbsp;

		<td>

		<textarea rows="15" cols="50" name="msg"></textarea><br />&nbsp;

		</td>

		</tr>

		<tr>

		<td  colspan="2" ><div align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="   Invite Friends   " onclick="javascript: checkvalidate();" /><br />&nbsp;<br />&nbsp;</div></td>

		</tr>

		

		</table>

		

		</div>

		<input type="hidden" name="option" value="<?php echo $option;?>" />

		<input type="hidden" name="from_email" value="<?php echo $from_email; ?>" />

		<input type="hidden" name="default_mesg" value="<?php echo $row->msg; ?>" />

		<input type="hidden" name="task" value="invite" />

		<input type="hidden" name="boxchecked" value="0" />

		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />

		</form>

		<?php

		echo $footer;

		}//dispkay

}



?>

