<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="contentpane">
 <?php
	echo $this->lit->getDetails();
 	if (!empty($_SERVER['HTTP_REFERER'])) {
 		print '<p><a href="'.$_SERVER['HTTP_REFERER'].'">Return to search results.</a></p>';
	} ?>
</div>