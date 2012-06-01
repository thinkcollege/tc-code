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
    	$cols = array('inputLabel' => 'Text Shown in Survey', 'displayLabel'  => 'Text Shown in Profile', 'grouping' => 'Grouping', 'Type' => 'Type');
    	print "<th>" . implode('</th><th>', $cols) . "</th>";
    ?>
   </tr>            
  </thead>
  <?php
  	$k = 0;
	foreach ($this->items as $i => $row) {
		$checked = JHTML::_('grid.id', $i, $row->id);
		$link = JRoute::_(COM_URI . '&task=editQuestion&cid[]='. $row->id);
		print "<tr class=\"row$k\"><td>$checked</td>";
		foreach ($cols as $key => $col) {
			if ($key == 'inputLabel' && trim($row->$key) == '') {
				$row->$key = '(none)';
			}
			#$row->$key = htmlentities($row->$key, ENT_COMPAT, 'UTF-8');
			if ($key == 'inputLabel') {
				
				print "<td><a href=\"$link\">{$row->$key}</a></td>";
			} else {
				print "<td>{$row->$key}</td>";
			}
		}
		print "</tr>";
		$k = 1 - $k;
	} ?>
 </table>
</div>
<?php echo $this->pagination->getListFooter(); ?>
<input type="hidden" name="option" value="com_programsdatabase" />
<input type="hidden" name="task" value="listQuestions" />
<input type="hidden" name="boxchecked" value="0" />
</form>

