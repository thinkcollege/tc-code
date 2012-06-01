<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="./" method="post" name="adminForm">
<div id="editcell">
 <table class="adminlist">
  <thead>
   <tr>
    <th width="20">
	 <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th><?php echo JHTML::_( 'grid.sort', 'Organization', 'org', $this->sortDirection, $this->sortColumn); ?></th>
    <?php
    	$item = isset($this->items[0]) ? $this->items[0] : array();
    	foreach ($item as $col => $val) {
			if (!in_array($col, array('id', 'org', 'org2'))) {
				echo "<th>" . JHTML::_( 'grid.sort', ucfirst($col), $col, $this->sortDirection, $this->sortColumn) . "</th>";
			}
		}
    ?>
   </tr>            
  </thead>
  <tbody>
  <?php
  	$k = 0;
	foreach ($this->items as $key => $row) {
		$checked = JHTML::_('grid.id', $key, $row->id);
		$link = JRoute::_(COM_URI . '&task=editProgram&cid[]=' . $row->id);
		print "<tr class=\"row$k\"><td>$checked";
		if (!$row->org && $row->org2) {
			$row->org = $row->org2;
		} else if (!$row->org) {
			$row->org = 'N/A';
		}
		unset($row->org2);
		switch ($row->published) {
			case 0:	 $row->published = 'New'; break;
			case 1:	 $row->published = 'Yes'; break;
			default: $row->published = 'No';  break;
		}
		
		foreach ($row as $col => $val) {
			if ($col == 'org') {
				print "<td><a href=\"$link\">$val</a></td>";
			} else if ($col != 'id') {
				print "<td>$val</td>";
			}
		}
		print "</tr>";
		$k = 1 - $k;
	} ?>
  </tbody>
 </table>
</div>
<?php echo $this->pagination->getListFooter(); ?>
<input type="hidden" name="option" value="com_programsdatabase" />
<input type="hidden" name="task" value="listPrograms" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
 </form>
