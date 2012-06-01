<?php
/**
* PDF Index - A Mambo/Joomla PDF Indexing Component
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* @version 2.4
* @package File Index
* @copyright (C) 2008 by Nate Maxfield
**/
 
/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
 
$_MAMBOTS->registerFunction( 'onSearch', 'botSearchIndexedPDFs' );
 
/**
* Search method
* @param array Named 'text' element is the search term
*/
function botSearchIndexedPDFs( $text, $phrase='', $ordering='' ) {
  global $my, $database;
  global $mosConfig_live_site, $mosConfig_offset;
  
  $key1 = date("z");
  $key2 = date("jWH");
 
$text = trim( $text );
	if ($text == '') {
		return array();
	}
	$section 	= _WEBLINKS_TITLE;
	
	// check if param query has previously been processed
	if ( !isset($_MAMBOTS->_search_mambot_params['pdfs']) ) {
		// load mambot params info
		$query = "SELECT params"
		. "\n FROM #__mambots"
		. "\n WHERE element = 'pdfs.searchbot'"
		. "\n AND folder = 'search'"
		;
		$database->setQuery( $query );
		$database->loadObject($mambot);

	// save query to class variable
		$_MAMBOTS->_search_mambot_params['pdfs'] = $mambot;
	}

	// pull query data from class variable
	$mambot = $_MAMBOTS->_search_mambot_params['pdfs'];
	$botParams = new mosParameters( $mambot->params );

	$nonmenu	= $botParams->def( 'nonmenu', 0 );
	//echo $nonmenu;
		
	if ($nonmenu == 1){
	 	$linkURL =  " CONCAT('".$mosConfig_live_site."','/index.php?option=com_file_index&key=',id,'&name=',title) AS href,";
	}else if ($nonmenu == 2){
		$linkURL =  " CONCAT('".$mosConfig_live_site."','/index.php?option=com_file_index&key=". $key1 ."',id,'". $key2 ."','&name=',title) AS href,";
	}else{
	  	$linkURL =  " CONCAT('".$mosConfig_live_site."','/',location,title) AS href,";
	}
	
	$wheres 	= array();
	switch ($phrase) {
		case 'exact':
			$wheres2 = array();

			$wheres2[] = "LOWER(description) LIKE '%$text%'";
			$wheres2[] = "LOWER(title) LIKE '%$text%'";
			$where = '(' . implode( ') OR (', $wheres2 ) . ')';
			break;

		case 'all':
		case 'any':
		default:
			$words 	= explode( ' ', $text );
			$wheres = array();
			foreach ($words as $word) {
				$wheres2 = array();
		  		$wheres2[] 	= "LOWER(description) LIKE '%$word%'";
				$wheres2[] 	= "LOWER(title) LIKE '%$word%'";
				$wheres[] 	= implode( ' OR ', $wheres2 );
			}
			$where 	= '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
			break;
	}

 if ($my->gid > 0){
  	$database->setQuery( "SELECT title AS title,"
    . " '' AS created,"
    . " 'PDF' AS section,"
    . $linkURL
    . " '1' AS browsernav"
    . " FROM #__com_file_index"
    . " WHERE (".$where.")"
    . " ORDER BY title"
  	);
 }else{
 	$database->setQuery( "SELECT title AS title,"
    . " '' AS created,"
    . " 'PDF' AS section,"
	. $linkURL
    . " '1' AS browsernav"
    . " FROM #__com_file_index"
	. " WHERE (".$where.")"
    . " AND restricted = '0'"
    . " ORDER BY title"
	
  	);
 }
  $rows = $database->loadObjectList();
  $i=0;
  if (count($rows) > 0){
	  foreach ($rows as $row) {
	     $database->setQuery( "SELECT description FROM #__com_file_index"
		. " WHERE title = '".$row->title."'"
		);
	    $textfull = $database->loadresult();
	    $rows[$i]->text = mosSmartSubstr_pdf($textfull, 200, $text);
	    $i++;
	  }
  } //foreach
  return $rows;
}

// Add support for Joomla 1.5
function mosSmartSubstr_pdf($text, $length=200, $searchword) {
 $wordpos = strpos(strtolower($text), strtolower($searchword));
 $halfside = intval($wordpos - $length/2 - strlen($searchword));
 if ($wordpos && $halfside > 0) {
 return '...' . substr($text, $halfside, $length);
 } else {
 return substr( $text, 0, $length);
 }
}
?>