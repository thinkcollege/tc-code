<?php
/**
* @version		groups.php 05 April 2009
* @package		JCE
* @subpackage	Admin Component
* @copyright	Copyright (C) 2005 - 2009 Ryan Demmer. All rights reserved.
* @license		GNU/GPL
* JCE is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Table class
require_once( JPATH_COMPONENT .DS. 'groups' .DS. 'groups.php' );
// Controller
require_once( JPATH_COMPONENT .DS. 'groups' .DS. 'controller.php' );

// Create the controller
$controller	= new GroupsController( array(
	'base_path' =>  JPATH_COMPONENT .DS. 'groups' 
) );

$controller->execute( JRequest::getCmd( 'task' ) );
$controller->redirect();
?>
