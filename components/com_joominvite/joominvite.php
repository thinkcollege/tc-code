<?php

/**

* @version $Id: joominvite.php 

* @package JoomInvite

* @copyright Copyright (C) 2008 Anikendra Das Choudhury. All rights reserved.

* Credit : Yves Christian

* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL

*

*/



// no direct access

defined( '_VALID_MOS' ) or die( 'Restricted access' );



/** load the html drawing class */

require_once( $mainframe->getPath( 'front_html' ) );



$lang = dirname( __FILE__) . '/lang/' . $mosConfig_lang . '.php';

if(!file_exists($lang)){

    $lang = dirname( __FILE__) . '/lang/english.php';

}

require_once($lang);



if (file_exists( $mosConfig_absolute_path .'/components/'.$option.'/cron.php' ))

	require_once( $mosConfig_absolute_path .'/components/'.$option.'/cron.php' );

	

if (file_exists( $mosConfig_absolute_path .'/components/'.$option.'/openinviter/openinviter.php' ))

	require_once( $mosConfig_absolute_path .'/components/'.$option.'/openinviter/openinviter.php' );	

	

if ($last_cron_date != date("Ymd"))  	

	process_cron($option);



$mainframe->setPageTitle( _JOOMINVITE_TITLE );



$cid = josGetArrayInts( 'cid' );



switch ($task) {

	case 'invite':

		invite( $cid, $option );

		break;

	case 'fetch':

		fetch($option);

		break;

	default:

		front($option);

		break;

}	



function front($option){

	HTML_JoomInvite::front($option);

}



function process_cron($option){

	global $database,$mosConfig_absolute_path,$mosConfig_mailfrom,$mosConfig_fromname;

	

	// get configuration

	$database->setQuery( "SELECT * FROM #__joominvite_config");

	$database->loadObject($conf);

	if ($database -> getErrorNum()) {

		echo $database -> stderr();

		return false;

	}

	$database->setQuery( "SELECT `email` FROM #__users");

	$emails = $database->loadObjectList();

	if ($database -> getErrorNum()) {

		echo $database -> stderr();

		return false;

	}

	//If the invitee has already joined then don't send him any more emails

	foreach($emails AS $email){

		$database->setQuery("UPDATE #__joominvites SET `to_be_invited` = 0 WHERE invitee_email = '$email->email'");

		$database->query();

		if ($database -> getErrorNum()) {

			echo $database -> stderr();

			return false;

		}

	}

	//Send reminders if activated

	if ($conf->auto_invites == 1)

	{

			$recall_time = date("YmdHis",mktime()-($conf->send_after*24*3600)); 

			$database->setQuery( "SELECT * FROM #__joominvites WHERE last_sent < $recall_time AND to_be_invited = 1");

			$rows = $database->loadObjectList();

			if ($database -> getErrorNum()) {

				echo $database -> stderr();

				return false;

			}

			if(isset($rows))

			{

				foreach($rows as $row)

				{

					$mailfrom = ($conf->email_from_user?$row->invited_by_email:$mosConfig_mailfrom);

					$fromname = ($conf->email_from_user?$row->invited_by_name:$mosConfig_fromname);

					mosMail($mailfrom,$fromname,$row->invitee_email,$conf->custom_subject,$row->msg,1);				

				}

			}

			$database->setQuery( "UPDATE #__joominvites SET last_sent = CURDATE() WHERE last_sent < $recall_time AND to_be_invited = 1");

			$database->query();

			if ($database -> getErrorNum()) {

				echo $database -> stderr();

				return false;

			}

	}

	

	$last_cron_date = date("Ymd");

	$Fnm = $mosConfig_absolute_path .'/components/'.$option.'/cron.php';

    $inF = fopen($Fnm,"w"); 

	fwrite($inF,'<?php $last_cron_date='.$last_cron_date.';?>');

	fclose($inF); 

}



function ers($ers){

	$contents="<table cellspacing='0' cellpadding='0' style='border:1px solid red;' align='center' class='tbErrorMsgGrad'><tr><td valign='middle' style='padding:3px' valign='middle' class='tbErrorMsg'><img src='/images/ers.gif'></td><td valign='middle' style='color:red;padding:5px;'>";

	foreach ($ers as $key=>$error)

		$contents.="{$error}<br >";

	$contents.="</td></tr></table><br >";

	return $contents;

}



function fetch($option){

	global $database,$my,$mosConfig_absolute_path;

	//from openinviter

	$inviter=new OpenInviter();

	$email_providers=$inviter->getPlugins();

	

	

	//query for original Version			

	$query = "SELECT * FROM #__joominvite_config";

	$database->setQuery($query);

	$database->loadObject($conf);

	

	if ($database -> getErrorNum()) {

		echo $database -> stderr();

		return false;

	}

		

	set_time_limit(0);

	

	//From openinviter

	$ers=array();

	$import_ok=false;

	

	if (($_SERVER['REQUEST_METHOD']=='POST'))

	{

	 $email_box=mosGetParam( $_REQUEST, 'email_box', '' ); 

	 $password_box=mosGetParam( $_REQUEST, 'password_box', '' ); 

	 $provider_box=mosGetParam( $_REQUEST, 'provider_box', '' ); 

	 

	if (empty($email_box))

		$ers['email']="Email missing";

	if (empty($password_box))

		$ers['password']="Password missing";

	if (empty($provider_box))

		$ers['provider']="Provider missing";

	if (count($ers)==0)

		{

		$inviter->startPlugin($provider_box);

		if (!isset($inviter))

			$ers['inviter']=_INVALID_EMAIL_PROVIDER;

		elseif (!$inviter->login($email_box,$password_box))

			$ers['login']=_INCORRECT_LOGIN;

		elseif (!$contacts=$inviter->getMyContacts())

			$ers['contacts']=_NO_CONTACTS_FETCHED;

		else

			{

			$inviter->logout();

			$import_ok=true;

			}

		}

	}



$contents=(count($ers)!=0?ers($ers):'');

		

	 	if (!$import_ok)

	 	{	

			foreach($ers AS $error)

				$errmsg .= $error.' ';   

	 		//mosRedirect( "index.php?option=$option",_NO_CONTACTS_FETCHED );

			mosRedirect( "index.php?option=$option",$errmsg );

	 	}

		

		$str="";

	 	

		if ($import_ok)

		{		

			if (count($contacts)==0)

			$contents=_EMPTY_ARRAY;

			

			else{	

			$msg = "";

			if($conf->use_custom_msg)

				$msg = $conf->msg;

				$YOUR_EMAIL=$email_box;

				

			foreach ($contacts as $email=>$name){

				$name = addslashes($name);

				//$query = "INSERT IGNORE INTO #__joominvites (`invitee_email`,`invitee_name`,`invited_by_email`,`msg`) VALUES ('".$email."','".$name."','".$YOUR_EMAIL."','".$msg."')";

				$query = "INSERT IGNORE INTO #__joominvites (`invitee_email`,`invitee_name`,`invited_by_email`,`msg`) VALUES ('". mysql_real_escape_string($email)."','". mysql_real_escape_string($name)."','". mysql_real_escape_string($YOUR_EMAIL)."','". mysql_real_escape_string($msg)."')";

				$database->setQuery($query);

				$database->query();

				if ($database->getErrorNum()) {

					echo $database->stderr();

					return false;

				}

			}

		}	

			$query = "SELECT * from #__joominvites WHERE `invited_by_email`= '$YOUR_EMAIL' ORDER BY `invitee_name`";

			$database->setQuery($query);

			$rows = $database->loadObjectList();

			if ($database->getErrorNum()) {

				echo $database->stderr();

				return false;

			}

			

			$name='';

			if($my->id)

				$name=$my->name; 

			HTML_JoomInvite::display($option,$rows,$YOUR_EMAIL,$name,$contents);

		}

		

}

	

	function invite( $cid, $option ){

		global $database,$mosConfig_live_site,$mosConfig_mailfrom,$mosConfig_fromname;

		$query = "SELECT * FROM #__joominvite_config";

		$database->setQuery($query);

		$database->loadObject($conf);

		if ($database -> getErrorNum()) {

			echo $database -> stderr();

			return false;

		}

		

		josSpoofCheck();



	if (!is_array( $cid ) || count( $cid ) < 1) {

		echo "<script> alert('Select a contact to invite'); window.history.go(-1);</script>\n";

		exit;

	}

	$from_email = mosGetParam($_REQUEST,'from_email','');

	$from_name = mosGetParam($_REQUEST,'name','');

	$msg = mosGetParam( $_REQUEST, 'msg', '' );

	$default_msg = mosGetParam( $_REQUEST, 'default_mesg', '' );

	$msg = $msg.'<br/><br/>'.$default_msg;

	$msgtosave = addslashes($msg);

	$n=0;

	//Warning: Hardcoded text

	$admin_sub="JoomInvites sent";

	$admin_msg = "Hi Admin,<br />$from_name ($from_email) has sent invites to :-<br/><br />"; 

	

	mosArrayToInts( $cid );

	foreach($cid AS $id){	

		$query = "UPDATE #__joominvites SET `invited_by_name`='$from_name', `to_be_invited`=1, `last_sent`=CURDATE(), `msg`='$msgtosave' WHERE `id`=$id";

		$database->setQuery($query);

		$database->query();

		if ($database->getErrorNum()) {

			echo $database->stderr();

			return false;

		}

		$query = "SELECT `invitee_name`, `invitee_email` FROM #__joominvites WHERE `id`=$id";

		$database->setQuery($query);

		$result = $database->loadObjectList();		

		if ($database->getErrorNum()) {

			echo $database->stderr();

			return false;

		}

		

		$name = $result[0]->invitee_name;

		$to = $result[0]->invitee_email;

		//$subject = $from_name." has recommended you to visit ".$mosConfig_live_site;

		$subject = $conf->custom_subject;
		$default_msg = $conf->msg;

		$subject = preg_replace('/{user}/',$from_name,$subject);

		$subject = preg_replace('/{my_site}/',$mosConfig_live_site,$subject);

		$body = "Hi $name,<br/>";
		$body.= stripslashes($msg); 	
		$body.="<br/><br/>$default_msg";

		$mailfrom = ($conf->email_from_user?$from_email:$mosConfig_mailfrom);

		$fromname = ($conf->email_from_user?$from_name:$mosConfig_fromname);

		mosMail($mailfrom,$fromname,$to,$subject,$body,1,NULL,NULL,null,$from_email,$from_name);

		$admin_msg.="$name ($to).<br/>";

		$n++;	

		//echo "<script> alert('Sent ".$subject." to ".$name." at ".$body."');</script>";	

	}

	if($conf->bcc_admin){

		mosMail($mosConfig_mailfrom,$mosConfig_fromname,$mosConfig_mailfrom,$admin_sub,$admin_msg,1);

	}

		

	//Warning: Hardcoded text

	mosRedirect( "index.php?option=$option","Successfully invited $n contacts, go ahead invite more friends" );		

}	      

?>