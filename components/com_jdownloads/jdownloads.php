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

defined( '_JEXEC' ) or die( 'Restricted access-php' );

Error_Reporting(E_ERROR);
clearstatcache();
session_start();

jimport( 'joomla.application.component.view');
$database = &JFactory::getDBO();
$jconfig = new JConfig();
   
    
	global $Itemid, $mainframe;   
    global $id, $limit, $limitstart, $site_aktuell, $catid, $cid, $task, $view, $pop, $jlistConfig, $jlistTemplates, $page_title;  
    
    $document =& JFactory::getDocument();
    $params    = &$mainframe->getParams();

	define( 'ELPATH', dirname(__FILE__) );
    
	$GLOBALS['_VERSION']	= new JVersion();
	$version				= $GLOBALS['_VERSION']->getShortVersion();
		
    $params2   = JComponentHelper::getParams('com_languages');
    $frontend_lang = $params2->get('site', 'en-GB');
    $language = JLanguage::getInstance($frontend_lang);
    
    require_once( $mainframe->getPath( 'class' ) );
	require_once( $mainframe->getPath( 'front_html' ) );
	require_once(ELPATH.'/../../includes/pageNavigation.php');

	$id = (int)JArrayHelper::getValue( $_REQUEST, 'cid', array(0));
	if (!is_array( $id)) {
         $id = array(0);
    }
    
    $GLOBALS['jlistConfig'] = buildjlistConfig();
    $GLOBALS['jlistTemplates'] = getTemplates();

    // Page Title
    $menus    = &JSite::getMenu();
    $menu    = $menus->getActive();

    // because the application sets a default page title, we need to get it
    // right from the menu item itself
    if (is_object( $menu )) {
        $menu_params = new JParameter( $menu->params );
        $x = $menu_params->get( 'page_title');
        if (!$menu_params->get( 'page_title')) {
            $params->set('page_title', $jlistConfig['jd.header.title']);
        }
    } else {
        $params->set('page_title', $jlistConfig['jd.header.title']);
    }
    
    if ($params->get('page_title')){
        $document->setTitle( $params->get( 'page_title' ) );
    } else {
        if (function_exists($menu_params->get( 'page_title'))){
          if ($menu_params->get( 'page_title')){ 
            $document->setTitle( $menu_params->get( 'page_title'));
          }
        }      
    }    
    $page_title =  $document->getTitle( 'title');
    

    $pop 			= intval( JArrayHelper::getValue( $_REQUEST, 'pop', 0 ) );
	$task 			= JArrayHelper::getValue( $_REQUEST, 'task' );
    $view             = JArrayHelper::getValue( $_REQUEST, 'view' );
	$cid 			= (int)JArrayHelper::getValue($_REQUEST, 'cid', array());
    $catid          = (int)JArrayHelper::getValue($_REQUEST, 'catid', 0);
    
	$limit        =  intval($jlistConfig['files.per.side']);
	$limitstart   = intval( JArrayHelper::getValue( $_REQUEST, 'start', 0 ) );
    $site_aktuell = intval( JArrayHelper::getValue( $_REQUEST, 'site', 1 ) );
    
    $menus    = &JSite::getMenu();
    $menu    = $menus->getActive();
    
    // AUP integration
    if ($jlistConfig['use.alphauserpoints']){
        $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
        if (file_exists($api_AUP)){
            require_once ($api_AUP);
        }
    }            
    
    if ($task && !$view) $view = $task;
        
switch ($view) {

	   case 'upload':
		    viewUpload($option,$view);
	        break;
	   
	   case 'summary':
		    Summary($option);
		    break;

	   case 'finish':
		    finish($option);
		    break;

       case 'viewcategory':
    		showOneCategory($option,$cid);
	       	break;

       case 'view.download':
            showDownload($option,$cid);               
            break;

       case 'download':
            download($option,$cid);               
            break;
                        
       case 'search':
            showSearchForm($option,$cid);
            break;            
            
       case 'search.result':
            showSearchResult($option,$cid);
            break;
            
       case 'report':
            reportDownload($option,$cid);
            break;
            
       case 'viewcategories':
            showCats($option,$cid);
            break;                             
           
	   default: showCats($option,$cid);
            break;
}

// show summary
function Summary($option){
	global $jlistConfig, $Itemid, $mainframe;
    
    $app = &JFactory::getApplication();
    $user = &JFactory::getUser();
    $database = &JFactory::getDBO();
    // AUP support
    $sum_aup_points = 0;
    $extern_site = false;
    $open_in_blank_page = false;
    $marked_files_id = array();
    $has_licenses = false;
    // file-id der ausgewählten dateien holen - falls verwendet
    $marked_files_id = JArrayHelper::getValue( $_POST, 'cb_arr', array(0));
    for($i=0,$n=count($marked_files_id);$i<$n;$i++){
        $marked_files_id[$i] = intval($marked_files_id[$i]);
    }
    // get file id
    $fileid = intval(JArrayHelper::getValue( $_REQUEST, 'cid', 0 ));
    // get cat id
    $catid = intval(JArrayHelper::getValue( $_REQUEST, 'catid', 0 ));
    
    // check access for manual url manipulation - fix
    $database->setQuery('SELECT cat_access FROM #__jdownloads_cats WHERE published = 1 AND cat_id = '.$catid);
    $cat_access = $database->loadResult();
    $access[0] = (int)substr($cat_access, 0, 1);
    $access[1] = (int)substr($cat_access, 1, 1); 
    if ($user->get('aid') < $access[1] || !$cat_access){
       // jump to the mainsite
       $app->redirect(JURI::base(true));
    }  
    
    $breadcrumbs =& $mainframe->getPathWay();
    $database->setQuery('SELECT cat_title FROM #__jdownloads_cats WHERE cat_id = '.$catid);
    $cat_title = $database->loadResult();
    if ($catid){
        $breadcrumbs = createPathway($catid, $breadcrumbs, $option);
        $breadcrumbs->addItem($cat_title, JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=viewcategory&amp;catid='.$catid));     
    }
    if ($fileid){
        $database->setQuery('SELECT * FROM #__jdownloads_files WHERE published = 1 AND file_id = '.$fileid);
        if (!$file = $database->loadObject()){
           // jump to the mainsite
           $app->redirect(JURI::base(true));
        }    
        $breadcrumbs->addItem($file->file_title, JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=view.download&amp;catid='.$catid.'&amp;cid='.$fileid));
        
    }
    $breadcrumbs->addItem(JText::_('JLIST_FRONTEND_HEADER_SUMMARY_TITLE'), '');    
    
    // is mirror file ?
    $is_mirror =  intval(JArrayHelper::getValue( $_REQUEST, 'm', 0 ));
 
    // ist vorhanden dann download direkt nicht über checkbox
    if ($fileid){
        $direktlink = true;
        $id_text = $fileid;        
        $filename = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=finish&amp;cid='.$fileid.'&amp;catid='.$catid.'&amp;m='.$is_mirror);
        $download_link = $filename;       
    }    
        
    // in text übertragen für anzeige der gewählten files
    $anz = 0;
    if (!$id_text){
        $anz = count($marked_files_id);
        if ( $anz > 1 ){
           $id_text = implode(',', $marked_files_id);
        } else {
           $id_text = $marked_files_id[0];
        }
    }

    // get filetitle and release for mail and summary
    $mail_files_arr = array();
    $mail_files = "<div><ul>";
    $database->setQuery("SELECT * FROM #__jdownloads_files WHERE published = 1 AND file_id IN ($id_text) ");
    if (!$mail_files_arr = $database->loadObjectList()){
        // jump to the mainsite
        $app->redirect(JURI::base(true));
    }    
    
    if ($jlistConfig['use.alphauserpoints']){
        // get standard points value from AUP
        $database->setQuery("SELECT points FROM #__alpha_userpoints_rules WHERE published = 1 AND plugin_function = 'plgaup_jdownloads_user_download'");
        $aup_default_points = (int)$database->loadResult(); 
    }    
     
    for ($i=0; $i<count($mail_files_arr); $i++) {

       // build sum of aup points
       if ($jlistConfig['use.alphauserpoints']){
          if ($jlistConfig['use.alphauserpoints.with.price.field']){
              $sum_aup_points = $sum_aup_points + (int)$mail_files_arr[$i]->price;
          } else {
              $sum_aup_points += $aup_default_points;
          }    
       }

       // get license name
       if ($mail_files_arr[$i]->license > 0){  
           $lic = $mail_files_arr[$i]->license;
           $has_licenses = true;
           $database->setQuery("SELECT license_title FROM #__jdownloads_license WHERE id = '$lic'");
           $lic_title = $database->loadResult(); 
           $mail_files .= "<div><li><b>".$mail_files_arr[$i]->file_title.' '.$mail_files_arr[$i]->release.'&nbsp;&nbsp;&nbsp;</b>'.JText::_('JLIST_FE_DETAILS_LICENSE_TITLE').': <b>'.$lic_title.'&nbsp;&nbsp;&nbsp;</b>'.JText::_('JLIST_FE_DETAILS_FILESIZE_TITLE').': <b>'.$mail_files_arr[$i]->size.'</b></li></div>';
       } else {
            $mail_files .= "<div><li><b>".$mail_files_arr[$i]->file_title.' '.$mail_files_arr[$i]->release.'&nbsp;&nbsp;&nbsp;</b>'.JText::_('JLIST_FE_DETAILS_FILESIZE_TITLE').': <b>'.$mail_files_arr[$i]->size.'</b></li></div>';
       }     
    }
    $mail_files .= "</ul></div>";
    
    // set flag when link must opened in a new browser window 
    if (!$is_mirror && $i == 1 && $mail_files_arr[0]->extern_site){
        $extern_site = true;    
    }
    if ($is_mirror == 1 && $i == 1 && $mail_files_arr[0]->extern_site_mirror_1){
        $extern_site = true;    
    }
    if ($is_mirror == 2 && $i == 1 && $mail_files_arr[0]->extern_site_mirror_2){
        $extern_site = true;    
    }
    // get file extension  when only one file selected - set flag when link must opened in a new browser window 
    if (count($marked_files_id) == 1 && $mail_files_arr[0]->url_download) {
        $view_types = array();
        $view_types = explode(',', $jlistConfig['file.types.view']);
        $fileextension = strtolower(substr(strrchr($mail_files_arr[0]->url_download,"."),1));
        if (in_array($fileextension, $view_types)){
            $open_in_blank_page = true;
        }
    }
        
    // wenn kein direktlink - checkox variante   
    if (!$direktlink){ 
        // wenn mehr als eine datei - markierte files als zip packen
        $download_verz = JURI::base().$jlistConfig['files.uploaddir'].'/';
        $zip_verz = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/';
        if (count($marked_files_id) > 1) {
            // zufallszahl für zip-dateinamen
            if (empty($user_random_id)){
                $user_random_id = buildRandomID();
            }
            $zip=new ss_zip();
            for ($i=0; $i<count($marked_files_id); $i++) {
                // fileurl holen
                $database->setQuery("SELECT url_download FROM #__jdownloads_files WHERE file_id = '".(int)$marked_files_id[$i]."'");
                $filename = utf8_decode($database->loadResult());
                $database->setQuery("SELECT cat_id FROM #__jdownloads_files WHERE file_id = '".(int)$marked_files_id[$i]."'");
                $cat_id = $database->loadResult();
                $database->setQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '$cat_id'");
                $cat_dir = $database->loadResult();
                $cat_dir = $cat_dir.'/'; 
                $zip->add_file($zip_verz.$cat_dir.$filename, $filename);
            }
            $zip->archive(); // return the ZIP
            $zip->save($zip_verz."tempzipfiles/".$jlistConfig['zipfile.prefix'].$user_random_id.".zip");
            $zip_size = fsize($zip_verz."tempzipfiles/".$jlistConfig['zipfile.prefix'].$user_random_id.".zip");
            $mail_files .= "<div><br />".JText::_('JLIST_FRONTEND_SUMMARY_ZIP_FILESIZE').': <b>'.$zip_size.'</b></div>';
            
            // alte zips löschen
            $del_ok = deleteOldFile($zip_verz."tempzipfiles/");
            $filename = $download_verz."tempzipfiles/".$jlistConfig['zipfile.prefix'].$user_random_id.".zip";
            $download_link = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=finish&catid='.$cat_id.'&list='.$id_text.'&amp;user='.$user_random_id); 
        } else {
            // nur 1 datei
            $database->setQuery("SELECT cat_id FROM #__jdownloads_files WHERE file_id = '".(int)$marked_files_id[0]."'");
            $cat_id = $database->loadResult();
            $filename = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=finish&cid='.(int)$marked_files_id[0].'&catid='.$cat_id);
            $download_link = $filename;
        }
    }
    $sum_aup_points = ABS($sum_aup_points);            
    jlist_HTML::Summary($option, $marked_files_id, $mail_files, $filename, $download_link, $del_ok, $extern_site, $sum_aup_points, $has_licenses, $open_in_blank_page);
}

// finish and start the download
function finish($option){
	global $mainframe, $jlistConfig, $mail_files, $Itemid;
   
   $app = &JFactory::getApplication();
   $user = &JFactory::getUser();
   $database = &JFactory::getDBO();
   $extern = false;
   $extern_site = false;
   $can_download = false;
   $price = '';
   
   // anti lecching
   $url = JURI::base( false );
   list($remove,$stuff2)=split('//',$url,2);
   list($domain,$stuff2)=split('/',$stuff2,2); 
   
   $refr=getenv("HTTP_REFERER");
   list($remove,$stuff)=split('//',$refr,2);
   list($home,$stuff)=split('/',$stuff,2); 
   
   // check leeching
   $blocking = false; 
   if ($home != $domain) {
       $allowed_urls = explode(',' , $jlistConfig['allowed.leeching.sites']);
       if ($jlistConfig['check.leeching']) {
           if ($jlistConfig['block.referer.is.empty']) {
               if (!$refr) {
                   $blocking = true;
               }
           } else {
               if  (!$refr){
                   $blocking = false;
               }    
           }    
           
           if (in_array($home,$allowed_urls)) {
              $blocking = false;
           } else {
             $blocking = true;        
           }  
       } 
   }
   
  
    if ($blocking) {
        // leeching message
        echo '<div align ="center"><br /><b><font color="red">'.JText::_('JLIST_FRONTEND_ANTILEECH_MESSAGE').'</font></b><br /></div>';
        echo '<div align ="center"><br />'.JText::_('JLIST_FRONTEND_ANTILEECH_MESSAGE2').'<br /></div>';
    
    } else {
    
    // get file id
    $fileid = intval(JArrayHelper::getValue( $_REQUEST, 'cid', 0 ));
    // get cat id
    $catid = intval(JArrayHelper::getValue( $_REQUEST, 'catid', 0 ));
    
    $is_mirror = intval(JArrayHelper::getValue( $_REQUEST, 'm', 0 ));
    
    // check access for manual url manipulation - fix
    $database->setQuery('SELECT cat_access FROM #__jdownloads_cats WHERE published = 1 AND cat_id = '.$catid);
    $cat_access = $database->loadResult();
    
    $access[0] = (int)substr($cat_access, 0, 1);
    $access[1] = (int)substr($cat_access, 1, 1); 
    if ($user->aid < $access[1] || !$cat_access){
       // jump to the mainsite
        $app->redirect(JURI::base(true));     
    }
    
    // get AUP user points
    $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
    if (file_exists($api_AUP)){
        require_once ($api_AUP);
        $profil = AlphaUserPointsHelper:: getUserInfo('', $user->id);
    }
    if ($jlistConfig['use.alphauserpoints']){
        // get standard points value from AUP
        $database->setQuery("SELECT points FROM #__alpha_userpoints_rules WHERE published = 1 AND plugin_function = 'plgaup_jdownloads_user_download'");
        $aup_fix_points = (int)$database->loadResult();
        $aup_fix_points = abs($aup_fix_points);
    }    
    
    // files liste holen wenn mhr als ein download markiert
    $files = $database->getEscaped (JArrayHelper::getValue( $_REQUEST, 'list', '' ));
    $files_arr = explode(',', $files);
    if ($files){
        // sammeldownload
        $user_random_id = intval(JArrayHelper::getValue( $_REQUEST, 'user', 0 ));
        $download_verz = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'; 
        $filename = $download_verz.'tempzipfiles/'.$jlistConfig['zipfile.prefix'].$user_random_id.'.zip'; 
        $filename_direct = JURI::base().$jlistConfig['files.uploaddir'].'/tempzipfiles/'.$jlistConfig['zipfile.prefix'].$user_random_id.'.zip';
        // check whether direct access
        $database->setQuery('SELECT file_id, file_title FROM #__jdownloads_files WHERE published = 1 AND file_id IN ('.$files.')');
        if (!$rows = $database->loadObjectList()){
           $app->redirect(JURI::base(true)); 
        }    

        // add AUP points
        if ($jlistConfig['use.alphauserpoints']){
            if ($jlistConfig['use.alphauserpoints.with.price.field']){
                $database->setQuery("SELECT SUM(price) FROM #__jdownloads_files WHERE file_id IN ($files)");
                $sum_points = (int)$database->loadResult();
                if ($profil->points >= $sum_points){
                    foreach($rows as $aup_data){
                        $database->setQuery("SELECT price FROM #__jdownloads_files WHERE file_id = '$aup_data->file_id'");
                        if ($price = (int)$database->loadResult()){
                            $can_download = setAUPPointsDownloads($user->id, $aup_data->file_title, $price);
                        }
                    }
                }
            
            } else {
                // use fix points
                $sum_points = $aup_fix_points * count($files_arr);
                if ($profil->points >= $sum_points){
                    foreach($rows as $aup_data){
                        $can_download = setAUPPointsDownloads($user->id, $aup_data->file_title, 0);
                    }
                } else {
                    $can_download = false;
                }    
            }
       
        } else {
            // no AUP active
            $can_download = true;
        }
        if ($jlistConfig['user.can.download.file.when.zero.points'] && $user->id){
            $can_download = true;
        }    
        if (!$can_download){
            $aup_no_points = '<div style="text-align:center" class="jd_div_aup_message">'.stripslashes($jlistConfig['user.message.when.zero.points']).'</div>'. 
            '<div style="text-align:center" class="jd_div_aup_message">'.JText::_('JLIST_BACKEND_SET_AUP_FE_MESSAGE_NO_DOWNLOAD_POINTS').' '.(int)$profil->points.'<br />'.JText::_('JLIST_BACKEND_SET_AUP_FE_MESSAGE_NO_DOWNLOAD_NEEDED').' '.JText::_($sum_points).'</div>'.
            '<div style="text-align:left" class="back_button"><a href="javascript:history.go(-1)">'.JText::_('JLIST_FRONTEND_BACK_BUTTON').'</a></div>';
            echo $aup_no_points;
        }
        // download limits
        // check the log - can user download the file?
        $may_download = false;
        $may_download = checkLog($fileid, $user);
        if (!$may_download){
            // download not possible
            $datenow =& JFactory::getDate(); 
            $date = $datenow->toFormat("%Y-%m-%d %H:%m");
            $back .= '<div style="text-align:left" class="back_button"><a href="javascript:history.go(-1)">'.JText::_('JLIST_FRONTEND_BACK_BUTTON').'</a></div>'; 
            echo '<div style="text-align:center" class="jd_limit_reached_message">'.stripslashes($jlistConfig['limited.download.reached.message']).' '.$date.'</div>'.$back;         
        }
        
    } else {
        // einzelner download
        // check whether direct access
        $database->setQuery("SELECT file_title FROM #__jdownloads_files WHERE published = 1 AND file_id = '".(int)$fileid."'");
        if (!$ok = $database->loadResult()){
           $app->redirect(JURI::base(true)); 
        }    
        
        // download limits
        // check the log - can user download the file?
        $may_download = false;
        $may_download = checkLog($fileid, $user);
        if (!$may_download){
            // download not possible
            $datenow =& JFactory::getDate(); 
            $date = $datenow->toFormat("%Y-%m-%d %H:%m");
            $back .= '<div style="text-align:left" class="back_button"><a href="javascript:history.go(-1)">'.JText::_('JLIST_FRONTEND_BACK_BUTTON').'</a></div>'; 
            echo '<div style="text-align:center" class="jd_limit_reached_message">'.stripslashes($jlistConfig['limited.download.reached.message']).' '.$date.'</div>'.$back;         
        } else {        

           // get filename and build path
           if (!$is_mirror){
               $database->setQuery("SELECT url_download FROM #__jdownloads_files WHERE file_id = '".(int)$fileid."'");
               $file_url = utf8_decode($database->loadResult());
               if ($file_url){
                   $database->setQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '".(int)$catid."'");
                   $cat_dir = $database->loadResult();
                   $filename = JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.$cat_dir.'/'.$file_url;
                   $filename_direct = JURI::base().$jlistConfig['files.uploaddir'].'/'.$cat_dir.'/'.$file_url;        
               } else {
                   $database->setQuery("SELECT * FROM #__jdownloads_files WHERE file_id = '".(int)$fileid."'");
                   $result = $database->loadObjectlist();
                   $filename = $result[0]->extern_file; 
                   if ($result[0]->extern_site){
                       $extern_site = true;
                   }
                   $extern = true;
               }
           } else {
             // is mirror 
             $database->setQuery("SELECT * FROM #__jdownloads_files WHERE file_id = '".(int)$fileid."'");
             $result = $database->loadObjectlist();
             if ($is_mirror == 1){
                 $filename = $result[0]->mirror_1; 
                 if ($result[0]->extern_site_mirror_1){
                     $extern_site = true;
                 }
             } else {
                 $filename = $result[0]->mirror_2; 
                 if ($result[0]->extern_site_mirror_2){
                     $extern_site = true;
                 }
             }
             $extern = true;    
           }      
           // AUP integration
           if ($jlistConfig['use.alphauserpoints.with.price.field'] && $jlistConfig['use.alphauserpoints']){
               $database->setQuery("SELECT price FROM #__jdownloads_files WHERE file_id = '".(int)$fileid."'");
               $price = (int)$database->loadResult();
           } else {
               if ($jlistConfig['use.alphauserpoints']){
                   $price = $aup_fix_points;
               }        
           }    
            
           $can_download = setAUPPointsDownload($user->id, $ok, $price);
           if ($jlistConfig['user.can.download.file.when.zero.points'] && $user->id){
               $can_download = true;
           }    
           if (!$can_download){
               // get AUP user data
               $profil = AlphaUserPointsHelper:: getUserInfo ( '', $user->id );
               $aup_no_points = '<div style="text-align:center" class="jd_div_aup_message">'.stripslashes($jlistConfig['user.message.when.zero.points']).'</div>'.
               '<div style="text-align:center" class="jd_div_aup_message">'.JText::_('JLIST_BACKEND_SET_AUP_FE_MESSAGE_NO_DOWNLOAD_POINTS').' '.(int)$profil->points.'<br />'.JText::_('JLIST_BACKEND_SET_AUP_FE_MESSAGE_NO_DOWNLOAD_NEEDED').' '.JText::_($price).'</div>'. 
               '<div style="text-align:left" class="back_button"><a href="javascript:history.go(-1)">'.JText::_('JLIST_FRONTEND_BACK_BUTTON').'</a></div>';
               echo $aup_no_points;
           } 
        }   
    }    
    // run download
    if ($can_download && $may_download){
        // send mail
        if ($jlistConfig['send.mailto.option'] == '1') {
            if ($fileid){
                sendMail($fileid);  
            } else {
                sendMail($files);               
            }    
        }
        // give uploader AUP points when is set on
        if ($jlistConfig['use.alphauserpoints']){
            setAUPPointsDownloaderToUploader($fileid, $files);  
        }
        
        // update downloads hits
        if ($files){
            $database->setQuery('UPDATE #__jdownloads_files SET downloads=downloads+1 WHERE file_id IN ('.$files.')'); 
            $database->query();    
        } else {
            if ($fileid){
                $database->setQuery("UPDATE #__jdownloads_files SET downloads=downloads+1 WHERE file_id = '".(int)$fileid."'");
                $database->query();
            }    
        }
            
	    // start download
        $x = download($filename, $filename_direct, $extern, $extern_site);
    }    
    if ($x == 2) {
        // files not exists
        echo '<div align ="center"><br /><b><font color="#990000">'.JText::_('JLIST_FRONTEND_FILE_NOT_FOUND_MESSAGE').'</font></b><br /><br /></div>';         
    }
  }    
}

// download starten
function download($file, $filename_direct, $extern, $extern_site){
     global $jlistConfig, $mainframe;
    
    $view_types = array();
    $view_types = explode(',', $jlistConfig['file.types.view']); 
    clearstatcache(); 
    // existiert file - wenn nicht error
    if (!$extern){
        if (!file_exists($file)) { 
            return 2;
        } else {
            $len = filesize($file);
        }    
    } else {   
         $len = urlfilesize($file); 
    }
    
    // if url go to other website - open it in a new browser window
    if ($extern_site){
        echo "<script>document.location.href='$file';</script>\n";  
        exit;   
    }    
    
    // if set the option for direct link to the file
    if (!$jlistConfig['use.php.script.for.download']){
        $app = &JFactory::getApplication();
        $app->redirect($filename_direct);
    } else {    
        $filename = basename($file);
        $file_extension = strtolower(substr(strrchr($filename,"."),1));
        $ctype = datei_mime($file_extension);
        ob_end_clean();
        // needed for MS IE - otherwise content disposition is not used?
        if (ini_get('zlib.output_compression')){
            ini_set('zlib.output_compression', 'Off');
        }
        
        header("Cache-Control: public, must-revalidate");
        header('Cache-Control: pre-check=0, post-check=0, max-age=0');
        // header("Pragma: no-cache");  // Problems with MS IE
        header("Expires: 0"); 
        header("Content-Description: File Transfer");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        header("Content-Type: " . $ctype);
        header("Content-Length: ".(string)$len);
        if (!in_array($file_extension, $view_types)){
            header('Content-Disposition: attachment; filename="'.$filename.'"');
        } else {
          // view file in browser
          header('Content-Disposition: inline; filename="'.$filename.'"');
        }   
        header("Content-Transfer-Encoding: binary\n");
        // set_time_limit doesn't work in safe mode
        if (!ini_get('safe_mode')){ 
            @set_time_limit(0);
        }
        @readfile($file);
    }
    exit;
}

function datei_mime($filetype) {
    
    switch ($filetype) {
        case "ez":  $mime="application/andrew-inset"; break;
        case "hqx": $mime="application/mac-binhex40"; break;
        case "cpt": $mime="application/mac-compactpro"; break;
        case "doc": $mime="application/msword"; break;
        case "bin": $mime="application/octet-stream"; break;
        case "dms": $mime="application/octet-stream"; break;
        case "lha": $mime="application/octet-stream"; break;
        case "lzh": $mime="application/octet-stream"; break;
        case "exe": $mime="application/octet-stream"; break;
        case "class": $mime="application/octet-stream"; break;
        case "dll": $mime="application/octet-stream"; break;
        case "oda": $mime="application/oda"; break;
        case "pdf": $mime="application/pdf"; break;
        case "ai":  $mime="application/postscript"; break;
        case "eps": $mime="application/postscript"; break;
        case "ps":  $mime="application/postscript"; break;
        case "xls": $mime="application/vnd.ms-excel"; break;
        case "ppt": $mime="application/vnd.ms-powerpoint"; break;
        case "wbxml": $mime="application/vnd.wap.wbxml"; break;
        case "wmlc": $mime="application/vnd.wap.wmlc"; break;
        case "wmlsc": $mime="application/vnd.wap.wmlscriptc"; break;
        case "vcd": $mime="application/x-cdlink"; break;
        case "pgn": $mime="application/x-chess-pgn"; break;
        case "csh": $mime="application/x-csh"; break;
        case "dvi": $mime="application/x-dvi"; break;
        case "spl": $mime="application/x-futuresplash"; break;
        case "gtar": $mime="application/x-gtar"; break;
        case "hdf": $mime="application/x-hdf"; break;
        case "js":  $mime="application/x-javascript"; break;
        case "nc":  $mime="application/x-netcdf"; break;
        case "cdf": $mime="application/x-netcdf"; break;
        case "swf": $mime="application/x-shockwave-flash"; break;
        case "tar": $mime="application/x-tar"; break;
        case "tcl": $mime="application/x-tcl"; break;
        case "tex": $mime="application/x-tex"; break;
        case "texinfo": $mime="application/x-texinfo"; break;
        case "texi": $mime="application/x-texinfo"; break;
        case "t":   $mime="application/x-troff"; break;
        case "tr":  $mime="application/x-troff"; break;
        case "roff": $mime="application/x-troff"; break;
        case "man": $mime="application/x-troff-man"; break;
        case "me":  $mime="application/x-troff-me"; break;
        case "ms":  $mime="application/x-troff-ms"; break;
        case "ustar": $mime="application/x-ustar"; break;
        case "src": $mime="application/x-wais-source"; break;
        case "zip": $mime="application/x-zip"; break;
        case "au":  $mime="audio/basic"; break;
        case "snd": $mime="audio/basic"; break;
        case "mid": $mime="audio/midi"; break;
        case "midi": $mime="audio/midi"; break;
        case "kar": $mime="audio/midi"; break;
        case "mpga": $mime="audio/mpeg"; break;
        case "mp2": $mime="audio/mpeg"; break;
        case "mp3": $mime="audio/mpeg"; break;
        case "aif": $mime="audio/x-aiff"; break;
        case "aiff": $mime="audio/x-aiff"; break;
        case "aifc": $mime="audio/x-aiff"; break;
        case "m3u": $mime="audio/x-mpegurl"; break;
        case "ram": $mime="audio/x-pn-realaudio"; break;
        case "rm":  $mime="audio/x-pn-realaudio"; break;
        case "rpm": $mime="audio/x-pn-realaudio-plugin"; break;
        case "ra":  $mime="audio/x-realaudio"; break;
        case "wav": $mime="audio/x-wav"; break;
        case "pdb": $mime="chemical/x-pdb"; break;
        case "xyz": $mime="chemical/x-xyz"; break;
        case "bmp": $mime="image/bmp"; break;
        case "gif": $mime="image/gif"; break;
        case "ief": $mime="image/ief"; break;
        case "jpeg": $mime="image/jpeg"; break;
        case "jpg": $mime="image/jpeg"; break;
        case "jpe": $mime="image/jpeg"; break;
        case "png": $mime="image/png"; break;
        case "tiff": $mime="image/tiff"; break;
        case "tif": $mime="image/tiff"; break;
        case "wbmp": $mime="image/vnd.wap.wbmp"; break;
        case "ras": $mime="image/x-cmu-raster"; break;
        case "pnm": $mime="image/x-portable-anymap"; break;
        case "pbm": $mime="image/x-portable-bitmap"; break;
        case "pgm": $mime="image/x-portable-graymap"; break;
        case "ppm": $mime="image/x-portable-pixmap"; break;
        case "rgb": $mime="image/x-rgb"; break;
        case "xbm": $mime="image/x-xbitmap"; break;
        case "xpm": $mime="image/x-xpixmap"; break;
        case "xwd": $mime="image/x-xwindowdump"; break;
        case "msh": $mime="model/mesh"; break;
        case "mesh": $mime="model/mesh"; break;
        case "silo": $mime="model/mesh"; break;
        case "wrl": $mime="model/vrml"; break;
        case "vrml": $mime="model/vrml"; break;
        case "css": $mime="text/css"; break;
        case "asc": $mime="text/plain"; break;
        case "txt": $mime="text/plain"; break;
        case "gpg": $mime="text/plain"; break;
        case "rtx": $mime="text/richtext"; break;
        case "rtf": $mime="text/rtf"; break;
        case "wml": $mime="text/vnd.wap.wml"; break;
        case "wmls": $mime="text/vnd.wap.wmlscript"; break;
        case "etx": $mime="text/x-setext"; break;
        case "xsl": $mime="text/xml"; break;
        case "flv": $mime="video/x-flv"; break;
        case "mpeg": $mime="video/mpeg"; break;
        case "mpg": $mime="video/mpeg"; break;
        case "mpe": $mime="video/mpeg"; break;
        case "qt":  $mime="video/quicktime"; break;
        case "mov": $mime="video/quicktime"; break;
        case "mxu": $mime="video/vnd.mpegurl"; break;
        case "avi": $mime="video/x-msvideo"; break;
        case "movie": $mime="video/x-sgi-movie"; break;
        case "asf": $mime="video/x-ms-asf"; break;
        case "asx": $mime="video/x-ms-asf"; break;
        case "wm":  $mime="video/x-ms-wm"; break;
        case "wmv": $mime="video/x-ms-wmv"; break;
        case "wvx": $mime="video/x-ms-wvx"; break;
        case "ice": $mime="x-conference/x-cooltalk"; break;
        case "rar": $mime="application/x-rar"; break;
        default:    $mime="application/octet-stream"; break; 
    }
    return $mime;
}


// frontend upload form anzeigen
function viewUpload($option, $view){
    global $Itemid, $mainframe;
    $database = &JFactory::getDBO();
  
    // view only when category exist
    $database->SetQuery('SELECT COUNT(*) FROM #__jdownloads_cats WHERE published = 1');
    $cat_sum = $database->loadResult();
    if (!$cat_sum) {
       echo JText::_('JLIST_FRONTEND_UPLOAD_ERROR_NO_CATS_EXIST');             
       
    } else { 
        $breadcrumbs =& $mainframe->getPathWay();
        $breadcrumbs->addItem(JText::_('JLIST_FRONTEND_UPLOAD_PAGE_TITLE'), JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=upload'));
               
		jlist_HTML::viewUpload($option, $view);
    }
}

// show only one category
function showOneCategory($option,$cid) {
    global $mainframe, $limit, $limitstart, $site_aktuell, $jlistConfig, $Itemid, $jlistTemplates;
   
    $breadcrumbs =& $mainframe->getPathWay();
    $database = &JFactory::getDBO();
    $app = &JFactory::getApplication();   
    $catid = (int)JArrayHelper::getValue($_REQUEST,'catid',0);
    
    // cat laden
	$database->setQuery('SELECT * FROM #__jdownloads_cats WHERE published = 1 AND cat_id = '.$catid);
	if (!$cat = $database->loadObjectList()){
        // jump to the mainsite - url manipulation
        $app->redirect(JURI::base(true));     
    }    

    // actualise pathway 
    $breadcrumbs = createPathway($catid, $breadcrumbs, $option);
    $breadcrumbs->addItem($cat[0]->cat_title, '');
    
    if(empty($cat)){
		$cat[0] = new jlist_cats($database);
		$cat[0]->cat_id = 0;
		$cat[0]->cat_title = JText::_('JLIST_FRONTEND_NOCAT');
    } else {
    
            // subcats laden
            $access = checkAccess_JD();
            
            // reihenfolge wie in optionen gesetzt
            $cat_sort_field = 'ordering';
            $cat_sort = '';
            if ($jlistConfig['cats.order'] == 1) {
                $cat_sort_field = 'cat_title';
            }
            if ($jlistConfig['cats.order'] == 2) {
                $cat_sort_field = 'cat_title';
                $cat_sort = 'DESC';
            }    
            
            $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE parent_id = '$catid' AND published = 1 AND cat_access <= '$access' ORDER BY $cat_sort_field $cat_sort");
            $subs = $database->loadObjectList();
        
            if ($subs) {
                $sum_subcats = array();
                $sum_subfiles = array();
                
                foreach($subs as $sub){
                    // summe für subcats und files der einzel cat holen
                    $files = 0;
                    $subcats = 0;
                    $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_files WHERE cat_id = '$sub->cat_id' AND published = 1");
                    $sum = $database->loadResult();
                    $files = $files + $sum;
                    infos($sub->cat_id, $subcats, $files, $access);
                    $sum_subfiles[] = $files;
                    $sum_subcats[] = $subcats;
                }         
            }    

        // anzahl files ermitteln
        $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_files WHERE cat_id = '$catid' AND published = 1");
        $total = $database->loadResult();

        if ( $total <= $limit ) {
            $limitstart = 0;
        }
        $sum_pages = ceil($total / $limit);
    
        // manipulation ungültiger werte abblocken
        if ($site_aktuell > $sum_pages || $limitstart > $total || $limitstart < 0){
            $limitstart = 0;
            $site_aktuell = 1; 
        }         
        
        // load files in order by config 
        $files = array();
        $files = getSortedFiles($catid, $limitstart, $limit);        
    }
    $access = array();
    $p_access = array();
    // übergeordnete cat access rechte holen und verarbeiten
    if ($cat[0]->parent_id){
        $database->setQuery("SELECT cat_access FROM #__jdownloads_cats WHERE cat_id = ".$cat[0]->parent_id);
        $parent_access = $database->loadResult();
        $p_access[0] = (int)substr($parent_access, 0, 1);
        $p_access[1] = (int)substr($parent_access, 1, 1);
        $access[0] = (int)substr($cat[0]->cat_access, 0, 1);
        $access[1] = (int)substr($cat[0]->cat_access, 1, 1);
        if ($p_access[0] > $access[0]) $access[0] = $p_access[0];
        if ($p_access[1] > $access[1]) $access[1] = $p_access[1];
    } else {
        $parent_access = NULL;
        $access[0] = (int)substr($cat[0]->cat_access, 0, 1);
        $access[1] = (int)substr($cat[0]->cat_access, 1, 1);
    }
    $columns = (int)$jlistTemplates[1][0]->cols;
     
    jlist_HTML::showOneCategory($option, $cat, $subs, $files, $catid, $total, $sum_pages, $limit, $limitstart, $sum_subcats, $sum_subfiles, $site_aktuell, $access, $columns);
}                                                                                              

// einzelnen download mit detaillierten infos anzeigen
function showDownload($option,$cid){
   global $mainframe, $Itemid; 
   $database = &JFactory::getDBO();
   $app = &JFactory::getApplication(); 
   
   $database->setQuery('SELECT * FROM #__jdownloads_files WHERE published = 1 AND file_id = '.(int)$cid);
   if (!$file = $database->loadObject()){
        $app->redirect(JURI::base(true)); 
   }    
    
   $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE published = 1 AND cat_id = '$file->cat_id'");
   if (!$cat = $database->loadObject()){
        $app->redirect(JURI::base(true)); 
   } 

  // $access = checkAccess_JD();
   $access = array();
   $access[0] = (int)substr($cat->cat_access, 0, 1);
   $access[1] = (int)substr($cat->cat_access, 1, 1);

   $breadcrumbs =& $mainframe->getPathWay();
   $breadcrumbs = createPathway($file->cat_id, $breadcrumbs, $option);
   $breadcrumbs->addItem($cat->cat_title, JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=viewcategory&amp;catid='.$cat->cat_id));
   $breadcrumbs->addItem($file->file_title, '' ); 
      
   jlist_HTML::showDownload($option, $file, $cat, $access);   
}  
// show only categories
function showCats($option,$cid){
	global $jlistConfig, $limit, $limitstart, $site_aktuell, $mainframe, $jlistTemplates;
    
    $database = &JFactory::getDBO(); 
	$breadcrumbs = $mainframe->getPathWay();
    // access
    $access = checkAccess_JD();
    
    if(is_array($cid)) $cid = 0;
	$parent_id = (int)JArrayHelper::getValue($_REQUEST,'parent_id',0);
	$where = '';
	if($cid){
		$where = ' AND cat_id='.$cid;
	}
	$database->SetQuery( "SELECT count(*)"
						. "\nFROM #__jdownloads_cats "
						. "\nWHERE published = 1 AND parent_id = 0 AND cat_access <= '$access'"
						);
  	$total = $database->loadResult();
    if ( $total <= $limit ) {
        $limitstart = 0;
    }
  	$sum_pages = ceil($total / $limit);
    
    // manipulation ungültiger werte abblocken
    if ($site_aktuell > $sum_pages || $limitstart > $total || $limitstart < 0){
       $limitstart = 0;
       $site_aktuell = 1; 
    } 
    
    // reihenfolge wie in optionen gesetzt
    $cat_sort_field = 'ordering';
    $cat_sort = '';
    if ($jlistConfig['cats.order'] == 1) {
        $cat_sort_field = 'cat_title';
    }
    if ($jlistConfig['cats.order'] == 2) {
        $cat_sort_field = 'cat_title';
        $cat_sort = 'DESC';
    }    

    $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE published = 1".$where." AND parent_id = 0 AND cat_access <= '$access' ORDER BY $cat_sort_field $cat_sort LIMIT $limitstart, $limit");
    $cats = $database->loadObjectList();

	if(empty($cats)){
		$cats[0] = new jlist_cats($database);
		$cats[0]->cat_id = 0;
		$cats[0]->cat_title = JText::_('JLIST_FRONTEND_NOCAT');
        $no_cats = true;
	} else {
        // gesamt download infos holen...
        $no_cats = false;
        $catlist = array();
        $query = "SELECT cat_id AS id, parent_id AS parent, cat_title AS name FROM #__jdownloads_cats WHERE published = 1 AND cat_access <= '$access'";
        $database->setQuery( $query );
        $catlist = $database->loadObjectList();
        
        // gesamtanzahl cats inkl. subcats 
        $sum_all_cats = count($catlist);
        // gesamtanzahl aller files
        $totalfiles = 0;
        foreach($catlist as $kat){
            $database->SetQuery( "SELECT count(*) FROM #__jdownloads_files WHERE published = 1 AND cat_id = '$kat->id'");
            $totalfiles =  $totalfiles + $database->loadResult();
        }
        $sub_cats = array();
        $sub_files = array();  
        
        // summe für subcats und files der einzel cat holen
        foreach($cats as $cat){
            $files = 0;
            $subcats = 0;
            $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_files WHERE published = 1 AND cat_id = '$cat->cat_id'");
            $sum = $database->loadResult();
            $files = $files + $sum;
            infos($cat->cat_id, $subcats, $files, $access);
            $sub_files[] = $files;
            $sub_cats[] = $subcats;
        }    
    } 
    $columns = (int)$jlistTemplates[1][0]->cols;
    if ($columns > 1 && strpos($jlistTemplates[1][0]->template_text, '{cat_title1}')){   
 	    jlist_HTML::showCatswithColumns($option, $cats, $total, $sum_pages, $limit, $limitstart, $site_aktuell, $sub_cats, $sub_files, $sum_all_cats, $totalfiles, $columns, $no_cats);
    } else {   
        jlist_HTML::showCats($option, $cats, $total, $sum_pages, $limit, $limitstart, $site_aktuell, $sub_cats, $sub_files, $sum_all_cats, $totalfiles, $no_cats);
    }    
}

function showSearchForm($option,$cid){
    global $mainframe;
    
   $breadcrumbs =& $mainframe->getPathWay();
   $breadcrumbs->addItem(JText::_('JLIST_FRONTEND_SEARCH_TITLE'), JRoute::_('index.php?option='.$option));
    
    jlist_HTML::showSearchForm($option);
}    
            
function showSearchResult($option,$cid){
   global $mainframe, $Itemid;
                                                                                                      
   $breadcrumbs =& $mainframe->getPathWay();
   $breadcrumbs->addItem(JText::_('JLIST_FRONTEND_SEARCH_TITLE'), JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=search'));
   $breadcrumbs->addItem(JText::_('JLIST_FRONTEND_SEARCH_RESULT_TITLE'),'');
    
    jlist_HTML::showSearchResult($option);
}

/**
 * send mail to admin if config set
 */
function sendMail($files){
    global $jlistConfig;
    
    $user = &JFactory::getUser();
    $database = &JFactory::getDBO(); 
    
    // In Joomla config muss sendmail aktiviert sein
    // get filetitle and release for mail and summary
    $mail_files_arr = array();
    $mail_files = "<div><ul>";
    $database->setQuery("SELECT * FROM #__jdownloads_files WHERE file_id IN ($files) ");
    $mail_files_arr = $database->loadObjectList();
    
    for ($i=0; $i<count($mail_files_arr); $i++) {
       if ($mail_files_arr[$i]->license > 0){
           // get license name
           $lic = $mail_files_arr[$i]->license;
           $database->setQuery("SELECT license_title FROM #__jdownloads_license WHERE id = '$lic'");
           $lic_title = $database->loadResult(); 
           $mail_files .= "<div><li>".$mail_files_arr[$i]->file_title.' '.$mail_files_arr[$i]->release.'&nbsp;&nbsp;&nbsp;'.JText::_('JLIST_FE_DETAILS_LICENSE_TITLE').': '.$lic_title.'&nbsp;&nbsp;&nbsp;'.JText::_('JLIST_FE_DETAILS_FILESIZE_TITLE').': '.$mail_files_arr[$i]->size.'</li></div>';
       } else {
           $mail_files .= "<div><li>".$mail_files_arr[$i]->file_title.' '.$mail_files_arr[$i]->release.'&nbsp;&nbsp;&nbsp;'.JText::_('JLIST_FE_DETAILS_FILESIZE_TITLE').': '.$mail_files_arr[$i]->size.'</li></div>';
       }  
    }
    $mail_files .= "</ul></div>";
 
    // get IP
    $ip = getenv ("REMOTE_ADDR");

    // date and time
    $timestamp = time();
    $date_time = strftime($jlistConfig['global.datetime'], $timestamp);

    $user_downloads = '<br />';

    // get user
    if ($user->get('id') == 0) {
       $user_name = JText::_('JLIST_MAIL_DOWNLOADER_NAME_VISITOR');
       $user_group = JText::_('JLIST_MAIL_DOWNLOADER_GROUP');
    } else {
       $user_name = $user->get('username');
       $user_group = $user->get('usertype');
       $user_email = $user->get('email');
    }

    $jlistConfig['send.mailto'] = str_replace(' ', '', $jlistConfig['send.mailto']);
    $empfaenger = explode(';', $jlistConfig['send.mailto']);
    $betreff = $jlistConfig['send.mailto.betreff'];
    $html_format = true;

    $text = "";
    $text = stripslashes($jlistConfig['send.mailto.template.download']);
    $text = str_replace('{file_list}', $mail_files, $text);
    $text = str_replace('{ip_address}', $ip, $text);
    $text = str_replace('{user_name}', $user_name, $text);
    $text = str_replace('{user_group}', $user_group, $text);
    $text = str_replace('{date_time}', $date_time, $text);
    $text = str_replace('{user_email}', $user_email, $text);
    if (!$jlistConfig['send.mailto.html']){
        $html_format = false;
        $text = strip_tags($text);
    }
    $first_adress = array_shift($empfaenger);
    $success = JUtility::sendMail($jlistConfig['send.mailto.fromname'], $jlistConfig['send.mailto.fromname'], $first_adress, $betreff, $text, $html_format, '',$empfaenger);
}    

function sendMailUploads($name, $mail, $url_download, $filetitle, $description){
    global $jlistConfig;
    $database = &JFactory::getDBO(); 
    // get IP
    $ip = getenv ("REMOTE_ADDR");
    // date and time
    $timestamp = time();
    $date_time = strftime($jlistConfig['global.datetime'], $timestamp);

    $jlistConfig['send.mailto.upload'] = str_replace(' ', '', $jlistConfig['send.mailto.upload']);
    $empfaenger = explode(';', $jlistConfig['send.mailto.upload']);
    $betreff = $jlistConfig['send.mailto.betreff.upload'];
    $html_format = true;

    $text = "";
    $text = stripslashes($jlistConfig['send.mailto.template.upload']);
    $text = str_replace('{name}', $name, $text);
    $text = str_replace('{ip}', $ip, $text);
    $text = str_replace('{mail}', $mail, $text);
    $text = str_replace('{file_title}', $filetitle, $text);
    $text = str_replace('{file_name}', $url_download, $text);
    $text = str_replace('{date}', $date_time, $text);
    $text = str_replace('{description}', $description, $text);
    if (!$jlistConfig['send.mailto.html.upload']){
        $html_format = false;
        $text = strip_tags($text);
    }
    $first_adress = array_shift($empfaenger);
    $success = JUtility::sendMail($jlistConfig['send.mailto.fromname.upload'], $jlistConfig['send.mailto.fromname.upload'], $first_adress, $betreff, $text, $html_format, '',$empfaenger);
}      

/**
 * Builds configuration variable
 * @return jlistConfig
 */
function buildjlistConfig(){
	$database = &JFactory::getDBO();

	$jlistConfig = array();
	$database->setQuery("SELECT id, setting_name, setting_value FROM #__jdownloads_config");
	$jlistConfigObj = $database->loadObjectList();
	if(!empty($jlistConfigObj)){
		foreach ($jlistConfigObj as $jlistConfigRow){
			$jlistConfig[$jlistConfigRow->setting_name] = $jlistConfigRow->setting_value;
		}
	}
	return $jlistConfig;
}

/**
 * Build random downloader User-ID
 */
function buildRandomID(){
   mt_srand((double)microtime()*1000000);
   mt_getrandmax();
   $random_id = mt_rand();
   return $random_id;
}

/* Alle Dateien in "tempzipfiles" löschen
/  die älter als der in config angegebenen zeit sind
*/

function deleteOldFile($dir){
	global $jlistConfig;
	
   $del_ok = false;
   $time = gettimeofday();
   foreach (glob($dir."*.*") as $datei) {
      if ( $time[sec] - date(filemtime($datei)) >= ($jlistConfig['tempfile.delete.time'] * 60) )
           $del_ok = unlink($datei);
      }
    return $del_ok;
}

// Get active templates text
// @return jlistTemplates

function getTemplates(){
	$database = &JFactory::getDBO();

    $templates_values = array();

    for ($i=1;$i<6;$i++) {
	   $database->setQuery("SELECT * FROM #__jdownloads_templates WHERE template_typ = '$i' AND template_active = 1");
       $templates_values[$i] = $database->loadObjectList();
       // ist leer, kein layout aktiviert. versuchen standard zu aktivieren, sonst meldung
       if (empty($templates_values[$i])){
           $database->setQuery("SELECT id FROM #__jdownloads_templates WHERE template_typ = '$i' AND locked = 1");
           $id = $database->loadResultArray(0);
           if ($id){
                $database->setQuery("UPDATE #__jdownloads_templates SET template_active = 1 WHERE id = $id[0]");
                $result = $database->query();
                $database->setQuery("SELECT * FROM #__jdownloads_templates WHERE template_typ = '$i' AND template_active = 1");
                $templates_values[$i] = $database->loadObjectList();
           }
       }    
    }
    return $templates_values;
}

// get files in sortorder (see config)
function getSortedFiles($catid, $limitstart, $limit) {
    global $jlistConfig;
    $database = &JFactory::getDBO();

    switch ($jlistConfig['files.order']) {
    
    case '0':
        $database->setQuery("SELECT * FROM #__jdownloads_files WHERE cat_id = '$catid' AND published = 1 ORDER BY ordering LIMIT $limitstart, $limit");
        break;

    case '1':
        $database->setQuery("SELECT a.* FROM #__jdownloads_files AS a WHERE a.cat_id = '$catid' AND a.published = 1 ORDER BY a.date_added DESC LIMIT $limitstart, $limit");
        break;

    case '2':
        $database->setQuery("SELECT a.* FROM #__jdownloads_files AS a WHERE a.cat_id = '$catid' AND a.published = 1 ORDER BY a.date_added ASC LIMIT $limitstart, $limit");
        break;

    case '3':
        $database->setQuery("SELECT a.* FROM #__jdownloads_files AS a WHERE a.cat_id = '$catid' AND a.published = 1 ORDER BY a.file_title ASC LIMIT $limitstart, $limit");
        break;

    case '4':
        $database->setQuery("SELECT a.* FROM #__jdownloads_files AS a WHERE a.cat_id = '$catid' AND a.published = 1 ORDER BY a.file_title DESC LIMIT $limitstart, $limit");
        break;
    
    case '5':
        $database->setQuery("SELECT a.* FROM #__jdownloads_files AS a WHERE a.cat_id = '$catid' AND a.published = 1 ORDER BY a.update_active DESC, a.modified_date DESC, a.date_added DESC LIMIT $limitstart, $limit");
        break;
    }
    $files = $database->loadObjectList();
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

// build comp header
function makeHeader($header, $compo_text, $is_showcats, $is_one_cat, $sum_subs, $is_detail, $is_search, $is_upload, $is_summary,  $is_finish, $sum_pages, $limit, $total, $limitstart, $site_aktuell) {
	global $jlistConfig, $Itemid, $page_title;
    
	$user = &JFactory::getUser(); 
	$database = &JFactory::getDBO();

    $menus = &JSite::getMenu();
    $menu = $menus->getActive();
    $Itemid = $menu->id;    
    // Anzeige 1 von 0 verhindern
    if ($sum_pages == 0){
        $sum_pages = 1;
    }    
    
	// compo header
    $header = '';
    $header = '<div class="componentheading"><h1>'.$page_title.'</h1></div>';
    
	// components description
	if ($compo_text && $jlistConfig['downloads.titletext'] != '') {
        $header_text = stripslashes($jlistConfig['downloads.titletext']);
		if ($jlistConfig['google.adsense.active'] && $jlistConfig['google.adsense.code'] != ''){
            $header_text = str_replace( '{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $header_text);
        } else {
            $header_text = str_replace( '{google_adsense}', '', $header_text);
        }   
        $header .= $header_text;
	}	

    // home link
    $header .= '<table class="jd_top_navi"><tr><td align="center"></td>';

    // insert search link
    $header .= '<td align="center"></td>';

    // insert frontend upload link if active
    if ($jlistConfig['frontend.upload.active']) {
        $header .= '<td align="center"><a href="'.JRoute::_('index.php?option=com_jdownloads&amp;Itemid='.$Itemid.'&amp;view=upload').'">'.'<img src="'.JURI::base().'components/com_jdownloads/images/upload.png" width="32" height="32" border="0" alt="" /></a> <a href="'.JRoute::_('index.php?option=com_jdownloads&amp;Itemid='.$Itemid.'&amp;view=upload').'">'.JText::_('JLIST_FRONTEND_UPLOAD_LINKTEXT').'</a></td>';
    }
    
    // listbox aller cats erzeugen wenn aktiviert in config
    // bei auswahl einer cat per js cat aufrufen
    if ($jlistConfig['show.header.catlist']){
        $catlistid = intval(JArrayHelper::getValue($_REQUEST, 'catid', 0));
   
		$access = checkAccess_JD();
        $src_list = array();
        $root_url = '';
        $url = array();
        
        // reihenfolge wie in optionen gesetzt
        $cat_sort_field = 'ordering';
        $cat_sort = '';
        if ($jlistConfig['cats.order'] == 1) {
            $cat_sort_field = 'cat_title';
        }
        if ($jlistConfig['cats.order'] == 2) {
            $cat_sort_field = 'cat_title';
            $cat_sort = 'DESC';
        }   

		$query = "SELECT cat_id AS id, parent_id AS parent, cat_title AS name FROM #__jdownloads_cats WHERE published = 1 AND cat_access <= '$access' ORDER BY $cat_sort_field $cat_sort";
		$database->setQuery( $query );
		$src_list = $database->loadObjectList();
        
        $query = "SELECT cat_id AS id, parent_id AS parent, cat_title AS name FROM #__jdownloads_cats WHERE published = 1 AND cat_access <= '$access' ORDER BY cat_id";
        $database->setQuery( $query );
        $src_for_url_list = $database->loadObjectList();
        
        $max_cat_id = $src_for_url_list[count($src_for_url_list)-1]->id;
        $x = 0;
        for ($i=0; $i < $max_cat_id; $i++){ 
            if ($src_for_url_list[$x]->id == ($i+1)){  
                $url[$src_for_url_list[$x]->id] = JRoute::_("index.php?option=com_jdownloads&Itemid=".$Itemid."&view=viewcategory&catid=".$src_for_url_list[$x]->id);
                $x++;
            } else {
                $url[$i+1] = 'null';                        
            }    
        }
        $url = implode(',',$url);
        $root_url = JRoute::_("index.php?option=com_jdownloads&Itemid=".$Itemid);    
		$preload = array();
		$preload[] = JHTML::_('select.option', '0', JText::_('JLIST_FRONTEND_HEADER_CATLIST_TITLE') );
		$selected = array();
		$selected[] = JHTML::_('select.option', $catlistid );
    	// treeSelectList ist veraltet... muss ersetzt werden durch makeoption und selectlist
        $cat_listbox = treeSelectList( $src_list, 0, $preload, 'cat_list',
                 'class="inputbox" size="1" onchange="gocat(\''.$root_url.'\',\''.$url.'\')"', 'value', 'text', $selected );
		
		$header .= '<td valign="bottom"><form name="go_cat" id="go_cat" action="" method="post">'.$cat_listbox.'</form></td></tr></table>';         
    } else {
         $header .='</tr></table>';
    }
		
    
    // Subheader !!
        
    // hide subheader
    if ($jlistConfig['view.subheader']) {
        if ($is_showcats) {
            $prev_active = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=&amp;site=".($site_aktuell-1)."&amp;start=".($limitstart-$limit)).'">'.JText::_('JLIST_FRONTEND_PREV_SITE_BUTTON').'</a>';
            $prev_inactive = '';
            $next_active = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=&amp;site=".($site_aktuell+1)."&amp;start=".($limitstart+$limit)).'">'.JText::_('JLIST_FRONTEND_NEXT_SITE_BUTTON').'</a>';
            $next_inactive = '';
			
           if ($site_aktuell == 1 && $site_aktuell + 1 > $sum_pages ){
               // keine links
               $nav1 = $prev_inactive;
               $nav2 = $next_inactive;
           } else {
                if ($site_aktuell == 1 && $site_aktuell + 1 <= $sum_pages) {
                    // nur link zur nächsten site
                    $nav1 = $prev_inactive;
                    $nav2 = $next_active;
                } else {
                    if ($site_aktuell > 1 && $site_aktuell + 1 <= $sum_pages ){
                        // link zur nächsten und vorigen site
                        $nav1 = $prev_active;
                        $nav2 = $next_active;
                    } else {
                        if ($site_aktuell > 1 && $site_aktuell == $sum_pages ){
                            // nur link nur zur vorigen site
                            $nav1 = $prev_active;
                            $nav2 = $next_inactive;
                        } 
                    }
               }
		   }			
           if ($jlistConfig['option.navigate.top']){
                $header .= '<table class="jd_cat_subheader">
                        <tr><td colspan="3"></td></tr>
                        <tr><td width="70%" valign="top"><b>'.JText::_('JLIST_FRONTEND_SUBTITLE_OVER_CATLIST').'</b></td>
                        <td width="30%" valign="top" colspan="2"><div style="text-align:right">'.$nav1.' '.JText::_('JLIST_FRONTEND_HEADER_PAGENAVI_PAGE_TEXT').' '.$site_aktuell.' '.JText::_('JLIST_FRONTEND_HEADER_PAGENAVI_TO_TEXT').' '.$sum_pages.' '.$nav2.'</div></td></tr>
                        <tr><td width="70%" valign="top" align="left">'.JText::_('JLIST_FRONTEND_SUBHEADER_NUMBER_OF_CATS_TITLE').': '.$total.'</td>';
                $header .= '</tr></table>';
           } else {
                $header .= '<table class="jd_cat_subheader">
                        <tr><td colspan="3"></td></tr>
                        <tr><td width="70%" valign="top"><b>'.JText::_('JLIST_FRONTEND_SUBTITLE_OVER_CATLIST').'</b></td>
                        <td width="30%" valign="top" colspan="2"><div style="text-align:right"> </div></td></tr>
                        <tr><td width="70%" valign="top" align="left">'.JText::_('JLIST_FRONTEND_SUBHEADER_NUMBER_OF_CATS_TITLE').': '.$total.'</td>';
                $header .= '</tr></table>';        
           }              
        }
        
        if ($is_one_cat) {
            $catid = intval(JArrayHelper::getValue($_REQUEST, 'catid', 0));
            $database->setQuery("SELECT cat_title FROM #__jdownloads_cats WHERE cat_id = '$catid'");
            $title = $database->loadResult();
            
            $prev_active = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=viewcategory&amp;catid=".$catid."&amp;site=".($site_aktuell-1)."&amp;start=".($limitstart-$limit)).'">'.JText::_('JLIST_FRONTEND_PREV_SITE_BUTTON').'</a>';
            $prev_inactive = '';
            $next_active = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=viewcategory&amp;catid=".$catid."&amp;site=".($site_aktuell+1)."&amp;start=".($limitstart+$limit)).'">'.JText::_('JLIST_FRONTEND_NEXT_SITE_BUTTON').'</a>';
            $next_inactive = '';

            // summe subcats nur anzeigen wenn vorhanden
            if ($sum_subs == 0){
                $einf ='';
            } else {
             $einf = JText::_('JLIST_FRONTEND_SUBHEADER_NUMBER_OF_SUBCATS_TITLE').': '.$sum_subs;
            }
           
           if ($site_aktuell == 1 && $site_aktuell + 1 > $sum_pages ){
               // keine links
               $nav1 = $prev_inactive;
               $nav2 = $next_inactive;
           } else {
                if ($site_aktuell == 1 && $site_aktuell + 1 <= $sum_pages) {
                // nur link zur nächsten site
                $nav1 = $prev_inactive;
                $nav2 = $next_active;
           } else {
                if ($site_aktuell > 1 && $site_aktuell + 1 <= $sum_pages ){
                // link zur nächsten und vorigen site
                $nav1 = $prev_active;
                $nav2 = $next_active;
                } else {
                   if ($site_aktuell > 1 && $site_aktuell == $sum_pages ){
                      // nur link nur zur vorigen site
                      $nav1 = $prev_active;
                      $nav2 = $next_inactive;
                   } 
                }
           }
           }
           if ($jlistConfig['option.navigate.top']){
                $header .= '';     
           } else {
                $header .= '';     
           }          
        }    
    }    
    
    if ($is_detail) {
        $header .= '<table class="jd_cat_subheader"><tr><td><b> '.JText::_('JLIST_FRONTEND_SUBTITLE_OVER_DETAIL').' </b></td><td width="30%" align="right"> </td></tr></table>'; 
    }                

    if ($is_search) {
        $header .= '<table class="jd_cat_subheader"><tr><td><b> '.JText::_('JLIST_FRONTEND_SEARCH_LINKTEXT').' </b></td><td width="30%" align="right"> </td></tr></table>'; 
    }
        
    if ($is_upload) {
        $header .= '<table class="jd_cat_subheader"><tr><td><b> '.JText::_('JLIST_FRONTEND_UPLOAD_PAGE_TITLE').' </b></td><td width="30%" align="right"> </td></tr></table>'; 
    }        
        
    if ($is_summary) {
        $header .= '<table class="jd_cat_subheader"><tr><td><b> '.JText::_('JLIST_FRONTEND_HEADER_SUMMARY_TITLE').' </b></td><td width="30%" align="right"> </td></tr></table>'; 
    }         
        
    if ($is_finish) {
        $header .= '<table class="jd_cat_subheader"><tr><td> '.JText::_('JLIST_FRONTEND_HEADER_FINISH_TITLE').' </td><td width="30%" align="right"> </td></tr></table>'; 
    }
        
    if ( !$jlistConfig['offline'] ) {
            return $header;
        } else {
            if ($user->get('aid') == 2) {
                return $header;     
            } else {
                $header = '<div class="componentheading"><h1>'.$jlistConfig['jd.header.title'].'</h1></div>';
                // components description
                if ($compo_text && $jlistConfig['downloads.titletext'] != '') {
                    $header .= $jlistConfig['downloads.titletext'];
                }
                return $header;    
            }
        }             
}

// build comp footer
function makeFooter($make_back_button, $is_showcats, $is_one_cat, $sum_pages, $limit, $limitstart, $site_aktuell) {
    global $Itemid, $jlistConfig;
    $database = &JFactory::getDBO();
	
    $footer = '';
    if ($sum_pages == 0){
        $sum_pages = 1;
    }
    
    // view pagnavigation bottom
    if ($jlistConfig['option.navigate.bottom']){ 
       if ($is_showcats) {
           $prev_active = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=&amp;site=".($site_aktuell-1)."&amp;start=".($limitstart-$limit)).'">'.JText::_('JLIST_FRONTEND_PREV_SITE_BUTTON').'</a>';
           $prev_inactive = ''; //JLIST_FRONTEND_PREV_SITE_BUTTON;
           $next_active = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=&amp;site=".($site_aktuell+1)."&amp;start=".($limitstart+$limit)).'">'.JText::_('JLIST_FRONTEND_NEXT_SITE_BUTTON').'</a>';
           $next_inactive = ''; //JLIST_FRONTEND_NEXT_SITE_BUTTON;
       }
       if ($is_one_cat) {
            $catid = intval(JArrayHelper::getValue($_REQUEST, 'catid', 0));
            $database->setQuery("SELECT cat_title FROM #__jdownloads_cats WHERE cat_id = '$catid'");
            $title = $database->loadResult();
            $prev_active = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=viewcategory&amp;catid=".$catid."&amp;site=".($site_aktuell-1)."&amp;start=".($limitstart-$limit)).'">'.JText::_('JLIST_FRONTEND_PREV_SITE_BUTTON').'</a>';
            $prev_inactive = ''; 
            $next_active = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=viewcategory&amp;catid=".$catid."&amp;site=".($site_aktuell+1)."&amp;start=".($limitstart+$limit)).'">'.JText::_('JLIST_FRONTEND_NEXT_SITE_BUTTON').'</a>';
            $next_inactive = ''; 
       }

       if ($site_aktuell == 1 && $site_aktuell + 1 > $sum_pages ){
          // keine links
          $nav1 = $prev_inactive;
          $nav2 = $next_inactive;
       } else {
          if ($site_aktuell == 1 && $site_aktuell + 1 <= $sum_pages) {
              // nur link zur nächsten site
              $nav1 = $prev_inactive;
              $nav2 = $next_active;
          } else {
              if ($site_aktuell > 1 && $site_aktuell + 1 <= $sum_pages ){
                  // link zur nächsten und vorigen site
                  $nav1 = $prev_active;
                  $nav2 = $next_active;
              } else {
                  if ($site_aktuell > 1 && $site_aktuell == $sum_pages ){
                     // nur link nur zur vorigen site
                      $nav1 = $prev_active;
                      $nav2 = $next_inactive;
                  } 
              }
          }
       }   
           
       if ($is_showcats || $is_one_cat){
                $footer .= '<table class="jd_footer" align="right">              
                            <tr><td width="70%" valign="top"></td>
                            <td width="30%" valign="top"><div style="text-align:right">'.$nav1.' '.JText::_('JLIST_FRONTEND_HEADER_PAGENAVI_PAGE_TEXT').' '.$site_aktuell.' '.JText::_('JLIST_FRONTEND_HEADER_PAGENAVI_TO_TEXT').' '.$sum_pages.' '.$nav2.'</div></td></tr></table>';         
       }
    }  
    // footer text
    if ($jlistConfig['downloads.footer.text'] != '') {
        $footer_text = stripslashes($jlistConfig['downloads.footer.text']);
        if ($jlistConfig['google.adsense.active'] && $jlistConfig['google.adsense.code'] != ''){
            $footer_text = str_replace( '{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $footer_text);
        } else {    
            $footer_text = str_replace( '{google_adsense}', '', $footer_text);
        }    
        $footer .= $footer_text;
    }
    
    // back button
	if ($make_back_button && $jlistConfig['view.back.button']){
        $footer .= '<div style="text-align:left" class="back_button"><a href="javascript:history.go(-1)">'.JText::_('JLIST_FRONTEND_BACK_BUTTON').'</a></div>'; 
    }
    $b_link = JText::_('JLIST_PRODUCT_FOOTER');
    if ($b_link){
        $footer .= '';
	}     
	return $footer;
}

function reportDownload($option,$cid){
    global $Itemid, $jlistConfig;
    
    $database = &JFactory::getDBO();
    $user = &JFactory::getUser();
    
    if ($jlistConfig['report.link.only.regged'] && !$user->guest || !$jlistConfig['report.link.only.regged']) { 
        $database->setQuery('SELECT file_title FROM #__jdownloads_files WHERE file_id = '.$cid.' AND published = 1');
        $title = $database->loadResult();
        if ($title){
            // send report
            $mailto_report = str_replace(' ', '', $jlistConfig['send.mailto.report']);
            $empfaenger = explode(';', $mailto_report);
            $betreff = JText::_('JLIST_REPORT_FILE_MESSAGE_TITLE');                                   
            $html_format = true;
            $text = sprintf(JText::_('JLIST_REPORT_FILE_MESSAGE_TEXT'), $title, $cid);
            $first_adress = array_shift($empfaenger);
            $success = JUtility::sendMail('jDownloads', 'jDownloads', $first_adress, $betreff, $text, $html_format, '',$empfaenger);
            if ($success){
                $message = '<div style="text-align:center" class="jd_cat_title"><br /><img src="'.JURI::base().'components/com_jdownloads/images/summary.png" width="48" height="48" border="0" alt="" />'.JText::_('JLIST_REPORT_FILE_MESSAGE_OK').'<br /><br /></div>';
            } else {
                $message = '<div style="text-align:center" class="jd_cat_title"><br /><img src="'.JURI::base().'components/com_jdownloads/images/warning.png" width="48" height="48" border="0" alt="" />'.JText::_('JLIST_REPORT_FILE_MESSAGE_ERROR').'<br /><br /></div>';
            }    
        } else {
                $message = '<div style="text-align:center" class="jd_cat_title"><br /><img src="'.JURI::base().'components/com_jdownloads/images/warning.png" width="48" height="48" border="0" alt="" />'.JText::_('JLIST_REPORT_FILE_MESSAGE_ERROR').'<br /><br /></div>';
        }    
        $message .= '<div style="text-align:left" class="back_button"><a href="javascript:history.go(-1)">'.JText::_('JLIST_FRONTEND_BACK_BUTTON').'</a></div>'; 
        echo $message;
    }     
} 

function checkAccess_JD(){
    
    $user = &JFactory::getUser();
    $access = '';
  
    if ($user->aid == 0) $access = '01';
    if ($user->aid == 1) $access = '11';
    if ($user->aid == 2) $access = '22';
    // }    
    return $access;
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

function infos($parent, &$subcats, &$files, $access) {
 $database = &JFactory::getDBO();
    // subcats holen
    $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE parent_id = '$parent' AND published = 1 AND cat_access <= '$access'");
    $rows = $database->loadObjectList();
    if ($database->getErrorNum()) {
        echo $database->stderr();
        return false;
    }
    if ($rows){
        foreach ($rows as $v) {
            $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_files WHERE cat_id = '$v->cat_id' AND published = 1");
            $sum = $database->loadResult();
            $files = $files + $sum;
            $subcats++;
            // nach nächster ebene suchen
            infos($v->cat_id, $subcats, $files, $access);
        }
    }
}

// Dateigröße einer externen Datei ermitteln
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
    return $size;    
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
    
    /* Prüfen ob Datei existiert */
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
    /* Alte Maße auslesen */
    $width = $size[0];
    $height = $size[1]; 

$maxwidth = $jlistConfig['thumbnail.size.width'];
$maxheight = $jlistConfig['thumbnail.size.height'];
if ($width/$maxwidth > $height/$maxheight) {
    $newwidth = $maxwidth;
    $newheight = $maxwidth*$height/$width;
} else {
    $newheight = $maxheight;
    $newwidth = $maxheight*$width/$height;
}
     
    /* Neues Bild erstellen mit den neuen Maßen */
    $newpic = imagecreatetruecolor($newwidth,$newheight);
    /* Jetzt wird das Bild nur noch verkleinert */
    imagecopyresized($newpic,$oldpic,0,0,0,0,$newwidth,$newheight,$width,$height); 
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

function checkFileName($name){
    global $jlistConfig;
    if ($name) {
        $name = utf8_decode($name);   
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
        // anti hack .php.rar
        $name = JString::str_ireplace('.php.', '.', $name);
        $name = JString::str_ireplace('.php4.', '.', $name); 
        $name = JString::str_ireplace('.php5.', '.', $name);
        //$name = utf8_encode($name);       
    }               
    return $name;    
}

Function createPathway($catid, $breadcrumbs, $option){
    global $mainframe, $Itemid;
    
    $database = &JFactory::getDBO();
    // cat laden
    $database->setQuery('SELECT * FROM #__jdownloads_cats WHERE published = 1 AND cat_id = '.$catid);
    $cat = $database->loadObjectList();

    $path = array();
    $values = array();
    while ($cat[0]->parent_id){
        $database->setQuery('SELECT cat_id, cat_title, cat_access, parent_id FROM #__jdownloads_cats WHERE published = 1 AND cat_id = '.$cat[0]->parent_id);
        $parent = $database->loadObject();
        if ($parent){
            array_unshift($path, $parent->cat_title.'|'.JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=viewcategory&amp;catid='.$parent->cat_id)); 
        } else {
          $cat[0]->parent_id = 0;  
        } 
        $cat[0]->parent_id = $parent->parent_id;    
    }
    foreach($path as $pat){
       $values = explode ( '|', $pat );
       $breadcrumbs->addItem($values[0], $values[1]);    
    }
    return $breadcrumbs;
}
    
function getID3v2Tags($file,$blnAllFrames=0){
    if (is_file($file)){
        $arrTag[_file]=$file;
        $fp=fopen($file,"rb");
        if($fp){
            $id3v2=fread($fp,3);
            if($id3v2=="ID3"){// a ID3v2 tag always starts with 'ID3'
                $arrTag[_ID3v2]=1;
                $arrTag[_version]=ord(fread($fp,1)).".".ord(fread($fp,1));// = version.revision
                fseek($fp,6);// skip 1 'flag' byte, because i don't need it :)
                unset($tagSize);
                for($i=0;$i<4;$i++){
                    $tagSize=$tagSize.base_convert(ord(fread($fp,1)),10,16);
                }
                $tagSize=hexdec($tagSize);
                if($tagSize>filesize($file)){
                    $arrTag[_error]=4;// = tag is bigger than file
                }
                fseek($fp,10);
                while(ereg("^[A-Z][A-Z0-9]{3}$",$frameName=fread($fp,4))){
                    unset($frameSize);
                    for($i=0;$i<4;$i++){
                        $frameSize=$frameSize.base_convert(ord(fread($fp,1)),10,16);
                    }
                    $frameSize=hexdec($frameSize);
                    if($frameSize>$tagSize){
                        $arrTag[_error]=5;// = frame is bigger than tag
                        break;
                    }
                    fseek($fp,ftell($fp)+2);// skip 2 'flag' bytes, because i don't need them :)
                    if($frameSize<1){
                        $arrTag[_error]=6;// = frame size is smaller then 1
                        break;
                    }
                    if($blnAllFrames==0){
                        if(!ereg("^T",$frameName)){// = not a text frame, they always starts with 'T'
                            unset($arrTag[$frameName]);
                            fseek($fp,ftell($fp)+$frameSize);// go to next frame
                            continue;// read next frame
                        }
                    }
                    $frameContent=fread($fp,$frameSize);
                    if(!$arrTag[$frameName]){
                        $arrTag[$frameName]=trim(utf8_encode($frameContent));// the frame content (always?) starts with 0, so it's better to remove it
                    }
                    else{// if there is more than one frame with the same name
                        $arrTag[$frameName]=$arrTag[$frameName]."~".trim($frameContent);
                    }
                }// while(ereg("^[A-Z0-9]{4}$",fread($fp,4)))
            }// if($id3v2=="ID3")
            else{
                $arrTag[_ID3v2]=0;// = no ID3v2 tag found
                $arrTag[_error]=3;// = no ID3v2 tag found
            }
        }// if($fp)
        else{
            $arrTag[_error]=2;// can't open file
        }
        fclose($fp);
    }// if(is_file($file) and eregi(".mp3$",$file)){
    else{
        $arrTag[_error]=1;// = file doesn't exists or isn't a mp3
    }
    // convert lenght
    if ($arrTag[TLEN] > 0){
        $arrTag[TLEN] = round(($arrTag[TLEN] / 1000)/60,2);
    }    
   
    return $arrTag;
}     

function getRatings($id){    
    global $mainframe, $jlistConfig;
    $app = &JFactory::getApplication();
    $user = &JFactory::getUser();
    $database = &JFactory::getDBO(); 
    $vote = array();
    $database->setQuery('SELECT * FROM #__jdownloads_rating WHERE file_id='. (int) $id);
    $vote = $database->loadObject();
    if ($vote->rating_count!=0){
            $result = number_format(intval($vote->rating_sum) / intval( $vote->rating_count ),2)*20;
    }    
    $rating_sum = intval($vote->rating_sum);
    $rating_count = intval($vote->rating_count);
    // rating only for registered?
    if (($jlistConfig['rating.only.for.regged'] && $user->get('aid') > 0) || !$jlistConfig['rating.only.for.regged']) {
        $script='
        <!-- JW AJAX Vote Plugin v1.1 starts here -->
        <script type="text/javascript">
        var live_site = \''.JURI::base().'\';
        var jwajaxvote_lang = new Array();
        jwajaxvote_lang[\'UPDATING\'] = \''.JText::_('JDVOTE_UPDATING').'\';
        jwajaxvote_lang[\'THANKS\'] = \''.JText::_('JDVOTE_THANKS').'\';
        jwajaxvote_lang[\'ALREADY_VOTE\'] = \''.JText::_('JDVOTE_ALREADY_VOTE').'\';
        jwajaxvote_lang[\'VOTES\'] = \''.JText::_('JDVOTE_VOTES').'\';
        jwajaxvote_lang[\'VOTE\'] = \''.JText::_('JDVOTE_VOTE').'\';
        </script>
        <script type="text/javascript" src="'.JURI::base().'components/com_jdownloads/rating/js/ajaxvote.php"></script>
        <!-- JW AJAX Vote Plugin v1.1 ends here -->
        ';    
        if(!$addScriptJWAjaxVote){ 
            $addScriptJWAjaxVote = 1;
            if($app->getCfg(caching)) {
                $html = $script;
            } else {
                $mainframe->addCustomHeadTag($script);
            }
        }        

        $html .='
        <!-- JW AJAX Vote Plugin v1.1 starts here -->
        <div class="jwajaxvote-inline-rating">
        <ul class="jwajaxvote-star-rating">
        <li id="rating'.$id.'" class="current-rating" style="width:'.$result.'%;"></li>
        <li><a href="javascript:void(null)" onclick="javascript:jwAjaxVote('.$id.',1,'.$rating_sum.','.$rating_count.');" title="1 '.JText::_('JDVOTE_STAR').' 5" class="one-star"></a></li>
        <li><a href="javascript:void(null)" onclick="javascript:jwAjaxVote('.$id.',2,'.$rating_sum.','.$rating_count.');" title="2 '.JText::_('JDVOTE_STARS').' 5" class="two-stars"></a></li>
        <li><a href="javascript:void(null)" onclick="javascript:jwAjaxVote('.$id.',3,'.$rating_sum.','.$rating_count.');" title="3 '.JText::_('JDVOTE_STARS').' 5" class="three-stars"></a></li>
        <li><a href="javascript:void(null)" onclick="javascript:jwAjaxVote('.$id.',4,'.$rating_sum.','.$rating_count.');" title="4 '.JText::_('JDVOTE_STARS').' 5" class="four-stars"></a></li>
        <li><a href="javascript:void(null)" onclick="javascript:jwAjaxVote('.$id.',5,'.$rating_sum.','.$rating_count.');" title="5 '.JText::_('JDVOTE_STARS').' 5" class="five-stars"></a></li>
        </ul>
        <div id="jwajaxvote'.$id.'" class="jwajaxvote-box">
        ';
    } else {
        // view only the results
        $html .='
        <!-- JW AJAX Vote Plugin v1.1 starts here -->
        <div class="jwajaxvote-inline-rating">
        <ul class="jwajaxvote-star-rating">
        <li id="rating'.$id.'" class="current-rating" style="width:'.$result.'%;"></li>
        <li><a href="javascript:void(null)" onclick="" title="1 '.JText::_('JDVOTE_STAR').' 5" class="one-star"></a></li>
        <li><a href="javascript:void(null)" onclick="" title="2 '.JText::_('JDVOTE_STARS').' 5" class="two-stars"></a></li>
        <li><a href="javascript:void(null)" onclick="" title="3 '.JText::_('JDVOTE_STARS').' 5" class="three-stars"></a></li>
        <li><a href="javascript:void(null)" onclick="" title="4 '.JText::_('JDVOTE_STARS').' 5" class="four-stars"></a></li>
        <li><a href="javascript:void(null)" onclick="" title="5 '.JText::_('JDVOTE_STARS').' 5" class="five-stars"></a></li>
        </ul>
        <div id="jwajaxvote'.$id.'" class="jwajaxvote-box">
        ';
    }
    if($rating_count!=1) {
       $html .= "(".$rating_count." ".JText::_('JDVOTE_VOTES').")";
    } else { 
       $html .= "(".$rating_count." ".JText::_('JDVOTE_VOTE').")";
    }
    $html .= '
        </div>
        </div>
        <div class="jwajaxvote-clr"></div>
        <!-- JW AJAX Vote Plugin v1.1 ends here -->    
        '; 
    return $html;       
}

function setAUPPointsUploads($submitted_by, $file_title){
    // added (or reduce) points to the alphauserpoints when is activated in the jD config
    // $submitted_by = user ID after upload a file
    global $jlistConfig;
    if ($jlistConfig['use.alphauserpoints'] && $submitted_by){
        $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
        if (file_exists($api_AUP)){
            require_once ($api_AUP);
            $aupid = AlphaUserPointsHelper::getAnyUserReferreID( $submitted_by );
            if ($aupid){
                $text = JText::_('JLIST_BACKEND_SET_AUP_UPLOAD_TEXT');
                $text = sprintf($text, $file_title);
                AlphaUserPointsHelper::newpoints( 'plgaup_jdownloads_user_upload_published', $aupid, $file_title, $text);
            }     
        }    
    }
}    

function setAUPPointsDownload($user_id, $file_title, $price){
    // added (or reduce) points to the alphauserpoints when is activated in the jD config
    // $user_id = user ID from the file download
    global $jlistConfig;
    if ($jlistConfig['use.alphauserpoints'] && $user_id){
        $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
        if (file_exists($api_AUP)){
            require_once ($api_AUP);
            $aupid = AlphaUserPointsHelper::getAnyUserReferreID( $user_id );
            if ($aupid){
                $text = JText::_('JLIST_BACKEND_SET_AUP_DOWNLOAD_TEXT');
                $text = sprintf($text, $file_title);
                // get AUP user data
                $profil = AlphaUserPointsHelper:: getUserInfo ( '', $user_id );
                if ($jlistConfig['user.can.download.file.when.zero.points'] || $profil->points > 0 || $price == 0){
                    if ($price){
                        // price as points activated
                        if ($profil->points >= $price){
                            if ($jlistConfig['use.alphauserpoints.with.price.field']){
                            AlphaUserPointsHelper::newpoints( 'plgaup_jdownloads_user_download_use_price', $aupid, '', $text, '-'.$price, $text);
                            return true;
                            } else {
                                AlphaUserPointsHelper::newpoints( 'plgaup_jdownloads_user_download', $aupid, '', $text);
                                return true;
                            }    
                        } else {
                            // not enough points . no download
                            return false;
                        }    
                    } else {
                        // use points set in AUP plugin
                        //AlphaUserPointsHelper::newpoints( 'plgaup_jdownloads_user_download', $aupid, '', $text);
                        return true;
                    }    
                } else {
                    // not enough points . no download
                    return false;
                }   
            }     
        } else {
           return true;
        }    
    } else {
      if ($price){
          // not registered user
          return false;
      } else {     
          // guest but no price
          return true;  
      }    
    } 
}

function setAUPPointsDownloads($user_id, $file_title, $price){
    // added (or reduce) points to the alphauserpoints when is activated in the jD config
    // $user_id = user ID from the file download
    global $jlistConfig;
    if ($jlistConfig['use.alphauserpoints'] && $user_id){
        $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
        if (file_exists($api_AUP)){
            require_once ($api_AUP);
            $aupid = AlphaUserPointsHelper::getAnyUserReferreID( $user_id );
            if ($aupid){
                $text = JText::_('JLIST_BACKEND_SET_AUP_DOWNLOAD_TEXT');
                $text = sprintf($text, $file_title);
                // get AUP user data
                $profil = AlphaUserPointsHelper:: getUserInfo ( '', $user_id );
                if ($jlistConfig['user.can.download.file.when.zero.points'] || $profil->points > 0 || $price == 0){
                    if ($price){
                        // price as points activated
                            AlphaUserPointsHelper::newpoints( 'plgaup_jdownloads_user_download_use_price', $aupid, '', $text, '-'.$price, $text);
                            return true;
                    } else {
                        AlphaUserPointsHelper::newpoints( 'plgaup_jdownloads_user_download', $aupid, '', $text);
                        return true;
                    }    
                } else {
                    return false;
                }   
            }     
        } else {
           return true;
        }   
    } else {
      return true;
    }
}

function setAUPPointsDownloaderToUploader($fileid, $files){
    // Assign points to the file uploader when a user download this file from jDownloads
    $database = &JFactory::getDBO(); 
    $files_arr = explode(',', $files);
    $files_arr[] = $fileid;  
    foreach ($files_arr as $file){  
      if ($file){
        $database->setQuery("SELECT submitted_by FROM #__jdownloads_files WHERE file_id = '$file'");
        $uploader_id = (int)$database->loadResult();
        if ($uploader_id){
            $database->setQuery("SELECT file_title FROM #__jdownloads_files WHERE file_id = '$file'");
            $file_title = $database->loadResult();
            $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
            if (file_exists($api_AUP)){
                require_once ($api_AUP);
                $aupid = AlphaUserPointsHelper::getAnyUserReferreID( $uploader_id );
                if ($aupid){
                    $text = JText::_('JLIST_BACKEND_SET_AUP_DOWNLOADER_TO_UPLOADER_TEXT');
                    $text = sprintf($text, $file_title);
                    AlphaUserPointsHelper::newpoints( 'plgaup_jdownloads_downloader_to_uploader', $aupid);
                }     
            }
        }
      }      
    }        
}    

function checkLog($fileid, $user){
    global $jlistConfig;
    $database = &JFactory::getDBO();
    $app = JFactory::getApplication();
    $offset = $app->getCfg('offset');
    $datenow =& JFactory::getDate(); 
    $datenow->setOffset($offset);
    
    $date = $datenow->toFormat("%Y-%m-%d");
    $max_files_day = $jlistConfig['limited.download.number.per.day'];
    if ($max_files_day == 0 || $user->get('aid') == 0 || $user->get('aid') == 2){ 
        // not limited or guest or special user group
        return true;
    } else {
        // delete first old data sets
        $database->setQuery("DELETE FROM #__jdownloads_log WHERE log_datetime != '$date'");
        $database->Query();                 
        // check limit
        $logged_user_files = array();
        $database->setQuery("SELECT * FROM #__jdownloads_log WHERE log_datetime = '$date' AND log_user = '". (int) $user->get('id')."'");
        $logged_user_files = $database->loadObjectList();
        if (!$logged_user_files || count($logged_user_files) < $max_files_day){
            // add file in log 
            $database->setQuery("INSERT INTO #__jdownloads_log (log_file_id, log_ip, log_datetime, log_user, log_browser) VALUES ('".$fileid."', '".$_SERVER['REMOTE_ADDR']."', '".$date."', '".$user->get('id')."', '')");
            $database->query();
            return true;
        } else {
            // download not allowed
            return false;
        }   
    }        
} 

// added for search function
function _ctrSort($a, $b) {
     if (!is_array($a) || !is_array($b) || !array_key_exists("ctr", $a) || !array_key_exists("ctr", $b) || $a['ctr'] == $b['ctr'])
         return 0;
    return ($a['ctr'] < $b['ctr']) ? 1 : -1;

}   

function placeThumbs($html_file, $thumb1, $thumb2, $thumb3){
     global $jlistConfig;
     
        if ($thumb1 != ''){
            $thumbnail =  JURI::base().'images/jdownloads/screenshots/thumbnails/'.$thumb1; 
            $screenshot = JURI::base().'images/jdownloads/screenshots/'.$thumb1; 
            $html_file = str_replace('{thumbnail}', $thumbnail, $html_file);
            $html_file = str_replace('{screenshot}', $screenshot, $html_file);
            $html_file = str_replace('{screenshot_end}', '', $html_file);
            $html_file = str_replace('{screenshot_begin}', '', $html_file); 
         } else { 
            if ($jlistConfig["thumbnail.view.placeholder.in.lists"]) {
                $thumbnail = JURI::base().'images/jdownloads/screenshots/thumbnails/no_pic.gif';
                $screenshot = JURI::base().'images/jdownloads/screenshots/no_pic.gif';
                $html_file = str_replace('{thumbnail}', $thumbnail, $html_file);
                $html_file = str_replace('{screenshot}', $screenshot, $html_file);    
                $html_file = str_replace('{screenshot_end}', '', $html_file);
                $html_file = str_replace('{screenshot_begin}', '', $html_file);
            } else {    
                $pos_end = strpos($html_file, '{screenshot_end}');
                $pos_beg = strpos($html_file, '{screenshot_begin}');
                if ($pos_beg && $pos_end){     
                     $html_file = substr_replace($html_file, '', $pos_beg, ($pos_end - $pos_beg) + 16);
                } 
            }    
         }  

     
        if ($thumb2 != ''){
            $thumbnail =  JURI::base().'images/jdownloads/screenshots/thumbnails/'.$thumb2; 
            $screenshot = JURI::base().'images/jdownloads/screenshots/'.$thumb2; 
            $html_file = str_replace('{thumbnail2}', $thumbnail, $html_file);
            $html_file = str_replace('{screenshot2}', $screenshot, $html_file);
            $html_file = str_replace('{screenshot_end2}', '', $html_file);
            $html_file = str_replace('{screenshot_begin2}', '', $html_file); 
         } else { 
            if ($jlistConfig["thumbnail.view.placeholder.in.lists"]) {
                $thumbnail = JURI::base().'images/jdownloads/screenshots/thumbnails/no_pic.gif';
                $screenshot = JURI::base().'images/jdownloads/screenshots/no_pic.gif';
                $html_file = str_replace('{thumbnail2}', $thumbnail, $html_file);
                $html_file = str_replace('{screenshot2}', $screenshot, $html_file);    
                $html_file = str_replace('{screenshot_end2}', '', $html_file);
                $html_file = str_replace('{screenshot_begin2}', '', $html_file);
            } else {    
                if ($pos_end = strpos($html_file, '{screenshot_end2}')){
                     $pos_beg = strpos($html_file, '{screenshot_begin2}');
                     $html_file = substr_replace($html_file, '', $pos_beg, ($pos_end - $pos_beg) + 17);
                } 
            }    
         }  
     
        if ($thumb3 != ''){
            $thumbnail =  JURI::base().'images/jdownloads/screenshots/thumbnails/'.$thumb3; 
            $screenshot = JURI::base().'images/jdownloads/screenshots/'.$thumb3; 
            $html_file = str_replace('{thumbnail3}', $thumbnail, $html_file);
            $html_file = str_replace('{screenshot3}', $screenshot, $html_file);
            $html_file = str_replace('{screenshot_end3}', '', $html_file);
            $html_file = str_replace('{screenshot_begin3}', '', $html_file); 
         } else { 
            if ($jlistConfig["thumbnail.view.placeholder.in.lists"]) {
                $thumbnail = JURI::base().'images/jdownloads/screenshots/thumbnails/no_pic.gif';
                $screenshot = JURI::base().'images/jdownloads/screenshots/no_pic.gif';
                $html_file = str_replace('{thumbnail3}', $thumbnail, $html_file);
                $html_file = str_replace('{screenshot3}', $screenshot, $html_file);    
                $html_file = str_replace('{screenshot_end3}', '', $html_file);
                $html_file = str_replace('{screenshot_begin3}', '', $html_file);
            } else {    
                if ($pos_end = strpos($html_file, '{screenshot_end3}')){
                     $pos_beg = strpos($html_file, '{screenshot_begin3}');
                     $html_file = substr_replace($html_file, '', $pos_beg, ($pos_end - $pos_beg) + 17);
                } 
            }    
         }  
    return $html_file;
}   
?>