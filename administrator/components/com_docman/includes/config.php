<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: config.php 638 2008-03-01 12:49:09Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

require_once ($_DOCMAN->getPath('classes', 'utils'));

include_once dirname(__FILE__) . '/config.html.php';
include_once dirname(__FILE__) . '/defines.php';

switch ($task) {
    case "cancel":
        mosRedirect("index2.php?option=com_docman");
        break;
    case "apply":
    case "save":
        saveConfig();
        break;
    case "show" :
    default :
        showConfig($option);
        break;
}

function showConfig($option)
{
    global $mosConfig_absolute_path, $_DOCMAN;

    // disable the main menu to force user to use buttons
    $_REQUEST['hidemainmenu']=1;

    $std_inp = 'style="width: 125px" size="2"';
    $std_opt = 'size="2"';

    // Create the 'yes-no' radio options
    foreach(array('isDown' , 'display_license', 'log' , 'emailgroups',
            'user_all', 'fname_lc' , 'overwrite' , 'security_anti_leech',
            'trimwhitespace', 'process_bots', 'individual_perm', 'hide_remote'
            )
        AS $field) {
        $lists[ $field ] = mosHTML::yesnoRadioList($field, $std_opt,
            $_DOCMAN->getCfg($field , 0));
    }

    $guest[] = mosHTML::makeOption(_DM_GRANT_NO , _DML_CFG_GUEST_NO);
    $guest[] = mosHTML::makeOption(_DM_GRANT_X , _DML_CFG_GUEST_X);
    $guest[] = mosHTML::makeOption(_DM_GRANT_RX , _DML_CFG_GUEST_RX);
    $lists['guest'] = mosHTML::selectList($guest, 'registered',
        '' , 'value', 'text',
        $_DOCMAN->getCfg('registered', _DM_GRANT_RX));

  	$upload =& new dmHTML_UserSelect('user_upload', 1 );
    $upload->addOption(_DML_CFG_USER_UPLOAD, _DM_PERMIT_NOOWNER);
    $upload->addGeneral(_DML_NO_USER_ACCESS, 'all');
    $upload->addMamboGroups();
    $upload->addDocmanGroups();
    $upload->addUsers();
    $upload->setSelectedValues(array($_DOCMAN->getCfg('user_upload', 0)));
    $lists['user_upload'] = $upload;

    $publish =& new dmHTML_UserSelect('user_publish', 1 );
    $publish->addOption(_DML_CFG_USER_PUBLISH, _DM_PERMIT_NOOWNER);
    $publish->addGeneral(_DML_AUTO_PUBLISH, 'all');
    $publish->addMamboGroups();
    $publish->addDocmanGroups();
    $publish->addUsers();
    $publish->setSelectedValues(array($_DOCMAN->getCfg('user_publish', 0)));
    $lists['user_publish'] = $publish;

    $approve =& new dmHTML_UserSelect('user_approve', 1 );
    $approve->addOption(_DML_CFG_USER_APPROVE, _DM_PERMIT_NOOWNER);
    $approve->addGeneral(_DML_AUTO_APPROVE, 'all');
    $approve->addMamboGroups();
    $approve->addDocmanGroups();
    $approve->addUsers();
    $approve->setSelectedValues(array($_DOCMAN->getCfg('user_approve', 0)));
    $lists['user_approve'] = $approve;

    $viewer =& new dmHTML_UserSelect('default_viewer', 1 );
    $viewer->addOption(_DML_SELECT_USER, _DM_PERMIT_NOOWNER);
    $viewer->addGeneral(_DML_EVERYBODY);
    $viewer->addMamboGroups();
    $viewer->addDocmanGroups();
    $viewer->addUsers();
    $viewer->setSelectedValues(array($_DOCMAN->getCfg('default_viewer', 0)));
    $lists['default_viewer'] = $viewer;

    $maintainer =& new dmHTML_UserSelect('default_editor', 1 );
    $maintainer->addOption(_DML_SELECT_USER, _DM_PERMIT_NOOWNER);
    $maintainer->addGeneral(_DML_NO_USER_ACCESS);
    $maintainer->addMamboGroups();
    $maintainer->addDocmanGroups();
    $maintainer->addUsers();
    $maintainer->setSelectedValues(array($_DOCMAN->getCfg('default_editor', 0)));
    $lists['default_maintainer'] = $maintainer;

    $author_can = array();
    $author_can[] = mosHTML::makeOption(_DM_AUTHOR_NONE , _DML_CFG_AUTHOR_NONE);
    $author_can[] = mosHTML::makeOption(_DM_AUTHOR_CAN_READ , _DML_CFG_AUTHOR_READ);
    $author_can[] = mosHTML::makeOption(_DM_AUTHOR_CAN_EDIT , _DML_CFG_AUTHOR_BOTH);
    $lists['creator_can'] = mosHTML::selectList($author_can, 'author_can',
        '', 'value', 'text',
        $_DOCMAN->getCfg('author_can', _DM_AUTHOR_CAN_EDIT));

    // Special compatibility mode
    /** REMOVED 1.4.0RC2
    $specialcompat = array();
    $specialcompat[] = mosHTML::makeOption(_DM_SPECIALCOMPAT_DM13, _DML_CFG_SPECIALCOMPAT_DM13);
    $specialcompat[] = mosHTML::makeOption(_DM_SPECIALCOMPAT_J10, _DML_CFG_SPECIALCOMPAT_J10);
    $lists['specialcompat'] = mosHTML::selectList($specialcompat, 'specialcompat',
        '', 'value', 'text',
        $_DOCMAN->getCfg('specialcompat', _DM_SPECIALCOMPAT_DM13));
        */

    // Blank handling for filenames
    $blanks[] = mosHTML::makeOption('0', _DML_CFG_ALLOWBLANKS);
    $blanks[] = mosHTML::makeOption('1', _DML_CFG_REJECT);
    $blanks[] = mosHTML::makeOption('2', _DML_CFG_CONVERTUNDER);
    $blanks[] = mosHTML::makeOption('3', _DML_CFG_CONVERTDASH);
    $blanks[] = mosHTML::makeOption('4', _DML_CFG_REMOVEBLANKS);
    $lists['fname_blank'] = mosHTML::selectList($blanks, 'fname_blank',
        '', 'value', 'text',
        $_DOCMAN->getCfg('fname_blank', 0));

    // assemble icon sizes
    $size[] = mosHTML::makeOption('0', '16x16 pixel');
    $size[] = mosHTML::makeOption('1', '32x32 pixel');
    $lists['icon_size'] = mosHTML::selectList($size, 'icon_size',
        $std_inp, 'value', 'text',
        $_DOCMAN->getCfg('icon_size', 0));

    // assemble icon themes
    /** REMOVED 1.4.0RC2
    $docsFiles = DOCMAN_Compat::mosReadDirectory("$mosConfig_absolute_path/components/com_docman/themes/");
    $docs = array(mosHTML::makeOption('', ''));

    foreach($docsFiles as $file) {
        if ($file <> "index.html")
            $docs[] = mosHTML::makeOption($file);
    }
    **/

    // assemble displaying order
    $order[] = mosHTML::makeOption('name', _DML_NAME);
    $order[] = mosHTML::makeOption('date', _DML_DATE);
    $order[] = mosHTML::makeOption('hits', _DML_HITS);
    $lists['default_order'] = mosHTML::selectList($order, 'default_order',
        'style="width: 125px"', 'value', 'text',
        $_DOCMAN->getCfg('default_order', 'name'));
    $order2[] = mosHTML::makeOption('ASC', _DML_ASCENDENT);
    $order2[] = mosHTML::makeOption('DESC', _DML_DESCENDENT);
    $lists['default_order2'] = mosHTML::selectList($order2, 'default_order2',
        'style="width: 125px"', 'value', 'text',
        $_DOCMAN->getCfg('default_order2', 'DESC'));

    // Assemble the methods we allow
    $methods = array();
    $methods[] = mosHTML::makeOption('http' , _DML_OPTION_HTTP);
    $methods[] = mosHTML::makeOption('link' , _DML_OPTION_LINK);
    $methods[] = mosHTML::makeOption('transfer' , _DML_OPTION_XFER);
    $default_methods = $_DOCMAN->getCfg('methods', array('http'));
    // ugh ... all because they like arrays of classes....
    $class_methods = array();
    foreach($default_methods as $a_method) {
        $class_methods[] = mosHTML::makeOption($a_method);
    }

    $lists['methods'] = mosHTML::selectList($methods, 'methods[]',
        'size="3" multiple', 'value', 'text', $class_methods);

    HTML_DMConfig::configuration($lists);
    $_DOCMAN->saveConfig(); // Save any defaults we created...

}

function saveConfig()
{
    DOCMAN_token::check() or die('Invalid Token');

    global $_DOCMAN, $task;

    $_POST = DOCMAN_Utils::stripslashes($_POST);

    $docmanMax = DOCMAN_Utils::text2number($_POST['maxAllowed']);
    $_POST[ 'maxAllowed'] = $docmanMax;

    $sysUploadMax = DOCMAN_Utils::text2number(ini_get('upload_max_filesize'));
    $sysPostMax = DOCMAN_Utils::text2number(ini_get('post_max_size'));
    $max = min($sysUploadMax , $sysPostMax);

    if ($docmanMax < 0) {
        mosRedirect("index2.php?option=com_docman&section=config", _DML_CONFIG_ERROR_UPLOAD);
    }

    $override_edit = _DM_ASSIGN_NONE;
    $author = mosGetParam($_POST, 'assign_edit_author', 0);
    $editor = mosGetParam($_POST, 'assign_edit_editor', 0);
    if ($author) {
        $override_edit = _DM_ASSIGN_BY_AUTHOR;
    }
    if ($editor) {
        $override_edit = _DM_ASSIGN_BY_EDITOR;
    }
    if ($author && $editor) {
        $override_edit = _DM_ASSIGN_BY_AUTHOR_EDITOR;
    }
    $_POST['editor_assign'] = $override_edit;
    unset($_POST['assign_edit_author']);
    unset($_POST['assign_edit_editor']);

    $override_down = _DM_ASSIGN_NONE;
    $author = mosGetParam($_POST, 'assign_download_author', 0);
    $editor = mosGetParam($_POST, 'assign_download_editor', 0);
    if ($author) {
        $override_down = _DM_ASSIGN_BY_AUTHOR;
    }
    if ($editor) {
        $override_down = _DM_ASSIGN_BY_EDITOR;
    }
    if ($author && $editor) {
        $override_down = _DM_ASSIGN_BY_AUTHOR_EDITOR;
    }
    $_POST['reader_assign'] = $override_down;
    unset($_POST['assign_download_author']);
    unset($_POST['assign_download_editor']);

    foreach($_POST as $key => $value) {
        $_DOCMAN->setCfg($key, $value);
    }

    if ($_DOCMAN->saveConfig()) {
        if ($max < $docmanMax) {
            mosRedirect("index2.php?option=com_docman&section=config", _DML_CONFIG_WARNING . DOCMAN_UTILS::number2text($max));
        } else {
            $section = ($task=='apply') ? '&section=config' : '';
            mosRedirect('index2.php?option=com_docman'.$section, _DML_CONFIG_UPDATED);
        }
    } else {
        mosRedirect("index2.php?option=com_docman&section=config", _DML_CONFIG_ERROR);
    }
}