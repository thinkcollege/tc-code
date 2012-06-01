<?php
/**
* @version 1.6
* @package JDownloads
* @copyright (C) 2010 www.jdownloads.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* 
*
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
Error_Reporting(E_ERROR);

function com_install() {

    $params   = JComponentHelper::getParams('com_languages');
    $frontend_lang = $params->get('site', 'en-GB');
    $language = JLanguage::getInstance($frontend_lang);

    // get language file for default layouts
    $language = &JFactory::getLanguage();
    $language->load('com_jdownloads'); 
    $database = &JFactory::getDBO();
    
  //*********************************************
  // JD VERSION:
     $jd_version = '1.7.5';
     $jd_version_state = 'Stable';
     $jd_version_svn = '776'; 
  //*********************************************
  
   
  ?>
  <center>
  <table width="100%" border="0">
   <tr>
      <td align="center">
        <img src="<?php echo JURI::base(); ?>components/com_jdownloads/images/jdownloads.jpg" border="0" alt="jDownloads Logo" /><br /><?php echo  'Version '.$jd_version.' '.$jd_version_state; ?>
      </td>
   </tr>   
   <tr>   
      <td background="E0E0E0" style="border:1px solid #999;">
        <code><b><?php echo JText::_('JLIST_INSTALL_0'); ?></b><br />
   
   <?php
    
   // exist the tables?
   $prefix = $database->_table_prefix; 
   $tablelist = $database->getTableList();
   if ( !in_array ( $prefix.'jdownloads_config', $tablelist ) ){
         echo '<p><font color="red"><big><b>'.JText::_('JLIST_INSTALL_ERROR_NO_TABLES').'</b></big></font></p>';
   } else {
   
      
   // make only as update when prior version is >= 1.4
   // *************************************************
   $database->setQuery("SELECT setting_value FROM #__jdownloads_config WHERE setting_name = 'jd.version'");
   $version = floatval($database->loadResult());
   if ($version && $version < 1.4) {
        // update not supported
        echo '<font color="red"><b><big>--> '.JText::_('JLIST_INSTALL_ABORT').'</big></b></font><br /></code></td></tr></table></center>'; 
          
   } else {   
       
      // install component



//********************************************************************************************
// move all images dirs to joomla images dir when not exists
// 
// *******************************************************************************************
    $source_dir   = array();
    $message = '';
    $ok = 0;
    $create_root_ok = true;
    
    $image_root   = JPATH_SITE.'/images/jdownloads/';
    $source_root  = JPATH_SITE.'/components/com_jdownloads/';
    $source_dir[] = 'catimages/';
    $source_dir[] = 'fileimages/';
    $source_dir[] = 'miniimages/';
    $source_dir[] = 'hotimages/';
    $source_dir[] = 'newimages/';
    $source_dir[] = 'updimages/';
    $source_dir[] = 'downloadimages/';
    $source_dir[] = 'headerimages/';    
    $source_dir[] = 'screenshots/'; 
    
    if (!is_dir($image_root)){
       if (!@mkdir($image_root, 0755)){
           // wwwrun problems?
           echo '<font color="red">--> '.JText::_('JLIST_INSTALL_MOVE_IMAGES_CREATE_ROOT_DIR_ERROR').'</font><br />'; 
           $create_root_ok = false;     
       }    
    }       
    if ($create_root_ok){
        $error = false;
        foreach($source_dir as $source){
            $sourcedir = $source_root.$source;
            if (@is_dir($sourcedir)){ 
                $destdir = $image_root.$source;
                if (!is_dir($destdir)){
                    $res = moveDirs($sourcedir, $destdir, true, $message);
                    if ($message != '') {
                        // Fehler
                        echo '<font color="red">--> '.JText::_('JLIST_INSTALL_MOVE_IMAGES_ERROR').' '.$message.'</font><br />';
                        $error = true;
                        $message = '';
                    } else {
                        // ok
                        $ok ++;
                    }
                }    
            }     
        }
   }
   if ($ok > 0){
       echo '<font color="green">--> '.$ok.' '.JText::_('JLIST_INSTALL_MOVE_IMAGES_OK').'</font><br />';     
   } else {
       if (!$error) {
           echo '<font color="green">--> '.JText::_('JLIST_INSTALL_MOVE_IMAGES_DEST_DIR_EXIST').'</font><br />';     
       } 
   }
   
   if ($create_root_ok){
        // dirs löschen falls nicht verschoben wurde, da schon da
        foreach($source_dir as $source){ 
            if (is_dir($source_root.$source)){
                delete_dir_and_allfiles($source_root.$source);   
            }    
        }    
   }   
        
//********************************************************************************************
// insert default config data - if not exist
// 
// *******************************************************************************************

      $sum_configs = 0;
      $root_dir = '';
      $is_update = false;
      
   		$database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'files.uploaddir'");
        $temp = $database->loadResult();
        if (!$temp) {
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('files.uploaddir', 'jdownloads')");
            $database->query();
            $sum_configs++;
        }  else {
            $database->setQuery("SELECT setting_value FROM #__jdownloads_config WHERE setting_name = 'files.uploaddir'");
            $dir = $database->loadResult();
            $root_dir = JPATH_SITE.'/'.$dir.'/';   
            $is_update = true;
        }    

   		$database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'global.datetime'");
        $temp = $database->loadResult();
        if (!$temp) {
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('global.datetime', '".JText::_('JLIST_INSTALL_DEFAULT_DATE_FORMAT')."')");
            $database->query();
            $sum_configs++;
        } else {
            $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '".JText::_('JLIST_INSTALL_DEFAULT_DATE_FORMAT')."' WHERE setting_name = 'global.datetime'");
            $database->query();
            $jlistConfig['global.datetime'] = JText::_('JLIST_INSTALL_DEFAULT_DATE_FORMAT');
        }

   		$database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'files.autodetect'");
        $temp = $database->loadResult();
        if (!$temp) {
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('files.autodetect', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto', '".JText::_('JLIST_SETTINGS_INSTALL_5')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.option', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.betreff', '".JText::_('JLIST_SETTINGS_INSTALL_3')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.from', '".JText::_('JLIST_SETTINGS_INSTALL_4')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.fromname', 'JDownloads')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.html', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('zipfile.prefix', 'downloads_')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('files.order', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('checkbox.top.text', '".JText::_('JLIST_SETTINGS_INSTALL_1')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('downloads.titletext', '')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('layouts.editor', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('licenses.editor', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('files.editor', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('categories.editor', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('info.icons.size', '20')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('cat.pic.size', '48')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('file.pic.size', '32')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('offline', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('offline.text', '".JText::_('JLIST_BACKEND_OFFLINE_MESSAGE_DEFAULT')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('system.list', '".JText::_('JLIST_BACKEND_FILESEDIT_SYSTEM_DEFAULT_LIST')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('language.list', '".JText::_('JLIST_BACKEND_FILESEDIT_LANGUAGE_DEFAULT_LIST')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('file.types.view', 'html,htm,txt,pdf,doc,jpg,jpeg,png,gif')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('directories.autodetect', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('mail.cloaking', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('tempfile.delete.time', '20')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('frontend.upload.active', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('allowed.upload.file.types', 'zip,rar')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('allowed.upload.file.size', '2048')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('upload.access', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('files.per.side', '10')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('upload.form.text','".JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_FORM_TEXT_LAYOUT')."')");      
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('jd.header.title', 'Downloads')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('files.per.side.be', '15')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('last.log.message', '')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('last.restore.log', '')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('show.header.catlist', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('anti.leech', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('direct.download', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('days.is.file.new', '15')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('picname.is.file.new', 'blue.png')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('loads.is.file.hot', '100')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('picname.is.file.hot', 'red.png')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('download.pic.details', 'download_blue.png')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('upload.auto.publish', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('cats.order', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('autopublish.founded.files', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('all.files.autodetect', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('file.types.autodetect', 'zip,rar,exe,pdf,doc,gif,jpg,png')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('jcomments.active', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fileplugin.defaultlayout','".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NAME')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fileplugin.show_hot', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fileplugin.show_new', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fileplugin.enable_plugin', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fileplugin.show_jdfiledisabled', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fileplugin.layout_disabled','".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NAME')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fileplugin.show_downloadtitle', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fileplugin.offline_title','".JText::_('JLIST_FRONTEND_SETTINGS_FILEPLUGIN_OFFLINE_FILETITLE')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fileplugin.offline_descr','".JText::_('JLIST_FRONTEND_SETTINGS_FILEPLUGIN_DESCRIPTION')."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('cat.pic.default.filename','folder.png')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('file.pic.default.filename','zip.png')");
            $database->query();
            $sum_configs++;
        }
        // new param für versionsnummer von jd
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'jd.version'");
        $temp = $database->loadResult();
        if (!$temp) {
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('jd.version','$jd_version')");
            $database->query();
            $sum_configs++;
        } else {
            // set new value
            $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '$jd_version' WHERE setting_name = 'jd.version'");  
            $database->query();
        }    
        
        // new param für versions status von jd
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'jd.version.state'");
        $temp = $database->loadResult();
        if (!$temp) {
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('jd.version.state','$jd_version_state')");
            $database->query();
            $sum_configs++;
        } else {
            // set new value
            $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '$jd_version_state' WHERE setting_name = 'jd.version.state'");  
            $database->query();
        }    

        // new param für svn version von jd
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'jd.version.svn'");
        $temp = $database->loadResult();
        if (!$temp) {
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('jd.version.svn','$jd_version_svn')");
            $database->query();
            $sum_configs++;
        } else {
            // set new value
            $database->setQuery("UPDATE #__jdownloads_config SET setting_value = '$jd_version_svn' WHERE setting_name = 'jd.version.svn'");  
            $database->query();
        }    

        // for send mails after frontend uploads 
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'send.mailto.upload'");
        $temp = $database->loadResult();
        if (!$temp) {
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.upload', '".JText::_('JLIST_SETTINGS_INSTALL_5' )."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.option.upload', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.betreff.upload', '".JText::_('JLIST_SETTINGS_INSTALL_6' )."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.from.upload', '".JText::_('JLIST_SETTINGS_INSTALL_4' )."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.fromname.upload', 'JDownloads')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.html.upload', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.template.upload', '".JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_MAIL_UPLOAD_TEMPLATE' )."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.template.download', '".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_MAIL_DEFAULT' )."')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('download.pic.mirror_1', 'mirror_blue1.png')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('download.pic.mirror_2', 'mirror_blue2.png')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('picname.is.file.updated', 'green.png')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('days.is.file.updated', '15')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('thumbnail.size.width', '100')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('thumbnail.size.height', '100')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('thumbnail.view.placeholder', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('thumbnail.view.placeholder.in.lists', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('backend.manager.access', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('option.navigate.bottom', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('option.navigate.top', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('view.category.info', '0')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('save.monitoring.log', '1')");
            $database->query();
            $sum_configs++;
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('view.subheader', '1')");
            $database->query();
            $sum_configs++;
        }

        // new in 1.5
        
        // options for config: view detail information site - default on 
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'view.detailsite'");
        $temp = $database->loadResult();
        if (!$temp) {
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('view.detailsite', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('check.leeching', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('allowed.leeching.sites', '')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('block.referer.is.empty', '0')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.author', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.author.url', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.release', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.price', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.license', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.language', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.system', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.pic.upload', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.desc.long', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('mp3.player.config', 'loop=0;showvolume=1;showstop=1;bgcolor1=006699;bgcolor2=66CCFF')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('mp3.view.id3.info', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('use.php.script.for.download', '1')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('mp3.info.layout', '".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_ID3TAG')."')");
            $database->query();
            $sum_configs++;
        } 
        // added in v1.5.1
        // for pad file support
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'pad.exists'");
        $temp = $database->loadResult();
        if (!$temp) {
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('pad.exists', '0')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('pad.use', '0')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('pad.folder', 'padfiles')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('pad.language', 'English')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('google.adsense.active', '0')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('google.adsense.code', '')");
            $database->query();
            $sum_configs++;

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('countdown.active', '0')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('countdown.start.value', '60')");
            $database->query();
            $sum_configs++;
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('countdown.text', '".JText::_('JLIST_BACKEND_SETTINGS_WAITING_NOTE_TEXT')."')");
            $database->query();
            $sum_configs++;
            
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.extern.file', '0')");
            $database->query();
            $sum_configs++;

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.select.file', '1')");
            $database->query();
            $sum_configs++;            
            
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fe.upload.view.desc.short', '1')");
            $database->query();
            $sum_configs++;            
            
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fix.upload.filename.blanks', '1')");
            $database->query();
            $sum_configs++;            
            
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fix.upload.filename.uppercase', '1')");
            $database->query();
            $sum_configs++;              

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('fix.upload.filename.specials', '1')");
            $database->query();
            $sum_configs++;              
            
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('use.report.download.link', '1')");
            $database->query();
            $sum_configs++;            

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('send.mailto.report', '')");
            $database->query();
            $sum_configs++;            

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('download.pic.files', 'download2.png')");
            $database->query();
            $sum_configs++;            

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('view.sum.jcomments', '1')");
            $database->query();
            $sum_configs++;         

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('change.cyrillic.chars', '1')");
            $database->query();
            $sum_configs++;              

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('be.new.files.order.first', '1')");
            $database->query();
            $sum_configs++;
        }
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'downloads.footer.text'");
        $temp = $database->loadResult();
        if (!$temp) {   
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('downloads.footer.text', '')");
            $database->query();
            $sum_configs++;
                    
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('view.back.button', '1')");
            $database->query();
            $sum_configs++;

        }
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'create.auto.cat.dir'");
        $temp = $database->loadResult();
        if (!$temp) {   
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('create.auto.cat.dir', '1')");
            $database->query();
            $sum_configs++;
            
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('reset.counters', '0')");
            $database->query();
            $sum_configs++;
        }
        
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'report.link.only.regged'");
        $temp = $database->loadResult();
        if (!$temp) {   
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('report.link.only.regged', '1')");
            $database->query();
            $sum_configs++;                    
        }
        
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'view.ratings'");
        $temp = $database->loadResult();
        if (!$temp) {   
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('view.ratings', '1')");
            $database->query();
            $sum_configs++;                    
        }
        
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'rating.only.for.regged'");
        $temp = $database->loadResult();
        if (!$temp) {   
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('rating.only.for.regged', '0')");
            $database->query();
            $sum_configs++;                    
        } 
        // added in version 1.7.0
        // **********************
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'view.also.download.link.text'");
        $temp = $database->loadResult();
        if (!$temp) {   
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('view.also.download.link.text', '1')");
            $database->query();
            $sum_configs++;                    
        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('auto.file.short.description', '0')");
            $database->query();
            $sum_configs++;                    

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('auto.file.short.description.value', '200')");
            $database->query();
            $sum_configs++;
            
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('view.jom.comment', '0')");
            $database->query();
            $sum_configs++;
            
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('use.lightbox.function', '1')");
            $database->query();
            $sum_configs++;
                    
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('use.alphauserpoints', '0')");
            $database->query();
            $sum_configs++;
            
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('use.alphauserpoints.with.price.field', '0')");
            $database->query();
            $sum_configs++;

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('user.can.download.file.when.zero.points', '1')");
            $database->query();
            $sum_configs++;

            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('user.message.when.zero.points', '".JText::_('JLIST_BACKEND_SET_AUP_FE_MESSAGE_NO_DOWNLOAD')."')");
            $database->query();
            $sum_configs++;
                        
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('limited.download.number.per.day', '0')");
            $database->query();
            $sum_configs++;
            
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('limited.download.reached.message', '".JText::_('JLIST_FE_MESSAGE_AMOUNT_FILES_LIMIT')."')");
            $database->query();
            $sum_configs++;                                
        }            
        
        // added in version 1.7.4 for content plugin
        // **********************
        $database->setQuery("SELECT * FROM #__jdownloads_config WHERE setting_name = 'download.pic.plugin'");
        $temp = $database->loadResult();
        if (!$temp) {   
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('download.pic.plugin', 'download2.png')");
            $database->query();
            $sum_configs++;                    
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('plugin.auto.file.short.description', '0')");
            $database->query();
            $sum_configs++;                    
            $database->SetQuery("INSERT INTO #__jdownloads_config (setting_name, setting_value) VALUES ('plugin.auto.file.short.description.value', '200')");
            $database->query();
            $sum_configs++;                    
        }

        if ($sum_configs == 0) {
            echo "<font color='green'>--> ".JText::_('JLIST_INSTALL_1')."</font><br />";
        } else {
            echo "<font color='green'>--> ".$sum_configs." ".JText::_('JLIST_INSTALL_2')."</font><br />";
        }

//***************************** config data end **********************************************

//********************************************************************************************
// alter tables - insert here new fields from 1.5 for update from 1.4 
// *******************************************************************************************
		$sum_added_fields = 0;
        $prefix = $database->_table_prefix;
        
        $tables = array( $prefix.'jdownloads_templates' );
        $result = $database->getTableFields( $tables );
        if (!$result[$prefix.'jdownloads_templates']['template_header_text']){
            $database->SetQuery("ALTER TABLE #__jdownloads_templates ADD template_header_text LONGTEXT NOT NULL AFTER template_typ");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }
        if (!$result[$prefix.'jdownloads_templates']['template_subheader_text']){
            $database->SetQuery("ALTER TABLE #__jdownloads_templates ADD template_subheader_text LONGTEXT NOT NULL AFTER template_header_text");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }
        if (!$result[$prefix.'jdownloads_templates']['template_footer_text']){
            $database->SetQuery("ALTER TABLE #__jdownloads_templates ADD template_footer_text LONGTEXT NOT NULL AFTER template_subheader_text");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }
        if (!$result[$prefix.'jdownloads_templates']['cols']){
            $database->SetQuery("ALTER TABLE #__jdownloads_templates ADD cols TINYINT(1) NOT NULL DEFAULT 1 AFTER note");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }
        // false fieldname delete from prior svn if exist
        if ($result[$prefix.'jdownloads_templates']['columns']){
            $database->SetQuery("ALTER TABLE #__jdownloads_templates DROP columns");
        }        
        
       // new alias fields
        $tables = array( $prefix.'jdownloads_cats' );
        $result = $database->getTableFields( $tables );
        if (!$result[$prefix.'jdownloads_cats']['cat_alias']){
            $database->SetQuery("ALTER TABLE #__jdownloads_cats ADD cat_alias varchar(255) NOT NULL default '' AFTER cat_title");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }
        // new alias fields
        $tables = array( $prefix.'jdownloads_files' );
        $result = $database->getTableFields( $tables );
        if (!$result[$prefix.'jdownloads_files']['file_alias']){
            $database->SetQuery("ALTER TABLE #__jdownloads_files ADD file_alias varchar(255) NOT NULL default '' AFTER file_title");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }        
        // new field for created file user-id - submitted by id
        if (!$result[$prefix.'jdownloads_files']['submitted_by']){
            $database->SetQuery("ALTER TABLE #__jdownloads_files ADD submitted_by INT(11) NOT NULL default '0' AFTER modified_date");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }
        // new field for add AUP points when published
        if (!$result[$prefix.'jdownloads_files']['set_aup_points']){
            $database->SetQuery("ALTER TABLE #__jdownloads_files ADD set_aup_points TINYINT(1) NOT NULL default '0' AFTER submitted_by");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }  
        // new field for file date
        if (!$result[$prefix.'jdownloads_files']['file_date']){
            $database->SetQuery("ALTER TABLE #__jdownloads_files ADD file_date datetime NOT NULL default '0000-00-00 00:00:00' AFTER date_added");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }
        // new field for publish date
        if (!$result[$prefix.'jdownloads_files']['publish_from']){
            $database->SetQuery("ALTER TABLE #__jdownloads_files ADD publish_from datetime NOT NULL default '0000-00-00 00:00:00' AFTER file_date");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }       
        // new field for publish date
        if (!$result[$prefix.'jdownloads_files']['publish_to']){
            $database->SetQuery("ALTER TABLE #__jdownloads_files ADD publish_to datetime NOT NULL default '0000-00-00 00:00:00' AFTER publish_from");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }       
        // new field for use publish from and end date
        if (!$result[$prefix.'jdownloads_files']['use_timeframe']){
            $database->SetQuery("ALTER TABLE #__jdownloads_files ADD use_timeframe TINYINT(1) NOT NULL default '0' AFTER publish_to");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }
        // new thumbnail fields
        if (!$result[$prefix.'jdownloads_files']['thumbnail2']){
            $database->SetQuery("ALTER TABLE #__jdownloads_files ADD thumbnail2 varchar(255) NOT NULL default '' AFTER thumbnail");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }                

        if (!$result[$prefix.'jdownloads_files']['thumbnail3']){
            $database->SetQuery("ALTER TABLE #__jdownloads_files ADD thumbnail3 varchar(255) NOT NULL default '' AFTER thumbnail2");
            if ($database->query()) {
            $sum_added_fields++;
            }    
        }

       // change to short cat ordering field
        if ($update){
            $tables = array( $prefix.'jdownloads_cats' );
            $result = $database->getTableFields( $tables );
            $database->SetQuery("ALTER TABLE #__jdownloads_cats CHANGE ordering ordering int(11) NOT NULL default '0'");
        }
        
        
        if ($sum_added_fields == 0) {
            echo "<font color='green'>--> ".JText::_('JLIST_INSTALL_1_2')."</font><br />";
        } else {
            echo "<font color='green'>--> ".$sum_added_fields." ".JText::_('JLIST_INSTALL_2_2')."</font><br />";        
		}
  	
//***************************** alter table end **********************************************

//********************************************************************************************
// write default layouts in database
// *******************************************************************************************


        $sum_layouts =0;
        $active = 0;

        $database->setQuery("SELECT * FROM #__jdownloads_templates WHERE template_typ = '1' AND locked = '1' AND  template_name ='".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_DEFAULT_NAME')."'");
        $temp = $database->loadResult();
        if (!$temp) {
            // exist active layout?
            $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_templates WHERE template_typ = '1' AND template_active = '1'");
            $active = $database->loadResult();
            if (!$active) {
                $active = 1;
            } else {
                $active = 0;
            }    
            $cat_layout = stripslashes(JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_DEFAULT'));
            $database->setQuery("INSERT INTO #__jdownloads_templates (template_name, template_typ, template_text, template_active, locked)  VALUES ('".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_DEFAULT_NAME')."', 1, '".$cat_layout."', ".$active.", 1)");
            $database->query();
            $sum_layouts++;
        }    
        // files
        $database->setQuery("SELECT * FROM #__jdownloads_templates WHERE template_typ = '2' AND locked = '1'  AND  template_name ='".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NAME')."'");
        $temp = $database->loadResult();
        if (!$temp) {
            $file_layout = stripslashes(JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT'));
            $database->setQuery("INSERT INTO #__jdownloads_templates (template_name, template_typ, template_text, template_active, locked)  VALUES ('".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NAME')."', 2, '".$file_layout."', 0, 1)");
            $database->query();
            $sum_layouts++;
        }
           
        // summary
        $database->setQuery("SELECT * FROM #__jdownloads_templates WHERE template_typ = '3' AND locked = '1'  AND  template_name ='".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NAME')."'");
        $temp = $database->loadResult();
        if (!$temp) {
            // exist active layout?
            $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_templates WHERE template_typ = '3' AND template_active = '1'");
            $active = $database->loadResult();
            if (!$active) {
                $active = 1;
            } else {
                $active = 0;
            }
            $summary_layout = stripslashes(JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_SUMMARY_DEFAULT'));
            $database->setQuery("INSERT INTO #__jdownloads_templates (template_name, template_typ, template_text, template_active, locked)  VALUES ('".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NAME')."', 3, '".$summary_layout."', ".$active.", 1)");
            $database->query();
            $sum_layouts++;
        }
        
        // layout for download details 
        $database->setQuery("SELECT * FROM #__jdownloads_templates WHERE template_typ = '5' AND locked = '1' AND  template_name ='".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_DETAILS_DEFAULT_NAME')."'");
        $temp = $database->loadResult();
        if (!$temp) {
            // exist active layout?
            $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_templates WHERE template_typ = '5' AND template_active = '1'");
            $active = $database->loadResult();
            if (!$active) {
                $active = 1;
            } else {
                $active = 0;
            }
            $detail_layout = stripslashes(JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_DETAILS_DEFAULT'));
            $database->setQuery("INSERT INTO #__jdownloads_templates (template_name, template_typ, template_text, template_active, locked)  VALUES ('".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_DETAILS_DEFAULT_NAME')."', 5, '$detail_layout', ".$active.", 1)");
            $database->query();
            $sum_layouts++;
        }        
        
        // Simple layout with Checkboxes for files
        $database->setQuery("SELECT * FROM #__jdownloads_templates WHERE template_typ = '2' AND locked = '1' AND template_name ='".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NEW_SIMPLE_1_NAME')." 1.4'");
        $temp = $database->loadResult();
        if (!$temp) {
            // exist active layout?
            $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_templates WHERE template_typ = '2' AND template_active = '1'");
            $active = $database->loadResult();
            if (!$active) {
                $active = 1;
            } else {
                $active = 0;
            }
            $file_layout = stripslashes(JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NEW_SIMPLE_1')); 
            $database->setQuery("INSERT INTO #__jdownloads_templates (template_name, template_typ, template_text, template_active, locked, note, checkbox_off, symbol_off)  VALUES ('".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NEW_SIMPLE_1_NAME')." 1.4', 2, '".$file_layout."', ".$active.", 1, '', 0, 1)");
            $database->query();
            $sum_layouts++;
        } 
        
        // Simple layout without Checkboxes for files
        $database->setQuery("SELECT * FROM #__jdownloads_templates WHERE template_typ = '2' AND locked = '1' AND template_name ='".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NEW_SIMPLE_2_NAME')." 1.4'");
        $temp = $database->loadResult();
        if (!$temp) {
            $file_layout = stripslashes(JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NEW_SIMPLE_2')); 
            $database->setQuery("INSERT INTO #__jdownloads_templates (template_name, template_typ, template_text, template_active, locked, note, checkbox_off, symbol_off)  VALUES ('".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DEFAULT_NEW_SIMPLE_2_NAME')." 1.4', 2, '".$file_layout."', 0, 1, '', 1, 1)");
            $database->query();
            $sum_layouts++;
        }
        
        // new  categories layout with 4 columns
        $database->setQuery("SELECT * FROM #__jdownloads_templates WHERE template_typ = '1' AND locked = '1' AND template_name ='".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_COL_TITLE')."'");
        $temp = $database->loadResult();
        if (!$temp) {
            $file_layout = stripslashes(JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_COL_DEFAULT')); 
            $database->setQuery("INSERT INTO #__jdownloads_templates (template_name, template_typ, template_text, template_active, locked, note, cols)  VALUES ('".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_COL_TITLE')."', 1, '".$file_layout."', 0, 1, '".JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_COL_NOTE')."', 4)");
            $database->query();
            $sum_layouts++;
        }
        
        if ($sum_layouts == 0) {
                echo "<font color='green'>--> ".JText::_('JLIST_INSTALL_3')."</font><br />";
           } else {
                echo "<font color='green'>--> ".$sum_layouts." ".JText::_('JLIST_INSTALL_4')."</font><br />";
        }
        
        
//*************************************** end layouts ****************************************

//********************************************************************************************
// Write default licenses in database
//
// Get it from language file and checked if exists in DB
//
//********************************************************************************************

        $lic_total = (int)JText::_('JLIST_SETTINGS_LICENSE_TOTAL');
        $sum_licenses = 0;

   		$database->setQuery("SELECT * FROM #__jdownloads_license WHERE license_title = '".JText::_('JLIST_SETTINGS_LICENSE1_TITLE')."'");
        $temp = $database->loadResult();
        if (!$temp) {
            $database->setQuery("INSERT INTO #__jdownloads_license (license_title, license_text, license_url)  VALUES ('".JText::_('JLIST_SETTINGS_LICENSE1_TITLE')."', '".JText::_('JLIST_SETTINGS_LICENSE1_TEXT')."', '".JText::_('JLIST_SETTINGS_LICENSE1_URL')."')");
            $database->query();
            $sum_licenses++;
        }

       $database->setQuery("SELECT * FROM #__jdownloads_license WHERE license_title = '".JText::_('JLIST_SETTINGS_LICENSE2_TITLE')."'");
       $temp = $database->loadResult();
       if (!$temp) {
            $database->setQuery("INSERT INTO #__jdownloads_license (license_title, license_text, license_url)  VALUES ('".JText::_('JLIST_SETTINGS_LICENSE2_TITLE')."', '".JText::_('JLIST_SETTINGS_LICENSE2_TEXT')."', '".JText::_('JLIST_SETTINGS_LICENSE2_URL')."')");
            $database->query();
            $sum_licenses++;
        }
   		
       $database->setQuery("SELECT * FROM #__jdownloads_license WHERE license_title = '".JText::_('JLIST_SETTINGS_LICENSE3_TITLE')."'");
       $temp = $database->loadResult();
       if (!$temp) {
            $database->setQuery("INSERT INTO #__jdownloads_license (license_title, license_text, license_url)  VALUES ('".JText::_('JLIST_SETTINGS_LICENSE3_TITLE')."', '".JText::_('JLIST_SETTINGS_LICENSE3_TEXT')."', '".JText::_('JLIST_SETTINGS_LICENSE3_URL')."')");
            $database->query();
            $sum_licenses++;
        }

       $database->setQuery("SELECT * FROM #__jdownloads_license WHERE license_title = '".JText::_('JLIST_SETTINGS_LICENSE4_TITLE')."'");
       $temp = $database->loadResult();
       if (!$temp) {
            $database->setQuery("INSERT INTO #__jdownloads_license (license_title, license_text, license_url)  VALUES ('".JText::_('JLIST_SETTINGS_LICENSE4_TITLE')."', '".JText::_('JLIST_SETTINGS_LICENSE4_TEXT')."', '".JText::_('JLIST_SETTINGS_LICENSE4_URL')."')");
            $database->query();
            $sum_licenses++;
        }

       $database->setQuery("SELECT * FROM #__jdownloads_license WHERE license_title = '".JText::_('JLIST_SETTINGS_LICENSE5_TITLE')."'");
       $temp = $database->loadResult();
       if (!$temp) {
            $database->setQuery("INSERT INTO #__jdownloads_license (license_title, license_text, license_url)  VALUES ('".JText::_('JLIST_SETTINGS_LICENSE5_TITLE')."', '".JText::_('JLIST_SETTINGS_LICENSE5_TEXT')."', '".JText::_('JLIST_SETTINGS_LICENSE5_URL')."')");
            $database->query();
            $sum_licenses++;
        }

       $database->setQuery("SELECT * FROM #__jdownloads_license WHERE license_title = '".JText::_('JLIST_SETTINGS_LICENSE6_TITLE')."'");
       $temp = $database->loadResult();
       if (!$temp) {
            $database->setQuery("INSERT INTO #__jdownloads_license (license_title, license_text, license_url)  VALUES ('".JText::_('JLIST_SETTINGS_LICENSE6_TITLE')."', '".JText::_('JLIST_SETTINGS_LICENSE6_TEXT')."', '".JText::_('JLIST_SETTINGS_LICENSE6_URL')."')");
            $database->query();
            $sum_licenses++;
        }

       $database->setQuery("SELECT * FROM #__jdownloads_license WHERE license_title = '".JText::_('JLIST_SETTINGS_LICENSE7_TITLE')."'");
       $temp = $database->loadResult();
       if (!$temp) {
            $database->setQuery("INSERT INTO #__jdownloads_license (license_title, license_text, license_url)  VALUES ('".JText::_('JLIST_SETTINGS_LICENSE7_TITLE')."', '".JText::_('JLIST_SETTINGS_LICENSE7_TEXT')."', '".JText::_('JLIST_SETTINGS_LICENSE7_URL')."')");
            $database->query();
            $sum_licenses++;
        }

       if ($sum_licenses == 0) {
          echo "<font color='green'>--> ".JText::_('JLIST_INSTALL_5')."</font><br />";
       } else {
          echo "<font color='green'>--> ".$sum_licenses." ".JText::_('JLIST_INSTALL_6')."</font><br />";
       }

//***************************** end licenses *************************************************

//********************************************************************************************
// Checked if exist joomfish 
// if yes, move the files
//********************************************************************************************
    if (@is_dir(JPATH_SITE.'/administrator/components/com_joomfish/contentelements')){
        $fishresult = 1;
        @rename( JPATH_SITE."/administrator/components/com_jdownloads/joomfish/jdownloads_cats.xml", JPATH_SITE."/administrator/components/com_joomfish/contentelements/jdownloads_cats.xml");
        @rename( JPATH_SITE."/administrator/components/com_jdownloads/joomfish/jdownloads_config.xml", JPATH_SITE."/administrator/components/com_joomfish/contentelements/jdownloads_config.xml");
        @rename( JPATH_SITE."/administrator/components/com_jdownloads/joomfish/jdownloads_files.xml", JPATH_SITE."/administrator/components/com_joomfish/contentelements/jdownloads_files.xml");
        @rename( JPATH_SITE."/administrator/components/com_jdownloads/joomfish/jdownloads_layouts.xml", JPATH_SITE."/administrator/components/com_joomfish/contentelements/jdownloads_layouts.xml");
        @rmdir ( JPATH_SITE."/administrator/components/com_jdownloads/joomfish"); 
    } else { 
        $fishresult = 0;
    }  
    	
	if ($fishresult) {
		echo "<font color='green'>--> ".JText::_('JLIST_INSTALL_17')." ".JPATH_SITE.'/administrator/components/com_joomfish/contentelements'.'</font><br />';
	} else {
        echo "<font color='green'>--> ".JText::_('JLIST_INSTALL_18')." ".JPATH_SITE.'/administrator/components/com_jdownloads/joomfish'.'<br />'.JText::_('JLIST_INSTALL_19').'</font><br />';
    }    	

//***************************** end joomfish *************************************************

//********************************************************************************************
// Checked default directories 
//********************************************************************************************

        // downloads
        $dir_exist = is_dir(JPATH_SITE."/jdownloads");

        if($dir_exist) {
           if (is_writable(JPATH_SITE."/jdownloads")) {
               echo "<font color='green'>--> ".JText::_('JLIST_INSTALL_7')."</font><br />";
           } else {
               echo "<font color='red'><strong>--> ".JText::_('JLIST_INSTALL_8')."</strong></font><br />";
           }
        } else {
            if ($makedir = @mkdir(JPATH_SITE."/jdownloads/", 0755)) {
			   echo "<font color='green'>--> ".JText::_('JLIST_INSTALL_9')."<br />";
		       } else {
		 	        echo "<font color='red'><strong>--> ".JText::_('JLIST_INSTALL_10')."</strong></font><br />";
		       }
        }

        // tempzipfiles
        $dir_existzip = is_dir(JPATH_SITE."/jdownloads/tempzipfiles");

        if($dir_existzip) {
           if (is_writable(JPATH_SITE."/jdownloads/tempzipfiles")) {
              echo "<font color='green'>--> ".JText::_('JLIST_INSTALL_11')."</font><br />";
           } else {
               echo "<font color='red'><strong>--> ".JText::_('JLIST_INSTALL_12')."</strong></font><br />";
           }
        } else {
            if ($makedir = @mkdir(JPATH_SITE."/jdownloads/tempzipfiles/", 0755)) {
    			echo "<font color='green'>--> ".JText::_('JLIST_INSTALL_13')."<br />";
		    } else {
		 	echo "<font color='red'><strong>--> ".JText::_('JLIST_INSTALL_14')."</strong></font><br />";
		    }
		 }

        // beispieldaten speichern - wenn neuinstallation
        if ($root_dir == ''){
            $dir_exist = is_dir(JPATH_SITE."/jdownloads");
            if($dir_exist) {
                if (is_writable(JPATH_SITE."/jdownloads")) {      
                     if (!is_dir(JPATH_SITE."/jdownloads/".JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT'))){
                        // daten speichern
                        // dirs für cats
                        $makdir = @mkdir(JPATH_SITE."/jdownloads/".JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT'), 0755);
                        $makdir = @mkdir(JPATH_SITE."/jdownloads/".JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT').'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_SUB'), 0755);
                        // cat erstellen in db
                        if ($makdir) {
                            $database->setQuery("INSERT INTO #__jdownloads_cats (cat_title, cat_description, cat_dir, parent_id, cat_pic, published)  VALUES ('".JText::_('JLIST_SAMPLE_DATA_CAT_NAME_ROOT')."', '".JText::_('JLIST_SAMPLE_DATA_CAT_NAME_TEXT')."', '".JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT')."', 0, 'joomla.png', 1)");
                            $database->query();
                            $database->setQuery("INSERT INTO #__jdownloads_cats (cat_title, cat_description, cat_dir, parent_id, cat_pic, published)  VALUES ('".JText::_('JLIST_SAMPLE_DATA_CAT_NAME_SUB')."', '".JText::_('JLIST_SAMPLE_DATA_CAT_NAME_TEXT')."', '".JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT').'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_SUB')."', 1, 'joomla.png', 1)");
                            $database->query();
                            // file kopieren nach catdir
                            $source_path = JPATH_SITE."/administrator/components/com_jdownloads/mod_jdownloads_top_1.5.zip";
                            $dest_path = JPATH_SITE.'/jdownloads/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT').'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_SUB').'/mod_jdownloads_top_1.5.zip'; 
                            @copy($source_path, $dest_path);
                            // downloads erstellen
                            $database->setQuery("INSERT INTO #__jdownloads_files (`file_id`, `file_title`, `description`, `description_long`, `file_pic`, `price`, `release`, `language`, `system`, `license`, `url_license`, `size`, `date_added`, `url_download`, `url_home`, `author`, `url_author`, `created_by`, `created_mail`, `modified_by`, `modified_date`, `downloads`, `cat_id`, `ordering`, `published`, `checked_out`, `checked_out_time`) VALUES (NULL, '".JText::_('JLIST_SAMPLE_DATA_FILE_NAME')."', '".JText::_('JLIST_SAMPLE_DATA_FILE_NAME_TEXT')."', '".JText::_('JLIST_SAMPLE_DATA_FILE_NAME_TEXT')."', 'joomla.png', '', '1.0', '2', '1', '1', '', '1.92 KB', '".date('Y-m-d H:i:s')."', 'mod_jdownloads_top_1.5.zip', 'www.jDownloads.com', 'Arno Betz', 'info@jDownloads.com', 'Installer', '', '', '0000-00-00 00:00:00', '0', '2', '0', '1', '0', '0000-00-00 00:00:00')");
                            $database->query();
                            checkAlias();
                            echo "<font color='green'>--> ".JText::_('JLIST_SAMPLE_DATA_CREATE_OK')."<br />";
                        }
                     } else {
                        // daten existieren schon
                        echo "<font color='green'> ".JText::_('JLIST_SAMPLE_DATA_EXISTS')."</font><br />";
                     } 
                } else {
                    // fehlermeldung: daten konnten nicht gespeichert werden
                    echo "<font color='red'><strong>--> ".JText::_('JLIST_SAMPLE_DATA_CREATE_ERROR')."</strong></font><br />";
                }    
            } else {
                // fehlermeldung: daten konnten nicht gespeichert werden
                echo "<font color='red'><strong>--> ".JText::_('JLIST_SAMPLE_DATA_CREATE_ERROR')."</strong></font><br />";
            }    
        }    
        checkAlias();
        
        echo "<font color='#FF6600'><strong>--> ".JText::_('JLIST_INSTALL_DB_TIP')."</strong></font><br />";
        ?>

		<br />
   		<font color="green"><b><?php echo JText::_('JLIST_INSTALL_15'); ?></b></font><br />
  		</code>
        </td>
        </tr>
        </table>
        <a href="index2.php?option=com_jdownloads"><big><strong><?php echo JText::_('JLIST_INSTALL_16'); ?></strong></big></a><br /><br />
        </center>
        <?php
    }
  }  
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

// Kopiert alle dirs inkl. subdirs und files nach $dest
// und löscht abscchliessend das $source dir
function moveDirs($source, $dest, $recursive = true, $message) {
    $error = false;
    if (!is_dir($dest)) { 
        mkdir($dest); 
      } 
    $handle = @opendir($source);
    if(!$handle) {
        $message = JText::_('JLIST_INSTALL_MOVE_IMAGES_COPY_ERROR');
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
    // $source löschen wenn KEIN error
    if (!$error) {
        $res = delete_dir_and_allfiles ($source);    
        if ($res) {
            $message = JText::_('JLIST_INSTALL_MOVE_IMAGES_DEL_AFTER_COPY_ERROR');        
        }
    } else {
        $message = JText::_('JLIST_INSTALL_MOVE_IMAGES_COPY_ERROR');
    }
    return $message;
} 

// delete_dir_and_allfiles - rekursiv löschen
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

?>