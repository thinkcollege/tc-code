<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: uninstall.docman.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'install.docman.helper.php');

function com_uninstall() {
    global $_DOCMAN;

    // remove modules
    if( defined('_DM_J15')) {
        DMInstallHelper::moduleFilesJ15('delete');
    } else {
        DMInstallHelper::moduleFiles('delete');
    }

    DMInstallHelper::pluginFiles('delete');
    DMInstallHelper::pluginDb('delete');

    // if there's no more data, we remove the tables
    if( DMInstallHelper::cntDbRecords() == 0 ) {
        DMInstallHelper::removeTables();
    }

    // delete the dmdocuments folder if it's empty
    if ( DMInstallHelper::cntFiles() == 0 ) {
    	DMInstallHelper::removeDmdocuments();
    }
}