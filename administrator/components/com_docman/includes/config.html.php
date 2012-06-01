<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: config.html.php 608 2008-02-18 13:31:26Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_HTML_CONFIG')) {
    return;
} else {
    define('_DOCMAN_HTML_CONFIG', 1);
}

class HTML_DMConfig
{
    function configuration(&$lists)
    {
        global $mosConfig_absolute_path, $mosConfig_live_site, $_DOCMAN;
        $tabs = new mosTabs(1);
        ?>


        <script language="JavaScript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js" type="text/javascript"></script>

		<style>
			.dmtitle { background-color: #EEE; font-weight:  bold; border-bottom: 1px solid #BBB; }
			.checkList label { padding-left: 10px; }
			select option.label { background-color: #EEE; border: 1px solid #DDD; color : #333; }
		</style>

        <?php dmHTML::adminHeading( _DML_CONFIGURATION, 'config' )?>

        <div class="dm_filters">
            <span class="componentheading">docman.config.php:
			 <?php echo is_writable($mosConfig_absolute_path.DS.'administrator'.DS.'components'.DS.'com_docman'.DS.'docman.config.php') ? '<b><font color="green">'._DML_WRITABLE.'</font></b>' : '<b><font color="red">'._DML_UNWRITABLE.'</font></b>' ?>
			</span>
        </div>

        <script language="javascript" type="text/javascript">
            function submitbutton(pressbutton) {
                var form = document.adminForm;
                if (pressbutton == 'cancel') {
                    submitform( pressbutton );
                    return;
                }
		  $msg = "";
          if (form.dmpath.value == ""){
			$msg = "\n<?php echo _DML_CFG_ERR_DOCPATH ;?>";
		  }
		  if( isNaN( parseInt( form.perpage.value ) ) ||
			  parseInt( form.perpage.value ) < 1 ) {
			$msg += "\n<?php echo _DML_CFG_ERR_PERPAGE;?>";
		  }
		  if( isNaN( parseInt( form.days_for_new.value ) ) ||
			  parseInt( form.days_for_new.value ) < 0 ) {
			$msg += "\n<?php echo _DML_CFG_ERR_NEW;?>";
		  }
		  if( isNaN( parseInt( form.hot.value ) ) ||
			  parseInt( form.hot.value ) < 0 ) {
			$msg += "\n<?php echo _DML_CFG_ERR_HOT;?>";
		  }
		  if( form.user_upload.value == "<?php echo _DM_PERMIT_NOOWNER;?>"){
			$msg += "\n<?php echo _DML_CFG_ERR_UPLOAD;?>";
		  }
		  if( form.user_approve.value == "<?php echo _DM_PERMIT_NOOWNER;?>" ){
			$msg += "\n<?php echo _DML_CFG_ERR_APPROVE;?>";
		  }
		  if( form.default_viewer.value == "<?php echo _DM_PERMIT_NOOWNER;?>" ){
			$msg += "\n<?php echo _DML_CFG_ERR_DOWNLOAD;?>";
		  }
		  if( form.default_editor.value == "<?php echo _DM_PERMIT_NOOWNER;?>" ){
			$msg += "\n<?php echo _DML_CFG_ERR_EDIT;?>";
		  }

          if ( $msg != "" ){
                $msghdr = "<?php echo _DML_ENTRY_ERRORS;?>";
                $msghdr += '\n=================================';
                alert( $msghdr+$msg+'\n' );

          } else {
        	   submitform( pressbutton );
          }
        }

        /* Make sure the user can only use 0-9 and K, M, G */
        function dmFilesize(f) {
        	var re = /[0-9KMGkmg]*/;
            f.value = f.value.match(re);
        }
        </script>

        <form action="index2.php?option=com_docman&amp;task=saveconfig" method="post" name="adminForm" id="adminForm">
        <div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>

        <?php
        $tabs->startPane("configPane");
        $tabs->startTab(_DML_GENERAL, "general-page");
        ?>
    <table class="adminform">
        <tr>
            <td width="250"><?php echo _DML_VERSION;?></td>
            <td width="100"><?php echo _DM_VERSION;?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_PATHFORSTORING;?></td>
            <td>
                <?php
                $newpath = $mosConfig_absolute_path.DS.'dmdocuments';
                $path = $_DOCMAN->getCfg('dmpath') ? $_DOCMAN->getCfg('dmpath') : $newpath;
                ?>
                <input size="50" type="text" name="dmpath" value="<?php echo $path?>" />
            </td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_PATHTT . '</span>',  _DML_CFG_PATHFORSTORING);?>
                <input type="button" value="<?php echo _DML_RESETDEFAULT;?>" name="Reset" onclick="document.adminForm.dmpath.value='<?php echo addslashes($newpath);?>';" />
            </td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
    </table>
        <?php
        $tabs->endTab();
        $tabs->startTab(_DML_FRONTEND, "frontend-page");
        ?>

    <table class="adminform">
        <tr>
        	<td class="dmtitle" colspan="3"><?php echo _DML_CFG_GENERALSET;?></td>
        </tr>
        <tr>
            <td width="250"><?php echo _DML_CFG_SECTIONISDOWN;?></td>
            <td width="100"><?php echo $lists['isDown'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_SECTIONTT . '</span>',  _DML_CFG_SECTIONISDOWN);?></td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_EXTENSIONSVIEWING;?>:</td>
            <td><input type="text" name="viewtypes" value="<?php
        echo $_DOCMAN->getCfg('viewtypes', "pdf|doc|txt|jpg|jpeg|gif|png")?>" style="width: 200px" /></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_EXTENSIONSVIEWINGTT . "</span>",  _DML_CFG_EXTENSIONSVIEWING);?></td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_NUMBEROFDOCS;?></td>
            <td><input type="text" name="perpage" value="<?php echo $_DOCMAN->getCfg('perpage', 5);?>" /></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_NUMBERTT . '</span>',  _DML_CFG_NUMBEROFDOCS);?></td>
        </tr>
         <tr>
            <td><?php echo _DML_CFG_DEFAULTLISTING;?></td>
            <td><?php echo $lists['default_order'];?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $lists['default_order2'];?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_EMAILGROUP;?></td>
            <td><?php echo $lists['emailgroups'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_EMAILGROUPTT . '</span>',  _DML_CFG_EMAILGROUP);?></td>
        </tr>
        <tr>
        	<td class="dmtitle" colspan="3"><?php echo _DML_CFG_THEMES;?></td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_ICONSIZE;?></td>
            <td><?php echo $lists['icon_size'];?></td>
            <td>&nbsp;</td>
        </tr>
         <tr>
            <td><?php echo _DML_CFG_TRIMWHITESPACE;?></td>
            <td><?php echo $lists['trimwhitespace'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_TRIMWHITESPACETT . '</span>',  _DML_CFG_TRIMWHITESPACE);?></td>
        </tr>
        <tr>
        	<td class="dmtitle" colspan="3"><?php echo _DML_CFG_EXTRADOCINFO;?></td>
        </tr>
         <tr>
            <td><?php echo _DML_CFG_DAYSFORNEW;?></td>
            <td><input type="text" name="days_for_new" value="<?php echo $_DOCMAN->getCfg('days_for_new', 5);?>" /></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_DAYSFORNEWTT . '</span>',  _DML_CFG_DAYSFORNEW);?></td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_HOT;?></td>
            <td><input type="text" name="hot" value="<?php echo $_DOCMAN->getCfg('hot', 100);?>" /></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_HOTTT . '</span>',  _DML_CFG_HOT);?></td>
        </tr>
        <tr >
            <td><?php echo _DML_CFG_DISPLAYLICENSES;?></td>
            <td><?php echo $lists['display_license'];?></td>
            <td>&nbsp;</td>
        </tr>
         <tr >
            <td><?php echo _DML_CFG_PROCESS_BOTS;?></td>
            <td><?php echo $lists['process_bots'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_PROCESS_BOTSTT . '</span>',  _DML_CFG_PROCESS_BOTS);?></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
    </table>
        <?php
        $tabs->endTab();
        $tabs->startTab(_DML_PERMISSIONS, "permissions-page");
        ?>
    <table class="adminform">
    	<tr>
        	<td class="dmtitle" colspan="3"><?php echo _DML_CFG_GUESTPERM;?></td>
        </tr>
        <tr>
            <td width="250"><?php echo _DML_CFG_GUEST ;?></td>
            <td width="250"><?php echo $lists['guest'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_GUEST_TT ,  _DML_CFG_GUEST);?></td>
        </tr>
        <tr>
        	<td class="dmtitle" colspan="3"><?php echo _DML_CFG_FRONTPERM;?></td>
        </tr>
         <tr>
            <td><?php echo _DML_CFG_UPLOAD;?></td>
            <td><?php echo $lists['user_upload']->toHtml();;?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_UPLOADTT . '</span>',  _DML_CFG_UPLOAD);?></td>
        </tr>
         <tr>
            <td><?php echo _DML_CFG_APPROVE;?></td>
            <td><?php echo $lists['user_approve']->toHtml();?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_APPROVETT . '</span>',  _DML_CFG_APPROVE);?></td>
        </tr>
         <tr>
            <td><?php echo _DML_CFG_PUBLISH;?></td>
            <td><?php echo $lists['user_publish']->toHtml();?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_PUBLISHTT . '</span>',  _DML_CFG_PUBLISH);?></td>
        </tr>
    </table>
    <table class="adminform">
         <tr>
        	<td class="dmtitle" colspan="3"><?php echo _DML_CFG_DOCPERM;?></td>
        </tr>
        <tr>
            <td width="250"><?php echo _DML_CFG_VIEW;?></td>
            <td width="250"><?php echo $lists['default_viewer']->toHtml();?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_VIEWTT . '</span>',  _DML_CFG_VIEW);?></td>
        </tr>
         <?php
        $author_checked = '';
        $editor_checked = '';
        $assign = $_DOCMAN->getCfg('reader_assign');
        if (($assign == 1) || ($assign == 3)) {
            $author_checked = 'checked';
        }
        if (($assign == 2) || ($assign == 3)) {
            $editor_checked = 'checked';
        }
        ?>
		<tr>
			<td><?php echo _DML_CFG_OVERRIDEVIEW;?></td>
			<td class="checkList">
				<input type="checkbox" name="assign_download_author" id="assign_download_author" <?php echo $author_checked;?> /><label for="assign_download_author"><?php echo _DML_CREATOR ?></label><br />
				<input type="checkbox" name="assign_download_editor" id="assign_download_editor" <?php echo $editor_checked;?> /><label for="assign_download_editor"><?php echo _DML_EDITOR ?></label><br />
			</td>
			<td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_WHOCANAREADERTT . '</span>',  _DML_CFG_WHOCANAREADER);?></td>
		</tr>
        <tr>
            <td><?php echo _DML_CFG_MAINTAIN;?></td>
            <td><?php echo $lists['default_maintainer']->toHtml();?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_MAINTAINTT . '</span>',  _DML_CFG_MAINTAIN);?></td>
        </tr>
        <?php
        $author_checked = '';
        $editor_checked = '';
        $assign = $_DOCMAN->getCfg('editor_assign');
        if (($assign == 1) || ($assign == 3)) {
            $author_checked = 'checked';
        }
        if (($assign == 2) || ($assign == 3)) {
            $editor_checked = 'checked';
        }

        ?>

		<tr>
			<td><?php echo _DML_CFG_OVERRIDEMANT;?></td>
			<td class="checkList">
				<input type="checkbox" name="assign_edit_author" id="assign_edit_author" <?php echo $author_checked;?> /><label for="assign_edit_author"><?php echo _DML_CREATOR ?></label><br />
				<input type="checkbox" name="assign_edit_editor" id="assign_edit_editor" <?php echo $editor_checked;?> /><label for="assign_edit_editor"><?php echo _DML_EDITOR ?></label><br />
			</td>
			<td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_WHOCANAEDITORTT . '</span>',  _DML_CFG_WHOCANAEDITOR);?></td>
		</tr>

        <tr>
            <td width="250"><?php echo _DML_CFG_INDIVIDUAL_PERM;?></td>
            <td width="100"><?php echo $lists['individual_perm'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_INDIVIDUAL_PERMTT . '</span>',  _DML_CFG_INDIVIDUAL_PERM);?></td>
        </tr>

   	</table>
    <table class="adminform">
   		<tr>
        	<td class="dmtitle" colspan="3"><?php echo _DML_CFG_CREATORPERM;?></td>
        </tr>
        <tr>
            <td width="250"><?php echo _DML_CFG_CREATORS_PERM;?></td>
            <td width="250"><?php echo $lists['creator_can'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_CREATORSPERMTT . '</span>',  _DML_CFG_CREATORS_PERM);?></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
    </table>
<?php /** removed 1.4.0RC2
    <table class="adminform">
        <tr>
            <td class="dmtitle" colspan="3"><?php echo _DML_CFG_COMPAT;?></td>
        </tr>
        <tr>
            <td width="250"><?php echo _DML_CFG_SPECIALCOMPATMODE;?></td>
            <td width="250"><?php echo $lists['specialcompat'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_SPECIALCOMPATMODETT . '</span>',  _DML_CFG_SPECIALCOMPATMODE);?></td>
        </tr>

        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
    </table>
    */ ?>
        <?php
        $tabs->endTab();
        $tabs->startTab(_DML_UPLOAD, "upload-page");
        ?>
    <table class="adminform">
    	<tr>
        	<td class="dmtitle" colspan="3"><?php echo _DML_CFG_GENERALSET;?></td>
        </tr>
        <tr>
			<td><?php echo _DML_CFG_UPMETHODS;?></td>
			<td><?php echo $lists['methods'];?></td>
			<td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_UPMETHODSTT . '</span>',  _DML_CFG_UPMETHODS);?></td>
		</tr>
        <tr>
            <td><?php echo _DML_CFG_MAXFILESIZE;?></td>
            <td><input type="text" name="maxAllowed" onkeyup="javascript:dmFilesize(this)" value="<?php echo DOCMAN_Utils::number2text($_DOCMAN->getCfg('maxAllowed', 1024000));?>" /></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_MAXFILESIZETT . ini_get('upload_max_filesize') . '</span>',  _DML_CFG_MAXFILESIZE);?></td>
        </tr>
         <tr>
            <td><?php echo _DML_CFG_OVERWRITEFILES;?></td>
            <td><?php echo $lists['overwrite'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_OVERWRITEFILESTT . '</span>',  _DML_CFG_OVERWRITEFILES);?></td>
        </tr>
        <tr>
        	<td class="dmtitle" colspan="3"><?php echo _DML_CFG_FILEXTENSIONS;?></td>
        </tr>
        <tr>
            <td width="250"><?php echo _DML_CFG_EXTALLOWED;?></td>
            <td width="275"><input type="text" name="extensions" value="<?php echo $_DOCMAN->getCfg('extensions', "zip|rar|pdf|txt")?>" /></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_EXTALLOWEDTT . '</span>',  _DML_CFG_EXTALLOWED);?></td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_USERCANUPLOAD;?></td>
            <td><?php echo $lists['user_all'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_USERCANUPLOADTT . '</span>',  _DML_CFG_USERCANUPLOAD);?></td>
        </tr>
         <tr>
        	<td class="dmtitle" colspan="3"><?php echo _DML_CFG_FILENAMES;?></td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_LOWERCASE;?></td>
            <td><?php echo $lists['fname_lc'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_LOWERCASETT . '</span>',  _DML_CFG_LOWERCASE);?></td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_FILENAMEBLANKS;?>:</td>
            <td><?php echo $lists['fname_blank'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_FILENAMEBLANKSTT . '</span>',  _DML_CFG_FILENAMEBLANKS);?></td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_REJECTFILENAMES;?>:</td>
            <td><input type="text" name="fname_reject" value="<?php echo htmlentities( $_DOCMAN->getCfg('fname_reject', ''), ENT_QUOTES);?>" /></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_REJECTFILENAMESTT . '</span>',  _DML_CFG_REJECTFILENAMES);?></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
    </table>
        <?php
        $tabs->endTab();
        $tabs->startTab(_DML_SECURITY, "security-page");
        ?>
    <table class="adminform">
        <tr>
            <td width="250"><?php echo _DML_CFG_ANTILEECH;?></td>
            <td width="100"><?php echo $lists['security_anti_leech'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_ANTILEECHTT . '</span>',  _DML_CFG_ANTILEECH)?></td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_ALLOWEDHOSTS;?></td>
            <td><input type="text" name="security_allowed_hosts" value="<?php echo $_DOCMAN->getCfg('security_allowed_hosts' , $_SERVER["HTTP_HOST"])?>" /></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_ALLOWEDHOSTSTT . '</span>',  _DML_CFG_ALLOWEDHOSTS);?>
            <input type="button" value="<?php echo _DML_RESETDEFAULT;?>" name="Reset" onclick="document.adminForm.security_allowed_hosts.value='<?php echo $_SERVER['HTTP_HOST'];?>';" />
            </td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_LOG;?></td>
            <td><?php echo $lists['log'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_LOGTT . '</span>',  _DML_CFG_LOG);?></td>
        </tr>
        <tr>
            <td><?php echo _DML_CFG_HIDE_REMOTE;?></td>
            <td><?php echo $lists['hide_remote'];?></td>
            <td><?php echo DOCMAN_Utils::mosToolTip(_DML_CFG_HIDE_REMOTETT . '</span>',  _DML_CFG_HIDE_REMOTE);?></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
    </table>

        <?php
        $tabs->endTab();
        $tabs->endPane();
        ?>
        <input type="hidden" name="id" value="" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="option" value="com_docman" />
        <input type="hidden" name="section" value="config" />
        <input type="hidden" name="docman_version" value="<?php echo _DM_VERSION;?>" />
        <?php echo DOCMAN_token::render();?>
    </form>

    <?php include_once($mosConfig_absolute_path."/components/com_docman/footer.php"); ?>

    <?php }
}