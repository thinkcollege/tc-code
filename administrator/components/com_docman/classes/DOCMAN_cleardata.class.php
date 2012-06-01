<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: DOCMAN_cleardata.class.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');


if (defined('_DOCMAN_cleardata')) {
    return true;
} else {
    define('_DOCMAN_cleardata', 1);
}

class DOCMAN_CleardataItem{
    /**
     * @abstract
     */
	var $name;

    /**
     * @abstract
     */
    var $friendlyname;

    var $msg;

    /**
     * @static
     */
     function & getInstance( $item ){
        $classname = "DOCMAN_CleardataItem_$item";
        $instance = new $classname;
     	return $instance;
     }

    function clear(){
    	if (!$this->check()) {
    		return false;
    	}
        return true;
    }

    function check(){return true;}
}


/**
 * @abstract
 */
class DOCMAN_CleardataItemTable extends DOCMAN_CleardataItem{
    var $table;
    var $where;
    function clear(){
        if(!$this->check()) {
            return false;
        }
    	global $database;
        $database->setQuery("DELETE FROM ".$this->table
                            ."\n ".$this->where);
        if( $database->query()){
            $this->msg = _DML_CLEARDATA_CLEARED.$this->friendlyname;
            return true;
        } else {
        	$this->msg = _DML_CLEARDATA_FAILED.$this->friendlyname;
            return false;
        }
    }
}

class DOCMAN_CleardataItem_docman extends DOCMAN_CleardataItemTable{
	var $name = 'docman';
    var $friendlyname = 'Documents';
    var $table = '#__docman';
}
class DOCMAN_CleardataItem_docman_groups extends DOCMAN_CleardataItemTable{
    var $name = 'docman_groups';
    var $friendlyname = 'User Groups';
    var $table = '#__docman_groups';
}
class DOCMAN_CleardataItem_docman_history extends DOCMAN_CleardataItemTable{
    var $name = 'docman_history';
    var $friendlyname = 'Document History';
    var $table = '#__docman_history';
}
class DOCMAN_CleardataItem_docman_licenses extends DOCMAN_CleardataItemTable{
    var $name = 'docman_licenses';
    var $friendlyname = 'Licenses';
    var $table = '#__docman_licenses';
}
class DOCMAN_CleardataItem_docman_log extends DOCMAN_CleardataItemTable{
    var $name = 'docman_log';
    var $friendlyname = 'Download Logs';
    var $table = '#__docman_log';
}
class DOCMAN_CleardataItem_categories extends DOCMAN_CleardataItemTable{
    var $name = 'categories';
    var $friendlyname = 'Categories';
    var $table = '#__categories';
    var $where = "WHERE section = 'com_docman'";

    function check(){
        global $database;
        $database->setQuery("SELECT COUNT(*) FROM #__docman");
        if( $database->loadResult() >=1 ){
        	$this->msg = _DML_CLEARDATA_CATS_CONTAIN_DOCS;
            return false;
        }
        return true;
    }
}

class DOCMAN_CleardataItem_files extends DOCMAN_CleardataItem{
	var $name = 'files';
    var $friendlyname = 'Files';
    function clear(){
        if(!$this->check()) {
            return false;
        }
        global $_DOCMAN;
        require_once($_DOCMAN->getPath('classes', 'file'));
    	$folder = new DOCMAN_Folder( $_DOCMAN->getCfg('dmpath' ));
        $files = $folder->getFiles();
        $this->msg = _DML_CLEARDATA_CLEARED.$this->friendlyname;
        if( count($files)){
            foreach( $files as $file ){
        	   if( !$file->remove() ){
        		  $this->msg = _DML_CLEARDATA_FAILED.$this->friendlyname;
                  return false;
        	   }
            }
        }
        return true;
    }

    function check(){
        global $database;
        $database->setQuery("SELECT COUNT(*) FROM #__docman");
        if( $database->loadResult() >=1 ){
            $this->msg = _DML_CLEARDATA_DELETE_DOCS_FIRST;
            return false;
        }
        return true;
    }
}


class DOCMAN_Cleardata {
	var $items = array();

    /**
     * @constructor
     */
    function DOCMAN_Cleardata( $items = null ){
    	if ( !$items ) {
            $items = array( 'docman', 'categories', 'files', 'docman_groups', 'docman_history', 'docman_licenses', 'docman_log');
        }
        foreach ($items as $item){
        	$this->items[] = & DOCMAN_CleardataItem::getInstance( $item );
        }
    }

    function clear(){
    	foreach( $this->items as $item){
    		$item->clear();
    	}
    }

    function & getList(){
    	return $this->items;
    }

}

