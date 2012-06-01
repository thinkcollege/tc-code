<?php
/**
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div id="<?php echo $params->get( 'moduleclass_sfx' ); ?>"><?php
global $mosConfig_live_site;
global $database;

	

	$query = "SELECT introtext"
	. "\n FROM #__content"
	. "\n WHERE id = '". $params->get( 'db_selector' ) ."'"
	;
	$database->setQuery( $query );
	$topblurb = $database->loadResult();
	
	echo $topblurb;
    
?></div>