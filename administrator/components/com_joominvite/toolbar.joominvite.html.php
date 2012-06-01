<?php
/**
* @version $Id: toolbar.joominvite.html.php 
* @package JoomInvite
* @copyright Copyright (C) 2008 Anikendra Das Choudhury. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage JoomInvite
*/
class TOOLBAR_joominvite {
	function _CONFIG() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	function _SENDMAIL() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::custom('sendmail','massemail.png','massemail.png','Send Mail',false);
		mosMenuBar::endTable();
	}
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
}
?>