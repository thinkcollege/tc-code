<?php
// no direct access
defined( '_JCE_EXT' ) or die( 'Restricted access' );
// Core function	
// Hack ** -- removed 'by reference' to comply with PHP 5.3 function joomlalinks( &$advlink ){
function joomlalinks( $advlink ){
	// Joomla! file and folder processing functions
	jimport('joomla.filesystem.folder');
	jimport('joomla.filesystem.file');
		
	// Base path for corelinks files
	$path = dirname( __FILE__ ) .DS. 'joomlalinks';	
		
	// Get all files
	$files = JFolder::files( $path, '\.(php)$' );	
	
	$items = array();
	
	// For AdvLink link plugins
	if( isset( $files ) ){
		foreach( $files as $file ){
			$items[] = array(
				'name'		=> JFile::stripExt( $file ),
				'path' 		=> $path,
				'file' 		=> $file
			);
		}
	}
	return $items;
}	
?>
