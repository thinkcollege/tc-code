<?php
/**
 * Language file
 * @author GranholmCMS
 * @link http://www.granholmcms.com
 */

defined('_JEXEC') or die('Restricted access');


class menudefinition {

  
function NEW_MENU() {
    JToolBarHelper::save();
	JToolBarHelper::spacer();
    JToolBarHelper::cancel();
    JToolBarHelper::spacer();
  }

  
function EDIT_MENU() {
	JToolBarHelper::save();
    JToolBarHelper::spacer();
	JToolBarHelper::cancel();
    JToolBarHelper::spacer();
  }

  
function CONFIG_MENU() {
    JToolBarHelper::save( 'savesettings', 'Save' );
    JToolBarHelper::back();
    JToolBarHelper::spacer();
  }

  
function ABOUT_MENU() {
    JToolBarHelper::back();
    JToolBarHelper::spacer();
  }

   
function DEFAULT_MENU() {
	JToolBarHelper::publishList();
	JToolBarHelper::spacer();
	JToolBarHelper::unpublishList();
	JToolBarHelper::spacer();
    JToolBarHelper::addNew();
	JToolBarHelper::spacer();
    JToolBarHelper::editList();
	JToolBarHelper::spacer();
    JToolBarHelper::deleteList();
    JToolBarHelper::spacer();
  }

}
?>
