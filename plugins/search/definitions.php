<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onSearch', 'plgSearchDefinitions' );
$mainframe->registerEvent( 'onSearchAreas', 'plgSearchDefinitionsAreas' );

JPlugin::loadLanguage( 'plg_search_definitions' );

/**
 * @return array An array of search areas
 */
function &plgSearchDefinitionsAreas()
{
	static $areas = array(
		'definitions' => 'Definitions'
	);
	return $areas;
}

/**
 * Content Search method
 * The sql must return the following fields that are used in a common display
 * routine: href, title, section, created, text, browsernav
 * @param string Target search string
 * @param string mathcing option, exact|any|all
 * @param string ordering option, newest|oldest|popular|alpha|category
 * @param mixed An array if the search it to be restricted to areas, null if search all
 */
function plgSearchDefinitions( $text, $phrase='', $ordering='', $areas=null )
{
	global $mainframe;

	$db		=& JFactory::getDBO();
	$user	=& JFactory::getUser();

	//require_once(JPATH_SITE.DS.'components'.DS.'com_definitions'.DS.'helpers'.DS.'route.php');

	if (is_array( $areas )) {
		if (!array_intersect( $areas, array_keys( plgSearchDefinitionsAreas() ) )) {
			return array();
		}
	}

	// load plugin params info
 	$plugin			=& JPluginHelper::getPlugin('search', 'definitions');
 	$pluginParams	= new JParameter( $plugin->params );

	$limit 			= $pluginParams->def( 'search_limit', 50 );
	$pagereturn		= $pluginParams->def( 'returntype', 'single' );

	$nullDate 		= $db->getNullDate();
	$date 			=& JFactory::getDate();
	$now			= $date->toMySQL();

	$text = trim( $text );
	if ($text == '') {
		return array();
	}

	$wheres = array();
	switch ($phrase) {
		case 'exact':
			$text		= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
			$wheres2 	= array();
			$wheres2[] 	= 'LOWER(a.tterm) LIKE '.$text;
			$wheres2[] 	= 'LOWER(a.tdefinition) LIKE '.$text;
			$where 		= '(' . implode( ') OR (', $wheres2 ) . ')';
			break;

		case 'all':
		case 'any':
		default:
			$words = explode( ' ', $text );
			$wheres = array();
			foreach ($words as $word) {
				$word		= $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
				$wheres2 	= array();
				$wheres2[] 	= 'LOWER(a.tterm) LIKE '.$word;
				$wheres2[] 	= 'LOWER(a.tdefinition) LIKE '.$word;
				$wheres[] 	= implode( ' OR ', $wheres2 );
			}
			$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
			break;
	}

	$morder = '';
	switch ($ordering) {
		case 'oldest':
			$order = 'a.tdate ASC';
			break;

		case 'newest':
			$order = 'a.tdate DESC';
			break;

		case 'category':
			$order = 'b.title ASC, a.tterm ASC';
			$morder = 'a.tterm ASC';
			break;

		case 'alpha':
			default:
			$order = 'a.tterm ASC';
			break;
	}

	$rowd = array();

	$query = 'SELECT a.tterm AS title, a.tdate AS created, a.tdefinition AS text, a.tletter, a.catid,'
	. ' "1" AS browsernav'
	. ' FROM #__definition AS a'
	. ' INNER JOIN #__categories AS b ON b.id=a.catid'
	. ' WHERE ( '.$where.' )'
	. ' AND a.published = 1'
	. ' AND b.published = 1'
	. ' AND b.access <= '.(int) $user->get( 'aid' )
	. ' ORDER BY '. $order
	;

	$db->setQuery( $query, 0, $limit );
	$rowd = $db->loadObjectList();
	
	if ($pagereturn=='single') {
		$Itemid = JRequest::getInt('Itemid');
		foreach($rowd as $key => $row) {
//********** Single Page view is not working, when it does, uncomment this line and delete the following line to enable the functionality ***************
			//$rowd[$key]->href = 'index.php?option=com_definition&func=view&Itemid='.$Itemid.'&catid='.$row->catid.'&term='.urlencode($row->title);
			$rowd[$key]->href = "index.php?option=com_definition&func=display&letter=".$row->tletter."&catid=".$row->catid;
		}
	} else {
		foreach($rowd as $key => $row) {
			$rowd[$key]->href = "index.php?option=com_definition&func=display&letter=".$row->tletter."&catid=".$row->catid;
		}	
	}

	return $rowd;
}
