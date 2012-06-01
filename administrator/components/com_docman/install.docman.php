<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: install.docman.php 628 2008-02-25 00:36:53Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'install.docman.helper.php');

function com_install() {
    global $mosConfig_absolute_path, $_DOCMAN;

    $return = true;

    // Logo
    DMInstallHelper::showLogo();

    if(!DMInstallHelper::checkWritable())
    {
        $link = defined('_DM_J15') ?
            'index.php?option=com_installer&type=components&task=manage&mosmsg=Select+DOCman+and+click+uninstall' :
            'index2.php?option=com_installer&element=component&mosmsg=Select+DOCman+and+click+uninstall';
        // this should get the attention of people who prefer to ignore error messages!
        ?><p style="font-size:200%">Installation failed! Please <a href="<?php echo $link?>">click here to uninstall docman</a>.
        Next, make the folders list above writable and try again.</p>
        <?php
        $return = false;
    }

    // Upgrade tables
    DMInstallHelper::upgradeTables();

    // Files
    DMInstallHelper::fileOperations();

    // modules
    if( defined('_DM_J15')) {
    	DMInstallHelper::moduleFilesJ15();
    } else {
        DMInstallHelper::moduleFiles();
    }
    DMInstallHelper::moduleDB();

    //plugins
    DMInstallHelper::pluginFiles();
    DMInstallHelper::pluginDB();

    // index.html files
    $paths = array( 'components'.DS.'com_docman',
                    'administrator'.DS.'components'.DS.'com_docman',
                    'mambots'.DS.'docman',
                    'dmdocuments'  );
    foreach ( $paths as $path ) {
        $path = $mosConfig_absolute_path.DS.$path;;
        DMInstallHelper::createIndex( $path );
    }

    // Update menus
    DMInstallHelper::removeAdminMenuImages();
    DMInstallHelper::setAdminMenuImages();

    // Link to add sample data
    DMInstallHelper::cpanel();

    return $return;
}
