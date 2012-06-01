<?php // no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::script('openvideo.js','components/com_programsdatabase/views/programsdatabase/tmpl/');

function generateCheckbox($var, $label) {
	return "<input type=\"checkbox\" id=\"$var\" name=\"$var\" value=\"1\" /> <label for=\"$var\">$label</label>";
} ?>
<div class="programsdatabase">
 <h1>Think College Program Database</h1>

 <p class="blueText">Find and compare information about college programs for students with intellectual disabilities! Click “Start Your Search” to customize your options, or "View All Programs" to view the whole database.</p>



 <div id="buttonContain" class="clearfix"><div class="startCon" align="center"><a href="/databases/programs-database?task=searchform"><img  src="/components/com_programsdatabase/views/programsdatabase/tmpl/start_search.gif" alt="Start your search" /></a><p class="startP"><a href="/databases/programs-database?task=searchform"><img class="startBut" src="/components/com_programsdatabase/views/programsdatabase/tmpl/start_but.gif" alt="Start your search" /></a> <a href="/databases/programs-database?task=searchform">START YOUR SEARCH</a></p></div>
	 <div class="browseAllcon"><div class="browseAll"><a href="/index.php?option=com_programsdatabase&task=search"><?php echo $this->countPrograms; ?></a></div><p class="startP"><a href="/index.php?option=com_programsdatabase&task=search"> <a href="/index.php?option=com_programsdatabase&task=search">VIEW ALL PROGRAMS</a></p></div></div><jdoc:include type="message" />
	<p>This information was submitted to Think College by the college programs. Think College does not necessarily endorse a program simply because it is listed in the database. There also may be programs available that are not included here. If you know of a program that is not currently listed here or you would like to make changes to a program that is listed, please email <a href="mailto:thinkcollege@umb.edu">thinkcollege@umb.edu</a>.</p>
<div id="videoInclude" align="center"><p><a href="javascript:ajaxpage(rootdomain+'/components/com_programsdatabase/views/programsdatabase/tmpl/video_include.html', 'videoInclude');"><img src="/components/com_programsdatabase/views/programsdatabase/tmpl/programvideo.gif" alt="click to view a video with instructions about this database" /></a> <a href="javascript:ajaxpage(rootdomain+'/components/com_programsdatabase/views/programsdatabase/tmpl/video_include.html', 'videoInclude');">WATCH A BRIEF VIDEO ON HOW TO USE THE DATABASE.</a></p></div></div>
