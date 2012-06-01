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

$adminPath = $mosConfig_absolute_path . '/administrator';
if (isset ($mosConfig_admin_path)) {
	$adminPath = $mosConfig_admin_path;
}
require_once("$adminPath/components/com_file_index/admin.file_index.class.php");
$indexManager = new indexManager($mosConfig_absolute_path, $adminPath);

//$cid = josGetArrayInts( 'cid' );


switch ($task) {
	case "index":
		index();
		break;
	
	case "reindex":
		reindex( $_POST['cid'] );
		break;

	case "deleteindex":
		deleteindex( $_POST['cid'] );
		break;
	
	case "fileindexed":
		fileindexed( $option, $task );
		break;
	
	case "edit":
		fileedit( $option, $_GET['id'] );
		break;
	
	case 'filecancel':
		filecancel();
		break;
	
	case 'menulink':
	case "filesave":
		filesave( $option );
		break;
	
	case "config":
		if($task=='saveedit') {
		$file_index_Public = "";
		$file_index_Private = "";
			foreach ($_POST['Index'] as $index){
			$break = explode("|", $index);
				if ($break[1] == 0){
					$file_index_Public .= $break[0].","; 
				}else if ($break[1] == 1){
					$file_index_Private .= $break[0].","; 
				}
			}
		
			$indexManager->setCfg( 'index_Public' , $file_index_Public );
			$indexManager->setCfg( 'index_Private' , $file_index_Private );
			$indexManager->saveConfiguration ($adminPath);
		}
		HTML_file_index::showConfiguration( $option, $indexManager, $file_index_Public, $file_index_Private );
		break;
		
	default:
		if($task=='saveedit') {
		$file_index_Public = "";
		$file_index_Private = "";
			foreach ($_POST['Index'] as $index){
				$break = explode("|", $index);
					if ($break[1] == 0){
						$file_index_Public .= $break[0].","; 
					}else if ($break[1] == 1){
						$file_index_Private .= $break[0].","; 
					}
			}
		
			$indexManager->setCfg( 'index_Public' , $file_index_Public );
			$indexManager->setCfg( 'index_Private' , $file_index_Private );
			$indexManager->saveConfiguration ($adminPath);
		}
		HTML_file_index::showConfiguration( $option, $indexManager, $file_index_Public, $file_index_Private );
		break;
}


function index(){
	global $mosConfig_absolute_path, $option, $Protected, $nonProtected;
	global $database;
	
	// Set an extended time limit (5 Minutes)
	set_time_limit(300);
	
	// Base Directory for the files.  Should be included in configuration?
	$BaseDir = '/';
	$ReturnResults = '';
	include 'config.file_index.php';
	
	if ($file_index_Private) {
	$Protected = explode(",", $file_index_Private);
	}
	if ($file_index_Public) {
	$nonProtected = explode(",", $file_index_Public);
	}
	
	// For Each Directory Find the PDFs and insert into the database.
	// Get all of the Diretories that need to be indexed.
	
	// Read in the protected directories
	if (count($Protected)>0){
		foreach ($Protected as $DirPro){
			$ReturnResults .= IndexFiles ($BaseDir, $DirPro, 1);
		}
	}
	
	// Read in the nonprotected directories
	if (count($nonProtected)>0){
		foreach ($nonProtected as $DirNon){
			$ReturnResults .= IndexFiles ($BaseDir, $DirNon, 0);
		}
	}
	
	// File CleanUp - Go through the DB and remove all of the file links that no longer exist.
	$query = "SELECT A.id,A.location,A.title FROM #__com_file_index AS A";
	$database->setQuery( $query );
	$rows = $database->loadObjectList();
	foreach ($rows AS $row){
		// Check and see if the file still exists on the server.
			$filename = $mosConfig_absolute_path.'/'.$row->location.$row->title;
			if (file_exists($filename)) {
			} else {
			   $database->setQuery("DELETE FROM #__com_file_index WHERE id = '$row->id'");
			   $database->query();
			   $ReturnResults .= "<tr><td nowrap>" . $row->title . " </td><td><font color=\"blue\">File no longer exists! Index Deleted</font></td><td></td></tr>";
			}
		}
	
	HTML_file_index::showFileIndex( $option,$ReturnResults );
}

function deleteindex($cid){
	global $mosConfig_absolute_path,$option,$Protected, $nonProtected;
	global $database;
	
	$ReturnResults = '';
	
	// Set an extended time limit (5 Minutes)
	set_time_limit(300);
	$ReturnResults = '';
	
	foreach ($cid AS $id){
	  $database->setQuery("DELETE FROM #__com_file_index WHERE id = '$id'");
	  $database->query();
	  $ReturnResults .= "<tr><td nowrap>" . $title . " </td><td><font color=\"blue\">Index Deleted</font></td><td></td></tr>";
	}
	
	HTML_file_index::showFileIndex( $option,$ReturnResults );
}

function reindex($cid){
	global $mosConfig_absolute_path,$option,$Protected, $nonProtected;
	global $database;
	
	// Set an extended time limit (5 Minutes)
	set_time_limit(300);
	$ReturnResults = '';
	
	foreach ($cid AS $id){
	  $ReturnResults .= ReIndexFiles($id);
	}
	
	
	HTML_file_index::showFileIndex( $option,$ReturnResults );
}

function fileindexed( $option, $task ){
	global $mosConfig_absolute_path, $mosConfig_list_limit, $Protected, $nonProtected;
	global $database, $mainframe;
	
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );

	$query = "SELECT COUNT(*)"
	. "\n FROM #__com_file_index"
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );
	
	$query = "SELECT * FROM #__com_file_index";
 
	$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $database->loadObjectList();

	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}
		
	HTML_file_index::showFileIndexed( $option, $rows, $pageNav, $task );
}

function fileedit( $option, $uid ){
	global $database, $mainframe, $my, $mosConfig_absolute_path, $option;
	
	$query = "SELECT * FROM #__com_file_index WHERE id = $uid"
	;
	$database->setQuery( $query );
	$rows = $database->loadRow();
	
	// build the html select list for the group access
	$access = array();
	$access[] = mosHTML::makeOption( '0', 'Public' );
	$access[] = mosHTML::makeOption( '1', 'Registered' );
	$access[] = mosHTML::makeOption( '2', 'Special' );
	$lists['access']	= mosHTML::selectList( $access, 'access', 'class="inputbox" size="3"', 'value', 'text', $rows[4] );
	// build the html select list for menu selection
	$lists['menuselect']		= mosAdminMenus::MenuSelect( );
	
	HTML_file_index::FileEdit( $option, $rows, $pageNav, $lists );
}

/**
* Cancels an edit operation
*/
function filecancel( ) {
	global $database;

	mosRedirect( 'index2.php?option=com_file_index&task=fileindexed' );
}

function filesave( $option ){
	global $mosConfig_absolute_path,$option,$Protected, $nonProtected;
	global $database, $mainframe, $task;
	
	
	$query = "UPDATE jos_com_file_index SET description= '".addslashes($_POST['description'])."', restricted= '".$_POST['access']."', username= '".$_POST['username']."', password= '".$_POST['password']."' WHERE id=" . $_POST['id'];
	$database->setQuery($query);
	if (!$database->query()) {
		echo $database->getErrorMsg();
		exit;
	}
	
	switch ( $task ) {
		
		case 'menulink':
			$menu = strval( mosGetParam( $_POST, 'menuselect', '' ) );
			$link = strval( mosGetParam( $_POST, 'link_name', '' ) );
		
			$link	= stripslashes( ampReplace($link) );
			
			$row = new mosMenu( $database );
			$row->menutype 		= $menu;
			$row->name 			= $link;
			$row->type 			= 'url';
			$row->published		= 1;
			$row->browsernav	= 2;
			$row->componentid	= 0;
			$row->link			= "/index.php?option=com_file_index&key=".$_POST['id']."&name=". $_POST['title'];
			$row->ordering		= 9999;
		
			if (!$row->check()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}
			if (!$row->store()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}
			$row->checkin();
			$row->updateOrder( "menutype = '$row->menutype' AND parent = $row->parent" );
			$msg = '(Link) in menu: successfully created';
			mosRedirect( 'index2.php?option=com_file_index&task=edit&id='. $_POST['id'], $msg  );
			break;

		case 'filesave':
		default:
			mosRedirect( 'index2.php?option=com_file_index&task=fileindexed' );

			break;
	}
	
	HTML_file_index::FileSave( );
}

// Function to insert the PDFs into the DB.
function IndexFiles ($BaseDir, $Dir, $restricted = '0', $filetype = '.pdf'){
	global $mosConfig_absolute_path;
	global $database;
	// Find the files in that directory that are PDFs.
	
	//Compare each file to the DB if it exists then compare the file size.  If it is different delete from the DB.  If the Same Skip.  If it does exist then index it.
	
	//Find files that have been deleted and delete them from the DB
		
		$results = '';
		$PDFdir = $mosConfig_absolute_path . $BaseDir . $Dir;
		if ($handle = opendir($PDFdir)) {
    		while (false !== ($file = readdir($handle))) { 
        		if (substr(strtolower($file), -4) == $filetype) { 
            		// Read the file.
					$original_name = $mosConfig_absolute_path . $BaseDir . $Dir . $file;
					$fileSize = filesize($original_name);
					
					// 0 equals reindex
					$NoIndex = 0;
					
					// Check and see if the file is already indexed.
					$query = "SELECT A.id,A.filesize FROM #__com_file_index AS A WHERE A.title = '" . $file ."'";
					$database->setQuery( $query );
					$row = $database->loadRow();
					
					// check to see if it is in the DB					
					if ($row[0] <> ''){
						// if it is already in the DB check to see if the file size is the same.
						if ($row[1] == $fileSize){
							// the file size is the same do not reindex.
							$NoIndex = 1;
						}
					}
					 
					if ($NoIndex == 0){
					
					// detect if it is windows.
					if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
					 $command = $mosConfig_absolute_path . "/administrator/components/com_file_index/includes/pdftotext.exe \"$original_name\" -"; 
					 $handle2 = popen($command." 2>&1", 'r'); 
					} else {
						if (file_exists("/usr/bin/pdftotext")){
							// hopefully this will fix some of the freebsd errors.
							$handle2 = popen("/usr/bin/pdftotext \"$original_name\" - 2>&1", 'r');
						}else{
							$handle2 = popen($mosConfig_absolute_path . "/administrator/components/com_file_index/includes/pdftotext \"$original_name\" - 2>&1", 'r');
						}
   					}
					
					$contents = '';
					if($handle2){
						while (!feof($handle2)) {
						  set_time_limit(0);
						  $contents .= fread($handle2, 8192);
						  ob_flush();
						}
					}
					// add slashes so it is mySQL safe.
					$read = addslashes($contents);
					
					// removes the "Error: PDF version 1.6 -- xpdf supports version 1.5 (continuing anyway)"
					if (strpos( $read, "Error: PDF version 1.6") === false){
						// it's ok don't do a thing.
					} else {
						$read = substr($read, 71);
					}
					
					if ($row[0]<>''){
					//Update the info in the DB
					$database->setQuery("UPDATE #__com_file_index SET location = '$Dir', description = '$read' WHERE id = '$row[0]'");
					}else{
					//Insert the info into the DB
					$database->setQuery("INSERT INTO #__com_file_index SET title = '$file', location = '$Dir', description = '$read', restricted = '$restricted', filesize = '$fileSize'");
					}
						
						if (!$database->query()) {
							echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
						}
						if ($row[0]<>''){
						$LastID = $row[0];
						}else{
						$LastID = mysql_insert_id();
						}
						
							if (strstr ( $read, "Error: " ) == FALSE && $read <> ''){
								$results .= "<tr><td nowrap><a href=\"index2.php?option=com_file_index&task=edit&id=" . $LastID . "\">" . $file . "</a>" . "  </td><td><font color=\"#008000\">sucessfully indexed (".$fileSize." bytes)</font></td><td></td></tr>";
							}else if ($read == ''){
								$results .= "<tr><td nowrap><a href=\"index2.php?option=com_file_index&task=edit&id=" . $LastID . "\">" . $file . "</a>" . "  </td><td nowrap><font color=\"#ff0000\">May not have been indexed (".$fileSize." bytes)</font></td><td><font color=\"#ff0000\">Could not read contents of the file.</font></td></tr>";
							}else{
								$results .= "<tr><td nowrap><a href=\"index2.php?option=com_file_index&task=edit&id=" . $LastID . "\">" . $file . "</a>" . "  </td><td nowrap><font color=\"#ff0000\">May not have been indexed (".$fileSize." bytes)</font></td><td><font color=\"#ff0000\">".strtok($contents,15)."...</font></td></tr>";
							}
					// Close the file 
					pclose($handle2);  
					}else{
						$results .= "<tr><td nowrap>" . $file . " </td><td><font color=\"#666666\">Previously Indexed</font></td><td></td></tr>";
					}
        		} 
			}
		 	closedir($handle); 
		}
		return $results;
}

// Function to insert the PDFs into the DB.
function ReIndexFiles($id){
	global $mosConfig_absolute_path;
	global $database;
	$results = '';
	$query = "SELECT A.id,A.username,A.password,A.location,A.title FROM #__com_file_index AS A WHERE A.id = '" . $id ."'";
	$database->setQuery( $query );
	$row = $database->loadRow();
	$original_name = $mosConfig_absolute_path . "/" . $row[3] . $row[4];
	$fileSize = filesize($original_name);
	
	// detect if it is windows.
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$command = $mosConfig_absolute_path . "/administrator/components/com_file_index/includes/pdftotext.exe \"$original_name\" -"; 
		$handle2 = popen($command." 2>&1", 'r'); 
			} else {
				if (file_exists("/usr/bin/pdftotext")){
					// hopefully this will fix some of the freebsd errors.
					$handle2 = popen("/usr/bin/pdftotext \"$original_name\" - 2>&1", 'r');
				}else{
					$handle2 = popen($mosConfig_absolute_path . "/administrator/components/com_file_index/includes/pdftotext \"$original_name\" - 2>&1", 'r');
				}
   			}
	
	$contents = '';
	
	if($handle2){
		while (!feof($handle2)) {
		  set_time_limit(0);
		  $contents .= fread($handle2, 8192);
		  ob_flush();
		}
	}
	// add slashes so it is mySQL safe.
	$read = addslashes($contents);
					
	// removes the "Error: PDF version 1.6 -- xpdf supports version 1.5 (continuing anyway)"
	if (strpos( $read, "Error: PDF version 1.6") === false){
		// it's ok don't do a thing.
	} else {
		$read = substr($read, 71);
	}
					
	//Update the info in the DB
	$database->setQuery("UPDATE #__com_file_index SET filesize = '$fileSize', description = '$read' WHERE id = '$id'");
	
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}
	
	if (strstr ( $read, "Error: " ) == FALSE && $read <> ''){
		$results .= "<tr><td nowrap>" . $row[4] . " </td><td><font color=\"#008000\">sucessfully indexed (".$fileSize." bytes)</font></td><td></td></tr>";
	}else if ($read == ''){
		$results .= "<tr><td nowrap><a href=\"index2.php?option=com_file_index&task=edit&id=" . $id . "\">" . $row[4] . "</a>" . "  </td><td nowrap><font color=\"#ff0000\">May not have been indexed (".$fileSize." bytes)</font></td><td><font color=\"#ff0000\">Could not read contents of the file.</font></td></tr>";
	}else{
		$results .= "<tr><td nowrap><a href=\"index2.php?option=com_file_index&task=edit&id=" . $id . "\">" . $row[4] . "</a>" . "  </td><td nowrap><font color=\"#ff0000\">May not have been indexed (".$fileSize." bytes)</font></td><td><font color=\"#ff0000\">".strtok($contents,15)."...</font></td></tr>";
	}
	return $results;
}

?>

