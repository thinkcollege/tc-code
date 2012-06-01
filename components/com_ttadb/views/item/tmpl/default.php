<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="program"><?php if (!empty($_SERVER['HTTP_REFERER'])) {
 		print '<p><a href="'.$_SERVER['HTTP_REFERER'].'"><strong>&lt;&lt; Return to search results. &lt;&lt;</strong></a></p>';
	} ?>
<?php $this->displayItem(reset($this->item)); 
include('fivestar.php'); ?>
<p>&nbsp;</p><?php if (!empty($_SERVER['HTTP_REFERER'])) {
 		print '<p><a href="'.$_SERVER['HTTP_REFERER'].'"><strong>&lt;&lt; Return to search results. &lt;&lt;</strong></a></p>';
	} ?>
</div>