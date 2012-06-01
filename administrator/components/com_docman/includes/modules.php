<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: modules.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

//include_once dirname(__FILE__) . '/modules.html.php';

$moduleid = (int) mosGetParam( $_REQUEST, 'moduleid', null );
$client = strval( mosGetParam( $_REQUEST, 'client', 'admin' ) );
mosArrayToInts( $cid );

switch ($task) {
    case 'publish':
    case 'unpublish':
        publishModule( array($moduleid), ($task == 'publish'), $option, $client );
        break;
    case 'orderup':
    case 'orderdown':
        orderModule( $moduleid, ($task == 'orderup' ? -1 : 1), $option, $client );
        break;
    default:
        mosRedirect( 'index2.php?option=com_docman' );
        break;
}


function publishModule( $cid=null, $publish=1, $option, $client='admin' ) {
    global $database, $my;

    if (count( $cid ) < 1) {
        $action = $publish ? 'publish' : 'unpublish';
        echo "<script> alert('Select a module to $action'); window.history.go(-1);</script>\n";
        exit;
    }

    mosArrayToInts( $cid );
    $cids = 'id=' . implode( ' OR id=', $cid );

    $query = "UPDATE #__modules"
    . "\n SET published = " . (int) $publish
    . "\n WHERE ( $cids )"
    . "\n AND ( checked_out = 0 OR ( checked_out = " . (int) $my->id . " ) )"
    ;
    $database->setQuery( $query );
    if (!$database->query()) {
        echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
        exit();
    }

    if (count( $cid ) == 1) {
        $row = new mosModule( $database );
        $row->checkin( $cid[0] );
    }

    mosCache::cleanCache( 'com_content' );

    $redirect = mosGetParam( $_REQUEST, 'redirect', 'index2.php?option='. $option .'&client='. $client );
    mosRedirect( $redirect );
}

/*
 * using custom function because the core function in com_modules doesn't
 * read id from $_GET
 */
function orderModule( $uid, $inc, $option, $client='admin' ){
    global $database;

    $row = new mosModule( $database );
    $row->load( (int)$uid );

    if ($client == 'admin') {
        $where = "client_id = 1";
    } else {
        $where = "client_id = 0";
    }

    $row->move( $inc, "position = " . $database->Quote( $row->position ) . " AND ( $where )"  );

    mosCache::cleanCache( 'com_content' );

    $redirect = mosGetParam( $_REQUEST, 'redirect', 'index2.php?option='. $option .'&client='. $client );
    mosRedirect( $redirect );

}