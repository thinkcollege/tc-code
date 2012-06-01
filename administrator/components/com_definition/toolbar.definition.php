<?php
/**
 * Language file
 * @author GranholmCMS
 * @link http://www.granholmcms.com
 */

defined('_JEXEC') or die('Restricted access');

require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

switch ($task) {
  case "add":
    menudefinition::NEW_MENU();
    break;

  case "edit":
    menudefinition::EDIT_MENU();
    break;

  case "config":
    menudefinition::CONFIG_MENU();
    break;

  case "about":
    menudefinition::ABOUT_MENU();
    break;

  default:
	menudefinition::DEFAULT_MENU();
    break;
}
?>
