<?php
/**
 * Language file
 * @author GranholmCMS
 * @link http://www.granholmcms.com
 */

defined('_JEXEC') or die('Restricted access');

class HTML_definition {

  function showDefinitionEntries( $option, &$rows, &$search, &$pageNav, &$clist ) {

    $entrylength   = "40";

    # Table header
    ?>
    <form action="index2.php" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td width="100%" class="sectionname">
	    <img src="components/com_definition/images/logo.png" valign="top">&nbsp;Definition
      </td>
      <td nowrap="nowrap">Display #</td>
			<td>
				<?php echo $pageNav->getLimitBox(); ?>
			</td>
			<td>Search:</td>
			<td>
				<input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
			</td>
			<td width="right">
				<?php echo $clist;?>
			</td>
    </tr>
    </table>
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
        <th width="2%" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
        <th class="title" align=left><div align="left">Term</div></th>
        <th class="title" align=left><div align="left">Category</div></th>
        <th class="title"><div align="center">Published</div></th>
      </tr>
      <?php
    $k = 0;
    for ($i=0, $n=count( $rows ); $i < $n; $i++) {
      $row = &$rows[$i];
      echo "<tr class='row$k'>";
      echo "<td width='5%'><input type='checkbox' id='cb$i' name='cid[]' value='$row->id' onclick='isChecked(this.checked);' /></td>";
      echo "<td align='left'><a href=\"index2.php?option=".$option."&task=edit&cid[]=".$row->id."\">$row->tterm</a></td>";
      echo "<td align='left'>$row->category</td>";

      $task = $row->published ? 'unpublish' : 'publish';
      $img = $row->published ? 'publish_g.png' : 'publish_x.png';
      ?>
        <td width="10%" align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>

    </tr>
    <?php    $k = 1 - $k; } ?>
    <tr>
      <th align="center" colspan="7">
        <?php echo $pageNav->getPagesLinks(); ?></th>
    </tr>
    <tr>
      <td align="center" colspan="7">
        <?php echo $pageNav->getPagesCounter(); ?></td>
    </tr>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="limitstart" value="<?php echo $pageNav->limitstart; ?>" />
  <input type="hidden" name="boxchecked" value="0" />
  </form>
<?php
}

function editDefinition( $option, &$row, &$publist, &$clist ) {
  require(JPATH_SITE."/administrator/components/com_definition/config.definition.php");
  $editor =& JFactory::getEditor();
?>
    <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
      var form = document.adminForm;
      if (pressbutton == 'cancel') {
        submitform( pressbutton );
        return;
      }
      // do field validation
      if (form.tterm.value == ""){
        alert( "Entry must have a term." );
      } else if (form.catid.value == "0"){
	alert( "You must select a category." );
      } else {
        submitform( pressbutton );
      }
    }
    </script>

    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
    <form action="index2.php" method="post" name="adminForm" id="adminForm">
      <tr>
	    <td width="100%" class="sectionname">
	      <img src="components/com_definition/images/logo.png" valign="top">&nbsp;Definition
        </td>
	  </tr>
	  <tr>
        <th colspan="2" class="title" >
          <?php echo $row->id ? 'Edit' : 'Add';?> Definition Entry
        </th>
      </tr>
      <tr>
        <td valign="top" align="right">Term:</td>
        <td>
          <input class="inputbox" type="text" name="tterm" value="<?php echo $row->tterm; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Definition:</td>
        <td width="420" valign="top"><?php echo $editor->display( 'tdefinition',  $row->tdefinition, '500', '200', '70', '30' ) ; ?></td>
      </tr>

     <tr>
				<td valign="top" align="right">Category:</td>
				<td>
					<?php echo $clist; ?>
				</td>
			</tr>

      <tr>
        <td width="20%" align="right">Name:</td>
        <td width="80%">
          <input class="inputbox" type="text" name="tname" size="50" maxlength="100" value="<?php echo htmlspecialchars( $row->tname, ENT_QUOTES );?>" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">E-Mail:</td>
        <td>
          <input class="inputbox" type="text" name="tmail" value="<?php echo $row->tmail; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Homepage:</td>
        <td>
          <input class="inputbox" type="text" name="tpage" value="<?php echo $row->tpage; ?>" size="50" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right">Comment:</td>
        <td>
          <textarea class="inputbox" cols="50" rows="3" name="tcomment" style="width=500px" width="500"><?php echo htmlspecialchars( $row->tcomment, ENT_QUOTES );?></textarea>
        </td>
      </tr>

      <tr>
        <td valign="top" align="right">Published:</td>
        <td>
          <?php echo $publist; ?>
        </td>
      </tr>

    </table>

    <input type="hidden" name="tdate" value="<?php echo date("Y-m-d H:i:s", time()); ?>" />
    <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
    <input type="hidden" name="option" value="<?php echo $option;?>" />
    <input type="hidden" name="task" value="" />
    </form>
<?php
  }

# End of class
}
?>
