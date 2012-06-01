<?php
/**
* @version		jce.php 03-03-2009
* @package		JCE Admin Component
* @subpackage	Plugins
* @copyright	Copyright (C) 2006 - 2009 Ryan Demmer. All rights reserved.
* @license		GNU/GPL
* JCE is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$task = JRequest::getCmd( 'task' );

/*
 * Editor or plugin request.
 */
if( $task == 'plugin' || $task == 'help' ){
	require_once( dirname( __FILE__ ) .DS. 'editor.php' );
	exit();
}

// Authorize
$user 	=& JFactory::getUser();
$acl	=& JFactory::getACL();

$tasks = array(
	// Admin
	'repair', 'purge', 
	// Installer
	'install', 'remove', 'manage', 
	// Standard
	'view', 'edit', 'save', 'apply', 'publish', 'unpublish', 'cancel', 'cancelEdit', 'orderup', 'orderdown', 
	// Plugins
	'add', 
	// Groups
	'copy', 'legend', 'addusers', 'removeusers',
	// Cpanel
	''
);

foreach( $tasks as $auth ){
	$acl->addACL( 'com_jce', $auth, 'users', 'super administrator' );
	$acl->addACL( 'com_jce', $auth, 'users', 'administrator' );
	$acl->addACL( 'com_jce', $auth, 'users', 'manager' );
}

if (!$user->authorize( 'com_jce', $task )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}
// Variables
$type = JRequest::getCmd( 'type' );
// Load Helper class
require_once( dirname( __FILE__ ) .DS. 'helper.php' );
// Load Updater class
require_once( dirname( __FILE__ ) .DS. 'updater.php' );
// Create updater instance
$updater =& JCEUpdater::getInstance();
// Repair / Purge
switch( $task ){
	case 'repair':
		switch ( $type ) {
			case 'editor':
				$updater->installEditor();
				break;
			case 'plugins':
				$updater->updatePlugins();
				break;
			case 'groups':
				$updater->updateGroups();
				break;
			case 'update':
				$updater->updateDB();
				break;
		}
		break;
	case 'purge':
		$updater->purgeDB();
		break;
}
// Check Updater
$updater->initCheck();

$client = JRequest::getWord( 'client', 'site' );
$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

JArrayHelper::toInteger($cid, array(0));
$type 	= JRequest::getCmd( 'type' );
$task 	= JRequest::getCmd( 'task' );

switch( $type ){
	case 'plugin':	
		switch( $task ){
			case 'install':
			case 'remove':
			case 'manage':
				require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'installer.php' );
				break;
			case 'view':
			default:
				require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'plugin.php' );
				break;
		}
		break;
	case 'group':	
		switch( $task ){
			case 'view':
			default:
				require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'groups.php' );
				break;
		}
		break;
	case 'language':
		switch( $task ){
			case 'install':
			case 'remove':
			case 'manage':
				require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'installer.php' );
				break;
			default:
				require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'cpanel.php' );
				break;
		}
		break;
	case 'extension':
		switch( $task ){
			case 'install':
			case 'remove':
			case 'manage':
				require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'installer.php' );
				break;
			default:
				require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'plugin.php' );
				break;
		}
		break;
	case 'config':
		require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'config.php' );
		break;
	case 'install':
		require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'installer.php' );
		break;
	default:
		switch( $task ){
			case 'install':
				require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'installer.php' );
				break;
			default:
			require_once( dirname( __FILE__ ) .DS. 'controller' .DS. 'cpanel.php' );
				break;	
		}
		break;
}
?>