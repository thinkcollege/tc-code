	<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
 <table class="adminlist">
  <thead>
   <tr>
    <th width="20">
	 <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
    <?php
    	$item = isset($this->items[0]) ? $this->items[0] : array();
    	foreach ($item as $col => $val) {
			if ($col != 'id') {
				print "<th>$col</th>";
			}
		}
    ?>
   </tr>            
  </thead>
  <?php
  	$k = 0;
	foreach ($this->items as $key => $row) {
		$checked = JHTML::_('grid.id', $key, $row->id);
		$link = JRoute::_(COM_URI . 'controller=program&task=edit&cid[]=' . $row->id);
		print "<tr class=\"row$k\"><td>$checked";
		foreach ($row as $col => $val) {
			if ($col == 'Organization') {
				print "<td><a href=\"$link\">$val</a></td>";
			} else if ($col != 'id') {
				print "<td>$val</td>";
			}
		}
		print "</tr>";
		$k = 1 - $k;
	} ?>
 </table>
</div>
 
<input type="hidden" name="option" value="com_programsdatabase" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="program" />
 
</form>

