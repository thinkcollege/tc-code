<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="programsdatabase">
<h1>Compare Programs</h1>
<p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back to search results</a></p>
<table id="compare" border="1" cellpadding="0" cellspacing="0">
<thead>
	<tr>
	 <th>Question:</th>
	<?php
	foreach ($this->programs as $i => $p) {
		if ($p->q38) {
			$title		= $p->q38;
			$subtitle	= $p->q9;
		} else if ($p->q4) {
			$title		= $p->q4;
			$subtitle	= $p->q9;
		} else {
			$title		= $p->q9;
			$subtitle	= '';
		}
		if ($title == $subtitle) {
			$subtitle = '';
		}
		print "<th id=\"p$i\">" . str_replace('/', ' / ', $title) . "</th>";
	} ?>
	</tr>
</thead>
<tfoot>
	<tr>
	 <th>Question:</th>
	<?php
	foreach ($this->programs as $i => $p) {
		if ($p->q38) {
			$title		= $p->q38;
			$subtitle	= $p->q9;
		} else if ($p->q4) {
			$title		= $p->q4;
			$subtitle	= $p->q9;
		} else {
			$title		= $p->q9;
			$subtitle	= '';
		}
		if ($title == $subtitle) {
			$subtitle = '';
		}
		print "<th>" . str_replace('/', ' / ', $title) . "</th>";
	} ?>
	</tr>
</tfoot>
<tbody>
<?php
$class = '';
foreach ($this->questions as $q) {
	if (empty($q->compareLabel) || !$q->compare) {
		continue;
	}
	$id		= "q$q->id";
	
	print "<tr$class><th id=\"$id\">" . str_replace('/', ' / ', $q->compareLabel) . "</th>";
	foreach ($this->programs as $i => $p) {
		$val	= $p->$id;
		print "<td headers=\"$id p$i\">";
		switch (intval($q->typeId)) {
			case 1:
			case 2:	
			case 3: print $val;																		break;
			case 4:
				if ($val && strpos($val, '||') !== false) {
					print '<p class="checkall">&bull;&thinsp;' . str_replace('||', '</p><p class="checkall">&bull;&thinsp;', $val) . '</p>';
				} else if ($val) {
					print $val;
				} else {
					print '&nbsp;';
				}
			break;
		}
		print "</td>";
	}
	print "</tr>";
	$class = $class ? '' : ' class="alternateRow"';
} ?>
</tbody>
</table>
<p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back to search results</a></p>
</div>