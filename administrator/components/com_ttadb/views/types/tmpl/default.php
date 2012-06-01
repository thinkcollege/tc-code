<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="./" method="post" name="adminForm">
 <div id="editcell">
  <table class="adminlist">
   <thead>
    <tr>
     <th width="20">
	  <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->types); ?>);" />
	 </th>
	 <th>Type</th><th>Tools</th>
    </tr>            
   </thead>
   <tbody>
   <?php
  	$k = 0;
	foreach ($this->types as $key => $row) {
		$checked = JHTML::_('grid.id', $key, $row->id);
		$link = "./?option=" . strtolower(JRequest::getWord('option')) . '&task=';
		print "<tr class=\"row$k\"><td>$checked</td><td><a href=\"{$link}editType&cid[]={$row->id}\">{$row->label}</a></td>"
			. "<td><a href=\"{$link}listItems&typeId={$row->id}\">Add / Edit {$row->label}</a> - "
			. "<a href=\"{$link}importItems&typeId={$row->id}\">Import {$row->label}s</td></tr>";
		
		$k = 1 - $k;
	} ?>
   </tbody>
  </table>
 </div>
 <?php echo $this->pagination->getListFooter(); ?>
 <input type="hidden" name="option" value="<?php echo strtolower(JRequest::getWord('option')); ?>" />
 <input type="hidden" name="task" value="listTypes" />
 <input type="hidden" name="boxchecked" value="0" />
 <noscript><input type="hidden" name="nojs" value="1" /></input></noscript>
</form>
