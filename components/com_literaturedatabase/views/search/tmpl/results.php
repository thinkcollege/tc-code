<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class="contentpane">
<h1>Search Results</h1>
<p><?php echo $this->summary; ?></p>
<pre><?php /*$a = get_class_methods(JFactory::getDBO()); sort($a); print_r($a);*/ ?></pre>
<ol>
 <?php 
 	foreach ($this->results as $result) {
 		print '<li>' . Literature::getLiterature($result['LitID'])->getSynopsis() . '</li>';
 	}?>
</ol>
<p>
 <a href="/?option=com_literaturedatabase&view=search">New Search</a>
 <?php echo (isset($this->prev) ? ' | <a href="' . $this->prev . '">Previous 25 Results</a>' : '')
 		  . (isset($this->next) ? ' | <a href="' . $this->next . '">Next 25 Results</a>' : '');?>
 </p>
</div>