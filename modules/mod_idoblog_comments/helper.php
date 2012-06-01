<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_idoblog'.DS.'config.php');

class modIdoblogCommentsHelper
{
	function getList(&$params)
	{
		global $mainframe;

		$db			=& JFactory::getDBO();

		$count		= (int) $params->get('count', 5);
 		$chars		= (int) $params->get('chars', 25);
		$config = new KBconfig();

$menu = &JSite::getMenu();
$Items	= $menu->getItems('link', 'index.php?option=com_idoblog&view=idoblog');
$Itemid=$Items[0]->id;

		// Content Items only
		$query = 'SELECT * FROM #__idoblog_comments WHERE publish=1 ORDER by date DESC';
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

		$i		= 0;
		$lists	= array();
		foreach ( $rows as $row )
		{
			$lists[$i]->link = JRoute::_('index.php?option=com_idoblog&task=viewpost&id='. $row->idarticle.'&Itemid='.$Itemid.'#comment'.$row->id);
			$lists[$i]->userlink = JRoute::_('index.php?option=com_idoblog&task=profile&userid='. $row->created_by.'&Itemid='.$Itemid);
			$lists[$i]->text = modIdoblogCommentsHelper::replace_bbcode(modIdoblogCommentsHelper::replace_smile(JString::substr(htmlspecialchars( $row->text ),0,$chars)));
			$lists[$i]->text=str_replace("&lt;br&gt;","<br>",$lists[$i]->text);
			$lists[$i]->text=str_replace("&lt;br /&gt;","<br>",$lists[$i]->text);


			$lists[$i]->name=$row->username;
			$lists[$i]->created_by=$row->created_by;
			$date = & JFactory::getDate($row->date, $mainframe->getCfg('offset'));
			$lists[$i]->date=$date->toFormat($config->dateformat.' %H:%M');
			$i++;
		}

		return $lists;



	}


function replace_smile($comment) {
GLOBAL $live_site;
//обработка смайлов
$path=$live_site.'components/com_idoblog/assets/images/smiley/';
$comment=str_replace(":)","<img src=".$path."regular_smile.gif>",$comment);
$comment=str_replace(":D","<img src=".$path."teeth_smile.gif>",$comment);
$comment=str_replace(":(","<img src=".$path."sad_smile.gif>",$comment);
$comment=str_replace(";)","<img src=".$path."wink_smile.gif>",$comment);
$comment=str_replace(":o","<img src=".$path."whatchutalkingabout_smile.gif>",$comment);
$comment=str_replace(":P","<img src=".$path."tounge_smile.gif>",$comment);
$comment=str_replace("8)","<img src=".$path."shades_smile.gif>",$comment);
$comment=str_replace(":up:","<img src=".$path."thumbs_up.gif>",$comment);
$comment=str_replace(":down:","<img src=".$path."thumbs_down.gif>",$comment);
$comment=str_replace(":-(","<img src=".$path."cry_smile.gif>",$comment);
$comment=str_replace(":devil:","<img src=".$path."angry_smile.gif>",$comment);
$comment=str_replace(":kiss:","<img src=".$path."kiss.gif>",$comment);
return $comment;
}

function replace_bbcode($comment) {
$comment=str_replace("[B]","<b>",$comment);
$comment=str_replace("[/B]","</b>",$comment);
$comment=str_replace("[I]","<i>",$comment);
$comment=str_replace("[/I]","</i>",$comment);
$comment=str_replace("[U]","<u>",$comment);
$comment=str_replace("[/U]","</u>",$comment);
$comment=str_replace("[S]","<s>",$comment);
$comment=str_replace("[/S]","</s>",$comment);
$comment=str_replace("[IMG]",'<img src="',$comment);
$comment=str_replace("[/IMG]",'">',$comment);
$comment=preg_replace("#\[URL=((?:[^]]|](?=\.\w{2,4}))+)\]((?:(?!\[/URL]).)+)\[/URL]#",'<a href="\\1">\\2</a>',$comment);
return $comment;
}

}
