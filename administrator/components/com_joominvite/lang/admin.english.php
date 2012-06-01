<?php
/**
* @version $Id: admin.english.php 
* @package Yellowpages
* @copyright Copyright (C) 2008 Anikendra Das Choudhury. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

define( '_JOOMINVITE_MAIN', 'Invite Your Friends');
define( '_SUBJECT', 'Subject');
define( '_MSG_BODY', 'Message');   
define( '_JOOMINVITE_CONFIG', 'JoomInvite Configuration'); 
define( '_SEND_AFTER', 'Send After');
define( '_HELP_SEND_AFTER', 'Days after which reminder emails should be sent');
define( '_USE_CUSTOM_MSG', 'Use Custom Message');
define( '_HELP_USE_CUSTOM_MSG', 'Do you want to append a custom message in the invitation email');
define( '_CUSTOM_SUBJECT', 'Subject');
define( '_HELP_CUSTOM_SUBJECT', 'The custom subject of invitation email. <b>{user}</b> would be replaced by the name of the user who sends invitation mails and <b>{my_site}</b> by url of your website.');
define( '_SEND_AUTO_INVITES', 'Send Auto Invites');
define( '_HELP_SEND_AUTO_INVITES', 'Do you want to send automatic reminder mails to those who havenot yet joined your website?');
define( '_MSG', 'Custom Message');
define( '_EMAIL_FROM_USER', 'Email From User'); 
define( '_HELP_EMAIL_FROM_USER','Check this box if you would like the <b>From</b> and <b>Reply To</b> email addresses that appear in the invitation emails to use the Users email address. If NOT checked, the <b>From</b> email address will be yours(Administrator) and the <b>Reply To</b> email address to be the Users.');
define( '_BCC_ADMIN', 'Copy (Bcc) Admin');  
define( '_HELP_BCC_ADMIN', 'Check this box if <b>you(Administrator)</b> would like to receive a <b>copy(Bcc)</b> of all emails that are sent from <b>JoomInvite</b>. You will receive only one email for each batch of invitations sent - listing the email addresses sent to, who sent the invitation and a copy of the Body text. <u>Warning</u>: <blink>depending on how many users you have, how active your site is etc., you could receive a lot of emails.</blink>');   
define( '_JOOMINVITE_CONFIGURATION_SAVED','The configuration details have been updated!');  
define( '_SEND_AFTER_DESC','Please specify the number of days after which auto invite mails should be sent.');
define( '_FROM_EMAIL_DESC','Please specify the email address to send mass mails from.')   
?>
