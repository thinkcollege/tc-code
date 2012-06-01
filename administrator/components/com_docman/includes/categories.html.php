<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: categories.html.php 608 2008-02-18 13:31:26Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_HTML_CATEGORIES')) {
    return;
} else {
    define('_DOCMAN_HTML_CATEGORIES', 1);
}

class HTML_DMCategories
{
    function show(&$rows, $myid, &$pageNav, &$lists, $type)
    {
        global $my, $mosConfig_absolute_path;

        $section = "com_docman";
        $section_name = "DOCman";

        ?>
		<form action="index2.php" method="post" name="adminForm">

        <?php dmHTML::adminHeading( _DML_CATS, 'categories' )?>


		<table class="adminlist">
        <thead>
		<tr>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows);?>);" />
			</th>
			<th class="title"><?php echo _DML_CATNAME;?></th>
			<th width="10%"><?php echo _DML_PUBLISHED;?></th>
			<th colspan="2"><?php echo _DML_REORDER;?></th>
			<th width="10%"><?php echo _DML_ACCESS;?></th>
			<th width="12%"><?php echo _DML_CATEGORY;?> ID</th>
			<th width="12%"># <?php echo _DML_DOCS;?></th>
			<th width="12%"><?php echo _DML_CHECKED_OUT;?></th>
		  </tr>
        </thead>
        <tfoot><tr><td colspan="11"><?php echo $pageNav->getListFooter();?></td></tr></tfoot>
        <tbody>
		<?php
        $k = 0;
        $i = 0;
        $n = count($rows);
        foreach ($rows as $row) {
            $img = $row->published ? 'tick.png' : 'publish_x.png';
            $task = $row->published ? 'unpublish' : 'publish';
            $alt = $row->published ? 'Published' : 'Unpublished';
            if (!$row->access) {
                $color_access = 'style="color: green;"';
                $task_access = 'accessregistered';
            } else if ($row->access == 1) {
                $color_access = 'style="color: red;"';
                $task_access = 'accessspecial';
            } else {
                $color_access = 'style="color: black;"';
                $task_access = 'accesspublic';
            }

            ?>
			<tr class="<?php echo "row$k";?>">
				<td width="20" align="right">
				<?php echo $pageNav->rowNumber($i);?>
				</td>
				<td width="20">
				<?php echo mosHTML::idBox($i, $row->id, ($row->checked_out_contact_category && $row->checked_out_contact_category != $my->id));?>
				</td>
				<td width="35%">
				<?php
            if ($row->checked_out_contact_category && ($row->checked_out_contact_category != $my->id)) {

                ?>
					<?php echo $row->treename . ' ( ' . $row->title . ' )';?>
					&nbsp;[ <i><?php echo _DML_CHECKED_OUT?></i> ]
					<?php
            } else {

                ?>
					<a href="#edit" onClick="return listItemTask('cb<?php echo $i;?>','edit')">
					<?php echo $row->treename . ' ( ' . $row->title . ' )';?>
					</a>
					<?php
            }

            ?>
				</td>
				<td align="center">
				<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
				<img src="images/<?php echo $img;?>"  border="0" alt="<?php echo $alt;?>" />
				</a>
				</td>
				<?php
            if ($section <> 'content') {

                ?>
					<td>
					<?php echo $pageNav->orderUpIcon($i);?>
					</td>
					<td>
					<?php echo $pageNav->orderDownIcon($i, $n);?>
					</td>
					<?php
            }

            ?>
				<td align="center">
				<a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_access;?>')" <?php echo $color_access;?>>
				<?php echo $row->groupname;?>
				</a>
				</td>
				<td align="center">
				<?php echo $row->id;?>
				</td>
				<td align="center">
				<?php echo $row->documents;?>
				</td>
				<td align="center">
				<?php echo $row->checked_out_contact_category ? $row->editor : "";?>
				</td>
				<?php
            $k = 1 - $k;

            ?>
			</tr>
			<?php
            $k = 1 - $k;
            $i++;
        }

        ?>
        </tbody>
		</table>


		<input type="hidden" name="option" value="com_docman" />
		<input type="hidden" name="section" value="categories" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="chosen" value="" />
		<input type="hidden" name="act" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="type" value="<?php echo $type;?>" />
        <?php echo DOCMAN_token::render();?>
		</form>
		<?php include_once($mosConfig_absolute_path."/components/com_docman/footer.php");
    }

    /**
    * Writes the edit form for new and existing categories
    *
    * @param mosCategory $ The category object
    * @param string $
    * @param array $
    */
    function edit(&$row, $section, &$lists, $redirect)
    {
        global $mosConfig_live_site, $mosConfig_absolute_path;
        if ($row->image == "") {
            $row->image = 'blank.png';
        }
        mosMakeHtmlSafe($row, ENT_QUOTES, 'description');

        ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton, section) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			if ( form.name.value == "" ) {
				alert('<?php echo _DML_CAT_MUST_SELECT_NAME;?>');
			} else {
				<?php getEditorContents('editor1', 'description') ;?>
				submitform(pressbutton);
			}
		}
		</script>

		<form action="index2.php" method="post" name="adminForm">

        <?php
        $tmp = ($row->id ? _DML_EDIT : _DML_ADD).' '._DML_CAT.' '.$row->name;
		dmHTML::adminHeading( $tmp, 'categories' )
        ?>

		<table width="100%">
		<tr>
			<td valign="top">
				<table class="adminform">
				<tr>
					<th colspan="3">
					<?php echo _DML_CATDETAILS;?>
					</th>
				<tr>
				<tr>
					<td>
					<?php echo _DML_CATTITLE;?>:
					</td>
					<td colspan="2">
					<input class="text_area" type="text" name="title" value="<?php echo $row->title;?>" size="50" maxlength="50" title="A short name to appear in menus" />
					</td>
				</tr>
				<tr>
					<td>
					<?php echo _DML_CATNAME;?>:
					</td>
					<td colspan="2">
					<input class="text_area" type="text" name="name" value="<?php echo $row->name;?>" size="50" maxlength="255" title="<?php echo _DML_LONGNAME;?>" />
					</td>
				</tr>
				<tr>
					<td align="right"><?php echo _DML_PARENTITEM;?>:</td>
					<td>
					<?php echo $lists['parent'];?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo _DML_IMAGE;?>:
					</td>
					<td>
					<?php echo $lists['image'];?>
					</td>
					<td rowspan="4" width="50%">
					<script language="javascript" type="text/javascript">
					if (document.forms[0].image.options.value!=''){
					  jsimg='../images/stories/' + getSelectedValue( 'adminForm', 'image' );
					} else {
					  jsimg='../images/M_images/blank.png';
					}
					document.write('<img src=' + jsimg + ' name="imagelib" width="80" height="80" border="2" alt="<?php echo _DML_PREVIEW;?>" />');
					</script>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo _DML_IMAGEPOS;?>:
					</td>
					<td>
					<?php echo $lists['image_position'];?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo _DML_ORDERING;?>:
					</td>
					<td>
					<?php echo $lists['ordering'];?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo _DML_ACCESSLEVEL;?>:
					</td>
					<td>
					<?php echo $lists['access'];?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo _DML_PUBLISHED;?>:
					</td>
					<td>
					<?php echo $lists['published'];?>
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo _DML_DESCRIPTION;?>:
					</td>
					<td colspan="2">
					<?php
        // parameters : areaname, content, hidden field, width, height, rows, cols
        DOCMAN_Compat::editorArea('editor1', $row->description , 'description', '500', '200', '50', '5') ;
        ?>
					</td>
				</tr>
				</table>
			</td>
        </tr>
		</table>

		<input type="hidden" name="option" value="com_docman" />
		<input type="hidden" name="section" value="categories" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="oldtitle" value="<?php echo $row->title ;?>" />
		<input type="hidden" name="id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="sectionid" value="com_docman" />
		<input type="hidden" name="redirect" value="<?php echo $redirect;?>" />
        <?php echo DOCMAN_token::render();?>
		</form>
        <?php include_once($mosConfig_absolute_path."/components/com_docman/footer.php");
    }
}
