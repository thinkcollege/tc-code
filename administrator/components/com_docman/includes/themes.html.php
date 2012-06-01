<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: themes.html.php 608 2008-02-18 13:31:26Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_HTML_THEMES')) {
    return;
} else {
    define('_DOCMAN_HTML_THEMES', 1);
}

class HTML_DMThemes {
    function showThemes(&$rows, &$pageNav)
    {
        global $my, $mosConfig_live_site;
        if (isset($row->authorUrl) && $row->authorUrl != '') {
            $row->authorUrl = str_replace('http://', '', $row->authorUrl);
        }

        ?>
		<form action="index2.php" method="post" name="adminForm">

        <?php dmHTML::adminHeading( _DML_INSTALLED_THEMES, 'templates' )?>

		<table class="adminlist">
        <thead>
		<tr>
			<th align="left" width="5">#</th>
			<th align="left" width="5">&nbsp;</th>
			<th width="25%" class="dm_title">
			<?php echo _DML_NAME ?>
			</th>
			<th width="10%">
			<?php echo _DML_DEFAULT ?>
			</th>
			<th width="20%" align="left">
			<?php echo _DML_AUTHOR ?>
			</th>
			<th width="5%" align="center">
			<?php echo _DML_VERSION ?>
			</th>
			<th width="10%" align="center">
			<?php echo _DML_DATE ?>
			</th>
			<th width="20%" align="left">
			<?php echo _DML_AUTHOR_URL ?>
			</th>
		</tr>
        </thead>

        <tfoot><tr><td colspan="11"><?php echo $pageNav->getListFooter();?></td></tr></tfoot>

		<tbody>
        <?php
        $k = 0;
        for ($i = 0, $n = count($rows); $i < $n; $i++) {
            $row = &$rows[$i];

            ?>
			<tr class="<?php echo 'row' . $k;
            ?>">
				<td>
				<?php echo $pageNav->rowNumber($i);
            ?>
				</td>
				<td>
				<?php
            if ($row->checked_out && $row->checked_out != $my->id) {

                ?>
					&nbsp;
					<?php
            } else {

                ?>
					<input type="radio" id="cb<?php echo $i;
                ?>" name="cid[]" value="<?php echo $row->mosname;
                ?>" onClick="isChecked(this.checked);" />
					<?php
            }

            ?>
				</td>
				<td>
				<a href="#edit" onclick="return listItemTask('cb<?php echo $i;
            ?>','edit')">
				<?php echo $row->name;
            ?>
				</a>
				</td>
				<td align="center">
				<?php
            if ($row->published == 1) {

                ?>
				<img src="images/tick.png" alt="Published">
					<?php
            } else {

                ?>
					&nbsp;
					<?php
            }

            ?>
					</td>
				<td>
				<?php echo $row->authorEmail ? '<a href="mailto:' . $row->authorEmail . '">' . $row->author . '</a>' : $row->author;
            ?>
				</td>
				<td align="center">
				<?php echo $row->version;
            ?>
				</td>
				<td align="center">
				<?php echo $row->creationdate;
            ?>
				</td>
				<td>
				<a href="<?php echo substr($row->authorUrl, 0, 7) == 'http://' ? $row->authorUrl : 'http://' . $row->authorUrl;
            ?>" target="_blank">
				<?php echo $row->authorUrl;
            ?>
				</a>
				</td>
			</tr>
			<?php
        }

        ?>
        </tbody>
		</table>

		<input type="hidden" name="option" value="com_docman" />
		<input type="hidden" name="section" value="themes" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
        <?php echo DOCMAN_token::render();?>
		</form>
		<?php
        require_once ("../components/com_docman/footer.php");
    }

    function editTheme(&$row, &$lists, &$params)
    {
        global $mosConfig_live_site, $mosConfig_absolute_path;

        ?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script language="Javascript" src="<?php echo $mosConfig_live_site;
        ?>/includes/js/overlib_mini.js"></script>

		<style>
			.dm_title { background-color: #EEE; font-weight:  bold; border-bottom: 1px solid #BBB; }
			.adminform { margin: 5px; }
		</style>

		<?php dmHTML::adminHeading( _DML_EDIT_THEME.': '.$row->name, 'templates' )?>


		<form action="index2.php" method="post" name="adminForm">
		<table cellspacing="0" cellpadding="0" width="100%">
		<tr valign="top">
			<td width="40%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo _DML_THEME_DETAILS ?>
					</th>
				</tr>
				<tr>
					<td width="100" align="left">
					<?php echo _DML_NAME ?>:
					</td>
					<td>
					<input class="text_area" type="text" name="name" size="35" value="<?php echo $row->name;?>" />
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo _DML_PUBLISHED ?>:
					</td>
					<td>
					<?php echo $lists['published'];?>
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo _DML_DESCRIPTION ?>:
					</td>
					<td>
					<?php echo $row->description;?>
					</td>
				</tr>
				</table>
			</td>
			<td width="60%">
				<table class="adminform" >
				<tr>
					<th>
					<?php echo _DML_PARAMS ?>
					</th>
                </tr>
				<tr>
					<td width="100%">
					<?php echo $params->render();?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="com_docman" />
		<input type="hidden" name="section" value="themes" />
		<input type="hidden" name="id" value="<?php echo $row->mosname;?>" />
		<input type="hidden" name="task" value="" />
        <?php echo DOCMAN_token::render();?>
		</form>
		<?php include_once($mosConfig_absolute_path."/components/com_docman/footer.php");
    }

    function showInstallForm($title, $p_startdir = "", $backLink = "")
    {
        global $mosConfig_absolute_path;
        ?>
		<script language="javascript" type="text/javascript">
			function submitbuttonDir(pressbutton) {
				var form = document.adminForm_dir;

				// do field validation
				if (form.userfile.value == ""){
					alert( "<?php echo _DML_SELECT_DIRECTORY?>" );
				} else {
					form.submit();
				}
			}
            function submitbuttonPackage(pressbutton) {
                var form = document.filename;

                // do field validation
                if (form.userfile.value == ""){
                    alert( "<?php echo _DML_SELECT_PACKAGE?>" );
                    return false;
                } else {
                    form.submit();
                }
            }
		</script>
		<form enctype="multipart/form-data" action="index2.php" method="post" name="filename">
		<input type="hidden" name="task" value="uploadfile" />
		<input type="hidden" name="option" value="com_docman">
		<input type="hidden" name="section" value="themes">

        <?php dmHTML::adminHeading( _DML_INSTALL_THEME, 'templates' )?>

		<table class="adminform">
	  	<tr>
	    	<th><?php echo _DML_UPLOAD_PACKAGE_FILE?></th>
	  	</tr>
	  	<tr>
	    	<td align="Left"><?php echo _DML_PACKAGE_FILE?>:&nbsp;
                <input class="text_area" name="userfile" type="file" />&nbsp;
                <input class="button" type="button" value="<?php echo _DML_UPLOAD_AND_INSTALL?>" onclick="submitbuttonPackage()" />
            </td>
	  	</tr>
		</table>
        <?php echo DOCMAN_token::render();?>
		</form>
		<br />

		<form enctype="multipart/form-data" action="index2.php" method="post" name="adminForm_dir">
		<input type="hidden" name="task" value="installfromdir" />
		<input type="hidden" name="option" value="com_docman">
		<input type="hidden" name="section" value="themes">
		<table class="adminform">
	  	<tr>
	    	<th><?php echo _DML_INSTALL_FROM_DIRECTORY?></th>
	  	</tr>
	  	<tr>
	    	<td align="Left">
				<?php echo _DML_INSTALL_DIRECTORY?>:&nbsp;
				<input type="text" name="userfile" class="text_area" size="50" value="<?php echo $p_startdir;?>"/>&nbsp;
				<input type="button" class="button" value="<?php echo _DML_INSTALL?>" onclick="submitbuttonDir()" />
			</td>
	  	</tr>
		</table>
		</form>
		<?php include_once($mosConfig_absolute_path."/components/com_docman/footer.php");
    }

    function showInstallMessage($message, $title, $url)
    {
        global $PHP_SELF;

        ?>

        <?php dmHTML::adminHeading( $title, 'templates' )?>

        <form action="index2.php" method="post" name="adminForm">

		<table class="adminform">
		<tr>
			<td align="Left">
				<strong><?php echo $message;
        ?></strong>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				[&nbsp;<a href="<?php echo $url;
        ?>" style="font-size: 16px; font-weight: bold"><?php echo _DML_CONTINUE?>...</a>&nbsp;]
			</td>
		</tr>
		</table>
        <input type="hidden" name="option" value="com_docman" />
        <input type="hidden" name="section" value="themes" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        </form>

		<?php
        require_once("../components/com_docman/footer.php");
    }

    function themeInstalled() {
        dmHTML::adminHeading( _DML_THEME_INSTALLED, 'templates' )?>

        <form action="index2.php" method="post" name="adminForm">
		<table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminform">

			<tr>
				<td>
                <a href='index2.php?option=com_docman&task=config'>
                    <?php echo _DML_ADJUST_CONFIG;?>
                </a>
                </td>
			</tr>
		</table>
        <input type="hidden" name="option" value="com_docman" />
        <input type="hidden" name="section" value="themes" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        </form>
		<?php
        include "../components/com_docman/footer.php";
    }

    function editCSSSource($theme, &$content)
    {
        global $mosConfig_absolute_path, $_DOCMAN;

        ?>
		<form action="index2.php" method="post" name="adminForm">

        <?php dmHTML::adminHeading( _DML_STYLESHEET_EDITOR, 'templates' )?>


		<table class="adminform">
		<tr>
			<th colspan="4">
			<?php echo _DML_PATH?>: <?php echo $_DOCMAN->getPath('themes', $theme)?>/css/theme.css
			<?php
        $css_path = $file = $_DOCMAN->getPath('themes', $theme) . "/css/theme.css";
        echo is_writable($css_path) ? '<b><font color="green">
			 - '._DML_WRITABLE.'</font></b>' : '<b><font color="red"> - '._DML_UNWRITABLE.'</font></b>';

        ?>
			</th>
		</tr>
		<tr>
			<td>
			<textarea style="width:99%" rows="25" name="filecontent" class="inputbox"><?php
            echo $content;
            ?></textarea>
			</td>
		</tr>
		</table>
		<input type="hidden" name="theme" value="<?php echo $theme;?>" />
		<input type="hidden" name="option" value="com_docman" />
		<input type="hidden" name="section" value="themes" />
		<input type="hidden" name="task" value="" />
        <?php echo DOCMAN_token::render();?>
		</form>
		<?php include_once($mosConfig_absolute_path."/components/com_docman/footer.php");
    }
}
