<?php
//Redirect to index2.php?option=com_jce

define( '_VALID_MOS', 1 );

$base = str_replace( 'mambots/editors/jce/jscripts/tiny_mce', '', str_replace( DIRECTORY_SEPARATOR, '/', dirname(__FILE__) ) );

include ( $base . "/configuration.php" );
include ( $base . "/includes/joomla.php" );

function getInput( $item, $def=null ){
	return htmlspecialchars( mosGetParam( $_REQUEST, $item, $def ) );
}

$mode = ( getInput( 'mode', 'basic' ) == 'basic' ) ? '0' : '1'; 
$title = getInput( 'title' );
$alt = getInput( 'alt' );

$img = getInput( 'img' );
$src = getInput( 'src' );

$img = ( $src ) ? $src : $img;
mosRedirect( "$mosConfig_live_site/index2.php?option=com_jce&task=popup&img=$img&mode=$mode&title=$title&alt=$alt" );

?>
