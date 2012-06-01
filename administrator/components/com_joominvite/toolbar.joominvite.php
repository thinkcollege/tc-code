<?php
/**
* @version $Id: toolbar.joominvite.php 
* @package Joomnvite
* @copyright Copyright (C) 2008 Anikendra Das Choudhury. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ($task) {
	case 'config':
		TOOLBAR_joominvite::_CONFIG();
		break;
	case 'send':
		TOOLBAR_joominvite::_SENDMAIL();
		break;
	default:
		TOOLBAR_joominvite::_DEFAULT();
		break;
}
?>