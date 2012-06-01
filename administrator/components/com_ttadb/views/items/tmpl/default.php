<?php
defined('_JEXEC') or die('Restricted access');
$cols = $this->getHeaders($this->attrs);
if ($this->items instanceof stdclass) {
	$this->items = array($this->items);
}
?><form action="./" method="post" name="adminForm">
<!-- <pre>choices:<?php print $this->choices; ?></pre> -->
<?php if (!empty($cols)) { ?>
<div id="editcell">
 <table class="adminlist">
  <thead>
   <tr>
    <th width="20">
	 <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th><th>Published</th>
	<?php if (isset($this->items[0]->_title)) {?><th>Aggregate Title</th><?php } ?>
	<?php print $cols; ?>
   </tr>            
  </thead>
  <tbody>
  <?php
  	$k = 0;
$i =0;
	foreach ($this->items as $col => &$row) {
		$checked		= JHTML::_('grid.id', $col, $row->id);
	
		$published = JHTML::_('grid.published', $row, $i );
		print "<tr class=\"row$k\"><td>$checked</td><td>$published</td>";
			$i++;
		$this->displayRow($row, $this->attrs);
		print "</tr>";
		$k ^= 1;
	} ?>
  </tbody>
 </table>
</div>
<?php
	print $this->pagination->getListFooter();
} else {
	print '<p class="warning">There are no attributes set as <strong>summary</strong> attributes for this type.</p>';
}?>
<input type="hidden" name="option" value="<?php echo strtolower(JRequest::getWord('option')); ?>" />
<input type="hidden" name="task" value="listItems" />
<input type="hidden" name="typeId" value="<?php echo JRequest::getVar('typeId', 0, '', 'int'); ?>" />
<input type="hidden" name="boxchecked" value="0" />
 </form>
