<?php
/**
 * JShare - an Advanced Social Bookmarking Module for Joomla 1.5
 *
 * @package    mod_jshare
 * @subpackage Modules
 * @link       http://www.joomify.com
 * @license    LGPL
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<div id="jbmwrap" style="padding:<?php echo $padding;?>px;" align="<?php echo $align;?>">
<style type="text/css">img.jbm{ padding-bottom: 1px; }</style>
<?php 

$style = (1==$style)?'_trans':'';

if ('1' == $ani) { ?>
<script type="text/javascript">
 
  window.addEvent('domready', function(){            
            var list = $$('#jbmwrap .jbm');            
            list.each(function(e) {
                var fx = new Fx.Style(e , 'opacity').start(1,0.5);
				var fx = new Fx.Styles(e, {duration:200, wait:false});				
                 e.addEvent('mouseenter', function(){
				 	    fx.start({
				 	    	'opacity': 1
				    	});
				 });
				 e.addEvent('mouseleave', function(){
				    	fx.start({
					       	'opacity': 0.5
					    });
				 });

		  });
  });
</script>



<?php }

    switch ( $jbookmarks ) {  case 1: ?>




 <?php break; case 2: break; }


              switch ( $facebook ) {  case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.facebook.com/" onclick="

                window.open('http://www.facebook.com/share.php?u='+encodeURIComponent(location.href)+'&amp;t='+encodeURIComponent(document.title));
                
                return false;"

                 title="<?php echo $alt;?> Facebook"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/facebook".$style.".gif";?>" alt="<?php echo $alt;?> Facebook" name="facebook" border="0" id="facebook"/></a>

<?php break; case 2: break; }




        switch ( $wong ) {  case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.mister-wong<?php echo ($wongl == '') ? '.com' : $wongl; ?>/"  onclick="

                window.open('http://www.mister-wong<?php echo ($wongl == "") ? ".com" : $wongl; ?>/index.php?action=addurl&amp;bm_url='+encodeURIComponent(location.href)+'&amp;bm_notice=<?php echo $description;?>&amp;bm_description='+encodeURIComponent(document.title)+'&amp;bm_tags=<?php echo $tags_space;?>');
                
                return false;"


                 title="<?php echo $alt;?> Mr. Wong"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/wong".$style.".gif";?>" alt="<?php echo $alt;?> Mr. Wong" name="wong" border="0" id="wong"/></a>

<?php break; case 2: break; } 

switch ( $webnews ) { case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.webnews.de/" onclick="
                
                
                window.open('http://www.webnews.de/einstellen?url='+encodeURIComponent(document.location)+'&amp;title='+encodeURIComponent(document.title)+'&amp;desc=<?php echo $description;?>');
                
                return false;


                " title="<?php echo $alt;?> Webnews"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/webnews".$style.".gif";?>" alt="<?php echo $alt;?> Webnews" name="Webnews" border="0" id="Webnews"/></a>

<?php break; case 2: break; } 



switch ( $buzka ) { case 1: ?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.buzka.com" onclick="
                
             window.open('http://buzka.com/' + location.href);
             return false;

                
                " title="<?php echo $alt;?> Buzka"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/buzka".$style.".gif";?>" alt="<?php echo $alt;?> Buzka" name="Buzka" border="0" id="Buzka"/></a>
<?php break; case 2: break; }


switch ( $live ) {	case 1:	?>
		<a rel="nofollow" style="text-decoration:none;" href="https://favorites.live.com/" onclick="

                window.open('https://favorites.live.com/quickadd.aspx?url='+encodeURIComponent(document.location.href));
                
                return false;

                " title="<?php echo $alt;?> Windows Live"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/live".$style.".png";?>" alt="<?php echo $alt;?> Windows Live" name="WindowsLive" border="0" id="WindowsLive"/></a>

<?php break; case 2: break; }



switch ( $iciode ) { case 1: ?>
		<a rel="nofollow" style="text-decoration:none;" href="http://www.icio.de" onclick="

             window.open('http://www.icio.de/add.php?url='+encodeURIComponent(location.href));
             return false;
                
                
                " title="<?php echo $alt;?> Icio"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/icio".$style.".gif";?>" alt="<?php echo $alt;?> Icio" name="Icio" border="0" id="Icio"/></a>
<?php break; case 2: break; }

    switch ( $ximmy ) {  case 1: ?>

<a rel="nofollow" style="text-decoration:none;" href="http://partners.ximmy.com/idevaffiliate.php?id=183&amp;keyword=Jbookmarks&amp;custom=16" onclick="

window.open('http://partners.ximmy.com/idevaffiliate.php?id=183&amp;keyword=Jbookmarks&amp;custom=16&amp;url='+encodeURIComponent(location.href));
return false;"

title="<?php echo $alt;?> Ximmy"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/ximmy".$style.".gif";?>" alt="<?php echo $alt;?> Ximmy" name="ximmy" border="0" id="ximmy" /></a>


 <?php break; case 2: break; }


switch ( $oneview) { case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.oneview.de/"  onclick="

                window.open('http://www.oneview.de/quickadd/neu/addBookmark.jsf?URL='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));

                return false;
                
                
                " title="<?php echo $alt;?> Oneview"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/oneview".$style.".gif";?>" alt="<?php echo $alt;?> Oneview" name="Oneview" border="0" id="Oneview"/></a>

<?php break; case 2: break; }



switch ( $kledy ) { case 1: ?>

<a rel="nofollow" style="text-decoration:none;" href="http://www.kledy.de" onclick="


window.open('http://www.kledy.de/submit.php?url='+(document.location.href));

return false;


" title="<?php echo $alt;?> Kledy.de Social Bookmarking"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/kledy".$style.".gif";?>" alt="<?php echo $alt;?> Kledy.de Social Bookmarking" name="Kledy" border="0" id="Kledy"/></a>

<?php break; case 2: break; }


switch ( $favit ) {

	case 1:

		?>

<a rel="nofollow" style="text-decoration:none;" href="http://www.favit.de" onclick="

window.open('http://www.favit.de/submit.php?url='+(document.location.href));

return false;


" title="<?php echo $alt;?> FAV!T Social Bookmarking"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/favit".$style.".gif";?>" alt="<?php echo $alt;?>  FAV!T Social Bookmarking" name="Favit" border="0" id="Favit"/></a>

<?php break; case 2: break; }

switch ( $favoriten) {

	case 1:

		?>

	    <a rel="nofollow" style="text-decoration:none;" href="http://www.favoriten.de/" onclick="
            window.open('http://www.favoriten.de/url-hinzufuegen.html?bm_url='+encodeURIComponent(document.location.href)+'&amp;bm_title='+encodeURIComponent(document.title));

            return false;" title="<?php echo $alt;?> Favoriten.de"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/favoriten".$style.".gif";?>" alt="<?php echo $alt;?> Favoriten.de" name="Favoriten" border="0" id="Favoriten"/></a>

<?php break; case 2: break; }

switch ( $seekxl ) { case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="http://social-bookmarking.seekxl.de/" onclick="

                
                window.open('http://social-bookmarking.seekxl.de?add_url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));
                
                return false;
                
                " title="<?php echo $alt;?> Seekxl"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/seekxl".$style.".gif";?>" alt="<?php echo $alt;?> Seekxl" name="Seekxl" border="0" id="Seekxl"/></a>

<?php break; case 2: break; }


switch ( $sbdk ) { case 1: ?>

<a rel="nofollow" style="text-decoration:none;" href="http://www.social-bookmarking.dk/" onclick="

window.open('http://www.social-bookmarking.dk/bookmarks/?action=add&amp;title='+encodeURIComponent(document.title)+'&amp;address='+(document.location.href));

return false;


" title="<?php echo $alt;?> Social Bookmark Portal"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/sbdk".$style.".gif";?>" alt="<?php echo $alt; ?> Social Bookmark Portal" name="SocialBookmarkPortal" border="0" id="SocialBookmarkPortal"/></a>

<?php break; case 2: break; }

switch ( $boni ) {

	case 1:

		?>

	    <a rel="nofollow" style="text-decoration:none;" href="http://www.bonitrust<?php if ($bonil != "") { echo $bonil; } else { echo ".de"; } ?>/" onclick="
            
            window.open('http://www.bonitrust<?php if ($bonil != "") { echo $bonil; } else { echo ".de"; } ?>/account/bookmark/?bookmark_url='+unescape(location.href));
            
            return false;

            
            " title="<?php echo $alt;?> BoniTrust"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/boni".$style.".gif";?>" alt="<?php echo $alt;?> BoniTrust" name="BoniTrust" border="0" id="BoniTrust"/></a>

<?php break; case 2: break; }

switch ( $power ) {

	case 1:

		?>

	    <a rel="nofollow" style="text-decoration:none;" href="http://www.power-oldie<?php if ($powerl == 'de') { echo 'com/de'; } elseif ($powerl == 'en') { echo '.com/en'; } ?>" onclick="

            
            window.open('http://www.power-oldie<?php if ($powerl == "de") { echo "com/de"; } elseif ($powerl == "en") { echo ".com/en"; } ?>');
            
            
            " title="<?php echo $alt;?> Power-Oldie"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/power".$style.".gif";?>" alt="<?php echo $alt;?> Power-Oldie" name="PowerOldie" border="0" id="PowerOldie"/></a>

<?php break; case 2: break; }

switch ( $bookmarkscc ) { case 1: ?>

	    <a rel="nofollow" style="text-decoration:none;" href="http://www.bookmarks<?php if ($powerl != '') { echo $bookmarksccl; } else { echo '.cc'; } ?>/" onclick="
            
            window.open('http://www.bookmarks<?php if ($bookmarksccl != "") { echo $bookmarksccl; } else { echo ".cc"; } ?>/bookmarken.php?action=neu&amp;url='+(document.location.href)+'&amp;title='+(document.title));
            
            return false;
            
            " title="<?php echo $alt;?> Bookmarks.cc"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/bookmarkscc".$style.".gif";?>" alt="<?php echo $alt;?> Bookmarks.cc" name="Bookmarkscc" border="0" id="Bookmarkscc"/></a>

<?php break; case 2: break; }

switch ( $newskick ) {

	case 1:

		?>

	    <a rel="nofollow" style="text-decoration:none;" href="http://www.newskick<?php if ($newskickl != '') { echo $newskickl; } else { echo '.de'; } ?>/" onclick="
            
            window.open('http://www.newskick<?php if ($newskickl != "") { echo $newskickl; } else { echo ".de"; } ?>/submit.php?url='+(document.location.href));
            
            return false;
            
            
            " title="<?php echo $alt;?> Newskick"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/newskick".$style.".gif";?>" alt="<?php echo $alt;?> Newskick" name="Newskick" border="0" id="Newskick"/></a>

<?php break; case 2: break; }

switch ( $newsider ) {

	case 1:

		?>

	<a rel="nofollow" style="text-decoration:none;" href="http://www.newsider<?php if ($newsiderl != '') { echo $newsiderl; } else { echo '.de'; } ?>/" onclick="
        
        
        window.open('http://www.newsider<?php if ($newsiderl != "") { echo $newsiderl; } else { echo ".de"; } ?>/submit.php?url='+(document.location.href));
        
        return false;
        
        " title="<?php echo $alt;?>  Newsider"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/newsider".$style.".gif";?>" alt="<?php echo $alt;?> Newsider" name="Newsider" border="0" id="Newsider"/></a>

<?php break; case 2: break; }

switch ( $linksilo ) {

	case 1:

		?>

			<a rel="nofollow" style="text-decoration:none;" href="http://www.linksilo<?php if ($linksilol != '') { echo $linksilol; } else { echo '.de'; } ?>/" onclick="
                        
                        
                        window.open('http://www.linksilo<?php if ($linksilol != "") { echo $linksilol; } else { echo ".de"; } ?>/index.php?area=bookmarks&amp;func=bookmark_new&amp;addurl='+encodeURIComponent(location.href)+'&amp;addtitle='+encodeURIComponent(document.title));
                        
                        return false;
                        
                        
                        " title="<?php echo $alt;?> Linksilo"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/linksilo".$style.".gif";?>" alt="<?php echo $alt;?> Linksilo" name="Linksilo" border="0" id="Linksilo"/></a>

<?php break; case 2: break; }

switch ( $readster ) {

	case 1:

		?>

			<a rel="nofollow" style="text-decoration:none;" href="http://www.readster<?php if ($readsterl != '') { echo $readsterl; } else { echo '.de'; } ?>/" onclick="
                        
                        window.open('http://www.readster<?php if ($readsterl != "") { echo $readsterl; } else { echo ".de"; } ?>/submit/?url='+encodeURIComponent(document.location)+'&amp;title='+encodeURIComponent(document.title));
                        
                        return false;
                        
                        
                        " title="<?php echo $alt;?> Readster"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/readster".$style.".gif";?>" alt="<?php echo $alt;?> Readster" name="Readster" border="0" id="Readster"/></a>

<?php break; case 2: break; }


switch ( $yigg ) {	case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="http://yigg<?php if ($yiggl != '') { echo $yiggl; } else { echo '.de'; } ?>/" onclick="

                
                window.open('http://yigg<?php if ($yiggl != "") { echo $yiggl; } else { echo ".de"; } ?>/neu?exturl='+encodeURIComponent(location.href));
                
                return false
                
                
                " title="<?php echo $alt;?> Yigg"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/yigg".$style.".gif";?>" alt="<?php echo $alt;?> Yigg" name="Yigg" border="0" id="Yigg"/></a>

<?php break; case 2: break; }

switch ( $linkarena ) { case 1:	?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.linkarena<?php if ($linkarenal != '') { echo $linkarenal; } else { echo '.com'; } ?>/" onclick="
                
                
                window.open('http://linkarena<?php if ($linkarenal != "") { echo $linkarenal; } else { echo ".com"; } ?>/bookmarks/addlink/?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title)+'&amp;desc=<?php echo $description;?>&amp;tags=<?php echo $tags_space;?>');
                
                return false;
                
                
                " title="<?php echo $alt;?> Linkarena"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/linkarena".$style.".gif";?>" alt="<?php echo $alt;?> Linkarena"  name="Linkarena" border="0" id="Linkarena"/></a>

<?php break; case 2: break; }

switch ( $digg ) { case 1:	?>

		<a rel="nofollow" style="text-decoration:none;" href="http://digg<?php if ($diggl != '') { echo $diggl; } else { echo '.com'; } ?>/" onclick="
                
                
                window.open('http://digg<?php if ($diggl != "") { echo $diggl; } else { echo ".com"; } ?>/submit?phase=2&amp;url='+encodeURIComponent(location.href)+'&amp;bodytext=<?php echo $description;?>&amp;tags=<?php echo $tags_space;?>&amp;title='+encodeURIComponent(document.title));
                
                return false;
                
                
                " title="<?php echo $alt;?> Digg"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/digg".$style.".gif";?>" alt="<?php echo $alt;?> Digg" name="Digg" border="0" id="Digg"/></a>

<?php break; case 2: break; }

switch ( $icio ) { case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="http://del.icio<?php if ($iciol != '') { echo $iciol; } else { echo '.us'; } ?>/" onclick="
                
                
                window.open('http://del.icio<?php if ($iciol != "") { echo $iciol; } else { echo ".us"; } ?>/post?v=2&amp;url='+encodeURIComponent(location.href)+'&amp;notes=<?php echo $description;?>&amp;tags=<?php echo $tags_space;?>&amp;title='+encodeURIComponent(document.title));
                
                return false;
                
                
                " title="<?php echo $alt;?> Del.icio.us"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/del".$style.".gif";?>" alt="<?php echo $alt;?> Del.icoi.us" name="Delicious" border="0" id="Delicious"/></a>

 	<?php

 break; case 2:	break; }


switch ( $reddit ) { case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="http://reddit<?php if ($redditl != '') { echo $redditl; } else { echo '.com'; } ?>/" onclick="
                
                
                window.open('http://reddit<?php if ($redditl != "") { echo $redditl; } else { echo ".com"; } ?>/submit?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));
                
                return false;
                
                
                " title="<?php echo $alt;?> Reddit"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/reddit".$style.".gif";?>" alt="<?php echo $alt;?> Reddit" name="Reddit" border="0" id="Reddit"/></a>

<?php break; case 2: break; }

switch ( $jumptags ) { case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.jumptags<?php if ($jumptagsl != '') { echo $jumptagsl; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.jumptags<?php if ($jumptagsl != "") { echo $jumptagsl; } else { echo ".com"; } ?>/add/?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));
                
                return false;
                
                " title="<?php echo $alt;?> Jumptags.com"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/jumptags".$style.".gif";?>" alt="<?php echo $alt;?> Jumptags" name="Jumptags" border="0" id="Jumptags"/></a>

<?php break; case 2: break; }

switch ( $upchuckr ) { case 1:	?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.upchuckr<?php if ($upchuckrl != '') { echo $upchuckrl; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.upchuckr<?php if ($upchuckrl != "") { echo $upchuckrl; } else { echo ".com"; } ?>/bookmarks.php/?action=add&amp;address='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title));
                
                return false;
                
                
                " title="<?php echo $alt;?> Upchuckr"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/upchuckr".$style.".gif";?>" alt="<?php echo $alt;?> Upchuckr" name="Upchuckr" border="0" id="Upchuckr"/></a>

<?php break; case 2: break; }





switch ( $simpy ) {	case 1:	?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.simpy<?php if ($simpyl != '') { echo $simpyl; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.simpy<?php if ($simpyl != "") { echo $simpyl; } else { echo ".com"; } ?>/simpy/LinkAdd.do?title='+encodeURIComponent(document.title)+'&amp;tags=<?php echo $tags;?>&amp;note=<?php echo $description;?>&amp;href='+encodeURIComponent(location.href));
                

                return false;
                
                " title="<?php echo $alt;?> Simpy"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/simpy".$style.".gif";?>" alt="<?php echo $alt;?> Simpy" name="Simpy" border="0" id="Simpy"/></a>

<?php break; case 2: break; }

switch ( $stumbleupon ) { case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.stumbleupon<?php if ($stumbleuponl != '') { echo $stumbleuponl; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.stumbleupon<?php if ($stumbleuponl != "") { echo $stumbleuponl; } else { echo ".com"; } ?>/submit?url='+encodeURIComponent(location.href)+'&amp;newcomment=<?php echo $description;?>&amp;title='+encodeURIComponent(document.title));
                
                return false;
                
                " title="<?php echo $alt;?> StumbleUpon"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/stumbleupon".$style.".gif";?>" alt="<?php echo $alt;?> StumbleUpon" name="StumbleUpon" border="0" id="StumbleUpon"/></a>

<?php break; case 2: break; }


switch ( $slashdot ) { 	case 1:	?>

		<a rel="nofollow" style="text-decoration:none;" href="http://slashdot<?php if ($slashdotl != '') { echo $slashdotl; } else { echo '.org'; } ?>/" onclick="
                
                window.open('http://slashdot<?php if ($slashdotl != "") { echo $slashdotl; } else { echo ".org"; } ?>/bookmark.pl?url='+encodeURIComponent(location.href)+'&amp;tags=<?php echo $tags_space;?>&amp;title='+encodeURIComponent(document.title));
                
                return false;
                
                
                " title="<?php echo $alt;?> Slashdot"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/slashdot".$style.".gif";?>" alt="<?php echo $alt;?> Slashdot" name="Slashdot" border="0" id="Slashdot"/></a>

<?php break; case 2: break; }

switch ( $netscape ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.netscape<?php if ($netscapel != '') { echo $netscapel; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.netscape<?php if ($netscapel != "") { echo $netscapel; } else { echo ".com"; } ?>/submit/?U='+encodeURIComponent(location.href)+'&amp;storyText=<?php echo $description;?>&amp;storyTags=<?php echo $tags;?>&amp;T='+encodeURIComponent(document.title));
                
                return false;
                
                
                " title="<?php echo $alt;?> Netscape"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/netscape".$style.".gif";?>" alt="<?php echo $alt;?> Netscape" name="Netscape" border="0" id="Netscape"/></a>

<?php break; case 2: break; }

switch ( $furl ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.furl<?php if ($furll != '') { echo $furll; } else { echo '.net'; } ?>/" onclick="
                
                window.open('http://www.furl<?php if ($furll != "") { echo $furll; } else { echo ".net"; } ?>/storeIt.jsp?u='+encodeURIComponent(location.href)+'&amp;keywords=<?php echo $tags;?>&amp;t='+encodeURIComponent(document.title));
                
                return false;
                
                
                " title="<?php echo $alt;?> Furl"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/furl".$style.".gif";?>" alt="<?php echo $alt;?> Furl" name="Furl" border="0" id="Furl"/></a>

<?php break; case 2: break; }

switch ( $yahoo ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.yahoo<?php if ($yahool != '') { echo $yahool; } else { echo '.com'; } ?>/" onclick="
                
                
                window.open('http://myweb2.search.yahoo<?php if ($yahool != "") { echo $yahool; } else { echo ".com"; } ?>/myresults/bookmarklet?t='+encodeURIComponent(document.title)+'&amp;d=<?php echo $description;?>&amp;tag=<?php echo $tags?>&amp;u='+encodeURIComponent(location.href));
                
                return false;
                
                
                " title="<?php echo $alt;?> Yahoo"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/yahoo".$style.".gif";?>" alt="<?php echo $alt;?> Yahoo" name="Yahoo" border="0" id="Yahoo"/></a>

		<?php

		break;

	case 2:

		break;

}





switch ( $blogmarks ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://blogmarks<?php if ($blogmarksl != '') { echo $blogmarksl; } else { echo '.net'; } ?>/" onclick="
                
                window.open('http://blogmarks<?php if ($blogmarksl != "") { echo $blogmarksl; } else { echo ".net"; } ?>/my/new.php?mini=1&amp;simple=1&amp;url='+encodeURIComponent(location.href)+'&amp;content=<?php echo $description;?>&amp;public-tags=<?php echo $tags?>&amp;title='+encodeURIComponent(document.title));
                
                return false;
                
                
                " title="<?php echo $alt;?> Blogmarks"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/blogmarks".$style.".gif";?>" alt="<?php echo $alt;?> Blogmarks" name="Blogmarks" border="0" id="Blogmarks"/></a>

<?php break; case 2: break; }

switch ( $diigo ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.diigo<?php if ($diigol != '') { echo $diigol; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.diigo<?php if ($diigol != "") { echo $diigol; } else { echo ".com"; } ?>/post?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title)+'&amp;tag=<?php echo $tags?>&amp;comments=<?php echo $description;?>'); 
                
                return false;
                
                
                " title="<?php echo $alt;?> Diigo"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/diigo".$style.".gif";?>" alt="<?php echo $alt;?> Diigo" name="Diigo" border="0" id="Diigo"/></a>

		<?php

		break;

	case 2:

		break;

}



switch ( $technorati ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.technorati<?php if ($technoratil != '') { echo $technoratil; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://technorati<?php if ($technoratil != "") { echo $technoratil; } else { echo ".com"; } ?>/faves?add='+encodeURIComponent(location.href)+'&amp;tag=<?php echo $tags_space?>');
                
                return false;
                
                " title="<?php echo $alt;?> Technorati"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/technorati".$style.".gif";?>" alt="<?php echo $alt;?> Technorati" name="Technorati" border="0" id="Technorati"/></a>

<?php break; case 2: break; }

switch ( $newsvine ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.newsvine<?php if ($newsvinel != '') { echo $newsvinel; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.newsvine<?php if ($newsvinel != "") { echo $newsvinel; } else { echo ".com"; } ?>/_wine/save?popoff=1&amp;u='+encodeURIComponent(location.href)+'&amp;tags=<?php echo $tags?>&amp;blurb='+encodeURIComponent(document.title));
                
                return false;
                
                
                " title="<?php echo $alt;?> Newsvine"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/newsvine".$style.".gif";?>" alt="<?php echo $alt;?> Newsvine" name="Newsvine" border="0" id="Newsvine"/></a>

<?php break; case 2: break; }

switch ( $blinkbits ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.blinkbits<?php if ($blinkbitsl != '') { echo $blinkbitsl; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.blinkbits<?php if ($blinkbitsl != "") { echo $blinkbitsl; } else { echo ".com"; } ?>/bookmarklets/save.php?v=1&amp;title='+encodeURIComponent(document.title)+'&amp;source_url='+encodeURIComponent(location.href)+'&amp;source_image_url=&amp;rss_feed_url=&amp;rss_feed_url=&amp;rss2member=&amp;body=<?php echo $description;?>');
                
                return false;
                
                
                " title="<?php echo $alt;?> Blinkbits"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/blinkbits".$style.".gif";?>" alt="<?php echo $alt;?> Blinkbits" name="Blinkbits" border="0" id="Blinkbits"/></a>

<?php break; case 2: break; }

switch ( $magnolia ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://ma.gnolia<?php if ($magnolial != '') { echo $magnolial; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://ma.gnolia<?php if ($magnolial != "") { echo $magnolial; } else { echo ".com"; } ?>/bookmarklet/add?url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title)+'&amp;description=<?php echo $description;?>&amp;tags=<?php echo $tags;?>');
                
                return false;
                
                
                " title="<?php echo $alt;?> Ma.Gnolia"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/ma.gnolia".$style.".gif";?>" alt="<?php echo $alt;?> Ma.Gnolia" name="MaGnolia" border="0" id="MaGnolia"/></a>

		<?php

		break;

	case 2:

		break;

}



switch ( $smarking ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://smarking<?php if ($smarkingl != '') { echo $smarkingl; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://smarking<?php if ($smarkingl != "") { echo $smarkingl; } else { echo ".com"; } ?>/editbookmark/?url='+ location.href +'&amp;description=<?php echo $description;?>&amp;tags=<?php echo $tags;?>');
                
                return false;
                
                
                " title="<?php echo $alt;?> Smarking"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/smarking".$style.".gif";?>" alt="<?php echo $alt;?> Smarking" name="Smarking" border="0" id="Smarking"/></a>

<?php break; case 2: break; }

switch ( $netvouz ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.netvouz<?php if ($netvouzl != '') { echo $netvouzl; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.netvouz<?php if ($netvouzl != "") { echo $netvouzl; } else { echo ".com"; } ?>/action/submitBookmark?url='+encodeURIComponent(location.href)+'&amp;description=<?php echo $description;?>&amp;tags=<?php echo $tags;?>&amp;title='+encodeURIComponent(document.title)+'&amp;popup=yes');
                
                return false;
                
                " title="<?php echo $alt;?> Netvouz"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/netvouz".$style.".gif";?>" alt="<?php echo $alt;?> Netvouz" name="Netvouz" border="0" id="Netvouz"/></a>

<?php break; case 2: break; }

switch ( $folkd ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.folkd<?php if ($folkdl != '') { echo $folkdl; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.folkd<?php if ($folkdl != "") { echo $folkdl; } else { echo ".com"; } ?>/page/submit.html?step2_sent=1&amp;url='+encodeURIComponent(location.href)+'&amp;check=page&amp;add_title='+encodeURIComponent(document.title)+'&amp;add_description=<?php echo $description;?>&amp;add_tags_show=&amp;add_tags=<?php echo $tags_semi;?>&amp;add_state=public');
                
                return false;
                
                " title="<?php echo $alt;?> Folkd"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/folkd".$style.".gif";?>" alt="<?php echo $alt;?> Folkd" name="Folkd" border="0" id="Folkd"/></a>

<?php break; case 2: break; }

switch ( $spurl ) {	case 1:	?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.spurl<?php if ($spurll != '') { echo $spurll; } else { echo '.net'; } ?>/" onclick="
                
                window.open('http://www.spurl<?php if ($spurll != "") { echo $spurll; } else { echo ".net"; } ?>/spurl.php?v=3&amp;tags=<?php echo $tags;?>&amp;title='+encodeURIComponent(document.title)+'&amp;url='+encodeURIComponent(document.location.href));
                
                return false;
                
                " title="<?php echo $alt;?> Spurl"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/spurl".$style.".gif";?>" alt="<?php echo $alt;?> Spurl" name="Spurl" border="0" id="Spurl"/></a>

<?php break; case 2: break; }

switch ( $google ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.google<?php if ($googlel != '') { echo $googlel; } else { echo '.com'; } ?>/" onclick="
                
                window.open('http://www.google<?php if ($googlel != "") { echo $googlel; } else { echo ".com"; } ?>/bookmarks/mark?op=add&amp;hl=en&amp;bkmk='+encodeURIComponent(location.href)+'&amp;annotation=<?php echo $description;?>&amp;labels=<?php echo $tags;?>&amp;title='+encodeURIComponent(document.title));
                
                return false;
                
                
                " title="<?php echo $alt;?> Google"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/google".$style.".gif";?>" alt="<?php echo $alt;?> Google" name="Google" border="0" id="Google"/></a>

<?php break; case 2: break; }


switch ( $blinklist ) {

	case 1:

		?>

		<a rel="nofollow" style="text-decoration:none;" href="http://www.blinklist<?php if ($blinklistl != '') { echo $blinklistl; } else { echo '.com'; } ?>/" onclick="
                
                
                
                window.open('http://www.blinklist<?php if ($blinklistl != "") { echo $blinklistl; } else { echo ".com"; } ?>/index.php?Action=Blink/addblink.php&amp;Description=<?php echo $description;?>&amp;Tag=<?php echo $tags;?>&amp;Url='+encodeURIComponent(location.href)+'&amp;Title='+encodeURIComponent(document.title));return false;




                " title="<?php echo $alt;?> Blinklist"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/blinklist".$style.".gif";?>" alt="<?php echo $alt;?> Blinklist" name="Blinklist" border="0" id="Blinklist"/></a>

<?php break; case 2: break; }





switch ( $linktype ) {
	case 1:
		$target= "_blank";
		break;
	case 2:
		$target= "_self";
		break;
}


switch ( $what ) { case 1: ?>

		<a rel="nofollow" style="text-decoration:none;" href="<?php echo $whatlink; ?>" target="<?php echo $target; ?>" title="Information"><img class="jbm" src="<?php echo $baseurl."modules/mod_jshare/images/what".$style.".gif";?>" alt="Information" name="Information" border="0" id="Information"/></a>

<?php break; case 2: break; } ?>

</div>
