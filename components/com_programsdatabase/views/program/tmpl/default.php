<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="program">
<?php
$p = $this->program;
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
print "<h1>$title</h1><p>$subtitle</p>";

if ($p->q10 || $p->q11 || $p->q12 || $p->q13 || $this->a15 || $this->q53 || ($this->a41 && ($this->a39 || $this->a43))) {
	$p->q53 = !empty($p->q53) ? str_replace('http://', '', $p->q53) : null;
	print "<div class=\"contact\"><h2>Contact Program</h2>"
		. "<address>" . ($p->q10 ? "$p->q10" : '&nbsp;') . '<br />' . ($p->q11 ? "$p->q11<br />" : '')
		. ($p->q12 ? $p->q12 : '&nbsp;') . ($p->q12 && $p->q13 ? ', ' : '')
		. ($p->q13 ? $p->q13 : '&nbsp;') . " $p->q14</address>"
		. '<p>' . ($p->q15 ? "Phone: $p->q15" : '&nbsp;') . '<br />'
		. ($p->q53 ? "Website: <a href=\"http://$p->q53\" target=\"_blank\">$p->q53</a>" : '&nbsp;')
		. "</p><h3>Contact Person</h3><p>$p->q41" . ($p->q42 ? ", $p->q42" : '&nbsp;') . '<br />'
		. ($p->q43 ? "E-mail: <a href=\"mailto:$p->q43\">$p->q43</a>" : '&nbsp;')
		. "</p></div>";
}
if ($p->q45 || $p->q49) {
	print "<div class=\"contact\"><h2>Affiliated Public School</h2>"
		. '<address>'
		. ($p->q44 ? "{$p->q44}" : '&nbsp;') . '<br />'
		. ($p->q45 != '' && $p->q45 != $p->q44
			? nl2br(preg_replace("/^\n{2,}/", "\n", $p->q45)) : '&nbsp;')
		. '</address><p>'
		. ($p->q46 ? "Phone: {$p->q46}" : '&nbsp;') . '<br />'
		. ($p->q47 && parse_url($p->q47) ? "Website: <a href=\"{$p->q47}\" target=\"_blank\">{$p->q47}</a>" : '&nbsp;')
		. "</p><h3>Contact Person:</h3><p>{$p->q48}" . ($p->q49 ? ", {$p->q49}" : '') . '<br />'
		. ($p->q50 ? "E-mail: <a href=\"{$p->q50}\">{$p->q50}" : '&nbsp;')
		. "</a></div>";
}
print "<div class=\"clear\"></div>";
foreach ($this->questions as $q) { 
	if (!(!LIVE && JRequest::getVar('showAll')) && (empty($this->program->{'q' . $q->id}) || !$q->displayLabel)) {
		continue;
	}
	$id		= "q$q->id";
	$idCol	= "q{$q->id}id";
	$val	= $this->program->$id;
	if (!LIVE && JRequest::getVar('showAll') && !$q->displayLabel) {
		$q->displayLabel = 'q' . $q->id;
	}
	
	print "<p class=\"answer\"><strong>$q->displayLabel</strong><br />";
	switch (intval($q->typeId)) {
		case 1:
		case 2:	
		case 3: print "$val.</p>";															break;
		case 4: print "</p><ul><li>" . str_replace('||', '</li><li>', $val) . '</li></ul>'; break;
		case 6: print "</p><ul><li>" . str_replace('||', '</li><li>', $val) . '</li></ul>'; break;
	}
}
if (strpos($_SERVER['HTTP_REFERER'], 'thinkcollege.net') !== false) {
	print "<p class=\"answer\"><a href=\"{$_SERVER['HTTP_REFERER']}\">Return to search results.</a></p>";
} 
$user =& JFactory::getUser();

	
	if (($user->usertype == 'Super Administrator' ) || ($user->usertype == 'Administrator' )) {
	echo '<p><a href="/index.php?option=com_programsdatabase&task=addfront&cid[]='.$p->id . '">Edit this program</a></p>';
	} else {
	echo ""; } ?>
<p>&nbsp;</p>
</div>