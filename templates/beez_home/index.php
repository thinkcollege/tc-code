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


 echo '<?xml version="1.0" encoding="utf-8"?'.'>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head><meta name="viewport" content="user-scalable=no, width=device-width" />
	<jdoc:include type="head" /><?php $this->setGenerator('Institute for Community Inclusion'); ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez_home/css/template.css" type="text/css" media="screen and (min-width: 481px)" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez_home/css/layout.css" type="text/css" media="screen and (min-width: 481px)" />
	<link rel="stylesheet" type="text/css" href="iphone_home.css" media="only screen and (max-width: 480px)" />
	
	<!--[if IE]><link rel="stylesheet" href="/templates/beez_home/css/ieonly.css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez_home/css/template.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez_home/css/layout.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/templates/beez_home/css/ieonly.css" /><![endif]-->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript" src="iphone_home.js"></script>
	

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-962830-9']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>	
</head>
<body>
	<div id="all"><div id="green"><h2 class="unseen">
				<?php echo JText::_('<a href="#content">Skip to content</a> or <a href="#hometab">Navigation</a>'); ?>
			</h2>

		<div id="header" class="ie_layout">
			<div id="logo"><h1 id="logoH"><a href="#" class="logolink"><img src="<?php echo $this->baseurl ?>/templates/beez_home/images/logo_home.gif" border="0" alt="<?php echo JText::_('Think College'); ?>" width="254" height="178" /></a></h1></div>
<jdoc:include type="modules" name="user3" /><div id="searchForm"><jdoc:include type="modules" name="user7" /></div>
			<div id="altTabs"></div><div id="hometab"><a href="index.php?option=com_content&view=article&id=3&Itemid=3"><img src="templates/beez_home/images/hometab_s.gif" width="218" height="70" border="0" alt="Students" /></a><a href="index.php?option=com_content&view=article&id=12&Itemid=4"><img src="templates/beez_home/images/hometab_f.gif" width="220" height="70" border="0" alt="Families" /></a><a href="index.php?option=com_content&view=article&id=18&Itemid=6"><img src="templates/beez_home/images/hometab_p.gif" width="218" height="70" border="0" alt="Professionals" /></a></div>

			
			

			
			

			

			
		</div><div id="picstripAlt"></div><div id="picstrip"><img src="templates/beez_home/images/picstrip1.jpg" width="239" height="91" alt="student at computer" /><img src="templates/beez_home/images/picstrip2.jpg" width="239" height="91" alt="two students working in library" /><img src="templates/beez_home/images/picstrip3.jpg" width="239" height="91" alt="students on campus" /><img src="templates/beez_home/images/picstrip4.jpg" width="237" height="91" alt="young woman with stack of books" /></div><!-- end header -->

		<div id="contentarea">
			



<div id="left"><jdoc:include type="modules" name="user4" /></div>


			
			
			<div id="main"><a name="content"></a>
				
				<jdoc:include type="component" /> 
			<jdoc:include type="modules" name="user1" style="beezDivision" headerLevel="3" /></div><!-- end main or main2 -->

			
			<div class="wrap"></div>
			<!-- wrapper -->
		</div><!-- contentarea -->

		<div id="footer">
		<div id="facebook"><a href="http://www.facebook.com/pages/Boston-MA/Think-College/60511340871" target="_blank"><img src="<?php echo $this->baseurl ?>/templates/beez_lower/images/find_us_on_facebook_badge.gif" alt="Find us on Facebook" /></a><a style="margin-left: 4px" href="http://twitter.com/thinkcollegeICI" target="_blank"><img src="<?php echo $this->baseurl ?>/templates/beez_lower/images/follow_twitter.png" alt="Follow Us On Twitter" /></a><div id="speechenabled"></div></div>

			<p><div class="small"> 
<p class="grey">&copy;<?php echo date("Y"); ?>. Think College is a project of the Institute for Community Inclusion at the University of Massachusetts Boston. The Think College initiatives are funded by grants from the National Institute on Disability and Rehabilitation Research, the Administration on Developmental Disabilities, the Office of Special Education Programs and the Office of Postsecondary Education. The contents of this website do not necessarily reflect an official position of the sponsoring agencies.
</p>	<jdoc:include type="modules" name="user8" style="beezDivision" headerLevel="3" />
			
		</div><!-- footer -->
	</div></div></div><!-- all -->	
<!-- Quantcast Tag -->
<script type="text/javascript">
var _qevents = _qevents || [];

(function() {
var elem = document.createElement('script');
elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
elem.async = true;
elem.type = "text/javascript";
var scpt = document.getElementsByTagName('script')[0];
scpt.parentNode.insertBefore(elem, scpt);
})();

_qevents.push({
qacct:"p-34ZvgyLpHlTrY"
});
</script>

<noscript>
<div style="display:none;">
<img src="//pixel.quantserve.com/pixel/p-34ZvgyLpHlTrY.gif" border="0" height="1" width="1" alt="Quantcast"/>
</div>
</noscript>
<!-- End Quantcast tag -->
</body>
</html>