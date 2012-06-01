<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: docman.php 575 2008-01-27 18:00:56Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

include_once dirname(__FILE__) . '/docman.html.php';

switch ($task) {
    case 'stats':
        showStatistics();
        break;

    case 'credits' :
        showCredits();
        break;

    case 'sampledata':
        installSampleData();
        break;

    // DOClink
    case "doclink":
        require_once($_DOCMAN->getPath('includes_f', 'doclink'));
        showDoclink();
        break;

    case "doclink-listview":
        require_once($_DOCMAN->getPath('includes_f', 'doclink'));
        showListview();
        break;

    // CPanel
    case 'cpanel':
    default:
        showCPanel();
}

function showCPanel()
{
    HTML_DMDocman::showCPanel();
}

function showCredits()
{
    global $mosConfig_absolute_path;

    ob_start();
    include( $mosConfig_absolute_path.'/administrator/components/com_docman/CHANGELOG.php' );
    $changelog = ob_get_clean();

    HTML_DMDocman::showCredits( $changelog );
}

function showStatistics()
{
    global $database;
    $query = "SELECT id, catid , dmname , dmcounter from #__docman " .
            // removed to fix artf7530
            // "\n WHERE dmowner=-1 OR dmowner=0 " .
            "\n ORDER BY dmcounter DESC";
    $database->setQuery($query, 0, 50);
    $row = $database->loadObjectList();
    HTML_DMDocman::showStatistics($row);
}

/**
 * Add sample category, file and document
 */
function installSampleData(){
    global $database, $my, $mosConfig_absolute_path;
    $dmdoc  = $mosConfig_absolute_path.DS.'dmdocuments';
    $img    = $mosConfig_absolute_path.DS.'administrator'.DS.'components'.DS.'com_docman'.DS.'images';
    $now = date('Y-m-d H:i:s');

    // get all super admins
    $database->setQuery("SELECT id FROM `#__users` WHERE `usertype`='Super Administrator'");
    $admins = implode(',', $database->loadResultArray() );

    // add sample group
    $group = new mosDMGroups($database);
    $group->groups_name         = _DML_SAMPLE_GROUP;
    $group->groups_description  = _DML_SAMPLE_GROUP_DESC;
    $group->groups_access       = 1;
    $group->groups_members      = $admins;
    if(!$group->store())
    {
    	mosRedirect('index2.php?option=com_docman', 'Error: installSampleData, $groups->store()');
    }
    $groupid = (-1 * $database->insertid()) + _DM_PERMIT_GROUP;

    // add sample license
    $license = new mosDMLicenses($database);
    $license->name      = _DML_SAMPLE_LICENSE;
    $license->license   = _DML_SAMPLE_LICENSE_DESC;
    if(!$license->store())
    {
        mosRedirect('index2.php?option=com_docman', 'Error: installSampleData, $license->store()');
    }
    $licenseid = $database->insertid();

    // add a sample file
    if ( !file_exists($dmdoc.DS.'sample_file.png')) {
       @copy($img.DS.'dm_logo.png', $dmdoc.DS._DML_SAMPLE_FILENAME);
    }

    // add sample category
    $category = new mosDMCategory($database);
    $category->parent_id        = 0;
    $category->title            = _DML_SAMPLE_CATEGORY;
    $category->name             = _DML_SAMPLE_CATEGORY;
    $category->image            = 'clock.jpg';
    $category->section          = 'com_docman';
    $category->image_position   = 'left';
    $category->description      = _DML_SAMPLE_CATEGORY_DESC;
    $category->published        = 1;
    $category->checked_out      = 0;
    $category->checked_out_time = '0000-00-00 00:00:00';
    $category->editor           = NULL;
    $category->ordering         = 1;
    $category->access           = 0;
    $category->count            = 0;
    $category->params           = '';
    if(!$category->store())
    {
        mosRedirect('index2.php?option=com_docman', 'Error: installSampleData, $category->store()');
    }
    $catid = $database->insertId();

    // add sample document
    $doc = new mosDMDocument($database);
    $doc->catid             = $catid;
    $doc->dmname            = _DML_SAMPLE_DOC;
    $doc->dmdescription     = _DML_SAMPLE_DOC_DESC;
    $doc->dmdate_published  = $now;
    $doc->dmowner           = -1;
    $doc->dmfilename        = _DML_SAMPLE_FILENAME;
    $doc->published         = 1;
    $doc->dmurl             = '';
    $doc->dmcounter         = 0;
    $doc->checked_out       = 0;
    $doc->checked_out_time  = '0000-00-00 00:00:00';
    $doc->approved          = 1;
    $doc->dmthumbnail       = '';
    $doc->dmlastupdateon    = $now;
    $doc->dmlastupdateby    = $my->id;
    $doc->dmsubmitedby      = $my->id;
    $doc->dmmantainedby     = $groupid;
    $doc->dmlicense_id      = $licenseid;
    $doc->dmlicense_display = 1;
    $doc->access            = 0;
    $doc->attribs           = 'crc_checksum=\nmd5_checksum=';
    if(!$doc->store())
    {
        mosRedirect('index2.php?option=com_docman', 'Error: installSampleData, $doc->store()');
    }

    mosRedirect('index2.php?option=com_docman', _DML_SAMPLE_COMPLETED);
}
