<?php
/**
* @version 1.6
* @package JDownloads
* @copyright (C) 2009 www.jdownloads.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* functions to check db after restore backup file!
*
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
  

function checkAfterRestore() {
    global $jlistConfig;
    $database = &JFactory::getDBO();
    
  //*********************************************
  // JD VERSION:
     $jd_version = '1.7.5';
     $jd_version_state = 'Stable';
     $jd_version_svn = '776'; 
  //*********************************************
    
    $output = '';
    
//********************************************************************************************
// insert default config data - if not exist
// *******************************************************************************************
      $root_dir = '';
      $sum_configs = 0;
      
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
            $output .= '<font color="green"><strong> '.JText::_('JLIST_INSTALL_1').'</strong></font><br />';
        } else {
            $output .= '<font color="green"> '.$sum_configs.' '.JText::_('JLIST_INSTALL_2').'</font><br />';
        }

        //***************************** config data end **********************************************

        $sum_added_fields = 0;
        $prefix = $database->_table_prefix;
        $tables = array( $prefix.'jdownloads_templates' );
        $result = $database->getTableFields( $tables );
        
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
        
        $tables = array( $prefix.'jdownloads_cats' );
        $result = $database->getTableFields( $tables );
        $database->SetQuery("ALTER TABLE #__jdownloads_cats CHANGE ordering ordering int(11) NOT NULL default '0'");
                   

        if ($sum_added_fields == 0) {
            $output .= "<font color='green'><strong> ".JText::_('JLIST_INSTALL_1_2')."</strong></font><br />";
        } else {
            $output .= "<font color='green'> ".$sum_added_fields." ".JText::_('JLIST_INSTALL_2_2')."</font><br />";        
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
        
        checkAlias();
   
   return $output;
}      
?>