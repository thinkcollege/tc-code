<?php
/**
* @version		editor.php 1.5.0 31 January 2008
* @package		JCE
* @subpackage	Admin Component
* @copyright	Copyright (C) 2006 - 2008 Ryan Demmmer. All rights reserved.
* @license		GNU/GPL
* JCE is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

define( 'JCE_PATH', 		JPATH_PLUGINS .DS. 'editors' .DS. 'jce' );
define( 'JCE_PLUGINS', 		JCE_PATH .DS. 'tiny_mce' .DS. 'plugins' );
define( 'JCE_LIBRARIES', 	JCE_PATH .DS. 'libraries' );
define( 'JCE_CLASSES', 		JCE_LIBRARIES .DS. 'classes' );

function checkPlugin( $plugin ){
	$db	=& JFactory::getDBO();
	
	$query = "SELECT id"
	. "\n FROM #__jce_plugins"
	. "\n WHERE name = ". $db->Quote( $plugin ) 
	. "\n AND published = 1"
	. "\n AND type = 'plugin'"
	;
	$db->setQuery( $query );
	return $db->loadResult();
}
switch( $task ){
	case 'plugin':							
		$plugin = JRequest::getVar( 'plugin', 'cmd' );
		if( checkPlugin( $plugin ) ){
			$file = basename( JRequest::getVar( 'file', 'cmd' ) );
			$path = JCE_PLUGINS .DS. $plugin;		
			if( is_dir( $path ) && file_exists( $path .DS. $file . '.php' ) ){
				include_once $path .DS. $file . '.php';
			}else{
				JError::raiseError(500, JText::_('File '. $file .' not found!') );
			}
		}else{
			JError::raiseError(500, JText::_('Plugin not found!') );
		}
		exit();
		break;
	case 'help':					
		$plugin = JRequest::getVar( 'plugin', 'cmd' );
		if( checkPlugin( $plugin ) ){
			jimport('joomla.application.component.view');
			$help = new JView( $config = array(
				'base_path' 	=> JCE_LIBRARIES,
				'layout' 		=> 'help'
			) );
			$help->display();
		}else{
			JError::raiseError(500, JText::_('Plugin not found!') );
		}
		exit();
		break; 
}
?>