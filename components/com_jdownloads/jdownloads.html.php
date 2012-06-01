<?php
/**
* @version 1.5
* @package JDownloads
* @copyright (C) 2009 Arno Betz - www.jdownloads.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* 
*
*/

function treeSelectList( &$src_list, $src_id, $tgt_list, $tag_name, $tag_attribs, $key, $text, $selected )
       {
   
           // establish the hierarchy of the menu
           $children = array();
           // first pass - collect children
           foreach ($src_list as $v ) {
               $pt = $v->parent;
               $list = @$children[$pt] ? $children[$pt] : array();
               array_push( $list, $v );
               $children[$pt] = $list;
           }
           // second pass - get an indent list of the items
           $ilist = JHTML::_('menu.treerecurse', 0, '', array(), $children );
   
           // assemble menu items to the array
           $this_treename = '';
           foreach ($ilist as $item) {
               if ($this_treename) {
                   if ($item->id != $src_id && strpos( $item->treename, $this_treename ) === false) {
                       $tgt_list[] = JHTML::_('select.option', $item->id, $item->treename );
                   }
               } else {
                   if ($item->id != $src_id) {
                       $tgt_list[] = JHTML::_('select.option', $item->id, $item->treename );
                  } else {
                      $this_treename = "$item->treename/";
                  }
              }
          }
          // build the html select list
           return JHTML::_('select.genericlist', $tgt_list, $tag_name, $tag_attribs, $key, $text, $selected );
      }

defined( '_JEXEC' ) or die( 'Restricted access-html' );
$jlistConfig = buildjlistConfig();
?>
    <script src="<?php echo JURI::base();?>components/com_jdownloads/jdownloads.js" type="text/javascript"></script>
    <script src="<?php echo JURI::base();?>components/com_jdownloads/rating/js/ajaxvote.js" type="text/javascript"></script>
<?php

if ($jlistConfig['use.lightbox.function']){
    ?>
    <script src="<?php echo JURI::base();?>components/com_jdownloads/lightbox/lightbox.js" type="text/javascript"></script>
    <?php
}    

$mainframe->addCustomHeadTag('<script type="text/javascript">var live_site = "'.JURI::base().'";</script>');
$mainframe->addCustomHeadTag( "<link href=\"".JURI::base()."components/com_jdownloads/jdownloads_fe.css\" rel=\"stylesheet\" type=\"text/css\"/>" );
$mainframe->addCustomHeadTag( "<link href=\"".JURI::base()."components/com_jdownloads/lightbox/lightbox.css\" rel=\"stylesheet\" type=\"text/css\"/>" );
$mainframe->addCustomHeadTag( "<link href=\"".JURI::base()."components/com_jdownloads/rating/css/ajaxvote.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\"/>" ); 

$marked_files_id = array();
global $addScriptJWAjaxVote;

class jlist_HTML{

/* ###########################################################
/  Nur Kategorien-�bersicht anzeigen
############################################################## */
	function showCats($option, $cats, $total, $sum_pages, $limit, $limitstart, $site_aktuell, $sub_cats, $sub_files, $sum_all_cats, $totalfiles, $no_cats){
		global $jlistConfig, $jlistTemplates, $Itemid, $mainframe, $page_title;
		$user = &JFactory::getUser();
		$database = &JFactory::getDBO();
	     
    $mainframe->setPageTitle($page_title);
    
    $html_cat = makeHeader($html_cat, true, true, false, 0, false, false, false, false, false, $sum_pages, $limit, $total, $limitstart, $site_aktuell);
    echo $html_cat;
	$html_cat = '';
    $metakey = '';		
	
	if(!empty($cats)){
		for ($i=0; $i < $limit; $i++) { 	
           if ($cats[$i]->cat_title){
               // get access control
               $access = array();
               $access[0] = (int)substr($cats[$i]->cat_access, 0, 1);
               $access[1] = (int)substr($cats[$i]->cat_access, 1, 1);
               // only view when user has corect access level
               if ($user->get('aid') >= $access[0]) {
                   $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_files WHERE cat_id = '{$cats[$i]->cat_id}' AND published = 1");
                   $anzahl_files = $database->loadResult();

                   //display cat info
                   if (!$no_cats){
                       $catlink = "<a href='".JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=viewcategory&amp;catid=".$cats[$i]->cat_id)."'>";
                   } else {
                       $catlink = ''; //$cats[$i]->cat_title;
                   }    

                   // symbol anzeigen - auch als url
                   if ($cats[$i]->cat_pic != '' ) {
                       $size = $jlistConfig['cat.pic.size'];
                       $catpic = $catlink.'<img src="'.JURI::base().'images/jdownloads/catimages/'.$cats[$i]->cat_pic.'" align="top" width="'.$size.'" height="'.$size.'" border="0" alt="" /> </a>';
                   } else {
                       $catpic = '';
                   }                   
                   $html_cat .= str_replace('{cat_title}', $catlink.$cats[$i]->cat_title.'</a>', $jlistTemplates[1][0]->template_text);
			       $html_cat = str_replace('{cat_description}', $cats[$i]->cat_description, $html_cat);
			       $html_cat = str_replace('{cat_pic}', $catpic, $html_cat);
                   if ($sub_cats[$i] == 0){
                       $html_cat = str_replace('{sum_subcats}','', $html_cat);
                   } else {
                       $html_cat = str_replace('{sum_subcats}', JText::_('JLIST_FRONTEND_COUNT_SUBCATS').' '.$sub_cats[$i], $html_cat);
                   }
                   if ($no_cats){
                       $html_cat = str_replace('{sum_files_cat}', '', $html_cat);
                   } else {
                       $html_cat = str_replace('{sum_files_cat}', JText::_('JLIST_FRONTEND_COUNT_FILES').' '.$sub_files[$i], $html_cat); 
                   }    
			       $html_cat = str_replace('{files}', "", $html_cat);
    	           $html_cat = str_replace('{checkbox_top}', "", $html_cat);
			       $html_cat = str_replace('{form_button}', "", $html_cat);
                   $html_cat = str_replace('{form_hidden}', "", $html_cat);
                   $html_cat = str_replace('{cat_info_end}', "", $html_cat);
                   $html_cat = str_replace('{cat_info_begin}', "", $html_cat);
                   // google adsense
                   if ($jlistConfig['google.adsense.active']){
                      $html_cat = str_replace('{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $html_cat);
                   } else {
                      $html_cat = str_replace('{google_adsense}', '', $html_cat);
                   } 
                   
                   // cat title row info only view in one category
                   // remove all title html tags in output
                   if ($pos_end = strpos($html_cat, '{cat_title_end}')){
                       $pos_beg = strpos($html_cat, '{cat_title_begin}');
                       $html_cat = substr_replace($html_cat, '', $pos_beg, ($pos_end - $pos_beg)+ 15);
                   }
                   // add metakey infos
                   if ($cats[$i]->metakey){
                        $metakey = $metakey.' '.$cats[$i]->metakey; 
                   }
               } // access control
           }
        }
        
        $mainframe->appendMetaTag( 'keywords' , strip_tags($metakey));  
        
    	$footer = makeFooter(false, true, false, $sum_pages, $limit, $limitstart, $site_aktuell);  
        $html_cat .= $footer;

        if ( !$jlistConfig['offline'] ) {
            echo $html_cat;
        } else {
            if ($user->get('aid') == 2) {
                echo JText::_('JLIST_BACKEND_OFFLINE_ADMIN_MESSAGE_TEXT');
                echo $html_cat;
            } else {
                $html_off = '<br /><br />'.stripslashes($jlistConfig['offline.text']).'<br /><br />';
                $html_off .= $footer;
                echo $html_off;
            }
        }
    }  
}

/* ###########################################################
/  Nur Kategorien-�bersicht anzeigen
############################################################## */
    function showCatswithColumns($option, $cats, $total, $sum_pages, $limit, $limitstart, $site_aktuell, $sub_cats, $sub_files, $sum_all_cats, $totalfiles, $columns, $no_cats){
        global $jlistConfig, $jlistTemplates, $Itemid, $mainframe, $page_title;
        $user = &JFactory::getUser();
        $database = &JFactory::getDBO();
         
    $mainframe->setPageTitle($page_title);
    
    $html_cat = makeHeader($html_cat, true, true, false, 0, false, false, false, false, false, $sum_pages, $limit, $total, $limitstart, $site_aktuell);
    echo $html_cat;
    $html_cat = '';
    $metakey = '';        
    $amount = 0;
    $rows = (count($cats) / $columns);
     
    if (count($cats) < $limit){
        $amount = count($cats);
    } else {
        $amount = $limit;
    }    
    if(!empty($cats)){
        for ($i=0; $i < $amount; $i++) {
        $a = 0;     
            for ($a=0; $a < $columns; $a++){
            if ($cats[$i]->cat_title){
               // get access control
               $access = array();
               $access[0] = (int)substr($cats[$i]->cat_access, 0, 1);
               $access[1] = (int)substr($cats[$i]->cat_access, 1, 1);
               // only view when user has corect access level
               if ($user->get('aid') >= $access[0]) {
                   $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_files WHERE cat_id = '{$cats[$i]->cat_id}' AND published = 1");
                   $anzahl_files = $database->loadResult();

                   //display cat info
                   if (!$no_cats){
                       $catlink = "<a href='".JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=viewcategory&amp;catid=".$cats[$i]->cat_id)."'>";
                   } else {
                        $catlink = $cats[$i]->cat_title;
                   } 

                   // symbol anzeigen - auch als url
                   if ($cats[$i]->cat_pic != '' ) {
                       $size = $jlistConfig['cat.pic.size'];
                       $catpic = $catlink.'<img src="'.JURI::base().'images/jdownloads/catimages/'.$cats[$i]->cat_pic.'" align="top" width="'.$size.'" height="'.$size.'" border="0" alt="" /> </a>';
                   } else {
                       $catpic = '';
                   }                   
                   $x = $a+1;
                   $x = (string)$x;
                   if ($i < $amount){
                        if ($a == 0){
                            if ($no_cats){
                                $html_cat .= str_replace("{cat_title$x}", $catlink, $jlistTemplates[1][0]->template_text);
                            } else {    
                                $html_cat .= str_replace("{cat_title$x}", $catlink.$cats[$i]->cat_title.'</a>', $jlistTemplates[1][0]->template_text);
                            }
                        } else {
                            $html_cat = str_replace("{cat_title$x}", $catlink.$cats[$i]->cat_title.'</a>', $html_cat);
                        }
                        $html_cat = str_replace("{cat_pic$x}", $catpic, $html_cat);
                        $html_cat = str_replace("{cat_description$x}", $cats[$i]->cat_description, $html_cat);
                        if ($sub_cats[$i] == 0){
                             if ($no_cats){
                                 $html_cat = str_replace("{sum_subcats$x}", '', $html_cat); 
                            } else {
                                $html_cat = str_replace("{sum_subcats$x}", JText::_('JLIST_FRONTEND_COUNT_SUBCATS').' 0', $html_cat); 
                            }    
                        } else {
                            $html_cat = str_replace("{sum_subcats$x}", JText::_('JLIST_FRONTEND_COUNT_SUBCATS').' '.$sub_cats[$i], $html_cat);
                        }
                        if ($no_cats){
                            $html_cat = str_replace("{sum_files_cat$x}", '', $html_cat);
                        } else {    
                            $html_cat = str_replace("{sum_files_cat$x}", JText::_('JLIST_FRONTEND_COUNT_FILES').' '.$sub_files[$i], $html_cat);
                        }    
                   } else {
                        $html_cat = str_replace("{cat_title$x}", '', $html_cat);
                        $html_cat = str_replace("{cat_pic$x}", '', $html_cat);
                        $html_cat = str_replace("{cat_description$x}", '', $html_cat);
                   }         
                   $html_cat = str_replace('{cat_description}', '', $html_cat);
                   $html_cat = str_replace('{files}', "", $html_cat);
                   $html_cat = str_replace('{checkbox_top}', "", $html_cat);
                   $html_cat = str_replace('{form_button}', "", $html_cat);
                   $html_cat = str_replace('{form_hidden}', "", $html_cat);
                   $html_cat = str_replace('{cat_info_end}', "", $html_cat);
                   $html_cat = str_replace('{cat_info_begin}', "", $html_cat);
                   // google adsense
                   if ($jlistConfig['google.adsense.active']){
                      $html_cat = str_replace('{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $html_cat);
                   } else {
                      $html_cat = str_replace('{google_adsense}', '', $html_cat);
                   }                    
                   // cat title row info only view in one category
                   // remove all title html tags in output
                   if ($pos_end = strpos($html_cat, '{cat_title_end}')){
                       $pos_beg = strpos($html_cat, '{cat_title_begin}');
                       $html_cat = substr_replace($html_cat, '', $pos_beg, ($pos_end - $pos_beg)+ 15);
                   }
                   // add metakey infos
                   if ($cats[$i]->metakey){
                        $metakey = $metakey.' '.$cats[$i]->metakey; 
                   }
               } // access control
            }
            if (($a+1) < $columns){
                $i++;
            }    
          } 
        }
        
        for ($b=1; $b < ($columns+1); $b++){
            $x = (string)$b;
            $html_cat = str_replace("{cat_title$x}", '', $html_cat);
            $html_cat = str_replace("{cat_pic$x}", '', $html_cat);
            $html_cat = str_replace("{sum_files_cat$x}", '', $html_cat); 
            $html_cat = str_replace("{sum_subcats$x}", '', $html_cat);
            $html_cat = str_replace("{cat_description$x}", '', $html_cat); 
        }
        
        $mainframe->appendMetaTag( 'keywords' , strip_tags($metakey));  
        
        $footer = makeFooter(false, true, false, $sum_pages, $limit, $limitstart, $site_aktuell);  
        $html_cat .= $footer;

        if ( !$jlistConfig['offline'] ) {
            echo $html_cat;
        } else {
            if ($user->get('aid') == 2) {
                echo JText::_('JLIST_BACKEND_OFFLINE_ADMIN_MESSAGE_TEXT');
                echo $html_cat;
            } else {
                $html_off = '<br /><br />'.stripslashes($jlistConfig['offline.text']).'<br /><br />';
                $html_off .= $footer;
                echo $html_off;
            }
        }
    }
}

/* ###########################################################
/  Einzelne Kategorie mit Liste der download files anzeigen
############################################################## */
	function showOneCategory($option, $cat, $subcats, $files, $catid, $total, $sum_pages, $limit, $limitstart, $sum_subcats, $sum_subfiles, $site_aktuell, $access, $columns){
		global $jlistConfig, $jlistTemplates, $Itemid, $mainframe, $mosConfig_MetaKeys, $page_title;
		$user = &JFactory::getUser();
		$database = &JFactory::getDBO();
        $app = &JFactory::getApplication();
    
    $mainframe->setPageTitle($page_title.' - '.$cat[0]->cat_title ); 
    $mainframe->appendMetaTag( 'keywords' , strip_tags($cat[0]->metakey ));  
    $mainframe->appendMetaTag( 'description' , strip_tags($cat[0]->metadesc)); 
    $sum_subs = count($subcats);
    $html_cat = makeHeader($html_cat, true, false, true, $sum_subs, false, false, false, false, false, $sum_pages, $limit, $total, $limitstart, $site_aktuell);
    echo $html_cat;        
    
    
    // url manipulation?
    if ($site_aktuell > 1) $subcats = '';
        // get pic
        if ($cat[0]->cat_pic != '' ) {
            $size = $jlistConfig['cat.pic.size'];
            $catpic = '<img src="'.JURI::base().'images/jdownloads/catimages/'.$cat[0]->cat_pic.'" align="top" width="'.$size.'" height="'.$size.'" border="0" alt="" /> ';
        } else {
            $catpic = '';
        }
  		//display cat info
		// make sure that this option only works with 1 column layouts
        if ($jlistConfig['view.category.info'] && $columns < 2){
            $viewcatinfo = true;
        } else {
            $viewcatinfo = false;
        }        
        if ($viewcatinfo) {
            $html_cat = str_replace('{cat_title}', $cat[0]->cat_title, $jlistTemplates[1][0]->template_text);
		    $html_cat = str_replace('{cat_description}', $cat[0]->cat_description, $html_cat);
		    $html_cat = str_replace('{cat_pic}', $catpic, $html_cat);
            $html_cat = str_replace('{sum_subcats}', '', $html_cat);
            $html_cat = str_replace('{sum_files_cat}', JText::_('JLIST_FRONTEND_COUNT_FILES').' '.count($files), $html_cat);
            $html_cat = str_replace('{cat_info_begin}', '', $html_cat); 
            $html_cat = str_replace('{cat_info_end}', '', $html_cat);
            // remove all title html tags in top cat output
            if ($pos_end = strpos($html_cat, '{cat_title_end}')){
                $pos_beg = strpos($html_cat, '{cat_title_begin}');
                $html_cat = substr_replace($html_cat, '', $pos_beg, ($pos_end - $pos_beg) + 15);
            }
        } else {
            if ($columns > 1 && strpos($jlistTemplates[1][0]->template_text, '{cat_title1}')){ 
                $html_cat = str_replace('{cat_title1}', '', $jlistTemplates[1][0]->template_text);
                for ($b=1; $b < 10; $b++){
                    $x = (string)$b;
                    $html_cat = str_replace("{cat_title$x}", '', $html_cat);
                    $html_cat = str_replace("{cat_pic$x}", '', $html_cat);
                    $html_cat = str_replace("{sum_files_cat$x}", '', $html_cat); 
                    $html_cat = str_replace("{sum_subcats$x}", '', $html_cat);
                    $html_cat = str_replace("{cat_description$x}", '', $html_cat);  
                } 
            } else {
                $html_cat = str_replace('{cat_title}', '', $jlistTemplates[1][0]->template_text);
                
            }    
            // remove all title html tags in top cat output
            if ($pos_end = strpos($html_cat, '{cat_title_end}')){
                $pos_beg = strpos($html_cat, '{cat_title_begin}');
                $html_cat = substr_replace($html_cat, '', $pos_beg, ($pos_end - $pos_beg) + 15);
            } 
            // remove all html tags in top cat output
            if ($pos_end = strpos($html_cat, '{cat_info_end}')){
                $pos_beg = strpos($html_cat, '{cat_info_begin}');
                $html_cat = substr_replace($html_cat, '', $pos_beg, ($pos_end - $pos_beg) + 14);
            } else {
                $html_cat = str_replace('{cat_description}', '', $html_cat);
                $html_cat = str_replace('{cat_pic}', '', $html_cat);
                $html_cat = str_replace('{sum_subcats}', '', $html_cat);
                $html_cat = str_replace('{sum_files_cat}', '', $html_cat);
            }
        }
        // google adsense
        if ($jlistConfig['google.adsense.active']){
            $html_cat = str_replace('{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $html_cat);
        } else {
            $html_cat = str_replace('{google_adsense}', '', $html_cat);
        }         
  		$html_files = '';
        $i = 0;
        $formid = $cat[0]->cat_id;
                
        // subcats anzeigen
        if(!empty($subcats)){
            $html_cat = str_replace('{files}', "", $html_cat);
            $html_cat = str_replace('{checkbox_top}', "", $html_cat);
            $html_cat = str_replace('{form_hidden}', "", $html_cat);
            $html_cat = str_replace('{form_button}', "", $html_cat);
            
            for ($i=0; $i < count($subcats); $i++){        
                 //display cat info
                 $catlink = "<a href='".JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=viewcategory&amp;catid=".$subcats[$i]->cat_id)."'>";
                 // Symbol anzeigen - auch als url
                 if ($subcats[$i]->cat_pic != '' ) {
                     $size = $jlistConfig['cat.pic.size'];
                     $catpic = $catlink.'<img src="'.JURI::base().'images/jdownloads/catimages/'.$subcats[$i]->cat_pic.'" align="top" width="'.$size.'" height="'.$size.'" border="0" alt="" /> </a>';
                 } else {
                     $catpic = '';
                 }                         

                 // more as one column   ********************************************************
                 if ($columns > 1 && strpos($jlistTemplates[1][0]->template_text, '{cat_title1}')){
                    $a = 0;     
                    for ($a=0; $a < $columns; $a++){   
                         $x = $a+1;
                         $x = (string)$x;
                         if ($i < count($subcats)){
                            if ($a == 0){
                                $html_cat .= str_replace("{cat_title$x}", $catlink.$subcats[$i]->cat_title.'</a>', $jlistTemplates[1][0]->template_text);
                            } else {
                                $html_cat = str_replace("{cat_title$x}", $catlink.$subcats[$i]->cat_title.'</a>', $html_cat);
                            } 
                            $html_cat = str_replace("{cat_pic$x}", $catpic, $html_cat);
                            $html_cat = str_replace("{cat_description$x}", $subcats[$i]->cat_description, $html_cat);
                            if ($sum_subcats[$i] == 0){
                                $html_cat = str_replace("{sum_subcats$x}", JText::_('JLIST_FRONTEND_COUNT_SUBCATS').' 0', $html_cat); 
                            } else {
                                $html_cat = str_replace("{sum_subcats$x}", JText::_('JLIST_FRONTEND_COUNT_SUBCATS').' '.$sum_subcats[$i], $html_cat);
                            }
                            $html_cat = str_replace("{sum_files_cat$x}", JText::_('JLIST_FRONTEND_COUNT_FILES').' '.$sum_subfiles[$i], $html_cat);
                         } else {
                            $html_cat = str_replace("{cat_title$x}", '', $html_cat);
                            $html_cat = str_replace("{cat_pic$x}", '', $html_cat);
                            $html_cat = str_replace("{cat_description$x}", '', $html_cat);
                         }
                         if (($a+1) < $columns){
                            $i++;
                            $catlink = "<a href='".JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=viewcategory&amp;catid=".$subcats[$i]->cat_id)."'>";
                            // Symbol anzeigen - auch als url
                            if ($subcats[$i]->cat_pic != '' ) {
                                $size = $jlistConfig['cat.pic.size'];
                                $catpic = $catlink.'<img src="'.JURI::base().'images/jdownloads/catimages/'.$subcats[$i]->cat_pic.'" align="top" width="'.$size.'" height="'.$size.'" border="0" alt="" /> </a>';
                            } else {
                                $catpic = '';
                            }
                         }  
                    }
                    for ($b=1; $b < 10; $b++){
                        $x = (string)$b;
                        $html_cat = str_replace("{cat_title$x}", '', $html_cat);
                        $html_cat = str_replace("{cat_pic$x}", '', $html_cat);
                        $html_cat = str_replace("{sum_files_cat$x}", '', $html_cat); 
                        $html_cat = str_replace("{sum_subcats$x}", '', $html_cat); 
                    }
                 } else {
                    $html_cat .= str_replace('{cat_title}', $catlink.$subcats[$i]->cat_title.'</a>', $jlistTemplates[1][0]->template_text);
                    if ($sum_subcats[$i] == 0){
                        $html_cat = str_replace('{sum_subcats}','', $html_cat);
                     } else {
                        $html_cat = str_replace('{sum_subcats}', JText::_('JLIST_FRONTEND_COUNT_SUBCATS').' '.$sum_subcats[$i], $html_cat);
                     }
                     $html_cat = str_replace('{sum_files_cat}', JText::_('JLIST_FRONTEND_COUNT_FILES').' '.$sum_subfiles[$i], $html_cat);
                 }
                   
                    $html_cat = str_replace('{cat_description}', $subcats[$i]->cat_description, $html_cat);
                    $html_cat = str_replace('{cat_pic}', $catpic, $html_cat);
                    $html_cat = str_replace('{cat_info_begin}', '', $html_cat); 
                    $html_cat = str_replace('{cat_info_end}', '', $html_cat);
                    if ($i > 0){
                         // remove all title html tags in top cat output
                         if ($pos_end = strpos($html_cat, '{cat_title_end}')){
                            $pos_beg = strpos($html_cat, '{cat_title_begin}');
                            $html_cat = substr_replace($html_cat, '', $pos_beg, ($pos_end - $pos_beg) + 15);
                         } 
                     } else {
                         $html_cat = str_replace('{subcats_title_text}', JText::_('JLIST_FE_FILELIST_TITLE_OVER_SUBCATS_LIST'), $html_cat);             
                         $html_cat = str_replace('{cat_title_begin}', '', $html_cat); 
                         $html_cat = str_replace('{cat_title_end}', '', $html_cat);
                     }    
                     // mehrfache file liste anzeige verhindern
                     if ($i < (count($subcats) -1)) {
                        $html_cat = str_replace('{files}', "", $html_cat);
                        $html_cat = str_replace('{checkbox_top}', "", $html_cat);
                        $html_cat = str_replace('{form_hidden}', "", $html_cat);
                        $html_cat = str_replace('{form_button}', "", $html_cat);
                     }
           }
            // google adsense
            if ($jlistConfig['google.adsense.active']){
                $html_cat = str_replace('{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $html_cat);
            } else {
                $html_cat = str_replace('{google_adsense}', '', $html_cat);
            }
        }                        
        // support for content plugins 
        $html_cat = JHTML::_('content.prepare', $html_cat);

        // build info pics
        $pic_date    = '';
        $pic_license = '';
        $pic_author  = '';
        $pic_website = '';
        $pic_system = '';
        $pic_language  = '';
        $pic_download = '';
        $pic_price = '';
        $pic_size = '';
        // anzeigen wenn im Layout aktiviert (0 = aktiv !!)
        if ($jlistTemplates[2][0]->symbol_off == 0 ) {
            $msize =  $jlistConfig['info.icons.size'];
            $pic_date = '<img src="'.JURI::base().'images/jdownloads/miniimages/date.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_DATE').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_DATE').'" />&nbsp;';
            $pic_license = '<img src="'.JURI::base().'images/jdownloads/miniimages/license.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_LICENCE').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_LICENCE').'" />&nbsp;';
            $pic_author = '<img src="'.JURI::base().'images/jdownloads/miniimages/contact.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_AUTHOR').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_AUTHOR').'" />&nbsp;';
            $pic_website = '<img src="'.JURI::base().'images/jdownloads/miniimages/weblink.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_WEBSITE').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_WEBSITE').'" />&nbsp;';
            $pic_system = '<img src="'.JURI::base().'images/jdownloads/miniimages/system.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_SYSTEM').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_SYSTEM').'" />&nbsp;';
            $pic_language = '<img src="'.JURI::base().'images/jdownloads/miniimages/language.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_LANGUAGE').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_LANGUAGE').'" />&nbsp;';
            $pic_downloads = '<img src="'.JURI::base().'images/jdownloads/miniimages/download.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_DOWNLOAD_HITS').'" />&nbsp;';
            $pic_download = '<img src="'.JURI::base().'images/jdownloads/miniimages/download.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" />&nbsp;';
            $pic_price = '<img src="'.JURI::base().'images/jdownloads/miniimages/currency.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_PRICE').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_PRICE').'" />&nbsp;';
            $pic_size = '<img src="'.JURI::base().'images/jdownloads/miniimages/stuff.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_FILESIZE').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_FILESIZE').'" />&nbsp;';
        }
        // a little pic for extern links
        $extern_url_pic = '<img src="'.JURI::base().'components/com_jdownloads/images/link_extern.gif" alt="" />';

        // files der cat anzeigen
        for ($i=0; $i<count($files); $i++) {
            $value = $files[$i]->file_id;
            // nur checkbox wenn kein externer link
            if (!$files[$i]->extern_file){
                $checkbox_list = '<input type="checkbox" id="cb'.$i.'" name="cb_arr[]" value="'.$value.'" onclick="istChecked(this.checked,'.$formid.');"/>';
            } else {
                $userinfo = JText::_('JLIST_FRONTEND_EXTERN_FILE_USER_INFO');
                $checkbox_list = JHTML::_('tooltip', $userinfo);
            }    
        	$html_file = str_replace('{file_id}',$files[$i]->file_id, $jlistTemplates[2][0]->template_text);
            // files title row info only view when it is the first file
            if ($i > 0){
                // remove all html tags in top cat output
                if ($pos_end = strpos($html_file, '{files_title_end}')){
                    $pos_beg = strpos($html_file, '{files_title_begin}');
                    $html_file = substr_replace($html_file, '', $pos_beg, ($pos_end - $pos_beg) + 17);
                }
            } else {
                $html_file = str_replace('{files_title_text}', JText::_('JLIST_FE_FILELIST_TITLE_OVER_FILES_LIST'), $html_file);
                $html_file = str_replace('{files_title_end}', '', $html_file);
                $html_file = str_replace('{files_title_begin}', '', $html_file);
            } 
             // titel einsetzen
             // new in 1.4 beta (2) - new placeholder varables for full translation
             // in the moment 1.3 placeholder names are supported
             $html_file = str_replace('{license_title}', JText::_('JLIST_FE_FILELIST_LICENSE_TITLE'), $html_file);
             $html_file = str_replace('{price_title}', JText::_('JLIST_FE_FILELIST_PRICE_TITLE'), $html_file);
             $html_file = str_replace('{language_title}', JText::_('JLIST_FE_FILELIST_LANGUAGE_TITLE'), $html_file);
             $html_file = str_replace('{filesize_title}', JText::_('JLIST_FE_FILELIST_FILESIZE_TITLE'), $html_file);
             $html_file = str_replace('{system_title}', JText::_('JLIST_FE_FILELIST_SYSTEM_TITLE'), $html_file);
             $html_file = str_replace('{author_title}', JText::_('JLIST_FE_FILELIST_AUTHOR_TITLE'), $html_file);
             $html_file = str_replace('{author_url_title}', JText::_('JLIST_FE_FILELIST_AUTHOR_URL_TITLE'), $html_file);
             $html_file = str_replace('{created_date_title}', JText::_('JLIST_FE_FILELIST_CREATED_DATE_TITLE'), $html_file);
             $html_file = str_replace('{hits_title}', JText::_('JLIST_FE_FILELIST_HITS_TITLE'), $html_file);
             $html_file = str_replace('{created_by_title}', JText::_('JLIST_FE_FILELIST_CREATED_BY_TITLE'), $html_file);
             $html_file = str_replace('{modified_by_title}', JText::_('JLIST_FE_FILELIST_MODIFIED_BY_TITLE'), $html_file);
             $html_file = str_replace('{modified_date_title}', JText::_('JLIST_FE_FILELIST_MODIFIED_DATE_TITLE'), $html_file);
             $html_file = str_replace('{file_date_title}', JText::_('JLIST_FE_FILELIST_FILE_DATE_TITLE'), $html_file);
             $html_file = str_replace('{file_name}', $files[$i]->url_download, $html_file);
             
             // google adsense
             if ($jlistConfig['google.adsense.active']){
                 $html_file = str_replace('{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $html_file);
             } else {
                 $html_file = str_replace('{google_adsense}', '', $html_file);
             } 
             // report download link
             if ($jlistConfig['use.report.download.link']){
                $report_link = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=report&amp;cid=".$files[$i]->file_id).'">'.JText::_('JLIST_CONFIG_REPORT_FILE_LINK_TEXT').'</a>';
                if ($jlistConfig['report.link.only.regged'] && !$user->guest || !$jlistConfig['report.link.only.regged']) {
                   $html_file = str_replace('{report_link}', $report_link, $html_file);
                } else {
                   $html_file = str_replace('{report_link}', '', $html_file);
                }   
             } else {
                $html_file = str_replace('{report_link}', '', $html_file);
             }
            
             // view sum comments 
             if ($jlistConfig['view.sum.jcomments'] && $jlistConfig['jcomments.active']){
                 $database->setQuery('SELECT COUNT(*) from #__jcomments WHERE object_group = \'com_jdownloads\' AND object_id = '.$files[$i]->file_id);
                 $sum_comments = $database->loadResult();
                 if ($sum_comments){
                     $comments = sprintf(JText::_('JLIST_FRONTEND_JCOMMENTS_VIEW_SUM_TEXT'), $sum_comments); 
                     $html_file = str_replace('{sum_jcomments}', $comments, $html_file);
                 } else {
                    $html_file = str_replace('{sum_jcomments}', '', $html_file);
                 }
             } else {   
                 $html_file = str_replace('{sum_jcomments}', '', $html_file);
             }    
            
            if ($jlistConfig['view.detailsite']){
                // titel als link zur detailseite
                $titel_link = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=view.download&catid='.$cat[0]->cat_id.'&cid='.$files[$i]->file_id);
                $titel_link_text = '<a href="'.$titel_link.'">'.$files[$i]->file_title.'</a>';
                $detail_link_text = '<a href="'.$titel_link.'">'.JText::_('JLIST_FE_DETAILS_LINK_TEXT_TO_DETAILS').'</a>';
                // Symbol anzeigen - auch als url
                if ($files[$i]->file_pic != '' ) {
                    $fpicsize = $jlistConfig['file.pic.size'];
                    $filepic = '<a href="'.$titel_link.'">'.'<img src="'.JURI::base().'images/jdownloads/fileimages/'.$files[$i]->file_pic.'" align="top" width="'.$fpicsize.'" height="'.$fpicsize.'" border="0" alt="" /> </a>';
                } else {
                    $filepic = '';
                }
                $html_file = str_replace('{file_pic}',$filepic, $html_file);
                // link zu details am ende
                $html_file = str_replace('{link_to_details}','<br />'.$detail_link_text, $html_file);
                $html_file = str_replace('{file_title}', $titel_link_text, $html_file);
            } else {
                // no links
                if ($files[$i]->file_pic != '' ) {
                    $fpicsize = $jlistConfig['file.pic.size'];
                    $filepic = '<img src="'.JURI::base().'images/jdownloads/fileimages/'.$files[$i]->file_pic.'" align="top" width="'.$fpicsize.'" height="'.$fpicsize.'" border="0" alt="" />';
                } else {
                    $filepic = '';
                }
                $html_file = str_replace('{file_pic}',$filepic, $html_file);
                // link zu details am ende entfernen
                $html_file = str_replace('{link_to_details}', '', $html_file);
                $html_file = str_replace('{file_title}', $files[$i]->file_title, $html_file);
            }    

            if ($files[$i]->release == '' ) {
                $html_file = str_replace('{release}', '', $html_file);
            } else {
                $html_file = str_replace('{release}',JText::_('JLIST_FRONTEND_VERSION_TITLE').$files[$i]->release, $html_file);
            }

            // thumbnails
            $html_file = placeThumbs($html_file, $files[$i]->thumbnail, $files[$i]->thumbnail2, $files[$i]->thumbnail3);                                                    
                
             if ($jlistConfig['auto.file.short.description'] && $jlistConfig['auto.file.short.description.value'] > 0){
                 if (strlen($files[$i]->description) > $jlistConfig['auto.file.short.description.value']){ 
                     $shorted_text=preg_replace("/[^ ]*$/", '..', substr($files[$i]->description, 0, $jlistConfig['auto.file.short.description.value']));
                     $html_file = str_replace('{description}', $shorted_text, $html_file);
                 } else {
                     $html_file = str_replace('{description}', $files[$i]->description, $html_file);
                 }    
             } else {
                 $html_file = str_replace('{description}', $files[$i]->description, $html_file);
             }   

            // pics for: new file / hot file / updated
            $hotpic = '<img src="'.JURI::base().'images/jdownloads/hotimages/'.$jlistConfig['picname.is.file.hot'].'" alt="" />';
            $newpic = '<img src="'.JURI::base().'images/jdownloads/newimages/'.$jlistConfig['picname.is.file.new'].'" alt="" />';
            $updatepic = '<img src="'.JURI::base().'images/jdownloads/updimages/'.$jlistConfig['picname.is.file.updated'].'" alt="" />';

            if ($jlistConfig['loads.is.file.hot'] > 0 && $files[$i]->downloads >= $jlistConfig['loads.is.file.hot'] ){
                $html_file = str_replace('{pic_is_hot}', $hotpic, $html_file);
            } else {    
                $html_file = str_replace('{pic_is_hot}', '', $html_file);
            }
    
            // berechnung f�r NEW
            $tage_diff = DatumsDifferenz_JD(date('Y-m-d H:m:s'), $files[$i]->date_added);
            if ($jlistConfig['days.is.file.new'] > 0 && $tage_diff <= $jlistConfig['days.is.file.new']){
                $html_file = str_replace('{pic_is_new}', $newpic, $html_file);
            } else {    
                $html_file = str_replace('{pic_is_new}', '', $html_file);
            }
            
            // berechnung f�r UPDATED
            // view only when in download is set it to updated active
            if ($files[$i]->update_active) {
                $tage_diff = DatumsDifferenz_JD(date('Y-m-d H:m:s'), $files[$i]->modified_date);
                if ($jlistConfig['days.is.file.updated'] > 0 && $tage_diff >= 0 && $tage_diff <= $jlistConfig['days.is.file.updated']){
                    $html_file = str_replace('{pic_is_updated}', $updatepic, $html_file);
                } else {    
                    $html_file = str_replace('{pic_is_updated}', '', $html_file);
                }
            } else {
                $html_file = str_replace('{pic_is_updated}', '', $html_file);
            }    

            // mp3 player
            $filetype = strtolower(substr(strrchr($files[$i]->url_download, '.'), 1));
            if ($filetype == 'mp3'){
                $mp3_path =  JURI::base().$jlistConfig['files.uploaddir'].'/'.$cat[0]->cat_dir.'/'.$files[$i]->url_download;
                $mp3_config = trim($jlistConfig['mp3.player.config']);
                $mp3_config = str_replace('', '', $mp3_config);
                $mp3_config = str_replace(';', '&amp;', $mp3_config);
                $mp3_player =  
                '<object type="application/x-shockwave-flash" data="components/com_jdownloads/mp3_player_maxi.swf" width="200" height="20">
                <param name="movie" value="components/com_jdownloads/mp3_player_maxi.swf" />
                <param name=�wmode� value=�transparent�/>
                <param name="FlashVars" value="mp3='.$mp3_path.'&amp;'.$mp3_config.'" />
                </object>';   
                if ($jlistConfig['mp3.view.id3.info']){
                    // read mp3 infos
                    $mp3_path_abs = JPATH_BASE.DS.$jlistConfig['files.uploaddir'].DS.$cat[0]->cat_dir.DS.$files[$i]->url_download;
                    $info = getID3v2Tags($mp3_path_abs);
                    if ($info){
                        // add it
                        $mp3_info = stripslashes($jlistConfig['mp3.info.layout']);
                        $mp3_info = str_replace('{name_title}', JText::_('JLIST_FE_VIEW_ID3_TITLE'), $mp3_info);
                        $mp3_info = str_replace('{name}', $files[$i]->url_download, $mp3_info);
                        $mp3_info = str_replace('{album_title}', JText::_('JLIST_FE_VIEW_ID3_ALBUM'), $mp3_info);
                        $mp3_info = str_replace('{album}', $info[TALB], $mp3_info);
                        $mp3_info = str_replace('{artist_title}', JText::_('JLIST_FE_VIEW_ID3_ARTIST'), $mp3_info);
                        $mp3_info = str_replace('{artist}', $info[TPE1], $mp3_info);
                        $mp3_info = str_replace('{genre_title}', JText::_('JLIST_FE_VIEW_ID3_GENRE'), $mp3_info);
                        $mp3_info = str_replace('{genre}', $info[TCON], $mp3_info);
                        $mp3_info = str_replace('{year_title}', JText::_('JLIST_FE_VIEW_ID3_YEAR'), $mp3_info);
                        $mp3_info = str_replace('{year}', $info[TYER], $mp3_info);
                        $mp3_info = str_replace('{length_title}', JText::_('JLIST_FE_VIEW_ID3_LENGTH'), $mp3_info);
                        $mp3_info = str_replace('{length}', $info[TLEN].' '.JText::_('JLIST_FE_VIEW_ID3_MINS'), $mp3_info);
                        $html_file = str_replace('{mp3_id3_tag}', $mp3_info, $html_file); 
                    } else {
                        $html_file = str_replace('{mp3_id3_tag}', '', $html_file); 
                    }    
                } else {
                    $html_file = str_replace('{mp3_id3_tag}', '', $html_file);
                }       
                $html_file = str_replace('{mp3_player}', $mp3_player, $html_file);
            } else {
                $html_file = str_replace('{mp3_player}', '', $html_file);
                $html_file = str_replace('{mp3_id3_tag}', '', $html_file);             
            }
            // get license data and build link
            $lic = array();
            if ($files[$i]->license == '') $files[$i]->license = 0;
            $database->setQuery('SELECT * from #__jdownloads_license WHERE id = '.$files[$i]->license);
            $lic = $database->loadObject();
            $lic_data = '';
            if (!$lic->license_url == '') {
                $lic_data = $pic_license.'<a href="'.$lic->license_url.'" target="_blank" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_LICENCE').'">'.$lic->license_title.'</a> '.$extern_url_pic;
            } else {
                if (!$lic->license_title == '') {
                     if (!$lic->license_text == '') {
                          $lic_data = $pic_license.$lic->license_title;
                          $lic_data .= JHTML::_('tooltip', $lic->license_text, $lic->license_title);
                     } else {
                          $lic_data = $pic_license.$lic->license_title;
                     }
                } else {
                     $lic_data = '';
                }
            }
            $html_file = str_replace('{license}',$lic_data, $html_file);
            $html_file = str_replace('{license_text}',$lic_data, $html_file);
            
            // checkboxen nur anzeigen wenn user hat zugang und checkbox in layout aktiviert ( = 0 !! )
            if ( $user->get('aid') >= $access[1] && $jlistTemplates[2][0]->checkbox_off == 0 ) {
                 $html_file = str_replace('{checkbox_list}',$checkbox_list, $html_file);
            } else {
                 $html_file = str_replace('{checkbox_list}','', $html_file);
            }

			$html_file = str_replace('{cat_id}', $files[$i]->cat_id, $html_file);
			
            // file size
            if (!$files[$i]->size == '') {
                $html_file = str_replace('{size}', $pic_size.$files[$i]->size, $html_file);
                $html_file = str_replace('{filesize_value}', $pic_size.$files[$i]->size, $html_file);
            } else {
                $html_file = str_replace('{size}', '', $html_file);
                $html_file = str_replace('{filesize_value}', '', $html_file);
            }
            
            // price
            if ($files[$i]->price != '') {
                $html_file = str_replace('{price_value}', $pic_price.$files[$i]->price, $html_file);
            } else {
                $html_file = str_replace('{price_value}', '', $html_file);
            }

            // file_date
            if ($files[$i]->file_date != '0000-00-00 00:00:00') {
                 $filedate_data = $pic_date.substr(JHTML::_('date',$files[$i]->file_date, $jlistConfig['global.datetime'], $offset = NULL),0,10);
            } else {
                 $filedate_data = '';
            }
            $html_file = str_replace('{file_date}',$filedate_data, $html_file);
            
            // date_added
            if ($files[$i]->date_added != '0000-00-00 00:00:00') {
                 $date_data = $pic_date.substr(JHTML::_('date',$files[$i]->date_added, $jlistConfig['global.datetime'], $offset = NULL),0,10);
            } else {
                 $date_data = '';
            }
			$html_file = str_replace('{date_added}',$date_data, $html_file);
            $html_file = str_replace('{created_date_value}',$date_data, $html_file);
            
            $html_file = str_replace('{created_by_value}',$files[$i]->created_by, $html_file);
            $html_file = str_replace('{modified_by_value}',$files[$i]->modified_by, $html_file);
            
            // modified_date
            if ($files[$i]->modified_date != '0000-00-00 00:00:00') {
                $modified_data = $pic_date.substr(JHTML::_('date',$files[$i]->modified_date, $jlistConfig['global.datetime'], $offset = NULL),0,10);
            } else {
                $modified_data = '';
            }
            $html_file = str_replace('{modified_date_value}',$modified_data, $html_file);

            // only view download-url when user has corect access level
            if ($user->get('aid') >= $access[1]) {
                $blank_window = '';
                $blank_window1 = '';
                $blank_window2 = '';
                // get file extension
                $view_types = array();
                $view_types = explode(',', $jlistConfig['file.types.view']);
                $only_file_name = basename($files[$i]->url_download);
                $fileextension = strtolower(substr(strrchr($only_file_name,"."),1));
                if (in_array($fileextension, $view_types)){
                    $blank_window = 'target="_blank"';
                }    
                // check is set link to a new window?
                if ($files[$i]->extern_file && $files[$i]->extern_site   ){
                    $blank_window = 'target="_blank"';
                }

                 // direct download ohne zusammenfassung?
                 if ($jlistConfig['direct.download'] == '0'){
                     $url_task = 'summary';
                 } else {
                     $url_task = 'finish';
                 }                    
                 $download_link = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view='.$url_task.'&cid='.$files[$i]->file_id.'&catid='.$files[$i]->cat_id); 
                  if ($url_task == 'finish'){ 
                      $download_link_text = '<a '.$blank_window.' href="'.$download_link.'" title="'.JText::_('JLIST_LINKTEXT_DOWNLOAD_URL').'" class="jd_download_url">';
                  } else {
                      $download_link_text = '<a href="'.$download_link.'" title="'.JText::_('JLIST_LINKTEXT_DOWNLOAD_URL').'">';                  
                  }    
				 if (!$pic_download){
                     $pic_download = '<img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.files'].'" align="middle" border="0" alt="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_DOWNLOAD').'" />';
                 }    
                 if ($jlistConfig['view.also.download.link.text']){
                    $html_file = str_replace('{url_download}',$download_link_text.$pic_download.'<br />'.JText::_('JLIST_LINKTEXT_DOWNLOAD_URL').'</a>', $html_file);
			     } else {
                    $html_file = str_replace('{url_download}',$download_link_text.$pic_download.'</a>', $html_file);  
                 }    
                // mirrors
                if ($files[$i]->mirror_1) {
                    if ($files[$i]->extern_site_mirror_1 && $url_task == 'finish'){
                        $blank_window1 = 'target="_blank"';
                    }
                    $mirror1_link_dum = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view='.$url_task.'&cid='.$files[$i]->file_id.'&catid='.$files[$i]->cat_id.'&m=1');
                    $mirror1_link = JRoute::_('<a '.$blank_window1.' href="'.$mirror1_link_dum.'" class="jd_download_url" title="'.JText::_('JLIST_FRONTEND_MIRROR_URL_TITLE_1').'">');
                    $mir1_down_pic = '<img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.mirror_1'].'" align="middle" border="0" alt="'.JText::_('JLIST_FRONTEND_MIRROR_URL_TITLE_1').'" title="'.JText::_('JLIST_FRONTEND_MIRROR_URL_TITLE_1').'" />';
                    $html_file = str_replace('{mirror_1}', $mirror1_link.$mir1_down_pic.'</a>', $html_file);
                } else {
                    $html_file = str_replace('{mirror_1}', '', $html_file);
                }
                if ($files[$i]->mirror_2) {
                    if ($files[$i]->extern_site_mirror_2 && $url_task == 'finish'){
                        $blank_window2 = 'target="_blank"';
                    }
                    $mirror2_link_dum = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view='.$url_task.'&cid='.$files[$i]->file_id.'&catid='.$files[$i]->cat_id.'&m=2');
                    $mirror2_link = '<a '.$blank_window2.' href="'.$mirror2_link_dum.'" class="jd_download_url" title="'.JText::_('JLIST_FRONTEND_MIRROR_URL_TITLE_2').'">';
                    $mir2_down_pic = '<img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.mirror_2'].'" align="middle" border="0" alt="'.JText::_('JLIST_FRONTEND_MIRROR_URL_TITLE_2').'" title="'.JText::_('JLIST_FRONTEND_MIRROR_URL_TITLE_2').'" />';                
                    $html_file = str_replace('{mirror_2}', $mirror2_link.$mir2_down_pic.'</a>', $html_file);
                } else {
                    $html_file = str_replace('{mirror_2}', '', $html_file);
                }            
            } else {
			     $html_file = str_replace('{url_download}', '', $html_file);
                 $html_file = str_replace('{mirror_1}', '', $html_file); 
                 $html_file = str_replace('{mirror_2}', '', $html_file); 
            }
            
            // build website url
            if (!$files[$i]->url_home == '') {
                 if (strpos($files[$i]->url_home, 'http://') !== false) {    
                     $html_file = str_replace('{url_home}',$pic_website.'<a href="'.$files[$i]->url_home.'" target="_blank" title="'.JText::_('JLIST_FRONTEND_HOMEPAGE').'">'.JText::_('JLIST_FRONTEND_HOMEPAGE').'</a> '.$extern_url_pic, $html_file);
                     $html_file = str_replace('{author_url_text} ',$pic_website.'<a href="'.$files[$i]->url_home.'" target="_blank" title="'.JText::_('JLIST_FRONTEND_HOMEPAGE').'">'.JText::_('JLIST_FRONTEND_HOMEPAGE').'</a> '.$extern_url_pic, $html_file);
                 } else {
                     $html_file = str_replace('{url_home}',$pic_website.'<a href="http://'.$files[$i]->url_home.'" target="_blank" title="'.JText::_('JLIST_FRONTEND_HOMEPAGE').'">'.JText::_('JLIST_FRONTEND_HOMEPAGE').'</a> '.$extern_url_pic, $html_file);
                     $html_file = str_replace('{author_url_text}',$pic_website.'<a href="http://'.$files[$i]->url_home.'" target="_blank" title="'.JText::_('JLIST_FRONTEND_HOMEPAGE').'">'.JText::_('JLIST_FRONTEND_HOMEPAGE').'</a> '.$extern_url_pic, $html_file);
                 }    
            } else {
                $html_file = str_replace('{url_home}', '', $html_file);
                $html_file = str_replace('{author_url_text}', '', $html_file);
            }

            // encode is link a mail
            if (strpos($files[$i]->url_author, '@') && $jlistConfig['mail.cloaking']){
                if (!$files[$i]->author) { 
                    $mail_encode = JHTML::_('email.cloak', $files[$i]->url_author);
                } else {
                    $mail_encode = JHTML::_('email.cloak',$files[$i]->url_author, true, $files[$i]->author, false);
                }        
            }
                    
            // build author link
            if ($files[$i]->author <> ''){
                if ($files[$i]->url_author <> '') {
                    if ($mail_encode) {
                        $link_author = $pic_author.' '.$mail_encode;
                    } else {
                        if (strpos($files[$i]->url_author, 'http://') !== false) {    
                            $link_author = $pic_author.'<a href="'.$files[$i]->url_author.'" target="_blank">'.$files[$i]->author.'</a> '.$extern_url_pic;
                        } else {
                            $link_author = $pic_author.'<a href="http://'.$files[$i]->url_author.'" target="_blank">'.$files[$i]->author.'</a> '.$extern_url_pic;
                        }        
                    }
                    $html_file = str_replace('{author}',$link_author, $html_file);
                    $html_file = str_replace('{author_text}',$link_author, $html_file);
                    $html_file = str_replace('{url_author}', '', $html_file);
                } else {
                    $link_author = $pic_author.$files[$i]->author;
                    $html_file = str_replace('{author}',$link_author, $html_file);
                    $html_file = str_replace('{author_text}',$link_author, $html_file);
                    $html_file = str_replace('{url_author}', '', $html_file);
                }
            } else {
                    $html_file = str_replace('{url_author}', $pic_author.$files[$i]->url_author, $html_file);
        	        $html_file = str_replace('{author}','', $html_file);
                    $html_file = str_replace('{author_text}','', $html_file); 
            }

            // set system value
            $file_sys_values = explode(',' , $jlistConfig['system.list']);
			if ($files[$i]->system == 0 ) {
                $html_file = str_replace('{system}', '', $html_file);
                 $html_file = str_replace('{system_text}', '', $html_file); 
            } else {
                $html_file = str_replace('{system}', $pic_system.$file_sys_values[$files[$i]->system], $html_file);
                $html_file = str_replace('{system_text}', $pic_system.$file_sys_values[$files[$i]->system], $html_file);
            }

            // set language value
            $file_lang_values = explode(',' , $jlistConfig['language.list']);
			if ($files[$i]->language == 0 ) {
                $html_file = str_replace('{language}', '', $html_file);
                $html_file = str_replace('{language_text}', '', $html_file);
            } else {
                $html_file = str_replace('{language}', $pic_language.$file_lang_values[$files[$i]->language], $html_file);
                $html_file = str_replace('{language_text}', $pic_language.$file_lang_values[$files[$i]->language], $html_file);
            }

            // insert rating system
            if ($jlistConfig['view.ratings']){
                $rating_system = getRatings($files[$i]->file_id);
                $html_file = str_replace('{rating}', $rating_system, $html_file);
            } else {
                $html_file = str_replace('{rating}', '', $html_file);
            }  			
            
            $html_file = str_replace('{downloads}',$pic_downloads.$files[$i]->downloads, $html_file);
            $html_file = str_replace('{hits_value}',$pic_downloads.$files[$i]->downloads, $html_file);
			$html_file = str_replace('{ordering}',$files[$i]->ordering, $html_file);
			$html_file = str_replace('{published}',$files[$i]->published, $html_file);
            
            // support for content plugins 
            $html_file = JHTML::_('content.prepare', $html_file);
			$html_files .= $html_file;
    	}

		// nur anzeigen wenn files vorhanden
        if (!empty($files)) {
            $html_cat = str_replace('{files}',$html_files,$html_cat);
        } else {
            $no_files_msg = '<br /><b> '.JText::_('JLIST_FRONTEND_NOFILES').'<br /><br /></b>';            
            $html_cat = str_replace('{files}', $no_files_msg, $html_cat);
        }    

        // top checkbox nur anzeigen wenn user hat zugang
        if ($user->get('aid') >= $access[1]) {
            $checkbox_top = '<tr><form name="down'.$formid.'" action="'.JURI::base().'index.php?option=com_jdownloads&amp;Itemid='.$Itemid.'&amp;view=summary'.'
                    " onsubmit="return pruefen('.$formid.',\''.JText::_('JLIST_JAVASCRIPT_TEXT_1').'\',\''.JText::_('JLIST_JAVASCRIPT_TEXT_2').'\');" method="post">
                    <td width="90%" align="right">'.$jlistConfig['checkbox.top.text'].'</td>
                    <td width="10%" align="center"><input type="checkbox" name="toggle"
                    value="" onclick="checkAlle('.$i.','.$formid.');" /></td></tr>';
            
            // top checkboxen nur anzeigen wenn im layout aktiviert
            if ($jlistTemplates[2][0]->checkbox_off == 0 && !empty($files)) {
               $html_cat = str_replace('{checkbox_top}', $checkbox_top, $html_cat);
            } else {
               $html_cat = str_replace('{checkbox_top}', '', $html_cat);
            }   
        } else {
            if ($jlistTemplates[2][0]->checkbox_off == 0){
                $html_cat = str_replace('{checkbox_top}', '<b>'.JText::_('JLIST_FRONTEND_CAT_ACCESS_REGGED').'</b>', $html_cat);
            } else {
                $html_cat = str_replace('{checkbox_top}', '', $html_cat);
            }        
        }
                
        $form_hidden = '<input type="hidden" name="boxchecked" value=""/> ';
        $html_cat = str_replace('{form_hidden}', $form_hidden, $html_cat);
        $html_cat .= '<input type="hidden" name="catid" value="'.$catid.'"/>';
        $html_cat .= '</form>';

        $html_cat .= '<script type="text/javascript" src="'.JURI::base().'includes/js/overlib_mini.js"></script>';

        // button nur anzeigen wenn checkboxen aktiviert
        $button = '';
        
        // only view submit button when user has corect access level
        if ($user->get('aid') >= $access[1] && $jlistTemplates[2][0]->checkbox_off == 0 && !empty($files)) {
            $html_cat = str_replace('{form_button}', $button, $html_cat);
        } else {
            $html_cat = str_replace('{form_button}', '', $html_cat);
        } 
    	$footer =  makeFooter(true, false, true, $sum_pages, $limit, $limitstart, $site_aktuell); 
        $html_cat .= $footer;
        
        if ( !$jlistConfig['offline'] ) {
            echo $html_cat;
        } else {
            if ($user->get('aid') == 2) {
                echo JText::_('JLIST_BACKEND_OFFLINE_ADMIN_MESSAGE_TEXT');
                echo $html_cat;
            } else {
                $html_off = '<br /><br />'.stripslashes($jlistConfig['offline.text']).'<br /><br />';
                $html_off .= $footer;
                echo $html_off;
            }
        }    
}


/* #################################################################################
/  Einzelnen Download anzeigen mit Detail Infos
/  ################################################################################# */
function showDownload($option, $file, $cat, $access){
    global $jlistConfig, $jlistTemplates, $Itemid, $mainframe, $addScriptJWAjaxVote, $page_title;
    $user = &JFactory::getUser();
    $database = &JFactory::getDBO();
    $mainframe = JFactory::getApplication();
    $app = JFactory::getApplication();

    $mainframe->setPageTitle($page_title.' - '.$cat->cat_title.' - '.$file->file_title );
    $mainframe->appendMetaTag( 'keywords' , strip_tags($file->metakey ));  
    $mainframe->appendMetaTag( 'description' , strip_tags($file->metadesc)); 

    $html_file = makeHeader($html_file, true, false, false, 0, true, false, false, false, false, 0, 0, 0, 0, 0);
    echo $html_file;        
    
    $html_file = str_replace('{price_value}',$file->price, $jlistTemplates[5][0]->template_text);
    // titel einsetzen
    $html_file = str_replace('{pathway_text}', JText::_('JLIST_FE_DETAILS_PATHWAY_TEXT'), $html_file);
    $html_file = str_replace('{details_block_title}', JText::_('JLIST_FE_DETAILS_DATA_BLOCK_TITLE'), $html_file);
    $html_file = str_replace('{license_title}', JText::_('JLIST_FE_DETAILS_LICENSE_TITLE'), $html_file);
    $html_file = str_replace('{price_title}', JText::_('JLIST_FE_DETAILS_PRICE_TITLE'), $html_file);
    $html_file = str_replace('{language_title}', JText::_('JLIST_FE_DETAILS_LANGUAGE_TITLE'), $html_file);
    $html_file = str_replace('{filesize_title}', JText::_('JLIST_FE_DETAILS_FILESIZE_TITLE'), $html_file);
    $html_file = str_replace('{system_title}', JText::_('JLIST_FE_DETAILS_SYSTEM_TITLE'), $html_file);
    $html_file = str_replace('{author_title}', JText::_('JLIST_FE_DETAILS_AUTHOR_TITLE'), $html_file);
    $html_file = str_replace('{author_url_title}', JText::_('JLIST_FE_DETAILS_AUTHOR_URL_TITLE'), $html_file);
    $html_file = str_replace('{created_date_title}', JText::_('JLIST_FE_DETAILS_CREATED_DATE_TITLE'), $html_file);
    $html_file = str_replace('{hits_title}', JText::_('JLIST_FE_DETAILS_HITS_TITLE'), $html_file);
    $html_file = str_replace('{created_by_title}', JText::_('JLIST_FE_DETAILS_CREATED_BY_TITLE'), $html_file);
    $html_file = str_replace('{modified_by_title}', JText::_('JLIST_FE_DETAILS_MODIFIED_BY_TITLE'), $html_file);
    $html_file = str_replace('{modified_date_title}', JText::_('JLIST_FE_DETAILS_MODIFIED_DATE_TITLE'), $html_file);
    $html_file = str_replace('{file_date_title}', JText::_('JLIST_FE_DETAILS_FILE_DATE_TITLE'), $html_file);
    $html_file = str_replace('{file_name}', $file->url_download, $html_file);
    
    // google adsense
    if ($jlistConfig['google.adsense.active']){
       $html_file = str_replace('{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $html_file);
    } else {
       $html_file = str_replace('{google_adsense}', '', $html_file);
    }
    // report download link
    if ($jlistConfig['use.report.download.link']){
       $report_link = '<a href="'.JRoute::_("index.php?option=com_jdownloads&amp;Itemid=".$Itemid."&amp;view=report&amp;cid=".$file->file_id).'" rel="nofollow">'.JText::_('JLIST_CONFIG_REPORT_FILE_LINK_TEXT').'</a>';
       if ($jlistConfig['report.link.only.regged'] && !$user->guest || !$jlistConfig['report.link.only.regged']) {
           $html_file = str_replace('{report_link}', $report_link, $html_file);
       } else {
           $html_file = str_replace('{report_link}', '', $html_file);
       } 
    } else {
       $html_file = str_replace('{report_link}', '', $html_file);
    }
    
    $database->setQuery('SELECT cat_title from #__jdownloads_cats WHERE cat_id = '.$file->cat_id);
    $cattitle = $database->loadResult();
    $html_file = str_replace('{cat_title}', $cattitle, $html_file);      
    
    // a little pic for extern links
    $extern_url_pic = '<img src="'.JURI::base().'components/com_jdownloads/images/link_extern.gif" alt="" />';
     
    // f�r JHMTL Tooltip
    JHTML::_('behavior.tooltip');
    
    // get pic
    if ($file->file_pic != '' ) {
        $fpicsize = $jlistConfig['file.pic.size'];
        $filepic = '<img src="'.JURI::base().'images/jdownloads/fileimages/'.$file->file_pic.'" align="top" width="'.$fpicsize.'" height="'.$fpicsize.'" border="0" alt="" /> ';
    } else {
        $filepic = '';
    }
    $html_file = str_replace('{file_pic}',$filepic, $html_file);
    $html_file = str_replace('{file_title}', $file->file_title, $html_file);    
    
    if ($file->release) {
        $html_file = str_replace('{release}',JText::_('JLIST_FRONTEND_VERSION_TITLE').$file->release, $html_file);        
    } else {
        $html_file = str_replace('{release}', '', $html_file);        
    }

    // thumbnails
    $html_file = placeThumbs($html_file, $file->thumbnail, $file->thumbnail2, $file->thumbnail3);                                                    

    // description
    if (!$file->description_long){
        $html_file = str_replace('{description_long}',$file->description, $html_file);
    } else {
        $html_file = str_replace('{description_long}',$file->description_long, $html_file);
    }    
    // pics for: new file / hot file /updated
    $hotpic = '<img src="'.JURI::base().'images/jdownloads/hotimages/'.$jlistConfig['picname.is.file.hot'].'" alt="" />';
    $newpic = '<img src="'.JURI::base().'images/jdownloads/newimages/'.$jlistConfig['picname.is.file.new'].'" alt="" />';
    $updatepic = '<img src="'.JURI::base().'images/jdownloads/updimages/'.$jlistConfig['picname.is.file.updated'].'" alt="" />';

    // berechnung f�r HOT
    if ($jlistConfig['loads.is.file.hot'] > 0 && $file->downloads >= $jlistConfig['loads.is.file.hot'] ){
        $html_file = str_replace('{pic_is_hot}', $hotpic, $html_file);
    } else {    
        $html_file = str_replace('{pic_is_hot}', '', $html_file);
    }
    
    // berechnung f�r NEW
    $tage_diff = DatumsDifferenz_JD(date('Y-m-d H:m:s'), $file->date_added);
    if ($jlistConfig['days.is.file.new'] > 0 && $tage_diff <= $jlistConfig['days.is.file.new']){
        $html_file = str_replace('{pic_is_new}', $newpic, $html_file);
    } else {    
        $html_file = str_replace('{pic_is_new}', '', $html_file);
    }
    
    // berechnung f�r UPDATED
    // view only when in download is set it to updated active
    if ($file->update_active) {
        $tage_diff = DatumsDifferenz_JD(date('Y-m-d H:m:s'), $file->modified_date);
        if ($jlistConfig['days.is.file.updated'] > 0 && $tage_diff >= 0 && $tage_diff <= $jlistConfig['days.is.file.updated']){
            $html_file = str_replace('{pic_is_updated}', $updatepic, $html_file);
        } else {    
            $html_file = str_replace('{pic_is_updated}', '', $html_file);
        }
    } else {
       $html_file = str_replace('{pic_is_updated}', '', $html_file);
    }    

    // mp3 player
    if ($file->extern_file){
      $extern_mp3 = true;
      $filetype = strtolower(substr(strrchr($file->extern_file, '.'), 1));
    } else {    
      $filetype = strtolower(substr(strrchr($file->url_download, '.'), 1));
      $extern_mp3 = false;
    }  
    if ($filetype == 'mp3'){
        if ($extern_mp3){
            $mp3_path = $file->extern_file;
        } else {        
            $mp3_path =  JURI::base().$jlistConfig['files.uploaddir'].'/'.$cat->cat_dir.'/'.$file->url_download;
        }    
                $mp3_config = trim($jlistConfig['mp3.player.config']);
                $mp3_config = str_replace('', '', $mp3_config);
                $mp3_config = str_replace(';', '&amp;', $mp3_config);
                $mp3_player =  
                '<object type="application/x-shockwave-flash" data="components/com_jdownloads/mp3_player_maxi.swf" width="200" height="20">
                <param name="movie" value="components/com_jdownloads/mp3_player_maxi.swf" />
                <param name=�wmode� value=�transparent�/>
                <param name="FlashVars" value="mp3='.$mp3_path.'&amp;'.$mp3_config.'" />
                </object>';   
        if ($jlistConfig['mp3.view.id3.info'] && !$extern_mp3){
               // read mp3 infos
                $mp3_path_abs = JPATH_BASE.DS.$jlistConfig['files.uploaddir'].DS.$cat->cat_dir.DS.$file->url_download;
                $info = getID3v2Tags($mp3_path_abs);
                if ($info){
                        // add it
                        $mp3_info = stripslashes($jlistConfig['mp3.info.layout']);
                        $mp3_info = str_replace('{name_title}', JText::_('JLIST_FE_VIEW_ID3_TITLE'), $mp3_info);
                        $mp3_info = str_replace('{name}', $file->url_download, $mp3_info);
                        $mp3_info = str_replace('{album_title}', JText::_('JLIST_FE_VIEW_ID3_ALBUM'), $mp3_info);
                        $mp3_info = str_replace('{album}', $info[TALB], $mp3_info);
                        $mp3_info = str_replace('{artist_title}', JText::_('JLIST_FE_VIEW_ID3_ARTIST'), $mp3_info);
                        $mp3_info = str_replace('{artist}', $info[TPE1], $mp3_info);
                        $mp3_info = str_replace('{genre_title}', JText::_('JLIST_FE_VIEW_ID3_GENRE'), $mp3_info);
                        $mp3_info = str_replace('{genre}', $info[TCON], $mp3_info);
                        $mp3_info = str_replace('{year_title}', JText::_('JLIST_FE_VIEW_ID3_YEAR'), $mp3_info);
                        $mp3_info = str_replace('{year}', $info[TYER], $mp3_info);
                        $mp3_info = str_replace('{length_title}', JText::_('JLIST_FE_VIEW_ID3_LENGTH'), $mp3_info);
                        $mp3_info = str_replace('{length}', $info[TLEN].' '.JText::_('JLIST_FE_VIEW_ID3_MINS'), $mp3_info);
                        $html_file = str_replace('{mp3_id3_tag}', $mp3_info, $html_file); 
                    } else {
                        $html_file = str_replace('{mp3_id3_tag}', '', $html_file); 
                    }    
                } else {
                    $html_file = str_replace('{mp3_id3_tag}', '', $html_file);
                }        
                $html_file = str_replace('{mp3_player}', $mp3_player, $html_file);
            } else {
                $html_file = str_replace('{mp3_player}', '', $html_file);
                $html_file = str_replace('{mp3_id3_tag}', '', $html_file);             
    }
    
    // get license data and build link
    $lic = array();
    if ($file->license == '') $file->license = 0;
    $database->setQuery('SELECT * from #__jdownloads_license WHERE id = '.$file->license);
    $lic = $database->loadObject();
    $lic_data = '';
    if (!$lic->license_url == '') {
         $lic_data = $pic_license.'<a href="'.$lic->license_url.'" target="_blank" title="'.JText::_('JLIST_FRONTEND_MINI_ICON_ALT_LICENCE').'">'.$lic->license_title.'</a> '.$extern_url_pic;
    } else {
        if (!$lic->license_title == '') {
             if (!$lic->license_text == '') {
                  $lic_data = $pic_license.$lic->license_title;
                  $lic_data .= JHTML::_('tooltip', $lic->license_text, $lic->license_title);
             } else {
                  $lic_data = $pic_license.$lic->license_title;
             }
        } else {
            $lic_data = '';
        }
    }
    $html_file = str_replace('{license_text}',$lic_data, $html_file);
    $html_file = str_replace('{filesize_value}',$file->size, $html_file);
    $html_file = str_replace('{created_by_value}',$file->created_by, $html_file);
    $html_file = str_replace('{modified_by_value}',$file->modified_by, $html_file);
    if ($file->modified_date != '0000-00-00 00:00:00') {
        $modified_data = $pic_date.substr(JHTML::_('date',$file->modified_date, $jlistConfig['global.datetime'], $offset = NULL),0,10);
    } else {
        $modified_data = '';
    }
    $html_file = str_replace('{modified_date_value}',$modified_data, $html_file);
    
    // funktion zur berechnung entfernt - hier nur falls vorhanden platzhalter entfernen
    $html_file = str_replace('{download_time}','', $html_file);    

    // file_date
    if ($file->file_date != '0000-00-00 00:00:00') {
         $filedate_data = $pic_date.substr(JHTML::_('date',$file->file_date, $jlistConfig['global.datetime'], $offset = NULL),0,10);
    } else {
         $filedate_data = '';
    }
    $html_file = str_replace('{file_date}',$filedate_data, $html_file);

    // date_added    
    if ($file->date_added != '0000-00-00 00:00:00') {
        $date_data = $pic_date.substr(JHTML::_('date',$file->date_added, $jlistConfig['global.datetime'], $offset = NULL),0,10);
    } else {
        $date_data = '';
    }
    $html_file = str_replace('{created_date_value}',$date_data, $html_file);

    // only view download link when user has corect access level
    if ($user->get('aid') >= $access[1]) {
        $blank_window = '';
        $blank_window1 = '';
        $blank_window2 = '';
        // get file extension
        $view_types = array();
        $view_types = explode(',', $jlistConfig['file.types.view']);
        $only_file_name = basename($file->url_download);
        $fileextension = strtolower(substr(strrchr($only_file_name,"."),1));
        if (in_array($fileextension, $view_types)){
            $blank_window = 'target="_blank"';
        }    
        // check is set link to a new window?
        if ($file->extern_file && $file->extern_site   ){
            $blank_window = 'target="_blank"';
        }
        // direct download ohne zusammenfassung?
        if ($jlistConfig['direct.download'] == '0'){ 
            $url_task = 'summary';
        } else {
            $url_task = 'finish';
        }
        $download_link = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view='.$url_task.'&cid='.$file->file_id.'&catid='.$file->cat_id);
        if ($url_task == 'finish'){
            $download_link_text = '<a '.$blank_window.' href="'.$download_link.'" class="jd_download_url"><img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.details'].'" border="0" alt="'.JText::_('JLIST_LINKTEXT_DOWNLOAD_URL').'" /></a>';
        } else {
            $download_link_text = '<a href="'.$download_link.'" class="jd_download_url"><img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.details'].'" border="0" alt="'.JText::_('JLIST_LINKTEXT_DOWNLOAD_URL').'" /></a>';
        }
        $html_file = str_replace('{url_download}',$pic_download.$download_link_text, $html_file);
        
        // mirrors
        if ($file->mirror_1) {
            if ($file->extern_site_mirror_1 && $url_task == 'finish'){
                $blank_window1 = 'target="_blank"';
            }
            $mirror1_link_dum = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view='.$url_task.'&cid='.$file->file_id.'&catid='.$file->cat_id.'&m=1');
            $mirror1_link = '<a '.$blank_window1.' href="'.$mirror1_link_dum.'" class="jd_download_url"><img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.mirror_1'].'" border="0" alt="'.JText::_('JLIST_FRONTEND_MIRROR_URL_TITLE_1').'" /></a>';
            $html_file = str_replace('{mirror_1}', $mirror1_link, $html_file);
        } else {
            $html_file = str_replace('{mirror_1}', '', $html_file);
        }
        if ($file->mirror_2) {
            if ($file->extern_site_mirror_2 && $url_task == 'finish'){
                $blank_window2 = 'target="_blank"';
            }            
            $mirror2_link_dum = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view='.$url_task.'&cid='.$file->file_id.'&catid='.$file->cat_id.'&m=2');
            $mirror2_link = '<a '.$blank_window2.' href="'.$mirror2_link_dum.'" class="jd_download_url"><img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.mirror_2'].'" border="0" alt="'.JText::_('JLIST_FRONTEND_MIRROR_URL_TITLE_2').'" /></a>';
            $html_file = str_replace('{mirror_2}', $mirror2_link, $html_file);
        } else {
            $html_file = str_replace('{mirror_2}', '', $html_file);
        }            
    } else {
        //Infotext kein zugriff 
        $regg = str_replace('<br />', '', JText::_('JLIST_FRONTEND_CAT_ACCESS_REGGED')); 
        $html_file = str_replace('{url_download}', $regg, $html_file);
        $html_file = str_replace('{mirror_1}', '', $html_file); 
        $html_file = str_replace('{mirror_2}', '', $html_file); 
    }    
    

    // build website url
    if (!$file->url_home == '') {
         if (strpos($file->url_home, 'http://') !== false) {    
             $html_file = str_replace('{author_url_text}',$pic_website.'<a href="'.$file->url_home.'" target="_blank" title="'.JText::_('JLIST_FRONTEND_HOMEPAGE').'">'.JText::_('JLIST_FRONTEND_HOMEPAGE').'</a> '.$extern_url_pic, $html_file);
         } else {
             $html_file = str_replace('{author_url_text}',$pic_website.'<a href="http://'.$file->url_home.'" target="_blank" title="'.JText::_('JLIST_FRONTEND_HOMEPAGE').'">'.JText::_('JLIST_FRONTEND_HOMEPAGE').'</a> '.$extern_url_pic, $html_file);
         }    
    } else {
        $html_file = str_replace('{author_url_text}', '', $html_file);
    }

    // encode is link a mail
    $link_author = '';
    if (strpos($file->url_author, '@') && $jlistConfig['mail.cloaking']){
        if (!$file->author) { 
            $mail_encode = JHTML::_('email.cloak',$file->url_author);
        } else {
            $mail_encode = JHTML::_('email.cloak',$file->url_author, true, $file->author, false);
        }        
    }
                    
    // build author link
    if ($file->author <> ''){
         if ($file->url_author <> '') {
              if ($mail_encode) {
                  $link_author = $mail_encode;
              } else {
                  if (strpos($file->url_author, 'http://') !== false) {
                     $link_author = $pic_author.'<a href="'.$file->url_author.'" target="_blank">'.$file->author.'</a> '.$extern_url_pic;
                  } else {
                     $link_author = $pic_author.'<a href="http://'.$file->url_author.'" target="_blank">'.$file->author.'</a>  '.$extern_url_pic;
                  }        
              }
              $html_file = str_replace('{author_text}',$link_author, $html_file);
              $html_file = str_replace('{url_author}', '', $html_file);
         } else {
              $link_author = $pic_author.$file->author;
              $html_file = str_replace('{author_text}',$link_author, $html_file);
              $html_file = str_replace('{url_author}', '', $html_file);
         }
    } else {
        $html_file = str_replace('{url_author}', $pic_author.$file->url_author, $html_file);
        $html_file = str_replace('{author_text}','', $html_file);
    }

    // set system value
    $file_sys_values = explode(',' , $jlistConfig['system.list']);
    if ($file->system == 0 ) {
         $html_file = str_replace('{system_text}', '', $html_file);
    } else {
         $html_file = str_replace('{system_text}', $pic_system.$file_sys_values[$file->system], $html_file);
    }

    // set language value
    $file_lang_values = explode(',' , $jlistConfig['language.list']);
    if ($file->language == 0 ) {
        $html_file = str_replace('{language_text}', '', $html_file);
    } else {
        $html_file = str_replace('{language_text}', $pic_language.$file_lang_values[$file->language], $html_file);
    }
    $html_file = str_replace('{hits_value}',$file->downloads, $html_file);
     
    // Option for JComments integration
    if ($jlistConfig['jcomments.active']){
        $jcomments = $mainframe->getCfg('absolute_path') . '/components/com_jcomments/jcomments.php';
        if (file_exists($jcomments)) {
            require_once($jcomments);
            $obj_id = $file->file_id;
            $obj_title = $file->file_title;
            $html_file .= JComments::showComments($obj_id, 'com_jdownloads', $obj_title);
        }    
    }
    
    // Option for JomComment integration
    if ($jlistConfig['view.jom.comment']){
       if (file_exists(JPATH_PLUGINS . DS . 'content' . DS . 'jom_comment_bot.php')){
           include_once( JPATH_PLUGINS . DS . 'content' . DS . 'jom_comment_bot.php' );
           $html_file .= jomcomment($file->file_id, "com_jdownloads");
       }    
    }    
    
    // insert rating system
    if ($jlistConfig['view.ratings']){
        $rating_system = getRatings($file->file_id);
        $html_file = str_replace('{rating}', $rating_system, $html_file);
    } else {
        $html_file = str_replace('{rating}', '', $html_file);
    }    
    
    $footer = makeFooter(true, false, false, $sum_pages, $limit, $limitstart, $site_aktuell);  
    $html_file .= $footer;
    // support for content plugins
    $html_file = JHTML::_('content.prepare', $html_file);
    if ( !$jlistConfig['offline'] ) {
            echo $html_file;
        } else {
            if ($user->get('aid') == 2) {
                echo JText::_('JLIST_BACKEND_OFFLINE_ADMIN_MESSAGE_TEXT');
                echo $html_file;
            } else {
                $html_off = '<br /><br />'.stripslashes($jlistConfig['offline.text']).'<br /><br />';
                $html_off .= $footer;
                echo $html_off;
            }
        }
}    

/* #################################################################################
/  View Summary page with link to Download
/  ################################################################################# */
function Summary($option, $marked_files_id, $mail_files, $filename, $download_link, $del_ok, $extern_site, $sum_aup_points, $has_licenses, $open_in_blank_page){
    global $jlistConfig, $jlistTemplates, $Itemid, $mainframe, $page_title;
    $user = &JFactory::getUser();
    $database = &JFactory::getDBO();
    
    $mainframe->setPageTitle($page_title.' - '.JText::_('JLIST_BACKEND_TEMP_TYP3') ); 
    $html_sum = makeHeader($html_sum, true, false, false, 0, false, false, false, true, false, 0, $sum_pages, $list_per_page, $total, $list_start);
    echo $html_sum;
        // build output from template
        $html_sum = $jlistTemplates[3][0]->template_text;
        $html_sum = str_replace('{download_liste}', $mail_files, $html_sum);
        $html_sum = str_replace('{title_text}', JText::_('JLIST_FE_SUMMARY_PAGE_TITLE_TEXT'), $html_sum);
        if ($has_licenses){
            $html_sum = str_replace('{license_note}', JText::_('JLIST_FE_SUMMARY_PAGE_LICENSE_NOTE'), $html_sum);
        } else {
            $html_sum = str_replace('{license_note}', '', $html_sum);
        }    
        
        // summary pic
        $sum_size = $jlistConfig['cat.pic.size'];
        $sumpic = '<img src="'.JURI::base().'components/com_jdownloads/images/summary.png" width="'.$sum_size.'" height="'.$sum_size.'" border="0" alt="" /> ';
        $html_sum = str_replace('{summary_pic}', $sumpic, $html_sum);
        
        // google adsense
        if ($jlistConfig['google.adsense.active']){
            $html_sum = str_replace('{google_adsense}', stripslashes($jlistConfig['google.adsense.code']), $html_sum);
        } else {
            $html_sum = str_replace('{google_adsense}', '', $html_sum);
        }    
        if ($user->guest && $jlistConfig['countdown.active']){
            $countdown = '<script type="text/javascript"> counter='.$jlistConfig['countdown.start.value'].'; active=setInterval("countdown2()",1000);
                           function countdown2(){
                              if (counter >0){
                                  counter-=1;
                                  document.getElementById("countdown").innerHTML=sprintf(\''.addslashes($jlistConfig['countdown.text']).'\',counter);
                              } else {
                                  document.getElementById("countdown").innerHTML=\''.'{link}'.'\'
                                  window.clearInterval(active);
                              }    
                           }
                           </script>';
        }
        // support for AUP
        if ($jlistConfig['use.alphauserpoints']){
            $api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
            if (file_exists($api_AUP)){
                require_once ($api_AUP);
                $profil = AlphaUserPointsHelper:: getUserInfo('', $user->id);
            }
            if ($profil){
                $points_info = sprintf( JText::_('JLIST_FE_VIEW_AUP_SUM_POINTS'), $sum_aup_points, $profil->points);
                $html_sum = str_replace('{aup_points_info}', $points_info, $html_sum); 
            } else {
                $points_info = sprintf( JText::_('JLIST_FE_VIEW_AUP_SUM_POINTS'), $sum_aup_points, 0);
                $html_sum = str_replace('{aup_points_info}', $points_info, $html_sum); 
            }    
        } else {
            $html_sum = str_replace('{aup_points_info}', '', $html_sum); 
        }    
            
        if (count($marked_files_id) > 1) {
            $link = '<div id="countdown" style="text-align:center"><a href="'.$download_link.'" target="_self"  title="'.JText::_('JLIST_LINKTEXT_ZIP').'"><img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.details'].'" border="0" alt="'.JText::_('JLIST_LINKTEXT_ZIP').'" /></a></div>';
            if ($countdown){
               $countdown = str_replace('{link}', $link, $countdown);
               $html_sum = str_replace('{download_link}', '<div id="countdown">'.$countdown.'</div>', $html_sum);
           } else {    
               $html_sum = str_replace('{download_link}', $link, $html_sum);
           }    
           $html_sum = str_replace('{external_download_info}', '', $html_sum);
        } else {
            if ($open_in_blank_page || $extern_site){
                $targed = '_blank';
                if ($extern_site){
                    $html_sum = str_replace('{external_download_info}', JText::_('JLIST_FRONTEND_DOWNLOAD_GO_TO_OTHER_SITE_INFO'), $html_sum);
                } else {
                    $html_sum = str_replace('{external_download_info}', '', $html_sum);
                }    
            } else {
                $targed = '_self';
                $html_sum = str_replace('{external_download_info}', '', $html_sum);
            }                    

            $link = '<div id="countdown" style="text-align:center"><a href="'.$download_link.'" target="'.$targed.'" title="'.JText::_('JLIST_LINKTEXT_ZIP').'" ><img src="'.JURI::base().'images/jdownloads/downloadimages/'.$jlistConfig['download.pic.details'].'" border="0" alt="'.JText::_('JLIST_LINKTEXT_ZIP').'" /></a></div>'; 
            if ($countdown){
                $countdown = str_replace('{link}', $link, $countdown);
                $html_sum = str_replace('{download_link}', '<div id="countdown">'.$countdown.'</div>', $html_sum);
            } else {    
                $html_sum = str_replace('{download_link}', $link, $html_sum);
            }
        }
        
		$footer = makeFooter(true, false, false, 0, 0, 0, 0);  
        $html_sum .= $footer;
    echo $html_sum;
	}

// view frontend upload form
function viewUpload($option, $view ){
	global $jlistConfig, $Itemid, $mainframe, $page_title;
	$user = &JFactory::getUser();
	$database = &JFactory::getDBO();                        
    
    $editor =& JFactory::getEditor();
    $params = array( 'smilies'=> '0' ,
                 'style'  => '1' ,  
                 'layer'  => '0' , 
                 'table'  => '0' ,
                 'clear_entities'=>'0'
                 );
    
    $mainframe->setPageTitle($page_title.' - '.JText::_('JLIST_FRONTEND_UPLOAD_PAGE_TITLE') );   
    // variablen vorbelegen
	$image1 = '<img src="'.JURI::base().'components/com_jdownloads/images/';
	$image2 = '" width="18" height="18" border="0" alt="" align="top" />';
	$upload_stop_pic = '<img src="'.JURI::base().'components/com_jdownloads/images/upload_stop.png" width="24" height="24" border="0" alt="" />';
	$upload_ok_pic = '<img src="'.JURI::base().'components/com_jdownloads/images/upload_ok.png" width="24" height="24" border="0" alt="" />';	
	
	$max_file_size = $jlistConfig['allowed.upload.file.size'] * 1024 ;
	$name_pic 		 =	'form_no_value.png';
	$mail_pic 		 =	'form_no_value.png';
	$filetitle_pic 	 =	'form_no_value.png';
	$catlist_pic	 =	'form_no_value.png';
	$file_upload_pic =	'form_no_value.png';
    $extern_file_pic =  'form_no_value.png';
	$description_pic =	'form_no_value.png';
	
    $html_form = makeHeader($html_form, true, false, false, 0, false, false, true, false, false, 0, 0, 0, 0, 0);
	echo $html_form;
	$html_form = '';  
	
    $footer = makeFooter(true, false, false, 0, 0, 0, 0);  

	// Zugriffskontrolle
    if (!$jlistConfig['frontend.upload.active']){     
              $msg = '<div class="jd_div_content"><br />'.JText::_('JLIST_FRONTEND_UPLOAD_ACCESS_ONLY_SPECIALS').'<br /><br /></div>';
              $access = false;
              $html_form .= '{msg}';     
	} elseif ($user->get('aid') < (int)$jlistConfig['upload.access']){
        if ((int)$jlistConfig['upload.access'] == 2 ) {
            $msg = '<div class="jd_div_content"><br />'.JText::_('JLIST_FRONTEND_UPLOAD_ACCESS_ONLY_SPECIALS').'<br /><br /></div>';
        } else {
            $msg = '<div class="jd_div_content"><br />'.JText::_('JLIST_FRONTEND_UPLOAD_ACCESS_ONLY_REGGED').'<br /><br /></div>';
        }
        $html_form .= '{msg}';
        $access = false;
    } else {
		$access= true;  
		// nur einf�gen wenn access = true
		$html_form .= '<div class="jd_div_content"><br />'.stripslashes($jlistConfig['upload.form.text']).'<br /></div>';
	}
		// Inhalte holen, falls vorhanden	
	if ($user->get('id') > 0) {
       $name = $user->get('username');
	   $mail = $user->get('email');
       $submitted_by = $user->id;
	   $disabled = 'disabled="disabled"';
	   $name_pic =	'';
	   $mail_pic =	'';
    } else {
	   $name =	$database->getEscaped (JArrayHelper::getValue( $_POST, 'name', '' ));
	   $mail =  $database->getEscaped (JArrayHelper::getValue( $_POST, 'mail', '' ));
	   $disabled = '';	   	   
    }

	$author = 		$database->getEscaped (JArrayHelper::getValue( $_POST, 'author', '' ));
	$author_url = 	$database->getEscaped (JArrayHelper::getValue( $_POST, 'author_url', '' ));
	$filetitle =	$database->getEscaped (JArrayHelper::getValue( $_POST, 'filetitle', '' ));
	$version =		$database->getEscaped (JArrayHelper::getValue( $_POST, 'version', '' ));
    $price =        $database->getEscaped (JArrayHelper::getValue( $_POST, "price", '' ));
	$catlist_sel =	$database->getEscaped (JArrayHelper::getValue( $_POST, "catlist", '0' ));
    $license_sel =	$database->getEscaped (JArrayHelper::getValue( $_POST, 'license', '0' ));
	$system_sel  =	$database->getEscaped (JArrayHelper::getValue( $_POST, 'system', '0' ));
	$language_sel =	$database->getEscaped (JArrayHelper::getValue( $_POST, 'language', '0' ));
	$description =	$database->getEscaped (JArrayHelper::getValue( $_POST, 'description', '' ));
    $description_long = $database->getEscaped (JArrayHelper::getValue( $_POST, 'description_long', '' ));	
    $extern_file =  $database->getEscaped (JArrayHelper::getValue( $_POST, 'extern_file', '' ));    
    $file_upload  = JArrayHelper::getValue($_FILES,'file_upload',array('tmp_name'=>''));
    $pic_upload  = JArrayHelper::getValue($_FILES,'pic_upload',array('tmp_name'=>''));
    $pic_upload2  = JArrayHelper::getValue($_FILES,'pic_upload2',array('tmp_name'=>''));
    $pic_upload3  = JArrayHelper::getValue($_FILES,'pic_upload3',array('tmp_name'=>''));		
	// is send?
    $sended = intval(JArrayHelper::getValue( $_POST, 'send', 0 ));
	if ($sended == 1) { 
		$no_valid = 0; 
		if ($name != '') {
			$name_pic =	'';
		} else {
			$name_pic =	'form_no_value.png';
			$no_valid++; }		

		// simple mail check		
		if ($mail != '' && eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,4}$", $mail)){
    		$mail_pic =	'';
		} else {
			$mail_pic =	'form_no_value.png';
			$mail = '';
			$no_valid++; }		

		if ($filetitle != '') {
			$filetitle_pic = '';
		} else {
			$filetitle_pic = 'form_no_value.png';
			$no_valid++; }		

		if ($catlist_sel != '0' ) {
			$catlist_pic = '';
		} else {
			$catlist_pic = 'form_no_value.png';
			$no_valid++; }		

		if ($file_upload['tmp_name'] != '') {
			$file_upload_pic = '';
		} else {
			if ($extern_file == ''){
                $file_upload_pic = 'form_no_value.png';
			    $no_valid++;
            } else {
              // extern file url exist
              $file_upload_pic = ''; 
            }    
        }
        
        if ($extern_file != '') {
            $extern_file_pic = '';
        } else {
            if ($file_upload['tmp_name'] == ''){
                $extern_file_pic = 'form_no_value.png';
                $no_valid++;
            } else {
              // file_upload exist
              $extern_file_pic = '';  
            }    
        }     		

		if ($description != '') {
			$description_pic =	'';
		} else {
			if ($jlistConfig['fe.upload.view.desc.short'] == '1') {
                $description_pic = 'form_no_value.png';
			    $no_valid++; 
            }
        }    		
				
		// when all is ready - save the data
		if ($no_valid == 0) {
			$msg = '';
			// get upload category
			$database->SetQuery("SELECT cat_dir FROM #__jdownloads_cats WHERE cat_id = '$catlist_sel'");
			$mark_catdir = $database->loadResult();
			$description = trim($description);
            $description_long = trim($description_long);
            
            // build file alias
            $file_alias = $filetitle;
            $file_alias = JFilterOutput::stringURLSafe($file_alias);
            if (trim(str_replace('-','',$file_alias)) == '') {
               $datenow =& JFactory::getDate();
               $file_alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
            }
    		
			// check file extensions
	       if ($file_upload['tmp_name'] != '') { 
            $filetype = strtolower(substr(strrchr($file_upload['name'], '.'), 1));
           	$file_types = trim($jlistConfig['allowed.upload.file.types']);
           	$file_types = str_replace(',', '|', $file_types);
           	if (!eregi( $file_types, $filetype ) || stristr($file_upload['name'], '.php.')){
           		$file_upload['tmp_name'] = '';
				$msg = '<div>'.$upload_stop_pic.'<font color="red"> '
				   		.JText::_('JLIST_FRONTEND_UPLOAD_ERROR_FILETYPE').
				   		'</font><br />&nbsp;</div>';
				$html_form = str_replace('{form}', '{msg}{form}', $html_form);	
           	}

			// check filesize
           	if ($file_upload['size'] > $max_file_size) {
           		$file_upload['tmp_name'] = '';
				$msg = '<div>'.$upload_stop_pic.'<font color="red"> '
				   		.JText::_('JLIST_FRONTEND_UPLOAD_ERROR_FILESIZE').
				   		'</font><br />&nbsp;</div>';
				$html_form = str_replace('{form}', '{msg}{form}', $html_form);	
           	}           	
           }
           
           // check file type when extern file
           if ($extern_file != '') { 
               $only_extern_link = false;
               $only_file_name = basename($extern_file);
               $filetype = strtolower(substr(strrchr($only_file_name, '.'), 1));
               if ($filetype){
                    $file_types = trim($jlistConfig['allowed.upload.file.types']);
                    $file_types = str_replace(',', '|', $file_types);
                    if (!eregi( $file_types, $filetype ) || stristr($only_file_name, '.php.')){
                        $extern_file = '';
                        $msg = '<div>'.$upload_stop_pic.'<font color="red"> '
                           .JText::_('JLIST_FRONTEND_UPLOAD_ERROR_FILETYPE').
                           '</font><br />&nbsp;</div>';
                        $html_form = str_replace('{form}', '{msg}{form}', $html_form);    
                    }
               } else {
                 $only_extern_link = true;
               }   
           }
            
            //pic upload bearbeiten
            $thumbnail = '';
            $thumbnail2 = '';
            $thumbnail3 = '';
            $upload_dir = '/images/jdownloads/screenshots/'; 
            $pic_types = 'gif|jpg|png';
            
            if($pic_upload['tmp_name']!=''){
              $pictype = strtolower(substr(strrchr($pic_upload['name'],"."),1)); 
              if (eregi( $pictype, $pic_types )) {
                 // replace special chars in filename
                $pic_filename = checkFileName($pic_upload['name']);
                $only_name = substr($pic_filename, 0, strrpos($pic_filename, '.'));
                $file_extension = strrchr($pic_filename,".");
                $num = 0;
                while (is_file(JPATH_SITE.$upload_dir.$pic_filename)){
                    $pic_filename = $only_name.$num++.$file_extension;
                    if ($num > 5000) break; 
                }
                $target_path =  JPATH_SITE.$upload_dir.$pic_filename;
                if(@move_uploaded_file($pic_upload['tmp_name'], $target_path)) {
                     // set chmod
                     @chmod($target_path, 0655);
                     // create thumb
                     create_new_thumb($target_path);
                     $thumbnail = basename($target_path);
                }      
              }             
            }

            //pic upload bearbeiten
            if($pic_upload2['tmp_name']!=''){
              $pictype = strtolower(substr(strrchr($pic_upload2['name'],"."),1)); 
              if (eregi( $pictype, $pic_types )) {
                 // replace special chars in filename
                $pic_filename = checkFileName($pic_upload2['name']);
                $only_name = substr($pic_filename, 0, strrpos($pic_filename, '.'));
                $file_extension = strrchr($pic_filename,".");
                $num = 0;
                while (is_file(JPATH_SITE.$upload_dir.$pic_filename)){
                    $pic_filename = $only_name.$num++.$file_extension;
                    if ($num > 5000) break; 
                }
                $target_path =  JPATH_SITE.$upload_dir.$pic_filename;
                if(@move_uploaded_file($pic_upload2['tmp_name'], $target_path)) {
                     // set chmod
                     @chmod($target_path, 0655);
                     // create thumb
                     create_new_thumb($target_path);
                     $thumbnail2 = basename($target_path);
                }      
              }             
            }
             
            //pic upload bearbeiten
            if($pic_upload3['tmp_name']!=''){
              $pictype = strtolower(substr(strrchr($pic_upload3['name'],"."),1)); 
              if (eregi( $pictype, $pic_types )) {
                 // replace special chars in filename
                $pic_filename = checkFileName($pic_upload3['name']);
                $only_name = substr($pic_filename, 0, strrpos($pic_filename, '.'));
                $file_extension = strrchr($pic_filename,".");
                $num = 0;
                while (is_file(JPATH_SITE.$upload_dir.$pic_filename)){
                    $pic_filename = $only_name.$num++.$file_extension;
                    if ($num > 5000) break; 
                }
                $target_path =  JPATH_SITE.$upload_dir.$pic_filename;
                if(@move_uploaded_file($pic_upload3['tmp_name'], $target_path)) {
                     // set chmod
                     @chmod($target_path, 0655);
                     // create thumb
                     create_new_thumb($target_path);
                     $thumbnail3 = basename($target_path);
                }      
              }             
            } 
               		
			//datei upload bearbeiten
			if($file_upload['tmp_name']!=''){
          		// replace special chars in filename
                $filename_new = checkFileName($file_upload['name']);
                $upload_dir = '/'.$jlistConfig['files.uploaddir'].'/'.$mark_catdir.'/';
                $only_name = substr($filename_new, 0, strrpos($filename_new, '.'));
                $file_extension = strrchr($filename_new,".");
                $num = 0;
                while (is_file(JPATH_SITE.$upload_dir.$filename_new)){
                    $filename_new = $only_name.$num++.$file_extension;
                    if ($num > 5000) break; 
                }
                $dir_and_filename = str_replace('/'.$jlistConfig['files.uploaddir'].'/', '', $upload_dir.$filename_new);
				$target_path = JPATH_SITE.$upload_dir.$filename_new;
                // upload only when the file not exist in the directory
              if (!is_file($target_path)){
		    	if(@move_uploaded_file($file_upload['tmp_name'], $target_path)) {
              		// get filesize
               		$size = fsize($target_path);
               		// get filedate
            		$date_added = JHTML::_('date', 'now', '%Y-%m-%d %H:%M:%S' );
					$url_download = basename($target_path);
                    $url_download = utf8_encode($url_download);
					
					// auto publish ?
                    if ($jlistConfig['upload.auto.publish']){
                        $publish = 1;
                        setAUPPointsUploads($submitted_by, $filetitle);
                        $set_aup_points = 0;
                    } else {
                        $set_aup_points = 1;
                        $publish = 0;
                    }
                    $file_extension = strtolower(substr(strrchr($url_download,"."),1));
                    $filepfad = JPATH_SITE.'/images/jdownloads/fileimages/'.$file_extension.'.png';
                    if(file_exists(JPATH_SITE.'/images/jdownloads/fileimages/'.$file_extension.'.png')){
                    $filepic       = $file_extension.'.png';
                    } else {
                    $filepic       = $jlistConfig['file.pic.default.filename'];
                    }                    
                    $database->setQuery("INSERT INTO #__jdownloads_files (`file_id`, `file_title`, `file_alias`,`description`, `description_long`, `file_pic`, `thumbnail`, `thumbnail2`, `thumbnail3`, `price`, `release`, `language`, `system`, `license`, `url_license`, `size`, `date_added`, `file_date`, `url_download`, `url_home`, `author`, `url_author`, `created_by`, `created_mail`, `modified_by`, `modified_date`, `submitted_by`, `set_aup_points`, `downloads`, `cat_id`, `ordering`, `published`, `checked_out`, `checked_out_time`)
                                                                     VALUES (NULL, '$filetitle', '$file_alias', '$description', '$description_long', '$filepic', '$thumbnail', '$thumbnail2', '$thumbnail3', '$price', '$version', '$language_sel', '$system_sel', '$license_sel', '', '$size', '$date_added', '', '$url_download', '$author_url', '$author', '', '$name', '$mail', '', '0000-00-00 00:00:00', '$submitted_by', '$set_aup_points', '0', '$catlist_sel', '0', '$publish', '0', '0000-00-00 00:00:00')");
		   	   		if (!$database->query()) {
						// fehler beim erstellen in DB	
						echo $database->stderr();
						exit;
					}					 
                    // alles OK!
                    if (!$msg) {
                        $msg = '<div>'.$upload_ok_pic.'<font color="green"> '
                               .JText::_('JLIST_FRONTEND_UPLOAD_OK').
                               '</font><br />&nbsp;</div>';
                               $html_form = str_replace('{form}', '{msg}{form}', $html_form);
                               // send email wenn aktiviert
                               if ($jlistConfig['send.mailto.option.upload']){
                                   sendMailUploads($name, $mail, $url_download, $filetitle, $description);   
                               }    
                    }
                 } else {
					// fehler beim verschieben
					$msg = '<div>'.$upload_stop_pic.'<font color="red"> '
				   		.JText::_('JLIST_FRONTEND_UPLOAD_ERROR_MOVE_FILE').
				   		'</font><br />&nbsp;</div>';
					$html_form = str_replace('{form}', '{msg}{form}', $html_form);	
				 }
              } else {
                  // file exist with the same name
                    $msg = '<div>'.$upload_stop_pic.'<font color="red"> '
                           .JText::_('JLIST_FRONTEND_UPLOAD_ERROR_FILE_EXISTS').
                           '</font><br />&nbsp;</div>';
                    $html_form = str_replace('{form}', '{msg}{form}', $html_form);    
              } 
		   	}
            
            // save the data with URL to extern file
            if($extern_file !=''){
                if (!$only_extern_link){
                    // get filesize
                    $size = urlfilesize($extern_file);
                    $a = array("B", "KB", "MB", "GB", "TB", "PB");
                    $pos = 0;
                    while ($size >= 1024) {
                        $size /= 1024;
                        $pos++;
                    }
                    $size = round($size,2)." ".$a[$pos];
                    
                    $file_extension = strtolower(substr(strrchr($extern_file,"."),1));
                    $filepfad = JPATH_SITE.'/images/jdownloads/fileimages/'.$file_extension.'.png';
                    if(file_exists(JPATH_SITE.'/images/jdownloads/fileimages/'.$file_extension.'.png')){
                        $filepic       = $file_extension.'.png';
                    } else {
                        $filepic       = $jlistConfig['file.pic.default.filename'];
                    }
                    $linked_to_extern_site = 0;
                } else {
                        $filepic = $jlistConfig['file.pic.default.filename'];
                        $linked_to_extern_site = 1;
                }    
                // get filedate
                $date_added = JHTML::_('date', 'now', '%Y-%m-%d %H:%M:%S' );
                // auto publish ?
                if ($jlistConfig['upload.auto.publish']){
                    $publish = 1;
                    setAUPPointsUploads($submitted_by, $filetitle);
                } else {
                    $publish = 0;
                }
                        
                $database->setQuery("INSERT INTO #__jdownloads_files (`file_id`, `file_title`, `file_alias`, `description`, `description_long`, `file_pic`, `thumbnail`, `price`, `release`, `language`, `system`, `license`, `url_license`, `size`, `date_added`, `file_date`, `url_download`, `extern_file`, `extern_site`, `url_home`, `author`, `url_author`, `created_by`, `created_mail`, `modified_by`, `modified_date`, `submitted_by`, `downloads`, `cat_id`, `ordering`, `published`, `checked_out`, `checked_out_time`) VALUES 
                                                                      (NULL, '$filetitle', '$file_alias', '$description', '$description_long', '$filepic', '$thumbnail', '$price', '$version', '$language_sel', '$system_sel', '$license_sel', '', '$size', '$date_added', '', '', '$extern_file', '$linked_to_extern_site', '$author_url', '$author', '', '$name', '$mail', '', '0000-00-00 00:00:00', '$submitted_by', '0', '$catlist_sel', '0', '$publish', '0', '0000-00-00 00:00:00')");
                if (!$database->query()) {
                    echo $database->stderr();
                    exit;
                }                     
                // alles OK!
                    if (!$msg) {
                        $msg = '<div>'.$upload_ok_pic.'<font color="green"> '
                               .JText::_('JLIST_FRONTEND_UPLOAD_URL_OK').
                               '</font><br />&nbsp;</div>';
                               $html_form = str_replace('{form}', '{msg}{form}', $html_form);
                               // send email wenn aktiviert
                               if ($jlistConfig['send.mailto.option.upload']){
                                   sendMailUploads($name, $mail, $extern_file, $filetitle, $description);   
                               }    
                    }
          }             

		// file saved upload end ------------------------------------------------------		
			
		} else {
			// fehlermeldung �bergeben	
			$msg = '<div>'.$upload_stop_pic.'<font color="red"> '.JText::_('JLIST_FRONTEND_UPLOAD_ERROR_NO_VALUE').'</font><br />&nbsp;</div>';
			$html_form = str_replace('{form}', '{msg}{form}', $html_form);
		}
	}

	// nur form einf�gen wenn access = true
	if ($access) {
        
	// form anzeigen 
    $form = '<form name="uploadForm" id="uploadForm" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">
	<table class="jd_upload_form" border="0" cellpadding="0" cellspacing="5" width="99%">
        <tr>
            <td width="140" valign="middle">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_NAME').'
            </td><td width="20" valign="top">'.$image1.$name_pic.$image2.'</td>
            <td width="267" valign="middle">
                <input type="text" name="name" id="name" maxlength="60" size="40" '.$disabled.' value="'.$name.'">
            </td>
        </tr>
        <tr>
            <td width="140" valign="middle">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_MAIL').'
            </td><td width="20" valign="top">'.$image1.$mail_pic.$image2.'</td>
            <td width="267">
                <input type="text" name="mail" maxlength="60" size="40" '.$disabled.' value="'.$mail.'">
            </td>
            </tr>';
	  if ($jlistConfig['fe.upload.view.author'] == '1') {	 
         $form .= '<tr>
            <td width="140" valign="middle">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_AUTHOR').'
            </td><td width="20" valign="top">&nbsp;</td>
            <td width="267">
                <input type="text" name="author" id="author" maxlength="60" size="40 value="'.$author.'">
            </td>
            </tr>';
      } 
      if ($jlistConfig['fe.upload.view.author.url'] == '1') {     
            $form .= '<tr>
            <td width="140" valign="middle">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_AUTHOR_URL').'
            </td><td width="20" valign="top">&nbsp;</td>
            <td width="267">
                <input type="text" name="author_url" maxlength="60" size="40"  value="'.$author_url.'">
            </td>
            </tr>';
      }
      
      $form .= '<tr>
            <td width="140" valign="middle">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_TITLE_FILE').'
            </td><td width="20" valign="top">'.$image1.$filetitle_pic.$image2.'</td>
            <td width="267">
                <input type="text" name="filetitle" maxlength="60" size="40" value="'.$filetitle.'">
            </td>
            </tr>';
	  if ($jlistConfig['fe.upload.view.release'] == '1') {     
            $form .= '<tr>
            <td width="140" valign="middle">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_VERSION').'
            </td><td width="20" valign="top">&nbsp;</td>
            <td width="267">
                <input type="text" name="version" maxlength="60" size="40" value="'.$version.'">
            </td>
            </tr>';
      }  
      if ($jlistConfig['fe.upload.view.price'] == '1') {     
            $form .= '<tr>
            <td width="140" valign="middle">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_PRICE').'
            </td><td width="20" valign="top">&nbsp;</td>  
            <td width="267">
                <input type="text" name="price" maxlength="20" size="40" value="'.$price.'">
            </td>
            </tr>';		
	  }	
		$form .= '<tr>
            <td width="140" valign="middle">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_CATEGORY').'
            </td><td width="20" valign="top">'.$image1.$catlist_pic.$image2.'</td>
            <td width="200">';
            
       	// build cat tree listbox
        $access = checkAccess_JD();
        $src_list = array();
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
		$preload = array();
		$preload[] = JHTML::_('select.option', '0', JText::_('JLIST_FRONTEND_UPLOAD_TITEL_LISTBOXES') );
		$selected = array();
		$selected[] = JHTML::_('select.option', '0' );
    	//1.5 Native treeselect List ersetzen durch makeoption und selectlist
        $cat_listbox= treeSelectList( &$src_list, 0, $preload, 'catlist','class="inputbox" size="1"', 'value', 'text', $catlist_sel );
 		$form .= $cat_listbox;
        $form .= '</td>
        </tr>';      
		
        if ($jlistConfig['fe.upload.view.license'] == '1') {     
         $form .= '<tr>
            <td width="140" valign="middle">
                '.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_LIZENZ').'
            </td><td width="20" valign="top">&nbsp;</td>
            <td width="267">';
		   // build listbox with licenses
    	   $licenses = array();
    	   $licenses[] = JHTML::_('select.option', '0', JText::_('JLIST_FRONTEND_UPLOAD_TITEL_LISTBOXES') );
    	   $database->setQuery( "SELECT id AS value, license_title AS text FROM #__jdownloads_license" );
    	   $licenses = array_merge( $licenses, $database->loadObjectList() );
    	   $lic_listbox = JHTML::_('select.genericlist', $licenses, 'license', 'size="1" class="inputbox"', 'value', 'text', $license_sel );
   		   $form .= $lic_listbox.'
            </td>
            </tr>';
        }        
		
        if ($jlistConfig['fe.upload.view.language'] == '1') {     
            $form .= '<tr>
            <td width="140" valign="middle">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_LANGUAGE').'
            </td><td width="20" valign="top">&nbsp;</td>
            <td width="200">';
	        // build language listbox
    	    $file_language = array();
    	    $file_lang_values = explode(',' , $jlistConfig['language.list']);
    	    for ($i=0; $i < count($file_lang_values); $i++) {
        	    $file_language[] = JHTML::_('select.option', $i, $file_lang_values[$i] );
    	    }
    	    $listbox_language = JHTML::_('select.genericlist', $file_language, 'language', 'class="inputbox" size="1"', 'value', 'text', $language_sel );                 
		    $form .= $listbox_language.'
            </td>
            </tr>';
        }      
		
        if ($jlistConfig['fe.upload.view.system'] == '1') {     
            $form .= '<tr>
                <td width="140" valign="middle">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_SYSTEM').'
                </td><td width="20" valign="top">&nbsp;</td>
                <td width="200">';
			
		    // build system listbox
    	    $file_system = array();
    	    $file_sys_values = explode(',' , $jlistConfig['system.list']);
    	    for ($i=0; $i < count($file_sys_values); $i++) {
        	    $file_system[] = JHTML::_('select.option', $i, $file_sys_values[$i] );
    	    }
    	    $listbox_system = JHTML::_('select.genericlist', $file_system, 'system', 'class="inputbox" size="1"', 'value', 'text', $system_sel );				
            $form .= $listbox_system.'</td></tr>';              
        }
        
        if ($jlistConfig['fe.upload.view.select.file'] == '1') {
            if ($jlistConfig['fe.upload.view.extern.file'] == '1') {
                $checkUpload = 'checkUploadFieldFile(file_upload)';
            } else {
                $checkUpload = '';
            }     
            $form .= '<tr>
            <td width="140" valign="top">'.JText::_('JLIST_FRONTEND_UPLOAD_FILENAME').'
            </td><td width="20" valign="top">'.$image1.$file_upload_pic.$image2.'</td>
            <td width="200">
                <input name="file_upload" id="file_upload" size="38" type="file" onchange="'.$checkUpload.'" value="'.$file_upload.'"><br />'.JText::_('JLIST_FRONTEND_UPLOAD_ALLOWED_FILETYPE').': <b>'.$jlistConfig['allowed.upload.file.types'].'</b><br />'.JText::_('JLIST_FRONTEND_UPLOAD_ALLOWED_MAX_SIZE').': <b>'.$jlistConfig['allowed.upload.file.size'].' KB</b>
            </td>
            </tr>';
        }
        if ($jlistConfig['fe.upload.view.extern.file'] == '1') {
            if ($jlistConfig['fe.upload.view.select.file'] == '1') {
               $checkUpload2 = 'checkUploadFieldExtern(extern_file)'; 
               $form .= '<tr><td width="140" valign="top"><b>'.JText::_('JLIST_FRONTEND_UPLOAD_EXTERN_FILE_OR').'</b></td></tr>'; 
            } else {
               $checkUpload2 = '';
            }    
            $form .= '<tr>
            <td width="140" valign="top">'.JText::_('JLIST_FRONTEND_UPLOAD_EXTERN_FILE_TITEL').'
            </td><td width="20" valign="top">'.$image1.$extern_file_pic.$image2.'</td>
            <td width="200">
                <input type="text" name="extern_file" id="extern_file" maxlength="255" size="60" onchange="'.$checkUpload2.'" value="'.$extern_file.'"><br />'.JText::_('JLIST_FRONTEND_UPLOAD_ALLOWED_FILETYPE').': <b>'.$jlistConfig['allowed.upload.file.types'].'</b>
            </td>
            </tr>';
        }

        if ($jlistConfig['fe.upload.view.pic.upload'] == '1') { 
            $form .= '<tr>
            <td width="140" valign="top">'.JText::_('JLIST_FRONTEND_UPLOAD_PIC_FILETITLE').'
            </td><td width="20" valign="top">&nbsp;</td>
            <td width="200">
                <input name="pic_upload" size="38" type="file" value="'.$pic_upload.'"><br />'.JText::_('JLIST_FRONTEND_UPLOAD_PIC_ALLOWED_FILES').' <b>.gif .jpg .png </b>
            </td>
            </tr>';
            $form .= '<tr>
            <td width="140" valign="top">'.JText::_('JLIST_FRONTEND_UPLOAD_PIC_FILETITLE').'
            </td><td width="20" valign="top">&nbsp;</td>
            <td width="200">
                <input name="pic_upload2" size="38" type="file" value="'.$pic_upload2.'"><br />'.JText::_('JLIST_FRONTEND_UPLOAD_PIC_ALLOWED_FILES').' <b>.gif .jpg .png </b>
            </td>
            </tr>';
            $form .= '<tr>
            <td width="140" valign="top">'.JText::_('JLIST_FRONTEND_UPLOAD_PIC_FILETITLE').'
            </td><td width="20" valign="top">&nbsp;</td>
            <td width="200">
                <input name="pic_upload3" size="38" type="file" value="'.$pic_upload3.'"><br />'.JText::_('JLIST_FRONTEND_UPLOAD_PIC_ALLOWED_FILES').' <b>.gif .jpg .png </b>
            </td>
            </tr>';
		}  
        if ($jlistConfig['fe.upload.view.desc.short'] == '1') {
            $form .= '<tr>
            <td width="140" valign="top">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_DESC_FILE').'
            </td><td width="20" valign="top">'.$image1.$description_pic.$image2.'</td> 
            <td width="267"> 
                '.$editor->display( 'description', '', '400', '400', '20', '20', false, $params ).'
            </td>
            </tr>';
        }
        if ($jlistConfig['fe.upload.view.desc.long'] == '1') {     
            $form .= '<tr>
            <td width="140" valign="top">'.JText::_('JLIST_FRONTEND_UPLOAD_TITEL_DESC_FILE_LONG').'
            </td><td width="20" valign="top">&nbsp;</td>
            <td width="267">
                '.$editor->display( 'description_long', '', '400', '400', '20', '20', false, $params ).'
               
            </td>
            </tr>';
        }
        $form .= '<tr>
            <td width="140" valign="middle">&nbsp;
            </td><td width="20" valign="top">&nbsp;</td>
            <td width="267">
                <input class="button" type="submit" name="senden" value="'.JText::_('JLIST_FRONTEND_UPLOAD_FILENAME_BUTTON_TEXT_SEND').'"> <input class="button" type="reset" name="cancel" 
                       value="'.JText::_('JLIST_FRONTEND_UPLOAD_FILENAME_BUTTON_TEXT_CLEAR').'">
            </td>
            </tr>
            </table>	
	<input type="hidden" name="option" value="'.$option.'" />
	<input type="hidden" name="view" value="'.$view.'" />
	<input type="hidden" name="send" value="1" /></form>
	<input type="hidden" name="MAX_FILE_SIZE" value="'.$max_file_size.'">';	
	
	} else {
		$form = '';  
	}	// end access if()...
	
	$html_form = str_replace('{form}', $form, $html_form);
	
	if (isset($msg)) {
		$html_form = str_replace('{msg}', $msg, $html_form);	
	}
	echo $html_form; 
	echo $footer; 
	
	// Focus auf erstes feld setzen
    
	if ($access) {
		if ($user->get('id') > 0) {  
	?>
    <script type="text/Javascript" language="JavaScript">
        if(document.uploadForm.author) {
           document.getElementById("author").focus();
        }
    </script>
	<?php
		} else {
	?>
    <script type="text/Javascript" language="JavaScript">
        if(document.uploadForm.name) {
           document.getElementById("name").focus();
        }
    </script>
	<?php
		} 
    }			      
}

function showSearchForm($option){
     global $Itemid, $jlistConfig, $mainframe, $page_title;
     $user = &JFactory::getUser();
     
    $mainframe->setPageTitle( $page_title.' - '.JText::_('JLIST_FRONTEND_SEARCH_LINKTEXT') );   
    $html_form = makeHeader($html_form, true, false, false, 0, false, true, false, false, false, 0, 0, 0, 0, 0);
    echo $html_form;
    $html_form = '';
    
    $html_form = '<form name="jdsearch" action="index.php?option=com_jdownloads&amp;Itemid='.$Itemid.'&amp;view=search.result" method="post">';
    $html_form .= '<table class="jd_div_content" border="0" cellpadding="0" cellspacing="5" width="99%">
        <tr><td><br /></td></tr>
        <tr>
            <td colspan="2" width="100" valign="middle">'.JText::_('JLIST_FRONTEND_SEARCH_DESCRIPTION').'
            </td>
        </tr>
        <tr>
            <td width="100" valign="middle">'.JText::_('JLIST_FRONTEND_SEARCH_TEXT_TITLE').'
            </td>
            <td width="200" valign="middle">
                <input class="jd_inputbox" type="text" name="jdsearchtext" id="jdsearchtext" maxlength="80" size="30"  value=""> <input class="button" type="submit" name="searchsubmit" value="'.JText::_('JLIST_FRONTEND_SEARCH_BUTTON_TEXT').'"/> 
            </td>
        </tr>
        <tr>
            <td width="100" valign="middle">'.JText::_('JLIST_FRONTEND_SEARCH_IN_TITLE').'
            </td>
            <td width="200">
                <input class="jd_inputbox" type="checkbox" name="jdsearchintitle" id="jdsearchintitle" value="1" checked="checked"> 
            </td>
        </tr>
        <tr>
           <td width="100" valign="middle">'.JText::_('JLIST_FRONTEND_SEARCH_IN_DESC').'
            </td>
            <td width="200">
                <input class="jd_inputbox" type="checkbox" name="jdsearchindesc" id="jdsearchindesc" value="1" checked="checked"> 
            </td>
        </tr>
        <tr>
           <td width="100" valign="middle">'.JText::_('JLIST_FRONTEND_SEARCH_NUMBERS').'
            </td>
            <td width="200">
                <input class="jd_inputbox" type="text" name="jdsearchnumber" id="jdsearchnumber" maxlength="3" size="3" value="30">
            </td>
        </tr>
        <tr><td><br /><br /></td></tr>
        </table>
        </form>';
        
    $html_form .= makeFooter(true, false, false, 0, 0, 0, 0); 
    
    if ( !$jlistConfig['offline'] ) {
            echo $html_cat;
    } else {
            if ($user->get('aid') == 2) {
                echo JText::_('JLIST_BACKEND_OFFLINE_ADMIN_MESSAGE_TEXT');
                echo $html_cat;
            } else {
                $html_off = '<br /><br />'.stripslashes($jlistConfig['offline.text']).'<br /><br />';
                echo $html_off;
            }
    }
    
    echo $html_form;
    ?>
    <script type="text/Javascript" language="JavaScript">
    <!--
        document.getElementById("jdsearchtext").focus();
    -->
    </script>
    <?php
}    

function showSearchResult($option){
    global $Itemid, $jlistConfig, $mainframe, $page_title;
    $database = &JFactory::getDBO();
    
    $mainframe->setPageTitle($page_title.' - '.JText::_('JLIST_FRONTEND_SEARCH_RESULT_TITLE') );   
    $html_form = makeHeader($html_form, true, false, false, 0, false, true, false, false, false, 0, 0, 0, 0, 0);

    $searchtext =    $database->getEscaped(JArrayHelper::getValue($_POST, 'jdsearchtext', ''));
    $searchintitle = $database->getEscaped(JArrayHelper::getValue($_POST, 'jdsearchintitle', ''));
    $searchintext =  $database->getEscaped(JArrayHelper::getValue($_POST, 'jdsearchindesc', ''));
    $searchnumber =  intval($database->getEscaped(JArrayHelper::getValue($_POST, 'jdsearchnumber', 30)));
    if (!$searchnumber) $searchnumber = 30;
    
    if (strlen($searchtext) < 3){
        echo "<script> alert('".JText::_('JLIST_FRONTEND_SEARCH_RESULT_TEXT_TO_SHORT')."'); window.history.go(-1); </script>\n";    
    }    
    if (!$searchintitle && !$searchintext){
        echo "<script> alert('".JText::_('JLIST_FRONTEND_SEARCH_RESULT_NO_OPTION')."'); window.history.go(-1); </script>\n";    
    }

    $search_array = explode(' ', $searchtext);
    $files2 = array();
    
    foreach ($search_array as $word) {
        if ($searchintitle && $searchintext){
            $database->setQuery("SELECT * FROM #__jdownloads_files WHERE file_title LIKE '%$word%' OR description LIKE '%$word%' OR description_long LIKE '%$word%' AND published = 1 ORDER BY date_added LIMIT $searchnumber");
        }
        if ($searchintitle && !$searchintext){
            $database->setQuery("SELECT * FROM #__jdownloads_files WHERE file_title LIKE '%$word%' AND published = 1 ORDER BY date_added LIMIT $searchnumber");
        }
        if (!$searchintitle && $searchintext){
            $database->setQuery("SELECT * FROM #__jdownloads_files WHERE description LIKE '%$word%' OR description_long LIKE '%$word%' AND published = 1 ORDER BY date_added LIMIT $searchnumber");
        }
        $files = $database->loadObjectList();

        foreach ($files as $file) {
            if (!array_key_exists($file->file_id, $files2)) {
                $files2[$file->file_id] = array('file'=>$file, 'ctr'=> 0);
            }
            $files2[$file->file_id]['ctr']++;
        }

    }
    
    usort($files2, '_ctrSort');

    if ($files2) {
        // files gefunden   
        // cat der files holen und auf access beschr�nken
        $access = checkAccess_JD();        
        $output = array();
        foreach($files2 as $file2) {
            $file = $file2['file'];
          if ($file->published) {  
            $database->setQuery("SELECT * FROM #__jdownloads_cats WHERE cat_id = '$file->cat_id'");
            $cat = $database->loadObjectList();
            if ($cat[0]->cat_access <= $access && $cat[0]->published){
                $output[] = $file;
            }
          }      
        }    
        if ($output) {
            $files_found = true;
            // result header
            $html_form .= '<table class="jd_search_form" border="0" cellpadding="0" cellspacing="5" width="99%">
                  <tr>
                  <td class="jd_search_result_title" width="100%"><b>'.JText::_('JLIST_FRONTEND_SEARCH_RESULT_TITLE').'</b><br />'
                  .JText::_('JLIST_FRONTEND_SEARCH_RESULT_SEARCH_TEXT').': <b>'.$searchtext.'</b><br />'
                  .JText::_('JLIST_FRONTEND_SEARCH_RESULT_SUM_FILES').': <b>'.count($output).'</b></td>
                  </tr>
                  <tr>
                  </tr>';
                            
            foreach ($output as $out){
                
                // suchtext farblich hervorheben
                foreach ($search_array as $word) {
                    $regexp = "/($word)(?![^<]+>)/i";
                    if ($searchintitle && $searchintext){
                        $out->description = preg_replace($regexp, '<font color="#CC3300">'.$word.'</font>', $out->description);
                        $out->file_title = preg_replace($regexp, '<font color="#CC3300">'.$word.'</font>', $out->file_title);
                    }
                    if ($searchintitle && !$searchintext){
                        $out->file_title = preg_replace($regexp, '<font color="#CC3300">'.$word.'</font>', $out->file_title);
                    }
                    if (!$searchintitle && $searchintext){
                        $out->description = preg_replace($regexp, '<font color="#CC3300">'.$word.'</font>', $out->description);
                    }
                }
                $titel_link = JRoute::_('index.php?option='.$option.'&amp;Itemid='.$Itemid.'&amp;view=view.download&catid='.$out->cat_id.'&cid='.$out->file_id);
                $titel_link_text = '<a href="'.$titel_link.'">'.$out->file_title.'</a>';
                $detail_link_text = '<a href="'.$titel_link.'">'.JText::_('JLIST_FE_DETAILS_LINK_TEXT_TO_DETAILS').'</a>';
                
                $html_form .= '<tr width="100%"><td class="jd_search_results"><b>'.$titel_link_text.' '.$out->release.'</b><br />'.substr($out->description, 0, 400).'...<br />'.$detail_link_text.'</td></tr>';    
            }    
            $html_form .= '</table>'; 
        } else {
            $files_found = false;  
        }    
    } else {
      $files_found = false;  
    }
    if (!$files_found) {
        // keine files gefunden - oder falsche berechtigung
        // result header
        $html_form .= '<table class="jd_search_form" border="0" cellpadding="0" cellspacing="5" width="99%">
                  <tr>
                  <td class="jd_search_result_title" width="100%"><b>'.JText::_('JLIST_FRONTEND_SEARCH_RESULT_TITLE').'</b><br />'
                  .JText::_('JLIST_FRONTEND_SEARCH_RESULT_SEARCH_TEXT').': <b>'.$searchtext.'</b><br />'
                  .JText::_('JLIST_FRONTEND_SEARCH_RESULT_NO_SUM_FILES').'</td>
                  </tr>
                  <tr>
                  </tr></table>';
    }    
    $html_form .= makeFooter(true, false, false, 0, 0, 0, 0);
    echo $html_form;
}

//end of class
}
?>