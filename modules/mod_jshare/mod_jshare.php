<?php
/**
 * JShare Social Bookmarking Module for Joomla 1.5
 *
 * @package    mod_jshare
 * @subpackage Modules
 * @link       http://www.joomify.com
 * @license    LGPL
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// todo
// require_once( dirname(__FILE__).DS.'helper.php' );
$pz = 'params';

$baseurl = JURI::base();
$align 			= $$pz->get('align', 'center');
$padding 		= $$pz->get('padding', '0');
$ani 			= $$pz->get('ani', 0);
$alt 			= $$pz->get('alt', "Add to:");
$whatlink		= $$pz->get('whatlink', "http://en.wikipedia.org/wiki/social_bookmarking");
$tags 			= addslashes(str_replace("\n","", $mainframe->getCfg( 'MetaKeys' )));
$tags 			= trim($tags);
$tags_space		= str_replace(',', ' ', $tags);
$tags_semi 		= str_replace(',', ';', $tags);
$tags_space     = str_replace('  ', ' ', $tags_space);
$description    = addslashes($mainframe->getCfg( 'MetaDesc' ));
$style 			= $$pz->get('style', "1");


// Alternative domains
$wongl			= $$pz->get('wongl', '');
$yiggl 			= $$pz->get('yiggl', '');
$newsiderl 		= $$pz->get('newsiderl', '');
$newskickl 		= $$pz->get('newskickl', '');
$favitl			= $$pz->get('favitl', '');
$kledyl			= $$pz->get('kledyl', '');
$bonil			= $$pz->get('bonil', '');
$powerl			= $$pz->get('powerl', '');
$bookmarksccl   = $$pz->get('bookmarksccl', '');
$linksilol 		= $$pz->get('linksilol', '');
$readsterl 		= $$pz->get('readsterl', '');
$linkarenal		= $$pz->get('linkarenal', '');
$diggl 			= $$pz->get('diggl', '');
$iciol 			= $$pz->get('iciol', '');
$redditl 		= $$pz->get('redditl', '');
$jumptagsl 		= $$pz->get('jumptagsl', '');
$upchuckrl 		= $$pz->get('upchuckrl', '');
$simpyl			= $$pz->get('simpyl', '');
$stumbleuponl 	= $$pz->get('stumbleuponl', '');
$slashdotl 		= $$pz->get('slashdotl', '');
$netscapel 		= $$pz->get('netscapel', '');
$furll 			= $$pz->get('furll', '');
$yahool			= $$pz->get('yahool', '');
$blogmarksl     = $$pz->get('blogmarksl', '');
$diigol 		= $$pz->get('diigol', '');
$technoratil    = $$pz->get('technoratil', '');
$newsvinel 		= $$pz->get('newsvinel', '');
$blinkbitsl		= $$pz->get('blinkbitsl', '');
$magnolial 		= $$pz->get('magnolial', '');
$smarkingl 		= $$pz->get('smarkingl', '');
$netvouzl 		= $$pz->get('netvouzl', '');
$folkdl			= $$pz->get('folkdl', '');
$spurll 		= $$pz->get('spurll', '');
$googlel 		= $$pz->get('googlel', '');
$blinklistl     = $$pz->get('blinklistl', '');


// multi TLD
$wong 	        = $$pz->get('wong', 2);

// .com TLD
$webnews 		= $$pz->get('webnews', 2);
$buzka 	        = $$pz->get('buzka', 2);
$facebook       = $$pz->get('facebook', 2);
$jbookmarks     = $$pz->get('jbookmarks', 1);
$live           = $$pz->get('live', 2);
//$fark           = $$pz->get('fark', 1);
$ximmy          = $$pz->get('ximmy', 2);

// .de TLD
$favoriten		= $$pz->get('favoriten', 2);
$seekxl 		= $$pz->get('seekxl', 2);
$iciode 		= $$pz->get('iciode', 2);
$oneview 		= $$pz->get('oneview', 2);

// .dk TLD
$sbdk 		    = $$pz->get('sbdk', 2);

// unsorted
$newsider 		= $$pz->get('newsider', 2);
$yigg 			= $$pz->get('yigg', 2);
$linktype 		= $$pz->get('linktype', 2);
$what 			= $$pz->get('what', 2);
$newskick 		= $$pz->get('newskick', 2);
$favit 			= $$pz->get('favit', 2);
$kledy 			= $$pz->get('kledy', 2);
$boni			= $$pz->get('boni', 2);
$power			= $$pz->get('power', 2);
$bookmarkscc   	= $$pz->get('bookmarkscc', 2);
$linksilo 		= $$pz->get('linksilo', 2);
$readster 		= $$pz->get('readster', 2);
$linkarena 		= $$pz->get('linkarena', 2);
$digg 			= $$pz->get('digg', 2);
$icio 			= $$pz->get('icio', 2);
$reddit 		= $$pz->get('reddit', 2);
$jumptags 		= $$pz->get('jumptags', 2);
$upchuckr 		= $$pz->get('upchuckr', 2);
$simpy 			= $$pz->get('simpy', 2);
$stumbleupon    = $$pz->get('stumbleupon', 2);
$slashdot 		= $$pz->get('slashdot', 2);
$netscape 		= $$pz->get('netscape', 2);
$furl 			= $$pz->get('furl', 2);
$yahoo 			= $$pz->get('yahoo', 2);
$blogmarks 		= $$pz->get('blogmarks', 2);
$diigo 			= $$pz->get('diigo', 2);
$technorati     = $$pz->get('technorati', 2);
$newsvine 		= $$pz->get('newsvine', 2);
$blinkbits		= $$pz->get('blinkbits', 2);
$magnolia 		= $$pz->get('magnolia', 2);
$smarking 		= $$pz->get('smarking', 2);
$netvouz 		= $$pz->get('netvouz', 2);
$folkd 			= $$pz->get('folkd', 2);
$spurl 			= $$pz->get('spurl', 2);
$google 		= $$pz->get('google', 2);
$blinklist 		= $$pz->get('blinklist', 2);
require( JModuleHelper::getLayoutPath( 'mod_jshare' ) );
?>
