<?php
/**
* @version 1.5
* @package JDownloads
* @copyright (C) 2009 www.jdownloads.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* 
*
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

Error_Reporting(E_ERROR);

define( 'EL_ADMIN_PATH', dirname(__FILE__) );

global $mainframe, $option, $tree, $jlistConfig, $limit; $limitstart;

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );
require_once(EL_ADMIN_PATH.'/../../includes/pageNavigation.php');

?>
<script type="text/javascript">
    function openWindow (url) {
        fenster = window.open(url, "_blank", "width=550, height=480, STATUS=YES, DIRECTORIES=NO, MENUBAR=NO, SCROLLBARS=YES, RESIZABLE=NO");
        fenster.focus();
    }
</script>
 
<?php

// added backend css
$document =& JFactory::getDocument();
$css = JURI::base(true).'/components/com_jdownloads/style.css';
$document->addStyleSheet( $css, 'text/css', null, array() );

$GLOBALS['jlistConfig'] = buildjlistConfig();

$user = &JFactory::getUser();

$cid 	= JArrayHelper::getValue($_REQUEST, 'cid', array());
$cat_id = intval(JArrayHelper::getValue($_REQUEST, 'cat_id', -1));
$limit = intval( JArrayHelper::getValue( $_REQUEST, 'limit', $jlistConfig['files.per.side.be'] ) );
$limitstart = intval( JArrayHelper::getValue( $_REQUEST, 'limitstart', 0 ) );
$task 	= JArrayHelper::getValue( $_REQUEST, 'task', '' );

$database = &JFactory::getDBO();
// search pad source
if (!$jlistConfig['pad.exists']){
    if (is_file(JPATH_COMPONENT_ADMINISTRATOR.DS.'pad'.DS.'padfile.php')){
        $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '1' WHERE setting_name = 'pad.exists'");
        $database->query();
        $GLOBALS['jlistConfig'] = buildjlistConfig();  
    }
}    

switch($task){

	case 'categories.publish':
	categoriesPublish( $cid, 1, $option );
	break;

	case 'categories.unpublish':
	categoriesPublish( $cid, 0, $option );
	break;

	case 'categories.edit':
	categoriesEdit($option,$cid);
	break;

	case 'categories.list':
	categoriesList($option, $task, $limitstart);
	break;

	case 'categories.save':
	categoriesSave($option);
	break;

	case 'categories.apply':
	categoriesSave($option,1);
	break;

	case 'categories.delete':
	categoriesDelete($option, $cid);
	break;

	case 'categories.cancel':
	categoriesCancel($option);
	break;

    case 'categories.orderup':
    categoriesOrder( $cid[0], -1, $option );
    break;

    case 'categories.orderdown':
    categoriesOrder( $cid[0], 1, $option );
    break;

    case 'saveorder':
        $typ = JArrayHelper::getValue( $_REQUEST, 'action' );
        if ($typ == 'file') {
          filesSaveOrder( $cid, $cat_id );
        } else {
          categoriesSaveOrder( $cid );
        }
    break;

/*FILES*/

	case 'files.publish':
	filesPublish( $cid, 1, $option, $cat_id );
	break;

	case 'files.unpublish':
	filesPublish( $cid, 0, $option, $cat_id );
	break;

	case 'files.edit':
    filesEdit($option,$cid,$cat_id);
	break;

	case 'files.list':
	filesList($option, $task, $cat_id, $limitstart);
	break;

	case 'files.save':
	filesSave($option, $cat_id);
	break;
    
    case 'files.copy':
    filesCopy($option, $cid, $cat_id);
    break;
    
    case 'files.copy.save':
    filesCopySave($option, $cat_id);
    break;

    case 'files.move':
    filesMove($option, $cid, $cat_id);
    break;
    
    case 'files.move.save':
    filesMoveSave($option, $cat_id);
    break;    
    
	case 'files.apply':
	filesSave($option, $cat_id, 1);
	break;

	case 'files.delete':
	filesDelete($option, $cid, $cat_id);
	break;

	case 'files.remove':
	filesRemove($option, $cid, $cat_id);
	break;

	case 'files.cancel':
	filesCancel($option, $cat_id);
	break;

    case 'files.saveorder':
    filesSaveOrder( $cid, $cat_id );
    break;
    
    case 'files.orderup':
	filesOrder( $cid[0], -1, $option, $cat_id );
	break;

	case 'files.orderdown':
	filesOrder( $cid[0], 1, $option, $cat_id );
	break;

// Licenses

	case 'license.edit':
	editLicense($option, $cid);
	break;
	
	case 'license.save':
	saveLicense($option);
	break;

	case 'license.delete':
	deleteLicense($option, $cid);
	break;

	case 'license.cancel':
	cancelLicense($option);
	break;


	case 'license.list':
	listLicense($option);
	break;

// Templates

	case 'templates.menu':
	menuTemplates($option, $cid);
	break;

	case 'templates.edit.cats':
	editTemplatesCats($option, $cid);
	break;

    case 'templates.save.cats':
	saveTemplatesCats($option);
	break;

    case 'templates.apply.cats':
	saveTemplatesCats($option,1);
	break;

	case 'templates.delete.cats':
	deleteTemplatesCats($option, $cid);
	break;

	case 'templates.cancel.cats':
	cancelTemplatesCats($option);
	break;

	case 'templates.list.cats':
	listTemplatesCats($option);
	break;

	case 'templates.active.cats':
	activeTemplatesCats($option, $cid);
	break;

	case 'templates.edit.files':
	editTemplatesFiles($option, $cid);
	break;

    case 'templates.save.files':
	saveTemplatesFiles($option);
	break;

    case 'templates.apply.files':
	saveTemplatesFiles($option,1);
	break;

	case 'templates.delete.files':
	deleteTemplatesFiles($option, $cid);
	break;

	case 'templates.cancel.files':
	cancelTemplatesFiles($option);
	break;

	case 'templates.list.files':
	listTemplatesFiles($option);
	break;

	case 'templates.active.files':
	activeTemplatesFiles($option, $cid);
	break;

    case 'templates.edit.details':
    editTemplatesDetails($option, $cid);
    break;

    case 'templates.save.details':
    saveTemplatesDetails($option);
    break;

    case 'templates.apply.details':
    saveTemplatesDetails($option,1);
    break;

    case 'templates.delete.details':
    deleteTemplatesDetails($option, $cid);
    break;

    case 'templates.cancel.details':
    cancelTemplatesDetails($option);
    break;

    case 'templates.list.details':
    listTemplatesDetails($option);
    break;

    case 'templates.active.details':
    activeTemplatesDetails($option, $cid);
    break;    
   
    
	case 'templates.edit.summary':
	editTemplatesSummary($option, $cid);
	break;

    case 'templates.save.summary':
	saveTemplatesSummary($option);
	break;

    case 'templates.apply.summary':
	saveTemplatesSummary($option,1);
	break;

	case 'templates.delete.summary':
	deleteTemplatesSummary($option, $cid);
	break;

	case 'templates.cancel.summary':
	cancelTemplatesSummary($option);
	break;

	case 'templates.list.summary':
	listTemplatesSummary($option);
	break;

	case 'templates.active.summary':
	activeTemplatesSummary($option, $cid);
	break;
	
// css edit
	case 'css.edit':
	cssEdit($option);
	break;

// css save
	case 'css.save':
	cssSave($option, $css_file, $css_text);
	break;

// language file edit
	case 'language.edit':
	languageEdit($option);
	break;

// language file save
	case 'language.save':
	languageSave($option, $lang_file, $lang_text);
	break;

//  create backup file
	case 'backup':
	runBackup($option);
	break;

//  upload file for restore
	case 'restore':
	showRestore($option, $task);
	break;

//  run restore file
    case 'restore.run':
    runRestore($option, $task);
    break;
    
//  manage download directories
    case 'directories.edit':
    directoriesEdit($option);
    break;

//  create new directory
    case 'directories.new':
    directoriesNew($option);
    break;

//  delete sub directory
    case 'directory.remove':
    directoryRemove($option);
    break;

// info
	case 'info':
	showInfo($option);
	break;

// info
	case 'support':
	showSupport($option);
	break;
    
    case 'install.sample':
    sampleInstall($option);
    break;
    
// Configuration
	case 'config.save':
	saveConfig($option);
	break;

	case 'config.show':
	showConfig($option);
	break;

    case 'scan.files':
    scanFiles($option, $task);
    break;

    case 'delete.log':
    deleteLog($option);
    break;

    case 'delete.restore.log':
    deleteRestoreLog($option);
    break;

    case 'editor.insert.file':
    editorInsertFile($option);
    break;
    
	default:
	jlist_HTML::controlPanel($option, $task);
	break;
}

/* checkFiles
/
/ check uploaddir and subdirs for variations
/ 
/
*/
function checkFiles($task) {
	global $jlistConfig;
	ini_set('max_execution_time', '600');
    ignore_user_abort(true);
    
    $database = &JFactory::getDBO();
	//check if all files and dirs in the uploaddir directory are listed
	if($jlistConfig['files.autodetect'] || $task == 'restore.run' || $task == 'scan.files'){
		if(file_exists(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'])){
          $startdir       = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/';
          $dir_len      = strlen($startdir);
          $dir          = $startdir;
          $only         = FALSE;
          $type         = array();
          if ($jlistConfig['all.files.autodetect']){
              $allFiles     = true;
          } else {   
              $allFiles     = FALSE;
              $type =  explode(',', $jlistConfig['file.types.autodetect']);
          }    
          $recursive    = TRUE;
          $onlyDir      = TRUE;
          $files        = array();
          $file         = array();
          
          $dirlist      = array();
          
          $new_files	   = 0;
          $new_dirs_found  = 0;
          $new_dirs_create = 0;
          $new_dirs_errors = 0;
          $new_dirs_exists = 0;
          $new_cats_create = 0;
          $log_message     = '';
          $success         = FALSE;   
          
          $log_array = array();          

          // zuerst neue cats suchen
          clearstatcache();
          $searchdir    = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/';
          $searchdirs   = array();
          $dirlist = searchdir($searchdir);
          $no_writable = 0;
          for ($i=0; $i < count($dirlist); $i++) {
              // no tempzifiles directory
              if(strpos($dirlist[$i], 'tempzipfiles') === FALSE) {
                  if (!is_writable($dirlist[$i])){
                      $no_writable++;
                  }
                  $dirlist[$i] = str_replace($searchdir, '', $dirlist[$i]);
                  // am ende / loeschen
                  if ($pos = strrpos($dirlist[$i], '/')){
                    $searchdirs[] = substr($dirlist[$i], 0, $pos);
                  }
                  // $dirlist[$i] = substr($dirlist[$i], 0, $pos);
                  // $searchdirs[] = $dirlist[$i];
              }
          }  
          for ($i=0; $i < count($searchdirs); $i++) {
             $dirs = explode('/', $searchdirs[$i]);
             $sum = count($dirs);
             // this characters are not allowed in foldernames
             if (!eregi("[?!:;\*@#%~=\+\$\^'\"\(\)\<\>]", $searchdirs[$i])) {              
               // pruefen ob dir als cat vorhanden
               $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_cats WHERE cat_dir = '$searchdirs[$i]'");
               $cat_da = $database->loadResult(); 
               // wenn nicht - hinzufuegen
               if (!$cat_da) {
                   $new_dirs_found++;
                   // neue cat anlegen
                   $row = new jlist_cats($database);
                   // bind it to the table
                   if (!$row -> bind($_POST)) {
                       echo "<script> alert('".$row -> getError()."'); window.history.go(-1); </script>\n";
                       exit();
                   }
                   $row->cat_description = '';    
                   $row->cat_title = $dirs[$sum - 1];
                   $row->cat_pic = $jlistConfig['cat.pic.default.filename'];                                 
                   if ($sum > 1) {
                       // cat_id fuer parent_id holen
                       $parent = substr($searchdirs[$i], 0, strrpos($searchdirs[$i] , '/') );
                       $database->setQuery("SELECT cat_id FROM #__jdownloads_cats WHERE cat_dir = '$parent'");
                       $row->parent_id = $database->loadResult();                        
                   }    
                   if ($row->cat_alias == ''){
                       $row->cat_alias = $row->cat_title;
                       $row->cat_alias = JFilterOutput::stringURLSafe($row->cat_alias);
                       if(trim(str_replace('-','',$row->cat_alias)) == '') {
                            $datenow =& JFactory::getDate();
                            $row->cat_alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
                       }
                   }    
                   // when file autopublish is set on - also categories autopublished
                   if ($jlistConfig['autopublish.founded.files']){
                       $row->published = 1;
                   } else {
                       $row->published = 0;
                   }
                       
                   $row->cat_dir = $searchdirs[$i];
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
                   $log_array[] = strftime($jlistConfig['global.datetime']).' - '.JText::_('JLIST_AUTO_CAT_CHECK_ADDED').' <b>'.$searchdirs[$i].'</b><br />';
               }
             }  else {
                // folder with illegal characters in name founded - create msg
                $log_array[] = strftime($jlistConfig['global.datetime']).' -  <b>'.$searchdirs[$i].'</b><font color="red"> '.JText::_('JLIST_AUTO_CAT_CHECK_ILLEGAL_NAME_FOUND_MSG').'</font><br />';
             }    
          }
          
          // Pruefen ob alle publishte cat-dirs existieren
          $mis_cats = 0;
          $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE published=1");
          $cats = $database->loadObjectList();
          foreach($cats as $cat){
                $cat_dir = $searchdir.$cat->cat_dir;
                // wenn nicht da - unpublishen
                if(!is_dir($cat_dir)){
                    $database->setQuery("UPDATE #__jdownloads_cats SET published = 0 WHERE cat_id = '$cat->cat_id'");
                    $database->query();
                    $mis_cats++;
                    $log_array[] = strftime($jlistConfig['global.datetime']).' - '.JText::_('JLIST_AUTO_CAT_CHECK_DISABLED').' <b>'.$cat->cat_dir.'</b><br />';
               }  
          }
          
           // when add categories - the access rigts must checked from all
          if ($new_cats_create){
              $sum = set_rights_of_cat (0, '00', $sum);    // all cats will checked   
          }   
          
          // alle files suchen und mit jdownloads_files abgleichen
          $all_dirs = scan_dir($dir, $type, $only, $allFiles, $recursive, $onlyDir, $files);
          if ($all_dirs != FALSE) {
              reset ($files);
              $new_files = 0;
              foreach($files as $key3 => $array2) {
                  $filename = $files[$key3]['file'];
                   if ($filename <> '') {
                      // no files in tempzipfiles and jD root directory
                     if(strpos($files[$key3]['path'], 'tempzipfiles') === FALSE && $files[$key3]['path'] != $startdir) {
                         $dir_path_total = $files[$key3]['path'];
                         $restpath = substr($files[$key3]['path'], $dir_len);
                         $only_dirs = substr($restpath, 0, strlen($restpath) - 1);
                         // existiert filename in files?
                         $exist_file = false;
                         $database->setQuery("SELECT * FROM #__jdownloads_files WHERE url_download = '".utf8_encode($filename)."'");
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
                           if ($task != 'restore.run'){  
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
                           }      
                            $database->setQuery("SELECT cat_id FROM #__jdownloads_cats WHERE cat_dir = '$only_dirs'");
                            $cat_id = $database->loadResult();
                            if ($cat_id) {
                                $file_extension = strtolower(substr(strrchr($filename,"."),1)); 
                                $file_obj = new jlist_files($database);
                                $file_obj->url_download   = $filename;
                                $test = utf8_decode($filename);
                                $str = mb_convert_encoding($filename, "ISO-8859-1");
                                $file_obj->file_title     = str_replace('.'.$file_extension, '', $filename); 
                                $file_obj->size           = $files[$key3]['size'];
                                $file_obj->description    = '';                                                                                       
                                $file_obj->date_added     = JHTML::_('date', 'now','%Y.%m.%d %H:%M:%S');
                                $file_obj->cat_id         = $cat_id;
                                $file_obj->file_alias = $file_obj->file_title;
                                $file_obj->file_alias = JFilterOutput::stringURLSafe($file_obj->file_alias);
                                if(trim(str_replace('-','',$file_obj->file_alias)) == '') {
                                    $datenow =& JFactory::getDate();
                                    $file_obj->file_alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
                                }
                                $filepfad = JPATH_SITE.'/images/jdownloads/fileimages/'.$file_extension.'.png';
                                if(file_exists(JPATH_SITE.'/images/jdownloads/fileimages/'.$file_extension.'.png')){
                                    $file_obj->file_pic       = $file_extension.'.png';
                                } else {
                                    $file_obj->file_pic       = $jlistConfig['file.pic.default.filename'];
                                }
                                $file_obj->created_by     = JText::_('JLIST_AUTO_FILE_CHECK_IMPORT_BY');
                                // nur sofort publishen wenn option aktiv
                                if ($jlistConfig['autopublish.founded.files']){
                                    $file_obj->published = 1;
                                } else {
                                    $file_obj->published = 0;
                                }    
                                if ($jlistConfig['be.new.files.order.first']){
                                    $file_obj->ordering = 0;
                                    $reorder = true; 
                                } else {   
                                    $file_obj->ordering = $file_obj->getNextOrder();  
                                    $reorder = false; 
                                }
                                
                                $file_obj->store();
                                
                                if ($reorder){
                                    $res = $file_obj->reorder('');
                                }

/*                                $database->setQuery("INSERT INTO #__jdownloads_files (`file_id`, `file_title`, `file_alias`, `description`, `description_long`, `file_pic`, `thumbnail`, `price`, `release`, `language`, `system`, `license`, `url_license`, `update_active`, `cat_id`, `metakey`, `metadesc`, `size`, `date_added`, `file_date`, `publish_from`, `publish_to`, `url_download`, `extern_file`, `url_home`, `author`, `url_author`, `created_by`, `created_mail`, `modified_by`, `modified_date`, `submitted_by`, `downloads`, `ordering`, `published`, `checked_out`, `checked_out_time`)
                                VALUES ('', ' $file_obj->file_title', '$file_obj->file_alias', '', '', '$file_obj->file_pic', '', '', '', '', '', '', '', '', '$file_obj->cat_id', '', '', '$file_obj->size ', '$file_obj->date_added', '', '', '', '$file_obj->url_download', '', '', '', '', '$file_obj->created_by', '', '', '', '', '', '$file_obj->ordering', '$file_obj->published', '0', '0000-00-00 00:00:00')");
                                if (!$database->query()) {
                                    echo $database->stderr();
                                    exit;
                                }
  */


                                $new_files++;
                                $log_array[] = strftime($jlistConfig['global.datetime']).' - '.JText::_('JLIST_AUTO_FILE_CHECK_ADDED').' <b>'.$only_dirs.'/'.utf8_decode($filename).'</b><br />';
                            } else {
                                // cat dir not exist or invalid name
                                
                            }        
                         }                   
                      }
                  }
              }  
          }					
	  
          //pruefen ob download dateien alle physisch vorhanden - sonst unpublishen
          $mis_files = 0;
	      $database->setQuery("SELECT * FROM #__jdownloads_files WHERE published=1");
          $files = $database->loadObjectList();
	      foreach($files as $file){
		      // nur interne files testen
              if ($file->url_download <> ''){   
                $database->setQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '$file->cat_id'");
                $cat_dir = $database->loadResult();  
                $cat_dir_long = $startdir.$cat_dir.'/'.utf8_decode($file->url_download);
                // wenn nicht da - unpublishen
                if(!is_file($cat_dir_long)){
                    $database->setQuery("UPDATE #__jdownloads_files SET published = 0 WHERE file_id = '$file->file_id'");
                    $database->query();
                    $mis_files++;
                    $log_array[] = strftime($jlistConfig['global.datetime']).' - '.JText::_('JLIST_AUTO_FILE_CHECK_DISABLED').' <b>'.$cat_dir.'/'.utf8_decode($file->url_download).'</b><br />';
               }  
             }
          }
           
       // save log
       if ($log_array) {
           foreach ($log_array as $log) {
                $log_message .= $log;
           }
           if ($task != 'restore.run'){
                $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '$log_message' WHERE setting_name = 'last.log.message'");
                $database->query();
                $jlistConfig['last.log.message'] = $log_message;
           }     
       }        
        
       if ($task == 'restore.run'){
            return $log_message;
       } 
              
        if ($task == '' or $task == 'scan.files') {
            echo '<table width="100%" bgcolor="#FFFFCC" cellpadding="10px" cellspacing="5px"><tr><td align="center">'.JText::_('JLIST_BACKEND_AUTOCHECK_TITLE').'<br />';
            if ($new_cats_create > 0){
                echo '<font color="#FF6600"><b>'.$new_cats_create.' '.JText::_('JLIST_BACKEND_AUTOCHECK_NEW_CATS').'</b></font><br />';
            } else {
                echo '<font color="green"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_NEW_CATS').'</b></font><br />';
            }
            
            if ($new_files > 0){
                echo '<font color="#FF6600"><b>'.$new_files.' '.JText::_('JLIST_BACKEND_AUTOCHECK_NEW_FILES').'</b></font><br />';
            } else {
                echo '<font color="green"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_NEW_FILES').'</b></font><br />';
            }            
            
            if ($mis_cats > 0){
                echo '<font color="##990000"><b>'.$mis_cats.' '.JText::_('JLIST_BACKEND_AUTOCHECK_MISSING_CATS').'</b></font><br />';
            } else {
                echo '<font color="green"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_MISSING_CATS').'</b></font><br />';
            }    
                
            
            if ($mis_files > 0){
                echo '<font color="#990000"><b>'.$mis_files.' '.JText::_('JLIST_BACKEND_AUTOCHECK_MISSING_FILES').'</b><br /></td></tr></table>';
            } else {
                echo '<font color="green"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_MISSING_FILES').'</b><br /></td></tr></table>';
            }
        
            if ($log_message)  echo '<table width="100%" bgcolor="#FFFFCC" cellpadding="10px" cellspacing="0px"><tr><td align="center">'.JText::_('JLIST_BACKEND_AUTOCHECK_LOG_TITLE').'<br />'.$log_message.'</td></tr></table>';

        } else {
            
            if ($task == 'files.list') {
            echo '<table width="100%" bgcolor="#FFFFCC" cellpadding="10px" cellspacing="0px"><tr><td>'.JText::_('JLIST_BACKEND_AUTOCHECK_TITLE').'</td>';
            if ($new_files > 0){
                echo '<td><font color="#FF6600"><b>'.$new_files.' '.JText::_('JLIST_BACKEND_AUTOCHECK_NEW_FILES').'<br />'.JText::_('JLIST_BACKEND_AUTOCHECK_REFRESH_MESSAGE').'</b></font></td>';
            } else {
                echo '<td><font color="green"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_NEW_FILES').'</b></font></td>';
            }
            if ($mis_files > 0){
                echo '<td><font color="#990000"><b>'.$mis_files.' '.JText::_('JLIST_BACKEND_AUTOCHECK_MISSING_FILES').'<br />'.JText::_('JLIST_BACKEND_AUTOCHECK_REFRESH_MESSAGE').'</b></td></tr></table>';
            } else {
                echo '<td><font color="green"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_MISSING_FILES').'</b></td></tr></table>';
            }
            
            if ($log_message)  echo '<table width="100%" bgcolor="#FFFFCC" cellpadding="10px" cellspacing="0px"><tr><td align="center">'.JText::_('JLIST_BACKEND_AUTOCHECK_LOG_TITLE').'<br />'.$log_message.'</td></tr></table>';
          }
          
            if ($task == 'categories.list') {
            echo '<table width="100%" bgcolor="#FFFFCC" cellpadding="10px" cellspacing="0px"><tr><td>'.JText::_('JLIST_BACKEND_AUTOCHECK_TITLE').'</td>';
            if ($new_cats_create > 0){
                echo '<td><font color="#FF6600"><b>'.$new_cats_create.' '.JText::_('JLIST_BACKEND_AUTOCHECK_NEW_CATS').'<br />'.JText::_('JLIST_BACKEND_AUTOCHECK_REFRESH_MESSAGE').'</b></font></td>';
            } else {
                echo '<td><font color="green"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_NEW_CATS').'</b></font></td>';
            }
            if ($mis_cats > 0){
                echo '<td><font color="#990000"><b>'.$mis_cats.' '.JText::_('JLIST_BACKEND_AUTOCHECK_MISSING_CATS').'<br />'.JText::_('JLIST_BACKEND_AUTOCHECK_REFRESH_MESSAGE').'</b></td></tr></table>';
            } else {
                echo '<td><font color="green"><b>'.JText::_('JLIST_BACKEND_AUTOCHECK_NO_MISSING_CATS').'</b></td></tr></table>';
            }
            
            if ($log_message)  echo '<table width="100%" bgcolor="#FFFFCC" cellpadding="10px" cellspacing="0px"><tr><td align="center">'.JText::_('JLIST_BACKEND_AUTOCHECK_LOG_TITLE').'<br />'.$log_message.'</td></tr></table>';
          }
          
          
        }
        
		} else {
			// error upload dir not exists
            echo '<font color="red"><b>'.JText::_('JLIST_AUTOCHECK_DIR_NOT_EXIST').'<br /><br />'.JText::_('JLIST_AUTOCHECK_DIR_NOT_EXIST_2').'</b></font>';
			
		}
    }            
}


/**
 * Fuellt das Array mit den Dateiinformationen
 * (Pfad, Verzeichnisname, Dateiname, Dateigroesse, letzte Aktualisierung
 *
 * @param		string	$dir 			Pfad zum Verzeichnis
 * @param		string	$file			enthaelt den Dateinamen
 * @param		string	$onlyDir		Enthaelt nur den Verzeichnisnamen
 * @param		array		$type		Suchmuster dateitypen
 * @param		bool		$allFiles	Listet alle Dateien in den Verzeichnissen auf ohne Ruecksicht auf $type
 * @param		array		$files		Enthaelt den Inhalt der Verzeichnisstruktur
 * @return	array						Das Array mit allen Dateinamen
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

/**
 * Durchlaeuft rekursiv das zu durchsuchende Verzeichnis
 *
 * @param		string	    $dir 			Pfad zum Verzeichnis
 * @param		array		$type			aufzulistende Dateitypen
 * @param		bool		$allFiles		Listet alle Dateien in den Verzeichnissen auf ohne Ruecksicht auf $type
 * @param		bool		$recursive	    Durchlaeuft rekursiv alle Verzeichnisse und Unterverzeichnisse
 * @param		string	    $onlyDir		Enthaelt nur den Verzeichnisnamen
 * @param		array		$files		    Enthaelt als Verweis(Referenz) den Inhalt der Verzeichnisstruktur
 * @return	    mixed						false im Fehlerfall, ansonsten ein Array mit allen Dateinamen
 */

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

////////////////////                CATEGORIES   	          ///////////////////////

// list subcats as tree
function tree($parent, $ident, $tree) {
    global $tree;
    $database = &JFactory::getDBO();

   $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE parent_id =".$parent." ORDER BY ordering");
    
    $rows = $database->loadObjectList();
    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }
    foreach ($rows as $v) {
    $v->cat_title = $ident.".&nbsp;&nbsp;<sup>L</sup>&nbsp;".$v->cat_title;
    $v->cat_title = str_replace('.&nbsp;&nbsp;<sup>L</sup>&nbsp;','.&nbsp;&nbsp;&nbsp;&nbsp;',$v->cat_title);
    $x = strrpos($v->cat_title,'.&nbsp;&nbsp;&nbsp;&nbsp;');
    $v->cat_title = substr_replace($v->cat_title, '.&nbsp;&nbsp;<sup>L</sup>&nbsp;', $x,7);
    $tree[] = $v;
    
    tree($v->cat_id, $ident.".&nbsp;&nbsp;<sup>L</sup>&nbsp;", $tree);
    }
}

function scanFiles($option, $task){
     jlist_HTML::scanFiles($option, $task);   
     
}   


//Publish Categories
function categoriesPublish( $cid=null, $publishform=1,  $option ) {
  global $mainframe;
  $database = &JFactory::getDBO();
  if (!is_array( $cid ) || count( $cid ) < 1) {
    $action = $publishcat ? 'publish' : 'unpublish';
    echo "<script> alert('".JText::_('JLIST_BACKEND_NO_SELECT_ACTION')."'); window.history.go(-1);</script>\n";
    exit;
  }
  $total = count ( $cid );
  $cids = implode( ',', $cid );

  $database->setQuery( "UPDATE #__jdownloads_cats"
  					. "\nSET published =". intval( $publishform )
					. "\nWHERE cat_id IN ( $cids )"
					);
  if (!$database->query()) {
    echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    exit();
  }
    switch ( $publishform ) {
		case 1:
			$msg = $total .JText::_('JLIST_BACKEND_CATSEDIT_SUC_PUBL')." ";
		break;
		case 0:
		default:
			$msg = $total .JText::_('JLIST_BACKEND_CATSEDIT_SUC_UNPUBL')." ";
		break;
	}
	if (count( $cid ) == 1) {
		$row = new jlist_files( $database );
		$row->checkin( $cid[0] );
	}
	$mainframe->redirect( 'index2.php?option='.$option.'&task=categories.list', $msg );
}

// Reihenfolge aendern ueber orderup/orderdown
function categoriesOrder( $uid, $inc, $option ) {
	global $mainframe;
  $database = &JFactory::getDBO();
	$row = new jlist_cats( $database );
	$row->load( $uid );
    $row->move( $inc );
	$mainframe->redirect( "index2.php?option=com_jdownloads&task=categories.list" );
}

// Reihenfolge aendern ueber saverorder symbol
function categoriesSaveOrder( &$cid ) {
  global $mainframe;
	$database = &JFactory::getDBO();
    $total  = count( $cid );                       
    // $order   = josgetarrayints( 'order' );
    $order = JRequest::getVar('order', array(), 'post', 'array' );

    for( $i=0; $i < $total; $i++ ) {
        $query = "UPDATE #__jdownloads_cats"
        . "\n SET ordering = " . (int) $order[$i]
        . "\n WHERE cat_id = " . (int) $cid[$i];
        $database->setQuery( $query );
        if (!$database->query()) {
            echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
            exit();
            }
        // update ordering
        $row = new jlist_cats( $database );
        $row->load( (int)$cid[$i] );
    }
       // clean any existing cache files
       $cache =& JFactory::getCache('com_jdownloads');
       $cache->clean('com_jdownloads');
    
    $msg  = JText::_('JLIST_BACKEND_CATS_SAVEORDER');
    $mainframe->redirect( 'index2.php?option=com_jdownloads&task=categories.list', $msg );
}

//Cancel Categories
function categoriesCancel($option){
  global $mainframe;
	$database = &JFactory::getDBO();
	$row = new jlist_cats( $database );
	$row->bind( $_POST );
	$row->checkin();
   // alle cats freigeben 
   // all cats checked in !!!
   $database->SetQuery("UPDATE #__jdownloads_cats SET checked_out = 0");
   $database->query();    
    
	$mainframe->redirect( "index2.php?option=".$option."&task=categories.list" );
}
//Delete Categories
function categoriesDelete($option, $cid){
	global $jlistConfig, $mainframe;
	$database = &JFactory::getDBO();

	$total = count( $cid );
	$cats = join(",", $cid);

	$del_error = false;
	$delerror = '';
	
	// testen ob subcats existieren - dann nicht loeschen
    $database->setQuery("SELECT count(*) FROM #__jdownloads_cats WHERE parent_id IN ($cats)");
    if ($subcats_exist = $database->loadResult()){
		$msg = JText::_('JLIST_BE_NO_DEL_SUBCATS_EXISTS');			
	}
    	
	// testen ob files hierzu existieren - dann nicht loeschen
    $database->setQuery("SELECT count(*) FROM #__jdownloads_files WHERE cat_id IN ($cats)");
    if ($files_exist = $database->loadResult()){
		$msg =JText::_('JLIST_BE_NO_DEL_FILES_EXISTS');
	}
	
	//Delete Categories and dirs
	if (!$subcats_exist && !$files_exist) {	
		// first get cat_dirs for delete dirs
		$database->SetQuery("SELECT * FROM #__jdownloads_cats WHERE cat_id IN ($cats)");
		$del_dirs = $database->loadObjectList();	
		
		// remove from DB table
		$database->SetQuery("DELETE FROM #__jdownloads_cats WHERE cat_id IN ($cats)");
		$database->Query();
		if ( !$database->query() ) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$msg = $total .JText::_('JLIST_BACKEND_CATSLIST_DEL')." ";

		//remove cat(s)
    	foreach($del_dirs as $dir) {
			if (!@rmdir(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$dir->cat_dir)) {
          		$del_error = true;
         	}
        	if ($del_error) {
            	$delerror = JText::_('JLIST_BE_DEL_CATS_DIRS_ERROR');
        	} else {
            	$delerror = JText::_('JLIST_BE_DEL_CATS_DIRS_OK');
        	}	
		}
	}
	$mainframe->redirect( 'index2.php?option='.$option.'&task=categories.list', $msg.$delerror );
}

//Save Categories
function categoriesSave($option,$apply=0){
	global $jlistConfig, $mainframe;
	$database = &JFactory::getDBO();

	$new_cat = false;   	
    $new_subcat = false;
    $move_to_sub_cat = false;
    $move_to_root_cat = false;        
	$title_changed = false;
    $cat_dir_changed = false;
	$move_sub_to_sub = false;
    $root_cat_select_again = false;
	
	$parent_cat_dir = '';
	$root_cat_dir	= '';
	$source_dir 	= '';
	$dest_dir		= '';
	$old_dir_name = '';
    $old_dir_name_long = '';        		
	$new_dir_name = '';
    $new_dir_name_long = '';
	$old_dir = '';
	$cat_dir_neu = '';
    $cat_dir_org = '';	  	

	$row = new jlist_cats($database);
	// bind it to the table
	if (!$row -> bind($_POST)) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}
    
	$row->cat_description = rtrim(stripslashes($row->cat_description));
    
	if(empty($row->cat_title)) {
        // error - title has not a value
        $mainframe->redirect("index2.php?option=".$option."&task=categories.edit&cid=".$row->cat_id, JText::_('JLIST_BACKEND_CATSEDIT_ERROR_TITLE')." ");
    }
    $row->cat_title   = trim($row->cat_title);
    
    // fill the alias with a valid value
    if(empty($row->cat_alias)) {
       $row->cat_alias = $row->cat_title;
       $row->cat_alias = JFilterOutput::stringURLSafe($row->cat_alias);
       if(trim(str_replace('-','',$row->cat_alias)) == '') {
            $datenow =& JFactory::getDate();
            $row->cat_alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
       }
    }   
    
    // convert Cyrillic and other characters
    $search = array('а','б','в','г','д','е','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ь','я','ю','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ь','Я','Ю','"','\'',';','?','!','@','#','$','%','^','&','*','(',')','_','=','~','<','>','/','ѓ','Ѓ','ќ','Ќ','ѕ','Ѕ','џ','Џ','Љ','љ','њ','Њ','ј','Ј','ä','ü','ö','ß','Ä','Ü','Ö');
    $replace = array('a','b','v','g','d','e','zh','z','i','j','k','l','m','n','o','p','r','s','t','u','f','h','tz','ch','sh','sht','a','yo','ya','yu','A','B','V','G','D','E','ZH','Z','I','J','K','L','M','N','O','P','R','S','T','U','F','H','TZ','CH','SH','SHT','A','YO','YA','YU','','','','','','','','','','','i','','','','_','','','','','','g','G','k','K','s','S','dj','DJ','LJ','lj','nj','NJ','j','J','ae','ue','oe','ss','Ae','Ue','Oe');
    if ($jlistConfig['create.auto.cat.dir']){
        $checked_cat_title = JString::str_ireplace($search, $replace, $row->cat_title);
    } else {
        $checked_cat_title = JString::str_ireplace($search, $replace, $row->cat_dir);
    }    
    // remove all others
    $checked_cat_title = preg_replace('#[^a-zA-Z0-9 _.-]#', '', $checked_cat_title);   
    
    
    if ($jlistConfig['create.auto.cat.dir'] && $row->cat_title && !$checked_cat_title){
        // ERROR - non latin caharacters used and auto folder creation is not set off
        $mainframe->redirect("index2.php?option=".$option."&task=categories.edit&cid=".$row->cat_id, JText::_('JLIST_BACKEND_CATSEDIT_ERROR_FOLDER_NAME')." ");
    }     
	// get id from the marked cat in list
	$marked_cat_id  = intval(JArrayHelper::getValue($_POST, 'catid', 0));
    if ($row->parent_id == 0 && $marked_cat_id == 0 && $marked_cat_id != $row->cat_id){
        // user has select again a root cat as new root cat
        $marked_cat_id = $row->cat_id;
        $root_cat_select_again = true;
        
    }    
    $row->published = intval(JArrayHelper::getValue($_POST, 'publish', 0));      

    // get old cat_dir value
    $old_dir_name = JArrayHelper::getValue($_POST, 'cat_dir_org', '');
    $old_dir_name_long = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$old_dir_name;
        	
	// get original title
	$org_title  = JArrayHelper::getValue($_POST, 'cat_title_org', '');
	if ($org_title != '' && $org_title != $row->cat_title) {
		if ($row->cat_id) {
			$title_changed = true;
		}	
	} else {
		$title_changed = false;		
	}
    
    // only cat_dir changed and auto creation is off
    $cat_dir_org  = JArrayHelper::getValue($_POST, 'cat_dir_org', '');
    $path_dir_entry = JArrayHelper::getValue($_POST, 'path_dir_entry', '');
    if (!$jlistConfig['create.auto.cat.dir'] && $row->cat_id && $cat_dir_org != $path_dir_entry.$checked_cat_title){
        $title_changed = true;
        $cat_dir_changed = true;
    }
    
    // check whether change the parent category and auto creation is off
    if (!$jlistConfig['create.auto.cat.dir'] && $row->cat_id && $marked_cat_id != $row->parent_id && $marked_cat_id != 0){
        if (!$root_cat_select_again){
            $move_to_sub_cat = true;
        }
    }

	// new or edit
	if (!$row->cat_id) {
    	// new cat
        $new_cat = true;
       // is subcat marked?
       if ($marked_cat_id == 0 ) {
          $row->parent_id = 0;
          $new_cat = true;
        } else {
            $row->parent_id = $marked_cat_id;
         	$new_subcat = true;            
        }
    } else {
        // cat changed
		$new_cat = false;
		if ($marked_cat_id != $row->cat_id ) {
		    if ($marked_cat_id == 0 ) {
            	// changed to root cat!
           		$move_to_root_cat = true;
		   		$row->parent_id = 0;
            } else {
               // aendern als subcat bzw. zu anderer subcat verschieben
			   // get dir from parent cat 
               if (($marked_cat_id != $row->cat_id) && ($marked_cat_id != $row->parent_id)){
                   $move_to_sub_cat = true;
               }   
				
				$database->setQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '$marked_cat_id'");
    			$root_cat_dir = $database->loadResult();
				
				$database->setQuery("SELECT parent_id FROM #__jdownloads_cats WHERE cat_id = '$marked_cat_id'");
    			if ($database->loadResult()) {
					$move_sub_to_sub = true;
				}
				
				$row->parent_id = $marked_cat_id;				
            }
        }
    }

 	// 1. new cat as root cat or  2. new cat as subcat -------------------
    if ($new_cat || $new_subcat) {
    	if ($new_subcat) {
			// get path from the root cat
			$database->setQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '$marked_cat_id'");
   			$parent_cat_dir = $database->loadResult();
			$new_dir = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$parent_cat_dir.'/'.$checked_cat_title;		
		} else {
			$new_dir = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$checked_cat_title;
		}		
    	$new_dir_short = str_replace(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/', '', $new_dir);
        
    	// create new dir if not exists
    	$dir_exist = is_dir("$new_dir");
    	if(!$dir_exist) {
        	if ($makedir = @mkdir("$new_dir", 0755)) {
            	$message = '';
			} else {
        		$message = $new_dir_short.' '.JText::_('JLIST_BACKEND_DIRSEDIT_CREATE_DIR_MESSAGE_ERROR');
			}
		} else {
        	$message = $new_dir_short.' '.JText::_('JLIST_BACKEND_DIRSEDIT_CREATE_DIR_MESSAGE_EXISTS');
    	}

    	if ($message) {
       		// error: abort with message
			$mainframe->redirect("index2.php?option=".$option."&task=categories.edit&cid=".$row->cat_id, $message." ");
    	}
    	$row->cat_dir = $new_dir_short;
	}	
    
	// 3. cat title changed ----------------------------------------------
	if ($title_changed){
		$message = '';
		$last_dir_entry = substr(strrchr($old_dir_name,"/"),1);
        if (!$last_dir_entry) $last_dir_entry = $old_dir_name;
        $new_dir_name = str_replace($last_dir_entry, $checked_cat_title, $old_dir_name);
        $new_dir_name_long = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$new_dir_name;        
		$cat_dir_neu = str_replace(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/', '', $new_dir_name);
		
		if ($new_dir_name_long != $old_dir_name_long){
            if ( @rename( $old_dir_name_long , $new_dir_name_long )) {
			    $message = '';
		    } else {
			    $message = JText::_('JLIST_BACKEND_CATSEDIT_SAVE_ERROR_DIR');
			    $mainframe->redirect("index2.php?option=".$option."&task=categories.edit&cid=".$row->cat_id, $message." ");
		    }
        }
        $row->cat_dir = $cat_dir_neu;
		
		// search subcats
		$database->setQuery("SELECT * FROM #__jdownloads_cats WHERE cat_id != '$row->cat_id' AND cat_dir LIKE '%$org_title/%'"); 
		$sub_cats = $database->loadObjectList();
		
		// actualize subcats
		if ($sub_cats) {
			foreach($sub_cats as $cats) {
				$cats->cat_dir = str_replace($old_dir_name, $new_dir_name, $cats->cat_dir);
				$database->setQuery("UPDATE #__jdownloads_cats SET cat_dir = '$cats->cat_dir' WHERE cat_id = '$cats->cat_id'");
				$database->query();				 			
			}
		} 	
	}
	
	// 4. move rootcat to subcat  --------------------------------------
   	if ($move_to_sub_cat) {
   		$message = '';
		$last_dir_entry = substr(strrchr($old_dir_name,"/"),1);
        if (!$last_dir_entry) $last_dir_entry = $old_dir_name; 
        if ($new_dir_name_long){
            $source_dir = $new_dir_name_long.'/';
        } else {    
            $source_dir = $old_dir_name_long.'/';
        } 
		$dest_dir	= JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$root_cat_dir.'/'.$last_dir_entry.'/';
		
		if ($source_dir != $dest_dir){
            $res = moveDirs($source_dir, $dest_dir, true, $message);
        }    
		if ($message != '') {
       		// error: abort with message
			$mainframe->redirect("index2.php?option=".$option."&task=categories.edit&cid=".$row->cat_id, $message." ");
		}	
		
		// search subcats

		if ($jlistConfig['create.auto.cat.dir']){
            $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE cat_id != '$row->cat_id' AND cat_dir LIKE '$old_dir_name/%'"); 
            $row->cat_dir =    $root_cat_dir.'/'.$checked_cat_title;
        } else {
            if ($path_dir_entry){
                $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE cat_id != '$row->cat_id' AND cat_dir LIKE '%/$row->cat_dir/%'"); 
            } else {
                $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE cat_id != '$row->cat_id' AND cat_dir LIKE '%$row->cat_dir/%'"); 
            }    
            $row->cat_dir = $root_cat_dir.'/'.$row->cat_dir;
        }    
        $sub_cats = $database->loadObjectList();
		// actualize cat_dir in subcats 
		if ($sub_cats) {
			foreach($sub_cats as $cats) {
				$cats->cat_dir = str_replace($old_dir_name, $row->cat_dir, $cats->cat_dir);
				$database->setQuery("UPDATE #__jdownloads_cats SET cat_dir = '$cats->cat_dir' WHERE cat_id = '$cats->cat_id'");
				$database->query();				 			
			}
		}	
	}
       
    // 5. move sub to root or sub to sub  --------------------------------------
   	if ($move_to_root_cat) {
   		$message = '';
		if ($new_dir_name_long){
            $source_dir = $new_dir_name_long.'/';
        } else {    
            $source_dir = $old_dir_name_long.'/';
        }    
		$dest_dir	= JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$checked_cat_title.'/';
		$res = moveDirs($source_dir, $dest_dir, true, $message);
		if ($message != '') {
            // error: abort with message
			$mainframe->redirect("index2.php?option=".$option."&task=categories.edit&cid=".$row->cat_id, $message." ");
		}	
		$new_dir_name = $checked_cat_title;		
		// search subcats
 		$database->setQuery("SELECT * FROM #__jdownloads_cats WHERE cat_id != '$row->cat_id' AND cat_dir LIKE '$old_dir_name/%'"); 
		$sub_cats = $database->loadObjectList();
		
		// actualize subcats cat_dir
		if ($sub_cats) {
			foreach($sub_cats as $cats) {
				$cats->cat_dir = str_replace($old_dir_name, $new_dir_name, $cats->cat_dir);
				$database->setQuery("UPDATE #__jdownloads_cats SET cat_dir = '$cats->cat_dir' WHERE cat_id = '$cats->cat_id'");
				$database->query();				 			
			}
		}	
		$row->cat_dir =	$new_dir_name;	
	}
    if ($row->metakey){
        $row->metakey = stripslashes($row->metakey);
    }
    if ($row->metadesc){
        $row->metadesc = stripslashes($row->metadesc);
    }  
    
    // for the: set_rights_of_cat function - the cat_access value must save with the old value
    if (!$row->cat_access) $row->cat_access = '00';
    $new_access = $row->cat_access;
    $old_access = JArrayHelper::getValue($_POST, 'old_access', '00');
    if ($old_access) $row->cat_access = $old_access;

    // set correct path when dir name auto creation is set off in config
    if (!$jlistConfig['create.auto.cat.dir'] && $row->cat_id && $cat_dir_org == $path_dir_entry.$checked_cat_title){
        if (!$move_to_sub_cat && !$move_to_root_cat && !$move_sub_to_sub && !$root_cat_select_again){
            $row->cat_dir =  $path_dir_entry.$checked_cat_title;
        }    
    }    
   
   // get a correct ordering value
   if (!$row->ordering) {
      $row->ordering = $row->getNextOrder();
   } 
    
	// write to db
	if (!$row -> store()) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}else{
		if(!$row->cat_id) $row->cat_id = mysql_insert_id();
	}
    
   // check the access level from the children cats
   $sum_changed = 0;
   set_rights_of_cat($row->cat_id, $new_access, $sum_changed);
   if ($sum_changed == -1){
       $msg = '- '.JText::_('JLIST_BACKEND_CATSEDIT_CATS_ACCESS_LEVEL_CAN_NOT_CHANGED_MSG');
   } else {
       if ($sum_changed > 0){
           $sum_changed--;
           $msg = '- '.$sum_changed.' '.JText::_('JLIST_BACKEND_CATSEDIT_SUM_CATS_ACCESS_LEVEL_CHANGED_AFTER_EDIT_MSG');
       } 
   }  

   $row->checkin();   
   // all cats checked in !!!
   $database->SetQuery("SELECT cat_id FROM #__jdownloads_cats WHERE cat_id != $row->cat_id");
   $free_cat = $database->loadResultArray();
   $free_cat_id = implode(",", $free_cat);
   $database->SetQuery("UPDATE #__jdownloads_cats SET checked_out = 0 WHERE cat_id IN ($free_cat_id)");
   $database->query();    
   
	if (!$apply) {
		$mainframe->redirect("index2.php?option=".$option."&task=categories.list", JText::_('JLIST_BACKEND_CATSEDIT_SAVE')." ".$msg);
	} else {
		$mainframe->redirect("index2.php?option=".$option."&task=categories.edit&hidemainmenu=1&cid=".$row->cat_id, JText::_('JLIST_BACKEND_CATSEDIT_SAVE')." ".$msg);
	} 	
}

// edit cats
function categoriesEdit($option, $cid){
	global $jlistConfig, $tree, $mainframe;
	$user = &JFactory::getUser();
	$database = &JFactory::getDBO();

	if(is_array($cid)) $cid = 0;
	$row = new jlist_cats( $database );
	$row->load( $cid );

	// fehlermeldung wenn bereits in bearbeitung
	if ($row->isCheckedOut( $user->get('id') )) {
		$mainframe->redirect( 'index2.php?option='.$option.'&task=categories.list', JText::_('JLIST_BACKEND_CATS_USED') );
	}
	$database->SetQuery("SELECT * FROM #__jdownloads_cats"
						. "\nWHERE cat_id = $cid");
	$database->loadObject($row);

	if ($cid) {
		$row->checkout( $user->get('id') );

        // alle cats sperren fuer edit - falls dateioperationen laufen sollen
        // all cats checked out !!!
        $database->SetQuery("SELECT cat_id FROM #__jdownloads_cats WHERE cat_id != $cid");
        $lock_cat = $database->loadResultArray();
        $lock_cat_id = implode(",", $lock_cat);
        $database->SetQuery("UPDATE #__jdownloads_cats SET checked_out = '$user->id' WHERE cat_id IN ($lock_cat_id)");
        $database->query();
    
	} else {
		$row->published	 = 1;
	}

    // auswahlliste fuer zugriffskontrolle
    $access_list[] = JHTML::_('select.option', '00', JText::_('JLIST_BACKEND_CATSEDIT_CAT_ACCESS_LEVEL_1'));
    $access_list[] = JHTML::_('select.option', '01', JText::_('JLIST_BACKEND_CATSEDIT_CAT_ACCESS_LEVEL_2'));
    $access_list[] = JHTML::_('select.option', '11', JText::_('JLIST_BACKEND_CATSEDIT_CAT_ACCESS_LEVEL_3'));
    $access_list[] = JHTML::_('select.option', '22', JText::_('JLIST_BACKEND_CATSEDIT_CAT_ACCESS_LEVEL_4'));
    $access_box = JHTML::_('select.genericlist', $access_list, 'cat_access', 'class="inputbox" size="4" ', 'value', 'text', $row->cat_access );
    
    // auswahlliste fuer catsymbol
    $cat_pic_dir = '/images/jdownloads/catimages/';
    $cat_pic_dir_path = $mainframe->getSiteURL().'images/jdownloads/catimages/';
    $pic_files = JFolder::files( JPATH_SITE.$cat_pic_dir );
    $cat_pic_list[] = JHTML::_('select.option', '', JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_PIC_TEXT'));
    foreach ($pic_files as $file) {
        if (eregi( "gif|jpg|png", $file )) {
            $cat_pic_list[] = JHTML::_('select.option',  $file );
        }
    }
    // auswahlliste mit bereits existierenden cats
    $cat_list = array();
    if ($row->cat_id) {
        $listtitle = JText::_('JLIST_BACKEND_CATSEDIT_ROOT_CAT_LISTBOX');
    } else {
        $listtitle = JText::_('JLIST_BACKEND_CATSEDIT_ROOT_CAT_LISTBOX_NEW');
    }
    
    // for edit manually cat dir
    $last_dir_entry = substr(strrchr($row->cat_dir,"/"),1);
    if (!$last_dir_entry){
        $last_dir_entry = $row->cat_dir;
    } else {   
        $path_pos = strrpos ( $row->cat_dir, "/" );
        $path_dir_entry = substr($row->cat_dir, 0, $path_pos + 1);
    }    

    // standard pic wenn als option ausgewaehlt
    if ($jlistConfig['cat.pic.default.filename'] && !$row->cat_pic) {
        $row->cat_pic = $jlistConfig['cat.pic.default.filename'];
    }    

    $inputbox_pic = JHTML::_('select.genericlist', $cat_pic_list, 'cat_pic', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.cat_pic.options[selectedIndex].value!='') {document.imagelib.src='$cat_pic_dir_path' + document.adminForm.cat_pic.options[selectedIndex].value} else {document.imagelib.src=''}\"", 'value', 'text', $row->cat_pic );

	jlist_HTML::categoriesEdit($option, $row, $inputbox_pic, $access_box, $last_dir_entry, $path_dir_entry);
}

// list cats
function categoriesList($option, $task, $limitstart){
	global $mainframe, $limit, $tree;
	$database = &JFactory::getDBO();
      
    $search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	if (get_magic_quotes_gpc()) {
		$search			= stripslashes( $search );
	}	
	
	if ( $search ) {
		$where[] = "LOWER( a.cat_title ) LIKE '%" . $database->getEscaped( trim( strtolower( $search ) ) ) . "%'";
	}	
	
	$filter 		= $mainframe->getUserStateFromRequest( "filter{$option}", 'filter', '' );
	$filter 		= intval( $filter );
    
    $database->SetQuery( "SELECT count(*)"
						. "\nFROM #__jdownloads_cats AS a"
						. "\nWHERE a.published 	>= 0"
						);
  	$total = $database->loadResult();
	echo $database->getErrorMsg();

    if (!$search){
        $where = array(	"a.published >= 0" , "a.parent_id = 0" );
    }
    
	if ($search && $filter == 1) {
		$where[] = "LOWER(a.cat_title) LIKE '%$search%'";
		
	$database->SetQuery( "SELECT count(*)"
						. "\nFROM #__jdownloads_cats AS a"
						. (count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
						);
  	$total = $database->loadResult();
	echo $database->getErrorMsg();
	}

	if ($search) {
		$where[] = "LOWER(a.cat_title) LIKE '%$search%'";
		
	$database->SetQuery( "SELECT count(*)"
						. "\nFROM #__jdownloads_cats AS a"
						. (count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
						);
  	$total = $database->loadResult();
	echo $database->getErrorMsg();
	}

	$pageNav = new JPagination( $total, $limitstart, $limit );

	$query = "SELECT a.*"
			. "\nFROM #__jdownloads_cats AS a"
			. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
			. "\nORDER BY a.parent_id, a.ordering, a.cat_title"
			;

	$database->SetQuery( $query );
    $cats = $database->loadObjectList();
	
	// subcategories view as tree
   	$tree = array();
   	
   	foreach ($cats as $v) {
       	$ident = '';
       	$tree[] = $v;
		tree($v->cat_id, $ident, $tree);
	} 
    $database->SetQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $database->loadObjectList();
    
    $tree = array_slice($tree, $pageNav->limitstart, $pageNav->limit);
    
    // get itemid for set category preview link 
    $database->setQuery("SELECT id from #__menu WHERE link = 'index.php?option=com_jdownloads' and published = 1");
    if (!$Itemid = $database->loadResult()){
        $database->setQuery("SELECT id from #__menu WHERE link = 'index.php?option=com_jdownloads&view=viewcategories' and published = 1");
        $Itemid = $database->loadResult();
    } 
    if (!$Itemid) $Itemid = 0;
	
	jlist_HTML::categoriesList($rows, $option, $pageNav, $search, $filter, $tree, $task, $limitstart, $Itemid);
}

/*///////////////////                FILES              //////////////////////*/

//Publish Files
function filesPublish( $cid=null, $publishform=1,  $option, $cat_id ) {
  global $mainframe;
  $database = &JFactory::getDBO();

	if (!is_array( $cid ) || count( $cid ) < 1) {
    	$action = $publishcat ? 'publish' : 'unpublish';
    	echo "<script> alert('".JText::_('JLIST_BACKEND_NO_SELECT_ACTION')."'); window.history.go(-1);</script>\n";
    	exit;
	}
	$total = count ( $cid );
	$cids = implode( ',', $cid );
	// publish only when a intern or extern file link exist	
	if ($publishform) {
		$database->setQuery( "SELECT file_id FROM #__jdownloads_files WHERE file_id IN ( $cids ) AND url_download = '' AND extern_file = ''");
		$nofiles = $database->loadResultArray();
	}	
	$database->setQuery( "UPDATE #__jdownloads_files"
  					. "\nSET published =". intval( $publishform )
					. "\nWHERE file_id IN ( $cids )"
					);
	if (!$database->query()) {
    	echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    	exit();
  	}
    if ($nofiles){
        $no_cid = implode( ',', $nofiles );
        $database->setQuery( "UPDATE #__jdownloads_files SET published = 0 WHERE file_id IN ( $no_cid )" );
        $database->query();
    }    
    switch ( $publishform ) {
			case 1:
				if ($nofiles) {
                    $nofiles_sum = count($nofiles);
                    $total = $total - $nofiles_sum;
                    $msg = $total .JText::_('JLIST_BACKEND_FILESEDIT_SUC_PUBL').' - '.$nofiles_sum.' '.JText::_('JLIST_BACKEND_EDIT_FILES_CAN_NOT_PUBLISH_INFO').' ';
                } else {
                    $msg = $total .JText::_('JLIST_BACKEND_FILESEDIT_SUC_PUBL')." ";
                }
                // add alphauserpoints when published user upload files
                if ($jlistConfig['use.alphauserpoints']){
                    $database->setQuery("SELECT * FROM #__jdownloads_files WHERE file_id IN ( $cids ) AND set_aup_points = '1'");
                    $add_points = $database->loadObjectList();
                    foreach ($add_points as $add_point){
                        addAUPPoints($add_point->submitted_by, $add_point->file_title);
                        $database->setQuery("UPDATE #__jdownloads_files SET set_aup_points = 0 WHERE file_id = '$add_point->file_id'");
                        $database->query(); 
                    }    
                }
			break;                                             
			case 0:              
			default:
				$msg = $total .JText::_('JLIST_BACKEND_FILESEDIT_SUC_UNPUBL')." ";
			break;
	}
    if (count( $cid ) == 1) {
		$row = new jlist_files( $database );
		$row->checkin( $cid[0] );
	}
	$mainframe->redirect( 'index2.php?option='.$option.'&task=files.list&cat_id='.$cat_id, $msg );
}

//Cancel Files
function filesCancel($option, $cat_id){
  global $mainframe;
	$database = &JFactory::getDBO();
	$row = new jlist_files( $database );
	$row->bind( $_POST );
	$row->checkin();
	$mainframe->redirect( "index2.php?option=".$option."&task=files.list&cat_id=".$cat_id );
}

//Delete downloads
function filesDelete($option, $cid, $cat_id){
	global $mainframe, $jlistConfig;
	$database = &JFactory::getDBO();
	$total = count( $cid );
	$files = join(",", $cid);

    // file delete option
    $file_delete = intval(JArrayHelper::getValue($_POST, 'delete_files', 0));
    if ($file_delete == 1) {
        $database->setQuery("SELECT * FROM #__jdownloads_files WHERE file_id IN ($files)");
	    $loads = $database->loadObjectList();

    	//remove file(s)
        foreach($loads as $url) { 
		  // keine externen links
          if ($url->url_download <> ''){
            // get cat_dir 
			$database->setQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '$url->cat_id'");
	    	$cat_dir = $database->loadResult();			

    	    if (!@unlink(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$cat_dir.'/'.$url->url_download)) {
                $del_error = true;
            }
          }
        }
      	if ($del_error) {
            $delerror = JText::_('JLIST_BACKEND_FILESEDIT_DEL_FILES_ERROR');
        } else {
            $delerror = JText::_('JLIST_BACKEND_FILESEDIT_DEL_FILES_OK');
        }
    }

	//Delete Downloads
	$database->SetQuery("DELETE FROM #__jdownloads_files WHERE file_id IN ($files)");
	$database->Query();
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$msg = $total .JText::_('JLIST_BACKEND_FILESLIST_DEL')." ";
    if ($file_delete == 1) {
        $msg = $msg.' '.$delerror;
    }
	$mainframe->redirect( 'index2.php?option='. $option .'&task=files.list&cat_id='.$cat_id, $msg );
}

//Remove Files from server
function filesRemove($option, $cid, $cat_id){
	global $mainframe, $jlistConfig;
	$database = &JFactory::getDBO();

	$database->setQuery("SELECT url_download FROM #__jdownloads_files WHERE file_id = '$cid'");
	$url_download = $database->loadResult();
	$database->setQuery("SELECT cat_id FROM #__jdownloads_files WHERE file_id = '$cid'");
	$cat_id = $database->loadResult();
	$database->setQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '$cat_id'");
	$cat_dir = $database->loadResult();
	//remove file
	@unlink(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$cat_dir.'/'.$url_download);
	//update db
	$database->setQuery("UPDATE #__jdownloads_files SET url_download = '', size = '', published = 0 WHERE file_id = '$cid'");
	$database->query();
	//redirect to edit
	$mainframe->redirect('index2.php?option=com_jdownloads&task=files.edit&hidemainmenu=1&cid='.$cid.'&cat_id='.$cat_id,JText::_('JLIST_BACKEND_FILESEDIT_REMOVE_OK'));
}

//Save Files
function filesSave($option, $cat_id, $apply=0){
	global $mainframe, $jlistConfig;
	$user = &JFactory::getUser();
	$database = &JFactory::getDBO();
    jimport('joomla.filesystem.file');

	$new_file = false;
    $file_cat_changed = false;
    $movedmsg = '';
    $padfile_upload = false;
	$row = new jlist_files($database);
	// bind it to the table
	if (!$row -> bind($_POST)) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}
	// use xml install file to fill the file informations    
    $use_xml_for_file_info = JArrayHelper::getValue($_POST, 'use_xml', 0);
    // id der markierten hauptkategorie
    $marked_cat_id  = intval(JArrayHelper::getValue($_POST, 'cat_id2', 0));
    // file upload?
    $file = JArrayHelper::getValue($_FILES,'file_upload',array('tmp_name'=>''));
    // padfile upload?
    $padfile = JArrayHelper::getValue($_FILES,'pad_upload',array('tmp_name'=>''));
    // pic upload
    $pic = JArrayHelper::getValue($_FILES,'file_upload_thumb',array('tmp_name'=>'')); 
    $pic2 = JArrayHelper::getValue($_FILES,'file_upload_thumb2',array('tmp_name'=>'')); 
    $pic3 = JArrayHelper::getValue($_FILES,'file_upload_thumb3',array('tmp_name'=>''));     
    $modified_date_old = JArrayHelper::getValue($_POST, 'modified_date_old', null);
    // get selected file for update download
    $selected_updatefile = JArrayHelper::getValue($_POST, 'update_file', 0);
    
    if (empty($row->file_title)) {
        if(!$padfile['tmp_name'] && !$use_xml_for_file_info){
           $mainframe->redirect("index2.php?option=".$option."&task=files.edit&hidemainmenu=1&cid=".$row->file_id, JText::_('JLIST_BACKEND_FILESEDIT_ERROR_TITLE'));
        }
    } else {
        $row->file_title = stripslashes($row->file_title);
    } 
    
    if ($row->file_alias == ''){
        $row->file_alias = JFilterOutput::stringURLSafe($row->file_title);
        if(trim(str_replace('-','',$row->file_alias)) == '') {
            $datenow =& JFactory::getDate();
            $row->file_alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
        }
    }
    
    // pic auswahl vom server
    $pic_server = JArrayHelper::getValue($_POST,'file_thumb', '');
    $pic_server2 = JArrayHelper::getValue($_POST,'file_thumb2', '');
    $pic_server3 = JArrayHelper::getValue($_POST,'file_thumb3', '');    
    $row->published = intval(JArrayHelper::getValue($_POST, 'publish', 0));
    if ($row->published && $row->set_aup_points){
        addAUPPoints($row->submitted_by, $row->file_title);
        $row->set_aup_points = 0;
    }    
    $row->update_active = intval(JArrayHelper::getValue($_POST, 'update', 0)); 
    
	if (empty($marked_cat_id)) {
        $mainframe->redirect("index2.php?option=".$option."&task=files.edit&hidemainmenu=1&cid=".$row->file_id, JText::_('JLIST_BACKEND_FILESEDIT_CATLIST_ERROR'));
    }  

    // filename holen
    $row->url_download = JArrayHelper::getValue($_POST, 'filename', '');
    
    if (!$row->file_id){
        $row->cat_id = $marked_cat_id;
		$row->created_by = $user->get('username');
        $new_file = true;
    } else {
      // actualize only when user has not changed self the date value
	  if ($modified_date_old == $row->modified_date){
	      $row->modified_date = JHTML::_('date', 'now','%Y.%m.%d %H:%M:%S');
      }
      //actalize modified_by
      $row->modified_by = $user->get('username');
              
	  if ($row->cat_id != $marked_cat_id){
          $file_cat_changed = true;
          $org_cat_id = $row->cat_id; 
          $row->cat_id = $marked_cat_id;
      }
    }

    // uploadverz. der kat holen
	$database->SetQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = $marked_cat_id");
	$mark_catdir = $database->loadResult();

    $row->description = rtrim(stripslashes($row->description));
    $row->description_long = rtrim(stripslashes($row->description_long));
    
	if ($row->file_id){    
		// get filesize and date if no value set
    	
        if ($row->size == '' and $file['tmp_name'] == '' and !$file_cat_changed) {
       	    if ($row->url_download) {
                $filepath = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$mark_catdir.'/'.$row->url_download;
        	    if (@is_file($filepath)) {
                    $row->size = fsize($filepath);
       	        }
            } else {
               // extern file
               $row->size = urlfilesize($row->extern_file,'b');
            }    
        }

    	// is date empty get filedate - only for intern linked files
        if ($row->url_download){
    	    if (empty($row->date_added) and $file['tmp_name'] == '' and !$file_cat_changed) {
  			    $row->date_added = date("Y-m-d H:i:s", filemtime(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$mark_catdir.'/'.$row->url_download));
        	}
        } else {
          // extern file
          if (empty($row->date_added) and $file['tmp_name'] == '' and !$file_cat_changed) {
             $row->date_added = urlfiledate($row->extern_file);
             $row->size = urlfilesize($row->extern_file,'b');
          }    
        }  
	} else {
        if ($row->size == '' and $file['tmp_name'] == '' and !$file_cat_changed) {
               if ($row->url_download) {
                $filepath = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$mark_catdir.'/'.$row->url_download;
                if (@is_file($filepath)) {
                    $row->size = fsize($filepath);
                   }
            } else {
               // extern file
               $row->size = urlfilesize($row->extern_file,'b');
            }    
        }
    }
    
    //handle file upload
	if($file['tmp_name'] != ''){
	    $upload_dir = '/'.$jlistConfig['files.uploaddir'].'/'.$mark_catdir.'/';
		// replace special chars in filename
        $filename_new = checkFileName($file['name']);
        // rename new file when it exists in this folder
        $only_name = substr($filename_new, 0, strrpos($filename_new, '.'));
        $file_extension = strrchr($filename_new,".");
        $num = 0;
        while (is_file(JPATH_SITE.$upload_dir.$filename_new)){
              $filename_new = $only_name.$num++.$file_extension;
              if ($num > 5000) break; 
        }
        $file['name'] = $filename_new; 
        $target_path = JPATH_SITE.$upload_dir.$file['name'];
        
        // delete first old assigned file if exist
        if ($row->url_download){
            if (is_file(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$mark_catdir.'/'.$row->url_download)){
                @unlink(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$mark_catdir.'/'.$row->url_download);
                $row->size = '';
            }
        }    
        
        if(@move_uploaded_file($file['tmp_name'], $target_path)) {
	   	   $database->setQuery("UPDATE #__jdownloads_files SET url_download = '{$file['name']}' WHERE file_id = '$row->file_id'");
	   	   $database->query();
           $row->url_download = basename($target_path);
           $row->extern_file = '';
           $row->extern_site = '';
           // set file extension pic
           $file_extension = strtolower(substr(strrchr($row->url_download,"."),1));
           $filepfad = JPATH_SITE.'/images/jdownloads/fileimages/'.$file_extension.'.png';
           if(file_exists(JPATH_SITE.'/images/jdownloads/fileimages/'.$file_extension.'.png')){
              $row->file_pic = $file_extension.'.png';
           } else {
              $row->file_pic = $jlistConfig['file.pic.default.filename'];
           }
           // get filesize and date if no value set from user after upload
           if (!$row->size) {
               $row->size = fsize($target_path);
           }
           // is date empty get filedate
           if (empty($row->date_added)) {
              $row->date_added = JHTML::_('date', 'now','%Y.%m.%d %H:%M:%S');
           }
           // use xml to read file info (works with joomla install packages (also others?)
           if ($use_xml_for_file_info){
               $xml_tags = getXMLdata($target_path, $row->url_download);
               if ($xml_tags[name] != ''){
                   $row = fillFileDateFromXML($row, $xml_tags);
                   $movedmsg .= JText::_('JLIST_BE_EDIT_FILES_USE_XML_RESULT_OK');
               } else {
                   // no xml data found
                   $row->file_title = $row->url_download;
                   $movedmsg .= JText::_('JLIST_BE_EDIT_FILES_USE_XML_RESULT_NO_FILE');
               }  
           }
    	} else {
		   $mainframe->redirect("index2.php?option=".$option."&task=files.edit&hidemainmenu=1&cid=".$row->file_id, JText::_('JLIST_BACKEND_FILESEDIT_CHECK_PERMISSIONS')." ");
		}
 	} else {
	   // kein upload - Kat gewechselt? dann file verschieben
       if ($file_cat_changed && $row->url_download != ''){
     		// datei verschieben
			// dir der alten cat holen 
			$database->SetQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '$org_cat_id'");
			$old_catdir = $database->loadResult();                
               
			if(@rename(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$old_catdir.'/'.$row->url_download, JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$mark_catdir.'/'.$row->url_download )) {
            	$movedmsg = JText::_('JLIST_BACKEND_FILESEDIT_SAVE_MOVEFILE_OK');
            } else {
                $movedmsg = JText::_('JLIST_BACKEND_FILESEDIT_SAVE_MOVEFILE_ERROR');
            }
       }
       // update the download with a file from server?
       if ($selected_updatefile){
           $update_dir = JPATH_SITE.DS.$jlistConfig['files.uploaddir'].DS;
           $update_filename = checkFileName($selected_updatefile);
           if ($update_filename != $selected_updatefile){
               // rename file
               @rename($update_dir.$selected_updatefile, $update_dir.$update_filename);
           } 
           // delete old file
           $database->setQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '$row->cat_id'");
           $cat_dir = $database->loadResult();
           @unlink(JPATH_SITE.DS.$jlistConfig['files.uploaddir'].DS.$cat_dir.DS.$row->url_download); 
           // set new url_download value
           $row->url_download = $update_filename;
           // move new file to cat folder
           if (@copy($update_dir.$update_filename, JPATH_SITE.DS.$jlistConfig['files.uploaddir'].DS.$cat_dir.DS.$update_filename)){
               $row->size = fsize(JPATH_SITE.DS.$jlistConfig['files.uploaddir'].DS.$cat_dir.DS.$update_filename);
               $movedmsg = JText::_('JLIST_BACKEND_FILESEDIT_SAVE_MOVEFILE_OK');
           }    
           @unlink($update_dir.$update_filename);     
           
           // use xml to read file info (works with joomla install packages (also others?)
           if ($use_xml_for_file_info){
               $xml_tags = getXMLdata(JPATH_SITE.DS.$jlistConfig['files.uploaddir'].DS.$cat_dir.DS.$update_filename, $row->url_download);
               if ($xml_tags[name] != ''){
                   $row = fillFileDateFromXML($row, $xml_tags);
                   $movedmsg .= JText::_('JLIST_BE_EDIT_FILES_USE_XML_RESULT_OK');
               }  else {
                   // no xml data found
                   $row->file_title = $row->url_download;
                   $movedmsg .= JText::_('JLIST_BE_EDIT_FILES_USE_XML_RESULT_NO_FILE');
               }  
           }     
       }    
	}
        
    //handle padfile upload
    if($padfile['tmp_name'] != ''){
        $padupload_dir = DS.$jlistConfig['pad.folder'].DS;
        // replace special chars in filename
        $padfilename_new = checkFileName($padfile['name']);
        $padfile['name'] = $padfilename_new; 
        $target_path = JPATH_SITE.$padupload_dir.$padfile['name'];
        
        // delete first old file if exist
        if (is_file(JPATH_SITE.DS.$jlistConfig['pad.folder'].DS.$padfilename_new)){
            @unlink(JPATH_SITE.DS.$jlistConfig['pad.folder'].DS.$padfilename_new);
        }
        if(@move_uploaded_file($padfile['tmp_name'], $target_path)) {
           if ($jlistConfig['pad.exists'] && $jlistConfig['pad.use']){
               include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'pad'.DS.'padfile.php');
               $PAD = new PADFile($target_path);
               $language = array();
               // Load file
               if (!$PAD->Load()){
                   $movedmsg .= ' '.JText::_('JLIST_BACKEND_FILE_EDIT_LOAD_PADFILE_ERROR');
               } else {
                   $padfile_upload = true;
                   // actualize the download data
                   $row->file_title       = $PAD->XML->GetValue('XML_DIZ_INFO/Program_Info/Program_Name');
                   $row->release          = $PAD->XML->GetValue('XML_DIZ_INFO/Program_Info/Program_Version').' '.$PAD->XML->GetValue('XML_DIZ_INFO/Program_Info/Program_Release_Status');
                   $row->description      = $PAD->GetBestDescription(450, $jlistConfig['pad.language']);                                  
                   $row->description_long = $PAD->GetBestDescription(2000, $jlistConfig['pad.language']);
                   $padlanguage           = $PAD->XML->GetValue('XML_DIZ_INFO/Program_Info/Program_Language');
                   $languages = explode(',', str_replace(' ', '', $jlistConfig['language.list']));
                   $xx = array_search ($padlanguage, $languages);
                   if ($xx > 0){
                        $row->language = (int)$xx;   
                   } else {
                        $row->language = 0;   
                   }   
                   $row->system           = $PAD->XML->GetValue('XML_DIZ_INFO/Program_Info/Program_OS_Support');
                   $row->license          = $PAD->XML->GetValue('XML_DIZ_INFO/Program_Info/Program_Type');
                   $row->description_long .= '<br />'.JText::_('JLIST_FE_DETAILS_SYSTEM_TITLE').': '.$row->system.'<br />'.JText::_('JLIST_FE_DETAILS_LICENSE_TITLE').': '.$row->license;
                   $dollar                = $PAD->XML->GetValue('XML_DIZ_INFO/Program_Info/Program_Cost_Dollars'); 
                   $row->metadesc         = $PAD->GetBestDescription(250, $jlistConfig['pad.language']);
                   $row->metakey          = $PAD->XML->GetValue('XML_DIZ_INFO/Program_Descriptions/'.$jlistConfig['pad.language'].'/Keywords');
                   $row->mirror_1         = $PAD->XML->GetValue('XML_DIZ_INFO/Web_Info/Download_URLs/Primary_Download_URL');
                   $row->mirror_2         = $PAD->XML->GetValue('XML_DIZ_INFO/Web_Info/Download_URLs/Secondary_Download_URL');
                   $row->url_home         = $PAD->XML->GetValue('XML_DIZ_INFO/Company_Info/Company_WebSite_URL');
                   $row->author           = $PAD->XML->GetValue('XML_DIZ_INFO/Company_Info/Contact_Info/Author_First_Name').' '.$PAD->XML->GetValue('XML_DIZ_INFO/Company_Info/Contact_Info/Author_Last_Name');
                   $row->url_author       = $PAD->XML->GetValue('XML_DIZ_INFO/Company_Info/Contact_Info/Contact_Email');                                  
                   if (!$dollar){
                               $currency = $PAD->XML->GetValue('XML_DIZ_INFO/Program_Info/Program_Cost_Other_Code');
                               $dollar = $PAD->XML->GetValue('XML_DIZ_INFO/Program_Info/Program_Cost_Other').' '.$currency;
                   } else {
                       $dollar = $dollar.' $';
                   }    
                   $row->price = $dollar;
                   $movedmsg .= ' '.JText::_('JLIST_BACKEND_FILE_EDIT_SAVE_WITH_PAD_INFO');  
               }    
           }
        } else {
          $movedmsg .= ' '.JText::_('JLIST_BACKEND_FILE_EDIT_SAVE_MOVEPADFILE_ERROR');  
        }    
    }    
    
    // pic upload
    $upload_dir = '/images/jdownloads/screenshots/';
    if($pic['tmp_name'] != ''){
        // replace special chars in filename
        $new_pic_name = checkFileName($pic['name']);
        $pic['name'] = $new_pic_name; 
        $only_name = substr($new_pic_name, 0, strrpos($new_pic_name, '.'));
        $file_extension = strrchr($new_pic_name,".");
        $num = 0;
        while (is_file(JPATH_SITE.$upload_dir.$new_pic_name)){
            $new_pic_name = $only_name.$num++.$file_extension;
            if ($num > 5000) break; 
        }    
        $target_path = JPATH_SITE.$upload_dir.$new_pic_name;
        if(@move_uploaded_file($pic['tmp_name'], $target_path)) {
              if ($row->file_id){
                  $database->setQuery("UPDATE #__jdownloads_files SET thumbnail = '$new_pic_name' WHERE file_id = '$row->file_id'");
                  $database->query();
              }    
              $x = create_new_thumb($target_path);       
              // set correct chmod
              @chmod($target_path, 0655);
              $row->thumbnail = $new_pic_name;
        }
    } else {
        // no new pic is uploaded
        if ($row->file_id){
            // old download and pic from server is selected
            $database->setQuery("UPDATE #__jdownloads_files SET thumbnail = '$pic_server' WHERE file_id = '$row->file_id'");
            $database->query();
        } else {
            // new download and pic from server is selected
            $row->thumbnail = $pic_server;
        }        
    } 
    
    if($pic2['tmp_name'] != ''){
        // replace special chars in filename
        $new_pic_name = checkFileName($pic2['name']);
        $pic2['name'] = $new_pic_name; 
        $only_name = substr($new_pic_name, 0, strrpos($new_pic_name, '.'));
        $file_extension = strrchr($new_pic_name,".");
        $num = 0;
        while (is_file(JPATH_SITE.$upload_dir.$new_pic_name)){
            $new_pic_name = $only_name.$num++.$file_extension;
            if ($num > 5000) break; 
        }
        $target_path = JPATH_SITE.$upload_dir.$new_pic_name;
        if(@move_uploaded_file($pic2['tmp_name'], $target_path)) {
              if ($row->file_id){
                  $database->setQuery("UPDATE #__jdownloads_files SET thumbnail2 = '$new_pic_name' WHERE file_id = '$row->file_id'");
                  $database->query();
              }    
              $x = create_new_thumb($target_path);       
              // set correct chmod
              @chmod($target_path, 0655);
              $row->thumbnail2 = $new_pic_name;
        }
    } else {
        // no new pic is uploaded
        if ($row->file_id){
            // old download and pic from server is selected
            $database->setQuery("UPDATE #__jdownloads_files SET thumbnail2 = '$pic_server2' WHERE file_id = '$row->file_id'");
            $database->query();
        } else {
            // new download and pic from server is selected
            $row->thumbnail2 = $pic_server2;
        }        
    }
    
    if($pic3['tmp_name'] != ''){
        // replace special chars in filename
        $new_pic_name = checkFileName($pic3['name']);
        $pic3['name'] = $new_pic_name;
        $only_name = substr($new_pic_name, 0, strrpos($new_pic_name, '.'));
        $file_extension = strrchr($new_pic_name,".");
        $num = 0;
        while (is_file(JPATH_SITE.$upload_dir.$new_pic_name)){
            $new_pic_name = $only_name.$num++.$file_extension;
            if ($num > 5000) break; 
        }        
        $target_path = JPATH_SITE.$upload_dir.$new_pic_name;
        if(@move_uploaded_file($pic3['tmp_name'], $target_path)) {
              if ($row->file_id){
                  $database->setQuery("UPDATE #__jdownloads_files SET thumbnail3 = '$new_pic_name' WHERE file_id = '$row->file_id'");
                  $database->query();
              }    
              $x = create_new_thumb($target_path);       
              // set correct chmod
              @chmod($target_path, 0655);
              $row->thumbnail3 = $new_pic_name;
        }
    } else {
        // no new pic is uploaded
        if ($row->file_id){
            // old download and pic from server is selected
            $database->setQuery("UPDATE #__jdownloads_files SET thumbnail3 = '$pic_server3' WHERE file_id = '$row->file_id'");
            $database->query();
        } else {
            // new download and pic from server is selected
            $row->thumbnail3 = $pic_server3;
        }        
    }        
    
    if (!$row->url_download && !$row->extern_file){
       // download without intern or extern file can not set to publish!
       $row->published = 0;
    }
    
    // set extern site flag off when not exist mirror link
    if (!$row->mirror_1){
        $row->extern_site_mirror_1 = 0;
    }
    if (!$row->mirror_2){
        $row->extern_site_mirror_2 = 0;
    }
    
    if ($row->metakey){
        $row->metakey = stripslashes($row->metakey);
    }
    if ($row->metadesc){
        $row->metadesc = stripslashes($row->metadesc);
    }    
     
    // get a correct ordering value
    if (!$row->ordering) {
        if ($jlistConfig['be.new.files.order.first']){
            $row->ordering = 0;
            $reorder = true;
        } else {    
            $row->ordering = $row->getNextOrder();
            $reorder = false;
        }          
    }
    // store it in the db
    if (!$row -> store()) {
        echo "<script> alert('"
            .$row -> getError()
            ."'); window.history.go(-1); </script>\n";
        exit();
    } 
    if(!$row->file_id) $row->file_id = mysql_insert_id();

    if ($reorder){
        $res = $row->reorder('');
    }
    
	$row->checkin();
	if(!$apply)	$mainframe->redirect("index2.php?option=".$option."&task=files.list&cat_id=".$cat_id, JText::_('JLIST_BACKEND_FILESEDIT_SAVE').$movedmsg." ");
	else $mainframe->redirect("index2.php?option=".$option."&task=files.edit&hidemainmenu=1&cid=".$row->file_id."&cat_id=".$cat_id, JText::_('JLIST_BACKEND_FILESEDIT_SAVE').$movedmsg." ");
}

// kopiere dateien in gleiche oder andere kategorie
function filesCopy($option, $cid, $cat_id){
    $database = &JFactory::getDBO();
    if (!is_array( $cid ) || count( $cid ) < 1) {
        //$action = $publishcat ? 'publish' : 'unpublish';
        echo "<script> alert('".JText::_('JLIST_BACKEND_NO_SELECT_ACTION')."'); window.history.go(-1);</script>\n";
        exit;
    }
    $files_id = implode(',', $cid);
    $database->SetQuery("SELECT * FROM #__jdownloads_files WHERE file_id IN ($files_id)");
    $files = $database->loadObjectList(); 
    jlist_HTML::filesCopy($option, $files_id, $files, $cat_id);   
}  

function filesCopySave($option, $cat_id_act){
    global $mainframe, $jlistConfig;
    $database = &JFactory::getDBO();
    
    $cid = array();
    $files_id = JArrayHelper::getValue($_REQUEST, 'cid2', 0);
    $publish_files = intval(JArrayHelper::getValue($_REQUEST, 'filespublish', 0));
    $copy_files = intval(JArrayHelper::getValue($_REQUEST, 'copyalsofiles', 0));
    
    $database->SetQuery("SELECT * FROM #__jdownloads_files WHERE file_id IN ($files_id)");
    $files = $database->loadObjectList(); 
    $cid = explode(',', $files_id);
    $sum = count($cid);

    $cat_id = intval(JArrayHelper::getValue($_REQUEST, 'cat_id2', array())); 
    if ($cat_id){
        $database->SetQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = $cat_id");
        $cat_dir_new = $database->loadResult();
        foreach($files as $file){
            if ($cat_id == $file->cat_id){
                $filetitle = JText::_('JLIST_BACKEND_FILES_COPY_DOWNLOADS_TEXT').' '.$file->file_title;
            } else {
                $filetitle = $file->file_title;
            }
            if ($copy_files && $file->url_download != ''){
                $url_download = $file->url_download;
                $database->SetQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = $file->cat_id");
                $cat_dir = $database->loadResult();
                $old_dir = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$cat_dir.'/'.$file->url_download;
                $new_dir = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$cat_dir_new.'/'.$file->url_download;
                if ( is_file ( $old_dir ) ){
                     @copy($old_dir, $new_dir); 
                } else {
                    $url_download = '';    
                } 
            } else {
                $url_download = '';
            }    
            $file->description = addslashes($file->description);
            $file->description_long = addslashes($file->description_long);
            $file->file_title = addslashes($file->file_title);
            $database->setQuery("INSERT INTO #__jdownloads_files (`file_id`, `file_title`, `description`, `description_long`, `file_pic`, `price`, `release`, `language`, `system`, `license`, `url_license`, `size`, `date_added`, `url_download`, `extern_file`, `mirror_1`, `mirror_2`, `url_home`, `author`, `url_author`, `created_by`, `created_mail`, `modified_by`, `modified_date`, `downloads`, `cat_id`, `ordering`, `published`, `checked_out`, `checked_out_time`) VALUES (NULL, '$filetitle', '$file->description', '$file->description_long', '$file->file_pic', '$file->price', '$file->release', '$file->language', '$file->system', '$file->license', '$file->url_license', '', '$file->date_added', '$url_download', '$file->extern_file', '$file->mirror_1', '$file->mirror_2', '$file->url_home', '$file->author', '$file->author_url', '$file->created_by', '$file->created_mail', '', '0000-00-00 00:00:00', '0', '$cat_id', '0', '$publish_files', '0', '0000-00-00 00:00:00')");
            if (!$database->query()) {
                // fehler beim erstellen in DB    
                echo $database->stderr();
                exit;
            }
        }    
    }    
    $mainframe->redirect("index2.php?option=".$option."&task=files.list&cat_id=".$cat_id_act, $sum.' '.JText::_('JLIST_BACKEND_FILES_COPY_SAVED')." ");
}  
  
// move files to other category
function filesMove($option, $cid, $cat_id){
    $database = &JFactory::getDBO();
    if (!is_array( $cid ) || count( $cid ) < 1) {
        //$action = $publishcat ? 'publish' : 'unpublish';
        echo "<script> alert('".JText::_('JLIST_BACKEND_NO_SELECT_ACTION')."'); window.history.go(-1);</script>\n";
        exit;
    }
    $files_id = implode(',', $cid);
    $database->SetQuery("SELECT * FROM #__jdownloads_files WHERE file_id IN ($files_id)");
    $files = $database->loadObjectList(); 
    jlist_HTML::filesMove($option, $files_id, $files, $cat_id);   
} 

function filesMoveSave($option, $cat_id_act){
    global $mainframe, $jlistConfig;
    $database = &JFactory::getDBO();
    
    //$cid = array();
    $files_id = JArrayHelper::getValue($_REQUEST, 'cid2', 0);
    
    $database->SetQuery("SELECT * FROM #__jdownloads_files WHERE file_id IN ($files_id)");
    $files = $database->loadObjectList(); 
    //$cid = explode(',', $files_id);
    $sum = 0;

    $cat_id = intval(JArrayHelper::getValue($_REQUEST, 'cat_id2', array())); 
    if ($cat_id){
        $database->SetQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = $cat_id");
        $cat_dir_new = $database->loadResult();
        foreach($files as $file){
            if ($cat_id != $file->cat_id){
                // move only when other cat is given
                $database->SetQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = $file->cat_id");
                $cat_dir = $database->loadResult();
                $old_dir = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$cat_dir.'/'.utf8_decode($file->url_download);
                $new_dir = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$cat_dir_new.'/'.utf8_decode($file->url_download);
                if ( is_file ( $old_dir ) ){
                     @copy($old_dir, $new_dir);
                     @unlink($old_dir); 
                } 
                $database->setQuery("UPDATE #__jdownloads_files SET cat_id = '$cat_id' WHERE file_id = '$file->file_id'");
                if (!$database->query()) {
                    // fehler beim erstellen in DB    
                    echo $database->stderr();
                    exit;
                }
                $sum++;
            }   
            
        }    
    }    
    $mainframe->redirect("index2.php?option=".$option."&task=files.list&cat_id=".$cat_id_act, $sum.' '.JText::_('JLIST_BACKEND_FILES_MOVE_SAVED')." ");
}  

// files edit
function filesEdit($option, $cid, $cat_id){
	global $mainframe, $jlistConfig, $tree;
	$user = &JFactory::getUser();
	$database = &JFactory::getDBO();

    // for tooltip
    JHTML::_('behavior.tooltip');
    // for datepicker
    JHTML::_('behavior.calendar');

    if(is_array($cid)) $cid = 0;

	$row = new jlist_files( $database );
	$row->load( $cid );

	// fail if checked out from another admin
	if ($row->isCheckedOut( $user->get('id') )) {
  		$mainframe->redirect( 'index2.php?option='.$option.'&task=files.list', JText::_('JLIST_BACKEND_FILES_USED') );
	}
	$database->SetQuery("SELECT * FROM #__jdownloads_files"
						. "\nWHERE file_id = $cid");
	$database->loadObject($row);

	if ($cid) {
		$row->checkout( $user->get('id') );
	} else {
		$row->published	 = 1;
	}

	if (!$row->date_added) {
		$row->date_added = JHTML::_('date', 'now','%Y.%m.%d %H:%M:%S');
	}

    // files list from upload root folder for updates via ftp
    $update_files = JFolder::files( JPATH_SITE.DS.$jlistConfig['files.uploaddir'], $filter= '.', $recurse=false, $fullpath=false, $exclude=array('index.htm', 'index.html', '.htaccess') );
    if ($update_files){
        $update_list_title = JText::_('JLIST_BACKEND_FILESEDIT_UPDATE_LIST_TITLE');
    } else {
        $update_list_title = JText::_('JLIST_BACKEND_FILESEDIT_NO_UPDATE_FILE_FOUND');
    }   
    $update_files_list[] = JHTML::_('select.option', '0', $update_list_title);
    foreach ($update_files as $file) {
        $update_files_list[] = JHTML::_('select.option', utf8_encode($file) );
    }
    $update_files_listbox =  JHTML::_('select.genericlist', $update_files_list, 'update_file', 'class="inputbox" size="1"', 'value', 'text', '' ); 
  
    
    // standard pic wenn als option ausgewaehlt
    if ($jlistConfig['file.pic.default.filename'] && !$row->file_pic) {
        $row->file_pic = $jlistConfig['file.pic.default.filename'];
    } 
    
    // build system listbox
    $file_system = array();
    $file_sys_values = explode(',' , $jlistConfig['system.list']);
    for ($i=0; $i < count($file_sys_values); $i++) {
        $file_system[] = JHTML::_('select.option',  $i, $file_sys_values[$i] );
    }
    $listbox_system = JHTML::_('select.genericlist', $file_system, 'system', 'class="inputbox" size="1"', 'value', 'text', $row->system );

    // build language listbox
    $file_language = array();
    $file_lang_values = explode(',' , $jlistConfig['language.list']);
    for ($i=0; $i < count($file_lang_values); $i++) {
        $file_language[] = JHTML::_('select.option',  $i, $file_lang_values[$i] );
    }
    $listbox_language = JHTML::_('select.genericlist', $file_language, 'language', 'class="inputbox" size="1"', 'value', 'text', $row->language );

    // get licenses array and build listbox with licenses
    $licenses = array();
    $licenses[] = JHTML::_('select.option', '0', JText::_('JLIST_BACKEND_FILESEDIT_LICENSES_LIST') );
    $database->setQuery( "SELECT id AS value, license_title AS text FROM #__jdownloads_license" );
    $licenses = array_merge( $licenses, $database->loadObjectList() );

    // symbol list for files
    $file_pic_dir = '/images/jdownloads/fileimages/';$mainframe->getSiteURL().
    $file_pic_dir_path = $mainframe->getSiteURL().'images/jdownloads/fileimages/';
    $pic_files = JFolder::files( JPATH_SITE.$file_pic_dir );
    $file_pic_list[] = JHTML::_('select.option', '', JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_FPIC_TEXT'));
    foreach ($pic_files as $file) {
        if (eregi( "gif|jpg|png", $file )) {
            $file_pic_list[] = JHTML::_('select.option', $file );
        }
    }

    $inputbox_pic = JHTML::_('select.genericlist', $file_pic_list, 'file_pic', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.file_pic.options[selectedIndex].value!='') {document.imagelib.src='$file_pic_dir_path' + document.adminForm.file_pic.options[selectedIndex].value} else {document.imagelib.src=''}\"", 'value', 'text', $row->file_pic );

    // thumbnail list for uploaded screenshot
    $file_thumb_dir = '/images/jdownloads/screenshots/thumbnails/';
    $file_thumb_dir_path = $mainframe->getSiteURL().'images/jdownloads/screenshots/thumbnails/';
    $thumb_files = JFolder::files( JPATH_SITE.$file_thumb_dir );
    $file_thumb_list[] = JHTML::_('select.option', '', JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_LIST_NO_PIC'));
    foreach ($thumb_files as $thumb) {
        if (eregi( "gif|jpg|png", $thumb )) {
            $file_thumb_list[] = JHTML::_('select.option', $thumb );
        }
    }

    $inputbox_thumb = JHTML::_('select.genericlist', $file_thumb_list, 'file_thumb', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.file_thumb.options[selectedIndex].value!='') {document.imagelib4.src='$file_thumb_dir_path' + document.adminForm.file_thumb.options[selectedIndex].value} else {document.imagelib4.src=''}\"", 'value', 'text', $row->thumbnail );
    $inputbox_thumb2 = JHTML::_('select.genericlist', $file_thumb_list, 'file_thumb2', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.file_thumb2.options[selectedIndex].value!='') {document.imagelib5.src='$file_thumb_dir_path' + document.adminForm.file_thumb2.options[selectedIndex].value} else {document.imagelib5.src=''}\"", 'value', 'text', $row->thumbnail2 );
    $inputbox_thumb3 = JHTML::_('select.genericlist', $file_thumb_list, 'file_thumb3', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.file_thumb3.options[selectedIndex].value!='') {document.imagelib6.src='$file_thumb_dir_path' + document.adminForm.file_thumb3.options[selectedIndex].value} else {document.imagelib6.src=''}\"", 'value', 'text', $row->thumbnail3 );

	jlist_HTML::filesEdit($option, $row, $licenses, $up_files, $inputbox_pic, $listbox_system, $listbox_language, $no_writable, $inputbox_thumb, $inputbox_thumb2, $inputbox_thumb3, $cat_id, $update_files_listbox);
}

// files list
function filesList($option, $task, $cat_id, $limitstart){
	global $mainframe, $limit;
	$database = &JFactory::getDBO();

	$where = array();
	
	$search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	if (get_magic_quotes_gpc()) {
		$search	= stripslashes( $search );
	}	
	
	if ( $search ) {
		$where[] = "LOWER( a.file_title ) LIKE '%" . $database->getEscaped( trim( strtolower( $search ) ) ) . "%'";
	}		
	
	$filter 		= $mainframe->getUserStateFromRequest( "filter{$option}", 'filter', '' );
	$filter 		= intval( $filter );
	//$cat_id 		= intval(JArrayHelper::getValue($_REQUEST,'cat_id',-1));
    
    switch($cat_id){
      case '-2': $where[] = "a.published > 0"; break;   
      case '-3': $where[] = "a.published = 0"; break;   
      case '0':  $where[] = "a.published >= 0"; 
                 $where[] = "a.cat_id = 0"; break;   
      case '-1': $where[] = "a.published >= 0"; break;  
      default:   $where[] = "a.published >= 0";
                 $where[] = "a.cat_id = ".$cat_id; break;
    }    

    $database->SetQuery( "SELECT count(*)"
						. "\nFROM #__jdownloads_files AS a"
						. "\nWHERE ". implode( ' AND ', $where )
						. "\nORDER BY a.cat_id, a.ordering"
						);
  	$total = $database->loadResult();
	echo $database->getErrorMsg();

	if ($search && $filter == 1) {
		$where[] = "LOWER(a.file_title) LIKE '%$search%'";
        $database->SetQuery( "SELECT count(*)"
						. "\nFROM #__jdownloads_files AS a"
						. (count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
						);
	  	$total = $database->loadResult();
	    echo $database->getErrorMsg();
	}

	if ($search) {
		$where[] = "LOWER(a.file_title) LIKE '%$search%'";
        $database->SetQuery( "SELECT count(*)"
						. "\nFROM #__jdownloads_files AS a"
						. (count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
						);
		$total = $database->loadResult();
	    echo $database->getErrorMsg();
	}

	$pageNav = new JPagination( $total, $limitstart, $limit );
	$query = "SELECT a.*"
			. "\nFROM #__jdownloads_files AS a"
			. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
			. "\nORDER BY a.cat_id, a.ordering"
			;
	$database->SetQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $database->loadObjectList();

	// get cat titles for view
	foreach($rows as $i=>$row){
		$cat = new jlist_cats($database);
		$cat->load($row->cat_id);
		$rows[$i]->cat_title = $cat->cat_title;
	}
	jlist_HTML::filesList($rows, $option, $pageNav, $search, $filter, $task, $limitstart);
}

// change saveorder
function filesSaveOrder( &$cid, $cat_id ) {
	global $mainframe;
  $database = &JFactory::getDBO();

    $total  = count( $cid );
//    $order   = josGetArrayInts( 'order' );
    $order = JRequest::getVar('order', array(), 'post', 'array' );

    for( $i=0; $i < $total; $i++ ) {
        $query = "UPDATE #__jdownloads_files"
        . "\n SET ordering = " . (int) $order[$i]
        . "\n WHERE file_id = " . (int) $cid[$i];
        $database->setQuery( $query );
        if (!$database->query()) {
            echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
            exit();
            }
        // update ordering
        $row = new jlist_files( $database );
        $row->load( (int)$cid[$i] );
    }

    // clean any existing cache files
    $cache =& JFactory::getCache('com_jdownloads');
    $cache->clean('com_jdownloads');    

    $msg  = JText::_('JLIST_BACKEND_FILES_SAVEORDER');
    $mainframe->redirect( 'index2.php?option=com_jdownloads&task=files.list&cat_id='.$cat_id, $msg );
}

// change saveorder with orderup/orderdown image
function filesOrder( $uid, $inc, $option, $cat_id ) {
	global $mainframe;
  $database = &JFactory::getDBO();

	$row = new jlist_files( $database );
	$row->load( $uid );
    $row->move( $inc );

	$mainframe->redirect( 'index2.php?option=com_jdownloads&task=files.list&cat_id='.$cat_id );
}

/**********************************************
/ License
/ ********************************************/

// license edit
function editLicense($option, $cid){
	global $mainframe;
	$user = &JFactory::getUser();
	$database = &JFactory::getDBO();

	if(is_array($cid)) $cid = 0;

	$row = new jlist_license( $database );
	$row->load( $cid );

	// fail if checked out not by 'me'
	if ($row->isCheckedOut( $user->get('id') )) {
		$mainframe->redirect( 'index2.php?option='.$option.'&task=license.list', $row->license_title.' '.JText::_('JLIST_BACKEND_LIC_USED') );
	}
	$database->SetQuery("SELECT * FROM #__jdownloads_license"
						. "\nWHERE id = $cid");
	$database->loadObject($row);

	if ($cid) {
		$row->checkout( $user->get('id') );
	} else {
		$row->published	 = 1;
	}

	jlist_HTML::editLicense($option, $row);
}

// license save
function saveLicense($option, $apply=0){
  global $mainframe;
	$database = &JFactory::getDBO();

	$row = new jlist_license($database);

	// bind it to the table
	if (!$row -> bind($_POST)) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}

	if(empty($row->license_title)) {
        $mainframe->redirect("index2.php?option=".$option."&task=license.edit", JText::_('JLIST_BACKEND_LICEDIT_ERROR_TITLE')); }

    $row->license_text = rtrim($row->license_text);
    
	// store it in the db
	if (!$row -> store()) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}else{
		if(!$row->id) $row->id = mysql_insert_id();
	}
	$row->checkin();
	if(!$apply)	$mainframe->redirect("index2.php?option=".$option."&task=license.list", JText::_('JLIST_BACKEND_LICEDIT_SAVE')." ");
	else $mainframe->redirect("index2.php?option=".$option."&task=license.edit&cid=".$row->id, JText::_('JLIST_BACKEND_LICEDIT_SAVE')." ");
}

function deleteLicense($option, $cid){
	global $mainframe;
  $database = &JFactory::getDBO();
	
	$total = count( $cid );
	$cats = join(",", $cid);

	//Delete Categories
	$database->SetQuery("DELETE FROM #__jdownloads_license WHERE id IN ($cats)");
	$database->Query();

	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = $total .JText::_('JLIST_BACKEND_LICLIST_DEL')." ";
	$mainframe->redirect( 'index2.php?option='. $option .'&task=license.list', $msg );
}

// license cancel
function cancelLicense($option){
	global $mainframe;
  $database = &JFactory::getDBO();

	$row = new jlist_license( $database );
	$row->bind( $_POST );
	$row->checkin();

	$mainframe->redirect( "index2.php?option=".$option."&task=license.list" );
}

// license list
function listLicense($option){
	global $mainframe;
	$database = &JFactory::getDBO();

	$pageNav = new JPagination( $total, $limitstart, $limit );
	$query = "SELECT * FROM #__jdownloads_license";

	$database->SetQuery( $query );
	$rows = $database->loadObjectList();

	jlist_HTML::listLicense($rows, $option);
}

/**********************************************
/ Templates
/ ********************************************/

function menuTemplates($option, $task) {

    jlist_HTML::controlPanelTemplate($option, $task);
}


// templates edit
function editTemplatesCats($option, $cid){
	global $mainframe;
	$user = &JFactory::getUser();
	$database = &JFactory::getDBO();

	if(is_array($cid)) $cid = 0;

	$row = new jlist_templates( $database );
	$row->load( $cid );

	if ($row->isCheckedOut( $user->get('id') )) {
		$mainframe->redirect( 'index2.php?option='.$option.'&task=templates.list.cats', $row->template_name.' '.JText::_('JLIST_BACKEND_CATS_USED') );
	}
	$database->SetQuery("SELECT * FROM #__jdownloads_templates"
						. "\nWHERE id = $cid");
	$database->loadObject($row);

    if ($cid) {
		$row->checkout( $user->get('id') );
	   }

    // load template text default
    if ($row->template_name == ''){
       $row->template_text = JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_DEFAULT');
     } else {
       if ($row->template_text == '') {
           $row->template_text = JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_DEFAULT');
       }
     }

    jlist_HTML::editTemplatesCats($option, $row);
}

function editTemplatesFiles($option, $cid){
	global $mainframe;
	$user = &JFactory::getUser();
	$database = &JFactory::getDBO();

	if(is_array($cid)) $cid = 0;

	$row = new jlist_templates( $database );
	$row->load( $cid );

	if ($row->isCheckedOut( $user->get('id') )) {
		$mainframe->redirect( 'index2.php?option='.$option.'&task=templates.list.files', $row->template_name.' '.JText::_('JLIST_BACKEND_TEMP_USED') );
	}
	$database->SetQuery("SELECT * FROM #__jdownloads_templates"
						. "\nWHERE id = $cid");
	$database->loadObject($row);

	if ($cid) {
		$row->checkout( $user->get('id') );
	}

    // load template text default
    if ($row->template_name == ''){
       $row->template_text = JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT');
     } else {
       if ($row->template_text == '') {
           $row->template_text = JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT');
       }
     }

    jlist_HTML::editTemplatesFiles($option, $row);
}

function editTemplatesDetails($option, $cid){
    global $mainframe;
    $user = &JFactory::getUser();
    $database = &JFactory::getDBO();

    if(is_array($cid)) $cid = 0;

    $row = new jlist_templates( $database );
    $row->load( $cid );

    if ($row->isCheckedOut( $user->get('id') )) {
        $mainframe->redirect( 'index2.php?option='.$option.'&task=templates.list.details', $row->template_name.' '.JText::_('JLIST_BACKEND_TEMP_USED') );
    }
    $database->SetQuery("SELECT * FROM #__jdownloads_templates"
                        . "\nWHERE id = $cid");
    $database->loadObject($row);

    if ($cid) {
        $row->checkout( $user->get('id') );
    }

    // load template text default
    if ($row->template_name == ''){
       $row->template_text = JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_DETAILS_DEFAULT');
     } else {
       if ($row->template_text == '') {
           $row->template_text = JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_DETAILS_DEFAULT');
       }
     }

    jlist_HTML::editTemplatesDetails($option, $row);
}

function editTemplatesSummary($option, $cid){
	global $mainframe;
	$user = &JFactory::getUser();
	$database = &JFactory::getDBO();

	if(is_array($cid)) $cid = 0;

	$row = new jlist_templates( $database );
	$row->load( $cid );

	if ($row->isCheckedOut( $user->get('id') )) {
		$mainframe->redirect( 'index2.php?option='.$option.'&task=templates.list.summary', $row->template_name.' '.JText::_('JLIST_BACKEND_TEMP_USED') );
	}
	$database->SetQuery("SELECT * FROM #__jdownloads_templates"
						. "\nWHERE id = $cid");
	$database->loadObject($row);

	if ($cid) {
		$row->checkout( $user->get('id') );
	} 

    // load template text default
    if ($row->template_name == ''){
       $row->template_text = JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_SUMMARY_DEFAULT');
     } else {
       if ($row->template_text == '') {
           $row->template_text = JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_SUMMARY_DEFAULT');
       }
     }

    jlist_HTML::editTemplatesSummary($option, $row);
}

// templates save
function saveTemplatesCats($option, $apply=0){
	global $mainframe;
  $database = &JFactory::getDBO();

	$row = new jlist_templates($database);

	// bind it to the table
	if (!$row->bind($_POST)) {
		echo "<script> alert('"
			.$row->getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}

	$row->locked = JArrayHelper::getValue($_POST, 'locked', '');
    if ($row->locked) {
        $row->template_name = JArrayHelper::getValue($_POST, 'tempname', '');   
    }    
    
    if(empty($row->template_name)) {
        $mainframe->redirect("index2.php?option=".$option."&task=templates.edit.cats", JText::_('JLIST_BACKEND_TEMPEDIT_ERROR_TITLE')); }

    $row->template_typ = '1';

    $row->template_text = rtrim(stripslashes($row->template_text));
    
	// store it in the db
	if (!$row -> store()) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}else{
		if(!$row->id) $row->id = mysql_insert_id();
	}
	$row->checkin();

	if(!$apply)	$mainframe->redirect("index2.php?option=".$option."&task=templates.list.cats", JText::_('JLIST_BACKEND_TEMPEDIT_SAVE')." ");
	else $mainframe->redirect("index2.php?option=".$option."&task=templates.edit.cats&cid=".$row->id, JText::_('JLIST_BACKEND_TEMPEDIT_SAVE')." ");
}

// templates save
function saveTemplatesFiles($option, $apply=0){
	global $mainframe;
  $database = &JFactory::getDBO();

	$row = new jlist_templates($database);

	// bind it to the table
	if (!$row -> bind($_POST)) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}

    $row->locked = JArrayHelper::getValue($_POST, 'locked', '');
    if ($row->locked) {
        $row->template_name = JArrayHelper::getValue($_POST, 'tempname', '');   
    }    

	if(empty($row->template_name)) {
        $mainframe->redirect("index2.php?option=".$option."&task=templates.edit.files", JText::_('JLIST_BACKEND_TEMPEDIT_ERROR_TITLE')); }

    $row->template_typ = '2';

   $row->template_text = rtrim(stripslashes($row->template_text)); 

	// store it in the db
	if (!$row -> store()) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}else{
		if(!$row->id) $row->id = mysql_insert_id();
	}
	$row->checkin();
	if(!$apply)	$mainframe->redirect("index2.php?option=".$option."&task=templates.list.files", JText::_('JLIST_BACKEND_TEMPEDIT_SAVE')." ");
	else $mainframe->redirect("index2.php?option=".$option."&task=templates.edit.files&cid=".$row->id, JText::_('JLIST_BACKEND_TEMPEDIT_SAVE')." ");
}

// templates save
function saveTemplatesDetails($option, $apply=0){
    global $mainframe;
    $database = &JFactory::getDBO();

    $row = new jlist_templates($database);

    // bind it to the table
    if (!$row -> bind($_POST)) {
        echo "<script> alert('"
            .$row -> getError()
            ."'); window.history.go(-1); </script>\n";
        exit();
    }

    $row->locked = JArrayHelper::getValue($_POST, 'locked', '');
    if ($row->locked) {
        $row->template_name = JArrayHelper::getValue($_POST, 'tempname', '');   
    }    

    if(empty($row->template_name)) {
        $mainframe->redirect("index2.php?option=".$option."&task=templates.edit.details", JText::_('JLIST_BACKEND_TEMPEDIT_ERROR_TITLE')); }

    $row->template_typ = '5';

    $row->template_text = rtrim(stripslashes($row->template_text)); 

    // store it in the db
    if (!$row -> store()) {
        echo "<script> alert('"
            .$row -> getError()
            ."'); window.history.go(-1); </script>\n";
        exit();
    }else{
        if(!$row->id) $row->id = mysql_insert_id();
    }
    $row->checkin();
    if(!$apply)    $mainframe->redirect("index2.php?option=".$option."&task=templates.list.details", JText::_('JLIST_BACKEND_TEMPEDIT_SAVE')." ");
    else $mainframe->redirect("index2.php?option=".$option."&task=templates.edit.details&cid=".$row->id, JText::_('JLIST_BACKEND_TEMPEDIT_SAVE')." ");
}

// templates save
function saveTemplatesSummary($option, $apply=0){
	global $mainframe;
  $database = &JFactory::getDBO();

	$row = new jlist_templates($database);

	// bind it to the table
	if (!$row -> bind($_POST)) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}

    $row->locked = JArrayHelper::getValue($_POST, 'locked', '');
    if ($row->locked) {
        $row->template_name = JArrayHelper::getValue($_POST, 'tempname', '');   
    }    
	
    if(empty($row->template_name)) {
        $mainframe->redirect("index2.php?option=".$option."&task=templates.edit.summary", JText::_('JLIST_BACKEND_TEMPEDIT_ERROR_TITLE')); }

    $row->template_typ = '3';

    $row->template_text = rtrim(stripslashes($row->template_text)); 

	// store it in the db
	if (!$row -> store()) {
		echo "<script> alert('"
			.$row -> getError()
			."'); window.history.go(-1); </script>\n";
		exit();
	}else{
		if(!$row->id) $row->id = mysql_insert_id();
	}
	$row->checkin();
	if(!$apply)	$mainframe->redirect("index2.php?option=".$option."&task=templates.list.summary", JText::_('JLIST_BACKEND_TEMPEDIT_SAVE')." ");
	else $mainframe->redirect("index2.php?option=".$option."&task=templates.edit.summary&cid=".$row->id, JText::_('JLIST_BACKEND_TEMPEDIT_SAVE')." ");
}

// templates delete
function deleteTemplatesCats($option, $cid){
	global $mainframe;
  $database = &JFactory::getDBO();

	$total = count( $cid );
    $cids = implode( ',', $cid );

    // check for active or default layout
    $error_msg = checkTemplatesStatus($total,$cid);

    if ($error_msg) {
        $mainframe->redirect("index2.php?option=".$option."&task=templates.list.cats", $error_msg);
    }

    // delete
	$database->SetQuery("DELETE FROM #__jdownloads_templates WHERE id IN ($cids)");
	$database->Query();

	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = $total .JText::_('JLIST_BACKEND_TEMPLIST_DEL')." ";
	$mainframe->redirect( 'index2.php?option='. $option .'&task=templates.list.cats', $msg );
}

// templates delete
function deleteTemplatesFiles($option, $cid){
  global $mainframe;
	$database = &JFactory::getDBO();

	$total = count( $cid );
	$cids = join(",", $cid);

    // check for active or default layout
    $error_msg = checkTemplatesStatus($total,$cid);

    if ($error_msg) {
        $mainframe->redirect("index2.php?option=".$option."&task=templates.list.files", $error_msg);
    }

    // delete
	$database->SetQuery("DELETE FROM #__jdownloads_templates WHERE id IN ($cids)");
	$database->Query();

	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = $total .JText::_('JLIST_BACKEND_TEMPLIST_DEL')." ";
	$mainframe->redirect( 'index2.php?option='. $option .'&task=templates.list.files', $msg );
}

// templates delete
function deleteTemplatesDetails($option, $cid){
    global $mainframe;
    $database = &JFactory::getDBO();

    $total = count( $cid );
    $cids = join(",", $cid);

    // check for active or default layout
    $error_msg = checkTemplatesStatus($total,$cid);

    if ($error_msg) {
        $mainframe->redirect("index2.php?option=".$option."&task=templates.list.details", $error_msg);
    }

    // delete
    $database->SetQuery("DELETE FROM #__jdownloads_templates WHERE id IN ($cids)");
    $database->Query();

    if ( !$database->query() ) {
        echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
        exit();
    }

    $msg = $total .JText::_('JLIST_BACKEND_TEMPLIST_DEL')." ";
    $mainframe->redirect( 'index2.php?option='. $option .'&task=templates.list.details', $msg );
}


// templates delete
function deleteTemplatesSummary($option, $cid){
  global $mainframe;
	$database = &JFactory::getDBO();

	$total = count( $cid );
	$cids = join(",", $cid);

    // check for active or default layout
    $error_msg = checkTemplatesStatus($total,$cid);

    if ($error_msg) {
        $mainframe->redirect("index2.php?option=".$option."&task=templates.list.summary", $error_msg);
    }

    // delete
    $database->SetQuery("DELETE FROM #__jdownloads_templates WHERE id IN ($cids)");
	$database->Query();

	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = $total .JText::_('JLIST_BACKEND_TEMPLIST_DEL')." ";
	$mainframe->redirect( 'index2.php?option='. $option .'&task=templates.list.summary', $msg );
}

// templates cancel
function cancelTemplatesCats($option){
  global $mainframe;
	$database = &JFactory::getDBO();

	$row = new jlist_templates( $database );
	$row->bind( $_POST );
	$row->checkin();
 
	$mainframe->redirect( "index2.php?option=".$option."&task=templates.list.cats" );
}

// templates cancel
function cancelTemplatesFiles($option){
  global $mainframe;
	$database = &JFactory::getDBO();

	$row = new jlist_templates( $database );
	$row->bind( $_POST );
	$row->checkin();

	$mainframe->redirect( "index2.php?option=".$option."&task=templates.list.files" );
}

// templates cancel
function cancelTemplatesDetails($option){
    global $mainframe;
    $database = &JFactory::getDBO();

    $row = new jlist_templates( $database );
    $row->bind( $_POST );
    $row->checkin();

    $mainframe->redirect( "index2.php?option=".$option."&task=templates.list.details" );
}

// templates cancel
function cancelTemplatesSummary($option){
  global $mainframe;
	$database = &JFactory::getDBO();

	$row = new jlist_templates( $database );
	$row->bind( $_POST );
	$row->checkin();

	$mainframe->redirect( "index2.php?option=".$option."&task=templates.list.summary" );
}

// templates list
function listTemplatesCats($option){
	global $mainframe;
	$database = &JFactory::getDBO();

	$pageNav = new JPagination( $total, $limitstart, $limit );
	$query = "SELECT * FROM #__jdownloads_templates where template_typ = 1";

	$database->SetQuery( $query );
	$rows = $database->loadObjectList();

	jlist_HTML::listTemplatesCats($rows, $option);
}

// templates list
function listTemplatesFiles($option){
	global $mainframe;
	$database = &JFactory::getDBO();

	$pageNav = new JPagination( $total, $limitstart, $limit );
	$query = "SELECT * FROM #__jdownloads_templates where template_typ = 2";

	$database->SetQuery( $query );
	$rows = $database->loadObjectList();

	jlist_HTML::listTemplatesFiles($rows, $option);
}


function listTemplatesDetails($option){
    global $mainframe;
    $database = &JFactory::getDBO();

    $pageNav = new JPagination( $total, $limitstart, $limit );
    $query = "SELECT * FROM #__jdownloads_templates where template_typ = 5";

    $database->SetQuery( $query );
    $rows = $database->loadObjectList();

    jlist_HTML::listTemplatesDetails($rows, $option);
}

// templates list
function listTemplatesSummary($option){
	global $mainframe;
	$database = &JFactory::getDBO();

	$pageNav = new JPagination( $total, $limitstart, $limit );
	$query = "SELECT * FROM #__jdownloads_templates where template_typ = 3";

	$database->SetQuery( $query );
	$rows = $database->loadObjectList();

	jlist_HTML::listTemplatesSummary($rows, $option);
}

// templates active
function activeTemplatesCats($option, $cid){
  global $mainframe;
	$database = &JFactory::getDBO();

    $total = count($cid);
    if ($total > 1) {
        echo "<script> alert('".JText::_('JLIST_BACKEND_TEMPLATE_ACTIVE_ERROR')."'); window.history.go(-1); </script>\n";
		exit();
    }
    
    $cids = implode( ',', $cid );
  
    // parent active disabled
	$database->SetQuery("UPDATE #__jdownloads_templates SET template_active = 0 WHERE template_typ = 1 AND template_active = 1");
	$database->Query();
    if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// new active enabled
	$database->SetQuery("UPDATE #__jdownloads_templates SET template_active = 1 WHERE template_typ = 1 AND id IN ( $cids )");
	$database->Query();
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = JText::_('JLIST_BACKEND_TEMPEDIT_ACTIVE')." ";
	$mainframe->redirect( 'index2.php?option='. $option .'&task=templates.list.cats', $msg );
}

// templates active
function activeTemplatesFiles($option, $cid){
	global $mainframe;
  $database = &JFactory::getDBO();

    $total = count($cid);
    if ($total > 1) {
        echo "<script> alert('".JText::_('JLIST_BACKEND_TEMPLATE_ACTIVE_ERROR')."'); window.history.go(-1); </script>\n";
		exit();
    }

    $cids = implode( ',', $cid );

    // parent active disabled
	$database->SetQuery("UPDATE #__jdownloads_templates SET template_active = 0 WHERE template_typ = 2 AND template_active = 1");
	$database->Query();
    if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// new active enabled
	$database->SetQuery("UPDATE #__jdownloads_templates SET template_active = 1 WHERE template_typ = 2 AND id IN ( $cids )");
	$database->Query();
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = JText::_('JLIST_BACKEND_TEMPEDIT_ACTIVE')." ";
	$mainframe->redirect( 'index2.php?option='. $option .'&task=templates.list.files', $msg );
}

// templates active
function activeTemplatesDetails($option, $cid){
    global $mainframe;
    $database = &JFactory::getDBO();

    $total = count($cid);
    if ($total > 1) {
        echo "<script> alert('".JText::_('JLIST_BACKEND_TEMPLATE_ACTIVE_ERROR') ."'); window.history.go(-1); </script>\n";
        exit();
    }

    $cids = implode( ',', $cid );

    // parent active disabled
    $database->SetQuery("UPDATE #__jdownloads_templates SET template_active = 0 WHERE template_typ = 5 AND template_active = 1");
    $database->Query();
    if ( !$database->query() ) {
        echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
        exit();
    }

    // new active enabled
    $database->SetQuery("UPDATE #__jdownloads_templates SET template_active = 1 WHERE template_typ = 5 AND id IN ( $cids )");
    $database->Query();
    if ( !$database->query() ) {
        echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
        exit();
    }

    $msg = JText::_('JLIST_BACKEND_TEMPEDIT_ACTIVE')." ";
    $mainframe->redirect( 'index2.php?option='. $option .'&task=templates.list.details', $msg );
}


// templates active
function activeTemplatesSummary($option, $cid){
	global $mainframe;
  $database = &JFactory::getDBO();

    $total = count($cid);
    if ($total > 1) {
        echo "<script> alert('".JText::_('JLIST_BACKEND_TEMPLATE_ACTIVE_ERROR')."'); window.history.go(-1); </script>\n";
		exit();
    }

    $cids = implode( ',', $cid );

    // parent active disabled
	$database->SetQuery("UPDATE #__jdownloads_templates SET template_active = 0 WHERE template_typ = 3 AND template_active = 1");
	$database->Query();
    if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// new active enabled
	$database->SetQuery("UPDATE #__jdownloads_templates SET template_active = 1 WHERE template_typ = 3 AND id IN ( $cids )");
	$database->Query();
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = JText::_('JLIST_BACKEND_TEMPEDIT_ACTIVE')." ";
	$mainframe->redirect( 'index2.php?option='. $option .'&task=templates.list.summary', $msg );
}

// css edit
function cssEdit($option) {
    
    $css_file = JPATH_SITE.'/components/com_jdownloads/jdownloads_fe.css';
    @chmod ($css_file, 0755);

	clearstatcache();
    if ( is_writable( $css_file ) == false ) {
      $css_writable = false;
    } else {
      $css_writable = true;
    }
	jlist_HTML::cssEdit($option, $css_file, $css_writable);
}

// css save
function cssSave($option, $css_file, $css_text) {
   global $mainframe;

   $css_file = JArrayHelper::getValue($_POST,'css_file', '');
   $css_text = JArrayHelper::getValue($_POST,'css_text', '');
   clearstatcache();

   if (!is_writable($css_file)) {
		$mainframe->redirect("index2.php?option=".$option."&task=css.edit", JText::_('JLIST_BACKEND_EDIT_CSS_WRITE_STATUS_TEXT').JText::_('JLIST_BACKEND_EDIT_LANG_CSS_FILE_WRITABLE_NO') );
    break;
  }

  if ($fp = fopen( $css_file, "w")) {
    fputs($fp,stripslashes($css_text));
    fclose($fp);
		$mainframe->redirect("index2.php?option=".$option."&task=css.edit", JText::_('JLIST_BACKEND_EDIT_CSS_SAVED'));
  }
}

// language edit
function languageEdit($option) {
    global $mainframe;
    
    $params   = JComponentHelper::getParams('com_languages');
    $frontend_lang = $params->get('site', 'en-GB');
    $language = JLanguage::getInstance($frontend_lang);    

    // get language file for edit 
    $language = &JFactory::getLanguage();
    $language->load('com_jdownloads');
    $lang_file = JLanguage::getLanguagePath(JPATH_SITE);
    
    $lang_file .= DS.$frontend_lang.DS.$frontend_lang.'.com_jdownloads.ini';
    
    @chmod ($lang_file, 0755);
	clearstatcache();
    if ( is_writable( $lang_file ) == false ) {
      $lang_writable = false;
    } else {
      $lang_writable = true;
    }

	jlist_HTML::languageEdit($option, $lang_file, $lang_writable);
}

// language save
function languageSave($option, $lang_file, $lang_text) {
   global $mainframe;

   $lang_file = JArrayHelper::getValue($_POST,'lang_file', '');
   $lang_text = JArrayHelper::getValue($_POST,'lang_text', '', _MOS_ALLOWHTML);

  if (!is_writable($lang_file)) {
 	$mainframe->redirect("index2.php?option=".$option."&task=language.edit", JText::_('JLIST_BACKEND_EDIT_LANG_WRITE_STATUS_TEXT').JText::_('JLIST_BACKEND_EDIT_LANG_CSS_FILE_WRITABLE_NO') );
    break;
  }

  if ($fp = fopen( $lang_file, "w")) {
    fputs($fp,stripslashes($lang_text));
    fclose($fp);
		$mainframe->redirect("index2.php?option=".$option."&task=language.edit", JText::_('JLIST_BACKEND_EDIT_LANG_SAVED'));
  }

}


/**********************************************
/ Configuration
/ ********************************************/

// Config view
function showConfig($option){
	global $jlistConfig, $mainframe;
	$database = &JFactory::getDBO();
	
	$user_groups = array();
	$user_groups[] = JHTML::_('select.option', '0', JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_ACCESS_ALL'));
	$user_groups[] = JHTML::_('select.option', '1', JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_ACCESS_REGGED'));
	$user_groups[] = JHTML::_('select.option', '2', JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_ACCESS_ADMINS'));
	
    $list_sortorder = array();
    $list_sortorder[] = JHTML::_('select.option', '0', JText::_('JLIST_BACKEND_SETTINGS_FILE_ORDER_1'));
    $list_sortorder[] = JHTML::_('select.option', '1', JText::_('JLIST_BACKEND_SETTINGS_FILE_ORDER_2'));
    $list_sortorder[] = JHTML::_('select.option', '2', JText::_('JLIST_BACKEND_SETTINGS_FILE_ORDER_3'));
    $list_sortorder[] = JHTML::_('select.option', '3', JText::_('JLIST_BACKEND_SETTINGS_FILE_ORDER_4'));
    $list_sortorder[] = JHTML::_('select.option', '4', JText::_('JLIST_BACKEND_SETTINGS_FILE_ORDER_5'));
    $list_sortorder[] = JHTML::_('select.option', '5', JText::_('JLIST_BACKEND_SETTINGS_FILE_ORDER_6'));
    
    $cats_sortorder = array();
    $cats_sortorder[] = JHTML::_('select.option', '0', JText::_('JLIST_BACKEND_SETTINGS_CATS_ORDER_1'));
    $cats_sortorder[] = JHTML::_('select.option', '1', JText::_('JLIST_BACKEND_SETTINGS_CATS_ORDER_2'));
    $cats_sortorder[] = JHTML::_('select.option', '2', JText::_('JLIST_BACKEND_SETTINGS_CATS_ORDER_3'));
    
    // auswahlliste for default catsymbol
    $cat_pic_dir = '/images/jdownloads/catimages/'; 
    $cat_pic_dir_path = $mainframe->getSiteURL().'images/jdownloads/catimages/';
    $pic_files = JFolder::files( JPATH_SITE.$cat_pic_dir );
    $cat_pic_list[] = JHTML::_('select.option', '', '');
    foreach ($pic_files as $file) {
        if (eregi( "gif|jpg|png", $file )) {
            $cat_pic_list[] = JHTML::_('select.option', $file );
        }
    }
    
    $inputbox_pic = JHTML::_('select.genericlist', $cat_pic_list, 'cat_pic', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.cat_pic.options[selectedIndex].value!='') {document.imagelib.src='$cat_pic_dir_path' + document.adminForm.cat_pic.options[selectedIndex].value} else {document.imagelib.src=''}\"", 'value', 'text', $jlistConfig['cat.pic.default.filename'] );
  
    // auswahlliste for default filesymbol
    $file_pic_dir = '/images/jdownloads/fileimages/';
    $file_pic_dir_path = $mainframe->getSiteURL().'images/jdownloads/fileimages/';
    $pic_files = JFolder::files( JPATH_SITE.$file_pic_dir );
    $file_pic_list[] = JHTML::_('select.option', '', '');
    foreach ($pic_files as $file) {
        if (eregi( "gif|jpg|png", $file )) {
            $file_pic_list[] = JHTML::_('select.option', $file );
        }
    }
    $inputbox_pic_file = JHTML::_('select.genericlist', $file_pic_list, 'file_pic', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.file_pic.options[selectedIndex].value!='') {document.imagelib2.src='$file_pic_dir_path' + document.adminForm.file_pic.options[selectedIndex].value} else {document.imagelib2.src=''}\"", 'value', 'text', $jlistConfig['file.pic.default.filename'] );
  
    // auswahlliste for hot image
    $hot_pic_dir = '/images/jdownloads/hotimages/';
    $hot_pic_dir_path = $mainframe->getSiteURL().'images/jdownloads/hotimages/';
    $hot_files = JFolder::files( JPATH_SITE.$hot_pic_dir );
    $hot_pic_list[] = JHTML::_('select.option', '', '');
    foreach ($hot_files as $hotfile) {
        if (eregi( "gif|jpg|png", $hotfile )) {
            $hot_pic_list[] = JHTML::_('select.option', $hotfile );
        }
    }    
    
    $inputbox_hot = JHTML::_('select.genericlist', $hot_pic_list, 'hot_pic', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.hot_pic.options[selectedIndex].value!='') {document.imagelib3.src='$hot_pic_dir_path' + document.adminForm.hot_pic.options[selectedIndex].value} else {document.imagelib3.src=''}\"", 'value', 'text', $jlistConfig['picname.is.file.hot'] );
      
    // auswahlliste for new image
    $new_pic_dir = '/images/jdownloads/newimages/';
    $new_pic_dir_path = $mainframe->getSiteURL().'images/jdownloads/newimages/';
    $new_files = JFolder::files( JPATH_SITE.$new_pic_dir );
    $new_pic_list[] = JHTML::_('select.option', '', '');
    foreach ($new_files as $newfile) {
        if (eregi( "gif|jpg|png", $newfile )) {
            $new_pic_list[] = JHTML::_('select.option', $newfile );
        }
    }    
    
    $inputbox_new = JHTML::_('select.genericlist', $new_pic_list, 'new_pic', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.new_pic.options[selectedIndex].value!='') {document.imagelib4.src='$new_pic_dir_path' + document.adminForm.new_pic.options[selectedIndex].value} else {document.imagelib4.src=''}\"", 'value', 'text', $jlistConfig['picname.is.file.new'] );
            
    // auswahlliste for download image
    $down_pic_dir = '/images/jdownloads/downloadimages/';
    $down_pic_dir_path = $mainframe->getSiteURL().'images/jdownloads/downloadimages/'; 
    $down_files = JFolder::files( JPATH_SITE.$down_pic_dir );
    $down_pic_list[] = JHTML::_('select.option', '', '');
    foreach ($down_files as $downfile) {
        if (eregi( "gif|jpg|png", $downfile )) {
            $down_pic_list[] = JHTML::_('select.option', $downfile );
        }
    }    
    
    $inputbox_down = JHTML::_('select.genericlist', $down_pic_list, 'down_pic', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.down_pic.options[selectedIndex].value!='') {document.imagelib5.src='$down_pic_dir_path' + document.adminForm.down_pic.options[selectedIndex].value} else {document.imagelib5.src=''}\"", 'value', 'text', $jlistConfig['download.pic.details'] ); 
  
    $inputbox_down2 = JHTML::_('select.genericlist', $down_pic_list, 'down_pic2', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.down_pic2.options[selectedIndex].value!='') {document.imagelib9.src='$down_pic_dir_path' + document.adminForm.down_pic2.options[selectedIndex].value} else {document.imagelib9.src=''}\"", 'value', 'text', $jlistConfig['download.pic.files'] ); 
  
    $inputbox_mirror_1 = JHTML::_('select.genericlist', $down_pic_list, 'mirror_1_pic', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.mirror_1_pic.options[selectedIndex].value!='') {document.imagelib6.src='$down_pic_dir_path' + document.adminForm.mirror_1_pic.options[selectedIndex].value} else {document.imagelib6.src=''}\"", 'value', 'text', $jlistConfig['download.pic.mirror_1'] );
  
    $inputbox_mirror_2 = JHTML::_('select.genericlist', $down_pic_list, 'mirror_2_pic', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.mirror_2_pic.options[selectedIndex].value!='') {document.imagelib7.src='$down_pic_dir_path' + document.adminForm.mirror_2_pic.options[selectedIndex].value} else {document.imagelib7.src=''}\"", 'value', 'text', $jlistConfig['download.pic.mirror_2'] );  
  
     // for plugin
     $inputbox_down_plg = JHTML::_('select.genericlist', $down_pic_list, 'down_pic_plg', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.down_pic_plg.options[selectedIndex].value!='') {document.imagelib10.src='$down_pic_dir_path' + document.adminForm.down_pic_plg.options[selectedIndex].value} else {document.imagelib10.src=''}\"", 'value', 'text', $jlistConfig['download.pic.plugin'] ); 

    // auswahlliste for update image
    $upd_pic_dir = '/images/jdownloads/updimages/';
    $upd_pic_dir_path = $mainframe->getSiteURL().'images/jdownloads/updimages/';
    $upd_files = JFolder::files( JPATH_SITE.$upd_pic_dir );
    $upd_pic_list[] = JHTML::_('select.option', '', '');
    foreach ($upd_files as $updfile) {
        if (eregi( "gif|jpg|png", $updfile )) {
            $upd_pic_list[] = JHTML::_('select.option', $updfile );
        }
    }    
    
    $inputbox_upd = JHTML::_('select.genericlist', $upd_pic_list, 'upd_pic', "class=\"inputbox\" size=\"1\""
  . " onchange=\"javascript:if (document.adminForm.upd_pic.options[selectedIndex].value!='') {document.imagelib8.src='$upd_pic_dir_path' + document.adminForm.upd_pic.options[selectedIndex].value} else {document.imagelib8.src=''}\"", 'value', 'text', $jlistConfig['picname.is.file.updated'] );
    
    // for content file plugin by pelma
    // check if exists
    $file_plugin_path =  JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'mos_jdownloads_file.php';        
    if (file_exists($file_plugin_path)) {
        $database->setQuery("SELECT template_name  FROM #__jdownloads_templates WHERE template_typ = 2");
        $templaterows = $database->loadObjectList();
        $file_templates = array();
        $templatecnt = 0;
        foreach ($templaterows as $templaterow) {
            $file_templates[] = JHTML::_('select.option', $templaterow->template_name, $templaterow->template_name);
            $templatecnt++;
        }
        $file_plugin_inputbox = JHTML::_('select.genericlist', $file_templates, "jlistConfig[fileplugin.defaultlayout]" , 'size="6" class="inputbox"', 'value', 'text', $jlistConfig['fileplugin.defaultlayout'] );
        $file_plugin_inputbox2 = JHTML::_('select.genericlist', $file_templates, "jlistConfig[fileplugin.layout_disabled]" , 'size="6" class="inputbox"', 'value', 'text', $jlistConfig['fileplugin.layout_disabled'] );  
    }                
    
	jlist_HTML::showConfig($option, $list_sortorder, $cats_sortorder, $user_groups, $file_plugin_inputbox, $file_plugin_inputbox2, $inputbox_pic, $inputbox_pic_file, $inputbox_hot, $inputbox_new, $inputbox_down, $inputbox_down2, $inputbox_mirror_1, $inputbox_mirror_2, $inputbox_upd, $inputbox_down_plg);
}

// Config save
function saveConfig($option){
	global $mainframe;
	$database = &JFactory::getDBO();
	$msg = '';
	$root_dir = JArrayHelper::getValue($_POST,'root_dir', 'downloads');
	$jlistConfig = JArrayHelper::getValue($_POST,'jlistConfig',array(),_MOS_ALLOWHTML);
    $resize_thumbs = intval(JArrayHelper::getValue($_POST,'resize_thumbs', 0));
    $jlistConfig['file.pic.default.filename'] = JArrayHelper::getValue($_POST,'file_pic', 'zip.png');
    $jlistConfig['cat.pic.default.filename'] = JArrayHelper::getValue($_POST,'cat_pic', 'folder.png');
    $jlistConfig['picname.is.file.new'] = JArrayHelper::getValue($_POST,'new_pic', 'newfile.gif');
    $jlistConfig['picname.is.file.hot'] = JArrayHelper::getValue($_POST,'hot_pic', 'hotfile.gif');
    $jlistConfig['picname.is.file.updated'] = JArrayHelper::getValue($_POST,'upd_pic', 'update_blue.png');
    $jlistConfig['download.pic.details'] = JArrayHelper::getValue($_POST,'down_pic', 'download_blue.png');
    $jlistConfig['download.pic.files'] = JArrayHelper::getValue($_POST,'down_pic2', 'download2.png');
    $jlistConfig['download.pic.mirror_1'] = JArrayHelper::getValue($_POST,'mirror_1_pic', 'mirror_blue1.png');
    $jlistConfig['download.pic.mirror_2'] = JArrayHelper::getValue($_POST,'mirror_2_pic', 'mirror_blue2.png');
    $jlistConfig['pad.folder'] = JArrayHelper::getValue($_POST,'pad.folder', 'padfiles'); 
    $jlistConfig['download.pic.plugin'] = JArrayHelper::getValue($_POST,'down_pic_plg', 'download2.png');
    $reset_couter = $jlistConfig['reset.counters'];
    $jlistConfig['reset.counters'] = 0;
    
    $jlistConfig['offline.text'] = stripslashes($jlistConfig['offline.text']);
    $jlistConfig['google.adsense.code'] = stripslashes($jlistConfig['google.adsense.code']); 
    $jlistConfig['downloads.titletext'] = stripslashes($jlistConfig['downloads.titletext']);
    $jlistConfig['downloads.footer.text'] = stripslashes($jlistConfig['downloads.footer.text']);
    $jlistConfig['mp3.info.layout'] = stripslashes($jlistConfig['mp3.info.layout']);
    $jlistConfig['upload.form.text'] = stripslashes($jlistConfig['upload.form.text']);   
    $jlistConfig['send.mailto.template.download'] = stripslashes($jlistConfig['send.mailto.template.download']);   
    $jlistConfig['send.mailto.template.upload'] = stripslashes($jlistConfig['send.mailto.template.upload']);
    $jlistConfig['fileplugin.offline_title'] = stripslashes($jlistConfig['fileplugin.offline_title']);
    $jlistConfig['user.message.when.zero.points'] = stripslashes($jlistConfig['user.message.when.zero.points']);
    $jlistConfig['countdown.text'] = stripslashes($jlistConfig['countdown.text']);   
    $jlistConfig['limited.download.reached.message'] = stripslashes($jlistConfig['limited.download.reached.message']);                                                                          
     
     // make sure that all AUP options are set back to default, when the main option is set off.
     if (!$jlistConfig['use.alphauserpoints']){
         $jlistConfig['use.alphauserpoints.with.price.field'] = '0';
         $jlistConfig['user.can.download.file.when.zero.points'] = '1';
     }    
    
    // remove spaces from lists 
    $jlistConfig['file.types.view'] = str_replace(' ', '', $jlistConfig['file.types.view']);
    $jlistConfig['file.types.autodetect'] = str_replace(' ', '',$jlistConfig['file.types.autodetect']);
    $jlistConfig['allowed.upload.file.types'] = str_replace(' ', '', $jlistConfig['allowed.upload.file.types']);
    $jlistConfig['allowed.leeching.sites'] = str_replace(' ', '', $jlistConfig['allowed.leeching.sites']);
    $anti_leech = $jlistConfig['anti.leech']; 
    // check the given upload size and correct it
    $max_upload_php_ini = ini_get('upload_max_filesize') * 1024; 
    if ($jlistConfig['allowed.upload.file.size'] > $max_upload_php_ini) $jlistConfig['allowed.upload.file.size'] = $max_upload_php_ini;
    
    if (!is_dir(JPATH_SITE.DS.$jlistConfig['pad.folder'])){
       if ($makedir = @mkdir(JPATH_SITE.DS.$jlistConfig['pad.folder'].DS, 0755)) {
          $msg = JText::_('JLIST_BACKEND_SETTINGS_PAD_FOLDER_CREATED');
       } else {
          $msg = JText::_('JLIST_BACKEND_SETTINGS_PAD_FOLDER_NOT_CREATED');
       }    
    }
    
    // upload foldername changed? - then rename the folder
    $old_dir = JPATH_SITE.'/'.$root_dir;
    $new_dir = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'];
    if ($old_dir != $new_dir) {
      	if ( !@rename( $old_dir, $new_dir ) ) {
   		    $msg = JText::_('JLIST_BACKEND_SETTINGS_UPLOADDIR_RENAME_ERROR');
		    $mainframe->redirect('index2.php?option='.$option.'&task=config.show',$msg);	
        } else {
   		    $msg = '';
	    }
    }    

    // make sure, that one from this fields is activated for frontend upload form
    if ( !$jlistConfig['fe.upload.view.select.file'] && !$jlistConfig['fe.upload.view.extern.file']){
         $jlistConfig['fe.upload.view.select.file'] = '1';
    }
    
    // make sure, that only one from the comments addons are activated
    if ($jlistConfig['view.jom.comment'] && $jlistConfig['jcomments.active']){
         $jlistConfig['view.jom.comment'] = '0';
         $jlistConfig['jcomments.active'] = '0';
    }
   
	// anti-leech option
    // if activated - copy and rename the htaccess
    $source = JPATH_SITE.'/administrator/components/com_jdownloads/htaccess.txt'; 
    $dest   = JPATH_SITE."/".$jlistConfig['files.uploaddir'].'/.htaccess'; 
    if ($anti_leech && !is_file($dest)){
        if (is_file($source)){ 
            @copy($source, $dest);
            $msg .= JText::_('JLIST_ACTIVE_ANTILEECH_OK');
       } else {
           $msg .= JText::_('JLIST_ACTIVE_ANTILEECH_ERROR');
       }
    } else {
        // anti leech off? then delete the htaccess
       if (!$anti_leech) { 
        if (is_file($dest)){
            if (@unlink($dest)){
                $msg .= JText::_('JLIST_ACTIVE_ANTILEECH_OFF_OK');                
            } else {
                $msg .= JText::_('JLIST_ACTIVE_ANTILEECH_OFF_ERROR');                
            }   
        }
       }  
    }   
     
	foreach($jlistConfig as $setting_name=>$setting_value){
        $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '".$database->getEscaped($setting_value)."' WHERE setting_name = '$setting_name'");
		$database->query();
	}
    
    $GLOBALS['jlistConfig'] = buildjlistConfig(); 
    
    // recreate all thumbs new
    if ($resize_thumbs){
        // first delete all old thumbs
        $thumb_dir = JPATH_SITE.'/images/jdownloads/screenshots/thumbnails/';
        $screen_dir = JPATH_SITE.'/images/jdownloads/screenshots/';
        delete_dir_and_allfiles($thumb_dir);
        $pic_files = array();
        $only      = TRUE;
        $type      = array("png","jpg","gif");
        $allFiles  = false;
        $recursive = FALSE;
        $onlyDir   = FALSE;
        $ok = scan_dir($screen_dir, $type, $only, $allFiles,$recursive, $onlyDir, $pic_files);
        if ($ok){
            foreach ($pic_files as $pics){
                    create_new_thumb($pics[path].$pics[file]);
            }
            $msg = $msg.' - '.JText::_('JLIST_CONFIG_SETTINGS_THUMBS_CREATE_ALL_MESSAGE');         
        }                        
            
    }  
    
    if ($reset_couter){
       $database->setQuery("UPDATE #__jdownloads_files SET downloads = 0");
       $database->query();
       $msg = $msg.' - '.JText::_('JLIST_BACKEND_SETTINGS_RESET_COUNTER_MSG');
    }
      
	$mainframe->redirect('index2.php?option='.$option.'&task=config.show',JText::_('JLIST_BACKEND_SETTINGS_SAVED').$msg);
}

//Information
function showInfo($option) {
	jlist_HTML::showInfo($option);
}

// run backup
function runBackup($option){
	global $jlistConfig;
	$database = &JFactory::getDBO();
		$prefix = $database->_table_prefix;
        
        $tabellen = array($prefix.'jdownloads_config', $prefix.'jdownloads_cats', $prefix.'jdownloads_files', $prefix.'jdownloads_license', $prefix.'jdownloads_templates', $prefix.'jdownloads_groups', $prefix.'jdownloads_log', $prefix.'jdownloads_rating');
		$ausgabe = '<?php'."\r\n";
		for ($i=0; $i < count($tabellen); $i++) {
            $ausgabe .= '$database->setQuery("TRUNCATE TABLE `'.$tabellen[$i].'`") ;$database->query();'."\r\n";
        }    

		foreach($tabellen as $tabelle){
            // id field name
            switch($tabelle){
				case $prefix.'jdownloads_cats':
        			 $id_name = 'cat_id'; 
				break;
				case $prefix.'jdownloads_files':
        			 $id_name = 'file_id';
				break;
                case $prefix.'jdownloads_rating':
                     $id_name = 'file_id';
                break;
                default:
        			 $id_name = 'id';
				break;
			}
			$database->setQuery("SELECT $id_name FROM $tabelle");
			$xids = $database->loadObjectList();

			foreach($xids as $xid){
				switch($tabelle){
					case $prefix.'jdownloads_config':
						$object = new jlist_config($database);
					break;
					case $prefix.'jdownloads_cats':
						$object = new jlist_cats($database);
					break;
					case $prefix.'jdownloads_files':
						$object = new jlist_files($database);
					break;
					case $prefix.'jdownloads_license':
						$object = new jlist_license($database);
					break;
					case $prefix.'jdownloads_templates':
						$object = new jlist_templates($database);
					break;
                    case $prefix.'jdownloads_groups':
                        $object = new jlist_groups($database);
                    break;
                    case $prefix.'jdownloads_log':
                        $object = new jlist_log($database);
                    break;
                    case $prefix.'jdownloads_rating':
                        $object = new jlist_rating($database);
                    break;
				}
    			switch($id_name){
	       			case 'cat_id':
                         $object->load($xid->cat_id);
			 	    break;
			     	case 'file_id':
                         $object->load($xid->file_id);
    				break;
                    default:
                         $object->load($xid->id);
    			 	break;
	       		}

				$sql = '$database->setQuery("INSERT INTO '.$tabelle.' ( %s ) VALUES ( %s );"); $database->query();$i++; '."\r\n";
				$fields = array();
				$values = array();
				foreach (get_object_vars( $object ) as $k => $v) {
					if (is_array($v) or is_object($v) or $v === NULL) {
						continue;
					}
					if ($k[0] == '_') {
						continue;
					}
					$fields[] = $database->NameQuote( $k );
					$values[] = $database->Quote( $v );
				}
				$ausgabe .= sprintf( $sql, implode( ",", $fields ) ,  implode( ",", $values ) );
			}
		}
		$ausgabe .= "\r\n?>";
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
		header ("Cache-Control: no-store, no-cache, must-revalidate");
        header ('Cache-Control: post-check=0, pre-check=0', false );
		header ("Pragma: no-cache");
		header ("Content-type: text/plain");
		header ('Content-Disposition: attachment; filename="'.'backup_jdownloads_v'.$jlistConfig['jd.version'].'.txt'.'"' );
		print $ausgabe;
		exit;
}

function showRestore($option, $task){
	jlist_HTML::showRestore($option, $task);
}

function runRestore($option, $task){
	global $mainframe, $jlistConfig;
	$database = &JFactory::getDBO();

    $output = '';
	
    // get restore file
	$file = JArrayHelper::getValue($_FILES,'restore_file',array('tmp_name'=>''));
	if($file['tmp_name']!= ''){
		$i = 0;
		// auf korrekte version (>= 1.4) pruefen - 
        @$datei = fopen($file['tmp_name'],"r") or die ("Can not open File!");
        $muster = "/\bjd.version\b/i";
        $muster2 = "/\bcat_top_id\b/i";
        while (!feof($datei)) {
            $zeile = fgets($datei, 4096);
             if (preg_match($muster, $zeile)) {
                if ($pos = strpos($zeile, "jd.version'", 100)){
                    // restore only from version 1.5 or newer
                    if ($vers = floatval(substr($zeile, 123, 3)) < 1.5){
                        fclose($datei);   
                        echo "<script> alert('".JText::_('JLIST_RESTORE_OLD_FILE')."'); window.history.go(-1); </script>\n";
                        exit();
                    } 
                }    
             }
             if (preg_match($muster2, $zeile)) {
                 $is_cat_top_id = true;
             } 
        }
       
        fclose($datei);

        // create temporary old cats data field for correct restore 
        if ($is_cat_top_id){
            $database->SetQuery("ALTER TABLE #__jdownloads_cats ADD cat_top_id VARCHAR(255) NOT NULL AFTER cat_id");
            $database->query();
        }    

        // write values in db tables
		require_once($file['tmp_name']);
        
        // remove temporary old data field
        if ($is_cat_top_id){
            $database->SetQuery("ALTER TABLE #__jdownloads_cats DROP cat_top_id"); 
            $database->query();
        }    
        
        // auto ueberwachung deaktivieren
        if ($jlistConfig['files.autodetect'] == 1 ){
            $monitoring = true;
            $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '0' WHERE setting_name = 'files.autodetect'");
            $database->query();
            $jlistConfig['files.autodetect'] = 0;
        }    
        
        // falls backup von alter version - pruefen
        require_once(EL_ADMIN_PATH.'/check.restore.jdownloads.php');
        $output = checkAfterRestore();
        $log_messages = checkFiles($task);
        $sum = '<font color="green"><b>'.sprintf(JText::_('JLIST_BACKEND_RESTORE_MSG'),(int)$i).'</b></font>';
        
        if ($log_messages){
            $output = addslashes($sum.'<br />'.$output.'<br />'.JText::_('JLIST_AFTER_RESTORE_TITLE_3').'<br />'.$log_messages.'<br />'.JText::_('JLIST_CHECK_FINISH').'');
        } else {   
            $output = addslashes($sum.'<br />'.$output.'<br />'.JText::_('JLIST_CHECK_FINISH').'');
        }    
        $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '$output' WHERE setting_name = 'last.restore.log'");
        $database->query();
        $jlistConfig['last.restore.log'] = stripslashes($output);
    }
        // auto ueberwachung aktivieren
        if ($monitoring){
            $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '1' WHERE setting_name = 'files.autodetect'");
            $database->query();
            $jlistConfig['files.autodetect'] = 1; 
        }       
    $mainframe->redirect('index2.php?option=com_jdownloads', $sum.' '.JText::_('JLIST_BACKEND_RESTORE_MSG_2'));
}

// support for editor button plugin
// insert for the content plugin a file id or a category id 
function editorInsertFile($option){
    global $mainframe, $jlistConfig;
    
    $database = &JFactory::getDBO();
    $document = & JFactory::getDocument();
    $lang = & JFactory::getLanguage();
    $lang->load('plg_editors-xtd_jdownload', JPATH_ADMINISTRATOR);
    
    // build cat tree listbox
    $query = "SELECT cat_id AS id, parent_id AS parent, cat_title AS name FROM #__jdownloads_cats WHERE published = '1' ORDER BY ordering";
    $database->setQuery( $query );
    $cats2 = $database->loadObjectList();
    $preload = array();
    $catlist= treeSelectList( $cats2, 0, $preload, 'cat_id', 'class="inputbox" size="10"', 'value', 'text', cat_id);

    // build files listbox
    $files_list = array();
    
    $query = "SELECT a.cat_id, a.published, a.file_id AS id,"
    . " CONCAT(b.cat_dir, '/', a.file_title, ' ', a.release) AS name"
    . ' FROM #__jdownloads_files AS a'
    . ' LEFT JOIN #__jdownloads_cats AS b ON b.cat_id = a.cat_id'
    . " WHERE a.published = '1'"
    . ' ORDER BY a.cat_id, a.file_title';
    
    $database->setQuery( $query );
    $files = $database->loadObjectList();
    foreach ($files as $file) {
        $files_list[] = JHTML::_('select.option', $file->id, $file->name);
    }
    $files_listbox =  JHTML::_('select.genericlist', $files_list, 'file_id', 'class="inputbox" size="10"', 'value', 'text', '' );
    
    $eName    = JRequest::getVar('e_name');
    $eName    = preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
    ?>
    <script type="text/javascript">
      function insertPagebreak()
      {
        var file_id = document.getElementById("file_id").value;
        var cat_id = document.getElementById("cat_id").value;
        var count = document.getElementById("count").value;
        var tag;
         
        if (file_id >0){
            tag = "\{jd_file file=="+file_id+"\}";
        } else {
           if (cat_id > 0){
               tag = "\{jd_file category=="+cat_id+" count=="+count+"\}";
           }    
        }    
        if (file_id || cat_id){
            window.parent.jInsertEditorText(tag, '<?php echo $eName; ?>');
        }    
        window.parent.document.getElementById('sbox-window').close();
        return false;
       }
    </script>

    <form>
    <table width="100%" cellpadding="2" cellspacing="2" border="0" style="padding: 10px;">
       <tr> 
         <td colspan="2">
            <img src="components/com_jdownloads/images/jd_logo_48.png" width="32px" height="32px" align="middle" border="0"/>
            <b><?php echo JText::_('JLIST_BUTTON_PLG_TITLE').'</b><br />'; ?>
            <?php echo JText::_('JLIST_BUTTON_PLG_DESC'); ?>
         </td>
       </tr>
       <tr>
          <td class="key" align="right" width="25%" valign="top">
              <label for="file_id">
                  <?php echo JText::_('JLIST_BUTTON_PLG_FILE_ID_TITLE'); ?>
              </label>
          </td>
          <td width="75%">
              <?php echo $files_listbox; ?><br />
              <?php echo JText::_('JLIST_BUTTON_PLG_FILE_ID_NOTE'); ?>
          </td>
       </tr>
       <tr><td colspan="2"><hr></td></tr>
            <tr>
                <td class="key" align="right" valign="top">
                    <label for="cat_id">
                        <?php echo JText::_('JLIST_BUTTON_PLG_CAT_ID_TITLE'); ?>
                    </label>
                </td>
                <td>
                   <?php echo $catlist; ?>
                </td>
            </tr>
            <tr>
                <td class="key" align="right" valign="top">
                    <label for="count">
                        <?php echo JText::_('JLIST_BUTTON_PLG_COUNT_TITLE'); ?>
                    </label>
                </td>
                <td>
                   <input type="text" id="count" name="count" value="0" /><br />
                   <?php echo JText::_('JLIST_BUTTON_PLG_COUNT_DESC'); ?>
                   
                </td>
            </tr>
            <tr>
                <td class="key" align="right"></td>
                <td>
                    <button onclick="insertPagebreak();return false;"><?php echo JText::_('JLIST_BUTTON_PLG_CAT_BUTTON_TEXT'); ?></button>
                </td>
            </tr>
            <tr><td colspan="2"><?php echo JText::_('JLIST_BUTTON_PLG_INFO'); ?></td></tr>
        </table>
        </form>
        <?php
} 
    
// create new directory
function directoriesNew($option){
	global $mainframe, $jlistConfig;

    $marked_dir     = JArrayHelper::getValue($_REQUEST, 'dirs', array());
    if ($marked_dir == '') {
       $marked_dir = '/'.$jlistConfig['files.uploaddir'].'/';
    }
    $new_dir_name   = JArrayHelper::getValue($_REQUEST, 'new_subdir', '');
    $new_dir_name   = str_replace('/', '', $new_dir_name);
    $new_dir_name   = trim($new_dir_name);

    $new_dir = JPATH_SITE.$marked_dir.$new_dir_name;

    // create new dir if not exists
    $dir_exist = is_dir("$new_dir");
    if(!$dir_exist) {
       if ($makedir = @mkdir("$new_dir", 0755)) {
    	   $message = str_replace(JPATH_SITE.'/', '', $new_dir).' '.JText::_('JLIST_BACKEND_DIRSEDIT_CREATE_DIR_MESSAGE_OK');
		   } else {
    	   $message = str_replace(JPATH_SITE.'/', '', $new_dir).' '.JText::_('JLIST_BACKEND_DIRSEDIT_CREATE_DIR_MESSAGE_ERROR');
           }
	} else {
       $message = str_replace(JPATH_SITE.'/', '', $new_dir).' '.JText::_('JLIST_BACKEND_DIRSEDIT_CREATE_DIR_MESSAGE_EXISTS');
    }
	$mainframe->redirect('index2.php?option='.$option.'&task=directories.edit',$message);
}

// delete subdirectory incl. files
function directoryRemove($option){
	global $mainframe, $jlistConfig;

    $marked_dir = JArrayHelper::getValue($_REQUEST, 'del_dir', array());

    // is value = root dir or false value - do nothing
    if ($marked_dir == '/'.$jlistConfig['files.uploaddir'].'/' || !stristr($marked_dir, '/'.$jlistConfig['files.uploaddir'].'/')) {
        $message = $del_dir.' '.JText::_('JLIST_BACKEND_DIRSEDIT_DELETE_DIR_ROOT_ERROR');
    	$mainframe->redirect('index2.php?option='.$option.'&task=directories.edit',$message);
    } else {
        // del marked dir complete
        $res = delete_dir_and_allfiles (JPATH_SITE.$marked_dir);

        switch ($res) {
          case 0:
            $message = $marked_dir.'<br />'.JText::_('JLIST_BACKEND_DIRSEDIT_DELETE_DIR_MESSAGE_OK');
            break;
          case -2:
            $message = $marked_dir.'<br />'.JText::_('JLIST_BACKEND_DIRSEDIT_DELETE_DIR_MESSAGE_ERROR');
            break;
          default:
            $message = $marked_dir.'<br />'.JText::_('JLIST_BACKEND_DIRSEDIT_DELETE_DIR_MESSAGE_ERROR_X');
            break;
        } 
	    $mainframe->redirect('index2.php?option='.$option.'&task=directories.edit',$message);
	}
}

/*
* Read configuration parameter
*
* @return jlistConfig
*/
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

// get upload directories
//
// @return dirs
//
function getDirectories() {
	global $jlistConfig;

    $dirs = array();
	//search all subdirectories
	if(file_exists(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'])){
		if ($handle = opendir(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'])) {
		    // List all the files
		    while (false !== ($file = readdir($handle))) {
              if($file != '.' && $file != '..') {
                if(is_dir(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$file)) {
		    		// added tio list array
		    		if ($file !== 'tempzipfiles') {
                        $dirs[] = $file;
                        }
   			    	}
                  }
			    }
		}
	    closedir($handle);
	}
	return $dirs;
}

// get all files in upload dir and subdirs
//
// @return up_files
//
function getFiles($searchdir) {
	global $jlistConfig;
    $up_files = array();

	if(file_exists(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'])){
       $startdir       = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/';
       $dir_len      = strlen($startdir);
       $dir          = $startdir;
       $type         = array("zip","txt","pdf");
       $only         = FALSE;
       $allFiles     = TRUE;
       $recursive    = TRUE;
       $onlyDir      = TRUE;
       $files        = array();
       $file         = array();

       $all_dirs = scan_dir($dir, $typ, $only, $allFiles, $recursive, $onlyDir, $files);
       if ($all_dirs != FALSE) {
           reset ($files);
           foreach($files as $key => $array) {
             // ist dirname > startdir?
             if ($startdir <> $files[$key]['path']) {
                 // unterverzeichnis vorhanden - nur pfadnamen ab download root + dateinamen
                 $restpath = substr($files[$key]['path'], $dir_len);
                 $files[$key]['path'] = $restpath;
                 } else { // dir ist startdir - also nur filenamen
                     $files[$key]['path'] = '';
                 }
           }

           // list all files
           foreach($files as $key3 => $array2) {
                if ($files[$key3]['file'] <> '') {
                    // no files in tempzifiles directory
                    if(strpos($files[$key3]['path'], 'tempzipfiles') === FALSE) {
                       $up_files[] = $files[$key3]['path'].$files[$key3]['file'];
                    }
                }
           }
       }
    }
	return $up_files;
}

// Kopiert alle dirs inkl. subdirs und files nach $dest
// und loescht abscchliessend das $source dir
function moveDirs($source, $dest, $recursive = true, $message) {

    $error = false;
	
	if (!is_dir($dest)) { 
        mkdir($dest); 
  	} 
 
    $handle = @opendir($source);
    
    if(!$handle) {
        $message = JText::_('JLIST_BACKEND_CATSEDIT_ERROR_CAT_COPY');
        return $message;
    }
    
    while ($file = @readdir ($handle)) {
        if (eregi("^\.{1,2}$",$file)) {
            continue;
        }
        
        if(!$recursive && $source != $source.$file."/") {
            if(is_dir($source.$file))
                continue;
        }
        
        if(is_dir($source.$file)) {
            moveDirs($source.$file."/", $dest.$file."/", $recursive, $message);
        } else {
            if (!@copy($source.$file, $dest.$file)) {
				$error = true;
			}
        }
    }
    @closedir($handle);
    
    // $source loeschen wenn KEIN error
    if (!$error) {
		$res = delete_dir_and_allfiles ($source);	
        if ($res) {
			$message = JText::_('JLIST_BACKEND_CATSEDIT_ERROR_CAT_DEL_AFTER_COPY');		
		}
	} else {
		$message = JText::_('JLIST_BACKEND_CATSEDIT_ERROR_CAT_COPY');
	}
	return $message;
} 

// check for aktive or default layout before delete
//
// @return error_msg
//
function checkTemplatesStatus($total,$cid) {
    $database = &JFactory::getDBO();

    $error_msg = '';
    
    // default template can not erase!
    for( $i=0; $i < $total; $i++ ) {
    	$database->setQuery("SELECT locked FROM #__jdownloads_templates WHERE id = ($cid[$i])");
        if ($database->loadResult() == 1 ) {
            $error_msg = JText::_('JLIST_BACKEND_TEMPLIST_ERROR_IS_LOCKED');
        }
    }
    // active template can not erase!
    for( $i=0; $i < $total; $i++ ) {
    	$database->setQuery("SELECT template_active FROM #__jdownloads_templates WHERE id = ($cid[$i])");
        if ($database->loadResult() == 1 ) {
            $error_msg = JText::_('JLIST_BACKEND_TEMPLIST_ERROR_IS_ACTIVE');
        }
    }
    return $error_msg;
}

// delete_dir_and_allfiles - rekursiv loeschen
// Rueckgabewerte:
//    0 - ok
//   -1 - kein Verzeichnis
//   -2 - Fehler beim Loeschen
//   -3 - Ein Eintrag war keine Datei/Verzeichnis/Link

function delete_dir_and_allfiles ($path) {
    if (!is_dir ($path)) {
        return -1;
    }
    $dir = @opendir ($path);
    if (!$dir) {
        return -2;
    }

    while (($entry = @readdir($dir)) !== false) {
        if ($entry == '.' || $entry == '..') continue;
        if (is_dir ($path.'/'.$entry)) {
            $res = delete_dir_and_allfiles ($path.'/'.$entry);
            // manage errors
            if ($res == -1) {
                @closedir ($dir); 
                return -2; 
            } else if ($res == -2) {
                @closedir ($dir); 
                return -2; 
            } else if ($res == -3) {
                @closedir ($dir); 
                return -3; 
            } else if ($res != 0) { 
                @closedir ($dir); 
                return -2; 
            }
        } else if (is_file ($path.'/'.$entry) || is_link ($path.'/'.$entry)) {
            // delete file
            $res = @unlink ($path.'/'.$entry);
            if (!$res) {
                @closedir ($dir);
                return -2; 
            }
        } else {
            @closedir ($dir);
            return -3;
        }
    }
    @closedir ($dir);
    // delete dir
    $res = @rmdir ($path);
    if (!$res) {
        return -2;
    }
    return 0;
}

// Beispieldaten installieren
function sampleInstall($option){
   global $jlistConfig;
   $database = &JFactory::getDBO();
   
        $root_dir = $jlistConfig['files.uploaddir'];
        // beispieldaten speichern - wenn neuinstallation
        $dir_exist = is_dir(JPATH_SITE.'/'.$root_dir);
            if($dir_exist) {
                if (is_writable(JPATH_SITE.'/'.$root_dir)) {      
                    if (!is_dir(JPATH_SITE.'/'.$root_dir.'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT'))){
                        // daten speichern
                        // dirs fuer cats
                        $makdir = @mkdir(JPATH_SITE.'/'.$root_dir.'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT'), 0755);
                        $makdir = @mkdir(JPATH_SITE.'/'.$root_dir.'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT').'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_SUB'), 0755);
                        // cat erstellen in db
                        if ($makdir) {
                            $database->setQuery("INSERT INTO #__jdownloads_cats (cat_title, cat_description, cat_dir, parent_id, cat_pic, published)  VALUES ('".JText::_('JLIST_SAMPLE_DATA_CAT_NAME_ROOT')."', '".JText::_('JLIST_SAMPLE_DATA_CAT_NAME_TEXT')."', '".JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT')."', 0, 'joomla.png', 1)");
                            $database->query();
                            $cattitle = JText::_('JLIST_SAMPLE_DATA_CAT_NAME_ROOT');
                            $database->setQuery("SELECT cat_id FROM #__jdownloads_cats WHERE cat_title = '$cattitle'");
                            $catid = $database->loadResult();
                            $database->setQuery("INSERT INTO #__jdownloads_cats (cat_title, cat_description, cat_dir, parent_id, cat_pic, published)  VALUES ('".JText::_('JLIST_SAMPLE_DATA_CAT_NAME_SUB')."', '".JText::_('JLIST_SAMPLE_DATA_CAT_NAME_TEXT')."', '".JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT').'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_SUB')."', ".$catid.", 'joomla.png', 1)");
                            $database->query();
                            // file kopieren nach catdir
                            $source_path = JPATH_SITE.'/administrator/components/com_jdownloads/mod_jdownloads_top_1.5.zip';
                            $dest_path = JPATH_SITE.'/'.$root_dir.'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT').'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_SUB').'/mod_jdownloads_top_1.5.zip'; 
                            @copy($source_path, $dest_path);
                            // downloads erstellen
                            $cattitle = JText::_('JLIST_SAMPLE_DATA_CAT_NAME_SUB');
                            $database->setQuery("SELECT cat_id FROM #__jdownloads_cats WHERE cat_title = '$cattitle'");
                            $catid = $database->loadResult();
                            
                            $database->setQuery("INSERT INTO #__jdownloads_files (`file_id`, `file_title`, `description`, `description_long`, `file_pic`, `price`, `release`, `language`, `system`, `license`, `url_license`, `size`, `date_added`, `url_download`, `url_home`, `author`, `url_author`, `created_by`, `created_mail`, `modified_by`, `modified_date`, `downloads`, `cat_id`, `ordering`, `published`, `checked_out`, `checked_out_time`) VALUES (NULL, '".JText::_('JLIST_SAMPLE_DATA_FILE_NAME')."', '".JText::_('JLIST_SAMPLE_DATA_FILE_NAME_TEXT')."', '".JText::_('JLIST_SAMPLE_DATA_FILE_NAME_TEXT')."', 'joomla.png', '', '1.0', '2', '1', '1', '', '1.92 KB', '".date('Y-m-d H:i:s')."', 'mod_jdownloads_top_1.5.zip', 'www.jDownloads.com', 'Arno Betz', 'info@.jDownloads.com', 'Installer', '', '', '0000-00-00 00:00:00', '0', '".$catid."', '0', '1', '0', '0000-00-00 00:00:00')");
                            $database->query();
                            checkAlias();
                            echo "<br /><font color='green'> ".JText::_('JLIST_SAMPLE_DATA_CREATE_OK')."<br />";
                        }
                    } else {
                        // daten existieren schon
                        echo "<br /><font color='red'> ".JText::_('JLIST_SAMPLE_DATA_EXISTS')."</font><br />";
                    } 
                } else {
                    // fehlermeldung: daten konnten nicht gespeichert werden
                    echo "<br /><font color='red'> ".JText::_('JLIST_SAMPLE_DATA_CREATE_ERROR')."</font><br />";
                } 
                       
            } else {
                // fehlermeldung: daten konnten nicht gespeichert werden
                echo "<br /><font color='red'> ".JText::_('JLIST_SAMPLE_DATA_CREATE_ERROR')."</font><br />";
            }
                echo '<br /><br /><a href="index2.php?option=com_jdownloads&task=" title="'.JText::_('JLIST_SAMPLE_DATA_BACK_TO_PANEL').'">'.JText::_('JLIST_SAMPLE_DATA_BACK_TO_PANEL').'</a><br />';
}

// Dateigroesse einer externen Datei ermitteln
function urlfilesize($url) {
    if (substr($url,0,4)=='http' || substr($url,0,3)=='ftp') {
        // for php 4 users
        if (!function_exists('get_headers')) {
            function get_headers($url, $format=0) {
                $headers = array();
                $url = parse_url($url);
                $host = isset($url['host']) ? $url['host'] : '';
                $port = isset($url['port']) ? $url['port'] : 80;
                $path = (isset($url['path']) ? $url['path'] : '/') . (isset($url['query']) ? '?' . $url['query'] : '');
                $fp = fsockopen($host, $port, $errno, $errstr, 3);
                if ($fp) {
                    $hdr = "GET $path HTTP/1.1\r\n";
                    $hdr .= "Host: $host \r\n";
                    $hdr .= "Connection: Close\r\n\r\n";
                    fwrite($fp, $hdr);
                    while (!feof($fp) && $line = trim(fgets($fp, 1024))) {
                        if ($line == "\r\n") break;
                        list($key, $val) = explode(': ', $line, 2);
                        if ($format)
                            if ($val) $headers[$key] = $val;
                            else $headers[] = $key;
                        else $headers[] = $line;
                    }
                    fclose($fp);
                    return $headers;
                }
                return false;
            }
        }
        $size = array_change_key_case(get_headers($url, 1),CASE_LOWER);
        $size = $size['content-length'];
        if (is_array($size)) { $size = $size[1]; }
    } else {
        $size = @filesize($url); 
    }
    $a = array("B", "KB", "MB", "GB", "TB", "PB");

    $pos = 0;
    while ($size >= 1024) {
           $size /= 1024;
           $pos++;
    }
    return round($size,2)." ".$a[$pos];    
} 
         
// datum der externen datei holen
function urlfiledate($url){
    if (file_exists($url)){
        $aktuell = date("Y-m-d H:i:s",filemtime($url));
    } else {
        $aktuell = date("Y-m-d H:i:s");
    }    
  return $aktuell;
}      

function create_new_thumb($picturepath) {
    global $jlistConfig;
    $thumbpath = JPATH_SITE.'/images/jdownloads/screenshots/thumbnails/';
    if (!is_dir($thumbpath)){
        @mkdir("$thumbpath", 0755);
    }    
    $newsize = $jlistConfig['thumbnail.size.width'];
    $thumbfilename = $thumbpath.basename($picturepath);
    if (file_exists($thumbfilename)){
       return true;
    }   
    
    /* Pruefen ob Datei existiert */
    if(!file_exists($picturepath)) {
        return false;
    }
    
    /* MIME-Typ auslesen */
    $size=getimagesize($picturepath);
    switch($size[2]) {
        case "1":
        $oldpic = imagecreatefromgif($picturepath);
        break;
        case "2":
        $oldpic = imagecreatefromjpeg($picturepath);
        break;
        case "3":
        $oldpic = imagecreatefrompng($picturepath);
        break;
        default:
        return false;
    }
    /* Alte Groesse auslesen */
    $width = $size[0];
    $height = $size[1]; 
    /* Neue Groesse errechnen */

    $maxwidth = $jlistConfig['thumbnail.size.width'];
    $maxheight = $jlistConfig['thumbnail.size.height'];
    if ($width/$maxwidth > $height/$maxheight) {
        $newwidth = $maxwidth;
        $newheight = $maxwidth*$height/$width;
    } else {
        $newheight = $maxheight;
        $newwidth = $maxheight*$width/$height;
    }
    /* Neues Bild erstellen mit den neuen Groesse */
    // png fix
    //$img = imagecreatefrompng($picturepath);
    //imagesavealpha($img,true);
    $newpic = imagecreatetruecolor($newwidth,$newheight);
    imagealphablending($newpic,false);
    imagesavealpha($newpic,true);
    
    /* Jetzt wird das Bild nur noch verkleinert */
    imagecopyresampled($newpic,$oldpic,0,0,0,0,$newwidth,$newheight,$width,$height); 
    // Bild speichern
    switch($size[2]) {
        case "1":    return imagegif($newpic, $thumbfilename);
        break;
        case "2":    return imagejpeg($newpic, $thumbfilename);
        break;
        case "3":    return imagepng($newpic, $thumbfilename);
        break;
    }
    //Bilderspeicher freigeben
    imagedestroy($oldpic);
    imagedestroy($newpic);
}

function set_rights_to_tree($p_catid, $p_right, $p_right_from, &$p_changed){
// function coded by pelma
// Funktion welche die Rechte eines Kategoriebaum setzt. Achtung REKURSIV !!!
// $p_catid      = ID der Kategorie deren Rechte gesetzt werden soll.
// $p_right      = Die Rechte welche gesetzt werden.
// $p_right_from = Die urspruenglichen Rechte
// $p_changed    = Anzahl der Korrekturen   
// echo $p_catid.' p_right_from:'.$p_right_from.' p_right:'.$p_right.'<br />';
    $database = &JFactory::getDBO();
    // Lesen der Kategorie aus der Datenbank.
    $l_sql = "SELECT cat_access FROM #__jdownloads_cats WHERE cat_id = ".$p_catid;
    $database->setQuery($l_sql);
    $r_catrow = $database->loadObjectList();

    // Hier werden die eigentlichen Rechte der aktuellen Kategorie gesetzt.
    //  Falls die Rechte der aktuellen Kategorie KLEINER sind als die zu setzenden Rechte.
    //  Damit wird verhindert, dass Unterkategorien welche schon hoehere Rechte haben nicht ueberschrieben werden.
    // Oder
    //  Falls die Rechte der aktuellen Kategorie kleiner oder gleich sind als die urspruenglichen Rechte.
    //  Sonst koennen kleinere Werte (=hoehere Rechte) nicht gesetzt werden.
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
      // Falls die Funktion in der Rekursivitaet ist, wird in der unteren foreach-Schleife die naechste Unterkategorie aufgerufen.
       return;
    }
    // Alle Unterkategorien abfahren.
    foreach ($l_childrows as $l_childrow){
      // Zuerst: Automatische Korrektur von Fehlern.
      // D.h. Eine Unterkategorie welche schon niedrigere Rechte hat (=hoeheren Wert in cat_access) muesste eigentlich nicht abgefahren werden.
      // Es koennte aber sein, dass diese Fehlern aufweist (z.B. bei einem Update von 1.3 nach 1.4).
      // Fehler heisst in diesem Fall, dass eine Unter-Unter-Kategorie groessere Rechte hat (=niedriger Wert in cat_access).
      // Dies ist ja verboten und muss korrigiert werden.
      // Dazu:
      // Die aktuelle Unterkategorie aus der Datenbank lesen
      $l_sql = "SELECT cat_access FROM #__jdownloads_cats WHERE cat_id = ".$l_childrow->cat_id;
      $database->setQuery($l_sql);
      $l_child = $database->loadObjectList();
      // Die Original verlangten Werte als Defaut setzen.
      $l_right = $p_right;
      $l_right_from = $p_right_from;

      // Falls die Rechte der abzufahrenden Unterkategorie kleiner sind (cat_access groesser) als die urspruenglichen Rechte
      // Und: die Rechte der abzufahrenden Unterkategorie kleiner sind (cat_access groesser) als die zu setzenden Rechte
      // Dann: die eigenen Rechte der Unterkategorie ihr selbst als neu zu setzende Rechte uebergeben.
      if (($l_child[0]->cat_access > $p_right_from) && ($l_child[0]->cat_access > $p_right)){
        $l_right = $l_child[0]->cat_access;
        $l_right_from = $l_child[0]->cat_access;
      }
      // Fuer alle Unterkategorien die Funktion nochmals aufrufen.
      set_rights_to_tree($l_childrow->cat_id, $l_right, $l_right_from, $p_changed);
    }
}

function get_lowest_rights($p_catid, $p_suggest_right){
// function coded by pelma  
// Funktion welche alle darueberliegenden Kategorien nach niedrigeren Rechten (=hoehere Werte) durchsucht,
// und den hoechsten Wert zurueckgibt. Diese Funktion ist nicht rekursiv.
// $p_catid =           KategorienID, von welcher aus nach oben durchsucht wird.
// $p_suggested_right = Die rechte welche gesetzt werden sollen, und hier ueberprueft werden.
    $database = &JFactory::getDBO();
    // Kategorie laden aus Datenbank
    $l_sql = "SELECT cat_id, parent_id, cat_access FROM #__jdownloads_cats WHERE cat_id = ".$p_catid;
    $database->setQuery($l_sql);
    $l_catrow = $database->loadObjectList();
    if (!isset($l_catrow[0])){
      // Die Kategorie existiert nicht. Nicht weiterfahren, aber die vorgeschlagenen Rechte zurueckgeben.
      // (Dies sollte eigentlich nie vorkommen)
     return $p_suggest_right;
    }
    // Initialiseren der Rechte welche von der Funktion zurueckgegeben werden.
    $l_therights = $p_suggest_right;
    // Den Kategorien-Baum solange hochfahren bis keine hoehere Kategorie mehr existiert. (d.h. bis die Hauptkategorie erreicht ist)
    while ($l_catrow[0]->parent_id > 0 ){
      // Naechst hoehere Parent-Kategorie aus Datenbank lesen.
      $l_sql = "SELECT parent_id, cat_access FROM #__jdownloads_cats WHERE cat_id = ".$l_catrow[0]->parent_id;
      $database->setQuery($l_sql);
      $l_catrow = $database->loadObjectList();
      // Wenn die geladene Parent-Kategorie einen hoeheren Wert hat, diesen uebernehmen.
      if ($l_catrow[0]->cat_access > $l_therights){
          $l_therights = $l_catrow[0]->cat_access;
      }
    }
    // Zurueck mit hoechstem gefundenem Wert (=niedrigstes Recht)
    return $l_therights;
}

function set_rights_of_cat($p_catid, $p_suggest_right, &$p_changed){
// function coded by pelma  
// Hauptprozedur. Diese wird aufgerufen um die Rechte einer Kategorie zu setzen, inklusive deren Unterkategorien.
// $p_catid =           KategorienID, welche gesetzt werden soll.
// $p_suggested_right = Die rechte welche gesetzt werden sollen.
// $p_changed         = Anzahl der Korrekturen oder (-1): Gewuenschte aenderung war nicht zulaessig!  
    $database = &JFactory::getDBO();
    // Kategorie laden aus Datenbank.
    $l_sql = "SELECT parent_id, cat_access FROM #__jdownloads_cats WHERE cat_id = ".$p_catid;
    $database->setQuery($l_sql);
    $l_catrow = $database->loadObjectList();
    if (!isset($l_catrow[0]) && ($p_catid > 0)){
      // Die Kategorie existiert nicht. Nicht weiterfahren.
      return '';
    }
    // Urspruengliche Rechte der Kategorie lesen.
    $l_rights_from = $l_catrow[0]->cat_access;
    if ($l_catrow[0]->parent_id == 0){
      // Es ist eine Hauptkategorie. Darueberliegende Kategorien muessen nicht nach niedrigen Rechten durchsucht werden.
      $l_rights_to_set = $p_suggest_right;
    } else {
      // Es ist eine Unterkategorie. Darueberliegenden Kategoriebaum nach niedrigen Rechten (=hoeherer Wert) durchsuchen.
      // Damit wird gewaehrleitet, dass eine Unterkategorie keine hoeheren Rechte erhalten kann.
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
            $name = strtr($name, array( "'" => "", 'ä' => 'ae', 'ü' => 'ue', 'ö' => 'oe', 'Ä' => 'ae', 'Ü' => 'ue', 'Ö' => 'oe', 'Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));
            // remove invalid chars
            $name = preg_replace('#[^A-Za-z0-9 _.-]#', '', $name);
        }
        //$name = utf8_decode($name); 
    }               
    return $name;    
}

function checkAlias(){
    $database = &JFactory::getDBO(); 
    // check alias field
    $database->setQuery("SELECT cat_id, cat_title, cat_alias FROM #__jdownloads_cats WHERE cat_alias = ''");
    $cats = $database->loadObjectList();
    if ($cats){
        foreach ($cats as $cat){
            $cat->cat_alias = $cat->cat_title;
            $cat->cat_alias = JFilterOutput::stringURLSafe($cat->cat_alias);
            if(trim(str_replace('-','',$cat->cat_alias)) == '') {
                $datenow =& JFactory::getDate();
                $cat->cat_alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
            }
            $database->setQuery("UPDATE #__jdownloads_cats SET cat_alias = '$cat->cat_alias' WHERE cat_id = '$cat->cat_id'");  
            $database->query();                                             
        }    
    }
    $database->setQuery("SELECT file_id, file_title, file_alias FROM #__jdownloads_files WHERE file_alias = ''");
    $files = $database->loadObjectList();
    if ($files){
        foreach ($files as $file){
            $file->file_alias = $file->file_title;
            $file->file_alias = JFilterOutput::stringURLSafe($file->file_alias);
            if(trim(str_replace('-','',$file->file_alias)) == '') {
                $datenow =& JFactory::getDate();
                $file->file_alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
            }
            $database->setQuery("UPDATE #__jdownloads_files SET file_alias = '$file->file_alias' WHERE file_id = '$file->file_id'");  
            $database->query();                                             
        }    
    }   
}

function deleteLog($option){
    global $mainframe, $jlistConfig;
    $database = &JFactory::getDBO();   
    $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '' WHERE setting_name = 'last.log.message'");
    $database->query();
    $jlistConfig['last.log.message'] = '';
    $mainframe->redirect("index2.php?option=com_jdownloads");
}  

function deleteRestoreLog($option){
    global $mainframe, $jlistConfig;
    $database = &JFactory::getDBO();   
    $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '' WHERE setting_name = 'last.restore.log'");
    $database->query();
    $jlistConfig['last.restore.log'] = '';
    $mainframe->redirect("index2.php?option=com_jdownloads");
} 

function addAUPPoints($submitted_by, $file_title){
    // added new points to the alphauserpoints when is activated in the jD config
    // $submitted_by = user ID after upload a file
    global $jlistConfig;
    if ($jlistConfig['use.alphauserpoints'] && $submitted_by){
        $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
        if (file_exists($api_AUP)){
            require_once ($api_AUP);
            $aupid = AlphaUserPointsHelper::getAnyUserReferreID( $submitted_by );
            if ($aupid){
                $text = JText::_('JLIST_BACKEND_SET_AUP_UPLOAD_TEXT');
                $text = sprintf($text,$file_title);
                 AlphaUserPointsHelper::newpoints('plgaup_jdownloads_user_upload_published', $aupid, $file_title, $text);
            }                                     
        }    
    }
}

//
// need PHP >= 5.2.0 and PECL zip >= 1.1.0 
function getXMLdata($fileandpath, $filename){
    global $jlistConfig;
    jimport( 'joomla.filesystem.archive' );
    $files_list = array();
    $xml_files = array();
    $xmltags = array();
    $path_parts = pathinfo($fileandpath);
    $destination_dir = JPATH_ROOT.DS.$jlistConfig['files.uploaddir'].DS.'tempzipfiles'.DS.$path_parts['filename'];
    if ($ok = @mkdir($destination_dir.DS)){
        if(JArchive::extract($fileandpath, $destination_dir.DS)){
            // get files list
            $xml_files = scan_dir($destination_dir.DS, $type=array('.xml','.XML'), $only=false, $allFiles=false, $recursive=TRUE, $onlyDir="", $files_list);
            if ($xml_files){
                foreach($xml_files as $key => $array2) {
                   $filepath[] = $xml_files[$key]['path'].DS.$xml_files[$key]['file'];
                }
                $xml_file = usort($filepath, "cmp_str"); 
                foreach($filepath as $fpath){
                   $xmltags = use_xml($fpath);
                   // get xml file tags
                   if ($xmltags[name] != ''){
                       delete_dir_and_allfiles($destination_dir.DS);
                       return $xmltags;
                       break; 
                   }    
                }
           }    
        }
        // delete all unzipped files and folder
        delete_dir_and_allfiles($destination_dir.DS);
    } 
    return false;     
}

function use_xml($u_xml){
    // function by JoomTools
    $felder = array("name","author","authorUrl", "authorMail", "creationDate","copyright","license","version","description");
    foreach($felder as $feld){
        $wert =preg_replace("/\s\s+/","",stripslashes(read_xml("<$feld>(.*)</$feld>",$u_xml)));
        $wert =str_replace(chr(91), '-', str_replace(chr(93), '-', $wert));
        $wert =ereg_replace("<!-CDATA-", "", $wert);
        $wert =ereg_replace("-->", "", $wert);
        $tag[$feld] = $wert;
    }
    return $tag;
}

function read_xml($search,$xmlfile){
    // function by JoomTools
    $fp = fopen($xmlfile,"r");
    while(!feof($fp)){
        $r_xml .= fgets($fp);
    }
    fclose($fp);
    eregi($search, $r_xml, $search_result1);
    $search_result = trim($search_result1[1]);
    return $search_result;
}

// fill file data from a given xml install file
function fillFileDateFromXML($row, $xmltags){
    $database = &JFactory::getDBO();   
    $lic_id = '';
    if ($xmltags['license']){
        $database->setQuery("SELECT id FROM #__jdownloads_license WHERE license_title LIKE '%".$xmltags['license']."%' OR license_url LIKE '%".$xmltags['license']."%'");
        $lic_id = $database->loadResult();                                      
    }
    $row->file_title       = $xmltags['name'];
    //alias
    $row->file_alias = JFilterOutput::stringURLSafe($row->file_title);

    if(trim(str_replace('-','',$row->file_alias)) == '') {
       $datenow =& JFactory::getDate();
       $row->file_alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
    }
    $row->release          = $xmltags['version']; 
    $row->description      = $xmltags['description'];
    $row->description_long = $row->description;
    if (!$lic_id){                                                           
        $row->license      = '';
    } else {
        $row->license      = (int)$lic_id;
    }    
    if ($date = strtotime($xmltags['creationDate'])){
        $row->file_date    = JHTML::_('date', $xmltags['creationDate'],'%Y-%m-%d %H:%M:%S');
    } else {
        $row->file_date    = '0000-00-00 00:00:00';
    }     
    $row->url_home         = $xmltags['authorUrl'];
    $row->author           = $xmltags['author'];
    $row->url_author       = $xmltags['authorMail'];
    return $row;
}                   

function cmp_str($a, $b) {
   if (strlen($a) == strlen($b)) {
     return 0;
     
   }
   return strlen($a) > strlen($b) ? 1 : -1;
}

function DatumsDifferenz_JD($Start,$Ende) {
    $Tag1=(int) substr($Start, 8, 2);
    $Monat1=(int) substr($Start, 5, 2);
    $Jahr1=(int) substr($Start, 0, 4);
    
    $Tag2=(int) substr($Ende, 8, 2);
    $Monat2=(int) substr($Ende, 5, 2);
    $Jahr2=(int) substr($Ende, 0, 4);

    if (checkdate($Monat1, $Tag1, $Jahr1)and checkdate($Monat2, $Tag2, $Jahr2)){
        $Datum1=mktime(0,0,0,$Monat1, $Tag1, $Jahr1);
        $Datum2=mktime(0,0,0,$Monat2, $Tag2, $Jahr2);

        $Diff=(Integer) (($Datum1-$Datum2)/3600/24);
        return $Diff;
    } else {
        return -1;
    }
}
?>