<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: install.docman.helper.php 651 2008-03-20 20:33:15Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if( !defined('DS') ) define('DS', DIRECTORY_SEPARATOR);


require_once( dirname(__FILE__).DS.'docman.class.php');
global $_DOCMAN, $_DMUSER;
$_DOCMAN = new dmMainFrame();
$_DMUSER = $_DOCMAN->getUser();
require_once($_DOCMAN->getPath('classes', 'compat'));

if(defined('_DM_J15')) {
    define( '_DM_INSTALLER_ICONPATH', JURI::root().'administrator/components/com_docman/images/');
} else {
    define( '_DM_INSTALLER_ICONPATH', '../administrator/components/com_docman/images/');
}

/**
 * Helper functions for the installer
 * @static
 */
class DMInstallHelper {
    function checkWritable()
    {
        global $mosConfig_absolute_path;

        if(defined('_DM_J15')) {
            $plugins = 'plugins';
        } else {
            $plugins = 'mambots';
        }

        $paths = array(
            DS,
            DS.'administrator'.DS.'modules'.DS,
            DS.$plugins.DS
        );

        clearstatcache();
        $msgs = array();
        foreach($paths as $path)
        {
            if(!is_writable($mosConfig_absolute_path.$path))
            {
            	$msgs[] =  '<font color="red">Unwriteable: &lt;joomla root&gt;'.$path . '</font><br />';
            }
        }

        if(count($msgs))
        {
            echo '<br /><p style="font-size:200%">';
        	echo implode("\n", $msgs);
            echo '</p>';
            return false;
        }

        return true;
    }

    function getDefaultFiles(){
        return array( '.htaccess', 'index.html' );
    }

    function getComponentId(){
        static $id;

        if( !$id ) {
            global $database;
            $database->setQuery("SELECT id FROM #__components WHERE name= 'DOCman'");
            $id =$database->loadResult();
        }
        return $id;
    }

    function removeAdminMenuImages(){
        global $database;

        $id = DMInstallHelper::getComponentId();

        $database->setQuery("UPDATE #__components SET admin_menu_img = '"._DM_INSTALLER_ICONPATH."dm_spacer_16.png' WHERE parent = $id");
        $database->query();
    }

    function setAdminMenuImages(){
      global $database;

        $id = DMInstallHelper::getComponentId();

        // Main mennu
        $database->setQuery("UPDATE #__components SET admin_menu_img = '"._DM_INSTALLER_ICONPATH."dm_documents_16.png' WHERE id=$id");
        $database->query();

        // Submenus
        $submenus = array();
        $submenus[] = array( 'image' => _DM_INSTALLER_ICONPATH.'dm_cpanel_16.png', 'name'=>'Home' );
        $submenus[] = array( 'image' => _DM_INSTALLER_ICONPATH.'dm_files_16.png', 'name'=>'Files' );
        $submenus[] = array( 'image' => _DM_INSTALLER_ICONPATH.'dm_documents_16.png', 'name'=>'Documents' );
        $submenus[] = array( 'image' => _DM_INSTALLER_ICONPATH.'dm_categories_16.png', 'name'=>'Categories' );
        $submenus[] = array( 'image' => _DM_INSTALLER_ICONPATH.'dm_groups_16.png', 'name'=>'Groups' );
        $submenus[] = array( 'image' => _DM_INSTALLER_ICONPATH.'dm_licenses_16.png', 'name'=>'Licenses' );
        $submenus[] = array( 'image' => _DM_INSTALLER_ICONPATH.'dm_stats_16.png', 'name'=>'Statistics' );
        $submenus[] = array( 'image' => _DM_INSTALLER_ICONPATH.'dm_logs_16.png', 'name'=>'Download Logs' );
        $submenus[] = array( 'image' => _DM_INSTALLER_ICONPATH.'dm_config_16.png', 'name'=>'Configuration' );
        $submenus[] = array( 'image' => _DM_INSTALLER_ICONPATH.'dm_templates_16.png', 'name'=>'Themes' );

        foreach( $submenus as $submenu ){
          $database->setQuery("UPDATE #__components SET admin_menu_img = '".$submenu['image']."'"
                                . "\n WHERE parent=$id AND name = '".$submenu['name']."';");
            $database->query();
        }
    }

    function moduleFiles( $action = 'move' ){
        global $mosConfig_absolute_path;

        $src = $mosConfig_absolute_path.DS.'administrator'.DS.'components'.DS.'com_docman'.DS.'modules'.DS.'admin';
        $dest = $mosConfig_absolute_path.DS.'administrator'.DS.'modules';

        $files = array(
                    'mod_docman_approval.php'   =>'mod_docman_approval.php',
                    'mod_docman_approval.xml.php'=>'mod_docman_approval.xml',
                    'mod_docman_latest.php'     =>'mod_docman_latest.php',
                    'mod_docman_latest.xml.php'     =>'mod_docman_latest.xml',
                    'mod_docman_logs.php'       =>'mod_docman_logs.php',
                    'mod_docman_logs.xml.php'       =>'mod_docman_logs.xml',
                    'mod_docman_news.php'       =>'mod_docman_news.php',
                    'mod_docman_news.xml.php'       =>'mod_docman_news.xml',
                    'mod_docman_top.php'        =>'mod_docman_top.php',
                    'mod_docman_top.xml.php'        =>'mod_docman_top.xml' );

        switch ($action) {
            case 'delete':
                foreach( $files as $file ){
                    @unlink( $dest.DS.$file );
                }

                break;
        	default:
            case 'move':
                foreach( $files as $orig=>$file ){
                    @rename( $src.DS.$orig, $dest.DS.$file );
                }
                break;
        }
    }

    // Modules each have their own dir in J1.5
    function moduleFilesJ15( $action = 'move' ){
        global $mosConfig_absolute_path;
        jimport('joomla.filesystem.file');

        $src = $mosConfig_absolute_path.DS.'administrator'.DS.'components'.DS.'com_docman'.DS.'modules'.DS.'admin';
        $dest = $mosConfig_absolute_path.DS.'administrator'.DS.'modules';

        $files = array(
                    'mod_docman_approval.php'   =>'mod_docman_approval.php',
                    'mod_docman_approval.xml.php'=>'mod_docman_approval.xml',
                    'mod_docman_latest.php'     =>'mod_docman_latest.php',
                    'mod_docman_latest.xml.php' =>'mod_docman_latest.xml',
                    'mod_docman_logs.php'       =>'mod_docman_logs.php',
                    'mod_docman_logs.xml.php'   =>'mod_docman_logs.xml',
                    'mod_docman_news.php'       =>'mod_docman_news.php',
                    'mod_docman_news.xml.php'   =>'mod_docman_news.xml',
                    'mod_docman_top.php'        =>'mod_docman_top.php',
                    'mod_docman_top.xml.php'    =>'mod_docman_top.xml' );

        switch ($action) {
            case 'delete':
                foreach( $files as $file ){
                    $moduledir = str_replace( array('.xml', '.php'), '', $file );
                    JFile::delete($dest.DS.$moduledir.DS.$file);
                }
                break;
            default:
            case 'move':
                foreach( $files as $orig=>$file ){
                    $moduledir = str_replace( array('.xml', '.php'), '', $file );
                    if( !file_exists($dest.DS.$moduledir)) {
                    	@mkdir($dest.DS.$moduledir);
                    }
                    @rename( $src.DS.$orig, $dest.DS.$moduledir.DS.$file );
                }
                break;
        }
    }

    function pluginFiles( $action = 'move' ){
        global $mosConfig_absolute_path;

        if(defined('_DM_J15')) {
        	$plugins = 'plugins';
        } else {
        	$plugins = 'mambots';
        }


        $src = $mosConfig_absolute_path.DS.'administrator'.DS.'components'.DS.'com_docman'.DS.'plugins';
        $dest = $mosConfig_absolute_path.DS.$plugins.DS.'docman';
        @mkdir($dest, 0755);

        $files = array(
                    'standardbuttons.php'     => 'standardbuttons.php',
                    'standardbuttons.xml.php' => 'standardbuttons.xml');

        switch ($action) {
            case 'delete':
                foreach( $files as $file ){
                    @unlink( $dest.DS.$file );
                }

                break;
            default:
            case 'move':
                foreach( $files as $orig=>$file ){
                    @rename( $src.DS.$orig, $dest.DS.$file );
                }
                break;
        }
    }


    function fileOperations(){
    	global $mosConfig_absolute_path;
        $root   = $mosConfig_absolute_path;
        $site   = $root.DS.'components'.DS.'com_docman';
        $admin  = $root.DS.'administrator'.DS.'components'.DS.'com_docman';
        $dmdoc  = $root.DS.'dmdocuments';

        @mkdir ($dmdoc, 0755);
        @rename($admin.DS.'htaccess.txt', $dmdoc.DS.'.htaccess' );
        @copy  ($site.DS.'index.html', $dmdoc.DS.'index.html');

        @chmod ($site, 0755);
        @chmod ($admin.DS.'classes'.DS.'DOCMAN_download.class.php', 0755);
        @chmod ($admin.DS.'classes'.DS.'DOCMAN_utils.php', 0755);

    }

    function showLogo(){
    	?><br />
        <table>
            <tr>
                <th><a href='index2.php?option=com_docman'><img border="0" alt="DOCman" src="components/com_docman/images/dm_logo.png" /></a></th>
            </tr>
        </table><?php
    }

    function cpanel(){
        global $mosConfig_live_site;

        ?><br /><br />
        <div class="cpanel">
            <div class="icon">
                <a href="index2.php?option=com_docman" style="text-decoration:none;">
                    <img border="0" align="top" alt="Home" src="<?php echo $mosConfig_live_site?>/administrator/components/com_docman/images/dm_cpanel_48.png"/>
                    <br />
                    <span>Home</span>
                </a>
            </div><br />
            <div class="icon">
                <a href="index2.php?option=com_docman&amp;task=sampledata" style="text-decoration:none;">
                    <img border="0" align="top" alt="Add Sample Data" src="<?php echo $mosConfig_live_site?>/administrator/components/com_docman/images/dm_newdocument_48.png"/>
                    <br />
                    <span>Add Sample Data</span>
                </a>
            </div>
        </div>
        <?php

    }

    /**
     * Count items in tables
     */
    function cntDbRecords() {
        global $database;
    	$cnt = array();
        $tables = DMInstallHelper::getTablesList();

        foreach( $tables as $table ){
            $database->setQuery("SELECT COUNT(*) FROM `$table`");
            $cnt[] = (int) $database->loadResult();
        }

        // count categories
        $database->setQuery("SELECT COUNT(*) FROM `#__categories` WHERE `section` = 'com_docman'");
        $cnt[] = (int) $database->loadResult();

        return array_sum($cnt);
    }

    function removeTables() {
        global $database;
        $tables = DMInstallHelper::getTablesList();

    	foreach( $tables as $table ){
            $database->setQuery("DROP TABLE `$table`");
            $database->query();
        }
    }

    function getTablesList(){
    	return array( '#__docman', '#__docman_groups', '#__docman_history', '#__docman_licenses', '#__docman_log');
    }

    /**
     * Count the number of files in /dmdocuments
     */
    function cntFiles(){
    	global $_DOCMAN;

        $files = DMInstallHelper::getDefaultFiles();
        $dir = DOCMAN_Compat::mosReadDirectory( $_DOCMAN->getCfg( 'dmpath' ) );
        return count( array_diff( $dir, $files ));
    }

    function removeDmdocuments(){
        global $_DOCMAN;

        $dmpath = $_DOCMAN->getCfg( 'dmpath' );

        $files = DMInstallHelper::getDefaultFiles();

    	foreach( $files as $file ) {
            @unlink ( $dmpath.DS.$file );
        }
        @rmdir( $dmpath );
    }

    /**
     * Create index.html files
     */
    function createIndex( $path ){
        // create index.html in the path
        DMInstallHelper::_createIndexFile( $path );

        if( ! file_exists($path)) {
        	return false;
        }
        // create index.html in subdirs
        $handle = opendir( $path );
        while ($file = readdir($handle)) {
            if ($file!='.' AND $file!='..') {
                $dir = $path.DS.$file;
                if( is_dir( $dir ) ) {
                    DMInstallHelper::createIndex( $dir );
                }
            }
        }
    }

    function _createIndexFile( $dir ) {
        @$handle = fopen($dir.DS.'index.html', 'w');
        @fwrite($handle, 'Restricted access');
    }

    function moduleDB(){
    	global $database, $_DOCMAN;

        // #__template_positions is removed in j15
        if(defined('_DM_J15')) {
        	return;
        }

        // add dmcpanel to the template positions
        $query = "SELECT COUNT(*) FROM #__template_positions WHERE position = 'dmcpanel'";
        $database->setQuery($query);
        if(!$database->loadResult()) {
        	$query = "INSERT INTO #__template_positions SET position = 'dmcpanel', description='DOCman'";
            $database->setQuery($query);
            $database->query();
        }

    }

     function pluginDB($action = 'insert'){
        global $database, $_DOCMAN;

        if(defined('_DM_J15')) {
            $table = '#__plugins';
        } else {
        	$table = '#__mambots';
        }

        switch($action) {
            case 'insert':
                $query = "INSERT INTO `$table` (`name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`)"
                    ."\n VALUES ('DOCman Standard Buttons', 'standardbuttons', 'docman', '0', '1', '1', '1', '0', '0', '0000-00-00 00:00:00', "
                    ." 'download=1\nview=1\ndetails=1\nedit=1\nmove=1\ndelete=1\nupdate=1\nreset=1\ncheckout=1\napprove=1\npublish=1')";
                break;
            case 'delete':
                $query = "DELETE FROM `$table` WHERE `element`='standardbuttons' AND `folder`='docman'";
                break;
        }

        $database->setQuery($query);
        $database->query();
    }

    /**
     * Upgrade tables from 1.3rc2/1.4beta2 style to 1.4rc1 style
     */
    function upgradeTables() {
    	global $database;
        $queries = array();

        $database->setQuery("SHOW INDEX FROM #__docman");
        $database->query();
        $num_keys = $database->getNumRows();
        switch($num_keys){
            case 1: // there's only a primary index, add some more
                $queries[] = "ALTER TABLE `#__docman` ADD INDEX `pub_appr_own_cat_name`  (`published`, `approved`, `dmowner`, `catid`, `dmname`(64))";
                $queries[] = "ALTER TABLE `#__docman` ADD INDEX `appr_pub_own_cat_date`  (`approved`, `published`, `dmowner`, `catid`, `dmdate_published`)";
                $queries[] = "ALTER TABLE `#__docman` ADD INDEX `own_pub_appr_cat_count` (`dmowner`, `published`, `approved`, `catid`, `dmcounter`)";
                // pass through (more can be added later on)
            default:
                break;
        }

        foreach($queries as $query) {
            $database->setQuery($query);
            if(! $database->query()) {
            	echo 'Error upgrading tables';
            }
        }
    }
}
