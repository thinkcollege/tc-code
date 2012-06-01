<?php
/**
* @version $Id: fb_layout.php 1070 2008-10-06 08:11:18Z fxstein $
* Fireboard Component
* @package Fireboard
* @Copyright (C) 2006 - 2007 Best Of Joomla All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.bestofjoomla.com
*
* Based on Joomlaboard Component
* @copyright (C) 2000 - 2004 TSMF / Jan de Graaff / All Rights Reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author TSMF & Jan de Graaff
**/

defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

// ################################################################
/**
 *  Function to print the pathway
 *  @param object    database object
 *  @param object    category object
 *  @param int        the post id
 *  @param boolean    set title
 */
function jb_print_pathway(&$database, $obj_fb_cat, $bool_set_title, $obj_post = 0) {
    echo '<div class="fb_pathway">' . fb_get_pathway($database, $obj_fb_cat, $bool_set_title, $obj_post) . '</div>';
}
/**
 *  Function to print the pathway
 *  @param object    database object
 *  @param object    category object
 *  @param int        the post id
 *  @param boolean    set title
 */
function jb_get_pathway(&$database, $obj_fb_cat, $bool_set_title, $obj_post = 0)
{
    global $mainframe, $fbConfig, $fbIcons;
    //Get the Category's parent category name for breadcrumb
    $database->setQuery('SELECT name,id FROM #__fb_categories WHERE id=' . $obj_fb_cat->getParent());
    $database->loadObject($objCatParentInfo);
    	check_dberror("Unable to load category.");
    //get the Moderator list for display
    $database->setQuery('SELECT * FROM #__fb_moderation LEFT JOIN #__users ON #__users.id=#__fb_moderation.userid WHERE #__fb_moderation.catid=' . $obj_fb_cat->getId());
    $modslist = $database->loadObjectList();
    	check_dberror("Unable to load moderators.");
    //    echo '<div class="fb_pathway">';
    // List of Forums
    // show folder icon
    $return = '<img src="' . JB_URLIMAGESPATH . 'folder.gif" border="0" alt="' . _GEN_FORUMLIST . '" style="vertical-align: middle;" />&nbsp;';
    // link to List of Forum Categories
    $return .= '&nbsp;'.fb_link::GetFireBoardLink(_GEN_FORUMLIST).'<br />';

    // List of    Categories
    if ($objCatParentInfo)
    {
        if ($bool_set_title)
            $mainframe->setPageTitle($objCatParentInfo->name . ' - ' . $obj_fb_cat->getName() . ' - ' . $fbConfig->board_title);

        // show lines
        $return .= '&nbsp;<img src="' . JB_URLIMAGESPATH . 'tree-end.gif" alt="|-" border="0" style="vertical-align: middle;" />';
        $return .= '&nbsp;<img src="' . JB_URLIMAGESPATH . 'folder.gif" alt="' . $objCatParentInfo->name . '" border="0" style="vertical-align: middle;" />&nbsp;';
        // link to Category
        $return .= '&nbsp;'.fb_link::GetCategoryLink('listcat', $objCatParentInfo->id, $objCatParentInfo->name).'<br />';
        $return .= '&nbsp;<img src="' . JB_URLIMAGESPATH . 'tree-blank.gif" alt="| " border="0" style="vertical-align: middle;" />';
    }
    else
    {
        if ($bool_set_title)
            $mainframe->setPageTitle($obj_fb_cat->getName() . ' - ' . $fbConfig->board_title);
    }

    // Forum
    // show lines
    $return .= '&nbsp;<img src="' . JB_URLIMAGESPATH . 'tree-end.gif" alt="|-" border="0" style="vertical-align: middle;" />';
    $return .= '&nbsp;<img src="' . JB_URLIMAGESPATH . 'folder.gif" alt="+" border="0" style="vertical-align: middle;" />&nbsp;';
    // Link to forum
    $return .= '&nbsp;'.fb_link::GetCategoryLink('showcat', $obj_fb_cat->getId(), $obj_fb_cat->getName());

    //check if this forum is locked
    if ($obj_fb_cat->getLocked()) {
        $return .= $fbIcons['forumlocked'] ? '&nbsp;&nbsp;<img src="' . JB_URLICONSPATH . '' . $fbIcons['forumlocked'] . '" border="0" alt="'
            . _GEN_LOCKED_FORUM . '" title="' . _GEN_LOCKED_FORUM . '"/>' : '    <img src="' . JB_URLIMAGESPATH . 'lock.gif"    border="0" width="13" height="13" alt="' . _GEN_LOCKED_FORUM . '" title="' . _GEN_LOCKED_FORUM . '">';
    }

    // check if this forum is reviewed
    if ($obj_fb_cat->getReview()) {
        $return .= $fbIcons['forumreviewed'] ? '&nbsp;&nbsp;<img src="' . JB_URLICONSPATH . '' . $fbIcons['forumreviewed']
            . '" border="0" alt="' . _GEN_REVIEWED . '" title="' . _GEN_REVIEWED . '"/>' : '    <img src="' . JB_URLIMAGESPATH . 'review.gif" border="0" width="15" height="15" alt="' . _GEN_REVIEWED . '" title="' . _GEN_REVIEWED . '">';
    }

    //check if this forum is moderated
    if ($obj_fb_cat->getModerated())
    {
        $return .= $fbIcons['forummoderated'] ? '&nbsp;&nbsp;<img src="' . JB_URLICONSPATH . '' . $fbIcons['forummoderated']
            . '" border="0" alt="' . _GEN_MODERATED . '" title="' . _GEN_MODERATED . '"/>' : '    <img src="' . JB_URLEMOTIONSPATH . 'moderate.gif" border="0"  alt="' . _GEN_MODERATED . '" title="' . _GEN_MODERATED . '"/>';
        $text = '';

        if (count($modslist) > 0)
        {
            foreach ($modslist as $mod) {
                $text = $text . ', ' . $mod->username;
            }

            $return .= '&nbsp;(' . _GEN_MODERATORS . ': ' . ltrim($text, ",") . ')';
        }
    }

    if ($obj_post != 0)
    {
        if ($bool_set_title)
            $mainframe->setPageTitle($obj_post->subject . ' - ' . $fbConfig->board_title);

        // Topic
        // show lines
        $return .= '<br />&nbsp;<img src="' . JB_URLIMAGESPATH . 'tree-blank.gif" alt="| " border="0" style="vertical-align: middle;" />';
        $return .= '&nbsp;<img src="' . JB_URLIMAGESPATH . 'tree-blank.gif" alt="| " border="0" style="vertical-align: middle;" />';
        $return .= '&nbsp;<img src="' . JB_URLIMAGESPATH . 'tree-end.gif" alt="|-" border="0" style="vertical-align: middle;" />';
        $return .= '&nbsp;<img src="' . JB_URLIMAGESPATH . 'folder.gif" alt="+" border="0" style="vertical-align: middle;" />&nbsp;';
        $return .= '&nbsp;<b>' . $obj_post->subject . '</b>';

        // Check if the Topic is locked?
        if ((int)$obj_post->locked != 0) {
            $return .= '&nbsp;<img src="' . JB_URLIMAGESPATH . 'lock.gif"    border="0" width="13" height="13" alt="' . _GEN_LOCKED_TOPIC . '" title="' . _GEN_LOCKED_TOPIC . '"/>';
        }
    }

    //    echo '</div>';
    return $return;
}
/**
 * Function  that get the menu used in the header of our board
 * @param int $cbitemid
 *             Community builder itemid, used for linking to cb profile
 * @param array $fbConfig
 * @param array $fbIcons
 * @param int $my_id
 *             The user id
 * @param int $type
 *             What kind of header do you want to print: 1: default (home/profile/latest posts/faq), 2: extended1 (home/profile/view/pending messages/faq) ,3:extended2 (home/profile/reply/view/pdf/faq)
 * @param string $view
 *             The view the user is currently using, only needs to be pass when type==3 or type==2
 * @param int $catid
 *             Only needs to be passed when type==3 or type==2
 * @param int $id
 *             Only needs to be passed when type==3 or type==2
 * @param int $thread
 *             Only needs to be passed when type==3 or type==2 (well actually just give 0 when type==2)
 * @param boolean $is_moderator
 *             Only needs to be passed when type==2
 * @param int $numPending
 *             Number of pending messages, only needs to be passed when type==2
 * @return String $header
 *             The menu :-)
 */

function jb_get_menu($cbitemid, $fbConfig, $fbIcons, $my_id, $type, $view = "", $catid = 0, $id = 0, $thread = 0, $is_moderator = false, $numPending = 0)
{
	$func = strtolower(mosGetParam($_REQUEST, 'func', ''));
	if ($func == '') // Set default as per config settings
	{
		switch ($fbConfig->fbdefaultpage)
		{
			case 'recent':
				$func = 'latest';
				break;
			case 'my':
				$func = $my_id ? 'mylatest' : 'latest';
				break;
			default:
				$func = 'listcat';
		}
	}

    $header = '<div id="fb_topmenu" ><div id="fireboard_tab"><ul>';

   /*  Hack ** $header .= ' <li ';
    if ($func == 'latest' || $func == '') $header .= ' class="fireboard_item_active" ';
    $header .=' >'.fb_link::GetShowLatestLink('<span>'.($fbIcons['showlatest'] ? '<img src="' . JB_URLICONSPATH . '' . $fbIcons['showlatest'] . '" border="0" alt="' . _FB_ALL_DISCUSSIONS . '" title="' . _FB_ALL_DISCUSSIONS . '"/>' : _FB_ALL_DISCUSSIONS).'</span>');
    $header .= '</li>'; */

  

/*  Hack ** $header .= '<li ';
	 if ($func == 'listcat' ) $header .= ' class="fireboard_item_active" ';
	$header .=' >'.fb_link::GetCategoryListLink('<span>'.($fbIcons['home'] ? '<img src="' . JB_URLICONSPATH . '' . $fbIcons['home'] . '" border="0" alt="' . _FB_CATEGORIES . '"  title="' . _FB_CATEGORIES . '" />' : _FB_CATEGORIES).'</span>');
    $header .= '</li>'; */

    if ($my_id != 0)
    {
       // Hack ** 8/20/2009 $header .= ' <li ';
	//    if ($func == 'myprofile' ) $header .= ' class="fireboard_item_active" ';
    //    $header .=' >'.fb_link::GetMyProfileLink($fbConfig, $cbitemid, '<span>'.($fbIcons['profile'] ? '<img src="' . JB_URLICONSPATH . '' . //$fbIcons['profile'] . '" border="0" alt="' . _GEN_MYPROFILE . '" title="' . _GEN_MYPROFILE . '"/>' : _GEN_MYPROFILE).'</span>');
    //    $header .= '</li>';
    }

    switch ($type)
    {
        case 3:
            if ($view == "flat") { // Below a hack to remove Threaded and flat tabs
    		//	$header .= '<li>';
    		//	$header .= fb_link::GetViewLink('view', $id, $catid, 'threaded', '<span>'. _GEN_THREADED_VIEW .'</span>');
              //  $header .= '</li>';
            }
            else
            {
    		//	$header .= '<li>';
             //   $header .= fb_link::GetViewLink('view', $id, $catid, 'flat', '<span>'. _GEN_FLAT_VIEW .'</span>');
            //    $header .= '</li>';
            }

            break;

        case 2:
            if ($view == "flat")
            {
    		//	$header .= '<li>';
    		//	$header .= fb_link::GetViewLink('showcat', $id, $catid, 'threaded', '<span>'. _GEN_THREADED_VIEW .'</span>');
             //   $header .= '</li>';
            }
			else
			{
    		//	$header .= '<li>';
             //   $header .= fb_link::GetViewLink('showcat', $id, $catid, 'flat', '<span>'. _GEN_FLAT_VIEW .'</span>');
            //    $header .= '</li>';
			}
            if ($is_moderator)
            {
                if ($numPending > 0)
                {
                    $numcolor = '<font color="red">';
                    $header .= '<li>';
                    $header .= fb_link::GetPendingMessagesLink( $catid, '<span>'.($fbIcons['pendingmessages']
                        ? '<img src="' . JB_URLICONSPATH . '' . $fbIcons['pendingmessages'] . '" border="0" alt="' . $numPending . ' ' . _SHOWCAT_PENDING . '" />' : $numcolor . '' . $numPending . '</font> ' . _SHOWCAT_PENDING).'</span>');
                    $header .= '</li>';
                }
            }
            break;

        case 1:
        default:

            break;
    }

    if ($fbConfig->enablerulespage)
    {
        $header .= ' <li ';
        if ($func == 'rules' ) $header .= ' class="fireboard_item_active" ';
        $header .= ' >'.fb_link::GetRulesLink($fbConfig, '<span>'.($fbIcons['rules'] ? '<img src="' . JB_URLICONSPATH . '' . $fbIcons['rules'] . '" border="0" alt="' . _GEN_RULES . '" title="' . _GEN_RULES . '"/>' : _GEN_RULES).'</span>');
        $header .= '</li>';
    }
	if ($fbConfig->enablehelppage)
    {
        $header .= ' <li ';
        if ($func == 'faq' ) $header .= ' class="fireboard_item_active" ';
        $header .= ' >'.fb_link::GetHelpLink($fbConfig, '<span>'.($fbIcons['help'] ? '<img src="' . JB_URLICONSPATH . '' . $fbIcons['help'] . '" border="0" alt="' . _GEN_HELP . '" title="' . _GEN_HELP . '"/>' : _GEN_HELP).'</span>');
        $header .= '</li>';
	}
    $header .= '</ul></div></div>';
    return $header;
}

function getSearchBox()
{ /* Hack **  remove search box
 $return = '<div id="fb_searchbox"><form action="' . sefRelToAbs(JB_LIVEURLREL . '&amp;func=search') . '" name="searchFB" method="post">';
    $boxsize = strlen(_GEN_SEARCH_BOX);

    if ($boxsize <= 15)
        $boxsize = 15;

   $return .= '<input class="fb_search_inputbox fbs" type="text" name="searchword" size="'. $boxsize . '" value="' . _GEN_SEARCH_BOX . '" onblur="if(this.value==\'\') this.value=\'' . _GEN_SEARCH_BOX . '\';" onfocus="if(this.value==\'' . _GEN_SEARCH_BOX . '\') this.value=\'\';" />';
	$return .= ' <input type="submit" value="'._FB_GO.'" name="submit" class="fb_search_button fbs"/>';
    $return .= '</form></div>'; */
    return $return;
}
?>