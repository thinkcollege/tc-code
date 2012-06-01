<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="programsdatabase">
 <h1>Search Results</h1><a class="searchAgain" href="/databases/programs-database?task=searchform"><img src="/components/com_programsdatabase/views/search/tmpl/search_again.gif" alt="search again" /></a><br />
<?php
$state		= JRequest::getVar('state');
$twoYear	= JRequest::getVar('2year');
$fourYear	= JRequest::getVar('4year');
$techSchool = JRequest::getvar('techSchool');
$dual		= JRequest::getVar('dual');
$adult		= JRequest::getVar('adult');
$res		= JRequest::getVar('res');
$pager		= $this->pagination; 
$statename = ($state ? $this->getStateName($state) : '');
if (count($this->results) == 0) { 
	if ($state || $twoYear || $fourYear || $techSchool || $dual || $adult || $res) {
	print "<p>Your search for initiatives that:</p><ul>"
		. ($state		? "<li> are in <strong>$statename</strong>.</li>" : '')
		. ($twoYear 	? "<li>are <strong>Two year colleges</strong>.</li>" : '')
		. ($fourYear	? "<li>are <strong>Four year colleges or universities</strong>.</li>" : '')
		. ($techSchool	? "<li>are <strong>Technical/Trade Schools</strong>.</li>" : '')
		. ($dual		? "<li><strong>serve students who are still in high school</strong>.</li>" : '')
		. ($adult		? "<li><strong>serve students who have exited high school</strong>.</li>" : '')
		. ($res			? "<li><strong>provide residential options</strong>.</li>" : '') . "</ul>";
print '<h3>Produced no results.</h3><strong>Click on the links below to search for:</strong><ul>';
 
print ($state ? '<li><a href="/index.php?option=com_programsdatabase&task=search&state=' . $state . '">All programs in <strong>' . $statename . '</strong></a></li>' : '' )
. ($twoYear 	?  '<li><a href="/index.php?option=com_programsdatabase&task=search&2year=1">All <strong>two-year programs</strong></a></li>' : '' )
. ($fourYear	?  '<li><a href="/index.php?option=com_programsdatabase&task=search&4year=1">All <strong>four-year programs</strong></a></li>' : '')
	. ($techSchool	? '<li><a href="/index.php?option=com_programsdatabase&task=search&techSchool=1">All <strong>tech school programs</strong></a></li>' : '')
		. ($dual		? '<li><a href="/index.php?option=com_programsdatabase&task=search&dual=1">All <strong>programs that serve students who are still in high school</strong></a></li>' : '')
		. ($adult		? '<li><a href="/index.php?option=com_programsdatabase&task=search&adult=1">All programs that <strong>serve students who have exited high school</strong></a></li>' : '')
		. ($res			? '<li><a href="/index.php?option=com_programsdatabase&task=search&res=1">All programs that <strong>provide residential options</strong></a></li>' : '') . '</ul>';
	
print_r($this->result);
} 
} else {
if ($state || $twoYear || $fourYear || $techSchool || $dual || $adult || $res) {
	print "<p>Your search for initiatives that:</p><ul>"
		. ($state		? "<li>in <strong>$statename</strong>.</li>" : '')
		. ($twoYear 	? "<li>is a <strong>Two year college</strong>.</li>" : '')
		. ($fourYear	? "<li>is a <strong>Four year college or university</strong>.</li>" : '')
		. ($techSchool	? "<li>is a <strong>Technical/Trade School</strong>.</li>" : '')
		. ($dual		? "<li><strong>Serves students who are still in high school</strong>.</li>" : '')
		. ($adult		? "<li><strong>Serves students who have exited high school</strong>.</li>" : '')
		. ($res			? "<li><strong>Provides residential options</strong>.</li>" : '') . "</ul>";
} 
print "You are viewing results " . ($pager->limitstart + 1) . " through "
	. ($pager->limitstart + $pager->limit <= $pager->total ? $pager->limitstart + $pager->limit : $pager->total)
	. " of " . $pager->total . ".  Click the check boxes next to a program's name and then click the Compare button at the top"
	. " or bottom of the list. Up to 3 programs can be compared at a time.</p>";
print '<form action="' . JRoute::_('index.php?task=compare') . '" method="get" name="adminForm">'
	. '<p><input type="submit" value="Compare" /></p> <ul class="results">';

$class = '';
$aQuery = array('task' => 'viewProfile');
foreach ($this->results as $result) {
	if ($result->org) {
		$title		= $result->org;
		$subtitle	= $result->program;
	} else if ($result->org2) {
		$title		= $result->org2;
		$subtitle	= $result->program;
	} else {
		$title		= $result->program ? $result->program : 'N/A';
		$subtitle	= '';
	}
	if ($title == $subtitle) {
		$subtitle = '';
	}
	print "<li$class><input type=\"checkbox\" id=\"c$result->id\" name=\"cid[]\" value=\"$result->id\" />&thinsp;"
		. JHTML::link(JRoute::_('index.php?task=program&cid=' . $result->id), (!LIVE && JRequest::getVar('showAll') ? 'ID:' . $result->id . ' ' : '') . $title, array('class', 'org'))
		. "<span class=\"location\">" . ($result->city != '' ? $result->city . ', ' : '') . "$result->state</span>\n"
		. "<br /><span class=\"prog\">" . ($subtitle ? $subtitle : '&nbsp;') . '</span>'
		. "</li>";
	$class = !$class ? ' class="bg-gray"' : '';
}
print '</ul><p><input type="submit" value="Compare" /></p></form>';
?>
 <form action="<?php echo JRoute::_('index.php?task=search'); ?>" method="get" name="adminForm">
  <?php echo $pager->getListFooter(); ?>
</form> <?php } ?>
</div>