<?php

defined('_JEXEC') or die('Restricted access');

function com_install() {
	require_once( JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_jce' .DS. 'updater.php' );
	
	$updater =& JCEUpdater::getInstance();	
	// Install Plugins data
	$updater->installPlugins( true );
	// Install Groups data
	$updater->installGroups( true );
}
function com_uninstall() {
	require_once( JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_jce' .DS. 'updater.php' );
	
	$updater =& JCEUpdater::getInstance();	
	$updater->cleanupDB();
}
?>