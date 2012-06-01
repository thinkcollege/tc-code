<?php
/**
* @version $Id: joominvite.class.php 
* @package JoomInvite
* @copyright Copyright (C) 2008 Anikendra Das Choudhury. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* Category database table class
* @package Joomla
* @subpackage JoomInvite
*/
class mosJoomInvite extends mosDBTable {
	/** @var int Primary key */
	var $id					= null;
	/** @var string */
	var $invitee_name		= null;
	/** @var string */
	var $invitee_email		= null;
	/** @var string */
	var $title				= null;
	/** @var string */
	var $invited_by_name	= null;
	/** @var string */
	var $invited_by_email	= null;
	/** @var int */
	var $to_be_invited		= null;
	/** @var datetime */
	var $last_sent			= null;
	/** @var boolean */
	var $checked_out		= null;
	/** @var time */
	var $checked_out_time	= null;
	/** @var string */
	var $msg = null;
	/**
	* @param database A database connector object
	*/
	function mosJoomInvite( &$db ) {
		$this->mosDBTable( '#__joominvites', 'id', $db );
	}
}

class mosJoomInvite_Config extends mosDBTable {
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $email_from_user	= null;
	/** @var int */
	var $bcc_admin			= null;
	/** @var int */
	var $use_custom_subject = null;
	/** @var string */
	var $custom_subject		= null;
	/** @var int */
	var $use_custom_msg		= null;
	/** @var string */
	var $msg				= null;
	/** @var int */
	var $auto_invites		= null;
	/** @var int  */
	var $send_after			= null;
	/** @var string */
	var $from_mail			= null; 
	/**
	* @param database A database connector object
	*/
	function mosJoomInvite_Config( &$db ){
		$this->mosDBTable ( '#__joominvite_config','id',$db);
	}
}
?>