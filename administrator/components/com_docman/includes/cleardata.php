<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: cleardata.php 608 2008-02-18 13:31:26Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

include_once dirname(__FILE__) . DS.'cleardata.html.php';
require_once($_DOCMAN->getPath('classes', 'cleardata'));

switch ($task) {
    case 'remove':
        clearData( $cid );
        break;

    default:
    case 'show':
        showClearData();
}

function clearData( $cid = array() )
{
    DOCMAN_token::check() or die('Invalid Token');

    $msgs=array();

    $cleardata = new DOCMAN_Cleardata( $cid );
    $cleardata->clear();
    $rows = & $cleardata->getList();
    foreach( $rows as $row ){
        $msgs[] = $row->msg;
    }
    mosRedirect( 'index2.php?option=com_docman&section=cleardata', implode(' | ', $msgs));
}

function showClearData(){
    $cleardata = new DOCMAN_Cleardata();
    $rows = & $cleardata->getList();
	HTML_DMClear::showClearData( $rows );
}
