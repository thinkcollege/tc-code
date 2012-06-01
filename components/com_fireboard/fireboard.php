<?php
/**
* @version $Id: fireboard.php 1070 2008-10-06 08:11:18Z fxstein $
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

// Dont allow direct linking
defined ('_VALID_MOS') or die('Direct Access to this location is not allowed.');

// Just for debugging and performance analysis
$mtime = explode(" ", microtime());
$tstart = $mtime[1] + $mtime[0];

// Kill notices (we have many..)
error_reporting (E_ALL ^ E_NOTICE);

global $mosConfig_lang, $fbIcons;
global $is_Moderator;

// ERROR: global scope mix
global $message;
// Get all the variables we need and strip them in case
$action 		= mosGetParam($_REQUEST, 'action', '');
$attachfile 	= mosGetParam($_FILES['attachfile'], 'name', '');
$attachimage 	= mosGetParam($_FILES['attachimage'], 'name', '');
$catid 			= intval(mosGetParam($_REQUEST, 'catid', 0));
$contentURL 	= mosGetParam($_REQUEST, 'contentURL', '');
$do 			= mosGetParam($_REQUEST, 'do', '');
$email 			= mosGetParam($_REQUEST, 'email', '');
$favoriteMe 	= mosGetParam($_REQUEST, 'favoriteMe', '');
$fb_authorname 	= mosGetParam($_REQUEST, 'fb_authorname', '');
$fb_thread 		= intval(mosGetParam($_REQUEST, 'fb_thread', 0));
$func 			= strtolower(mosGetParam($_REQUEST, 'func', ''));
$id 			= intval(mosGetParam($_REQUEST, 'id', ''));
$limit 			= intval(mosGetParam($_REQUEST, 'limit', 0));
$limitstart 	= intval(mosGetParam($_REQUEST, 'limitstart', 0));
$markaction 	= mosGetParam($_REQUEST, 'markaction', '');
$message 		= mosGetParam($_REQUEST, 'message', '');
$page 			= intval(mosGetParam($_REQUEST, 'page', 0));
$parentid 		= intval(mosGetParam($_REQUEST, 'parentid', 0));
$pid 			= intval(mosGetParam($_REQUEST, 'pid', 0));
$replyto 		= intval(mosGetParam($_REQUEST, 'replyto', 0));
$resubject 		= mosGetParam($_REQUEST, 'resubject', '');
$return 		= mosGetParam($_REQUEST, 'return', '');
$rowid 			= intval(mosGetParam($_REQUEST, 'rowid', 0));
$rowItemid 		= intval(mosGetParam($_REQUEST, 'rowItemid', 0));
$sel 			= mosGetParam($_REQUEST, 'sel', '');
$subject 		= mosGetParam($_REQUEST, 'subject', '');
$subscribeMe 	= mosGetParam($_REQUEST, 'subscribeMe', '');
$thread 		= intval(mosGetParam($_REQUEST, 'thread', 0));
$topic_emoticon = mosGetParam($_REQUEST, 'topic_emoticon', '');
$userid 		= intval(mosGetParam($_REQUEST, 'userid', 0));
$view 			= mosGetParam($_REQUEST, 'view', '');
$msgpreview 	= mosGetParam($_REQUEST, 'msgpreview', '');

include_once ($mainframe->getCfg("absolute_path") . "/components/com_fireboard/sources/fb_debug.php");

// get fireboards configuration params in
require_once ($mainframe->getCfg("absolute_path") . "/components/com_fireboard/sources/fb_config.class.php");
global $fbConfig;
$fbConfig = new fb_config();
$fbConfig->load();

//include_once ($mainframe->getCfg("absolute_path") . '/administrator/components/com_fireboard/fireboard_config.php');

// Central Location for all internal links
require_once ($mainframe->getCfg("absolute_path") . "/components/com_fireboard/sources/fb_link.class.php");

// Class structure should be used after this and all the common task should be moved to this class
require_once ($mainframe->getCfg("absolute_path") . "/components/com_fireboard/class.fireboard.php");

// get right Language file
if (file_exists(JB_ABSADMPATH . '/language/' . JB_LANG . '.php')) {
    include_once (JB_ABSADMPATH . '/language/' . JB_LANG . '.php');
    }
else {
    include_once (JB_ABSADMPATH . '/language/english.php');
    }

// Include Clexus PM class file
if ($fbConfig->pm_component == "clexuspm")
{
    require_once ($mosConfig_absolute_path . '/components/com_mypms/class.mypms.php');
    $ClexusPMconfig = new ClexusPMConfig();
}

//time format
include_once (JB_ABSSOURCESPATH . 'fb_timeformat.class.php');

// systime is current time with proper board offset
define ('JB_SECONDS_IN_HOUR', 3600);
define ('JB_SECONDS_IN_YEAR', 31536000);
// define ('JB_OFFSET_USER', ($mainframe->getCfg('offset_user') * JB_SECONDS_IN_HOUR));
// For now: we add the correct offset to systime
// In the future the offset should be removed and only applied when
// displaying items -> store data in UTC
define ('JB_OFFSET_BOARD',($fbConfig->board_ofset * JB_SECONDS_IN_HOUR));

$systime = time() + JB_OFFSET_BOARD;

// additional database defines
define ('JB_DB_MISSING_COLUMN', 1054);

// Retrieve current cookie data for session handling
$settings = $_COOKIE['fboard_settings'];

// set configuration dependent params
$str_FB_templ_path = JB_ABSPATH . '/template/' . ($fb_user_template?$fb_user_template:$fbConfig->template);
$board_title = $fbConfig->board_title;
$fromBot = 0;
$prefview = $fbConfig->default_view;

// JOOMLA STYLE CHECK
if ($fbConfig->joomlastyle < 1) {
    $boardclass = "fb_";
    }

// Include Badword class file
if ($fbConfig->badwords and !class_exists('Badword')) {
	foreach (array('badwords2','badword') as $com_bw) {
		$com_bw = $mosConfig_absolute_path.'/components/com_'.$com_bw.'/class.'.$com_bw.'.php';
		if (is_file($com_bw)) {
			require_once ($com_bw);
			break;
		}
	}
}

// Include preview here before inclusion of other files
if ($func == "getpreview") {

    if (file_exists(JB_ABSTMPLTPATH . '/smile.class.php')) {
        include (JB_ABSTMPLTPATH . '/smile.class.php');
    }
    else {
        include (JB_ABSPATH . '/template/default/smile.class.php');
    }

    $message = utf8_urldecode(utf8_decode($msgpreview));

    $msgbody = smile::smileReplace( $message , 0, $fbConfig->disemoticons, $smileyList);
    $msgbody = nl2br($msgbody);
    $msgbody = str_replace("__FBTAB__", "\t", $msgbody);
    // $msgbody = ereg_replace('%u0([[:alnum:]]{3})', '&#x1;',$msgbody);

    $msgbody = smile::htmlwrap($msgbody, $fbConfig->wrap);
    header("Content-Type: text/html; charset=utf-8");
    echo $msgbody;
    die();
}

if ($func == "showcaptcha") {
   include (JB_ABSPATH . '/template/default/plugin/captcha/randomImage.php');
   die();
}

// Add required header tags
$mainframe->addCustomHeadTag('<script type="text/javascript" src="' . JB_JQURL . '"></script>');
$mainframe->addCustomHeadTag('<script type="text/javascript">
//1: show, 0 : hide
jr_expandImg_url = "' . JB_URLIMAGESPATH . '";</script>');
$mainframe->addCustomHeadTag('<script type="text/javascript" src="' . JB_COREJSURL . '"></script>');

if ($fbConfig->joomlastyle < 1) {
    $mainframe->addCustomHeadTag('<link type="text/css" rel="stylesheet" href="' . JB_TMPLTCSSURL . '" />');
    }
else {
    $mainframe->addCustomHeadTag('<link type="text/css" rel="stylesheet" href="' . JB_DIRECTURL . '/template/default/joomla.css" />');
    }

// WHOIS ONLINE IN FORUM
if (file_exists(JB_ABSTMPLTPATH . '/plugin/who/who.class.php')) {
    include (JB_ABSTMPLTPATH . '/plugin/who/who.class.php');
    }
else {
    include (JB_ABSPATH . '/template/default/plugin/who/who.class.php');
    }

// include required libraries
if (file_exists(JB_ABSTMPLTPATH . '/fb_layout.php')) {
    require_once (JB_ABSTMPLTPATH . '/fb_layout.php');
    }
else {
    require_once (JB_ABSPATH . '/template/default/fb_layout.php');
    }

require_once (JB_ABSSOURCESPATH . 'fb_permissions.php');
require_once (JB_ABSSOURCESPATH . 'fb_category.class.php');

if ($catid != '') {
    $thisCat = new jbCategory($database, $catid);
    }

if (defined('JPATH_BASE')) {
    jimport ('pattemplate.patTemplate');
    }
else {
    require_once (JB_JABSPATH . '/includes/patTemplate/patTemplate.php');
    }

$obj_FB_tmpl = new patTemplate();
$obj_FB_tmpl->setBasedir($str_FB_templ_path);

// Permissions: Check for administrators and moderators
if ($my->id != 0)
{
    $aro_group = $acl->getAroGroup($my->id);
    if ($aro_group and FBTools::isJoomla15())
    	$aro_group->group_id = $aro_group->id;  // changed fieldname in Joomla 1.5: "group_id" -> "id"
    $is_admin = (strtolower($aro_group->name) == 'super administrator' || strtolower($aro_group->name) == 'administrator');
}
else
{
    $aro_group = 0;
    $is_admin = 0;
}

$is_Moderator = fb_has_moderator_permission($database, $thisCat, $my->id, $is_admin);

//intercept the RSS request; we should stop afterwards
if ($func == 'fb_rss')
{
    include (JB_ABSSOURCESPATH . 'fb_rss.php');
    die();
}

if ($func == 'fb_pdf')
{
    include (JB_ABSSOURCESPATH . 'fb_pdf.php');
    die();
}

if ($func == '') // Set default start page as per config settings
{
	switch ($fbConfig->fbdefaultpage)
	{
		case 'recent':
			$func = 'latest';
			break;
		case 'my':
			$func = $my->id > 0 ? 'mylatest' : 'latest';
			break;
		default:
			$func = 'listcat';
	}
}

// Include the Community Builder language file if necessary and set CB itemid value
$cbitemid = 0;

if ($fbConfig->cb_profile)
{
    // $cbitemid = JBgetCBprofileItemid();
    // Include CB language files
    $UElanguagePath = $mainframe->getCfg('absolute_path') . '/components/com_comprofiler/plugin/language';
    $UElanguage = $mainframe->getCfg('lang');

    if (!file_exists($UElanguagePath . '/' . $mosConfig_lang . '/' . $mosConfig_lang . '.php')) {
        $UElanguage = 'default_language';
        }

    include_once ($UElanguagePath . '/' . $UElanguage . '/' . $UElanguage . '.php');
}

// fireboard Current Template Icons Pack
// See if there's an icon pack installed
$useIcons = 0; //init
$fbIcons = 0;

if (file_exists(JB_ABSTMPLTPATH . '/icons.php'))
{
    include_once (JB_ABSTMPLTPATH . '/icons.php');
    $useIcons = 1;
}
else
{
    include_once (JB_ABSPATH . '/template/default/icons.php');
}

//Get the userid; sometimes the new var works whilst $my->id doesn't..?!?
$my_id = $my->id;

// Check if we only allow registered users
if ($fbConfig->regonly && !$my_id)
{
    echo _FORUM_UNAUTHORIZIED . "<br />";
    echo _FORUM_UNAUTHORIZIED2;
}
// or if the board is offline
else if ($fbConfig->board_offline && !$is_admin)
{
    echo $fbConfig->offline_message;
}
else
{
//
// This is the main session handling section. We rely both on cookie as well as our own
// fireboard session table inside the database. We are leveraging the cookie to keep track
// of an individual session and its various refreshes. As we will never know what the last
// pageview of a session will be (as defined by a commonly used 30min break/pause) we
// keep updateing the cookie until we detect a 30+min break. That break tells us to reset
// the last visit timestamp inside the database.
// We also redo the security checks with every new session to minimize the risk of exposed
// access rights though someone 'leeching' on to another session. This resets the cached
// priviliges after every 30 min of inactivity
//
	// We only do the session handling for registered users
	// No point in keeping track of whats new for guests
	if ($my_id > 0)
	{
		// First we drop an updated cookie, good for 1 year
		// We have consolidated multiple instances of cookie management into this single location
		// NOT SURE IF WE STILL NEED THIS ONE after session management got dbtized
		setcookie("fboard_settings[member_id]", $my_id, time() + JB_SECONDS_IN_YEAR, '/');

		// We assume that this is a new user and that we don't know about a previous visit
		$new_fb_user = 0;
		$resetView = 0;

		// Lookup existing session sored in db. If none exists this is a first time visitor
		$database->setQuery("SELECT * from #__fb_sessions where userid=" . $my_id);
		$fbSessionArray = $database->loadObjectList();
			check_dberror("Unable to load sessions.");
		$fbSession = $fbSessionArray[0];
		$fbSessionUpd = null;

		// If userid is empty/null no prior record did exist -> new session and first time around
		if ($fbSession->userid == "" ) {
			$new_fb_user = 1;
			$resetView = 1;
			// Init new sessions for first time user
			$fbSession->userid = $my_id;
			$fbSession->allowed = '';
			$fbSession->lasttime = $systime - JB_SECONDS_IN_YEAR;  // show threads up to 1 year back as new
			$fbSession->readtopics = '';
			$fbSession->currvisit = $systime;
		}

		// detect fbsession timeout (default: after 30 minutes inactivity)
		$fbSessionTimeOut = ($fbSession->currvisit + $fbConfig->fbsessiontimeout) < $systime;

		// new indicator handling
		if ($markaction == "allread") {
			$fbSession->lasttime = $fbSessionUpd->lasttime = $systime;
			$fbSession->readtopics = $fbSessionUpd->readtopics = '';
			echo "<script> alert('" . _GEN_ALL_MARKED . "'); window.location='" . sefRelToAbs(JB_LIVEURLREL) . "';</script>\n";
		} elseif ($fbSessionTimeOut) {
			$fbSession->lasttime = $fbSessionUpd->lasttime = $fbSession->currvisit;
			$fbSession->readtopics = $fbSessionUpd->readtopics = '';
		}

		// get all accessaible forums if needed (eg on forum modification, new session)
		if (!$fbSession->allowed or $fbSession->allowed == 'na' or $fbSessionTimeOut) {
			$allow_forums = FBTools::getAllowedForums($my_id, $aro_group->group_id, $acl);
			if (!$allow_forums) $allow_forums = '0';
			if ($allow_forums <> $fbSession->allowed)
				$fbSession->allowed = $fbSessionUpd->allowed = $allow_forums;
			unset($allow_forums);
		}

		// save fbsession
		if ($new_fb_user) {
			$database->insertObject('#__fb_sessions', $fbSession);
				check_dberror('Unable to insert new session record for user.');
		} else {
			$fbSessionUpd->userid = $fbSession->userid;
			$fbSession->currvisit = $fbSessionUpd->currvisit = $systime;
			$database->updateObject('#__fb_sessions', $fbSessionUpd, 'userid');
				check_dberror('Unable to update session record for user.');
		}
		unset($fbSessionUpd);

		// Now lets get the view type for the forum
		$database->setQuery("select view from #__fb_users where userid=$my_id");
		$prefview = $database->loadResult();
			check_dberror('Unable load default view type for user.');

		// If the prefferred view comes back empty this must be a new user
		// who does not yet have a FireBoard profile -> lets create one
		if ($prefview == "")
		{
			//assume there's no profile; set userid and the default view type as preferred view type.
			$prefview = $fbConfig->default_view;

			$database->setQuery("insert into #__fb_users (userid,view,moderator) values ('$my_id','$prefview','$is_admin')");
			$database->query();
				check_dberror('Unable to create user profile.');

			// If Cummunity Builder is enabled, lets make sure we update the view preference
			if ($fbConfig->cb_profile)
			{
		        $cbprefview = $prefview == "threaded" ? "_UE_FB_VIEWTYPE_THREADED" : "_UE_FB_VIEWTYPE_FLAT";

				$database->setQuery("update #__comprofiler set fbviewtype='$cbprefview' where user_id='$my_id'");
				$database->query();
					check_dberror('Unable to update Community Builder profile.');
			}
		}
		// If its not a new profile check if we have Community Builder enabled and read from there
		else if ($fbConfig->cb_profile)
		{
			$database->setQuery("select fbviewtype from #__comprofiler where user_id='$my_id'");
			$fbviewtype = $database->loadResult();
				check_dberror('Unable load default view type for user from Community Builder.');

			$prefview = $fbviewtype == "_UE_FB_VIEWTYPE_THREADED" ? "threaded" : "flat";
		}
		// Only reset the view if we have determined above that we need to
		// Without that test the user would not be able to make intra session
		// view changes by clicking on the threaded vs flat view link
		if ($resetView == 1)
		{
    		setcookie("fboard_settings[current_view]", $prefview, time() + JB_SECONDS_IN_YEAR, '/');
	    	$view = $prefview;
	    }

	    // Assign previous visit without user offset to variable for templates to decide
		// whether or not to use the NEW indicator on forums and posts
		$prevCheck = $fbSession->lasttime; // - JB_OFFSET_USER; Don't use the user offset - it throws the NEW indicator off
	}
	else
	{
		// collect accessaible categories for guest user
		$database->setQuery("SELECT id FROM #__fb_categories WHERE pub_access=0 AND published=1");
		$fbSession->allowed =
			($arr_pubcats = $database->loadResultArray())?implode(',', $arr_pubcats):'';
			check_dberror('Unable load accessible categories for user.');

		// For guests we don't show new posts
		$prevCheck = $systime;
	}
	// no access to categories?
	if (!$fbSession->allowed) $fbSession->allowed = '0';

    //Initial:: determining what kind of view to use... from profile, cookie or default settings.
    //pseudo: if (no view is set and the cookie_view is not set)
    if ($view == "" && $settings['current_view'] == "")
    {
        //pseudo: if there's no prefered type, use FB's default view otherwise use preferred view from profile
        //and then set the cookie right
        $view = $prefview == "" ? $fbConfig->default_view : $prefview;
        setcookie("fboard_settings[current_view]", $view, time() + JB_SECONDS_IN_YEAR, '/');
    }
    //pseudo: otherwise if (no view set but cookie isn't empty use view as set in cookie
    else if ($view == "" && $settings['current_view'] != "")
  	{
        $view = $settings['current_view'];
    }

    //Get the max# of posts for any one user
    $database->setQuery("SELECT max(posts) from #__fb_users");
    $maxPosts = $database->loadResult();
    	check_dberror('Unable load max(posts) for user.');

    //Get the topics this user has already read this session from #__fb_sessions
    $readTopics=$fbSession->readtopics;
    $read_topics = explode(',', $readTopics);

    /*       _\|/_
             (o o)
     +----oOO-{_}-OOo--------------------------------+
     |    Until this section we have included the    |
     |   necessary files and gathered the required   |
     |     variables. Now let's start processing     |
     |                     them                      |
     +----------------------------------------------*/

    //Check if the catid requested is a parent category, because if it is
    //the only thing we can do with it is 'listcat' and nothing else
    if ($func == "showcat" || $func == "view" || $func == "post")
    {
        $database->setQuery("SELECT parent FROM #__fb_categories WHERE id=$catid");
	    $strCatParent = $database->loadResult();
			check_dberror('Unable to load categories.');

        if ($catid == '' || $strCatParent == 0)
    		{
            $func = 'listcat';
        }
    }

    switch ($func)
    {
        case 'view':
            $fbMenu = jb_get_menu(FB_CB_ITEMID, $fbConfig, $fbIcons, $my_id, 3, $view, $catid, $id, $thread);

            break;

        case 'showcat':
            //get number of pending messages
            $database->setQuery("SELECT count(*) FROM #__fb_messages WHERE catid=$catid and hold=1");
            $numPending = $database->loadResult();
            	check_dberror('Unable load pending messages.');

            $fbMenu = jb_get_menu(FB_CB_ITEMID, $fbConfig, $fbIcons, $my_id, 2, $view, $catid, $id, $thread, $is_Moderator, $numPending);
            break;

        default:
            $fbMenu = jb_get_menu(FB_CB_ITEMID, $fbConfig, $fbIcons, $my_id, 1);

            break;
    }

    // display header
    $obj_FB_tmpl->readTemplatesFromFile("header.html");
    $obj_FB_tmpl->addVar('jb-header', 'menu', $fbMenu);
    $obj_FB_tmpl->addVar('jb-header', 'board_title', $board_title);
    $obj_FB_tmpl->addVar('jb-header', 'css_path', JB_DIRECTURL . '/template/' . $fbConfig->template . '/forum.css');
    $obj_FB_tmpl->addVar('jb-header', 'offline_message', $fbConfig->board_offline ? '<span id="fbOffline">' . _FORUM_IS_OFFLINE . '</span>' : '');
    $obj_FB_tmpl->addVar('jb-header', 'searchbox', getSearchBox());
    $obj_FB_tmpl->addVar('jb-header', 'pb_imgswitchurl', JB_URLIMAGESPATH . "shrink.gif");
    $obj_FB_tmpl->displayParsedTemplate('jb-header');

    //BEGIN: PROFILEBOX
    if (file_exists(JB_ABSTMPLTPATH . '/plugin/profilebox/profilebox.php')) {
        include (JB_ABSTMPLTPATH . '/plugin/profilebox/profilebox.php');
        }
    else {
        include (JB_ABSPATH . '/template/default/plugin/profilebox/profilebox.php');
        }
    //FINISH: PROFILEBOX

    switch ($func)
    {
        case 'who':
            if (file_exists(JB_ABSTMPLTPATH . '/plugin/who/who.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/who/who.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/who/who.php');
                }

            break;

        #########################################################################################
        case 'announcement':
            if (file_exists(JB_ABSTMPLTPATH . '/plugin/announcement/announcement.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/announcement/announcement.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/announcement/announcement.php');
                }

            break;

        #########################################################################################
        case 'stats':
            if (file_exists(JB_ABSTMPLTPATH . '/plugin/stats/stats.class.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/stats/stats.class.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/stats/stats.class.php');
                }

            if (file_exists(JB_ABSTMPLTPATH . '/plugin/stats/stats.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/stats/stats.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/stats/stats.php');
                }

            break;

        #########################################################################################
        case 'fbprofile':
            if (file_exists(JB_ABSTMPLTPATH . '/plugin/fbprofile/fbprofile.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/fbprofile/fbprofile.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/fbprofile/fbprofile.php');
                }

            break;

        #########################################################################################
        case 'userlist':
            if (file_exists(JB_ABSTMPLTPATH . '/plugin/userlist/userlist.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/userlist/userlist.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/userlist/userlist.php');
                }

            break;

        #########################################################################################
        case 'post':
            if (file_exists(JB_ABSTMPLTPATH . '/smile.class.php')) {
                include (JB_ABSTMPLTPATH . '/smile.class.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/smile.class.php');
                }

            if (file_exists(JB_ABSTMPLTPATH . '/post.php')) {
                include (JB_ABSTMPLTPATH . '/post.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/post.php');
                }

            break;

        #########################################################################################
        case 'view':
            if (file_exists(JB_ABSTMPLTPATH . '/smile.class.php')) {
                include (JB_ABSTMPLTPATH . '/smile.class.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/smile.class.php');
                }

            if (file_exists(JB_ABSTMPLTPATH . '/view.php')) {
                include (JB_ABSTMPLTPATH . '/view.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/view.php');
                }

            break;

        #########################################################################################
        case 'faq':
            if (file_exists(JB_ABSTMPLTPATH . '/faq.php')) {
                include (JB_ABSTMPLTPATH . '/faq.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/faq.php');
                }

            break;

        #########################################################################################
        case 'showcat':
            if (file_exists(JB_ABSTMPLTPATH . '/smile.class.php')) {
                include (JB_ABSTMPLTPATH . '/smile.class.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/smile.class.php');
                }

            if (file_exists(JB_ABSTMPLTPATH . '/showcat.php')) {
                include (JB_ABSTMPLTPATH . '/showcat.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/showcat.php');
                }

            break;

        #########################################################################################
        case 'listcat':
            if (file_exists(JB_ABSTMPLTPATH . '/listcat.php')) {
                include (JB_ABSTMPLTPATH . '/listcat.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/listcat.php');
                }

            break;

        #########################################################################################
        case 'review':
            if (file_exists(JB_ABSTMPLTPATH . '/smile.class.php')) {
                include (JB_ABSTMPLTPATH . '/smile.class.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/smile.class.php');
                }

            if (file_exists(JB_ABSTMPLTPATH . '/moderate_messages.php')) {
                include (JB_ABSTMPLTPATH . '/moderate_messages.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/moderate_messages.php');
                }

            break;

        #########################################################################################
        case 'rules':
            include (JB_ABSSOURCESPATH . 'fb_rules.php');

            break;

        #########################################################################################

        case 'userprofile':
            if (file_exists(JB_ABSTMPLTPATH . '/smile.class.php')) {
                include (JB_ABSTMPLTPATH . '/smile.class.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/smile.class.php');
                }

            if (file_exists(JB_ABSTMPLTPATH . '/plugin/myprofile/myprofile.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/myprofile/myprofile.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/myprofile/myprofile.php');
                }

            break;

        #########################################################################################
        case 'myprofile':
            if (file_exists(JB_ABSTMPLTPATH . '/smile.class.php')) {
                include (JB_ABSTMPLTPATH . '/smile.class.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/smile.class.php');
                }

            if (file_exists(JB_ABSTMPLTPATH . '/plugin/myprofile/myprofile.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/myprofile/myprofile.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/myprofile/myprofile.php');
                }

            break;

        #########################################################################################
        case 'report':
            if (file_exists(JB_ABSTMPLTPATH . '/plugin/report/report.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/report/report.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/report/report.php');
                }

            break;

        #########################################################################################
        case 'uploadavatar':
            if (file_exists(JB_ABSTMPLTPATH . '/plugin/myprofile/myprofile_avatar_upload.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/myprofile/myprofile_avatar_upload.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/myprofile/myprofile_avatar_upload.php');
                }

            break;

        #########################################################################################
        case 'latest':
        case 'mylatest':
            if (file_exists(JB_ABSTMPLTPATH . '/latestx.php')) {
                include (JB_ABSTMPLTPATH . '/latestx.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/latestx.php');
                }

            break;

        #########################################################################################
        case 'search':
            require_once (JB_ABSSOURCESPATH . 'fb_search.class.php');

            $searchword = mosGetParam($_REQUEST, 'searchword', '');

            $obj_FB_search = &new jbSearch($database, $searchword, $my_id, $limitstart, $fbConfig->messages_per_page_search);
            $obj_FB_search->show();
            break;

        //needs work ... still in progress
        case 'advsearch':
            if (file_exists(JB_ABSTMPLTPATH . '/plugin/advancedsearch/advsearch.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/advancedsearch/advsearch.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/advancedsearch/advsearch.php');
                }

            break;

        case 'advsearchresult':
            if (file_exists(JB_ABSTMPLTPATH . '/plugin/advancedsearch/advsearchresult.php')) {
                include (JB_ABSTMPLTPATH . '/plugin/advancedsearch/advsearchresult.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/plugin/advancedsearch/advsearchresult.php');
                }

            break;

        #########################################################################################
        case 'markthisread':
            // get all already read topics
            $database->setQuery("SELECT readtopics FROM #__fb_sessions WHERE userid=$my_id");
            $allreadyRead = $database->loadResult();
            	check_dberror("Unable to load read topics.");
            /* Mark all these topics read */
            $database->setQuery("SELECT thread FROM #__fb_messages WHERE catid=$catid and thread not in ('$allreadyRead') GROUP BY THREAD");
            $readForum = $database->loadObjectList();
            	check_dberror("Unable to load messages.");
            $readTopics = '--';

            foreach ($readForum as $rf) {
                $readTopics = $readTopics . ',' . $rf->thread;
                }

            $readTopics = str_replace('--,', '', $readTopics);

            if ($allreadyRead != "") {
                $readTopics = $readTopics . ',' . $allreadyRead;
                }

            $database->setQuery("UPDATE #__fb_sessions set readtopics='$readTopics' WHERE userid=$my_id");
            $database->query();
            	check_dberror('Unable to update readtopics in session table.');

            echo "<script> alert('" . _GEN_FORUM_MARKED . "'); window.history.go(-1); </script>\n";
            break;

        #########################################################################################
        case 'karma':
            include (JB_ABSSOURCESPATH . 'fb_karma.php');

            break;

        #########################################################################################
        case 'bulkactions':
            switch ($do)
            {
                case "bulkDel":
                    FBTools::fbDeletePosts( $is_Moderator, $return);

                    break;

                case "bulkMove":
                    FBTools::fbMovePosts($catid, $is_Moderator, $return);
                    break;
            }

            break;

        ######################

        /*    template chooser    */
        case "templatechooser":
            $fb_user_template = strval(mosGetParam($_COOKIE, 'fb_user_template', ''));

            $fb_user_img_template = strval(mosGetParam($_REQUEST, 'fb_user_img_template', $fb_user_img_template));
            $fb_change_template = strval(mosGetParam($_REQUEST, 'fb_change_template', $fb_user_template));
            $fb_change_img_template = strval(mosGetParam($_REQUEST, 'fb_change_img_template', $fb_user_img_template));

            if ($fb_change_template)
            {
                // clean template name
                $fb_change_template = preg_replace('#\W#', '', $fb_change_template);

                if (strlen($fb_change_template) >= 40) {
                    $fb_change_template = substr($fb_change_template, 0, 39);
                    }

                // check that template exists in case it was deleted
                if (file_exists($mosConfig_absolute_path . '/components/com_fireboard/template/' . $fb_change_template . '/forum.css'))
                {
                    $lifetime = 60 * 10;
                    $fb_current_template = $fb_change_template;
                    setcookie('fb_user_template', "$fb_change_template", time() + $lifetime);
                }
                else {
                    setcookie('fb_user_template', '', time() - 3600);
                    }
            }

            if ($fb_change_img_template)
            {
                // clean template name
                $fb_change_img_template = preg_replace('#\W#', '', $fb_change_img_template);

                if (strlen($fb_change_img_template) >= 40) {
                    $fb_change_img_template = substr($fb_change_img_template, 0, 39);
                    }

                // check that template exists in case it was deleted
                if (file_exists($mosConfig_absolute_path . '/components/com_fireboard/template/' . $fb_change_img_template . '/forum.css'))
                {
                    $lifetime = 60 * 10;
                    $fb_current_img_template = $fb_change_img_template;
                    setcookie('fb_user_img_template', "$fb_change_img_template", time() + $lifetime);
                }
                else {
                    setcookie('fb_user_img_template', '', time() - 3600);
                    }
            }

            mosRedirect (sefRelToAbs(JB_LIVEURLREL));
            break;

        #########################################################################################
        case 'credits':
            include (JB_ABSSOURCESPATH . 'fb_credits.php');

            break;

        #########################################################################################
        default:
            if (file_exists(JB_ABSTMPLTPATH . '/listcat.php')) {
                include (JB_ABSTMPLTPATH . '/listcat.php');
                }
            else {
                include (JB_ABSPATH . '/template/default/listcat.php');
                }

            break;
    } //hctiws

    // Bottom Module
    if (mosCountModules('fb_bottom'))
    {
?>

        <div class = "bof-bottom-modul">
            <?php
            mosLoadModules('fb_bottom', -2);
            ?>
        </div>

<?php
    }

    // Credits
    echo '<div class="fb_credits"> ';
    if ($fbConfig->enablerss)
    {
    	$mainframe->addCustomHeadTag('<link rel="alternate" type="application/rss+xml" title="'._LISTCAT_RSS.'" href="'.sefRelToAbs(JB_LIVEURLREL.'&amp;func=fb_rss&amp;no_html=1').'")/>');
        echo fb_link::GetRSSLink('<img class="rsslink" src="' . JB_URLEMOTIONSPATH . 'rss.gif" border="0" alt="' . _LISTCAT_RSS . '" title="' . _LISTCAT_RSS . '" />');
    }
    echo '</div>';

    // display footer
    $obj_FB_tmpl->readTemplatesFromFile("footer.html");
    $obj_FB_tmpl->displayParsedTemplate('fb-footer');
} //else

// Just for debugging and performance analysis
$mtime = explode(" ", microtime());
$tend = $mtime[1] + $mtime[0];
$tpassed = ($tend - $tstart);
//echo $tpassed;
?>
