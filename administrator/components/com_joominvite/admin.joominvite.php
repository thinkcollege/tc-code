<?php
/**
* @version $Id: admin.joominvite.php 
* @package JoomInvite
* @copyright Copyright (C) 2008 Anikendra Das Choudhury. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

// ensure user has access to this function
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
		| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_joominvite' ))) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

$cid = josGetArrayInts( 'cid' );
switch ($task){
	case 'config':
	configWindow($option);
	break;
	case 'save':
	saveConfig($option);
	break;
	case 'remove':
	deleteContacts( $cid, $option );
	break;
	case 'send':
	sendMail($option);
	break;
	case 'sendmail':
	sendMassMail( $option);
	break;
	case 'show':
	default:
	front($option);
	break;
}

function sendMail( $option){
	HTML_joominvite::MailForm($option);
}

function sendMassMail( $option){
	global $database,$mosConfig_fromname,$mosConfig_mailfrom;
	$i = 0;
	//require_once("config.php");
	josSpoofCheck();
	
	$subject = mosGetParam( $_REQUEST,'subject','' );
	$body = mosGetParam( $_REQUEST,'introtext','' );
	
	if (get_magic_quotes_gpc()) {
		$body	= stripslashes( $body );
	}
	$query = "SELECT `invitee_email` AS email FROM `#__joominvites` WHERE 1";
	$database->setQuery($query);
	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}
	foreach($rows AS $contact){
		mosMail($mosConfig_mailfrom,$mosConfig_fromname,$contact->email,$subject,$body,1,null,null,NULL,$mosConfig_mailfrom,$mosConfig_fromname);
		$i++;
		//echo "Sent Mail to $contact->email";
	}
	mosRedirect( "index2.php?option=$option","Sent mail to $i persons. $subject  $body" );	
}

	

function deleteContacts( $cid, $option ) {
	global $database;
	
	josSpoofCheck();

	if (!is_array( $cid ) || count( $cid ) < 1) {
		echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
		exit;
	}
	if (count( $cid )) {
		mosArrayToInts( $cid );
		$cids = 'id=' . implode( ' OR id=', $cid );
		$query = "DELETE FROM #__joominvites"
		. "\n WHERE ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	mosRedirect( "index2.php?option=$option" );
}

function front($option){
	global $database,$mosConfig_list_limit,$mainframe;
	
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$search 	= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	if (get_magic_quotes_gpc()) {
		$search	= stripslashes( $search );
	}

	$where = array();

	if ($search) {
		$where[] = "LOWER(a.invitee_name) LIKE '%" . $database->getEscaped( trim( strtolower( $search ) ) ) . "%'";
	}

	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__joominvites AS a"
	. (count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
	;
	$database->setQuery( $query );
	$total = $database->loadResult();
	
	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$query = "SELECT a.*"
	. "\n FROM #__joominvites AS a"
	. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
	. "\n ORDER BY a.invitee_name"
	;
	$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );

	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	HTML_joominvite::front( $option, $rows, $search, $pageNav );
}

function saveConfig($option){
	global $database;
	josSpoofCheck();

	$row = new mosJoomInvite_Config($database);

	// bind it to the table
	if (!$row -> bind($_POST)) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}

	// store it in the db
	if (!$row -> store()) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();	
	}
	
	mosRedirect("index2.php?option=$option&task=config", _JOOMINVITE_CONFIGURATION_SAVED);	
}

function configWindow($option){
	global $database;
	$query = "SELECT a.* FROM #__joominvite_config as a WHERE 1";
	$database->setQuery( $query );
	$database->loadObject($conf);
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}
	HTML_joominvite::configWindow( $option, $conf );
}
?>