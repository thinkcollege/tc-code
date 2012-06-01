<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="programsdatabase">
 <h1>Search Results</h1>

<?php 	$refine = JRequest::getVar('refine');
			
			if ($refine == 'popular') { echo '<h2>Highest rated items</h2>'; }
		elseif ($refine == 'recent') { echo '<h2>Recently updated items</h2>'; }
		else { echo '';}
$pager = $this->pager;
print $this->getSearchTerms() . "You are viewing results " . ($pager->limitstart + 1) . " through "
	. ($pager->limitstart + $pager->limit <= $pager->total ? $pager->limitstart + $pager->limit : $pager->total)
	. " of " . $pager->total . ". " . JHTML::link(JRoute::_('index.php'), ' <strong>&lt;&lt; Return to Search Page &lt;&lt;</strong>');
print '<ul class="results">';

$class = '';
$aQuery = array('task' => 'viewProfile');
foreach ($this->results as $item) {
	print "<li$class>";
	$this->displayItem($item, TtaDbView::SUMMARY);
	$class = !$class ? ' class="bg-gray"' : '';
	print "</li>";
}
print '</ul>';
?>
 <form action="<?php echo JRoute::_('&task=search&typeid=' . JRequest::getInt('typeId')); ?>" method="get" name="adminForm">
  <?php echo $pager->getListFooter(); ?>
</form>
</div>