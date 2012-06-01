<?php $showLeftColumn = $this->countModules('left1'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/beez_lower/css/tc_live.css" type="text/css" media="screen" /> 
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/beez_lower/css/tc_live_layout.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/beez_lower/css/tc_live_print.css" type="text/css" media="print" /> 
<?php if ($id == '163'): ?><script type="text/javascript" src="templates/beez_lower/javascript/tc_live_rollover.js">
</script><?php elseif ($id == '164'): ?><script type="text/javascript" src="templates/beez_lower/javascript/tc_live_mod1.js">
</script><?php elseif ($id == '170'): ?><script type="text/javascript" src="templates/beez_lower/javascript/tc_live_mod2.js">
</script>
<?php elseif ($id == '232'): ?><script type="text/javascript" src="templates/beez_lower/javascript/tc_live_mod3.js">
</script>
<?php elseif ($id == '298'): ?><script type="text/javascript" src="templates/beez_lower/javascript/tc_live_mod4.js">
</script><?php endif; 
if (($Itemid == '284') || ($id == '300')): ?><script type="text/javascript" src="templates/beez_lower/javascript/tc_live_mod3_switchvideo.js"></script><?php endif; ?>
<script type="text/javascript" src="html5media/html5media.min.js"></script>
<script type="text/javascript" src="http://use.typekit.com/nss2kgl.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<!--[if IE]>
	<style type="text/css" media="screen,projection">
ul#menulist_roottcLive li:hover ul {
	margin-top: 21px;
	margin-left: -65px;

}
h1, .xmap .componentheading, h2, h3, h4, ul#menulist_roottcLive li a:link, ul#menulist_roottcLive li a:visited {

	font-family: verdana,sans-serif;

}


#page {
	overflow: hidden;
}
</style>
<![endif]-->
	<!--[if IE 6]>
	<style type="text/css" media="screen,projection">
	#main {
	width: 710px;
	</style>
	<![endif]-->
	<!--[if gte IE 8]>--><link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/beez_lower/css/ie8only.css" type="text/css" media="screen" /><!--<![endif]-->
<script type="text/javascript">
   var _gaq = _gaq || [];
   _gaq.push( 
      ['_setAccount', 'UA-962830-9'],
      ['_trackDownload'], // This is where gaAddons calls go
      ['_trackOutbound'], // Showing three basic calls
      ['_trackPageview']
      );
(function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
   })();
</script>
<script type="text/javascript" src="/gaAddons-2.1.1.min.js"></script>
</head>
<body>
<strong></strong>	<div id="green">
		<h2 class="unseen"><?php echo JText::_('<a href="#content">Skip to content</a> or <a href="#menulist_roottcLive">Navigation</a>'); ?></h2>
		<div id="header" class="clearfix"><div id="headerBg" class="clearfix"><?php if  ($id != 163): ?><div id="smLogo"><img src="/templates/beez_lower/images/tc_live/small_logo.gif" width="69" height="70" alt="Think College Live" /></div><?php endif; ?><jdoc:include type="modules" name="user11" /><br /><div id="breadbar"><jdoc:include type="modules" name="tcbreadcrumbs" /></div>
			
		
			
			
		</div></div><!-- end header -->
		<div id="contentarea" class="clearfix">
			<div id="<?php echo $showLeftColumn ? 'left' : 'leftNarrow'; ?>">
<jdoc:include type="modules" name="left1" style="beezDivision" headerLevel="3" />
				<br />&nbsp;
			</div>
			<div id="<?php echo $showLeftColumn ? 'main' : 'mainWide'; ?>">
				<div id="contain">
				
				
				<a name="content"></a>
				<jdoc:include type="modules" name="user9" style="beezDivision" headerLevel="3" />
				<!-- This needs to be here so that this statement will work in components. <jdoc:include type="message" />-->
			<?php if ($option == 'com_fireboard'): ?><div id="exchangeImg"><img align="right"  src="/templates/beez_lower/images/tc_live/icon_exchange_sm.gif" alt="Think College Exchange" /></div><?php endif; ?>
					<jdoc:include type="component" />
			
				<jdoc:include type="modules" name="user1" style="beezDivision" headerLevel="3" />
			</div>
		</div>
		<!-- end main or mainWide -->
		<div class="wrap"></div>
		<!-- wrapper -->
	</div>
	<!-- contentarea -->
	<div id="footer"><div id="footerBg">
		<p><a href="/index.php">Think College Home</a></p>
			<span align="center"><a style="display: block; font-size: 95%; padding: 10px 0" href="mailto:help@thinkcollege.net">Contact webmaster: help@thinkcollege.net</a></span>	
			</div></div>
			<!-- footer -->
		</div>
	</div>