<?php
/**
* PDF Index - A Mambo/Joomla PDF Indexing Component

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

defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

require_once( $mainframe->getPath( 'admin_html' ) );

switch ($task) {
	case "read":
		read( $_GET['key'] );
		break;
	
	default:
		read( $_GET['key'] );
		break;
}

function read( $key ){
	global $mosConfig_absolute_path, $option, $Protected, $nonProtected;
	global $my, $database;
	
	//Remove the date from the fileid
	$name = $_GET['name'];
	$key1 = date("z");
	$key2 = date("jWH");
	$pos = strrpos($key, $key2);
	
	if ($pos > 0) {
		$key = str_replace($key2, '', $key);
		echo "Key: " . $key . "<br>";
		$cnt = strlen($key1);
		echo "Count: " . $cnt . "<br>";
		$key = substr($key, $cnt);
		echo "Key: " . $key . "<br>";
		
	}

	$query = "SELECT a.title, a.location, a.filesize FROM #__com_file_index AS a WHERE id = '$key' AND title = '$name' AND a.restricted <= '$my->gid'";
	//echo $query;
	$database->setQuery( $query );
	$row = $database->loadRow();

	if (isset($row[0])) {
	    $filename = $row[1] . $row[0];
	 	if( ! is_file($filename) || $filename[0] == '.' || $filename[0] == '/' )
		die("Bad access attempt.\n");
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=".basename($filename).";");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($filename));
		readfile("$filename");
		exit();
	} else {
	    echo "No file found or Permission denied";
	}
}
// Handy Function that is not being used here.
function getSessionId() {
global $mainframe;

if( is_callable( array( 'mosMainframe', 'sessionCookieName'))){ 
	// Joomla >= 1.0.8
	$sessionCookieName = mosMainFrame::sessionCookieName();
	$sessionCookie = mosGetParam( $_COOKIE, $sessionCookieName, null );
	return mosMainFrame::sessionCookieValue( $sessionCookie );
}elseif( is_callable( array('mosSession', 'getCurrent' ))){
	// Mambo 4.6
	$session =& mosSession::getCurrent();
	return $session->session_id;
}elseif( !empty( $mainframe->_session->session_id )){
	// Mambo <= 4.5.2.3 and Joomla <= 1.0.7
	return $mainframe->_session->session_id;
}
}
?>
