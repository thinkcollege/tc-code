<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: docman.html.php 651 2008-03-20 20:33:15Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_HTML_DOCMAN')) {
    return;
} else {
    define('_DOCMAN_HTML_DOCMAN', 1);
}

class HTML_DMDocman
{
    function _quickiconButton( $link, $image, $text, $path = '/administrator/images/', $target = '_self' )
    {
        ?>
        <div style="float:left;">
            <div class="icon">
                <a href="<?php echo $link; ?>" target="<?php echo $target;?>">
                    <?php echo mosAdminMenus::imageCheckAdmin( $image, $path, NULL, NULL, $text ); ?>
                    <span><?php echo $text; ?></span>
                </a>
            </div>
        </div>
        <?php
    }

    function showCPanel()
    {
        global $mosConfig_live_site, $mosConfig_absolute_path;
        global $_DOCMAN;

        ?><script language="JavaScript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_docman/includes/js/docmanjavascript.js"></script>

        <?php if(defined('_DM_J15')) {
            JToolBarHelper::title('&nbsp;', 'dm_logo');
        } else { ?>
            <table>
                <tr>
                    <th><img border="0" alt="DOCman logo" src="components/com_docman/images/dm_logo.png" /></th>
                </tr>
            </table>
        <?php } ?>


        <table class="adminform">
            <tr>
                <td width="55%" valign="top">
                    <div id="cpanel">
                    <?php
                        $link = "index2.php?option=com_docman&amp;section=files";
                        HTML_DMDocman::_quickiconButton( $link, 'dm_files_48.png', _DML_FILES, _DM_ICONPATH);
                        $link = "index2.php?option=com_docman&amp;section=files&amp;task=upload";
                        HTML_DMDocman::_quickiconButton( $link, 'dm_newfile_48.png', _DML_NEW_FILE, _DM_ICONPATH);

                        $link = "index2.php?option=com_docman&amp;section=documents";
                        HTML_DMDocman::_quickiconButton( $link, 'dm_documents_48.png', _DML_DOCS, _DM_ICONPATH );
                        $link = "index2.php?option=com_docman&amp;section=documents&amp;task=new";
                        HTML_DMDocman::_quickiconButton( $link, 'dm_newdocument_48.png', _DML_NEW_DOCUMENT, _DM_ICONPATH );

                        $link = "index2.php?option=com_docman&amp;section=categories";
                        HTML_DMDocman::_quickiconButton( $link, 'categories.png', _DML_CATS);
                        $link = "index2.php?option=com_docman&amp;section=groups";
                        HTML_DMDocman::_quickiconButton( $link, 'user.png', _DML_GROUPS);
                        $link = "index2.php?option=com_docman&amp;section=licenses";
                        HTML_DMDocman::_quickiconButton( $link, 'dm_licenses_48.png', _DML_LICENSES, _DM_ICONPATH );

                        $link = "index2.php?option=com_docman&amp;task=stats";
                        HTML_DMDocman::_quickiconButton( $link, 'dm_stats_48.png', _DML_STATS, _DM_ICONPATH );
                        $link = "index2.php?option=com_docman&amp;section=logs";
                        HTML_DMDocman::_quickiconButton( $link, 'dm_logs_48.png', _DML_DOWNLOAD_LOGS, _DM_ICONPATH);

                        $link = "index2.php?option=com_docman&amp;section=config";
                        HTML_DMDocman::_quickiconButton( $link, 'config.png', _DML_CONFIG);
                        $link = "index2.php?option=com_docman&amp;section=themes";
                        HTML_DMDocman::_quickiconButton( $link, 'templatemanager.png', _DML_THEMES);
                        $link = "index2.php?option=com_docman&amp;section=themes&amp;task=edit&amp;cid[0]=".$_DOCMAN->getCfg('icon_theme');
                        HTML_DMDocman::_quickiconButton( $link, 'dm_edittheme_48.png', _DML_EDIT_DEFAULT_THEME, _DM_ICONPATH);
                        $link = "index2.php?option=com_docman&amp;section=cleardata";
                        HTML_DMDocman::_quickiconButton( $link, 'dm_cleardata_48.png', _DML_CLEARDATA, _DM_ICONPATH);

                        $link = "index2.php?option=com_docman&amp;task=credits";
                        HTML_DMDocman::_quickiconButton( $link, 'credits.png', _DML_CREDITS);

                        HTML_DMDocman::_quickiconButton( _DM_HELP_URL, 'dm_help_48.png', _DML_HELP, _DM_ICONPATH, '_blank');

                    ?>
                    </div>
                </td>
                <td width="45%" valign="top">
                    <div style="width=100%;">
                        <form action="index2.php" method="post" name="adminForm">
                            <?php DOCMAN_Compat::mosLoadAdminModules('dmcpanel', 1);?>
                            <input type="hidden" name="sectionid" value="" />
                            <input type="hidden" id="cid" name="cid[]" value="" />
                            <input type="hidden" name="option" value="com_docman" />
                            <input type="hidden" name="task" value="" />
                        </form>
                    </div>
                </td>
            </tr>
        </table>
    <?php include_once($mosConfig_absolute_path."/components/com_docman/footer.php");
    }

    function showStatistics(&$row)
    {
        global $mosConfig_absolute_path;
        ?>
       <form action="index2.php?option=com_docman" method="post" name="adminForm" id="adminForm">

        <?php dmHTML::adminHeading( _DML_DOCSTATS, 'stats' )?>

        <table class="adminlist" width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
            <thead>
            <tr>
                <th class="title" width="15%" align="left"><?php echo _DML_RANK;?></td>
                <th class="title" width="60%" align="left"><?php echo _DML_TITLE;?></td>
                <th class="title" width="25%" align="left"><?php echo _DML_DOWNLOADS;?></td>
            </tr>
            </thead>

            <tbody>
		<?php
        $enum = 1;
        $color = 0;
        foreach($row as $rows) {

            ?>
				<tr class="row<?php echo $color;?>">
					<td width="15%" align="left"><?php echo $enum;?></td>
					 <td width="60%" align="left"><?php echo $rows->dmname;?></td>
					 <td width="25%" align="left"><b><?php echo $rows->dmcounter;?></b></td>
				</tr>
				<?php
            if (!$color) {
                $color = 1;
            } else {
                $color = 0;
            }
            $enum++;
        }

        ?>
        </tbody>
		</table>
		<input type="hidden" name="task" value="">
        <input type="hidden" name="option" value="com_docman">
		</form>

        <?php include_once($mosConfig_absolute_path."/components/com_docman/footer.php");
    }

    function showCredits( $changelog )
    {
        global $_DOCMAN, $mosConfig_absolute_path;

        ?>
        <form action="index2.php?option=com_docman" method="post" name="adminForm" id="adminForm">
            <input type="hidden" name="task" value="">
            <input type="hidden" name="option" value="com_docman">
        </form>

        <?php if(defined('_DM_J15')) {
            JToolBarHelper::title('&nbsp;', 'dm_logo');
        } else { ?>
            <table>
                <tr>
                    <th><img border="0" alt="DOCman logo" src="components/com_docman/images/dm_logo.png" /></th>
                </tr>
            </table>
        <?php } ?>


        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td bgcolor="#FFFFFF">
            	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
            	<tr>
            	<td align="center"><strong>DOCman <?php echo _DML_VERSION .' '._DM_VERSION;?></strong><br /><br />
           		</tr>
           		</table>
           	</td>
       	</tr>
    	</table>

        <table class="adminlist">
        <thead>
		<tr>
			<th>
			<?php echo _DML_APPLICATION?>
			</th>
			<th>
			<?php echo _DML_URL?>
			</th>
			<th>
			<?php echo _DML_VERSION?>
			</th>
			<th>
			<?php echo _DML_LICENSE?>
			</th>
		</tr>
        </thead>
        <tbody>
		<tr>
            <td>
            DOCman
            </td>
            <td>
            <a href="http://www.joomlatools.org" target="_blank">
            http://www.joomlatools.org
            </a>
            </td>
            <td>
            <?php echo _DM_VERSION;?>
            </td>
            <td>
            <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">
            GNU/GPL
            </a>
            </td>
        </tr>
        <tr>
			<td>
			Savant2
			</td>
			<td>
			<a href="http://www.phpsavant.com" target="_blank">
			http://www.phpsavant.com
			</a>
			</td>
			<td>
			2.3.2
			</td>
			<td>
			<a href="http://www.gnu.org/copyleft/lesser.html" target="_blank">
			LGPL
			</a>
			</td>
		</tr>
		<tr>
			<td>
			PEAR HTML Package
			</td>
			<td>
			<a href="http://pear.php.net" target="_blank">
			http://pear.php.net
			</a>
			</td>
			<td>
			1.1
			</td>
			<td>
			<a href="http://www.php.net/license/2_02.txt" target="_blank">
			PHP License
			</a>
			</td>
		</tr>
        <tr>
            <td>
            <?php echo _DML_ICONS?>
            </td>
            <td>
            <a href="http://www.iconaholic.com" target="_blank">
            http://www.iconaholic.com
            </a>
            </td>
            <td>
            n/a
            </td>
            <td>
            <?php echo _DML_ICONS_PERMISSION?> <a href="http://www.iconaholic.com" target="_blank">Iconaholic</a>
            </td>
        </tr>
        <tr>
            <td>
            <?php echo _DML_ICONS?>
            </td>
            <td><a href="http://www.kde-look.org/content/show.php?content=52900" target="_blank">Utopia</a></td>
            <td>1.0</td>
            <td><a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">
            GNU/GPL
            </a></td>
        </tr>
        </tbody>

		</table>
        <br />
        <table class="adminlist">
        <thead>
        <tr>
            <th>
            <?php echo _DML_CHANGELOG?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><pre><?php echo $changelog ?></pre></td>
        </tr>
        </tbody>
        </table>
        <?php include_once($mosConfig_absolute_path."/components/com_docman/footer.php");
    }
}