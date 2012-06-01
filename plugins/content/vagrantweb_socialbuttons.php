<?php
/**
 * @package		VagrantWeb Social Buttons
 * @license		GNU/GPL 2.0 http://www.gnu.org/licenses/gpl-2.0.html
 * @Copyright (c) 2010 Carter McLaughlin
 * @author		Carter McLaughlin <carter@vagrantweb.ca>
 
 */

defined( '_JEXEC' ) or  die('Restricted access');
jimport( 'joomla.event.plugin' );
require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php';
$mainframe->registerEvent('onPrepareContent', 'plgContentVagrantWeb_SocialButtons');	

function plgContentVagrantWeb_SocialButtons(&$article, &$params, $page=0) {
	
	global $mainframe;
	
	if(JRequest::getVar('view','')=='article') $url = "http://".$_SERVER['HTTP_HOST'] . getenv('REQUEST_URI'); 
	else {
		if(isset($article->id)) $url = "http://".$_SERVER['HTTP_HOST'].JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));
		else return;
		}		

	$url2 = urlencode($url);
	
	$plugin =& JPluginHelper::getPlugin('content', 'vagrantweb_socialbuttons');

	$like = '';  $retweet = '';  $buzz = '';  $digg = '';
	
	$cp = new JParameter( $plugin->params );

        $position = ($cp->get('position'));
	
	if($cp->get('like')) $like = '<div class="faceandtweet_like" style="float:'.$cp->get('float').'; width:'.$cp->get('like_width').'px; height:'.$cp->get('like_height').'px;"><iframe src="http://www.facebook.com/plugins/like.php?href='.$url2 .'&amp;layout='.$cp->get('like_style').'&amp;width='.$cp->get('like_width').'&amp;show_faces=false&amp;action='.$cp->get('like_verb').'&amp;colorscheme='.$cp->get('like_color_scheme').'&amp;height='.$cp->get('like_height').'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$cp->get('like_width').'px; height:'.$cp->get('like_height').'px;" allowTransparency="true"></iframe></div>';
	
	
	if($cp->get('retweet')) $retweet = '<div class="faceandtweet_retweet" style="float:'.$cp->get('float').'; width:'.$cp->get('count-width').'px;"><a href="http://twitter.com/share?url='.$url2.'" class="twitter-share-button" data-text="'.$article->title.':" data-count="'.$cp->get('count').'" data-via="'.$cp->get('twitter_account').'" data-related="'.$cp->get('twitter_account2').'">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>';	
	
        if($cp->get('buzz')) $buzz = '<div class="faceandtweet_retweet" style="float:'.$cp->get('float').'; width:110px;"><a title="'.$buzzTitle.'" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="small-count"></a><script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script></div>';
		
		if($cp->get('digg')) $digg = '<div class="faceandtweet_retweet" style="float:'.$cp->get('float').'; width:110px;"><a class="DiggThisButton DiggCompact"
href="http://digg.com/submit?url='.$url2.'&amp;title='.$article->title.'"></a></div>';

	$dos = $retweet . $like . $buzz . $digg;
	
	if(!$cp->get('where')&&JRequest::getVar('view','')!='article') return;
	
if($position==0){ 	
$article->text =  $article->text.'<div class="faceandtweet">'.$dos.'<div style="clear:both;"></div></div>';
	
}else if($position==1){
$article->text =  '<div class="faceandtweet">'.$dos.'<div style="clear:both;"></div></div>'.$article->text.'';
}else{
$article->text =  '<div class="faceandtweet">'.$dos.'<div style="clear:both;"></div></div>'.$article->text.'<div class="faceandtweet" style="margin-top:15px;">'.$dos.'<div style="clear:both;"></div></div>';
}
		
}





?>