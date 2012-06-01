<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: admin.docman.php 608 2008-02-18 13:31:26Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

// ensure user has access to this function
if (!($acl -> acl_check('administration', 'edit', 'users', $my -> usertype, 'components', 'all') | $acl -> acl_check('administration', 'edit', 'users', $my -> usertype, 'components', 'com_docman'))){
    mosRedirect('index2.php', _DML_NOT_AUTHORIZED);
}

require_once $mainframe->getPath('admin_html');
require_once $mainframe->getPath('class');

global $_DOCMAN, $_DMUSER, $cid, $gid, $id, $pend, $updatedoc, $sort, $view_type, $css, $task, $option;

$_DOCMAN = new dmMainFrame();
$_DOCMAN->loadLanguage('backend');

$_DMUSER = $_DOCMAN->getUser();

require_once $_DOCMAN->getPath('classes', 'html');
require_once($_DOCMAN->getPath('classes', 'utils'));
require_once($_DOCMAN->getPath('classes', 'token'));


$cid = mosGetParam($_REQUEST, 'cid', array()) ;
if (!is_array($cid)){
    $cid = array(0);
}
$gid = (int) mosGetParam($_REQUEST, 'gid', '0');


// retrieve some expected url (or form) arguments
$pend      = mosGetParam($_REQUEST, 'pend', 'no');
$updatedoc = mosGetParam($_POST, 'updatedoc', '0');
$sort      = mosGetParam($_REQUEST, 'sort', '0');
$view_type = mosGetParam($_REQUEST, 'view', 1);
if( !isset($section)) {
    global $section;
    $section =  mosGetParam($_REQUEST, 'section', '');
}

// add stylesheet
$css = $mosConfig_live_site.'/administrator/components/com_docman/includes/docman.css';
$mainframe->addCustomHeadTag( '<link rel="stylesheet" type="text/css" media="all" href="'.$css.'" id="docman_stylesheet" />' );

// Little hack to make sure mosmsg is always displayed:
if( !isset( $_SERVER['HTTP_REFERER'] )) {
	$_SERVER['HTTP_REFERER'] = $mosConfig_live_site . '/administrator/index2.php?option=com_docman';
}

// execute task
if (($task == 'cpanel') || ($section == null)){
   include_once($_DOCMAN -> getPath('includes', 'docman'));
}else{
    include_once($_DOCMAN -> getPath('includes', $section));
}

