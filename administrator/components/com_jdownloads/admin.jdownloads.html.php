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

global $mainframe;

function yesnoSelectList($tag_name,$tag_attribs,$selected)
  {
        $arr = array(
        JHTML::_('select.option', 0, JText::_('JLIST_FE_NO' ) ),
        JHTML::_('select.option', 1, JText::_('JLIST_FE_YES' ) ),
        );
        return JHTML::_('select.genericlist', $arr, $tag_name, $tag_attribs, 'value', 'text', (int) $selected );
  }


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



$params   = JComponentHelper::getParams('com_languages');
$frontend_lang = $params->get('site', 'en-GB');
$language = JLanguage::getInstance($frontend_lang);

class jlist_HTML{

////////////////////                CATEGORIES              ///////////////////////
function categoriesEdit($option, $row, $inputbox_pic, $access_box, $last_dir_entry, $path_dir_entry){
    global $jlistConfig, $mainframe;
    $database = &JFactory::getDBO();
    $editor =& JFactory::getEditor(); 
	?>

	<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'categories.cancel') {
			submitform( pressbutton );
			return;
		}
		// do field validation
		if (form.create_auto_cat_dir.value == 1) {
          if (form.cat_title.value == "" || form.catid.value == ""){
			if (form.cat_title.value == ""){
                alert( "<?php echo JText::_('JLIST_BACKEND_CATSEDIT_ERROR_TITLE');?>" );
            }
            if (form.catid.value == ""){
                alert( "<?php echo JText::_('JLIST_BACKEND_CATSEDIT_ERROR_CATLIST');?>" );
            }
		} else {
			submitform( pressbutton );
		}
      } else {
        if (form.cat_title.value == "" || form.catid.value == "" || form.cat_dir.value == ""){
            if (form.cat_title.value == ""){
                alert( "<?php echo JText::_('JLIST_BACKEND_CATSEDIT_ERROR_TITLE');?>" );
            }
            if (form.catid.value == ""){
                alert( "<?php echo JText::_('JLIST_BACKEND_CATSEDIT_ERROR_CATLIST');?>" );
            }
            if (form.cat_dir){
                if (form.cat_dir.value == ""){
                    alert( "<?php echo JText::_('JLIST_BACKEND_CATSEDIT_ERROR_CAT_DIR_TEXT');?>" );
                }
            }    
        } else {
            submitform( pressbutton );
        }
      } 
	}
	
	function disable_enable_field ( element, status ) {
 		var elementToToggle = document.getElementById( element.id );
 		if ( status == 'active' )
 		{
 			element.readOnly = false;
 			elementToToggle.className = "active";
 		}
 		else
 		{
 			element.readOnly = true;
 			elementToToggle.className = "deactive";
 		}
	}
	</script>

	<form action="index2.php" method="post" name="adminForm" id="adminForm">

  <?php  $publish = array();
         $publish[] = JHTML::_('select.option', '0', JText::_('JLIST_FE_NO'));
         $publish[] = JHTML::_('select.option', '1', JText::_('JLIST_FE_YES'));
         $publish = JHTML::_('select.genericlist', $publish, "publish", 'size="1" class="inputbox"', 'value', 'text', $row->published );
  ?>
	<table width="100%" border="0">
		<tr>
			<td width="100%" valign="top">
			<table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo $row->cat_id ? JText::_('JLIST_BACKEND_CATSEDIT_EDIT') : JText::_('JLIST_BACKEND_CATSEDIT_ADD');?></th>
		      	</tr>
		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table>
                            <tr>
                                <td>
                                    <strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_REQUIRES_INFO')." "; ?>
                                    <br /></strong>
                                </td>
                            </tr>

		  					<tr>
                                <td><strong>
                                    <?php echo JText::_('JLIST_BACKEND_CATSEDIT_CAT_TITLE')." <b><font color=red>*</font></b>"; ?></strong><br />
		    						<input name="cat_title" id="cat_title" value="<?php echo $row->cat_title; ?>" size="50" maxlength="255"/>
                                    <input type="hidden" name="ordering" value="<?php echo $row->ordering;?>"/>
		    					</td>
		    					<td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_CATSEDIT_CAT_TITLE_DESC'); ?>
		    					</td>
                            </tr>    
                            <tr>
                                <td><strong>
                                    <?php echo JText::_('JLIST_EDIT_ALIAS_NAME_TITLE')." "; ?></strong><br />
                                    <input name="cat_alias" id="cat_alias" value="<?php echo $row->cat_alias; ?>" size="50" maxlength="255"/>
                                </td>
                                <td valign ="top"><?php echo " ".JText::_('JLIST_EDIT_ALIAS_NAME_DESC'); ?>
                                </td>    
		  					</tr>
                            
                                <?php
                                 if ($jlistConfig['create.auto.cat.dir']){
                                     if ($row->cat_id){ ?>
                                      <tr>
                                      <td><strong>
                                      <?php echo JText::_('JLIST_EDIT_CAT_DIR_TITLE').' '; ?></strong><br />
                                      <input name="cat_dir" value="<?php echo $row->cat_dir; ?>" size="50" maxlength="255" disabled="disabled"/> 
                                      </td>
                                      <td valign ="top"><?php echo " ".JText::_('JLIST_EDIT_CAT_DIR_TITLE_DESC1'); ?>
                                      </td>
                                      </tr>
                               <?php }
                                 } else { ?>
                                      <tr>
                                      <td><strong>
                                      <?php echo JText::_('JLIST_EDIT_CAT_DIR_TITLE').' <b><font color=red>*</font></b>'; ?></strong><br />
                                      <?php if ($path_dir_entry){ ?>
                                           <font color="#990000"><?php echo $path_dir_entry.'</font><br />'; 
                                           } ?>
                                      <input name="cat_dir" value="<?php echo $last_dir_entry; ?>" size="50" maxlength="255"/> 
                                      </td>
                                      <td valign ="top"><?php echo " ".JText::_('JLIST_EDIT_CAT_DIR_TITLE_DESC2'); ?>
                                      </td>
                                      </tr>
                                 <?php } ?>     
                            <tr>
                                <td><strong>
                                <?php echo JText::_('JLIST_BACKEND_EDIT_PUBLISHED').' '; ?></strong><br />
                                <?php echo $publish; ?> 
                                </td>
                            </tr>
                            <tr>
		    					<td valign="top"><strong>
                                    <?php
                                    if ($row->cat_id) {
                                        echo JText::_('JLIST_BACKEND_CATSEDIT_CAT_LISTBOX_TITLE')." ";
                                    } else {
                                        echo JText::_('JLIST_BACKEND_CATSEDIT_CAT_LISTBOX_TITLE_NEW')." ";
                                    }
                                    ?>
                                    </strong><br />
                                <?php
                                  // build cat tree listbox
									$src_list = array();
									$query = "SELECT cat_id AS id, parent_id AS parent, cat_title AS name FROM #__jdownloads_cats ORDER BY cat_title";
									$database->setQuery( $query );
									$cats = $database->loadObjectList();
									$sum_cats = count($cats);
                                    $preload = array();
									$preload[] = JHTML::_('select.option', '0', JText::_('JLIST_BACKEND_CATSEDIT_ROOT_CAT_LISTBOX'));
							    	if ($sum_cats < 20){
                                        $catlist = treeSelectList( $cats, 0, $preload, 'catid', 'class="inputbox" size="10"', 'value', 'text', $row->cat_id);
                                    } elseif ($sum_cats < 40){
                                       $catlist = treeSelectList( $cats, 0, $preload, 'catid', 'class="inputbox" size="20"', 'value', 'text', $row->cat_id);
                                    } else {
                                       $catlist = treeSelectList( $cats, 0, $preload, 'catid', 'class="inputbox" size="25"', 'value', 'text', $row->cat_id);
                                    }  
									echo $catlist;                                
								?>
                                </td>
		    					<td valign ="top">
                                    <?php
                                    if ($row->cat_id) {
                                        echo JText::_('JLIST_BACKEND_CATSEDIT_CAT_LISTBOX_DESC')." ";
                                    } else {
                                        echo JText::_('JLIST_BACKEND_CATSEDIT_CAT_LISTBOX_DESC_NEW')." ";
                                    }
                                    ?>
                                </td>
                            </tr>
                            
		  					<tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_CATSEDIT_CAT_ACCESS_TITLE')." <b><font color=red>*</font></b>"; ?></strong><br />
		    					<?php echo $access_box; ?>
		    					</td>
		    					<td valign ="top"><?php echo ' '.JText::_('JLIST_BACKEND_CATSEDIT_CAT_ACCESS_DESC'); ?>
		    					</td>
		  					</tr>

                            <tr>
	       				        <td><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_PIC_TEXT')." "; ?></strong><br />
                                   <?php echo $inputbox_pic; ?>
					            </td>
                                <td valign ="top"><?php echo ' '.JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_PIC_DESC'); ?>
		    				    </td>
					        </tr>

					       <tr>
                                <td>
					               <script language="javascript" type="text/javascript">
					               if (document.adminForm.cat_pic.options.value!=''){
		      			               jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/catimages/'; ?>" + getSelectedText( 'adminForm', 'cat_pic' );
	           				       } else {
		          			              jsimg='';
				        	       }
					               document.write('<img src=' + jsimg + ' name="imagelib" width="32" height="32" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_PIC_PREVIEW'); ?>" />');
				    	           </script>
  					           </td>
                          </tr>
                           
                            <table>
		  					<tr>
		  						<td><strong><?php echo JText::_('JLIST_BACKEND_CATSEDIT_CAT_DESCRIPTION')." "; ?></strong><br />
		  							<?php
                                    if ($jlistConfig['categories.editor'] == "1") { 
									   echo $editor->display( 'cat_description',  @$row->cat_description , '100%', '500', '80', '5' ) ;
                                    } else {?>
                                       <textarea name="cat_description" rows="12" cols="50"><?php echo $row->cat_description; ?></textarea>
                                    <?php
                                    } ?>
		  						</td>
                            </tr>
                            </table>   
                            <table>
                            <tr>
                                <td valign="top"> <strong><?php echo JText::_('JLIST_BACKEND_EDIT_METADESC_TITLE')." "; ?></strong><br />
                                    <textarea name="metadesc" rows="3" cols="50"><?php echo $row->metadesc; ?></textarea>
                                </td>
                                <td valign ="top"><?php echo ' '.JText::_('JLIST_BACKEND_EDIT_METADESC_DESC'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <strong><?php echo JText::_('JLIST_BACKEND_EDIT_METAKEY_TITLE')." "; ?></strong><br />
                                    <textarea name="metakey" rows="3" cols="50"><?php echo $row->metakey; ?></textarea>
                                </td>
                                <td valign ="top"><?php echo ' '.JText::_('JLIST_BACKEND_EDIT_METAKEY_DESC'); ?>
                                </td>
		  					</tr>
                            </table>
		  				</table>
		  			</td>
		  		</tr>
			</table>
			</td>
		</tr>
	</table>
<br /><br />

		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="cat_id" value="<?php echo $row->cat_id; ?>" />
		<input type="hidden" name="cat_dir2" value="<?php echo $row->cat_dir; ?>" />
		<input type="hidden" name="cat_dir_org" value="<?php echo $row->cat_dir; ?>" />
        <input type="hidden" name="path_dir_entry" value="<?php echo $path_dir_entry; ?>" />  
        <input type="hidden" name="cat_title_org" value="<?php echo $row->cat_title; ?>" />
        <input type="hidden" name="parent_id" value="<?php echo $row->parent_id; ?>" />
        <input type="hidden" name="old_access" value="<?php echo $row->cat_access; ?>" /> 
		<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
        <input type="hidden" name="create_auto_cat_dir" value="<?php echo $jlistConfig['create.auto.cat.dir']; ?>" /> 
        <input type="hidden" name="task" value="" />
	</form>

<?php
}

// list cats
function categoriesList($rows, $option, $pageNav, $search, $filter, $tree, $task, $limitstart, $Itemid){
	global $mainframe, $jlistConfig;
    
    $database = &JFactory::getDBO();
	JHTML::_('behavior.tooltip');

    
?>
    <form action="index2.php" method="post" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		
		<tr>
			<td align="right" colspan="14">
				<?php echo JText::_('JLIST_BACKEND_CATSLIST_SEARCH')." ";
				if (!$search) {
				?>
				<input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" />
				<?php } else { ?>
				<b><input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" /></b>
				<?php }
				echo JText::_('JLIST_BACKEND_CATSLIST_SEARCH_LIMIT')." ";
				echo $pageNav->getLimitBox();
				?>
			</td>
	  </tr>
	  <tr>
          <td colspan="14">
              <?php
               if($jlistConfig['files.autodetect']){
                  checkFiles($task);
               } else {
                  echo '<div align="center"><b><font color="#FF6600">'.JText::_('JLIST_BACKEND_PANEL_STATUS_DOWNLOADS_OFF_DESC').'</font></b></div>';
               }
             ?>
          </td>
      </tr>
		<tr>
			<th width="5" align="left"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $tree ); ?>);" /></th>
            <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_CATSLIST_CATID')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_CATSLIST_TITLE')." "; ?></th>
			<th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_CATSLIST_COUNT')." "; ?></th>
            <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_CATSLIST_PIC')." "; ?></th>
            <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_CATSLIST_PATH')." "; ?></th>
			<th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_CATSLIST_LINK')." "; ?></th>
            <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_CATSLIST_READ')." "; ?></th>
            <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_CATSLIST_DOWNLOAD')." "; ?></th>
            <?php if(!$jlistConfig['files.autodetect']){ ?>
                <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_CATSLIST_SCAN_CATEGORY')." "; ?></th>
			<?php } ?>
            <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_CATSLIST_PUBLISHED')." "; ?></th>
			<th class="title" style="text-align: center" colspan="2" width="60"><?php echo JText::_('JLIST_BACKEND_CATSLIST_ORDERING')." "; ?></th>
			<th width="1%" style="text-align: center"><a href="javascript: saveorder( <?php echo count( $tree )-1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="Save Order" /></a></th>
        </tr>
		<?php
		$k = 0;

		
		
		for($i=0, $n=count( $tree ); $i < $n; $i++) { 
			$row = &$tree[$i];
			$row->id = $row->cat_id;
		
            // read sum of files from every cat
            $database->setQuery('SELECT COUNT(*) FROM #__jdownloads_files WHERE cat_id = '.$row->cat_id);
            $tree[$i]->sum_files = $database->loadResult();
        
			$link 		= 'index2.php?option=com_jdownloads&task=categories.edit&hidemainmenu=1&cid='.$row->cat_id;
			$checked 	= JHTML::_('grid.checkedout', $row, $i );
			$scan_link  = '<a href="'.JURI::base().'components/com_jdownloads/scancat.php?id='.$row->cat_id.'"  target="_blank" onclick="openWindow(this.href); return false" title="'.JText::_('JLIST_BACKEND_CATSLIST_SCAN_CATEGORY_TEXT').'"><img src="components/com_jdownloads/images/restore.png" width="24px" height="24px" align="middle" border="0"/></a>';
                                                                                                                                                                                                                                                  
            ?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $checked; ?>
            </td>

            <td align="center">
				<?php echo $row->cat_id; ?>
			</td>

			<td><a href="<?php echo $link; ?>" title="<?php echo JText::_('JLIST_BACKEND_CATSEDIT_TITLE');?>"><?php echo $row->cat_title; ?></a>
            </td>

            <td align="center">
                <?php echo $row->sum_files; ?>
            </td>

            <td align="center">
            <?php 
			if ($row->cat_pic != ''){
				?>
			<img src="<?php echo $mainframe->getSiteURL().'images/jdownloads/catimages/'.$row->cat_pic; ?>" width="32px" height="32px" align="middle" border="0"/>
            </td>
			<?php } ?>
            
			
			<td align="center">
            <?php
                echo JHTML::_('tooltip', $mainframe->getSiteURL().$jlistConfig['files.uploaddir'].'/'.$row->cat_dir, JText::_('JLIST_BACKEND_CATSLIST_PATH'));
            ?>
			</td>

			<td align="center">
				<?php
                if ($row->published) {
                    $url_cat_link = JRoute::_('<a href="'.$mainframe->getSiteURL().'index.php?option=com_jdownloads&amp;Itemid='.$Itemid.'&amp;view=viewcategory&amp;catid='.$row->id.'" target="_blank">'.JText::_('JLIST_BACKEND_CATSLIST_LINK_TEXT').'</a>');
                    echo $url_cat_link;
                } else {
                    echo '';
                }        
                ?>
			</td>

            <?php // set access info
              $access = array();
              $access[] = '<font color="green">'.JText::_('JLIST_BACKEND_CATSLIST_ACCESS_ALL').'</font>';
              $access[] = '<font color="#FF6600">'.JText::_('JLIST_BACKEND_CATSLIST_ACCESS_REGGED').'</font>';
              $access[] = '<font color="red">'.JText::_('JLIST_BACKEND_CATSLIST_ACCESS_ADMINS').'</font>';
              ?>
              <td align="center">
                  <?php echo $access[(int)substr($row->cat_access, 0, 1)]; ?>
              </td>
              <td align="center">
                  <?php echo $access[(int)substr($row->cat_access, 1, 1)]; ?>
              </td>
               <?php if(!$jlistConfig['files.autodetect']){ ?> 
                    <td align="center">
                        <?php echo $scan_link; ?>
                    </td>
               <?php } ?>
			<?php
			$task2 = $row->published ? 'categories.unpublish' : 'categories.publish';
			$img = $row->published ? 'publish_g.png' : 'publish_x.png';
			?>              

			<td align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task2;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a>
            </td>

			<td width="25" align="right">
				<?php		if ($i > 0) { ?>
				<a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','categories.orderup')">
				<img src="images/uparrow.png" width="12" height="12" border="0" alt="orderup">
				</a>
				<?php		} ?>
		  	</td>

			<td width="25" align="left">
				<?php		if ($i < $n-1) { ?>
				<a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','categories.orderdown')">
				<img src="images/downarrow.png" width="12" height="12" border="0" alt="orderdown">
				</a>
				<?php		}?>
			</td>
			<td align="center">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
			</td>
				<?php $k = 1 - $k;  } ?>
		</tr>
		<tr>
		  <td align="center" colspan="14"><?php echo $pageNav->getPagesLinks(); ?></td>
	  	</tr>
		<tr>
		  <td align="center" colspan="14"><?php echo $pageNav->getPagesCounter(); ?></td>
	  	</tr>
	</table> 
	<input type="hidden" name="boxchecked" value="0">
	<input type="hidden" name="option" value="<?php echo $option; ?>">
	<input type="hidden" name="task" value="categories.list">
	<input type="hidden" name="hidemainmenu" value="0">
	<input type="hidden" name="action" value="cat">
    <input type="hidden" name="limitstart" value="" />

</form>

<?php
}

/***********************************************************************
/                               FILES
/***********************************************************************/
function filesEdit($option, $row, $licenses, $up_files, $inputbox_pic, $listbox_system, $listbox_language, $no_writable, $inputbox_thumb, $inputbox_thumb2, $inputbox_thumb3, $cat_id, $update_files_listbox) {
	global $jlistConfig, $mainframe;
	$database = &JFactory::getDBO();
	jimport( 'joomla.html.pane');
	$editor =& JFactory::getEditor();
	?>
	<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
        
		if (pressbutton == 'files.cancel') {
			submitform( pressbutton );
			return;
		}

        // mirror files nur wenn intern oder extern angegeben
        if (form.extern_file){
          if (form.file_upload.value == "" && form.extern_file.value == ""){
            if (form.mirror_1.value != "" || form.mirror_2.value != ""){
                alert( "<?php echo JText::_('JLIST_BACKEND_FILESEDIT_ERROR_ONLY_MIRROR');?>" );
                return; 
            }
          }
        }
		// do field validation
		if (form.file_title.value == "" || form.cat_id2.value == 0){
			if (form.file_title.value == "" && radioValue(form.use_xml) == 0){
                    alert( "<?php echo JText::_('JLIST_BACKEND_FILESEDIT_ERROR_TITLE');?>" );
            } else {
                submitform( pressbutton );
            }    
            if (form.cat_id2.value == ""){
                alert( "<?php echo JText::_('JLIST_BACKEND_FILESEDIT_CATLIST_ERROR');?>" );
            }    
		} else {
			submitform( pressbutton );
		}
	}
    function radioValue(rObj) {
         for (var i=0; i<rObj.length; i++) if (rObj[i].checked) return rObj[i].value;
        return false;
    }
    
    function basename (path, suffix) {
        // Returns the filename component of the path  
        var b = path.replace(/^.*[\/\\]/g, '');
        if (typeof(suffix) == 'string' && b.substr(b.length-suffix.length) == suffix) {
            b = b.substr(0, b.length-suffix.length);
        }
        return b;
    }
	</script>

	<form action="index2.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	
	<div align="right">
	<?php 
		// download was sent in from frontend
		if ($row->created_mail) { 
			echo '<div><font color="#666666"><small>'.JText::_('JLIST_BACKEND_FILESEDIT_SENT_IN_FROM').'<font color="#990000">'.$row->created_by.'</font> '.JText::_('JLIST_BACKEND_FILESEDIT_SENT_IN_FROM_EMAIL').'<font color="#990000">'.$row->created_mail.'</font></small></font></div>';
		} else {
			echo '&nbsp;<br /></div>';		
		}
	
        $publish = array();
        $publish[] = JHTML::_('select.option', '0', JText::_('JLIST_FE_NO'));
        $publish[] = JHTML::_('select.option', '1', JText::_('JLIST_FE_YES'));
        $publish = JHTML::_('select.genericlist', $publish, "publish", 'size="1" class="inputbox"', 'value', 'text', $row->published );
        echo '<div align="center"><b><font color="#990000">'.JText::_('JLIST_BACKEND_EDIT_PUBLISHED').'</b>: '.$publish.'</font> ';
    
        if ($row->file_id){
        $update = array();
        $update[] = JHTML::_('select.option', '0', JText::_('JLIST_FE_NO'));
        $update[] = JHTML::_('select.option', '1', JText::_('JLIST_FE_YES'));
        $update = JHTML::_('select.genericlist', $update, "update", 'size="1" class="inputbox"', 'value', 'text', $row->update_active );
        echo '&nbsp;&nbsp;<b><font color="#990000">'.JText::_('JLIST_BACKEND_EDIT_FILES_SET_UPDATE_TITLE').'</b>: '.$update.'</font> '.JHTML::_('tooltip',JText::_('JLIST_BACKEND_EDIT_FILES_SET_UPDATE_TEXT')).'</div>';   
        }
    
$pane =& JPane::getInstance('Tabs');
echo $pane->startPane('editfile');
echo $pane->startPanel(JText::_('JLIST_BACKEND_FILESEDIT_TABTITLE_1'),'daten1');
?>
<table width="100%" border="0">
	<tr>
		<td width="40%" valign="top">
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo $row->file_id ? JText::_('JLIST_BACKEND_FILESEDIT_EDIT') : JText::_('JLIST_BACKEND_FILESEDIT_ADD');?></th>
		      	</tr>
		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table width="100%">
    	  					<tr>
		    					<td colspan="3"><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_REQUIRES_INFO')." "; ?><br /><br />
                                <?php echo JText::_('JLIST_BACKEND_FILESEDIT_FILE_TITLE')." "; ?></strong><br />
                                    <input name="file_title" value="<?php echo $row->file_title; ?>" size="60" maxlength="255"/>

                                    <strong><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_RELEASE')." "; ?></strong>
		    						<input name="release" value="<?php echo $row->release; ?>" size="15" maxlength="255"/>

                                    <strong><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_SYSTEM')." "; ?></strong>
                                    <?php echo $listbox_system; ?>

                                    <strong><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_LANGUAGE')." "; ?></strong>
                                    <?php echo $listbox_language; ?>
                                    
                                    <strong><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_LICENSE')." "; ?></strong>
                                    <?php
                                    $templic = (int)$row->license;
                                    $inputbox_lic = JHTML::_('select.genericlist', $licenses, 'license', 'size="1" class="inputbox"', 'value', 'text', $templic );
                                    echo $inputbox_lic; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong><?php echo JText::_('JLIST_EDIT_ALIAS_NAME_TITLE')." "; ?></strong><br />
                                    <input name="file_alias" value="<?php echo $row->file_alias; ?>" size="60" maxlength="255"/>
                                </td>
                                <td>
                                    <br /><?php echo JText::_('JLIST_EDIT_ALIAS_NAME_DESC')." "; ?>
                               </td>    
                            </tr>
		  					</tr>
		  					<tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_FILE_CAT')." "; ?></strong><br />
		    					<?php 
									// build cat tree listbox
									$src_list = array();
									$query = "SELECT cat_id AS id, parent_id AS parent, cat_title AS name FROM #__jdownloads_cats ORDER BY cat_title";
									$database->setQuery( $query );
									$cats2 = $database->loadObjectList();
                                    $sum_cats = count($cats2);
									$preload = array();
                                    if ($sum_cats < 20){
                                        $catlist= treeSelectList( $cats2, 0, $preload, 'cat_id2', 'class="inputbox" size="10"', 'value', 'text', $row->cat_id);
                                    } elseif ($sum_cats < 50){
                                       $catlist= treeSelectList( $cats2, 0, $preload, 'cat_id2', 'class="inputbox" size="15"', 'value', 'text', $row->cat_id);
                                    } else {
                                       $catlist= treeSelectList( $cats2, 0, $preload, 'cat_id2', 'class="inputbox" size="20"', 'value', 'text', $row->cat_id);
                                    } 
                                    echo $catlist;	?>
		    					</td>
		    					<td valign="top"><br />
                                    <?php echo JText::_('JLIST_BACKEND_FILESEDIT_FILE_CAT_DESC')." "; ?>
		    					</td>
		  					</tr>
		  					<tr>
	       				        <td><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_FPIC_TEXT')." "; ?></strong><br />
                                   <?php echo $inputbox_pic; ?>
					           </td>
					           <td>
                                    <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_FPIC_DESC')." "; ?>
					           </td>
					        </tr>
					       <tr>
                                <td>
					               <script language="javascript" type="text/javascript">
					               if (document.adminForm.file_pic.options.value!=''){
		      			               jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/fileimages/'; ?>" + getSelectedText( 'adminForm', 'file_pic' );
	           				       } else {
		          			              jsimg='';
				        	       }
					               document.write('<img src=' + jsimg + ' name="imagelib" width="32" height="32" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_FPIC_PREVIEW'); ?>" />');
				    	           </script>
  					           </td>
                          </tr>
							<table>
		  					<tr>
		    					<td colspan="2"><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_DESCRIPTION_KURZ')." "; ?></strong><br />
                                <?php
                                if ($jlistConfig['files.editor'] == "1") {
                                    echo $editor->display( 'description',  @$row->description , '100%', '350', '75', '20' ) ;
                                } else {?>
                                    <textarea name="description" rows="4" cols="60"><?php echo $row->description; ?></textarea>
                                <?php
                                } ?>
		    					</td>
		    					<td valign="top">
		    					     <?php echo ' '.JText::_('JLIST_BACKEND_FILESEDIT_EDITOR_INFO')." "; ?>
		    					</td>
		  					</tr>
		  					</table>
                            <table>
                            <tr>
		    					<td colspan="2"><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_DESCRIPTION_LONG')." "; ?></strong><br />
                                <?php
                                if ($jlistConfig['files.editor'] == "1") {
                                    echo $editor->display( 'description_long',  @$row->description_long , '100%', '500', '80', '5' ) ;
                                } else {?>
                                    <textarea name="description_long" rows="8" cols="60"><?php echo $row->description_long; ?></textarea>
                                <?php
                                } ?>
		    					</td>
		  					</tr>
		  					</table>
		  					
                        </table>
                   </td>
                  </tr>
                 </table>
			</td>
		</tr>
	</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_FILESEDIT_TABTITLE_2'),'daten2');
?>
<table width="100%" border="0">
	<tr>
		<td width="40%" valign="top">
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo $row->file_id ? JText::_('JLIST_BACKEND_FILESEDIT_EDIT') : JText::_('JLIST_BACKEND_FILESEDIT_ADD');?></th>
		      	</tr>
		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table width="100%">

                            <tr>
		    				  <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_HITS')." "; ?></strong><br />
	    						<input name="downloads" value="<?php echo $row->downloads; ?>" size="10"/>
  	    					  </td>
                              <td><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_HITS_DESC'); ?>
		    				  </td>
		  					</tr>

                            <tr>
                              <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_PRICE')." "; ?></strong><br />
                                <input name="price" value="<?php echo $row->price; ?>" size="10" maxlength="20"/>
                                </td>
                              <td><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_PRICE_DESC'); ?></td>
                            </tr>                            
                            
		  					
		  					<tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_SIZE')." "; ?></strong><br />
		    						<input name="size" value="<?php echo $row->size; ?>" size="25" maxlength="50"/>
		    					</td>
                                <td>
                                    <?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_SIZE_DESC'); ?>
		    					</td>
		  					</tr>

		  					<tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_DADDED')." "; ?></strong><br />
		    						<input name="date_added" id="date_added" value="<?php echo $row->date_added; ?>" size="25"/>
		    						<input name="reset" type="reset" class="button" onclick="return showCalendar('date_added', '%Y-%m-%d')" value="..." />
		    					</td> 
                                <td><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_DADDED_DESC'); ?>
		    					</td>
		  					</tr>
                            <tr>
                                <td><strong><?php echo JText::_('JLIST_EDIT_FILE_FILE_DATE_TITLE')." "; ?></strong><br />
                                    <input name="file_date" id="file_date" value="<?php echo $row->file_date; ?>" size="25"/>
                                    <input name="reset" type="reset" class="button" onclick="return showCalendar('file_date', '%Y-%m-%d')" value="..." />
                                </td> 
                                <td><?php echo " ".JText::_('JLIST_EDIT_FILE_FILE_DATE_DESC'); ?>
                                </td>
                              </tr>
                            <?php // not used in 1.7.1 
                            /* 
                            <tr><td colspan="3"><hr></td></tr> 
                           <tr>
                                <td><strong><?php echo JText::_('JLIST_EDIT_FILE_START_END_DATE_ACTIVATE_TITLE')." "; ?></strong><br />
                                    <?php echo JHTML::_('select.booleanlist',"use_timeframe",'',($row->use_timeframe) ? 1:0);?> 
                                 </td>
                                <td valign ="top"><?php echo JText::_('JLIST_EDIT_FILE_START_END_DATE_ACTIVATE_DESC'); ?>
                                  </td>
                           </tr> 
                           <tr>
                                <td><strong><?php echo JText::_('JLIST_EDIT_FILE_START_DATE_TITLE')." "; ?></strong><br />
                                    <input name="publish_from" id="publish_from" value="<?php echo $row->publish_from; ?>" size="25"/>
                                    <input name="reset" type="reset" class="button" onclick="return showCalendar('publish_from', '%Y-%m-%d')" value="..." />
                                </td> 
                                <td>
                                </td>
                           </tr>
                           <tr>
                                <td><strong><?php echo JText::_('JLIST_EDIT_FILE_END_DATE_TITLE')." "; ?></strong><br />
                                    <input name="publish_to" id="publish_to" value="<?php echo $row->publish_to; ?>" size="25"/>
                                    <input name="reset" type="reset" class="button" onclick="return showCalendar('publish_to', '%Y-%m-%d')" value="..." />
                                </td> 
                                <td>
                                </td>
                            </tr>                                                       
                            */ ?> 
                            <?php if ($row->file_id) { ?>
                            <tr><td colspan="3"><hr></td></tr>   
                            <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_CREATED_BY')." "; ?></strong><br />
                                   <input name="created_by" id="created_by" value="<?php echo $row->created_by; ?>" size="25" maxlength="255"/>
                                </td>
                                </td> 
                                <td><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_INFO_FIELDS_DESC'); ?>
                                </td>
                            </tr>
                            <tr>     
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_MODIFIED_DATE')." "; ?></strong><br />
                                <input name="modified_date" id="modified_date" value="<?php echo $row->modified_date; ?>" size="25"/>
                                </td>
                                </td> 
                                <td><?php echo " "; ?>
                                </td>
                            </tr>
                            <tr>     
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_MODIFIED_BY')." "; ?></strong><br />
                                <input name="modified_by" id="modified_by" value="<?php echo $row->modified_by; ?>" size="25" maxlength="255"/>
                                </td>
                                </td> 
                                <td><?php echo " "; ?>
                                </td>
                            </tr>
                            <?php } ?>
                            
		  					 <tr><td colspan="3"><hr></td></tr>   
                            <tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_AUTHOR_INFOS_TITLE')." "; ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_URL_HOME')." "; ?></strong><br />
		    						<input name="url_home" value="<?php echo $row->url_home; ?>" size="70" maxlength="255"/>
		    					</td>
                                <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_URL_HOME_DESC'); ?>
                                </td>
		  					</tr>

		  					<tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_AUTHOR')." "; ?></strong><br />
		    						<input name="author" value="<?php echo $row->author; ?>" size="70" maxlength="255"/>
		    					</td>
                                <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_AUTHOR_DESC'); ?>
                                </td>
		  					</tr>

		  					<tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_AUTHOR_URL')." "; ?></strong><br />
		    						<input name="url_author" value="<?php echo $row->url_author; ?>" size="70" maxlength="255"/>
		    					</td>
		    					<td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_AUTHOR_URL_DESC'); ?>
		    					</td>
		  					</tr>
                             <tr><td colspan="3"><hr></td></tr>   
                            <tr>
                                <td valign="top"> <strong><?php echo JText::_('JLIST_BACKEND_EDIT_METADESC_TITLE')." "; ?></strong><br />
                                    <textarea name="metadesc" rows="3" cols="40"><?php echo $row->metadesc; ?></textarea>
                                </td>
                                <td valign ="top"><?php echo ' '.JText::_('JLIST_BACKEND_EDIT_METADESC_DESC'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <strong><?php echo JText::_('JLIST_BACKEND_EDIT_METAKEY_TITLE')." "; ?></strong><br />
                                    <textarea name="metakey" rows="3" cols="40"><?php echo $row->metakey; ?></textarea>
                                </td>
                                <td valign ="top"><?php echo ' '.JText::_('JLIST_BACKEND_EDIT_METAKEY_DESC'); ?>
                                </td>
                            </tr>

                        </table>
                   </td>
                  </tr>
                 </table>
			</td>
		</tr>
	</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_FILESEDIT_TABTITLE_3'),'daten3');
?>

<table width="100%" border="0">
	<tr>
		<td width="40%" valign="top">
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo $row->file_id ? JText::_('JLIST_BACKEND_FILESEDIT_EDIT') : JText::_('JLIST_BACKEND_FILESEDIT_ADD');?></th>
		      	</tr>
		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table width="100%">
                            <?php
                            $writable = is_writable(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/') ? JText::_('JLIST_BACKEND_FILESEDIT_URL_DOWNLOAD_WRITABLE') : JText::_('JLIST_BACKEND_FILESEDIT_URL_DOWNLOAD_NOTWRITABLE');
		  					if (!$writable) {
                             ?>
                                <tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_URL_DOWNLOAD')." "; ?></strong><br />
		    					<?php
                                    echo JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/ - '.$writable;
		    					?>
		    					</td>
                                <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_URL_DOWNLOAD_STATUS_DESC'); ?>
  	    					    </td>
		  					</tr>
                            <?php } ?>
		  					<tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_FILE')." "; ?></strong><br />
		    						<input name="file_upload" size="50"  type="file"/><br /><?php echo JText::_('JLIST_UPLOAD_MAX_FILESIZE_INFO_TITLE').' '. ini_get('upload_max_filesize'); ?>
                                </td>
                                <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_FILE_DESC'); ?>
  	    					    </td>
                            </tr>

                             <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_FILE_UPDATE_FILE_TITLE')." "; ?></strong><br />
                                <?php echo $update_files_listbox; ?>
                                 <br /></td>
                                <td valign ="top"><?php echo JText::_('JLIST_BACKEND_FILESEDIT_FILE_UPDATE_FILE_DESC');?>
                                  </td>
                              </tr>

                            <tr>
                                <td><strong><?php echo JText::_('JLIST_BE_EDIT_FILES_USE_XML_TITLE')." "; ?></strong><br />
                                    <?php echo JHTML::_('select.booleanlist',"use_xml",'0', '' ? 1:0);?> 
                                 </td>
                                <td valign ="top"><?php echo JText::_('JLIST_BE_EDIT_FILES_USE_XML_DESC'); ?>
                                  </td>
                             </tr>
                              
                            <?php
                       if($row->url_download != ''){ ?>
                            <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_FILE_ADD_FROM_SERVER')." "; ?></strong><br /><br /><big><font color="#990000">
                                <?php echo $row->url_download; ?>
                                 </font></big><br /></td>
                                <td valign ="top"><?php echo JText::_('JLIST_BACKEND_FILE_EDIT_EXT_DOWNLOAD_INFO');?><br /><br />
                                     <a href="index2.php?option=<?php echo $option;?>&task=files.remove&cid=<?php echo $row->file_id;?>"><?php echo JText::_('JLIST_BACKEND_FILESEDIT_FILE_REMOVE');?><br /></a>
                                  </td>
                              </tr>

                     <?php } else { ?>

                            <tr>
                                <td><strong><font color="#990000"><?php echo JText::_('JLIST_BACKEND_FILESEDIT_FILE_ADD_FROM_SERVER_NO')." "; ?></font></strong><br />
                                  </td>
                              </tr>
                              
                              <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILE_EDIT_EXT_DOWNLOAD_TITLE')." "; ?></strong><br />
                                 <input name="extern_file" value="<?php echo $row->extern_file; ?>" size="70" maxlength="255" />
                                 </td>
                                <td valign ="top"><?php echo JText::_('JLIST_BACKEND_FILE_EDIT_EXT_DOWNLOAD_DESC'); ?>
                                  </td>
                              </tr>
                              <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_OPEN_LINK_IN_OTHER_WEBSITE_TITLE')." "; ?></strong><br />
                                    <?php echo JHTML::_('select.booleanlist',"extern_site",'',($row->extern_site) ? 1:0);?> 
                                 </td>
                                <td valign ="top"><?php echo JText::_('JLIST_BACKEND_FILESEDIT_OPEN_LINK_IN_OTHER_WEBSITE_DESC'); ?>
                                  </td>
                              </tr>
                    <?php } ?>
                              <tr><td colspan="3"><hr></td></tr>  
                              <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_MIRROR_URL_TITLE')." "; ?></strong><br />
                                 <input name="mirror_1" value="<?php echo $row->mirror_1 ?>" size="70" maxlength="255"/>
                                 </td>
                                <td valign ="top"><?php echo JText::_('JLIST_BACKEND_FILESEDIT_MIRROR_URL_DESC'); ?>
                                  </td>
                              </tr>
                              <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_OPEN_LINK_IN_OTHER_WEBSITE_TITLE')." "; ?></strong><br />
                                    <?php echo JHTML::_('select.booleanlist',"extern_site_mirror_1",'',($row->extern_site_mirror_1) ? 1:0);?> 
                                 </td>
                                <td valign ="top"><?php echo JText::_('JLIST_BACKEND_FILESEDIT_OPEN_LINK_IN_OTHER_WEBSITE_DESC'); ?>
                                  </td>
                              </tr>
                              
                              <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_MIRROR_URL_TITLE')." "; ?></strong><br />
                                 <input name="mirror_2" value="<?php echo $row->mirror_2 ?>" size="70" maxlength="255"/>
                                 </td>
                                <td valign ="top"><?php echo JText::_('JLIST_BACKEND_FILESEDIT_MIRROR_URL_DESC'); ?>
                                  </td>
                              </tr>
                              <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_OPEN_LINK_IN_OTHER_WEBSITE_TITLE')." "; ?></strong><br />
                                    <?php echo JHTML::_('select.booleanlist',"extern_site_mirror_2",'',($row->extern_site_mirror_2) ? 1:0);?> 
                                 </td>
                                <td valign ="top"><?php echo JText::_('JLIST_BACKEND_FILESEDIT_OPEN_LINK_IN_OTHER_WEBSITE_DESC'); ?>
                                  </td>
                              </tr>
                              <tr><td colspan="3"><hr></td></tr>
                              <tr>
                                   <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_LIST_TITLE')." "; ?></strong><br />  
                                   <?php echo $inputbox_thumb; ?>
                               </td>
                               <td>
                                    <?php echo JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_LIST_DESC')." "; ?>
                               </td>
                            </tr>

                           <tr>
                                <td>
                                   <script language="javascript" type="text/javascript">
                                   if (document.adminForm.file_thumb.options.value!=''){
                                         jsimg2="<?php echo $mainframe->getSiteURL().'images/jdownloads/screenshots/thumbnails/'; ?>" + getSelectedText( 'adminForm', 'file_thumb' );
                                      } else {
                                            jsimg2='';
                                   }
                                   document.write('<img src=' + jsimg2 + ' name="imagelib4" border="1" border-color="#555555" alt="" />');
                                   </script>
                                 </td>
                          </tr>                              
                          <tr>
                             <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_UPLOAD_TITLE')." "; ?></strong><br />
                                  <input name="file_upload_thumb" size="50"  type="file"/>
                              </td>
                              <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_UPLOAD_DESC'); ?>
                              </td>
                          </tr>
                          
                          <tr><td colspan="3"><hr></td></tr>
                              <tr>
                                   <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_LIST_TITLE')." "; ?></strong><br />  
                                   <?php echo $inputbox_thumb2; ?>
                               </td>
                               <td>
                                    <?php echo JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_LIST_DESC')." "; ?>
                               </td>
                            </tr>

                           <tr>
                                <td>
                                   <script language="javascript" type="text/javascript">
                                   if (document.adminForm.file_thumb2.options.value!=''){
                                         jsimg3="<?php echo $mainframe->getSiteURL().'images/jdownloads/screenshots/thumbnails/'; ?>" + getSelectedText( 'adminForm', 'file_thumb2' );
                                      } else {
                                            jsimg3='';
                                   }
                                   document.write('<img src=' + jsimg3 + ' name="imagelib5" border="1" border-color="#555555" alt="" />');
                                   </script>
                                 </td>
                          </tr>                              
                          <tr>
                             <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_UPLOAD_TITLE')." "; ?></strong><br />
                                  <input name="file_upload_thumb2" size="50"  type="file"/>
                              </td>
                              <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_UPLOAD_DESC'); ?>
                              </td>
                          </tr>
                          
<tr><td colspan="3"><hr></td></tr>
                              <tr>
                                   <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_LIST_TITLE')." "; ?></strong><br />  
                                   <?php echo $inputbox_thumb3; ?>
                               </td>
                               <td>
                                    <?php echo JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_LIST_DESC')." "; ?>
                               </td>
                            </tr>

                           <tr>
                                <td>
                                   <script language="javascript" type="text/javascript">
                                   if (document.adminForm.file_thumb3.options.value!=''){
                                         jsimg4="<?php echo $mainframe->getSiteURL().'images/jdownloads/screenshots/thumbnails/'; ?>" + getSelectedText( 'adminForm', 'file_thumb3' );
                                      } else {
                                            jsimg4='';
                                   }
                                   document.write('<img src=' + jsimg4 + ' name="imagelib6" border="1" border-color="#555555" alt="" />');
                                   </script>  
                                 </td>
                          </tr>                              
                          <tr>
                             <td><strong><?php echo JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_UPLOAD_TITLE')." "; ?></strong><br />
                                  <input name="file_upload_thumb3" size="50"  type="file"/>
                              </td>
                              <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILESEDIT_THUMBNAIL_UPLOAD_DESC'); ?>
                              </td>
                          </tr>                                                    
                    </table>
		  			</td>
		  		</tr>
				</table>
			</td>
		</tr>
	</table>

<?php
echo $pane->endPanel();            
if ($jlistConfig['pad.exists'] & $jlistConfig['pad.use']){
echo $pane->startPanel(JText::_('JLIST_BACKEND_FILE_EDIT_PAD_TAB_TITLE'),'padfile');
?>

<table width="100%" border="0">
    <tr>
        <td width="40%" valign="top">
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_FILE_EDIT_PAD_HEAD');?></th>
                  </tr>
                  <tr>
                      <td valign="top" align="left" width="100%">
                          <table width="100%">

                              <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILE_EDIT_PAD_FOLDER_STATE')." "; ?></strong><br />
                                <?php
                                $dis = '';
                                if (!is_dir(JPATH_SITE.'/'.$jlistConfig['pad.folder'])){
                                   echo JPATH_SITE.'/'.$jlistConfig['pad.folder'].' - <font color="red"><b>'.JText::_('JLIST_BACKEND_FILE_EDIT_PAD_FOLDER_NOT_EXISTS').'</b></font>';
                                    $dis = 'disabled="disabled"';
                                   ?>
                                   </td>
                                   <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILE_EDIT_PAD_FOLDER_STATE_DESC'); ?>
                                  </td>
                              </tr> <?php
                                } else {    
                                   $writable = is_writable(JPATH_SITE.'/'.$jlistConfig['pad.folder'].'/') ? JText::_('JLIST_BACKEND_FILESEDIT_URL_DOWNLOAD_WRITABLE') : JText::_('JLIST_BACKEND_FILESEDIT_URL_DOWNLOAD_NOTWRITABLE');
                                   echo JPATH_SITE.'/'.$jlistConfig['pad.folder'].'/ - '.$writable;
                                   ?>
                                   </td>
                                   <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILE_EDIT_PAD_FOLDER_STATE_DESC'); ?>
                                   </td>
                                   </tr>
                                <?php }
                                ?>
                                
                              <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILE_EDIT_PAD_FILE_TITLE')." "; ?></strong><br />
                                    <input name="pad_upload" <?php echo $dis; ?> size="50" onchange="document.adminForm.file_title.value=basename(this.value,'.xml')" type="file"/>
                                </td>
                                <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILE_EDIT_PAD_FILE_DESC'); ?>
                               </td>
                            </tr>
                      <?php /* if($row->url_download != ''){ 
                               $filename = substr($row->url_download, 0, strrpos($row->url_download, '.') );
                               if (is_file(JPATH_SITE.DS.$jlistConfig['pad.folder'].DS.$filename.'.xml')){ ?>    
                                <tr>
                                <td><strong><?php echo JText::_('JLIST_BACKEND_FILE_EDIT_PAD_ASSIGN_PAD_TITLE')." "; ?></strong><br />
                                    
                                </td>
                                <td valign ="top"><?php echo " ".JText::_('JLIST_BACKEND_FILE_EDIT_PAD_ASSIGN_PAD_DESC'); ?>
                               </td>
                               </tr>
                      <?php 
                               } 
                           } */ ?>     
                           
                          </table>
                      </td>
                  </tr>
                </table>
            </td>
        </tr>
    </table>

<?php
echo $pane->endPanel();            
}
echo $pane->endPane('editfile');?> 

		<input type="hidden" name="hidemainmenu" value="1">
        <input type="hidden" name="ordering" value="<?php echo $row->ordering;?>"/>
        <input type="hidden" name="published" value="<?php echo $row->published;?>"/>
        <input type="hidden" name="update_active" value="<?php echo $row->update_active;?>"/>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="file_id" value="<?php echo $row->file_id; ?>" />
		<input type="hidden" name="cat_id" value="<?php if ($row->cat_id) {echo $row->cat_id;}else{echo $cat_id;} ?>" />
		<input type="hidden" name="filename" value="<?php echo $row->url_download; ?>" />		
        <input type="hidden" name="modified_date_old" value="<?php echo $row->modified_date; ?>" />        
		<input type="hidden" name="task" value="" />
	</form>

<?php
}

// files list
function filesList($rows, $option, $pageNav, $search, $filter, $task, $limitstart){
	global $mainframe, $jlistConfig;
	$database = &JFactory::getDBO();

    // f�r ToolTip
    JHTML::_('behavior.tooltip');

    $cat_id = JArrayHelper::getValue($_REQUEST,'cat_id',-1);
?>
	<form action="index2.php" method="post" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		
		<tr>
            <td colspan="7" align="left">
				<?php
				 // 1.5 Native to-do
                $del_files_option = yesnoSelectList( 'delete_files', 'class="inputbox"', '1', JText::_('JLIST_FE_YES'), JText::_('JLIST_FE_NO') );
                echo ' '.JText::_('JLIST_BACKEND_FILESLIST_DEL_FILES_OPTION')." ".$del_files_option.' '.JHTML::_('tooltip',JText::_('JLIST_BACKEND_FILESLIST_DEL_FILES_OPTION_TOOLTIP')); ?>
            </td>
        	<td colspan="8" align="right">
				<?php echo JText::_('JLIST_BACKEND_FILESLIST_SEARCH')." ";?>
				<input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" />
				<?php
				echo JText::_('JLIST_BACKEND_FILESLIST_SEARCH_LIMIT')." ";
				echo $pageNav->getLimitBox().' ';

	            // build cat tree listbox
		        $src_list = array();
		        $query = "SELECT cat_id AS id, parent_id AS parent, cat_title AS name FROM #__jdownloads_cats ORDER BY ordering";
		        $database->setQuery( $query );
		        $cats = $database->loadObjectList();
		        $preload = array();
		        $preload[] = JHTML::_('select.option', '-1', JText::_('JLIST_BACKEND_FILESLIST_CATS') );
		        $preload[] = JHTML::_('select.option', '0',  JText::_('JLIST_BACKEND_FILESLIST_NOCATS') );
                $preload[] = JHTML::_('select.option', '-2', JText::_('JLIST_BACKEND_FILESLIST_FILTER_PUBLISHED') );		
                $preload[] = JHTML::_('select.option', '-3', JText::_('JLIST_BACKEND_FILESLIST_FILTER_UNPUBLISHED') );
    	        $html= treeSelectList( $cats, 0, $preload, 'cat_id',
                         'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $cat_id);
				echo $html;
				?>
			</td>
	  </tr>
	  <tr>
		  <td colspan="15">
              <?php
               if($jlistConfig['files.autodetect']){
                  checkFiles($task);
               } else {
                  echo '<div align="center"><b><font color="#FF6600">'.JText::_('JLIST_BACKEND_PANEL_STATUS_DOWNLOADS_OFF_DESC').'</font></b></div>';
               }
             ?>
          </td>
	  </tr>

		<tr>
			<th width="5" align="left"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
            <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_FILESLIST_FILE_ID')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_FILESLIST_FILE')." "; ?></th>
			<th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_FILESLIST_RELEASE')." "; ?></th>
            <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_FILESLIST_PIC')." "; ?></th>
            <th class="title"><?php echo JText::_('JLIST_BACKEND_FILESLIST_CAT')." "; ?></th>
			<th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_FILESLIST_DESCRIPTION')." "; ?></th>
            <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_FILESLIST_FILENAME')." "; ?></th>
			<th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_FILESEDIT_PRICE')." "; ?></th>
            <th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_FILESLIST_DADDED')." "; ?></th>
			<th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_FILESLIST_HITS')." "; ?></th>
			<th class="title" style="text-align: center"><?php echo JText::_('JLIST_BACKEND_FILESLIST_PUBLISHED')." "; ?></th>
			<th class="title" colspan="2" width="60" style="text-align: center">
            <?php echo JText::_('JLIST_BACKEND_FILESLIST_ORDERING')." "; ?></th>
			<th width="1%" style="text-align: center"><a href="javascript: saveorder( <?php echo count( $rows )-1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="Save Order" /></a></th>
		</tr>
		<?php
		$k = 0;
		for($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			$row->id = $row->file_id;
			$link 		= 'index2.php?option=com_jdownloads&amp;task=files.edit&amp;hidemainmenu=1&amp;cid='.$row->file_id;
			$checked 	= JHTML::_('grid.checkedout', $row, $i );
			?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $checked; ?></td>

            <td align="center">
				<?php echo $row->file_id; ?>
			</td>

        	<td><a href="<?php echo $link; ?>" ><?php echo $row->file_title; ?></a></td>

			<td align="center"><?php  echo $row->release; ?></td>
			
			<td align="center">
                <?php if($row->file_pic){ ?>
                    <img src="<?php echo $mainframe->getSiteURL().'images/jdownloads/fileimages/'.$row->file_pic; ?>" width="24px" height="24px" align="middle" border="0"/> 
                <?php } ?>
            </td>

			<td><?php
               $cat_link = 'index2.php?option=com_jdownloads&amp;task=categories.edit&hidemainmenu=1&cid='.$row->cat_id; ?>
               <a href="<?php echo $cat_link; ?>"  title="<?php echo JText::_('JLIST_BACKEND_CATSEDIT_TITLE'); ?>"><?php echo $row->cat_title; ?></a>
             </td>

			<td align="center">
            <?php
                if (strlen($row->description) > 200 ) {
                    $description_short =  str_replace("'", " ", substr($row->description, 0, 200).' ...');
                } else {
                    $description_short = str_replace(chr(39), " ", $row->description);
                }
                if ($description_short != '') {
                    echo JHTML::_('tooltip',strip_tags($description_short), JText::_('JLIST_BACKEND_FILESLIST_DESCRIPTION_SHORT'));
                }
            ?>
            </td>
            
            <td align="center">
            <?php if ($row->url_download != '') {
                echo JHTML::_('tooltip',$row->url_download);
            } else {
                if ($row->extern_file != '') {
                    echo JHTML::_('tooltip',$row->extern_file);
                }       
            }        
            ?></td>

            <td align="center"><?php echo $row->price;?></td> 
			<td align="center"><?php echo substr(JHTML::_('date',$row->date_added, $jlistConfig['global.datetime'] ,$offset = NULL),0,10); ?></td>
			<td align="center"><?php echo $row->downloads;?></td>

			<?php
			$task2 = $row->published ? 'files.unpublish' : 'files.publish';
			$img = $row->published ? 'publish_g.png' : 'publish_x.png';
			?>
			<td align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task2;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>
			<td width="25" align="right">
				<?php		if ($i > 0) { ?>
				<a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','files.orderup')">
				<img src="images/uparrow.png" width="12" height="12" border="0" alt="orderup">
				</a>
				<?php		} ?>
		  	</td>
			<td width="25" align="left">
				<?php		if ($i < $n-1) { ?>
				<a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','files.orderdown')">
				<img src="images/downarrow.png" width="12" height="12" border="0" alt="orderdown">
				</a>
				<?php		}?>
			</td>
			<td align="center">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
			</td>
				<?php $k = 1 - $k;  } ?>
		</tr>
		<tr>
		  <td align="center" colspan="15"><?php echo $pageNav->getPagesLinks(); ?></td>
	  	</tr>
		<tr>
		  <td align="center" colspan="15"><?php echo $pageNav->getPagesCounter(); ?></td>
	  	</tr>
	</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="files.list" />
	<input type="hidden" name="hidemainmenu" value="0">
    <input type="hidden" name="action" value="file">
    <input type="hidden" name="limitstart" value="" />

 </form>

<?php
}

// Dateien kopieren (ohne dateizuordnung)
function filesCopy($option, $files_id, $files, $cat_id){
    $database = &JFactory::getDBO();
    
    ?>
    <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
        var form = document.adminForm;
        
        if (pressbutton == 'files.list') {
            submitform( pressbutton );
            return;
        }
        
        // do field validation
        if (form.cat_id2.value == 0){
            alert( "<?php echo JText::_('JLIST_BACKEND_FILESEDIT_CATLIST_ERROR');?>" );
        } else {
            submitform( pressbutton );
        }
    }
    </script>
   

    <form action="index2.php" method="post" name="adminForm">
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
        
        <tr>
            <th  colspan="3" class="title"><?php echo JText::_('JLIST_BACKEND_FILES_COPY_TITLE').' '; ?></th>
        </tr>
        <tr>
           <td width="30%" valign="top"><b><?php echo JText::_('JLIST_BACKEND_FILES_COPY_DESC'); ?></b><br /><br />
       <?php 
           // build cat tree listbox
           $src_list = array();
           $query = "SELECT cat_id AS id, parent_id AS parent, cat_title AS name FROM #__jdownloads_cats ORDER BY ordering";
           $database->setQuery( $query );
           $cats2 = $database->loadObjectList();
           $preload = array();
           $catlist= treeSelectList( $cats2, 0, $preload, 'cat_id2', 'class="inputbox" size="10"', 'value', 'text', $row->cat_id);
           echo $catlist.'<br />';
           echo '<div align="left"><br /><b>'.JText::_('JLIST_BACKEND_FILES_COPY_TEXT_3').'</b>:<br />'.JHTML::_('select.booleanlist',"filespublish","",(0) ? 1:0).'</div>';
           echo '<div align="left"><br /><b>'.JText::_('JLIST_BACKEND_FILES_COPY_TEXT_4').'</b>:<br />'.JHTML::_('select.booleanlist',"copyalsofiles","",(0) ? 1:0).'</div>';
      ?> 
       </td>
       <td width="30%" valign="top">
       <b><?php echo JText::_('JLIST_BACKEND_FILES_COPY_TEXT_1'); ?></b><br />
       <ul>
       <?php foreach ($files as $file){
                echo '<li>'.$file->file_title.'</li>';
       } 
       ?></ul>
       </td>
       <td width="30%" valign="top"> 
         <b><?php echo JText::_('JLIST_BACKEND_FILES_COPY_TEXT_2'); ?></b>  
        </tr>
        
        
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="task" value="files.copy.save" /> 
    <input type="hidden" name="cid2" value="<?php echo $files_id; ?>" />
    <input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>" /> 
    <input type="hidden" name="hidemainmenu" value="0" />
   </table>
   </form>
  <?php 
    
}    

// Dateien kopieren (ohne dateizuordnung)
function filesMove($option, $files_id, $files, $cat_id){
    $database = &JFactory::getDBO();
    
    ?>
    <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
        var form = document.adminForm;
        
        if (pressbutton == 'files.list') {
            submitform( pressbutton );
            return;
        }
        
        // do field validation
        if (form.cat_id2.value == 0){
            alert( "<?php echo JText::_('JLIST_BACKEND_FILESEDIT_CATLIST_ERROR');?>" );
        } else {
            submitform( pressbutton );
        }
    }
    </script>
   

    <form action="index2.php" method="post" name="adminForm">
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
        
        <tr>
            <th  colspan="3" class="title"><?php echo JText::_('JLIST_BACKEND_FILES_MOVE_TITLE').' '; ?></th>
        </tr>
        <tr>
           <td width="30%" valign="top"><b><?php echo JText::_('JLIST_BACKEND_FILES_MOVE_DESC'); ?></b><br /><br />
       <?php 
           // build cat tree listbox
           $src_list = array();
           $query = "SELECT cat_id AS id, parent_id AS parent, cat_title AS name FROM #__jdownloads_cats ORDER BY ordering";
           $database->setQuery( $query );
           $cats2 = $database->loadObjectList();
           $preload = array();
           $catlist= treeSelectList( $cats2, 0, $preload, 'cat_id2', 'class="inputbox" size="10"', 'value', 'text', $row->cat_id);
           echo $catlist;
           
      ?> 
       </td>
       <td width="30%" valign="top">
       <b><?php echo JText::_('JLIST_BACKEND_FILES_MOVE_TEXT_1'); ?></b><br />
       <ul>
       <?php foreach ($files as $file){
                echo '<li>'.$file->file_title.'</li>';
       } 
       ?></ul>
       </td>
       <td width="30%" valign="top"> 
         <b><?php echo JText::_('JLIST_BACKEND_FILES_MOVE_TEXT_2'); ?></b>  
        </tr>
        
        
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="task" value="files.move.save" /> 
    <input type="hidden" name="cid2" value="<?php echo $files_id; ?>" />
    <input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>" /> 
    <input type="hidden" name="hidemainmenu" value="0" />
   </table>
   </form>
  <?php 
    
}   

/*
/  Display the main component control panel
*/

function controlPanel($option, $task) {
    global $mainframe, $jlistConfig;
    jimport( 'joomla.html.pane');
    $database = &JFactory::getDBO();
    
    // get stats data
    $database->setQuery('SELECT COUNT(*) FROM #__jdownloads_cats');
    $sum_cats = intval($database->loadResult());
    $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_files");
    $sum_files = intval($database->loadResult());
    $database->setQuery("SELECT SUM(downloads) FROM #__jdownloads_files");
    $sum_downloads = intval($database->loadResult());
    $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_files WHERE published = 0");
    $sum_files_unpublished = intval($database->loadResult());
    $database->setQuery("SELECT COUNT(*) FROM #__jdownloads_cats WHERE published = 0");
    $sum_cats_unpublished = intval($database->loadResult());        
    $color = '#990000';
    $stats = str_replace('#1', '<font color="'.$color.'"><b>'.$sum_files.'</b></font>', JText::_('JLIST_BACKEND_CP_STATS_TEXT'));
    $stats = str_replace('#2', '<font color="'.$color.'"><b>'.$sum_cats.'</b></font>', $stats);
    $stats = str_replace('#3', '<font color="'.$color.'"><b>'.$sum_downloads.'</b></font>', $stats);
    $stats = str_replace('#4', '<font color="'.$color.'"><b>'.$sum_cats_unpublished.'</b></font>', $stats);
    $stats = str_replace('#5', '<font color="'.$color.'"><b>'.$sum_files_unpublished.'</b></font>', $stats);
    
    // check all as updated marked files and unselect it when duration is over
    $database->setQuery("SELECT * FROM #__jdownloads_files WHERE update_active = '1'");
    $files = $database->loadObjectList();
    if ($files){
        foreach ($files as $file){
           $tage_diff = DatumsDifferenz_JD(date('Y-m-d H:m:s'), $file->modified_date);
           if ($jlistConfig['days.is.file.updated'] > 0 && $tage_diff >= 0 && $tage_diff <= $jlistConfig['days.is.file.updated']){
               // nothing
           } else {    
               $database->setQuery("UPDATE #__jdownloads_files SET update_active = '0' WHERE file_id = '$file->file_id'");
               $database->query();
           } 
        }
    }    
    
?>
        
<!-- ICON begin -->

    <table class="adminform">
        <tr>

	       <td width="40%" valign="top">

    	   	<div id="cpanel">

				<div style="float:left;">
			<div class="icon">
				<a href="index2.php?option=com_jdownloads&task=files.list" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_FILES');?>">
                <img src="components/com_jdownloads/images/downloads.png" width="48px" height="48px" align="middle" border="0"/>
            	<br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_FILES');?>
				</a>
			</div>
		</div>

				<div style="float:left;">
			<div class="icon">
				<a href="index2.php?option=com_jdownloads&task=categories.list" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_CATEGORIES');?>">
	            <img src="components/com_jdownloads/images/categories.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_CATEGORIES');?>
	            </a>
			</div>
		</div>

				<div style="float:left;">
			<div class="icon">
	            <a href="index2.php?option=com_jdownloads&task=license.list" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_LICENSE');?>">
	            <img src="components/com_jdownloads/images/licenses.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_LICENSE');?>
	            </a>
			</div>
		</div>

<div style="float:left;">
			<div class="icon">
	            <a href="index2.php?option=com_jdownloads&task=templates.menu" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_TEMPLATES');?>">
	            <img src="components/com_jdownloads/images/template.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_TEMPLATES');?>
	            </a>
			</div>
		</div>

<div style="float:left;">
			<div class="icon">
	            <a href="index2.php?option=com_jdownloads&task=config.show" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_SETTINGS');?>">
	            <img src="components/com_jdownloads/images/config.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_SETTINGS');?>
	            </a>
			</div>
		</div>
		
<div style="float:left;">
			<div class="icon">
	            <a href="index2.php?option=com_jdownloads&task=backup" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_BACKUP');?>">
	            <img src="components/com_jdownloads/images/backup.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_BACKUP');?>
	            </a>
			</div>
		</div>
		
<div style="float:left;">
			<div class="icon">
	            <a href="index2.php?option=com_jdownloads&task=restore" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_RESTORE');?>">
	            <img src="components/com_jdownloads/images/restore.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_RESTORE');?>
	            </a>
			</div>
		</div>
		
<div style="float:left;">
			<div class="icon">
	            <a href="index2.php?option=com_jdownloads&task=info" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_INFO');?>">
	            <img src="components/com_jdownloads/images/info.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_INFO');?>
	            </a>
			</div>
		</div>

<div style="float:left;">
			<div class="icon">
	            <a href="http://www.jDownloads.com" target="_blank" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_SUPPORT');?>">
	            <img src="components/com_jdownloads/images/support.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_SUPPORT');?>
	            </a>
			</div>
		</div>
	</div>

<!-- ICON END -->

    <div style="clear:both;"> </div>
	</td>

<!-- TABS begin -->
	<td width="60%" valign="top">
		<div style="width: 100%;"><br />
            <?php // beispieldaten option
            $database->SetQuery("SELECT COUNT(*) FROM #__jdownloads_files");
            $sum = $database->loadResult();
            if (!is_dir(JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/'.JText::_('JLIST_SAMPLE_DATA_CAT_FOLDER_ROOT')) && !$sum){ ?>
                <div align="right"><a href="index2.php?option=com_jdownloads&task=install.sample" title="<?php echo JText::_('JLIST_SAMPLE_DATA_BE_OPTION_LINK_TEXT');?>"><?php echo JText::_('JLIST_SAMPLE_DATA_BE_OPTION_LINK_TEXT').' >> ';?></a></div> <?php } ?>
			
            <form action="index2.php" method="post" name="adminlist">

              <?php	$pane =& JPane::getInstance('Tabs');
                    echo $pane->startPane('paneltabs');
                    echo $pane->startPanel(JText::_('JLIST_BACKEND_PANEL_TABTEXT_STATUS'),'status');
              ?>
              <table width="95%" border="0">
                <tr>
                    <th class="adminheading"><?php echo JText::_('JLIST_BACKEND_PANEL_STATUS_OFFLINE_HEADER')." "; ?></th>
                </tr>
                <tr>
   		          <td valign="top" align="left" width="100%">
                    <?php echo JText::_('JLIST_BACKEND_PANEL_STATUS_TITEL').' ';
                        if ($jlistConfig['offline']) {
                            echo JText::_('JLIST_BACKEND_PANEL_STATUS_OFFLINE'); ?><br /><br />
                            <?php echo JText::_('JLIST_BACKEND_PANEL_STATUS_DESC_OFFLINE'); ?><br /><br />
                            <?php
                        } else {
                            echo JText::_('JLIST_BACKEND_PANEL_STATUS_ONLINE'); ?><br /><br />
                            <?php echo JText::_('JLIST_BACKEND_PANEL_STATUS_DESC_ONLINE'); ?><br /><br />
                            <?php
                        }
                        echo $stats; ?>
                        
                  </td>
               </tr>
               
               <tr>
                    <th class="adminheading"><?php echo JText::_('JLIST_BACKEND_PANEL_STATUS_DOWNLOADS_HEADER')." "; ?></th>
                </tr>
               <tr>
                <td>
                    <?php
                     if($jlistConfig['files.autodetect']){
                       checkFiles($task);
                     } else {
                       echo '<b><font color="#FF6600">'.JText::_('JLIST_BACKEND_PANEL_STATUS_DOWNLOADS_OFF_DESC').'</font></b><br />';
                     }
                    ?>
                    <br />
                    <div><a href="<?php echo JURI::base();?>components/com_jdownloads/scan.php"  target="_blank" onclick="openWindow(this.href); return false" title="<?php echo JText::_('JLIST_RUN_MONITORING_BUTTON_TEXT');?>"><?php echo JText::_('JLIST_RUN_MONITORING_BUTTON_TEXT');?></a></div> 
                    
                    <?php echo JText::_('JLIST_RUN_MONITORING_INFO'); ?><br />
                </td>
               </tr>
               
              </table>
              <?php
               echo $pane->endPanel();
               
               echo $pane->startPanel(JText::_('JLIST_BACKEND_PANEL_TABTEXT_3'),'log');
              ?>
              <table width="95%" border="0">
               <tr>
                    <th class="adminheading"><?php echo JText::_('JLIST_BACKEND_AUTOCHECK_LOG_TAB_TITLE'); 
                           if ($jlistConfig['last.log.message']) { ?> 
                               <div><a href="index2.php?option=com_jdownloads&task=delete.log"><?php echo JText::_('JLIST_BACKEND_DELETE_LOG_LINK_TEXT');?></a></div>
                       <?php } ?>        
                     </th>
               </tr>

                  <tr>
                     <td valign="top" align="left" width="100%">
                         <?php echo $jlistConfig['last.log.message']; ?>
                  </td>
               </tr>
              </table>
              <?php
               echo $pane->endPanel();
               
               echo $pane->startPanel(JText::_('JLIST_BACKEND_PANEL_TABTEXT_4'),'restore log');                   
              ?>
              <table width="95%" border="0">
               <tr>
                    <th class="adminheading"><?php echo JText::_('JLIST_BACKEND_RESTORE_LOG_TAB_TITLE'); 
                           if ($jlistConfig['last.restore.log']) { ?>
                               <div><a href="index2.php?option=com_jdownloads&task=delete.restore.log"><?php echo JText::_('JLIST_BACKEND_DELETE_LOG_LINK_TEXT');?></a></div>
                     <?php } ?>   
                    </th>
               </tr>

                  <tr>
                     <td valign="top" align="left" width="100%">
                         <?php echo $jlistConfig['last.restore.log']; ?>
                  </td>
               </tr>
              </table>
              <?php
               echo $pane->endPanel();
               
               echo $pane->startPanel(JText::_('JLIST_BACKEND_PANEL_TABTEXT_5'),'server limits');
              ?>
              <table width="95%" border="0">
               <tr>
                    <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SERVER_INFOS_TAB_TITLE')." "; ?></th>
               </tr>

                  <tr>
                     <td colspan="2" valign="top" align="left" width="100%">
                         <?php echo JText::_('JLIST_BACKEND_SERVER_INFOS_TAB_DESC'); ?>
                  </td>
                  
               </tr>
               <tr>
                 <td width="40%" style="background-color:#DCDCDC;">
                 <?php echo JText::_('JLIST_BACKEND_SERVER_INFOS_TAB_FILE_UPLOADS'); ?>
                 </td>
                 <td width="60%" style="background-color:#DCDCDC;">
                 <?php if (get_cfg_var(file_uploads)){ echo JText::_('JLIST_FE_YES'); } else { echo JText::_('JLIST_FE_NO'); } ?> 
                 </td>
               </tr>
               <tr>  
                 <td width="40%" style="background-color:#DCDCDC;">
                 <?php echo JText::_('JLIST_BACKEND_SERVER_INFOS_TAB_MAX_FILESIZE'); ?>
                 </td>
                 <td width="60%" style="background-color:#DCDCDC;">
                 <?php echo get_cfg_var (upload_max_filesize); ?>
                 </td>
               </tr>  
               <tr>  
                 <td width="40%" style="background-color:#DCDCDC;">
                 <?php echo JText::_('JLIST_BACKEND_SERVER_INFOS_TAB_POST_MAX_SIZE'); ?>
                 </td>
                 <td width="60%" style="background-color:#DCDCDC;">
                 <?php echo get_cfg_var (post_max_size); ?>
                 </td>
               </tr>  
               <tr>  
                 <td width="40%" style="background-color:#DCDCDC;">
                 <?php echo JText::_('JLIST_BACKEND_SERVER_INFOS_TAB_MEMORY_LIMIT'); ?>
                 </td>
                 <td width="60%" style="background-color:#DCDCDC;">
                 <?php echo get_cfg_var (memory_limit); ?>
                 </td>
               </tr>  
               <tr>  
                 <td width="40%" style="background-color:#DCDCDC;">
                 <?php echo JText::_('JLIST_BACKEND_SERVER_INFOS_TAB_MAX_INPUT_TIME'); ?>
                 </td>
                 <td width="60%" style="background-color:#DCDCDC;">
                 <?php echo get_cfg_var (max_input_time); ?>
                 </td>
               </tr>  
               <tr>  
                 <td width="40%" style="background-color:#DCDCDC;">
                 <?php echo JText::_('JLIST_BACKEND_SERVER_INFOS_TAB_MAX_EXECUTION_TIME'); ?>
                 </td>
                 <td width="60%" style="background-color:#DCDCDC;">
                 <?php echo get_cfg_var (max_execution_time); ?>
                 </td>
               </tr>  
              </table>
              <?php
               echo $pane->endPanel();
               
               echo $pane->startPanel(JText::_('JLIST_BACKEND_PANEL_TABTEXT_2'),'version');              
              ?>
              <table width="95%" border="0">
               <tr>
                    <th class="adminheading"><?php echo JText::_('JLIST_BACKEND_PANEL_STATUS_VERSION_HEADER')." "; ?></th>
               </tr>

   		       <tr>
   		          <td valign="top" align="left" width="100%">
           	          <?php echo '<b><font color="#990000">jDownloads '.$jlistConfig['jd.version'].' '.$jlistConfig['jd.version.state'].' Build '.$jlistConfig['jd.version.svn'].'</font></b>'.JText::_('JLIST_VERSION');?>
                      <br /><br />
                      <big><b><a href="http://www.jdownloads.com/index.php?option=com_versions&tmpl=component&catid=1&myVersion=<?php echo $jlistConfig['jd.version']; ?>" target="_blank" ><?php echo JText::_('JLIST_BACKEND_UPDATE_CHECK_TITLE');?></a></b></big>
                  </td>
               </tr>
              </table>
              <?php
               echo $pane->endPanel();
               
               echo $pane->endPane('paneltabs');?>               
        </form> 
        </div>
    </td>

<!-- TABS END -->

    </tr>
</table>

<?php
}

/**********************************************
/ Licenses
/ ********************************************/

// Licenses list

function listLicense($rows, $option){
	global $mainframe, $jlistConfig;

	JHTML::_('behavior.tooltip');
?>
	<form action="index2.php" method="post" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
	  <tr>
		  <td colspan="7">&nbsp;</td>
	  </tr>
		<tr>
			<th width="5" align="left"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_LICLIST_TITLE')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_LICLIST_LINK')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_LICEDIT_LIC_DESCRIPTION')." "; ?></th>
        </tr>
		<?php
		$k = 0;
		for($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			//$row->id = $row->cat_id;
			$link 		= 'index2.php?option=com_jdownloads&task=license.edit&hidemainmenu=1&cid='.$row->id;
			$checked 	= JHTML::_('grid.checkedout', $row, $i );
			?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $checked; ?></td>
			<td><a href="<?php echo $link; ?>" title="<?php echo JText::_('JLIST_BACKEND_LICEDIT_EDIT');?>"><?php echo $row->license_title; ?></a></td>

			<td><a href="<?php echo $row->license_url; ?>" target="_blank"><?php echo $row->license_url; ?></a></td>
            <td>
            <?php
                if (strlen($row->license_text) > 200 ) {
                   echo substr($row->license_text, 0, 197).'...';
                } else {
                   echo $row->license_text;
                }
            ?>
            </td>

            <?php $k = 1 - $k;  } ?>
		</tr>
	</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0">
</form>

<?php
}

function editLicense($option, $row){
global $mainframe, $jlistConfig;
$editor =& JFactory::getEditor();
	?>

	<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'license.cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.license_title.value == ""){
			alert( "<?php echo JText::_('JLIST_BACKEND_LICEDIT_ERROR_TITLE');?>" );
		} else {
			submitform( pressbutton );
		}
	}
	</script>

	<form action="index2.php" method="post" name="adminForm" id="adminForm">
	<table width="100%" border="0">
		<tr>
			<td width="100%" valign="top">
			<table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo $row->id ? JText::_('JLIST_BACKEND_LICEDIT_EDIT') : JText::_('JLIST_BACKEND_LICEDIT_ADD');?></th>
		      	</tr>
		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table>
		  					<tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_LICEDIT_LIC_TITLE')." "; ?></strong><br />
		    						<input name="license_title" value="<?php echo $row->license_title; ?>" size="70" maxlength="50"/>
		       					</td>
		  					</tr>
                            <tr>
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_LICEDIT_LIC_URL')." "; ?></strong><br />
		    						<input name="license_url" value="<?php echo $row->license_url; ?>" size="70" maxlength="255"/>
		       					</td>
		  					</tr>
		  					<tr>
		  						<td><strong><?php echo JText::_('JLIST_BACKEND_LICEDIT_LIC_DESCRIPTION')." "; ?></strong><br />
	  							<?php
                                 if ($jlistConfig['licenses.editor'] == "1") {
									echo $editor->display( 'license_text',  @$row->license_text , '100%', '500', '80', '5' ) ;
									
                                 } else {?>
                                    <textarea name="license_text" rows="20" cols="80"><?php echo $row->license_text; ?></textarea>
                                <?php
                                } ?>

		  						</td>
		  					</tr>
		  				</table>
		  			</td>
		  		</tr>
			</table>
			</td>
		</tr>
	</table>
<br /><br />

		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
	</form>

<?php
}

/**********************************************
/ Templates
/ ********************************************/

 /* Display the submenu for templates
 */

function controlPanelTemplate($option) {
    global $mainframe;
    jimport( 'joomla.html.pane');
    ?>

<!-- ICON begin -->

    <table class="adminform">
        <tr>

	       <td width="40%" valign="top">

    	   	<div id="cpanel">

				<div style="float:left;">
			<div class="icon">
				<a href="index2.php?option=com_jdownloads&task=templates.list.cats" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_TEMPLATES_CATS');?>">
	            <img src="components/com_jdownloads/images/template.png" width="48px" height="48px" align="middle" border="0"/>
                <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_TEMPLATES_CATS');?>
	            </a>
			</div>
		</div>

				<div style="float:left;">
			<div class="icon">
	            <a href="index2.php?option=com_jdownloads&task=templates.list.files" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_TEMPLATES_FILES');?>">
	            <img src="components/com_jdownloads/images/template.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_TEMPLATES_FILES');?>
	            </a>
            </div>
		</div>

        <div style="float:left;">
            <div class="icon">
                <a href="index2.php?option=com_jdownloads&task=templates.list.details" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_TEMPLATES_DETAILS');?>">
                <img src="components/com_jdownloads/images/template.png" width="48px" height="48px" align="middle" border="0"/>
                <br />
                <?php echo JText::_('JLIST_BACKEND_CPANEL_TEMPLATES_DETAILS');?>
                </a>
            </div>
        </div>
        
        
				<div style="float:left;">
			<div class="icon">
	            <a href="index2.php?option=com_jdownloads&task=templates.list.summary" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_CPANEL_TEMPLATES_SUMMARY');?>">
	            <img src="components/com_jdownloads/images/template.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_CPANEL_TEMPLATES_SUMMARY');?>
	            </a>
			</div>
		</div>

        <div style="float:left;">
			<div class="icon">
	            <a href="index2.php?option=com_jdownloads&task=css.edit" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_EDIT_CSS_TITLE');?>">
	            <img src="components/com_jdownloads/images/css.png" width="48px" height="48px" align="middle" border="0"/>
	            <br />
	            <?php echo JText::_('JLIST_BACKEND_EDIT_CSS_TITLE');?>
	            </a>
			</div>
		</div>
        <div style="float:left;">
            <div class="icon">
                <a href="index2.php?option=com_jdownloads&task=language.edit" style="text-decoration:none;" title="<?php echo JText::_('JLIST_BACKEND_EDIT_LANG_TITLE');?>">
                <img src="components/com_jdownloads/images/langmanager.png" width="48px" height="48px" align="middle" border="0"/>
                <br />
                <?php echo JText::_('JLIST_BACKEND_EDIT_LANG_TITLE');?>
                </a>
            </div>
        </div>
	</div>


<!-- ICON END -->
    
    <div style="clear:both;"> </div>
	</td>

<!-- TABS begin -->
	<td width="60%" valign="top">
      <form action="index2.php" method="post" name="adminForm">
		<div style="width: 97%;">
              <?php	$pane =& JPane::getInstance('Tabs');
                    echo $pane->startPane('temppaneltabs');
                    echo $pane->startPanel(JText::_('JLIST_BACKEND_TEMPPANEL_TABTEXT_INFO'),'Info');            
              ?>
              <table class="admintable" width="100%">
   		       <tr>
   		          <td valign="top" align="left" width="100%">
                     <?php echo  JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_HEAD'); ?>
                     <?php echo  JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_HEAD_INFO').JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_HEAD_INFO2'); ?>
                  </td>
               </tr>
              </table>
              <?php
               echo $pane->endPanel();
               echo $pane->endPane('temppaneltabs');?>
        </div>
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="task" value="controlPanel" />
    </form>
    </td>
<!-- TABS END -->
    </tr>
</table>

	

<?php
}


// Templates list

function listTemplatesCats($rows, $option){
	global $mainframe, $jlistConfig;

    $temp_typ = array();
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP1');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP2');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP3');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP4');

	JHTML::_('behavior.tooltip');
?>
	<form action="index2.php" method="post" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
	  <tr align="right">
		  <td colspan="7"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_LOCKED_DESC'); ?></td>
	  </tr>
		<tr>
			<th width="5" align="left"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_TITLE')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_TYP')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_ACTIVE')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_LOCKED')." "; ?></th>
        </tr>
		<?php
		$k = 0;
		for($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			$link 		= 'index2.php?option=com_jdownloads&task=templates.edit.cats&hidemainmenu=1&cid='.$row->id;
			$checked 	= JHTML::_('grid.checkedout', $row, $i );
			?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $checked; ?></td>
			<td><a href="<?php echo $link; ?>" title="<?php echo JText::_('JLIST_BACKEND_TEMPEDIT_EDIT');?>"><?php echo $row->template_name; ?></a></td>

            <td>
            <?php
            echo $temp_typ[$row->template_typ -1]; ?>
            </td>

            <td>
            <?php
            if ($row->template_active > 0) { ?>
                <img src="components/com_jdownloads/images/active.png" width="12px" height="12px" align="middle" border="0"/>
            <?php } ?>
            </td>
            
			<td>
            <?php
            if ($row->locked > 0) { ?>
                <img src="components/com_jdownloads/images/active.png" width="12px" height="12px" align="middle" border="0"/>
            <?php } ?>
            </td>

            <?php $k = 1 - $k;  } ?>
		</tr>
	</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0">
</form>

<?php
}

function editTemplatesCats($option, $row){
    global $mainframe, $jlistConfig;
    $editor =& JFactory::getEditor();
    ?>
    <script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'templates.cancel.cats') {
			submitform( pressbutton );
			return;
		}

		if (form.template_name.value == ""){
			alert( "<?php echo JText::_('JLIST_BACKEND_TEMPEDIT_ERROR_TITLE');?>" );
		} else {
			submitform( pressbutton );
		}
	}
	</script>

	<form action="index2.php" method="post" name="adminForm" id="adminForm">
	
	<table width="100%" border="0">
		<tr>
			<td width="100%" valign="top">
			<table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo $row->id ? JText::_('JLIST_BACKEND_TEMPEDIT_EDIT') : JText::_('JLIST_BACKEND_TEMPEDIT_ADD');?></th>
		      	</tr>
		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table>
		  					<tr valign="top">
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME')." "; ?></strong><br />
		    					    <?php 
                                          if ($row->locked){
                                             $dis = 'disabled="disabled"'; 	
                                          } else {
                                             $dis = '';
                                          } ?>
                                    <input name="template_name" value="<?php echo $row->template_name; ?>" <?php echo $dis; ?> size="70" maxlength="64"/>
		       					</td>
                                <td><?php if (!$dis) { echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME_DESCRIPTION'); 
                                        } else { echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME_DESCRIPTION').'<br />'.JText::_('JLIST_BACKEND_TEMPEDIT_TITLE_NOT_ALLOWED_TO_CHANGE_DESK'); }?>
                                   </td>
   		  					</tr>
                            <tr valign="top">
                                <td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NOTE_TITLE')." "; ?></strong><br />
                                    <textarea name="note" rows="1" cols="80"><?php echo $row->note; ?></textarea>
                                   </td>
                                   <td><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NOTE_DESC'); ?>
                                   </td>
                            </tr>
		  					
                            <tr valign="top">
                                <td><strong><?php echo JText::_('JLIST_EDIT_TEMPL_AMOUNT_COL_TITLE')." "; ?></strong><br />
                                    <input name="cols" value="<?php echo $row->cols; ?>" size="5" maxlength="1"/> 
                                   </td>
                                   <td><?php echo JText::_('JLIST_EDIT_TEMPL_AMOUNT_COL_DESC'); ?>
                                   </td>
                              </tr>
                            
                              <tr>
		  						<td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_TEXT')." "; ?></strong><br />
		  							<?php
                                    if ($jlistConfig['layouts.editor'] == "1") {
                                    	echo $editor->display( 'template_text',  @$row->template_text , '100%', '500', '80', '5' ) ;
                                        } else {?>
                                        <textarea name="template_text" rows="20" cols="80"><?php echo $row->template_text; ?></textarea>
                                        <?php
                                        } ?>
		  						</td>
		       					<td valign="top">
                                   <?php
                                        echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_DESC').'<br /><br />'.JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_DESC2').'<br /><br />'.JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_DESC3');
                                   ?>
		       					</td>
		  					</tr>

                            <tr>
		      		           <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_INFO_TITLE')." "; ?>
                               </th>
		      	            </tr>
                            <tr>
                               <td>
                               <br />
                                <div><img src="components/com_jdownloads/images/downloads_cats.gif" alt="Templates Infos" border="1" /></div>
                              </td>
                              <td valign="top"><?php echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_CATS_INFO_TEXT');?>
                              </td>
                            </tr>

		  				</table>
		  			</td>
		  		</tr>
			</table>
			</td>
		</tr>
	</table>
<br /><br />

		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
        <input type="hidden" name="tempname" value="<?php echo $row->template_name; ?>" />
        <input type="hidden" name="locked" value="<?php echo $row->locked; ?>" />        
		<input type="hidden" name="task" value="" />
	</form>

<?php
}

// Templates list

function listTemplatesFiles($rows, $option){
	global $mainframe, $jlistConfig;

    $temp_typ = array();
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP1');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP2');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP3');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP4');

	JHTML::_('behavior.tooltip');
?>
	<form action="index2.php" method="post" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
	  <tr align="right">
		  <td colspan="7"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_LOCKED_DESC'); ?></td>
	  </tr>
		<tr>
			<th width="5" align="left"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_TITLE')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_TYP')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_ACTIVE')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_LOCKED')." "; ?></th>
        </tr>
		<?php
		$k = 0;
		for($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			$link 		= 'index2.php?option=com_jdownloads&task=templates.edit.files&hidemainmenu=1&cid='.$row->id;
			$checked 	= JHTML::_('grid.checkedout', $row, $i );
			?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $checked; ?></td>
			<td><a href="<?php echo $link; ?>" title="<?php echo JText::_('JLIST_BACKEND_TEMPEDIT_EDIT');?>"><?php echo $row->template_name; ?></a></td>

            <td>
            <?php
            echo $temp_typ[$row->template_typ -1]; ?>
            </td>

            <td>
            <?php
            if ($row->template_active > 0) { ?>
                <img src="components/com_jdownloads/images/active.png" width="12px" height="12px" align="middle" border="0"/>
            <?php } ?>

            </td>
            
            <td>
            <?php
            if ($row->locked > 0) { ?>
                <img src="components/com_jdownloads/images/active.png" width="12px" height="12px" align="middle" border="0"/>
            <?php } ?>
            </td>

            <?php $k = 1 - $k;  } ?>
		</tr>
	</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0" />
</form>

<?php
}

function listTemplatesDetails($rows, $option){
    global $mainframe, $jlistConfig;

    $temp_typ = array();
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP1');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP2');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP3');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP4');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP5');
    
    JHTML::_('behavior.tooltip');
?>
    <form action="index2.php" method="post" name="adminForm">
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr align="right">
          <td colspan="7"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_LOCKED_DESC'); ?></td>
      </tr>
        <tr>
            <th width="5" align="left"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
            <th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_TITLE')." "; ?></th>
            <th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_TYP')." "; ?></th>
            <th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_ACTIVE')." "; ?></th>
            <th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_LOCKED')." "; ?></th>
        </tr>
        <?php
        $k = 0;
        for($i=0, $n=count( $rows ); $i < $n; $i++) {
            $row = &$rows[$i];
            $link         = 'index2.php?option=com_jdownloads&task=templates.edit.details&hidemainmenu=1&cid='.$row->id;
            $checked     = JHTML::_('grid.checkedout', $row, $i );
            ?>
        <tr class="<?php echo "row$k"; ?>">
            <td><?php echo $checked; ?></td>
            <td><a href="<?php echo $link; ?>" title="<?php echo JText::_('JLIST_BACKEND_TEMPEDIT_EDIT');?>"><?php echo $row->template_name; ?></a></td>

            <td>
            <?php
            echo $temp_typ[$row->template_typ -1]; ?>
            </td>

            <td>
            <?php
            if ($row->template_active > 0) { ?>
                <img src="components/com_jdownloads/images/active.png" width="12px" height="12px" align="middle" border="0"/>
            <?php } ?>

            </td>
            
            <td>
            <?php
            if ($row->locked > 0) { ?>
                <img src="components/com_jdownloads/images/active.png" width="12px" height="12px" align="middle" border="0"/>
            <?php } ?>
            </td>

            <?php $k = 1 - $k;  } ?>
        </tr>
    </table>
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="hidemainmenu" value="0" />
</form>

<?php
}

function editTemplatesFiles($option, $row){
global $mainframe, $jlistConfig;
$editor =& JFactory::getEditor();
    ?>
    <script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'templates.cancel.files') {
			submitform( pressbutton );
			return;
		}

		if (form.template_name.value == ""){
			alert( "<?php echo JText::_('JLIST_BACKEND_TEMPEDIT_ERROR_TITLE');?>" );
		} else {
			submitform( pressbutton );
		}
	}
	</script>

	<form action="index2.php" method="post" name="adminForm" id="adminForm">
	
	<table width="100%" border="0">
		<tr>
			<td width="100%" valign="top">
			<table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo $row->id ? JText::_('JLIST_BACKEND_TEMPEDIT_EDIT') : JText::_('JLIST_BACKEND_TEMPEDIT_ADD');?></th>
		      	</tr>
		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table>
		  					<tr valign="top">
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME')." "; ?></strong><br />
		    						<?php 
                                          if ($row->locked){
                                             $dis = 'disabled="disabled"';     
                                          } else {
                                             $dis = '';
                                          } ?>
                                    <input name="template_name" value="<?php echo $row->template_name; ?>" <?php echo $dis; ?> size="70" maxlength="64"/>
		       					</td>
		       					<td><?php if (!$dis) { echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME_DESCRIPTION'); 
                                        } else { echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME_DESCRIPTION').'<br />'.JText::_('JLIST_BACKEND_TEMPEDIT_TITLE_NOT_ALLOWED_TO_CHANGE_DESK'); }?>
                                   </td>
		  					</tr>

                            <tr valign="top">
                                <td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NOTE_TITLE')." "; ?></strong><br />
                                    <textarea name="note" rows="1" cols="80"><?php echo $row->note; ?></textarea>
                                   </td>
                                   <td><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NOTE_DESC'); ?>
                                   </td>
                              </tr>
                              
<tr valign="top">
                                <td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_CHECKBOX_TITLE')." "; ?></strong><br />
                                   <?php 
                                   $checkboxed = array();
                                   $checkboxed[] = JHTML::_('select.option', '0', JText::_('JLIST_FE_YES'));
                                   $checkboxed[] = JHTML::_('select.option', '1', JText::_('JLIST_FE_NO'));
                                   echo JHTML::_('select.genericlist', $checkboxed, 'checkbox_off', 'size="1" class="inputbox"', 'value', 'text', $row->checkbox_off );
                                  ?>                    
                                   </td>
                                   <td><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_CHECKBOX_DESC'); ?>
                                   </td>
                              </tr>                              

                              <td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_SYMBOLE_TITLE')." "; ?></strong><br />
                                   <?php 
                                   $symbols = array();
                                   $symbols[] = JHTML::_('select.option', '0', JText::_('JLIST_FE_YES'));
                                   $symbols[] = JHTML::_('select.option', '1', JText::_('JLIST_FE_NO'));
                                   echo JHTML::_('select.genericlist', $symbols, 'symbol_off', 'size="1" class="inputbox"', 'value', 'text', $row->symbol_off );
                                  ?>                    
                                   </td>
                                   <td><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_SYMBOLE_DESC'); ?>
                                   </td>
                              </tr>                            
                            
		  					<tr>
		  						<td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_TEXT')." "; ?></strong><br />
		  							<?php
                                    if ($jlistConfig['layouts.editor'] == "1") {
                                    	echo $editor->display( 'template_text',  @$row->template_text , '100%', '500', '80', '5' ) ;
                                        } else {?>
                                        <textarea name="template_text" rows="20" cols="80"><?php echo $row->template_text; ?></textarea>
                                        <?php
                                        } ?>
		  						</td>
		       					<td valign="top">
                                   <?php
                                        echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DESC').'<br /><br />'.JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_DESC2').'<br /><br />'.JText::_('JLIST_BACKEND_TEMPEDIT_INFO_LIGHTBOX');
                                   ?>
		       					</td>
		  					</tr>

                            <tr>
	      		              <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_INFO_TITLE')." "; ?></th>
           	                  </tr>
                              <tr><td>
                              <br />
                              <div><img src="components/com_jdownloads/images/downloads_files.gif" alt="Templates Infos" border="1" /></div>
                              </td>
                              <td valign="top"><?php echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FILES_INFO_TEXT');?>
                              </td>
                              </tr>

		  				</table>
		  			</td>
		  		</tr>
			</table>
			</td>
		</tr>
	</table>
<br /><br />

		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
        <input type="hidden" name="tempname" value="<?php echo $row->template_name; ?>" />
        <input type="hidden" name="locked" value="<?php echo $row->locked; ?>" />
        <input type="hidden" name="task" value="" />
	</form>

<?php
}

function editTemplatesDetails($option, $row){
global $mainframe, $jlistConfig;
$editor =& JFactory::getEditor();
    ?>
    <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'templates.cancel.details') {
            submitform( pressbutton );
            return;
        }

        if (form.template_name.value == ""){
            alert( "<?php echo JText::_('JLIST_BACKEND_TEMPEDIT_ERROR_TITLE');?>" );
        } else {
            submitform( pressbutton );
        }
    }
    </script>

    <form action="index2.php" method="post" name="adminForm" id="adminForm">
   
    <table width="100%" border="0">
        <tr>
            <td width="100%" valign="top">
            <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo $row->id ? JText::_('JLIST_BACKEND_TEMPEDIT_EDIT') : JText::_('JLIST_BACKEND_TEMPEDIT_ADD');?></th>
                  </tr>
                  <tr>
                      <td valign="top" align="left" width="100%">
                          <table>
                              <tr valign="top">
                                <td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME')." "; ?></strong><br />
                                    <?php 
                                          if ($row->locked){
                                             $dis = 'disabled="disabled"';     
                                          } else {
                                             $dis = '';
                                          } ?>
                                    <input name="template_name" value="<?php echo $row->template_name; ?>" <?php echo $dis; ?> size="70" maxlength="64"/>
                                   </td>
                                   <td><?php if (!$dis) { echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME_DESCRIPTION'); 
                                        } else { echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME_DESCRIPTION').'<br />'.JText::_('JLIST_BACKEND_TEMPEDIT_TITLE_NOT_ALLOWED_TO_CHANGE_DESK'); }?>
                                   </td>
                              </tr>
                              <tr valign="top">
                                <td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NOTE_TITLE')." "; ?></strong><br />
                                    <textarea name="note" rows="1" cols="80"><?php echo $row->note; ?></textarea>
                                   </td>
                                   <td><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NOTE_DESC'); ?>
                                   </td>
                              </tr>
                              <tr>
                                  <td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_TEXT')." "; ?></strong><br />
                                      <?php
                                    if ($jlistConfig['layouts.editor'] == "1") {
                                        echo $editor->display( 'template_text',  @$row->template_text , '100%', '500', '80', '5' ) ;
                                        } else {?>
                                        <textarea name="template_text" rows="20" cols="80"><?php echo $row->template_text; ?></textarea>
                                        <?php
                                        } ?>
                                  </td>
                                   <td valign="top">
                                   <?php
                                        echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_DETAILS_DESC').'<br /><br />'.JText::_('JLIST_BACKEND_TEMPEDIT_INFO_LIGHTBOX');
                                   ?>
                                   </td>
                              </tr>

                            <tr>
                                <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_DETAILS_INFO_TITLE')." "; ?></th>
                                 </tr>
                              <tr><td>
                              <br />
                              <div><img src="components/com_jdownloads/images/downloads_details.gif" alt="Templates Infos" border="1" /></div>
                              </td>
                              <td valign="top"><?php echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_DETAILS_INFO_TEXT');?>
                              </td>
                              </tr>

                          </table>
                      </td>
                  </tr>
            </table>
            </td>
        </tr>
    </table>
<br /><br />

        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="hidemainmenu" value="0" />
        <input type="hidden" name="option" value="<?php echo $option; ?>" />
        <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
        <input type="hidden" name="tempname" value="<?php echo $row->template_name; ?>" />
        <input type="hidden" name="locked" value="<?php echo $row->locked; ?>" />
        <input type="hidden" name="task" value="" />
    </form>

<?php
}

// Templates list

function listTemplatesSummary($rows, $option){
	global $mainframe, $jlistConfig;

    $temp_typ = array();
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP1');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP2');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP3');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP4');
    $temp_typ[] = JText::_('JLIST_BACKEND_TEMP_TYP5'); 
    
	JHTML::_('behavior.tooltip');
?>
	<form action="index2.php" method="post" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
	  <tr align="right">
		  <td colspan="7"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_LOCKED_DESC'); ?></td>
	  </tr>
		<tr>
			<th width="5" align="left"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_TITLE')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_TYP')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_ACTIVE')." "; ?></th>
			<th class="title"><?php echo JText::_('JLIST_BACKEND_TEMPLIST_LOCKED')." "; ?></th>
        </tr>
		<?php
		$k = 0;
		for($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			$link 		= 'index2.php?option=com_jdownloads&task=templates.edit.summary&hidemainmenu=1&cid='.$row->id;
			$checked 	= JHTML::_('grid.checkedout', $row, $i );
			?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $checked; ?></td>
			<td><a href="<?php echo $link; ?>" title="<?php echo JText::_('JLIST_BACKEND_TEMPEDIT_EDIT');?>"><?php echo $row->template_name; ?></a></td>

            <td>
            <?php
            echo $temp_typ[$row->template_typ -1]; ?>
            </td>

            <td>
            <?php
            if ($row->template_active > 0) { ?>
                <img src="components/com_jdownloads/images/active.png" width="12px" height="12px" align="middle" border="0"/>
            <?php } ?>
            </td>

			<td>
            <?php
            if ($row->locked > 0) { ?>
                <img src="components/com_jdownloads/images/active.png" width="12px" height="12px" align="middle" border="0"/>
            <?php } ?>
            </td>

            <?php $k = 1 - $k;  } ?>
		</tr>
	</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0" />
</form>

<?php
}

function editTemplatesSummary($option, $row){
global $mainframe, $jlistConfig;
$editor =& JFactory::getEditor();
    ?>
    <script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'templates.cancel.summary') {
			submitform( pressbutton );
			return;
		}

		if (form.template_name.value == ""){
			alert( "<?php echo JText::_('JLIST_BACKEND_TEMPEDIT_ERROR_TITLE');?>" );
		} else {
			submitform( pressbutton );
		}
	}
	</script>

	<form action="index2.php" method="post" name="adminForm" id="adminForm">
	<table width="100%" border="0">
		<tr>
			<td width="100%" valign="top">
			<table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo $row->id ? JText::_('JLIST_BACKEND_TEMPEDIT_EDIT') : JText::_('JLIST_BACKEND_TEMPEDIT_ADD');?></th>
		      	</tr>
		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table>
		  					<tr valign="top">
		    					<td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME')." "; ?></strong><br />
		    					<?php 
                                          if ($row->locked){
                                             $dis = 'disabled="disabled"';     
                                          } else {
                                             $dis = '';
                                          } ?>
                                    <input name="template_name" value="<?php echo $row->template_name; ?>" <?php echo $dis; ?> size="70" maxlength="64"/>
		       					</td>
		       					<td><?php if (!$dis) { echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME_DESCRIPTION'); 
                                        } else { echo JText::_('JLIST_BACKEND_TEMPEDIT_NAME_DESCRIPTION').'<br />'.JText::_('JLIST_BACKEND_TEMPEDIT_TITLE_NOT_ALLOWED_TO_CHANGE_DESK'); }?>
                                   </td>
		  					</tr>
                            <tr valign="top">
                                <td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NOTE_TITLE')." "; ?></strong><br />
                                    <textarea name="note" rows="1" cols="80"><?php echo $row->note; ?></textarea>
                                   </td>
                                   <td><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_NOTE_DESC'); ?>
                                   </td>
                              </tr>
		  					<tr>
		  						<td><strong><?php echo JText::_('JLIST_BACKEND_TEMPEDIT_TEXT')." "; ?></strong><br />
		  							<?php
                                    if ($jlistConfig['layouts.editor'] == "1") {
                                    	echo $editor->display( 'template_text',  @$row->template_text , '100%', '500', '80', '5' ) ;
                                        } else {?>
                                        <textarea name="template_text" rows="20" cols="80"><?php echo $row->template_text; ?></textarea>
                                        <?php
                                        } ?>
		  						</td>
		       					<td valign="top">
                                   <?php
                                        echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FINAL_DESC');
                                   ?>
		       					</td>
		  					</tr>

                            <tr>
	      		              <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FINAL_INFO_TITLE')." "; ?></th>
           	                  </tr>
                              <tr><td>
                              <br />
                              <div><img src="components/com_jdownloads/images/summary.gif" alt="Templates Infos" border="1" /></div>
                              </td>
                              <td valign="top"><?php echo JText::_('JLIST_BACKEND_SETTINGS_TEMPLATES_FINAL_INFO_TEXT');?>
                              </td>
                              </tr>

		  				</table>
		  			</td>
		  		</tr>
			</table>
			</td>
		</tr>
	</table>
<br /><br />

		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
        <input type="hidden" name="tempname" value="<?php echo $row->template_name; ?>" />
        <input type="hidden" name="locked" value="<?php echo $row->locked; ?>" />
		<input type="hidden" name="task" value="" />
	</form>

<?php
}

// css edit
function cssEdit($option, $css_file, $css_writable) {

    //$css_file = stripslashes($css_file);
    $f=fopen($css_file,"r");
    $css_text = fread($f, filesize($css_file));
    $css_text = htmlspecialchars($css_text);
    ?>

	<form action="index2.php" method="post" name="adminForm" id="adminForm">
	
	<table width="100%" border="0">
		<tr>
			<td width="100%" valign="top">
			<table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_EDIT_CSS_TITLE');?> </th>
		      	</tr>
		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table>
		  					<tr colspan="2" valign="top">
		    					<td><strong>
                                    <?php
                                        echo JText::_('JLIST_BACKEND_EDIT_CSS_WRITE_STATUS_TEXT')." ";
                                        if ($css_writable) {
                                            echo JText::_('JLIST_BACKEND_EDIT_LANG_CSS_FILE_WRITABLE_YES');
                                        } else {
                                            echo JText::_('JLIST_BACKEND_EDIT_LANG_CSS_FILE_WRITABLE_NO');
                                            ?>
                                            </strong><br />
                                            <?php echo JText::_('JLIST_BACKEND_EDIT_LANG_CSS_FILE_WRITABLE_INFO'); ?><br />
                                        <?php } ?>
		       					</td>
		  					</tr>
		  					<tr>
		  						<td><strong><?php echo JText::_('JLIST_BACKEND_EDIT_CSS_FIELD_TITLE').": ".$css_file; ?></strong><br />
                                    <textarea name="css_text" rows="36" cols="100"><?php echo $css_text ?></textarea>
		  						</td>
		  					</tr>
		  				</table>
		  			</td>
		  		</tr>
			</table>
			</td>
		</tr>
	</table>
<br /><br />

		<input type="hidden" name="css_file" value="<?php echo $css_file; ?>" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
	</form>
	<?php
}

// language edit
function languageEdit($option, $lang_file, $lang_writable) {

    //$lang_file = stripslashes($lang_file);
    $f=fopen($lang_file,"r");
    $lang_text = fread($f, filesize($lang_file));
    $lang_text = htmlspecialchars($lang_text);
    ?>
    
	<form action="index2.php" method="post" name="adminForm" id="adminForm">

	<table width="100%" border="0">
		<tr>
			<td width="100%" valign="top">
			<table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_EDIT_LANG_TITLE');?> </th>
		      	</tr>
		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table>
		  					<tr colspan="2" valign="top">
		    					<td><strong>
                                <?php
                                echo JText::_('JLIST_BACKEND_EDIT_LANG_WRITE_STATUS_TEXT')." ";
                                if ($lang_writable) {
                                    echo JText::_('JLIST_BACKEND_EDIT_LANG_CSS_FILE_WRITABLE_YES');
                                } else {
                                    echo JText::_('JLIST_BACKEND_EDIT_LANG_CSS_FILE_WRITABLE_NO');
                                    ?>
                                    </strong><br />
		       					    <?php echo JText::_('JLIST_BACKEND_EDIT_LANG_CSS_FILE_WRITABLE_INFO'); ?>
                                    <?php } ?>
		       					</td>
		  					</tr>

		  					<tr>
		  						<td><strong><?php echo JText::_('JLIST_BACKEND_EDIT_LANG_FIELD_TITLE').": ".$lang_file; ?></strong><br />
                                    <textarea name="lang_text" rows="36" cols="100"><?php echo $lang_text ?></textarea>
		  						</td>
		  					</tr>
		  				</table>
		  			</td>
		  		</tr>
			</table>
			</td>
		</tr>
	</table>
<br /><br />
		<input type="hidden" name="lang_file" value="<?php echo $lang_file; ?>" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
	</form>
	<?php
}

/* ==============================================
/  Configuration
/ =============================================== */
function showConfig($option, $list_sortorder, $cats_sortorder, $user_groups, $file_plugin_inputbox, $file_plugin_inputbox2, $inputbox_pic, $inputbox_pic_file, $inputbox_hot, $inputbox_new, $inputbox_down, $inputbox_down2, $inputbox_mirror_1, $inputbox_mirror_2, $inputbox_upd, $inputbox_down_plg){
	global $jlistConfig, $mainframe;
	jimport( 'joomla.html.pane');
  ?>
  <form action="index2.php" method="post" name="adminForm" id="adminForm">
	<div>&nbsp;<br /></div>

<?php	
$pane =& JPane::getInstance('Tabs');
echo $pane->startPane('jdconfig');
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_TABTEXT_DOWNLOADS'),'downloads');
?>
<table width="97%" border="0">
	<tr>
		<td width="40%" valign="top">

<?php /* Global config */ ?>
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_FILES_HEAD')." "; ?></th>
		      	</tr>

		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table width="100%">
						<tr>
       					<td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_UPLOADDIR')." "; ?></strong><br />
	    					<?php echo JPATH_SITE.'/';?>
		    					<input name="jlistConfig[files.uploaddir]" value="<?php echo $jlistConfig['files.uploaddir']; ?>" size="50" />/<br />
	    					<?php echo (is_writable(JPATH_SITE.DS.$jlistConfig['files.uploaddir'])) ? JText::_('JLIST_BACKEND_FILESEDIT_URL_DOWNLOAD_WRITABLE') : JText::_('JLIST_BACKEND_FILESEDIT_URL_DOWNLOAD_NOTWRITABLE');?></td>
	   					<td>
	    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_UPLOADDIR_DESC');?>
	   					</td>
	  					</tr>

                        <tr>
    					<td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_UPLOADDIRTEMP')." "; ?></strong><br />
	    					<?php echo JPATH_SITE.'/'.$jlistConfig['files.uploaddir'].'/tempzipfiles';?>
                            <br />
	    					<?php echo (is_writable(JPATH_SITE.DS.$jlistConfig['files.uploaddir'].'/tempzipfiles')) ? JText::_('JLIST_BACKEND_FILESEDIT_URL_TEMP_WRITABLE') : JText::_('JLIST_BACKEND_FILESEDIT_URL_TEMP_NOTWRITABLE');?></td>
    					<td>
	    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_UPLOADDIRTEMP_DESC');?>
    					</td>
	  					</tr>

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_DATETIME')." "; ?></strong><br />
                                <input name="jlistConfig[global.datetime]" value="<?php echo $jlistConfig['global.datetime']; ?>" size="30" maxlength="50"/></td>
                        <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_DATETIME_DESC');?>
                        </td>
                          </tr>                        
                        
                        <tr>
    					<td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_ZIPFILE_PREFIX_TEXT')." "; ?></strong><br />
		    					<input name="jlistConfig[zipfile.prefix]" value="<?php echo $jlistConfig['zipfile.prefix']; ?>" size="30" maxlength="50"/></td>
    					<td>
                            <br />
	    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_ZIPFILE_PREFIX_DESC');?>
    					</td>
	  					</tr>

 						<tr>
    					<td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_DEL_TEMPFILE_TIME')." "; ?></strong><br />
		    					<input name="jlistConfig[tempfile.delete.time]" value="<?php echo $jlistConfig['tempfile.delete.time']; ?>" size="10" maxlength="10"/></td>
    					<td>
                            <br />
	    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEL_TEMPFILE_TIME_DESC');?>
    					</td>
	  					</tr>

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_DIRECT_DOWNLOAD_ACTIVE_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[direct.download]","",($jlistConfig['direct.download']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_DIRECT_DOWNLOAD_ACTIVE_DESC');?>
                        </td>
                          </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_USE_SRIPT_FOR_DOWNLOAD_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[use.php.script.for.download]","",($jlistConfig['use.php.script.for.download']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_USE_SRIPT_FOR_DOWNLOAD_DESC');?>
                        </td>
                          </tr>                            	  					
                        
                        <tr>
                              <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_VIEW_FILE_TYPES')." "; ?></strong><br />
                               <input name="jlistConfig[file.types.view]" value="<?php echo $jlistConfig['file.types.view']; ?>" size="50" maxlength="500"/>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_VIEW_FILE_TYPES_DESC');?>
                        </td>                
                        </tr>
                         <tr><td colspan="2"><hr></td></tr> 
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SET_AMOUNTS_FILE_LIMIT_TITLE')." "; ?></strong><br />
                                <input name="jlistConfig[limited.download.number.per.day]" value="<?php echo $jlistConfig['limited.download.number.per.day']; ?>" size="10" maxlength="4"/></td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SET_AMOUNTS_FILE_LIMIT_DESC');?>
                        </td>
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SET_AUP_FE_MESSAGE_NO_DOWNLOAD_EDIT')." "; ?></strong><br />
                                <textarea name="jlistConfig[limited.download.reached.message]" rows="3" cols="40"><?php echo stripslashes($jlistConfig['limited.download.reached.message']); ?></textarea>
                                </td>
                                <td valign="top"><br />
                                </td>
                        </tr>                        
                          <tr><td colspan="2"><hr></td></tr> 
                        <tr>
                              <td width="330"><strong><font color="#990000"><?php echo JText::_('JLIST_BACKEND_SETTINGS_RESET_COUNTER_TITEL')." "; ?></font></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[reset.counters]","",($jlistConfig['reset.counters']) ? 1:0);?> 
                        </td>
                        <td>
                        <br />
                               <font color="#990000"><?php echo JText::_('JLIST_BACKEND_SETTINGS_RESET_COUNTER_DESC');?></font>
                        </td>                
                        </tr>                                                                          

                    </table>
                   </td>
                  </tr>
                 </table>
			</td>
		</tr>
	</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_FAUTO_TAB_TITLE'),'autodetect');
?>
<table width="97%" border="0">
    <tr>
        <td width="40%" valign="top">

<?php /* config for autodetect downloads/files */ ?>
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_FAUTO_HEADER_TITLE')." "; ?></th>
                  </tr>

                  <tr>
                      <td valign="top" align="left" width="100%">
                          <table width="100%">

                       <tr>
                        <td valign="top" width="330"><strong><font color="#990000"><?php echo JText::_('JLIST_BACKEND_SETTINGS_FAUTO')." "; ?></font></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[files.autodetect]","",($jlistConfig['files.autodetect']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_FAUTO_DESC');?>
                        </td>
                          </tr>
                        
                        <tr>
                        <td valign="top" width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FAUTO_ALL_FILES_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[all.files.autodetect]","",($jlistConfig['all.files.autodetect']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_FAUTO_ALL_FILES_DESC');?>
                        </td>
                        </tr> 
                        
                        <tr>
                        <td valign="top" width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FAUTO_ONLY_THIS_FILES_TITLE')." "; ?></strong><br />
                               <input name="jlistConfig[file.types.autodetect]" value="<?php echo $jlistConfig['file.types.autodetect']; ?>" size="50" maxlength="500"/>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_FAUTO_ONLY_THIS_FILES_DESC');?>
                        </td>                
                        </tr>                       
                        
                        <tr>
                        <td valign="top" width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_DOWNLOADS_AUTOPUBLISH_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[autopublish.founded.files]","",($jlistConfig['autopublish.founded.files']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_DOWNLOADS_AUTOPUBLISH_DESC');?>
                        </td>
                        </tr>
                    </table>
                   </td>
                  </tr>
                 </table>
            </td>
        </tr>
    </table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_TABTEXT_FRONTEND'),'frontend');
?>
<table width="97%" border="0">
	<tr>
		<td width="40%" valign="top">

<?php /* Frontend config */ ?>
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_FRONTEND_HEAD')." "; ?></th>
		      	</tr>

		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table width="100%">

						<tr>
    					<td width="330"><strong><font color="#990000"><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_HEADER_TITLE')." "; ?></font></strong><br />
                               <input name="jlistConfig[jd.header.title]" value="<?php echo $jlistConfig['jd.header.title']; ?>" size="50" maxlength="80" />
                        </td>
    					<td>
    					<br />
    					       <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_HEADER_DESC').'<br />'.JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_HEADER_DESC2');?>
    					</td>
	  					</tr>                        
						
						<tr>
    					<td width="330"><strong><font color="#990000"><?php echo JText::_('JLIST_BACKEND_OFFLINE_OPTION_TITLE')." "; ?></font></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[offline]","",($jlistConfig['offline']) ? 1:0);?>
                        </td>
    					<td>
    					<br />
    					       <?php echo JText::_('JLIST_BACKEND_OFFLINE_OPTION_DESC');?>
    					</td>
	  					</tr>

                        <tr>
		    			<td width="330"><strong><?php echo JText::_('JLIST_BACKEND_OFFLINE_MESSAGE_TITLE')." "; ?></strong><br />
                                <textarea name="jlistConfig[offline.text]" rows="10" cols="40"><?php echo stripslashes($jlistConfig['offline.text']); ?></textarea>
                                </td>
		    					<td valign="top"><br />
		    					<?php echo JText::_('JLIST_BACKEND_OFFLINE_MESSAGE_DESC');?>
		    					</td>
    					</tr>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_COMPO_TEXT')." "; ?></strong><br />
                                <textarea name="jlistConfig[downloads.titletext]" rows="4" cols="40"><?php echo stripslashes($jlistConfig['downloads.titletext']); ?></textarea>
                                </td>
                                <td valign="top"><br />
                                <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_COMPO_TEXT_DESC');?>
                                </td>
                        </tr>

                        <tr>
		    			<td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_FOOTER_TEXT_TITLE')." "; ?></strong><br />
                                <textarea name="jlistConfig[downloads.footer.text]" rows="4" cols="40"><?php echo stripslashes($jlistConfig['downloads.footer.text']); ?></textarea>
                                </td>
		    					<td valign="top"><br />
		    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_FOOTER_TEXT_DESC');?>
		    					</td>
    					</tr>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_CATLISTBOX_ACTIVE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[show.header.catlist]","",($jlistConfig['show.header.catlist']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_CATLISTBOX_DESC');?>
                        </td>                
                        </tr>                        
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_CAT_VIEW_INFO_IN_LISTS_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[view.category.info]","",($jlistConfig['view.category.info']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_CAT_VIEW_INFO_IN_LISTS_TEXT');?>
                        </td>                
                        </tr>
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_VIEW_DETAILSITE_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[view.detailsite]","",($jlistConfig['view.detailsite']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_VIEW_DETAILSITE_DESC');?>
                        </td>
                        </tr>                         
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_VIEW_PAGENAVI_TOP_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[option.navigate.top]","",($jlistConfig['option.navigate.top']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_VIEW_PAGENAVI_TOP_TEXT');?>
                        </td>                
                        </tr>
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_VIEW_PAGENAVI_BOTTOM_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[option.navigate.bottom]","",($jlistConfig['option.navigate.bottom']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_VIEW_PAGENAVI_BOTTOM_TEXT');?>
                        </td>                
                        </tr> 
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_BACK_BUTTON')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[view.back.button]","",($jlistConfig['view.back.button']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_BACK_BUTTON_DESC');?>
                        </td>                
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_USE_PIC_AND_TEXT_FOR_DOWNLOAD_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[view.also.download.link.text]","",($jlistConfig['view.also.download.link.text']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_USE_PIC_AND_TEXT_FOR_DOWNLOAD_TITLE_DESC');?>
                        </td>                
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_USE_LIGHTBOX_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[use.lightbox.function]","",($jlistConfig['use.lightbox.function']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_USE_LIGHTBOX_DESC');?>
                        </td>                
                        </tr>                        
                        <tr><td colspan="2"><hr></td></tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_USE_CUT_DESCRIPTION_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[auto.file.short.description]","",($jlistConfig['auto.file.short.description']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_USE_CUT_DESCRIPTION_TITLE_DESC');?>
                        </td>
                        </tr>                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_USE_CUT_DESCRIPTION_LENGTH_TITLE')." "; ?></strong><br />
                                <input name="jlistConfig[auto.file.short.description.value]" value="<?php echo $jlistConfig['auto.file.short.description.value']; ?>" size="10" maxlength="10"/></td>
                        <td>
                        <br />
                        <?php echo JText::_('JLIST_BACKEND_USE_CUT_DESCRIPTION_LENGTH_TITLE_DESC');?>
                        </td>
                        </tr>                        
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_CONFIG_REPORT_FILE_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[use.report.download.link]","",($jlistConfig['use.report.download.link']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_CONFIG_REPORT_FILE_DESC');?>
                        </td>
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_CONFIG_REPORT_FILE_REGGED_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[report.link.only.regged]","",($jlistConfig['report.link.only.regged']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_CONFIG_REPORT_FILE_REGGED_DESC');?>
                        </td>
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_CONFIG_REPORT_FILE_MAIL_TITLE')." "; ?></strong><br />
                                <textarea name="jlistConfig[send.mailto.report]" rows="2" cols="40"><?php echo $jlistConfig['send.mailto.report']; ?></textarea> 
                        <td valign="top">
                            <br />
                            <?php echo JText::_('JLIST_CONFIG_REPORT_FILE_MAIL_DESC');?>
                        </td>
                        </tr>                                                
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_RATING_ON_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[view.ratings]","",($jlistConfig['view.ratings']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_RATING_ON_DESC');?>
                        </td>
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_RATING_ONLY_REGGED_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[rating.only.for.regged]","",($jlistConfig['rating.only.for.regged']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_RATING_ONLY_REGGED_DESC');?>
                        </td>
                        </tr>
                        <tr><td colspan="2"><hr></td></tr>                                                
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_CHECKBOX_TEXT')." "; ?></strong><br />
		    					<input name="jlistConfig[checkbox.top.text]" value="<?php echo $jlistConfig['checkbox.top.text']; ?>" size="50" maxlength="80"/></td>
    					<td>
    					<br />
    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_CHECKBOX_TEXT_DESC').'<br />'.JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_USE_CHECKBOX_INFO');?>
    					</td>
	  					</tr>

						<tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_FILES_PER_SIDE')." "; ?></strong><br />
		    					<input name="jlistConfig[files.per.side]" value="<?php echo $jlistConfig['files.per.side']; ?>" size="10" maxlength="10"/></td>
    					<td>
    					<br />
    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_FILES_PER_SIDE_DESC');?>
    					</td>
	  					</tr>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_SORTCATSORDER_TEXT')." "; ?></strong><br />
                        <?php
                        $catsorder = (int)$jlistConfig['cats.order'];
                        $inputbox = JHTML::_('select.genericlist', $cats_sortorder, 'jlistConfig[cats.order]' , 'size="3" class="inputbox"', 'value', 'text', $catsorder );
                        echo $inputbox; ?>
                        </td>
                        <td valign="top">
                        <br />
                        <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_SORTCATSORDER_DESC');?>
                        </td>
                          </tr>
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_SORTFILESORDER_TEXT')." "; ?></strong><br />
                        <?php
                        $filesorder = (int)$jlistConfig['files.order'];
                        $inputbox = JHTML::_('select.genericlist', $list_sortorder, 'jlistConfig[files.order]' , 'size="5" class="inputbox"', 'value', 'text', $filesorder );
                        echo $inputbox; ?>
                        </td>
    					<td valign="top">
    					<br />
    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_SORTFILESORDER_DESC');?>
    					</td>
	  					</tr>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                        <td width="330"><font color="#990000"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_JCOMMENTS_TITLE')." "; ?></strong></font><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[jcomments.active]","",($jlistConfig['jcomments.active']) ? 1:0);?>
                        </td>
                        <td>
                               <?php 
                               $jcomments = $mainframe->getCfg('absolute_path') . '/components/com_jcomments/jcomments.php';
                               if (file_exists($jcomments)) {
                                    echo JText::_('JLIST_BACKEND_SETTINGS_JCOMMENTS_EXISTS_DESC');
                               } else {
                                    echo JText::_('JLIST_BACKEND_SETTINGS_JCOMMENTS_DESC');
                               } ?>
                        </td>
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_JCOMMENTS_VIEW_SUM_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[view.sum.jcomments]","",($jlistConfig['view.sum.jcomments']) ? 1:0);?>
                        </td>
                        <td valign="top">
                        <br />
                        <?php echo JText::_('JLIST_BACKEND_SETTINGS_JCOMMENTS_VIEW_SUM_DESC');?>
                        </td>
                        </tr>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                        <td width="330"><font color="#990000"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_JOM_COMMENT_TITLE')." "; ?></strong></font><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[view.jom.comment]","",($jlistConfig['view.jom.comment']) ? 1:0);?>
                        </td>
                        <td>
                               <?php 
                               if (file_exists(JPATH_PLUGINS . DS . 'content' . DS . 'jom_comment_bot.php')){
                                    echo JText::_('JLIST_BACKEND_SETTINGS_JOM_COMMENT_EXISTS_DESC');
                               } else {
                                    echo JText::_('JLIST_BACKEND_SETTINGS_JOM_COMMENT_DESC');
                               } ?>
                        </td>  
                        </tr>
                        <tr><td colspan="2"><font color="#990000"><?php echo JText::_('JLIST_BACKEND_SETTINGS_JOM_COMMENT_NOTE'); ?></font></td></tr>
		  				</table>
		  			</td>
		  		</tr>
				</table>
					</td>
				</tr>
				</table>						
				
            </td>
		</tr>
	</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_TABTEXT_BACKEND'),'backend');
?>
<table width="97%" border="0">
	<tr>
		<td width="40%" valign="top">

<?php /* Backend config */ ?>
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_BACKEND_HEAD')." "; ?></th>
		      	</tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_CONFIG_CREATE_AUTO_DIR_NAME_TITLE')." "; ?></strong><br />
                                <?php echo JHTML::_('select.booleanlist',"jlistConfig[create.auto.cat.dir]","",($jlistConfig['create.auto.cat.dir']) ? 1:0);?>
                        <td>
                        <br />
                        <?php echo JText::_('JLIST_CONFIG_CREATE_AUTO_DIR_NAME_DESC');?>
                        </td>
                        </tr>                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_NEW_FILES_ORDER_FIRST_TITLE')." "; ?></strong><br />
                                <?php echo JHTML::_('select.booleanlist',"jlistConfig[be.new.files.order.first]","",($jlistConfig['be.new.files.order.first']) ? 1:0);?>
                        <td>
                        <br />
                        <?php echo JText::_('JLIST_BACKEND_SETTINGS_NEW_FILES_ORDER_FIRST_DESC');?>
                        </td>
                          </tr>                          					    
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BE_SETTINGS_FILES_PER_SIDE_BE_TITLE')." "; ?></strong><br />
		    					<input name="jlistConfig[files.per.side.be]" value="<?php echo $jlistConfig['files.per.side.be']; ?>" size="10" maxlength="5"/>
    					<td>
    					<br />
    					<?php echo JText::_('JLIST_BE_SETTINGS_FILES_PER_SIDE_BE_DESC');?>
    					</td>
	  					</tr>

                        <tr>
    					<td width="330"><br /><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_EDITOR_FILES')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[files.editor]","",($jlistConfig['files.editor']) ? 1:0);?>
                        </td>
    					<td>
    					       <?php echo JText::_('JLIST_BACKEND_SETTINGS_EDITOR_FILES_DESC');?>
    					</td>
	  					</tr>

                        <tr>
    					<td width="330"><br /><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_EDITOR_CATS')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[categories.editor]","",($jlistConfig['categories.editor']) ? 1:0);?>
                        </td>
    					<td>
    					       <?php echo JText::_('JLIST_BACKEND_SETTINGS_EDITOR_CATS_DESC');?>
    					</td>
	  					</tr>

                        <tr>
    					<td width="330"><br /><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_EDITOR_LICENSES')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[licenses.editor]","",($jlistConfig['licenses.editor']) ? 1:0);?>
                        </td>
    					<td>
    					       <?php echo JText::_('JLIST_BACKEND_SETTINGS_EDITOR_LICENSES_DESC');?>
    					</td>
	  					</tr>

                        <tr>
    					<td width="330"><br /><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_EDITOR_LAYOUTS')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[layouts.editor]","",($jlistConfig['layouts.editor']) ? 1:0);?>
                        </td>
    					<td>
    					       <?php echo JText::_('JLIST_BACKEND_SETTINGS_EDITOR_LAYOUTS_DESC');?>
    					</td>
	  					</tr>
	  					
                        <tr>
		    			<td width="330"><br /><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FILE_LANGUAGE_LIST')." "; ?></strong><br />
                                <textarea name="jlistConfig[language.list]" rows="4" cols="40"><?php echo $jlistConfig['language.list']; ?></textarea>
                                </td>
		    					<td valign="top"><br />
		    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_FILE_LANGUAGE_LIST_DESC');?>
		    					</td>
    					</tr>

                        <tr>
		    			<td width="330"><br /><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FILE_SYSTEM_LIST')." "; ?></strong><br />
                                <textarea name="jlistConfig[system.list]" rows="4" cols="40"><?php echo $jlistConfig['system.list']; ?></textarea>
                                </td>
		    					<td valign="top"><br />
		    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_FILE_SYSTEM_LIST_DESC');?>
		    					</td>
    					</tr>

		  				</table>
		  			</td>
		  		</tr>
				</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_TABTEXT_IMAGES'),'pics');
?>
<table width="97%" border="0">
	<tr>
		<td width="40%" valign="top">

<?php /* Images config */ ?>
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_IMAGES_HEAD')." "; ?></th>
		      	</tr>

		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table width="100%">
                        <tr>
                        <td colspan="2">
                        <?php echo JText::_('JLIST_BACKEND_SETTINGS_IMAGES_NOTE')." "; ?>
                        </td>
                        </tr>
                        <tr>
    					<td valign="top" width="330"><br /><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_MINIPICS_SIZE')." "; ?></strong><br />
                          <input name="jlistConfig[info.icons.size]" value="<?php echo $jlistConfig['info.icons.size']; ?>" size="5" maxlength="5"/> px 
                          </td>
    					<td valign="top">
	    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_MINIPICS_SIZE_DESC').'<br />'.JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_USE_SYMBOLE_INFO').'<br />';
                                  $msize =  $jlistConfig['info.icons.size'];
                                  $sample_path = $mainframe->getSiteURL().'images/jdownloads/miniimages/'; 
                                  $sample_pic = '<img src="'.$sample_path.'date.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="" /> ';
                                  $sample_pic .= '<img src="'.$sample_path.'language.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="" /> ';
                                  $sample_pic .= '<img src="'.$sample_path.'weblink.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="" />';
                                  $sample_pic .= '<img src="'.$sample_path.'stuff.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="" /> ';
                                  $sample_pic .= '<img src="'.$sample_path.'contact.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="" /> ';
                                  $sample_pic .= '<img src="'.$sample_path.'system.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="" />';
                                  $sample_pic .= '<img src="'.$sample_path.'currency.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="" /> ';
                                  $sample_pic .= '<img src="'.$sample_path.'download.png" align="middle" width="'.$msize.'" height="'.$msize.'" border="0" alt="" />';
                                  echo $sample_pic; ?>
    					</td>
	  					</tr>
                        <tr><td colspan="3"><hr></td></tr>
                        <tr>
                        <td valign="top" width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_CATPICS_SIZE')." "; ?></strong><br />
                                <input name="jlistConfig[cat.pic.size]" value="<?php echo $jlistConfig['cat.pic.size']; ?>" size="5" maxlength="5"/> px</td>
                        <td valign="top">
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_CATPICS_SIZE_DESC');?>
                        </td>
                        </tr>
                        <tr>
                          <td valign="top"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_PIC_TITLE')." "; ?></strong><br />
                             <?php echo $inputbox_pic; ?>
                          </td>
                          <td valign ="top"><?php echo ' '.JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_PIC_DESC'); ?>
                          </td>
                        </tr>
                        <tr>
                             <td valign="top">
                               <script language="javascript" type="text/javascript">
                                if (document.adminForm.cat_pic.options.value!=''){
                                    jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/catimages/'; ?>" + getSelectedText( 'adminForm', 'cat_pic' );
                                } else {
                                     jsimg='';
                                }
                                document.write('<img src=' + jsimg + ' name="imagelib" width="<?php echo $jlistConfig['cat.pic.size']; ?>" height="<?php echo $jlistConfig['cat.pic.size']; ?>" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_FILE_NO_DEFAULT_PIC'); ?>" />');
                               </script>
                             </td>
                        </tr> 
                         <tr><td colspan="3"><hr></td></tr>
                        <tr>
                        <td valign="top" width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_FILEPICS_SIZE')." "; ?></strong><br />
                                <input name="jlistConfig[file.pic.size]" value="<?php echo $jlistConfig['file.pic.size']; ?>" size="5" maxlength="5"/> px</td>
                        <td valign="top">
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_FILEPICS_SIZE_DESC');?>
                        </td>
                        </tr>
                        <tr>
                          <td valign="top"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_FILE_PIC_TITLE')." "; ?></strong><br />
                             <?php echo $inputbox_pic_file; ?>
                          </td>
                          <td valign ="top"><?php echo ' '.JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_FILE_PIC_DESC'); ?>
                          </td>
                          </tr>

                           <tr>
                             <td valign="top">
                               <script language="javascript" type="text/javascript">
                                if (document.adminForm.file_pic.options.value!=''){
                                    jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/fileimages/'; ?>" + getSelectedText( 'adminForm', 'file_pic' );
                                } else {
                                     jsimg='';
                                }
                                document.write('<img src=' + jsimg + ' name="imagelib2" width="<?php echo $jlistConfig['file.pic.size']; ?>" height="<?php echo $jlistConfig['file.pic.size']; ?>" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_FILE_NO_DEFAULT_PIC'); ?>" />');
                               </script>
                             </td>
                          </tr>
                          <tr><td colspan="3"><hr></td></tr> 
                          <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_NEW_TITLE')." "; ?></strong><br />
                                <input name="jlistConfig[days.is.file.new]" value="<?php echo $jlistConfig['days.is.file.new']; ?>" size="5" maxlength="5"/></td>
                        <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_NEW_DESC');?>
                        </td>
                          </tr>
                          
                         <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_HOT_NEW_PIC_TITLE')." "; ?></strong><br />
                            <?php echo $inputbox_new; ?>     
                        </td>
                        <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_HOT_NEW_PIC_DESC2');?>
                        </td>
                          </tr>
                          <tr>
                          <td valign="top">
                               <script language="javascript" type="text/javascript">
                                if (document.adminForm.new_pic.options.value!=''){
                                    jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/newimages/'; ?>" + getSelectedText( 'adminForm', 'new_pic' );
                                } else {
                                     jsimg='';
                                }
                                document.write('<img src=' + jsimg + ' name="imagelib4" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_FILE_NO_DEFAULT_PIC'); ?>" />');
                               </script>
                          </td>
                        </tr>  
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_HOT_TITLE')." "; ?></strong><br />
                                <input name="jlistConfig[loads.is.file.hot]" value="<?php echo $jlistConfig['loads.is.file.hot']; ?>" size="5" maxlength="10"/></td>
                        <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_HOT_DESC');?>
                        </td>
                          </tr>  
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_HOT_NEW_PIC_TITLE')." "; ?></strong><br />
                            <?php echo $inputbox_hot; ?>
                        </td>
                        <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_HOT_NEW_PIC_DESC');?>
                        </td>
                        </tr>
                        <tr>
                          <td valign="top">
                               <script language="javascript" type="text/javascript">
                                if (document.adminForm.hot_pic.options.value!=''){
                                    jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/hotimages/'; ?>" + getSelectedText( 'adminForm', 'hot_pic' );
                                } else {
                                     jsimg='';
                                }
                                document.write('<img src=' + jsimg + ' name="imagelib3" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_FILE_NO_DEFAULT_PIC'); ?>" />');
                               </script>
                          </td>
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_UPD_TITLE')." "; ?></strong><br />
                                <input name="jlistConfig[days.is.file.updated]" value="<?php echo $jlistConfig['days.is.file.updated']; ?>" size="5" maxlength="5"/></td>
                        <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_UPD_DESC');?>
                        </td>
                          </tr>
                          
                         <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_HOT_NEW_PIC_TITLE')." "; ?></strong><br />
                            <?php echo $inputbox_upd; ?>     
                        </td>
                        <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_HOT_NEW_PIC_DESC3');?>
                        </td>
                          </tr>
                          <tr>
                          <td valign="top">
                               <script language="javascript" type="text/javascript">
                                if (document.adminForm.upd_pic.options.value!=''){
                                    jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/updimages/'; ?>" + getSelectedText( 'adminForm', 'upd_pic' );
                                } else {
                                     jsimg='';
                                }
                                document.write('<img src=' + jsimg + ' name="imagelib8" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_FILE_NO_DEFAULT_PIC'); ?>" />');
                               </script>
                          </td>
                        </tr> 
                        <tr><td colspan="3"><hr></td></tr>   
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_DETAILS_DOWNLOAD_BUTTON_TITLE')." "; ?></strong><br />
                             <?php echo $inputbox_down; ?>     
                         <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_DETAILS_DOWNLOAD_BUTTON_DESC');?>
                        </td>
                        </tr>                            
                        <tr>
                          <td valign="top">
                               <script language="javascript" type="text/javascript">
                                if (document.adminForm.down_pic.options.value!=''){
                                    jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/downloadimages/'; ?>" + getSelectedText( 'adminForm', 'down_pic' );
                                } else {
                                     jsimg='';
                                }
                                document.write('<img src=' + jsimg + ' name="imagelib5" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_FILE_NO_DEFAULT_PIC'); ?>" />');
                               </script>
                          </td>
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FILES_DOWNLOAD_BUTTON_TITLE')." "; ?></strong><br />
                             <?php echo $inputbox_down2; ?>     
                         <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_FILES_DOWNLOAD_BUTTON_DESC');?>
                        </td>
                        </tr>                            
                        <tr>
                          <td valign="top">
                               <script language="javascript" type="text/javascript">
                                if (document.adminForm.down_pic2.options.value!=''){
                                    jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/downloadimages/'; ?>" + getSelectedText( 'adminForm', 'down_pic2' );
                                } else {
                                     jsimg='';
                                }
                                document.write('<img src=' + jsimg + ' name="imagelib9" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_FILE_NO_DEFAULT_PIC'); ?>" />');
                               </script>
                          </td>
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_DETAILS_MIRROR_BUTTON_TITLE1')." "; ?></strong><br />
                             <?php echo $inputbox_mirror_1; ?>     
                         <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_DETAILS_DOWNLOAD_BUTTON_DESC');?>
                        </td>
                        </tr>                            
                        <tr>
                          <td valign="top">
                               <script language="javascript" type="text/javascript">
                                if (document.adminForm.mirror_1_pic.options.value!=''){
                                    jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/downloadimages/'; ?>" + getSelectedText( 'adminForm', 'mirror_1_pic' );
                                } else {
                                     jsimg='';
                                }
                                document.write('<img src=' + jsimg + ' name="imagelib6" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_FILE_NO_DEFAULT_PIC'); ?>" />');
                               </script>
                          </td>
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_DETAILS_MIRROR_BUTTON_TITLE2')." "; ?></strong><br />
                             <?php echo $inputbox_mirror_2; ?>     
                         <td>
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_DETAILS_DOWNLOAD_BUTTON_DESC');?>
                        </td>
                        </tr>                            
                        <tr>
                          <td valign="top">
                               <script language="javascript" type="text/javascript">
                                if (document.adminForm.mirror_2_pic.options.value!=''){
                                    jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/downloadimages/'; ?>" + getSelectedText( 'adminForm', 'mirror_2_pic' );
                                } else {
                                     jsimg='';
                                }
                                document.write('<img src=' + jsimg + ' name="imagelib7" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_FILE_NO_DEFAULT_PIC'); ?>" />');
                               </script>
                          </td>
                        </tr>
                        <tr><td colspan="3"><hr></td></tr>
                        <tr><td colspan="3"><strong><?php echo JText::_('JLIST_CONFIG_SETTINGS_THUMBS_TITLE')." "; ?></strong><br />
                            <?php echo JText::_('JLIST_CONFIG_SETTINGS_THUMBS_INFO')." "; ?><br /><br />
                            <?php if (function_exists(gd_info)){
                                        echo '<font color="green">'.JText::_('JLIST_CONFIG_SETTINGS_THUMBS_STATUS_GD_OK').'</font>';
                                  } else {
                                        echo '<font color="red">'.JText::_('JLIST_CONFIG_SETTINGS_THUMBS_STATUS_GD_NOT_OK').'</font>';
                                  } ?>   
                        </td></tr>
                        <tr>
                        <td width="330">
                                <input name="jlistConfig[thumbnail.size.height]" value="<?php echo $jlistConfig['thumbnail.size.height']; ?>" size="6" maxlength="5"/> px</td>
                        <td>
                            <?php echo JText::_('JLIST_CONFIG_SETTINGS_THUMBS_SIZE_HEIGHT');?>
                        </td>
                          </tr>
                          <tr>
                           <td width="330">
                                <input name="jlistConfig[thumbnail.size.width]" value="<?php echo $jlistConfig['thumbnail.size.width']; ?>" size="6" maxlength="5"/> px</td>
                           <td>
                            <?php echo JText::_('JLIST_CONFIG_SETTINGS_THUMBS_SIZE_WIDTH');?>
                           </td>
                        </tr>
                        
                        <tr>
                        <td width="330"><br /><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_VIEW_PLACEHOLDER_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[thumbnail.view.placeholder]","",($jlistConfig['thumbnail.view.placeholder']) ? 1:0);?>
                               <br />
                               <?php 
                               $nopic = '<img src="'.$mainframe->getSiteURL().'images/jdownloads/screenshots/thumbnails/no_pic.gif"/>';
                               echo $nopic;
                               ?> 
                        </td>
                        <td valign="top">
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_VIEW_PLACEHOLDER_TEXT');?>
                        </td>                
                        </tr>
                        
                        <tr>
                        <td width="330"><br /><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_VIEW_PLACEHOLDER_IN_LIST_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[thumbnail.view.placeholder.in.lists]","",($jlistConfig['thumbnail.view.placeholder.in.lists']) ? 1:0);?>
                        </td>
                        <td valign="top">
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_VIEW_PLACEHOLDER_IN_LIST_TEXT');?>
                        </td>                
                        </tr>
                        
                        <tr>
                        <td width="330"><br /><strong><?php echo JText::_('JLIST_CONFIG_SETTINGS_THUMBS_CREATE_ALL_NEW_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist','resize_thumbs', 'class="inputbox"', 0);?>
                        </td>
                        <td>
                               <?php echo JText::_('JLIST_CONFIG_SETTINGS_THUMBS_CREATE_ALL_NEW_DESC');?>
                        </td>
                          </tr>   
                           
                        </table>
		  			</td>
		  		</tr>
  			</table>
       </td>
	</tr>
</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_MEDIA_TAB_TITLE'),'multimedia');
?>
<table width="97%" border="0">
    <tr>
        <td width="40%" valign="top">
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_MEDIA_TITLE')." "; ?></th>
                  </tr>

                  <tr>
                      <td valign="top" align="left" width="100%">
                          <table width="100%">
                       <tr>
                        <td colspan="2"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_MP3_DESC1')." "; ?></strong>
                        </td>
                       </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_MP3_CONFIG_TITLE')." "; ?></strong><br />
                            <textarea name="jlistConfig[mp3.player.config]" rows="6" cols="40"><?php echo stripslashes($jlistConfig['mp3.player.config']); ?></textarea>
                        </td>
                        <td valign="top">
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_MP3_CONFIG_DESC');?>
                        </td>
                        </tr> 
                        <tr>
                        <td width="330"><br /><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_MP3_CONFIG_VIEW_ID3_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[mp3.view.id3.info]","",($jlistConfig['mp3.view.id3.info']) ? 1:0);?>
                        </td>
                        <td valign="top">
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_MP3_CONFIG_VIEW_ID3_DESC');?>
                        </td>                
                        </tr>
                       
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_MP3_CONFIG_VIEW_ID3_LAY_TITLE')." "; ?></strong><br />
                            <textarea name="jlistConfig[mp3.info.layout]" rows="12" cols="40"><?php echo stripslashes($jlistConfig['mp3.info.layout']); ?></textarea>
                        </td>
                        <td valign="top">
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_MP3_CONFIG_VIEW_ID3_LAY_DESC');?>
                        </td>
                        </tr>
                        <tr>
                        <td colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_MP3_DESC2')." "; ?>
                        </td>                
                        </tr>                       
                    </table>
                   </td>
                  </tr>
                 </table>
            </td>
        </tr>
    </table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_FE_UPLOAD_TAB_TITLE'),'upload');
?>
<table width="97%" border="0">
    <tr>
        <td width="40%" valign="top">
                 <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_CONFIG_UPLOAD_GENERAL_HEAD')." "; ?></th>
                  </tr>

                  <tr>
                      <td valign="top" align="left" width="100%">
                          <table width="100%">
                       <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_CONFIG_UPLOAD_FILENAME_BLANK_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fix.upload.filename.blanks]","",($jlistConfig['fix.upload.filename.blanks']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_CONFIG_UPLOAD_FILENAME_BLANK_DESC');?>
                        </td>                
                        </tr>                          

                       <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_CONFIG_UPLOAD_FILENAME_LOW_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fix.upload.filename.uppercase]","",($jlistConfig['fix.upload.filename.uppercase']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_CONFIG_UPLOAD_FILENAME_LOW_DESC');?>
                        </td>                
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_CONFIG_UPLOAD_FILENAME_SPECIAL_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fix.upload.filename.specials]","",($jlistConfig['fix.upload.filename.specials']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_CONFIG_UPLOAD_FILENAME_SPECIAL_DESC');?>
                        </td>                
                        </tr>
                        <tr><td colspan="2"><font color="#990000"><?php echo JText::_('JLIST_CONFIG_UPLOAD_IMAGE_FILENAME_NOTE');?></font></td></tr>                                                  
                          
                         </table>
                      </td>
                   </tr>
                </table>          
<?php /* upload config */ ?>
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_FE_UPLOAD_TITLE_HEAD')." "; ?></th>
                  </tr>

                  <tr>
                      <td valign="top" align="left" width="100%">
                          <table width="100%">

                        <tr>
                        <td width="330"><strong><font color="#990000"><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_ACTIVE')." "; ?></font></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[frontend.upload.active]","",($jlistConfig['frontend.upload.active']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_ACTIVE_DESC');?>
                        </td>                
                        </tr>
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_PERMISSIONS')." "; ?></strong><br />
                               
                               <?php
                               $upload_x = intval($jlistConfig['upload.access']);
                               $acc_list = JHTML::_('select.genericlist', $user_groups, 'jlistConfig[upload.access]', 'size="1" class="inputbox"', 'value', 'text',  $upload_x); 
                               echo $acc_list; ?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_PERMISSIONS_DESC');?>
                        </td>                
                        </tr>                        
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_FRONTEND_UPLOAD_AUTO_PUBLISH')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[upload.auto.publish]","",($jlistConfig['upload.auto.publish']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_FRONTEND_UPLOAD_AUTO_PUBLISH_DESC');?>
                        </td>                
                        </tr>                        
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_FILETYPES')." "; ?></strong><br />
                               <input name="jlistConfig[allowed.upload.file.types]" value="<?php echo $jlistConfig['allowed.upload.file.types']; ?>" size="50" maxlength="500"/>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_FILETYPES_DESC');?>
                        </td>                
                        </tr>                            

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_FILESIZE')." "; ?></strong><br />
                               <input name="jlistConfig[allowed.upload.file.size]" value="<?php echo $jlistConfig['allowed.upload.file.size']; ?>" size="50" maxlength="80"/><br />
                               <?php echo JText::_('JLIST_UPLOAD_MAX_FILESIZE_INFO_TITLE').' '. ini_get('upload_max_filesize'); ?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_FILESIZE_DESC');?>
                        </td>                
                        </tr>

                         <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_FORM_TEXT')." "; ?></strong><br />
                                <textarea name="jlistConfig[upload.form.text]" rows="8" cols="40"><?php echo stripslashes($jlistConfig['upload.form.text']); ?></textarea>
                                </td>
                                <td valign="top"><br />
                                <?php echo JText::_('JLIST_BACKEND_SETTINGS_FRONTEND_UPLOADS_FORM_TEXT_DESC');?>
                                </td>
                        </tr>
                        
                        </table>
                      </td>
                  </tr>
                </table>
       </td>
    </tr>
    <tr>
      <td>
         <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_DESC_TITLE_HEAD')." "; ?></th>
                  </tr>

                  <tr>
                      <td valign="top" align="left" width="100%">
                         <table width="100%">
                           <tr>
                              <td colspan="2">
                                <?php echo JText::_('JLIST_CONFIG_FEUPLOAD_DESC_OPTION')." "; ?>
                              </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_AUTHOR_NAME');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.author]","",($jlistConfig['fe.upload.view.author']) ? 1:0);?>
                            </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_AUTHOR_URL');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.author.url]","",($jlistConfig['fe.upload.view.author.url']) ? 1:0);?>
                            </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_RELEASE_VIEW');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.release]","",($jlistConfig['fe.upload.view.release']) ? 1:0);?>
                            </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_PRICE_VIEW');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.price]","",($jlistConfig['fe.upload.view.price']) ? 1:0);?>
                            </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_LICENSE_VIEW');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.license]","",($jlistConfig['fe.upload.view.license']) ? 1:0);?>
                            </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_LANGUAGE_VIEW');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.language]","",($jlistConfig['fe.upload.view.language']) ? 1:0);?>
                            </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_SYSTEM_VIEW');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.system]","",($jlistConfig['fe.upload.view.system']) ? 1:0);?>
                            </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_LONG_DESC_VIEW');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.desc.long]","",($jlistConfig['fe.upload.view.desc.long']) ? 1:0);?>
                            </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_SHORT_DESC_VIEW');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.desc.short]","",($jlistConfig['fe.upload.view.desc.short']) ? 1:0);?>
                            </td>
                            <td>
                            <br />
                               <?php echo JText::_('JLIST_CONFIG_FEUPLOAD_SHORT_DESC_DESC');?>
                            </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_FILE_PIC_VIEW');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.pic.upload]","",($jlistConfig['fe.upload.view.pic.upload']) ? 1:0);?>
                            </td>
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_EXTERN_FILE_VIEW');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.extern.file]","",($jlistConfig['fe.upload.view.extern.file']) ? 1:0);?>
                            </td>
                            <td>
                            <br />
                               <?php echo JText::_('JLIST_CONFIG_FEUPLOAD_EXTERN_FILE_DESC');?>
                            </td> 
                           </tr>
                           <tr>     
                            <td width="330"><?php echo JText::_('JLIST_CONFIG_FEUPLOAD_UPLOAD_FILE_VIEW');?><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[fe.upload.view.select.file]","",($jlistConfig['fe.upload.view.select.file']) ? 1:0);?>
                            </td>
                            <td>
                            <br />
                               <?php echo JText::_('JLIST_CONFIG_FEUPLOAD_UPLOAD_FILE_DESC');?>
                            </td> 
                           </tr>
                         </table>     
                      </td>    
                 </tr>
          </table>       
       </td>
    </tr>
</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_TABTEXT_SECURITY'),'security');
?>
<table width="97%" border="0">
	<tr>
		<td width="40%" valign="top">

<?php /* Backend config */ ?>
                <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_SECURITY_HEAD')." "; ?></th>
		      	</tr>
                
                <tr>
                    <td width="330"><br />
                        <strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_ANTILEECH_TITLE')." "; ?></strong><br />
                         <?php echo JHTML::_('select.booleanlist',"jlistConfig[anti.leech]","",($jlistConfig['anti.leech']) ? 1:0);?>
                    </td>
                    <td>
                        <?php echo JText::_('JLIST_BACKEND_SETTINGS_ANTILEECH_DESK');?>
                    </td>
                  </tr> 
                <tr>
                    <td width="330"><br />
                        <strong><?php echo JText::_('JLIST_STOP_LEECHING_OPTION_TITLE')." "; ?></strong><br />
                         <?php echo JHTML::_('select.booleanlist',"jlistConfig[check.leeching]","",($jlistConfig['check.leeching']) ? 1:0);?>
                    </td>
                    <td>
                        <?php echo JText::_('JLIST_STOP_LEECHING_OPTION_DESC');?>
                    </td>
                  </tr>
                  <tr>
                    <td width="330"><br />
                        <strong><?php echo JText::_('JLIST_STOP_LEECHING_OPTION_NO_REFERER_TITLE')." "; ?></strong><br />
                         <?php echo JHTML::_('select.booleanlist',"jlistConfig[block.referer.is.empty]","",($jlistConfig['block.referer.is.empty']) ? 1:0);?>
                    </td>
                    <td>
                        <?php echo JText::_('JLIST_STOP_LEECHING_OPTION_NO_REFERER_DESC');?>
                    </td>
                  </tr>
                  <tr>
                     <td width="330"><br /><strong><?php echo JText::_('JLIST_STOP_LEECHING_ALLOWED_SITES_OPTION_TITLE')." "; ?></strong><br />
                             <textarea name="jlistConfig[allowed.leeching.sites]" rows="4" cols="40"><?php echo $jlistConfig['allowed.leeching.sites']; ?></textarea>
                     </td>
                     <td valign="top"><br />
                              <?php echo JText::_('JLIST_STOP_LEECHING_ALLOWED_SITES_OPTION_DESC');?>
                     </td>
                   </tr>                  
                                    

<!-- Mail encode -->
                <tr>
    				<td width="330"><br />
						<strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_MAIL_SECURITY_TITEL')." "; ?></strong><br />
                     	<?php echo JHTML::_('select.booleanlist',"jlistConfig[mail.cloaking]","",($jlistConfig['mail.cloaking']) ? 1:0);?>
                    </td>
    				<td>
    					<?php echo JText::_('JLIST_BACKEND_SETTINGS_MAIL_SECURITY_DESC');?>
    				</td>
	  			</tr>

  				</table>
  			</td>
  		</tr>
</table>

<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_TABTEXT_EMAIL'),'email');
?>
<table width="97%" border="0">
	<tr>
		<td width="40%" valign="top">

<?php /* E-Mail config */ ?>
			<table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_MAIL_HEAD')." "; ?></th>
		      	</tr>

		      	<tr>
		      		<td valign="top" align="left" width="100%">
		      			<table width="100%">

                        <tr>
    					<td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_OPTION')." "; ?></strong><br />
                                <?php echo JHTML::_('select.booleanlist',"jlistConfig[send.mailto.option]","",($jlistConfig['send.mailto.option']) ? 1:0);?>
                        </td>
    					<td>
    					       <?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_OPTION_DESC');?>
    					</td>
	  					</tr>

                        <tr>
    					<td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_HTML')." "; ?></strong><br />
                                <?php echo JHTML::_('select.booleanlist',"jlistConfig[send.mailto.html]","",($jlistConfig['send.mailto.html']) ? 1:0);?>
                        </td>
    					<td>
    					       <?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_HTML_DESC');?>
    					</td>
	  					</tr>

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO')." "; ?></strong><br />
                                <textarea name="jlistConfig[send.mailto]" rows="2" cols="40"><?php echo $jlistConfig['send.mailto']; ?></textarea> 
    					<td>
                            <br />
        					<?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_DESC');?>
    					</td>
	  					</tr>

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_BETREFF')." "; ?></strong><br />
		    					<input name="jlistConfig[send.mailto.betreff]" value="<?php echo $jlistConfig['send.mailto.betreff']; ?>" size="50" maxlength="80"/></td>
    					<td>
                            <br />
        					<?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_BETREFF_DESC');?>
    					</td>
	  					</tr>
                        
                        <tr>
                          <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_MAIL_UPLOAD_TEMPLATE_TITLE')." "; ?></strong><br />
                              <textarea name="jlistConfig[send.mailto.template.download]" rows="10" cols="40"><?php echo stripslashes($jlistConfig['send.mailto.template.download']); ?></textarea>
                          </td>
                          <td valign="top">
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_MAIL_DOWNLOAD_TEMPLATE_DESC');?>
                        </td>
                          
                        </tr>
                    </table>
		  			</td>
		  		</tr>
                  
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_MAIL_UPLOAD_HEAD')." "; ?></th>
                  </tr>

                  <tr>
                      <td valign="top" align="left" width="100%">
                          <table width="100%">

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_OPTION')." "; ?></strong><br />
                                <?php echo JHTML::_('select.booleanlist',"jlistConfig[send.mailto.option.upload]","",($jlistConfig['send.mailto.option.upload']) ? 1:0);?>
                        </td>
                        <td>
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_OPTION_UPLOAD_DESC');?>
                        </td>
                          </tr>

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_HTML')." "; ?></strong><br />
                                <?php echo JHTML::_('select.booleanlist',"jlistConfig[send.mailto.html.upload]","",($jlistConfig['send.mailto.html.upload']) ? 1:0);?>
                        </td>
                        <td>
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_HTML_DESC');?>
                        </td>
                          </tr>

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO')." "; ?></strong><br />
                                <textarea name="jlistConfig[send.mailto.upload]" rows="2" cols="40"><?php echo $jlistConfig['send.mailto.upload']; ?></textarea> 
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_UPLOAD_DESC');?>
                        </td>
                          </tr>

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_BETREFF')." "; ?></strong><br />
                                <input name="jlistConfig[send.mailto.betreff.upload]" value="<?php echo $jlistConfig['send.mailto.betreff.upload']; ?>" size="50" maxlength="80"/></td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_SEND_MAILTO_BETREFF_DESC');?>
                        </td>
                          </tr>
                        <tr>
                          <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_MAIL_UPLOAD_TEMPLATE_TITLE')." "; ?></strong><br />
                              <textarea name="jlistConfig[send.mailto.template.upload]" rows="10" cols="40"><?php echo stripslashes($jlistConfig['send.mailto.template.upload']); ?></textarea>
                          </td>
                          <td valign="top">
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_MAIL_UPLOAD_TEMPLATE_DESC');?>
                        </td>
                          
                        </tr>                          
                          
                    </table>
                      </td>
                  </tr>
                  
				</table>
                
                </td>
    </tr>
</table>
                
<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_TABTEXT_SPECIALS'),'specials');
?>
<table width="97%" border="0">
    <tr>
        <td width="40%" valign="top">

<?php /* upload config */ ?>
           <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_ADSENSE_TITLE')." "; ?></th>
                  </tr>

                  <tr>
                      <td valign="top" align="left" width="100%">
                          <table width="100%">
                          <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_ADSENSE_ACTIVATE_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[google.adsense.active]","",($jlistConfig['google.adsense.active']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_ADSENSE_ACTIVATE_DESC');?>
                        </td>                
                        </tr>

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_ADSENSE_CODE_TITLE')." "; ?></strong><br />
                                <textarea name="jlistConfig[google.adsense.code]" rows="8" cols="40"><?php echo stripslashes($jlistConfig['google.adsense.code']); ?></textarea>
                                </td>
                                <td valign="top"><br />
                                <?php echo JText::_('JLIST_BACKEND_SETTINGS_ADSENSE_CODE_DESC');?>
                                </td>
                        </tr>
                        
                        </table>
                      </td>
                  </tr>
                </table>
       </td>
    </tr>
    <tr>
      <td>
         <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_WAITING_HEADER')." "; ?></th>
                  </tr>

                  <tr>
                      <td valign="top" align="left" width="100%">
                         <table width="100%">
                          <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_WAITING_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[countdown.active]","",($jlistConfig['countdown.active']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_WAITING_DESC');?>
                        </td>                
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_WAITING_VALUE_TITLE')." "; ?></strong><br />
                                <input name="jlistConfig[countdown.start.value]" value="<?php echo $jlistConfig['countdown.start.value']; ?>" size="5" /></td>
                        <td>
                            <br />
                        
                        </td>
                          </tr>                        

                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_WAITING_NOTE_TITLE')." "; ?></strong><br />
                                <textarea name="jlistConfig[countdown.text]" rows="8" cols="40"><?php echo stripslashes($jlistConfig['countdown.text']); ?></textarea>
                                </td>
                                <td valign="top"><br />
                                <?php echo JText::_('JLIST_BACKEND_SETTINGS_WAITING_NOTE_DESC');?>
                                </td>
                        </tr>

                         </table>     
                      </td>    
                 </tr>
                 
        
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SET_AUP_HEADER')." "; ?></th>
                  </tr>
                  <tr><td><font color="#990000"><?php echo JText::_('JLIST_BACKEND_SET_AUP_HEADER_TEXT'); ?></font></td></tr>
                  <tr>
                  <td valign="top" align="left" width="100%">
                      <table width="100%">
                  <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SET_AUP_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[use.alphauserpoints]","",($jlistConfig['use.alphauserpoints']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SET_AUP_DESC');?>
                        </td>                
                        </tr>
                          <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SET_AUP_DOWNLOAD_WHEN_ZERO_POINTS_TEXT')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[user.can.download.file.when.zero.points]","",($jlistConfig['user.can.download.file.when.zero.points']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SET_AUP_DOWNLOAD_WHEN_ZERO_POINTS_DESC');?>
                        </td>                
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SET_AUP_FE_MESSAGE_NO_DOWNLOAD_EDIT')." "; ?></strong><br />
                                <textarea name="jlistConfig[user.message.when.zero.points]" rows="3" cols="40"><?php echo stripslashes($jlistConfig['user.message.when.zero.points']); ?></textarea>
                                </td>
                                <td valign="top"><br />
                                </td>
                        </tr>
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SET_AUP_USE_FILE_PRICE_TITLE')." "; ?></strong><br />
                               <?php echo JHTML::_('select.booleanlist',"jlistConfig[use.alphauserpoints.with.price.field]","",($jlistConfig['use.alphauserpoints.with.price.field']) ? 1:0);?>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SET_AUP_USE_FILE_PRICE_DESC');?>
                        </td>                
                        </tr>                                                
                         </table>     
                  </td>    
             </tr> 
                 
<?php if ($jlistConfig['pad.exists']){ ?>
             </table> 
                 <tr>
                     <td>
                 
                 <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_PAD_HEAD_TITLE')." "; ?></th>
                  </tr>

                  <tr>
                      <td valign="top" align="left" width="100%">
                          <table width="100%">
                       <tr>
                        <td colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_PAD_INFO_TEXT'); ?>
                        </td>
                       </tr>
                       <tr>
                        <td width="330"><strong><font color="#990000"><?php echo JText::_('JLIST_BACKEND_SETTINGS_PAD_ACTIVATE_TITLE')." "; ?></font></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[pad.use]","",($jlistConfig['pad.use']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_PAD_ACTIVATE_DESC');?>
                        </td>
                          </tr>
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_PAD_FOLDER_TITLE')." "; ?></strong><br />
                            <input name="jlistConfig[pad.folder]" value="<?php echo $jlistConfig['pad.folder']; ?>" disabled="disabled" size="50" maxlength="100"/><br />
                            <?php echo (is_writable(JPATH_SITE.DS.$jlistConfig['pad.folder'])) ? JText::_('JLIST_BACKEND_FILESEDIT_URL_DOWNLOAD_WRITABLE') : JText::_('JLIST_BACKEND_FILESEDIT_URL_DOWNLOAD_NOTWRITABLE');?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_SETTINGS_PAD_FOLDER_DESC');?>
                        </td>
                        </tr> 
                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_PAD_LANGUAGE_TITLE')." "; ?></strong><br />
                               <input name="jlistConfig[pad.language]" value="<?php echo $jlistConfig['pad.language']; ?>" size="50" maxlength="50"/>
                        </td>
                        <td>
                        <br />
                               <?php echo JText::_('JLIST_BACKEND_SETTINGS_PAD_LANGUAGE_DESC');?>
                        </td>                
                        </tr>                       
                    </table>
                   </td>
                  </tr>
                 </table>                 
   <?php } ?>
                     </td>
                 </tr>
                 
          </table>       
       </td>
    </tr>
</table>




<?php
echo $pane->endPanel();
echo $pane->startPanel(JText::_('JLIST_BACKEND_SETTINGS_TABTEXT_PLUGINS'),'plugins');
?>
<table width="97%" border="0">
  <tr>
    <td width="40%" valign="top">

      <?php /* File Plugin config */ ?>
      <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
         <tr>
            <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_SETTINGS_GLOBAL_FILEPLUGIN_HEAD'); ?></th>
         </tr>
         <tr>
           <td valign="top" align="left" width="100%">
              <table width="100%">

                <?php
                if (!$file_plugin_inputbox){
                ?> <tr>
                     <td width="330"><strong>
                     <?php echo '<font color="#990000">'.JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_NOT_INSTALLED').'</font>'; ?>
                     </td>
                   </tr>
                <?php
                } else {
                ?>
               <tr>
                <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_DEFAULTLAYOUT')." "; ?></strong><br />
                   <?php
                     echo( $file_plugin_inputbox);
                   ?>
                </td>
                <td>
                   <?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_DEFAULTLAYOUT_DESC');?>
                </td>
               </tr>
               <tr>
               <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_DETAILS_DOWNLOAD_BUTTON_TITLE')." "; ?></strong><br />
                  <?php echo $inputbox_down_plg; ?>     
                 <td>
                  
                </td>
                </tr>                            
                <tr>
                    <td valign="top">
                         <script language="javascript" type="text/javascript">
                          if (document.adminForm.down_pic_plg.options.value!=''){
                              jsimg="<?php echo $mainframe->getSiteURL().'images/jdownloads/downloadimages/'; ?>" + getSelectedText( 'adminForm', 'down_pic_plg' );
                          } else {
                              jsimg='';
                          }
                          document.write('<img src=' + jsimg + ' name="imagelib10" border="1" alt="<?php echo JText::_('JLIST_BACKEND_SETTINGS_DEFAULT_CAT_FILE_NO_DEFAULT_PIC'); ?>" />');
                          </script>
                     </td>
                </tr>
               <tr>
                <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_ENABLEPLUGIN')." "; ?></strong><br />
                   <?php echo JHTML::_('select.booleanlist',"jlistConfig[fileplugin.enable_plugin]","",($jlistConfig['fileplugin.enable_plugin']) ? 1:0);?>
                </td>
                <td>
                   <?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_ENABLEPLUGIN_DESC');?>
                </td>
               </tr>
               <tr>
                <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_SHOWDISABLED')." "; ?></strong><br />
                   <?php echo JHTML::_('select.booleanlist',"jlistConfig[fileplugin.show_jdfiledisabled]","",($jlistConfig['fileplugin.show_jdfiledisabled']));?>
                </td>
                <td>
                   <?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_SHOWDISABLED_DESC');?>
                </td>
               </tr>
               <tr>
                <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_DOWNLOADTITLE')." "; ?></strong><br />
                   <?php echo JHTML::_('select.booleanlist',"jlistConfig[fileplugin.show_downloadtitle]","",($jlistConfig['fileplugin.show_downloadtitle']) ? 1:0);?>
                </td>
                <td>
                   <?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_DOWNLOADTITLE_DESC');?>
                </td>
               </tr>

               <tr>
                 <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_OFFLINETITLE')." "; ?></strong><br />
                     <textarea name="jlistConfig[fileplugin.offline_title]" rows="3" cols="40"><?php echo stripslashes($jlistConfig['fileplugin.offline_title']); ?></textarea>
                 </td>
                 <td valign="top"><br />
                    <?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_OFFLINETITLE_DESC');?>
                 </td>
               </tr>

               <tr>
                 <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_OFFLINEDESC')." "; ?></strong><br />
                     <textarea name="jlistConfig[fileplugin.offline_descr]" rows="3" cols="40"><?php echo $jlistConfig['fileplugin.offline_descr']; ?></textarea>
                 </td>
                 <td valign="top"><br />
                    <?php echo JText::_('JLIST_BACKEND_SETTINGS_FILEPLUGIN_OFFLINEDESC_DESC');?>
                 </td>
               </tr>
               <tr><td colspan="2"><hr></td></tr>
               <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_USE_CUT_DESCRIPTION_TITLE')." "; ?></strong><br />
                            <?php echo JHTML::_('select.booleanlist',"jlistConfig[plugin.auto.file.short.description]","",($jlistConfig['plugin.auto.file.short.description']) ? 1:0);?>
                        </td>
                        <td>
                            <br />
                            <?php echo JText::_('JLIST_BACKEND_USE_CUT_DESCRIPTION_TITLE_DESC');?>
                        </td>
                        </tr>                        
                        <tr>
                        <td width="330"><strong><?php echo JText::_('JLIST_BACKEND_USE_CUT_DESCRIPTION_LENGTH_TITLE')." "; ?></strong><br />
                                <input name="jlistConfig[plugin.auto.file.short.description.value]" value="<?php echo $jlistConfig['plugin.auto.file.short.description.value']; ?>" size="10" maxlength="10"/></td>
                        <td>
                        <br />
                        <?php echo JText::_('JLIST_BACKEND_USE_CUT_DESCRIPTION_LENGTH_TITLE_DESC');?>
                        </td>
                        </tr> 

                <?php
                }
                ?>

              </table>
           </td>
         </tr>
      </table>
    </td>
  </tr>
</table>

<?php
echo $pane->endPanel();
echo $pane->endPane('jdconfig');
?>

	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="root_dir" value="<?php echo $jlistConfig['files.uploaddir'];?>" />	
	<input type="hidden" name="task" value="" />
	</form>
	<?php
}


// show restore
function showRestore($option, $task) {
	global $mainframe;
	?>

	<form action="index2.php" method="post" name="adminForm" enctype="multipart/form-data">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		
		<tr>
			<th class="adminheading" colspan="3"><?php echo JText::_('JLIST_BACKEND_RESTORE_TITLE_HEAD').' '; ?></th>
        </tr>
        <tr>
            <td style="padding:10px;" colspan="3"><?php echo JText::_('JLIST_BACKEND_RESTORE_WARNING'); ?>
            </td>
        </tr>
        <tr>
            <td style="padding:20px;" align="center" colspan="3"><b><?php echo JText::_('JLIST_BACKEND_RESTORE_FILE'); ?></b><br /><br />
                <input type="file" size="50" name="restore_file">
            </td>
        </tr>
		<tr>
			<td style="padding:20px;" align="center" colspan="3"><input type="submit" name="submitbutton" value="<?php echo JText::_('JLIST_BACKEND_RESTORE_RUN');?>" onclick="return confirm('<?php echo JText::_('JLIST_BACKEND_RESTORE_RUN_FINAL');?>');">
			</td>
		</tr>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="restore.run" />
	<input type="hidden" name="hidemainmenu" value="0" />
   </table>
   </form>
 	<?php
}

function scanFiles($option, $task){    
   global $mainframe;
   ?>
    <form action="index2.php" method="post" name="adminForm" id="adminForm">
     <table width="100%" border="0">
      <tr>
        <td width="40%" valign="top">
            <table cellpadding="4" cellspacing="1" border="0" class="adminlist">
                <tr>
                      <th class="adminheading" colspan="2"><?php echo JText::_('JLIST_RUN_MONITORING_TITLE')." "; ?></th>
                  </tr>

                  <tr valign="top" align="center" width="100%">
                     <td style="padding:10px;" colspan="2"><?php echo JText::_('JLIST_RUN_MONITORING_INFO'); ?>
                     </td>
                </tr>
                  <tr valign="top" align="center" width="100%">
                    
                    
                    <?php checkFiles($task); ?>
                    
                </tr>
            </table>
        </td>
      </tr>
     </table>

     <br /><br />
     <?php
        echo '<a href="index2.php?option=com_jdownloads"><big><strong>'.JText::_('JLIST_BACKEND_INFO_LINK_BACK').'</strong></big></a>';
     ?>

      <input type="hidden" name="boxchecked" value="0" />
      <input type="hidden" name="option" value="<?php echo $option; ?>" />
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="hidemainmenu" value="0" />
    </form>
    <?php
}    

// show info
function showInfo($option){
	global $mainframe;
?>
	<form action="index2.php" method="post" name="adminForm" id="adminForm">
<table width="100%" border="0">
	<tr>
		<td width="40%" valign="top">
			<table cellpadding="4" cellspacing="1" border="0" class="adminlist">
		    	<tr>
		      		<th class="adminheading" colspan="2"><?php echo JText::_('JLIST_BACKEND_INFO_TEXT_TITLE')." "; ?></th>
		      	</tr>

		      	<tr valign="top" align="center" width="100%">
		      		<td>
		      		 <img src="components/com_jdownloads/images/jdownloads.jpg" alt="jDownloads Logo"/><br /><br />
                    </td>
                </tr>
		      	<tr valign="top" align="center" width="100%">
                    <td>
                       	<big>jDownloads - a Download Management Component for Joomla!</big><br />
                         Copyright 2007/2008 by Arno Betz - <a href="http://www.jdownloads.com" target="_blank">www.jDownloads.com</a> all rights reserved.
                         <br /><br />
                         <b>jDownloads logo</b> created and copyright by <a href="http://www.rkdesign.ch" target="_blank">rkdesign</a> - all rights reserved.<br /><br />
                         <?php echo JText::_('JLIST_BACKEND_INFO_LICENSE_TITLE').'<br />';
                               echo JText::_('JLIST_BACKEND_INFO_LICENSE_TEXT'); 
                         ?>
   					</td>
				</tr>
            </table>
        </td>
    </tr>
</table>

<br /><br />
<?php
        $back_link = '<a href="index2.php?option=com_jdownloads"><big><strong>'.JText::_('JLIST_BACKEND_INFO_LINK_BACK').'</strong></big></a>';
        echo $back_link;
        ?>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0" />
</form>
	<?php
}

}

?>