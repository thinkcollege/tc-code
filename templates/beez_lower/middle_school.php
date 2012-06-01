<?php 
$showLeftColumn = $this->countModules('left'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/beez_lower/css/middle_school.css" type="text/css" media="screen" /> <!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/beez_lower/css/ieonly.css" type="text/css" media="screen" /><![endif]-->
<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/beez_lower/css/ie6only.css" type="text/css" media="screen" /><!<![endif]-->


<style media="screen" type="text/css">
#middleMain {<?php
switch ($Itemid) {
	case 298:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/home_bg.gif);';
	$bottomUp = '<div class="bottomUp"><strong>Think College Island:</strong> Welcome to Think College Island. This island was created so that students of all abilities could start thinking about and planning for college in middle school. We hope that you explore the many things each island has to offer. Remember, if you want to get back home, click the icon with the house on the top of any page. <img src="templates/beez_lower/images/middle_school/home_small.png" alt="Home page" /> And if you have any questions, you can click the orange question mark on the top right of any page. <img src="templates/beez_lower/images/middle_school/faqs_small.png" alt="Frequently asked questions" /> Have a great journey!</div>';
	break;
	case 299:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/about_me_bg.jpg);';
		$bottomUp = '<div class="bottomUp"><strong>My About Me Island:</strong> Welcome to My About Me Island. Here you download worksheets that you can fill out and you can travel over to the My Disability Island, My Health Island and My Skills & Interests Island. All of these islands will help you become more aware of who you are and how you can speak up for yourself both inside and outside of school.</div>';	
	break;
	case 300:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/study_island_bg.jpg);';
$bottomUp = '<div class="bottomUp"><strong>My Study Island:</strong> Welcome to My Study Island. On this island you can learn about ways to be more organized, what your learning type is (visual, auditory or hands-on), listen to podcasts and look at coursework that will help you get into college someday.</div>';
	break;
	case 301:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/my_social_life_bg.jpg);';
	$bottomUp = '<div class="bottomUp"><strong>My Social Life Island:</strong> Welcome to My Social Life Island. This island will teach you about bullying and online safety, help you understand your feelings, assist you in developing social skills, and explain ways to communicate with your family.</div>';
	break;
	case 302:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/career_island_bg.jpg);';
	$bottomUp = '<div class="bottomUp"><strong>My Career Island:</strong> Welcome to My Career Island. This island will help you match your skills and interests to different kinds of careers, show you the timeline to get to college, teach you about planning for college and even bring you on some virtual college tours.</div>';
	$audiojs = 'middle_school_career.js';
	break;
	case 303:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/skills_interests_bg.jpg);';
	$bottomUp = '<div class="bottomUp"><strong>My Skills and Interest Island:</strong> Welcome to My Skills & Interest Island. This island will help you with your schoolwork such as math, writing and spelling. You will also find ways to better organize yourself and fill out worksheets on your interests and hobbies that you can use to share with people.</div>';
	break;
	case 304:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/disability_bg.jpg);';
	$bottomUp = '<div class="bottomUp"><strong>My Disability Island:</strong> Welcome to My Disability Island. On this island you will learn about types of disabilities, your health, what an individualized education plan is and how you can participate in your IEP.  You can view a voice thread created of students talking about themselves.</div>';
	break;
	case 305:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/resources_bg.jpg);';
	break;
	case 306:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/treasure_bg.jpg);';
	$bottomUp = '<div class="bottomUp"><strong>My Treasure Island:</strong> Welcome to My Treasure Island. This island is meant for you to access worksheets and files that you can show your teachers, parents, friends and families. You will also be able to create an online portfolio that shows people exactly who you are and what you’re good at. Check back soon for the ePortfolio program!</div>';
	break;
	case 307:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/multimedia_bg.jpg);';
	$bottomUp = '<div class="bottomUp"><strong>My Multimedia:</strong>  Welcome to My Multimedia. Here you can listen to podcasts, watch videos, view presentations and access links to many helpful websites.</div>';
	break;
	case 308:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/multimedia_bg.jpg);';
	$bottomUp = '';
	break;
	default:
	echo '';
	$bottomUp = '';
	break;
	case 386:
	echo '
	background-image: url(/templates/beez_lower/images/middle_school/multimedia_bg.jpg);';
	$bottomUp = '<div class="bottomUp"><strong>My Video Island</strong>  Welcome to My Video Island. Here you can watch videos that will help you explore college and career options.</div>';
	break;
}
?>
	background-repeat: no-repeat;
	background-position: top left;}
	@-moz-document url-prefix() {
  #containAll {
    overflow: hidden;
  }
}

<?php  $session =& JFactory::getSession();
//returned object $session, is a class JSession:: methods get, set, getInstance etc
$popupon = JRequest::getVar('popupon'); 
if($popupon == 'no') $session->set('popups', 'no');
if($popupon == 'yes') $session->set('popups', 'yes');
if($session->get('popups') == 'no') echo '
.msIcon:hover .infoBubbl, #footerList li:hover .bottomUp, #headerList li:hover .topDrop {
	display: none;
}';
 ?>
</style>
<!--[if gte IE 8]><link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/beez_lower/css/ie8only.css" type="text/css" media="screen" /><![endif]-->
<script type="text/javascript" src="templates/beez_lower/javascript/jquery-1.5.2.min.js"></script>
<!--[if !IE]>-->

<script type="text/javascript">
/* function touchStart( e ) {
 var block = document.getElementById("soundBlock")
block.innerHTML=
 "<audio controls autoplay=\"autoplay\" src=\"/templates/beez_lower/images/middle_school/sounds/HP-MyAboutMe.mp3\" /></audio>";
 e.preventDefault();
 return false;
} */

jQuery.noConflict();
	jQuery(document).ready(function(){	
	
  jQuery("#containAll").fadeTo( 600, 1);
jQuery("#myHealth img.closer").click(function(){
  jQuery("#myHealth").toggleClass("clickList");
});
jQuery("#myHealthtwo img.closer").click(function(){
  jQuery("#myHealthtwo").toggleClass("clickList");
});
jQuery("#myOnline img.closer").click(function(){
  jQuery("#myOnline").toggleClass("clickList");
});
jQuery("#mediaPresentations img.closer").click(function(){
  jQuery("#mediaPresentations").toggleClass("clickList");
});
jQuery("#myLocker img.closer").click(function(){
  jQuery("#myLocker").toggleClass("clickList");
});
jQuery("#myWorksheets img.closer").click(function(){
  jQuery("#myWorksheets").toggleClass("clickList");
});
jQuery("#myInterestsandh img.closer").click(function(){
  jQuery("#myInterestsandh").toggleClass("clickList");
});
jQuery("#myIepmaterials img.closer").click(function(){
  jQuery("#myIepmaterials").toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev1 .closer").click(function(){
  jQuery('#resourcesList li.firstLev1').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev2 .closer").click(function(){
  jQuery('#resourcesList li.firstLev2').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev3 .closer").click(function(){
  jQuery('#resourcesList li.firstLev3').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev4 .closer").click(function(){
  jQuery('#resourcesList li.firstLev4').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev5 .closer").click(function(){
  jQuery('#resourcesList li.firstLev5').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev6 .closer").click(function(){
  jQuery('#resourcesList li.firstLev6').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev7 .closer").click(function(){
  jQuery('#resourcesList li.firstLev7').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev8 .closer").click(function(){
  jQuery('#resourcesList li.firstLev8').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev9 .closer").click(function(){
  jQuery('#resourcesList li.firstLev9').toggleClass("clickList");
});
jQuery("#lockerList li.firstLev1 .closer").click(function(){
  jQuery('#lockerList li.firstLev1').toggleClass("clickList");
});
jQuery("#lockerList li.firstLev2 .closer").click(function(){
  jQuery('#lockerList li.firstLev2').toggleClass("clickList");
});
jQuery("#lockerList li.firstLev3 .closer").click(function(){
  jQuery('#lockerList li.firstLev3').toggleClass("clickList");
});
jQuery("#lockerList li.firstLev4 .closer").click(function(){
  jQuery('#lockerList li.firstLev4').toggleClass("clickList");
});
jQuery("#lockerList li.firstLev5 .closer").click(function(){
  jQuery('#lockerList li.firstLev5').toggleClass("clickList");
});
jQuery(".expAnimate").click(function(){
  jQuery(this).addClass("clickTrans");
jQuery("#containAll").fadeTo(600, 0);
var href = jQuery(this +".clickTrans a").attr('href');

             // Delay setting the location
        setTimeout(function() {window.location = href}, 900);
        return false;

});



});
</script><!--<![endif]-->
<!--[if IE]>

<script type="text/javascript">jQuery.noConflict();
	jQuery(document).ready(function(){
		
   jQuery("#containAll").fadeTo( 600, 1);
jQuery("#myHealth img.closer").click(function(){
  jQuery("#myHealth").toggleClass("clickList");
});
jQuery("#myHealthtwo img.closer").click(function(){
  jQuery("#myHealthtwo").toggleClass("clickList");
});
jQuery("#myOnline img.closer").click(function(){
  jQuery("#myOnline").toggleClass("clickList");
});
jQuery("#mediaPresentations img.closer").click(function(){
  jQuery("#mediaPresentations").toggleClass("clickList");
});
jQuery("#myLocker img.closer").click(function(){
  jQuery("#myLocker").toggleClass("clickList");
});
jQuery("#myWorksheets img.closer").click(function(){
  jQuery("#myWorksheets").toggleClass("clickList");
});
jQuery("#myInterestsandh img.closer").click(function(){
  jQuery("#myInterestsandh").toggleClass("clickList");
});
jQuery("#myIepmaterials img.closer").click(function(){
  jQuery("#myIepmaterials").toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev1 .closer").click(function(){
  jQuery('#resourcesList li.firstLev1').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev2 .closer").click(function(){
  jQuery('#resourcesList li.firstLev2').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev3 .closer").click(function(){
  jQuery('#resourcesList li.firstLev3').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev4 .closer").click(function(){
  jQuery('#resourcesList li.firstLev4').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev5 .closer").click(function(){
  jQuery('#resourcesList li.firstLev5').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev6 .closer").click(function(){
  jQuery('#resourcesList li.firstLev6').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev7 .closer").click(function(){
  jQuery('#resourcesList li.firstLev7').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev8 .closer").click(function(){
  jQuery('#resourcesList li.firstLev8').toggleClass("clickList");
});
jQuery("#resourcesList li.firstLev9 .closer").click(function(){
  jQuery('#resourcesList li.firstLev9').toggleClass("clickList");
});
jQuery("#lockerList li.firstLev1 .closer").click(function(){
  jQuery('#lockerList li.firstLev1').toggleClass("clickList");
});
jQuery("#lockerList li.firstLev2 .closer").click(function(){
  jQuery('#lockerList li.firstLev2').toggleClass("clickList");
});
jQuery("#lockerList li.firstLev3 .closer").click(function(){
  jQuery('#lockerList li.firstLev3').toggleClass("clickList");
});
jQuery("#lockerList li.firstLev4 .closer").click(function(){
  jQuery('#lockerList li.firstLev4').toggleClass("clickList");
});
jQuery("#lockerList li.firstLev5 .closer").click(function(){
  jQuery('#lockerList li.firstLev5').toggleClass("clickList");
});
jQuery(".expAnimate").click(function(){
  jQuery(this).addClass("clickTrans");
jQuery("#containAll").fadeTo(600, 0);

});
});
</script><![endif]-->
<!--[if lte IE 6]><script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/beez_lower/javascript/ie6warn.js"></script><!<![endif]-->

<script type="text/javascript" src="/templates/beez_lower/javascript/middle_school.js"></script>
<script type="text/javascript">
/* window.onload = function() {
	var counter=0;
document.getElementById("preloadAudio").innerHTML = ""; 
//Let's print out the elements of the array.
for (counter=0; counter < soundfile.length; counter++)
{
	document.getElementById("preloadAudio").innerHTML +=   
"<embed src=\"" + soundfile[counter] + "\" hidden=\"true\" autostart=\"false\" />";
}
} */
</script>
<script type="text/javascript" src="http://use.typekit.com/nss2kgl.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</head>
<body><script type="text/javascript">
<!--//--><![CDATA[//><!--
document.write( '<div id="containAll" class="clearfix">' );
//--><!]]>
</script>
<div id="green" class="clearfix">
		<h2 class="unseen"><?php echo JText::_('<a href="#content">Skip to content</a> or <a href="#tabs">Navigation</a>'); ?></h2>
		<div id="header"><br />
			
		
		</div><!-- end header -->
		<div id="contentarea" class="clearfix">
			
			<div id="middleMain">
			
				
				
				<a name="content"></a>
				<jdoc:include type="modules" name="user9" style="beezDivision" headerLevel="3" />
				<!-- This needs to be here so that this statement will work in components. <jdoc:include type="message" />-->
			<ul id="headerList">
<?php echo $Itemid != 298 ? '<li class="expAnimate homeTop"><a href="think-college-island"><img src="templates/beez_lower/images/middle_school/home.png" alt="Home page" /></a>' : '<li class="homeTop"><img src="templates/beez_lower/images/middle_school/home.png" alt="Home page" />' ; ?><ul class="topDrop"><li class="expAnimate"><a href="/think-college-island/about-me"><img src="/templates/beez_lower/images/middle_school/about_me_top.png" alt="My About Me Island" /></a></li><!-- <li class="expAnimate"><a href="/think-college-island/study-island"><img src="/templates/beez_lower/images/middle_school/study_island_top.png" alt="My Study Island" /></a></li> -->
<li class="expAnimate"><a href="/think-college-island/treasure-island"><img src="/templates/beez_lower/images/middle_school/treasure_top.png" alt="My Treasure Island" /></a></li>
<li class="expAnimate"><a href="/think-college-island/career-island"><img src="/templates/beez_lower/images/middle_school/career_island_top.png" alt="My Career Island" /></a></li><li class="expAnimate"><a href="/think-college-island/social-life-island"><img src="/templates/beez_lower/images/middle_school/social_life_top.png" alt="My Social Life Island" /></a></li><li class="expAnimate"><a href="/think-college-island/disability-island"><img src="/templates/beez_lower/images/middle_school/my_disability_top.png" alt="My Disability Island" /></a></li><li class="expAnimate"><a href="/think-college-island/skills-interests-island"><img src="/templates/beez_lower/images/middle_school/my_interests_top.png" alt="My Skills and Interests Island" /></a></li></ul><li class="moreLinkstop"><a href="http://www.diigo.com/list/lcooney98/UMB-Middle-
School-project---Parents-and-Educators" target="_blank"><img src="templates/beez_lower/images/middle_school/navigator.png" alt="Family and Teacher Resources" /></a><ul class="topDrop"><li>Family &amp; Teacher Resources</li></ul></li><li class="expAnimate resourcesTop"><a href="/think-college-island/resources-island"><img src="templates/beez_lower/images/middle_school/resources.png" alt="Resources" /></a><ul class="topDrop"><li>Site Resources</li></ul></li><li class="faqTop"><img style="display: block;" src="templates/beez_lower/images/middle_school/faqs.png" alt="FAQs" /><ul class="topDrop"><li>Frequently Asked
Questions coming soon, please
email <a href="mailto:lori.cooney@umb.edu">lori.cooney@umb.edu</a> with
any questions you may have.</li></ul></li>
</ul>	<?php if($Itemid == '298') { if(!isset($_COOKIE['visited'])) {  
 echo '<div id="previewVideo"><a href="templates/beez_lower/hometutorial.html" onclick="javascript:void window.open(\'templates/beez_lower/hometutorial.html\',\'1316808537054\',\'width=580,height=325,toolbar=0,menubar=0,location=0,status=1,scrollbars=0,resizable=1,left=0,top=0\');return false;"><img src="/templates/beez_lower/images/middle_school/preview.png" alt="Take a video tour of the island." /></a></div>';

setcookie('visited',100, 1924923600, '/', $_SERVER['HTTP_HOST'], false, true); }} ?><jdoc:include type="component" />
			
				<jdoc:include type="modules" name="user1" style="beezDivision" headerLevel="3" />
	
		</div>
		
		<div class="wrap"></div>
		<!-- wrapper -->
	</div>
	<!-- contentarea -->
		<div id="footer"><ul id="footerList"><li id="homeFooter"<?php echo ($Itemid == 298) ? ' style="padding-right: 70px"' : ' style="padding-right: 150px"'; ?> onmouseover="playSound(0)" onmouseout="stopSound();" onclick="stopSound();"><div class="bottomUp">Go to the main Think College website for more information for students, families, and professionals.</div><a href="/index.php"><img src="templates/beez_lower/images/middle_school/thinkcollege_logo.png" alt="Think College Home" /></a></li><li class="expAnimate" onmouseover="playSound(1)" onmouseout="stopSound();"onclick="stopSound();"><div class="bottomUp">Listen to podcasts, watch videos, view presentations and go to the website list.</div><a href="/think-college-island/multimedia-island"><img src="templates/beez_lower/images/middle_school/my_multimedia.png" alt="My Multimedia" /></a></li><!--<li class="expAnimate"><a href="/disability"><img src="templates/beez_lower/images/middle_school/my_disability.png" alt="My Disability" /></a></li> --><li onmouseover="playSound(2)" onmouseout="stopSound();" onclick="stopSound();"><div class="bottomUp">Follow Think College on Facebook.</div><a href="http://www.facebook.com/thinkcollege" target="_blank"><img src="templates/beez_lower/images/middle_school/facebook.png" alt="Think College Facebook page" /></a></li><li onmouseover="playSound(3)" onmouseout="stopSound();" onclick="stopSound();"><div class="bottomUp">Follow Think College on Twitter.</div><a href="https://twitter.com/#!/thinkcollegeICI" target="_blank"><img src="templates/beez_lower/images/middle_school/twittericon.png" alt="Think College Twitter page" /></a></li><li class="siteHelp" onmouseover="playSound(4)" onmouseout="stopSound();" onclick="stopSound();"><?php echo $bottomUp; ?><a href="#"><img src="templates/beez_lower/images/middle_school/my_site_help.png" alt="My site help" /></a></li><?php if ($Itemid == 298): ?><li><div class="bottomUp">Watch a brief video explaining how this website works.</div><a href="templates/beez_lower/hometutorial.html" onclick="javascript:void window.open('templates/beez_lower/hometutorial.html','1316808537054','width=580,height=325,toolbar=0,menubar=0,location=0,status=1,scrollbars=0,resizable=1,left=0,top=0');return false;"><img src="templates/beez_lower/images/middle_school/my_vid_tutor.png" alt="My Video Tutorial" /></a></li><?php endif; ?><li onmouseover="playSound(5)" onmouseout="stopSound();" onclick="stopSound();"><?php 
 ?><form id="popupSwitch" action="" method="post"><?php echo $session->get('popups') == 'no' ? '<input type="hidden" name="popupon" value="yes" /><input class="submiton" type="submit" value="Turn Popups On" />' : '<div class="bottomUp">If you find the popup messages on this site too distracting, turn them off.</div><input type="hidden" name="popupon" value="no" /><input class="submitoff" type="submit" value="Turn Popups Off" />'; ?></form></li></ul></div>
			<!-- footer --><div id="copyText"><span id="soundBlock"></span><span id="preloadAudio"></span>
			<p>&copy;<?php echo date("Y"); ?>. Think College is a project of the Institute for Community Inclusion at the University of Massachusetts Boston. The Think College Middle School initiative is funded by a grant from the Administration on Developmental Disabilities, grant number 93-632.
Island background images courtesy of <a href="http://www.toondoo.com" target="_blank">www.toondoo.com.</a></p></div>
		</div>
	</div><script type="text/javascript">
<!--//--><![CDATA[//><!--
document.write( '</div>' );
//--><!]]>
</script>