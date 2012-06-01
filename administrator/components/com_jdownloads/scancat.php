<?php
/**
* jDownloads 
* @version 1.7.2
* @package 
* @copyright (C) 2010 by Arno Betz - www.jdownloads.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
*  
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="content-language" content="en">
<title>jDownloads - check download area</title>

</head>
<body>
<style type="text/css">
BODY
{
 FONT-FAMILY: Verdana;
 FONT-SIZE: 8pt;
 COLOR: #222222;
 background-color: #FFFFCC;
 padding: 15;
}
</style>
<?php
Error_Reporting(E_ERROR);

/* Initialize Joomla framework */
define( '_JEXEC', 1 );
define('JPATH', dirname(__FILE__) );
define( 'DS', DIRECTORY_SEPARATOR );
$parts = explode( DS, JPATH );  
$j_root =  implode( DS, $parts ) ;
$x = array_search ( 'administrator', $parts  );
if (!$x) exit;
for($i=0; $i < $x; $i++){
    $path = $path.$parts[$i].DS; 
}
define('JPATH_BASE', $path );
define('JPATH_SITE', $path );
/* Required Files */
require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );
/* To use Joomla's Database Class */
require_once ( JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'factory.php' );
require_once ( JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table.php' );
// jDownloads database tables class
require_once ( JPATH_BASE.DS.'components'.DS.'com_jdownloads'.DS.'jdownloads.class.php' );
/* Create the Application */
$mainframe =& JFactory::getApplication('site');
$root_path = JPATH_BASE; 
require_once 'ProgressBar.class.php';
$database = &JFactory::getDBO();
$mainframe =& JFactory::getApplication('site');
// get jd config
$jlistConfig = buildjlistConfig();
$task = 'scan.files';
$lang =& JFactory::getLanguage();
$lang->load('com_jdownloads');
$mainframe->setPageTitle( JText::_('JLIST_RUN_MONITORING_TITLE'));

echo '<div  style="font-family:Verdana; font-size:10"><b>'.JText::_('JLIST_RUN_MONITORING_INFO2').'</b><br />'.JText::_('JLIST_RUN_MONITORING_INFO').'<br /><br />';

$time_start = microtime_float();
$selected_cat_id  = intval(JArrayHelper::getValue($_REQUEST, 'id', 0));
if ($selected_cat_id){  
    $database->setQuery("SELECT cat_dir, cat_id FROM #__jdownloads_cats WHERE cat_id = '$selected_cat_id'");
    $selected_cat = $database->loadObject();
    if ($selected_cat){
        echo JText::_('JLIST_RUN_MONITORING_INFO8').'<br /><font color="#990000">'.JPATH_SITE.$jlistConfig['files.uploaddir'].'/'.$selected_cat->cat_dir.'</font><br /><br /></div>';    
        flush(); 
        checkFiles($selected_cat);
    } else {
        echo 'Error: Selected category not found!';
        exit;
    }    
}
$time_end = microtime_float();
$time = $time_end - $time_start;

echo '<br /><small>The scan duration: '.number_format ( $time, 2).' seconds.</small>'; 
echo '</body></html>';


/* checkFiles
/
/ check uploaddir and subdirs for variations
/ 
/
*/
function checkFiles($selected_cat) {
    global $jlistConfig, $lang;
    ini_set('max_execution_time', '600');
    ignore_user_abort(true); 
    
    $database = &JFactory::getDBO();
    //check if all files and dirs in the uploaddir directory are listed
        if(file_exists(JPATH_SITE.$jlistConfig['files.uploaddir'])){
          $startdir       = JPATH_SITE.$jlistConfig['files.uploaddir'].'/'.$selected_cat->cat_dir;
          $dir_len_cat_dir = strlen($selected_cat->cat_dir);
          $dir_len      = strlen($startdir) - $dir_len_cat_dir;
          $dir          = $startdir;
          $only         = FALSE;
          $type         = array();
          if ($jlistConfig['all.files.autodetect']){
              $allFiles     = true;
          } else {   
              $allFiles     = FALSE;
              $type =  explode(',', $jlistConfig['file.types.autodetect']);
          }    
          $recursive    = FALSE;
          $onlyDir      = TRUE;
          $files        = array();
          $file         = array();
          
          $dirlist      = array();
          
          $new_files       = 0;
          $new_dirs_found  = 0;
          $new_dirs_create = 0;
          $new_dirs_errors = 0;
          $new_dirs_exists = 0;
          $new_cats_create = 0;
          $log_message     = '';
          $success         = FALSE;   
          
          $log_array = array();          
          
          // ********************************************   
          // first search new categories
          // ********************************************   
          
          clearstatcache();
          $searchdir    = $startdir;
          $searchdirs   = array();
          $dirlist = searchdir($searchdir);
          
          $no_writable = 0;
          for ($i=0; $i < count($dirlist); $i++) {
                  if (!is_writable($dirlist[$i])){
                      $no_writable++;
                  }
                  $dirlist[$i] = str_replace($searchdir, '', $dirlist[$i]);
                  // delete last slash /
                  if ($pos = strrpos($dirlist[$i], '/')){
                      $dirlist[$i] = str_replace('/', '', $dirlist[$i]);
                      $searchdirs[] = substr($dirlist[$i], 0, $pos);
                  }
                  // $dirlist[$i] = substr($dirlist[$i], 0, $pos);
                  // $searchdirs[] = $dirlist[$i];
          }  
          unset($dirlist);
          $count_cats = count($searchdirs);
          // first progressbar for cats
          $title1 = JText::_('JLIST_RUN_MONITORING_INFO3');
          $bar = new ProgressBar();
          $bar->setMessage($title1);
          $bar->setAutohide(false);
          $bar->setSleepOnFinish(0);
          $bar->setPrecision(100);
          $bar->setForegroundColor('#990000');
          $bar->setBackgroundColor('#CCCCCC');
          $bar->setBarLength(300);
          $bar->initialize($count_cats-1); // print the empty bar

          for ($i=0; $i < count($searchdirs); $i++) {
             $dirs = explode('/', $searchdirs[$i]);
             $sum = count($dirs);
             // this characters are not allowed in foldernames
             if (!eregi("[?!:;\*@#%~=\+\$\^'\"\(\)\<\>]", $searchdirs[$i])) {              
               // check that folder exist
               $searched_dir = $selected_cat->cat_dir.'/'.$searchdirs[$i];
               $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_cats WHERE cat_dir = '$searched_dir'");
               $cat_da = $database->loadResult(); 
               // when not exist - add it
               if (!$cat_da) {
                   $new_dirs_found++;
                   // create new cat
                   $row = new jlist_cats($database);
                   // bind it to the table
                   if (!$row -> bind($_POST)) {
                       echo "<script> alert('".$row -> getError()."'); window.history.go(-1); </script>\n";
                       exit();
                   }
                   $row->cat_description = '';    
                   $row->cat_title = $dirs[$sum - 1];
                   $row->cat_pic = $jlistConfig['cat.pic.default.filename'];                                 
                   if ($sum) {
                       $row->parent_id = $selected_cat->cat_id;                        
                   }    
                   $row->cat_alias = $row->cat_title;
                   $row->cat_alias = JFilterOutput::stringURLSafe($row->cat_alias);
                   if(trim(str_replace('-','',$row->cat_alias)) == '') {
                        $datenow =& JFactory::getDate();
                        $row->cat_alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
                   }
                   $row->cat_dir = $searched_dir;
                   
                   // set publishing?
                   if ($jlistConfig['autopublish.founded.files']){
                       $row->published = 1;
                   } else {
                       $row->published = 0;
                   }    
                   
                   // get a correct ordering value
                   if (!$row->ordering) {
                       $row->ordering = $row->getNextOrder();
                   }    
                   if (!$row -> store()) {
                       echo "<script> alert('".$row -> getError()."'); window.history.go(-1); </script>\n";
                       exit();
                   } else {
                       if(!$row->cat_id) $row->cat_id = mysql_insert_id();
                   }
                   $new_cats_create++;
                   $log_array[] = strftime($jlistConfig['global.datetime']).' - '.JText::_('JLIST_AUTO_CAT_CHECK_ADDED').' <b>'.$searched_dir.'</b><br />';
               }
             }  else {
                // folder with illegal characters in name founded - create msg
                $log_array[] = strftime($jlistConfig['global.datetime']).' -  <b>'.$searchdirs[$i].'</b><font color="red"> '.JText::_('JLIST_AUTO_CAT_CHECK_ILLEGAL_NAME_FOUND_MSG').'</font><br />';
             }
             $bar->increase(); // calls the bar with every processed element    
          }
          echo '<small><br />'.JText::_('JLIST_BACKEND_AUTOCHECK_SUM_FOLDERS').' '.count($searchdirs).'<br /><br /></small>';   
           flush();
          unset($dirs);
          unset($searchdirs);
          
          // ********************************************
          // Prüfen ob alle publishte cat-dirs existieren
          // ********************************************
          
          $mis_cats = 0;
          $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE published=1 AND cat_dir LIKE '".$selected_cat->cat_dir.'/'."%'");
          $cats = $database->loadObjectList();
          
          $count_cats = count($cats);
          // first progressbar for cats
          $bar = new ProgressBar();
          $title2 = JText::_('JLIST_RUN_MONITORING_INFO4');  
          $bar->setMessage($title2);
          $bar->setAutohide(false);
          $bar->setSleepOnFinish(0);
          $bar->setPrecision(100);
          $bar->setForegroundColor('#990000');
          $bar->setBarLength(300);
          $bar->initialize($count_cats); // print the empty bar          
          
          foreach($cats as $cat){
                $cat_dir = JPATH_SITE.$jlistConfig['files.uploaddir'].'/'.$cat->cat_dir;
                // wenn nicht da - unpublishen
                if(!is_dir($cat_dir)){
                    $database->setQuery("UPDATE #__jdownloads_cats SET published = 0 WHERE cat_id = '$cat->cat_id'");
                    $database->query();
                    $mis_cats++;
                    $log_array[] = strftime($jlistConfig['global.datetime']).' - <font color="red">'.JText::_('JLIST_AUTO_CAT_CHECK_DISABLED').' <b>'.$cat->cat_dir.'</b></font><br />';
               } 
               $bar->increase(); // calls the bar with every processed element  
          }
          echo '<br /><br />';   
           // when add categories - the access rigts must checked from all
          if ($new_cats_create){
              $sum = set_rights_of_cat (0, '00', $sum);    // all cats will checked   
          }   
           flush();
          unset($cats);
          
          // ****************************************************             
          // search all files and compare it with the files table
          // ****************************************************   
                    
          $all_dirs = scan_dir($dir.'/', $type, $only, $allFiles, $recursive, $onlyDir, $files);
          if ($all_dirs != FALSE) {
              $count_files = count($files);
              // first progressbar for cats
              $bar = new ProgressBar();
              $title3 = JText::_('JLIST_RUN_MONITORING_INFO5');  
              $bar->setMessage($title3);
              $bar->setAutohide(false);
              $bar->setSleepOnFinish(0);
              $bar->setPrecision(100);
              $bar->setForegroundColor('#990000');
              $bar->setBarLength(300);
              $bar->initialize($count_files); // print the empty bar          
              
              reset ($files);
              $new_files = 0;
              foreach($files as $key3 => $array2) {
                  $filename = $files[$key3]['file'];
                   if ($filename <> '') {
                         $dir_path_total = $files[$key3]['path'];
                         $restpath = substr($files[$key3]['path'], $dir_len);
                         $only_dirs = substr($restpath, 0, strlen($restpath)-1);
                         // existiert filename in files?
                         $exist_file = false;
                         $database->setQuery("SELECT cat_id FROM #__jdownloads_files WHERE url_download = '".utf8_encode($filename)."'");
                         $row_file_exists = $database->loadObjectList();
                         // wenn da - in cats suchen
                         if ($row_file_exists) {
                            foreach ($row_file_exists as $row_file_exist) {
                              if (!$exist_file) { 
                                $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_cats WHERE cat_dir = '$only_dirs' AND cat_id = '$row_file_exist->cat_id'" );
                                $row_cat_find = $database->loadResult();               
                               
                                if ($row_cat_find) {
                                    $exist_file = true;
                                } else {
                                   $exist_file = false;                                    
                                }    
                              }
                            }     
                         }  else {
                              $exist_file = false;
                         }    
                         
                         if(!$exist_file) {
                           // not check the filename when restore backup file
                              $filename_new = checkFileName($filename);
                              if ($filename_new != $filename) {
                                if (utf8_decode($filename_new) != $filename){
                                  $success = @rename($startdir.$only_dirs.'/'.$filename, $startdir.$only_dirs.'/'.$filename_new); 
                                  if ($success) {
                                      $filename = $filename_new; 
                                  } else {
                                      // could not rename filename
                                  }
                                } else {
                                  $filename = $filename_new;
                                }     
                              }

                            $database->setQuery("SELECT cat_id FROM #__jdownloads_cats WHERE cat_dir = '$only_dirs'");
                            $cat_id = $database->loadResult();
                            if ($cat_id) {
                                $file_extension = strtolower(substr(strrchr($filename,"."),1)); 
                                // fill the data
                                $file_file_id = 0;
                                $file_url_download   = $filename;
                                $file_file_title     = str_replace('.'.$file_extension, '', $filename); 
                                $file_size           = $files[$key3]['size'];
                                $file_description    = '';                                                                                       
                                $file_date_added     = JHTML::_('date', 'now','%Y.%m.%d %H:%M:%S');
                                $file_file_date      = '0000-00-00 00:00:00';
                                $file_cat_id         = $cat_id;
                                $file_file_alias = $file_file_title;
                                $file_file_alias = JFilterOutput::stringURLSafe($file_file_alias);
                                if(trim(str_replace('-','',$file_file_alias)) == '') {
                                    $datenow =& JFactory::getDate();
                                    $file_file_alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
                                }
                                $filepfad = JPATH_SITE.'images/jdownloads/fileimages/'.$file_extension.'.png';
                                if(file_exists(JPATH_SITE.'images/jdownloads/fileimages/'.$file_extension.'.png')){
                                    $file_file_pic       = $file_extension.'.png';
                                } else {
                                    $file_file_pic       = $jlistConfig['file.pic.default.filename'];
                                }
                                $file_created_by     = JText::_('JLIST_AUTO_FILE_CHECK_IMPORT_BY');
                                // publish only when option is activated
                                if ($jlistConfig['autopublish.founded.files']){
                                    $file_published = 1;
                                } else {
                                    $file_published = 0;
                                }    
                                
                                $database->setQuery("INSERT INTO #__jdownloads_files (`file_id`, `file_title`, `file_alias`, `description`, `description_long`, `file_pic`, `thumbnail`, `price`, `release`, `language`, `system`, `license`, `url_license`, `update_active`, `cat_id`, `metakey`, `metadesc`, `size`, `date_added`, `file_date`, `publish_from`, `publish_to`, `url_download`, `extern_file`, `url_home`, `author`, `url_author`, `created_by`, `created_mail`, `modified_by`, `modified_date`, `submitted_by`, `downloads`, `ordering`, `published`, `checked_out`, `checked_out_time`)
                                VALUES ('', '$file_file_title', '$file_file_alias', '$file_description', '$file_description_long', '$file_file_pic', '', '', '', '', '', '', '', '', '$file_cat_id', '', '', '$file_size', '$file_date_added', '$file_file_date', '', '', '$file_url_download', '', '', '', '', '$file_created_by', '', '', '', '', '', '$file_ordering', '$file_published', '0', '0000-00-00 00:00:00')");
                                if (!$database->query()) {
                                    echo $database->stderr();
                                    exit;
                                } else {
                                    if (!$file_file_id) $file_file_id = mysql_insert_id();
                                    if ($file_file_id){
                                        $database->setQuery("SELECT max(ordering) FROM #__jdownloads_files WHERE cat_id = '$file_cat_id'");
                                        $ord = (int)$database->loadResult() + 1;
                                        $database->setQuery("UPDATE #__jdownloads_files SET ordering = '$ord' WHERE file_id = '$file_file_id'");     
                                        $database->query();
                                    }    
                                }    
                                $new_files++;
                                $log_array[] = strftime($jlistConfig['global.datetime']).' - '.JText::_('JLIST_AUTO_FILE_CHECK_ADDED').' <b>'.$only_dirs.'/'.utf8_decode($filename).'</b><br />';
                            } else {
                                // cat dir not exist or invalid name
                                
                            }        
                         }                   
                      
                  }
                  $bar->increase(); // calls the bar with every processed element
                   
              }  
          }                    
          echo '<small><br />'.JText::_('JLIST_BACKEND_AUTOCHECK_SUM_FILES').' '.count($files).'<br /><br /></small>';   
          unset($database->_log);
          unset($files);
          flush();
          
          //prüfen ob download dateien alle physisch vorhanden - sonst unpublishen
          $mis_files = 0;
          $database->setQuery("SELECT * FROM #__jdownloads_files WHERE published=1 AND cat_id ='".$selected_cat->cat_id."'");
          $files = $database->loadObjectList();
          
          $count_files = count($files);
          // first progressbar for cats
          $bar = new ProgressBar();
          $title4 = JText::_('JLIST_RUN_MONITORING_INFO6');
          $bar->setMessage($title4);
          $bar->setAutohide(false);
          $bar->setSleepOnFinish(0);
          $bar->setPrecision(100);
          $bar->setForegroundColor('#990000');
          $bar->setBarLength(300);
          $bar->initialize($count_files); // print the empty bar

          foreach($files as $file){
              // nur interne files testen
              if ($file->url_download <> ''){   
                $database->setQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '$file->cat_id'");
                $cat_dir = $database->loadResult();  
                $cat_dir_long = $startdir.'/'.utf8_decode($file->url_download);
                // wenn nicht da - unpublishen
                if(!is_file($cat_dir_long)){
                    $database->setQuery("UPDATE #__jdownloads_files SET published = 0 WHERE file_id = '$file->file_id'");
                    $database->query();
                    $mis_files++;
                    $log_array[] = strftime($jlistConfig['global.datetime']).' - <font color="red">'.JText::_('JLIST_AUTO_FILE_CHECK_DISABLED').' <b>'.$startdir.'/'.utf8_decode($file->url_download).'</b></font><br />';
               }  
             }
             $bar->increase(); // calls the bar with every processed element 
          }
          echo '<br /><br />';
          echo '<div style="font-family:Verdana; font-size:10"><b>'.JText::_('JLIST_RUN_MONITORING_INFO7').'<br />';
          if ($new_dirs_found){
              echo JText::_('JLIST_RUN_MONITORING_INFO9').'</b><br /><br /></div>';
          } else {
              echo '</b><br /></div>';
          }    
          flush(); 
       
       // save log
       foreach ($log_array as $log) {
            $log_message .= $log;
       }
       $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '$log_message' WHERE setting_name = 'last.log.message'");
       $database->query();
       $jlistConfig['last.log.message'] = $log_message;
          
       echo '<table width="100%"><tr><td><font size="1" face="Verdana">'.JText::_('JLIST_BACKEND_AUTOCHECK_TITLE').'</font><br />';
                           
            
            if ($new_cats_create > 0){
                echo '<font color="#FF6600" size="1" face="Verdana"><b>'.$new_cats_create.' '.JText::_('JLIST_BACKEND_AUTOCHECK_NEW_CATS').'</b></font><br />';
            } else {
                echo '<font color="green" size="1" face="Verdana"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_NEW_CATS').'</b></font><br />';
            }
            
            if ($new_files > 0){
                echo '<font color="#FF6600" size="1" face="Verdana"><b>'.$new_files.' '.JText::_('JLIST_BACKEND_AUTOCHECK_NEW_FILES').'</b></font><br />';
            } else {
                echo '<font color="green" size="1" face="Verdana"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_NEW_FILES').'</b></font><br />';
            }            
            
            if ($mis_cats > 0){
                echo '<font color="#990000" size="1" face="Verdana"><b>'.$mis_cats.' '.JText::_('JLIST_BACKEND_AUTOCHECK_MISSING_CATS').'</b></font><br />';
            } else {
                echo '<font color="green" size="1" face="Verdana"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_MISSING_CATS').'</b></font><br />';
            }    
            
            if ($mis_files > 0){
                echo '<font color="#990000"  size="1" face="Verdana"><b>'.$mis_files.' '.JText::_('JLIST_BACKEND_AUTOCHECK_MISSING_FILES').'</b><br /></td></tr></table>';
            } else {
                echo '<font color="green" size="1" face="Verdana"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_MISSING_FILES').'</b><br /></td></tr></table>';
            }
        
            if ($log_message)  echo '<table width="100%"><tr><td><font size="1" face="Verdana">'.JText::_('JLIST_BACKEND_AUTOCHECK_LOG_TITLE').'<br />'.$log_message.'</font></td></tr></table>';

         
        } else {
            // error upload dir not exists
            echo '<font color="red"><b>'.JText::_('JLIST_AUTOCHECK_DIR_NOT_EXIST').'<br /><br />'.JText::_('JLIST_AUTOCHECK_DIR_NOT_EXIST_2').'</b></font>';
            
        }
                
}


// get all dirs und subdirs for upload
// $path : path to browse
// $maxdepth : how deep to browse (-1=unlimited)
// $mode : "FULL"|"DIRS"|"FILES"
// $d : must not be defined

function searchdir ( $path , $maxdepth = -1 , $mode = "DIRS" , $d = 0 ) {
   if ( substr ( $path , strlen ( $path ) - 1 ) != '/' ) { $path .= '/' ; }
   $dirlist = array () ;
   if ( $mode != "FILES" ) {
       $dirlist[] = $path ;
   }
   if ( $handle = opendir ( $path ) ) {
       while ( false !== ( $file = readdir ( $handle ) ) ) {
           if ( $file != '.' && $file != '..' ) {
               $file = $path . $file ;
               if ( ! is_dir ( $file ) ) {
                  if ( $mode != "DIRS" ) {
                   $dirlist[] = $file ;
                  }
               }
               elseif ( $d >=0 && ($d < $maxdepth || $maxdepth < 0) ) {
                   $result = searchdir ( $file . '/' , $maxdepth , $mode , $d + 1 ) ;
                   $dirlist = array_merge ( $dirlist , $result ) ;
               }
               }
       }
       closedir ( $handle ) ;
   }
   if ( $d == 0 ) { 
       natcasesort ( $dirlist ) ;
   }
   return ( $dirlist ) ;
}

function scan_dir($dir, $type=array(),$only=FALSE, $allFiles=FALSE, $recursive=TRUE, $onlyDir="", &$files){
    $handle = @opendir($dir);
    if(!$handle)
        return false;
    while ($file = @readdir ($handle))
    {
        if (eregi("^\.{1,2}$",$file))
        {
            continue;
        }
        if(!$recursive && $dir != $dir.$file."/")
        {
            if(is_dir($dir.$file))
                continue;
        }
        if(is_dir($dir.$file))
        {
            scan_dir($dir.$file."/", $type, $only, $allFiles, $recursive, $file, $files);
        }
        else
        {
   if($only)
                $onlyDir = $dir;

            $files = buildArray($dir,$file,$onlyDir,$type,$allFiles,$files);
        }
    }
    @closedir($handle);
    return $files;
}

/**
 * Füllt das Array mit den Dateiinformationen
 * (Pfad, Verzeichnisname, Dateiname, Dateigröße, letzte Aktualisierung
 *
 * @param        string    $dir             Pfad zum Verzeichnis
 * @param        string    $file            enthält den Dateinamen
 * @param        string    $onlyDir        Enthält nur den Verzeichnisnamen
 * @param        array        $type        Suchmuster dateitypen
 * @param        bool        $allFiles    Listet alle Dateien in den Verzeichnissen auf ohne Rücksicht auf $type
 * @param        array        $files        Enthält den Inhalt der Verzeichnisstruktur
 * @return    array                        Das Array mit allen Dateinamen
 */
function buildArray($dir,$file,$onlyDir,$type,$allFiles,$files) {

    $typeFormat = FALSE;
    foreach ($type as $item)
  {
      if (strtolower($item) == substr(strtolower($file), -strlen($item)))
            $typeFormat = TRUE;
    }

    if($allFiles || $typeFormat == TRUE)
    {
        if(empty($onlyDir))
            $onlyDir = substr($dir, -strlen($dir), -1);
        $files[$dir.$file]['path'] = $dir;
        $files[$dir.$file]['file'] = $file;
        $files[$dir.$file]['size'] = fsize($dir.$file);
        $files[$dir.$file]['date'] = filemtime($dir.$file);
    }
    return $files;
}

function fsize($file) {
        $a = array("B", "KB", "MB", "GB", "TB", "PB");

        $pos = 0;
        $size = filesize($file);
        while ($size >= 1024) {
                $size /= 1024;
                $pos++;
        }

        return round($size,2)." ".$a[$pos];
}
  
function buildjlistConfig(){
    $database = &JFactory::getDBO();
    $jlistConfig = array();
    $database->setQuery("SELECT setting_name, setting_value FROM #__jdownloads_config");
    $jlistConfigObj = $database->loadObjectList();
    if(!empty($jlistConfigObj)){
        foreach ($jlistConfigObj as $jlistConfigRow){
            $jlistConfig[$jlistConfigRow->setting_name] = $jlistConfigRow->setting_value;
        }
    }
    return $jlistConfig;
}

function set_rights_to_tree($p_catid, $p_right, $p_right_from, &$p_changed){
// function coded by pelma
// Funktion welche die Rechte eines Kategoriebaum setzt. Achtung REKURSIV !!!
// $p_catid      = ID der Kategorie deren Rechte gesetzt werden soll.
// $p_right      = Die Rechte welche gesetzt werden.
// $p_right_from = Die ursprünglichen Rechte
// $p_changed    = Anzahl der Korrekturen   
// echo $p_catid.' p_right_from:'.$p_right_from.' p_right:'.$p_right.'<br />';
    $database = &JFactory::getDBO();
    // Lesen der Kategorie aus der Datenbank.
    $l_sql = "SELECT cat_access FROM #__jdownloads_cats WHERE cat_id = ".$p_catid;
    $database->setQuery($l_sql);
    $r_catrow = $database->loadObjectList();

    // Hier werden die eigentlichen Rechte der aktuellen Kategorie gesetzt.
    //  Falls die Rechte der aktuellen Kategorie KLEINER sind als die zu setzenden Rechte.
    //  Damit wird verhindert, dass Unterkategorien welche schon höhere Rechte haben nicht überschrieben werden.
    // Oder
    //  Falls die Rechte der aktuellen Kategorie kleiner oder gleich sind als die ursprünglichen Rechte.
    //  Sonst können kleinere Werte (=höhere Rechte) nicht gesetzt werden.
    if (($r_catrow[0]->cat_access < $p_right) || ($r_catrow[0]->cat_access <= $p_right_from)){
      $l_sql = "UPDATE #__jdownloads_cats SET cat_access = '".$p_right."' WHERE cat_id = ".$p_catid;
      $database->setQuery($l_sql);
      $database->query();
      if ($p_changed != -1){
          $p_changed++;
      }    
    }

    // Alle Unterkategorien der aktuellen Kategorie aus der Datenbank lesen.
    // d.h. Alle Kategorien deren parent_id der aktuellen KategorienID entsprechen.
    $l_sql = "SELECT cat_id FROM #__jdownloads_cats WHERE parent_id = ".$p_catid;
    $database->setQuery($l_sql);
    $l_childrows = $database->loadObjectList();
    if (!isset($l_childrows[0])){
      // Keine Unterkategorien gefunden, d.h. das Ende des aktuellen Kategorienbaumes ist erreicht. Die Funktion verlassen.
      // Falls die Funktion in der Rekursivität ist, wird in der unteren foreach-Schleife die nächste Unterkategorie aufgerufen.
       return;
    }
    // Alle Unterkategorien abfahren.
    foreach ($l_childrows as $l_childrow){
      // Zuerst: Automatische Korrektur von Fehlern.
      // D.h. Eine Unterkategorie welche schon niedrigere Rechte hat (=höheren Wert in cat_access) müsste eigentlich nicht abgefahren werden.
      // Es könnte aber sein, dass diese Fehlern aufweist (z.B. bei einem Update von 1.3 nach 1.4).
      // Fehler heisst in diesem Fall, dass eine Unter-Unter-Kategorie grössere Rechte hat (=niedriger Wert in cat_access).
      // Dies ist ja verboten und muss korrigiert werden.
      // Dazu:
      // Die aktuelle Unterkategorie aus der Datenbank lesen
      $l_sql = "SELECT cat_access FROM #__jdownloads_cats WHERE cat_id = ".$l_childrow->cat_id;
      $database->setQuery($l_sql);
      $l_child = $database->loadObjectList();
      // Die Original verlangten Werte als Defaut setzen.
      $l_right = $p_right;
      $l_right_from = $p_right_from;

      // Falls die Rechte der abzufahrenden Unterkategorie kleiner sind (cat_access grösser) als die ursprünglichen Rechte
      // Und: die Rechte der abzufahrenden Unterkategorie kleiner sind (cat_access grösser) als die zu setzenden Rechte
      // Dann: die eigenen Rechte der Unterkategorie ihr selbst als neu zu setzende Rechte übergeben.
      if (($l_child[0]->cat_access > $p_right_from) && ($l_child[0]->cat_access > $p_right)){
        $l_right = $l_child[0]->cat_access;
        $l_right_from = $l_child[0]->cat_access;
      }
      // Für alle Unterkategorien die Funktion nochmals aufrufen.
      set_rights_to_tree($l_childrow->cat_id, $l_right, $l_right_from, $p_changed);
    }
}

function get_lowest_rights($p_catid, $p_suggest_right){
// function coded by pelma  
// Funktion welche alle darüberliegenden Kategorien nach niedrigeren Rechten (=höhere Werte) durchsucht,
// und den höchsten Wert zurückgibt. Diese Funktion ist nicht rekursiv.
// $p_catid =           KategorienID, von welcher aus nach oben durchsucht wird.
// $p_suggested_right = Die rechte welche gesetzt werden sollen, und hier überprüft werden.
    $database = &JFactory::getDBO();
    // Kategorie laden aus Datenbank
    $l_sql = "SELECT cat_id, parent_id, cat_access FROM #__jdownloads_cats WHERE cat_id = ".$p_catid;
    $database->setQuery($l_sql);
    $l_catrow = $database->loadObjectList();
    if (!isset($l_catrow[0])){
      // Die Kategorie existiert nicht. Nicht weiterfahren, aber die vorgeschlagenen Rechte zurückgeben.
      // (Dies sollte eigentlich nie vorkommen)
     return $p_suggest_right;
    }
    // Initialiseren der Rechte welche von der Funktion zurückgegeben werden.
    $l_therights = $p_suggest_right;
    // Den Kategorien-Baum solange hochfahren bis keine höhere Kategorie mehr existiert. (d.h. bis die Hauptkategorie erreicht ist)
    while ($l_catrow[0]->parent_id > 0 ){
      // Nächsthöhere Parent-Kategorie aus Datenbank lesen.
      $l_sql = "SELECT parent_id, cat_access FROM #__jdownloads_cats WHERE cat_id = ".$l_catrow[0]->parent_id;
      $database->setQuery($l_sql);
      $l_catrow = $database->loadObjectList();
      // Wenn die geladene Parent-Kategorie einen höheren Wert hat, diesen übernehmen.
      if ($l_catrow[0]->cat_access > $l_therights){
          $l_therights = $l_catrow[0]->cat_access;
      }
    }
    // Zurück mit höchstem gefundenem Wert (=niedrigstes Recht)
    return $l_therights;
}

function set_rights_of_cat($p_catid, $p_suggest_right, &$p_changed){
// function coded by pelma  
// Hauptprozedur. Diese wird aufgerufen um die Rechte einer Kategorie zu setzen, inklusive deren Unterkategorien.
// $p_catid =           KategorienID, welche gesetzt werden soll.
// $p_suggested_right = Die rechte welche gesetzt werden sollen.
// $p_changed         = Anzahl der Korrekturen oder (-1): Gewünschte Änderung war nicht zulässig!  
    $database = &JFactory::getDBO();
    // Kategorie laden aus Datenbank.
    $l_sql = "SELECT parent_id, cat_access FROM #__jdownloads_cats WHERE cat_id = ".$p_catid;
    $database->setQuery($l_sql);
    $l_catrow = $database->loadObjectList();
    if (!isset($l_catrow[0]) && ($p_catid > 0)){
      // Die Kategorie existiert nicht. Nicht weiterfahren.
      return '';
    }
    // Ursprüngliche Rechte der Kategorie lesen.
    $l_rights_from = $l_catrow[0]->cat_access;
    if ($l_catrow[0]->parent_id == 0){
      // Es ist eine Hauptkategorie. Darüberliegende Kategorien müssen nicht nach niedrigen Rechten durchsucht werden.
      $l_rights_to_set = $p_suggest_right;
    } else {
      // Es ist eine Unterkategorie. Darüberliegenden Kategoriebaum nach niedrigen Rechten (=höherer Wert) durchsuchen.
      // Damit wird gewährleitet, dass eine Unterkategorie keine höheren Rechte erhalten kann.
      $l_rights_to_set = get_lowest_rights($p_catid, $p_suggest_right);
      if ($l_rights_to_set > $p_suggest_right) $p_changed = -1;
    }
    // Die Rechte der Kategorie und aller Unter- und Unter-Unter-Kategorien setzen.
    set_rights_to_tree($p_catid, $l_rights_to_set, $l_rights_from, $p_changed);
}

function checkFileName($name){
    global $jlistConfig;
    if ($name) {
         $name = utf8_encode($name);  
        // change to uppercase
        if ($jlistConfig['fix.upload.filename.uppercase']){
            $name = strtolower($name); 
        }            
        // change blanks
        if ($jlistConfig['fix.upload.filename.blanks']){
            $name = str_replace(' ', '_', $name);
        }
        if ($jlistConfig['fix.upload.filename.specials']){
            // change special chars
            $name = strtr($name, array( "'" => "", 'ä' => 'ae', 'ü' => 'ue', 'ö' => 'oe', 'Ä' => 'ae', 'Ü' => 'ue', 'Ö' => 'oe', 'ß' => 'ss', 'Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));
            // remove invalid chars
            $name = preg_replace('#[^A-Za-z0-9 _.-]#', '', $name);
        }
    }               
    return $name;    
}

/*
 * Simple function to replicate PHP 5 behaviour
 */
function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}    
?>