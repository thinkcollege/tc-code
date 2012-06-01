<?php
/**
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

				 $option = JRequest::getCmd('option');
				$id = JRequest::getInt('id');
if ($option == 'com_content') {
    $view = JRequest::getCmd('view');
    switch ($view)
    {
        case 'article':
            $id = JRequest::getInt('id');
            $db = &JFactory::getDBO();
            $db->setQuery('SELECT `sectionid` FROM `#__content` WHERE `id` = '.$id);

            $sectionid = $db->loadResult();
            // do whatever you want with the section id
        break;

        case 'category':
            $id = JRequest::getInt('id');
            $db = &JFactory::getDBO();
            $db->setQuery('SELECT `section` FROM `#__categories` WHERE `id` = '.$id);

            $sectionid = $db->loadResult();
            // do whatever you want with the section id
        break;

        case 'section':
            $sectionid = JRequest::getInt('id');
            // do whatever you want with the section id
        break;
    }
}
 echo '<?xml version="1.0" encoding="utf-8"?'.'>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head><meta http-equiv="pragma" content="no-cache" />
	<meta name="viewport" content="user-scalable=no, width=device-width" />
	<jdoc:include type="head" />
	<?php $this->setGenerator('Institute for Community Inclusion'); 

$catid = JRequest::getCmd('catid');
$sitemap = JRequest::getCmd('sitemap');
	if (($sectionid == '9') || ($option == 'com_fireboard' && $catid > '2' && $catid !== '8' && $catid !== '7') || ($option == 'com_ctp') || (($option == 'com_xmap') && ($sitemap == '1'))): include('tc_live.php');  elseif ($sectionid == '10'): include('middle_school.php'); else: ?>
		<?php $print = JRequest::getCmd('print'); if ($print != 'on'): ?>
		<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/beez_lower/css/template.css" type="text/css" media="screen and (min-width: 481px)" />
	
<link rel="stylesheet" type="text/css" 
      href="<?php echo $this->baseurl; ?>/templates/beez_lower/css/layout.css" media="screen and (min-width: 481px)" /> 
	

	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/beez_lower/css/print.css" type="text/css" media="Print" />
	<!--[if IE]><link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/beez_lower/css/template.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" 
      href="<?php echo $this->baseurl;
?>/templates/beez_lower/css/layout.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/templates/beez_lower/css/ieonly.css" /><![endif]-->
	<!--[if IE 7]>
		<link href="<?php echo $this->baseurl;
?>/templates/<?php echo $this->template;?>/css/ie7only.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<style type="text/css" media="screen,projection"><?php // find a section id to determine page banner
  if (($option == 'com_content') && ($sectionid == '1')): ?>#header {
	
	background-image: url(/templates/beez_lower/images/tabline_s.gif);
}
#contentarea, #breadbar, #footer {
	background-color: #1d3f69;
}

#main {
	background-image: url(/templates/beez_lower/images/s_bot_lef.gif);
	background-position: bottom left;
	background-repeat: no-repeat;
}

#contain {
	background-image: url(/templates/beez_lower/images/s_up_lef.gif);
	background-position: 0 42px;
	background-repeat: no-repeat;
}


<?php endif; 
 if (($option == 'com_content') && ($sectionid == '2')): ?>#header {
	
	background-image: url(/templates/beez_lower/images/tabline_f.gif);
}
#contentarea, #breadbar, #footer {
	background-color: #7e1b19;
}

#main {
	background-image: url(/templates/beez_lower/images/f_bot_lef.gif);
	background-position: bottom left;
	background-repeat: no-repeat;
}

#contain {
	background-image: url(/templates/beez_lower/images/f_up_lef.gif);
	background-position: 0 42px;
	background-repeat: no-repeat;
}<?php endif; if (($option == 'com_content') && ($sectionid == '3')): ?>
 #header {
	
	background-image: url(/templates/beez_lower/images/tabline_p.gif);
}
#contentarea, #breadbar, #footer {
	background-color:  #dac488;
}
#main #breadbar, #main #breadbar a:link, #main #breadbar a:visited, .small, p.grey, #speechenabled a:link, #speechenabled a:visited  {color: #1d3f69;
}

#main {
	background-image: url(/templates/beez_lower/images/p_bot_lef.gif);
	background-position: bottom left;
	background-repeat: no-repeat;
}

#contain {
	background-image: url(/templates/beez_lower/images/p_up_lef.gif);
	background-position: 0 42px;
	background-repeat: no-repeat;
}
 

 <?php endif;  if ((($option == 'com_content') && (($sectionid == '5') || ($sectionid == '6'))) || $sectionid == '8'  || $sectionid == '9' || ($option == 'com_wrapper') || ($option == 'com_definition') || ($option == 'com_search') || ($option == 'com_fireboard') || ($option == 'com_idoblog') || ($option == 'com_programsdatabase') || ($option == 'com_literaturedatabase')  || ($option == 'com_jdownloads')|| ($option == 'com_user') || ($option == 'com_ttadb')  || ($option == 'com_xmap') ): ?>
 #header {
	
	background-image: url(/templates/beez_lower/images/tabline.gif);
}
#contentarea, #breadbar, #footer {
	background-color:  #65676a;
}

#main {
	background-image: url(/templates/beez_lower/images/bot_lef.gif);
	background-position: bottom left;
	background-repeat: no-repeat;
}

#contain {
	background-image: url(/templates/beez_lower/images/up_lef.gif);
	background-position: 0 42px;
	background-repeat: no-repeat;
}
 <?php endif; if ($Itemid == '46'): ?>
 

.moduletablePAC {
	right: 415px;
	top: 170px;
}

.PACtable {
height: 560px;
}<?php endif; ?>
</style><?php else: ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl;
?>/templates/beez_lower/css/print.css" type="text/css" media="screen, projection, print" /><?php endif; ?>
<!--[if !IE]>-->
 
<link rel="stylesheet" type="text/css" href="iphone.css" media="only screen and (max-width: 480px)" /><!--<![endif]-->

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>	
<?php  if (($id == '66') || ($id == '73') || ($id == '69')): ?>
	
<script type="text/javascript" src="animatedcollapse.js">

/***********************************************
* Animated Collapsible DIV v2.2- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>


<script type="text/javascript">
animatedcollapse.addDiv('keyOneA', 'fade=1,speed=400,group=keyOne,hide=1')
animatedcollapse.addDiv('keyOneB', 'fade=1,speed=400,group=keyOne,hide=1')
animatedcollapse.addDiv('keyOneC', 'fade=1,speed=400,group=keyOne,hide=1')
animatedcollapse.addDiv('keyOneD', 'fade=1,speed=400,group=keyOne,hide=1')
animatedcollapse.addDiv('keyOneE', 'fade=0,speed=400,group=keyOne,persist=0,hide=1')
animatedcollapse.addDiv('keyTwoA', 'fade=1,speed=400,group=keyTwo,hide=1')
animatedcollapse.addDiv('keyTwoB', 'fade=0,speed=400,group=keyTwo,persist=0,hide=1')
animatedcollapse.addDiv('keyThreeA', 'fade=1,speed=400,group=keyThree,hide=1')
animatedcollapse.addDiv('keyThreeB', 'fade=1,speed=400,group=keyThree,hide=1')
animatedcollapse.addDiv('keyThreeC', 'fade=1,speed=400,group=keyThree,hide=1')
animatedcollapse.addDiv('keyThreeD', 'fade=1,speed=400,group=keyThree,hide=1')
animatedcollapse.addDiv('keyThreeE', 'fade=1,speed=400,group=keyThree,hide=1')
animatedcollapse.addDiv('keyThreeF', 'fade=1,speed=400,group=keyThree,hide=1')
animatedcollapse.addDiv('keyThreeG', 'fade=1,speed=400,group=keyThree,hide=1')
animatedcollapse.addDiv('keyThreeH', 'fade=0,speed=400,group=keyThree,persist=0,hide=1')
animatedcollapse.addDiv('keyFourA', 'fade=1,speed=400,group=keyFour,hide=1')
animatedcollapse.addDiv('keyFourB', 'fade=1,speed=400,group=keyFour,hide=1')
animatedcollapse.addDiv('keyFourC', 'fade=0,speed=400,group=keyFour,persist=0,hide=1')
animatedcollapse.addDiv('keyFiveA', 'fade=1,speed=400,group=keyFive,hide=1')
animatedcollapse.addDiv('keyFiveB', 'fade=0,speed=400,group=keyFive,persist=0,hide=1')
animatedcollapse.addDiv('pcpTable', 'fade=0,speed=40,group=pcp,persist=0,hide=1')

animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}

animatedcollapse.init()

</script><?php endif; if ($id == '66'): ?><style type="text/css">

#left {
	width: 40px;
	text-align: left;
}

#main {
	width: 938px;
}

#contain {
	width: 938px;
}</style><?php endif; ?>
<script src="html5media/html5media.min.js"></script>
  <script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript" src="iphone.js"></script>
<?php if ($id == '196'): ?>
<script src="html5media/html5media.min.js"></script>
<?php endif; ?>
<script type="text/javascript"> /*
   var _gaq = _gaq || [];
   _gaq.push( 
      ['_setAccount', 'UA-962830-9'],
      ['_trackDownload'],
      ['_trackPageview']);
(function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
   })(); /*
</script></head>
<body>
	<div id="green">	<h2 class="unseen"><?php echo JText::_('<a href="#content">Skip to content</a> or <a href="#tabs">Navigation</a>'); ?>
			</h2>

		<div id="header">
			<h1 id="logo"><a class="logolink" href="index.php"><?php if ($sectionid =='8'): ?><img src="<?php echo $this->baseurl;
?>/templates/beez_lower/images/newsletter_left.jpg" border="0" alt="<?php echo JText::_('Think College'); ?>" width="162" height="142" /><?php else: ?><img src="<?php echo $this->baseurl;
?>/templates/beez_lower/images/lower_logo.gif" border="0" alt="<?php echo JText::_('Think College'); ?>" width="199" height="142" /><?php endif; ?></a></h1>
<div id="searchForm"><jdoc:include type="modules" name="user7" /></div>
			<?php if ($sectionid != '8'): ?><div id="tabs">
				<a href="index.php?option=com_content&amp;view=article&amp;id=3&amp;Itemid=40"><img src="templates/beez_lower/images/tab_s.gif" width="229" height="42" border="0" alt="Students" /></a>
				<a href="index.php?option=com_content&amp;view=article&amp;id=12&amp;Itemid=41"><img src="templates/beez_lower/images/tab_f.gif" width="231" height="42" border="0" alt="Families" /></a>
				<a href="index.php?option=com_content&amp;view=article&amp;id=18&amp;Itemid=42"><img src="templates/beez_lower/images/tab_p.gif" width="228" height="42" border="0" alt="Professionals" /></a>
			</div><?php else: ?><div id="newsletterHed"><img src="<?php echo $this->baseurl; ?>/templates/beez_lower/images/newsletter_middle.gif" border="0" alt="<?php echo JText::_('Think College E-News'); ?>" width="363" height="75" /></div><?php endif; ?><div id="altTabs"></div>

		
			

			<jdoc:include type="modules" name="user3" />
			

			

			
		</div><!-- end header -->

		<div id="contentarea">
			



<div id="left"><jdoc:include type="modules" name="left" style="beezDivision" headerLevel="3" /><jdoc:include type="modules" name="syndicate" /><?php if ($sectionid == '8'): ?><ul class="archiveslink"><li><a href="/publications/newsletters">Newsletter Archives</a></li></ul><?php endif; ?>
<?php if (($option == 'com_literaturedatabase') || ($option == 'com_programsdatabase') || ($option == 'com_ttadb') || ($Itemid == '28') || ($Itemid == '127') || ($Itemid == '117') || ($Itemid == '247') || ($Itemid == '245')):
					?><jdoc:include type="modules" name="database2" style="beezDivision" headerLevel="3" /><?PHP
				endif; ?><br />&nbsp;</div>

			
			
			<div id="main"><div id="contain"><div id="breadbar"><jdoc:include type="modules" name="user6" /></div><form class="printBut" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post"><?php if ($print != 'on'): ?><button class="printBut" type="submit">Print view <img src="/images/M_images/printButton.png" alt="Print view" /></button><input type="hidden" name="print" value="on" /><?php else: ?><button class="printBut" type="submit" />Normal view <img src="/images/M_images/printButton.png" alt="Normal view" /></button><input type="hidden" name="print" value="off" /><?php endif; ?></form><a name="content"></a>
				<jdoc:include type="modules" name="user9" style="beezDivision" headerLevel="3" />
				<!-- This needs to be here so that this statement will work in components. <jdoc:include type="message" /> -->
				<jdoc:include type="component" /><jdoc:include type="modules" name="user5" style="beezDivision" headerLevel="3" />
			<jdoc:include type="modules" name="user1" style="beezDivision" headerLevel="3" /></div></div><!-- end main or main2 -->

		

			<div class="wrap"></div>
			<!-- wrapper -->
		</div><!-- contentarea -->

		<div id="footer">
<div id="facebook"><a href="http://www.facebook.com/pages/Boston-MA/Think-College/60511340871" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/beez_lower/images/find_us_on_facebook_badge.gif" alt="Find us on Facebook" /></a><a style="margin-left: 4px" href="http://twitter.com/thinkcollegeICI" target="_blank"><img src="<?php echo $this->baseurl; ?>/templates/beez_lower/images/follow_twitter.png" alt="Follow Us On Twitter" /></a><div id="speechenabled"></div></div>
			<p><div class="small"> 
<p class="grey">&copy;<?php echo date("Y"); ?>. Think College is a project of the Institute for Community Inclusion at the University of Massachusetts Boston. The Think College initiatives are funded by grants from the National Institute on Disability and Rehabilitation Research, the Administration on Developmental Disabilities, the Office of Special Education Programs, and the Office of Postsecondary Education. The contents of this website do not necessarily reflect an official position of the sponsoring agencies.
</p><jdoc:include type="modules" name="user8" style="beezDivision" headerLevel="3" />
</div><!-- footer -->
	</div></div><? endif; ?>
<script src='http://www.google-analytics.com/ga.js' type='text/javascript'></script>
<script type='text/javascript'>
var pageTracker = _gat._getTracker("UA-962830-9");
pageTracker._trackPageview();
</script>
<script src='/gaAddons.js' type='text/javascript'></script></body>
</html>