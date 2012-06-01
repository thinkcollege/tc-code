<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$model = $this->getModel();
$contribs = $model->getContributors();
$pubTypes = $model->getPublicationTypes();
$topics = $model->getTopics();
$audiences = $model->getAudiences();
?>
<div class="contentpane"><h1>Searchable Literature Database</h1>
<p>This database consists of annotated listings of books, journal articles, and other publications related to the topic of postsecondary education and people with intellectual/developmental disabilities. In cases where the PDF is available, it is linked within the database. In other cases, we will let you know where you may obtain the resources you find.</p>
<p>The database will be updated on a quarterly basis as new publications become available. If you would like to suggest an entry into this database, contact Cate Weir at <a href="mailto:cathryn.weir@umb.edu?subject=Thinkcollege Literature Database - Feedback">cathryn.weir@umb.edu</a>.</p>
<?php
$messages = &JFactory::getApplication()->getMessageQueue();
$errors = array();
foreach ($messages as $message) {
	if ($message['type'] == 'error') {
		$errors[] = $message['message'];
	}
}
if (count($errors) > 0) {
	print '<ul class="error"><li>' . implode('</li><li>', $errors) . '</li></ul>';
}?>
<form action="./" method="get" enctype="application/x-www-form-urlencoded">
<p><strong><a href="./?option=com_literaturedatabase&view=search&task=search&all">Browse All Articles</a><br />or<br />Search:</strong></p>
<p><label for="contributor[]"><strong>Contributors:</strong></label><br />
 <select size="5" multiple="multiple" name="contributor[]" id="contributor">
  <option value="0">All Contributors</option>
  <?php
  	foreach ($contribs as $contrib) {
  		print "<option value=\"{$contrib['Value']}\">{$contrib['Option']}</opttion>";
  	} ?>
 </select>
</p>
<p><label for="type[]"><strong>Publication Types:</strong></label><br />
 <select size="5" multiple="multiple" name="type[]" id="type">
  <option value="0">All Publication Types</option>
  <?php
  	$pubType = reset($pubTypes);
  	do {
  		print '<option value="'.$pubType['Value'].'">'.$pubType['Option'].'</opttion>';
  	} while ($pubType = next($pubTypes));
  	?>
 </select>
</p>
<p><label for="subject[]"><strong>Subjects:</strong></label><br />
 <select size="5" multiple="multiple" name="subject[]" id="subject">
  <option value="0">All Topics</option>
  <?php
  	$topic = reset($topics);
  	do {
  		print '<option value="'.$topic['Value'].'">'.$topic['Option'].'</opttion>';
  	} while ($topic = next($topics));
  	?>
 </select>
</p>
<p><label for="audience[]"><strong>Audiences:</strong></label><br />
 <select size="5" multiple="multiple" name="audience[]" id="audience">
  <option value="0">All Audiences</option>
  <?php
  	$aud = reset($audiences);
  	do {
  		print '<option value="'.$aud['Value'].'">'.$aud['Option'].'</opttion>';
  	} while ($aud = next($audiences));
  	?>
 </select>
</p>
<p><label for="year"><strong>Year of Publication:</strong></label><br />
 <select name="year">
  <option value="-999">All Years</option>
  <option value="0">This Year</option>
  <option value="-1">Last Year</option>
  <option value="-3">Last 3 Years</option>
  <option value="-5">Last 5 Years</option>
  <option value="-10">Last 10 Years</option>
 </select>
</p>
<p><input type="submit" value="Search" /></p>
<input type="hidden" name="option" value="com_literaturedatabase" />
<input type="hidden" name="view" value="search" />
<input type="hidden" name="task" value="search" />
</form>
</div>
