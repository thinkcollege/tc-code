<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: search.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

include_once dirname(__FILE__) . '/search.html.php';
include_once dirname(__FILE__) . '/documents.php';
include_once dirname(__FILE__) . '/documents.html.php';

require_once($_DOCMAN->getPath('classes', 'mambots'));
require_once($_DOCMAN->getPath('classes', 'utils'));

$GLOBALS['search_mode']   = mosGetParam($_REQUEST, 'search_mode', 'any');
$GLOBALS['ordering']      = mosGetParam($_REQUEST, 'ordering', 'newest');
$GLOBALS['invert_search'] = (int) mosGetParam($_REQUEST, 'invert_search', 0);
$GLOBALS['reverse_order'] = (int) mosGetParam($_REQUEST, 'reverse_order', 0);
$GLOBALS['search_where']  = mosGetParam($_REQUEST, 'search_where', 0);
$GLOBALS['search_phrase'] = mosGetParam($_REQUEST, 'search_phrase', '');
$GLOBALS['search_catid']  = (int) mosGetParam($_REQUEST, 'catid', 0);

function fetchSearchForm($gid, $itemid)
{
    global $search_mode, $ordering, $invert_search, $reverse_order, $search_where, $search_phrase, $search_catid;
    // category select list
    $options = array(mosHTML::makeOption('0', _DML_ALLCATS));
    $lists['catid'] = dmHTML::categoryList($search_catid , "", $options);

    $mode = array();
    $mode[] = mosHTML::makeOption('any' , _DML_SEARCH_ANYWORDS);
    $mode[] = mosHTML::makeOption('all' , _DML_SEARCH_ALLWORDS);
    $mode[] = mosHTML::makeOption('exact' , _DML_SEARCH_PHRASE);
    $mode[] = mosHTML::makeOption('regex' , _DML_SEARCH_REGEX);

    $lists['search_mode'] = mosHTML::selectList($mode , 'search_mode', 'id="search_mode" class="inputbox"' , 'value', 'text', $search_mode);

    $orders = array();
    $orders[] = mosHTML::makeOption('newest', _DML_SEARCH_NEWEST);
    $orders[] = mosHTML::makeOption('oldest', _DML_SEARCH_OLDEST);
    $orders[] = mosHTML::makeOption('popular', _DML_SEARCH_POPULAR);
    $orders[] = mosHTML::makeOption('alpha', _DML_SEARCH_ALPHABETICAL);
    $orders[] = mosHTML::makeOption('category', _DML_SEARCH_CATEGORY);

    $lists['ordering'] = mosHTML::selectList($orders, 'ordering', 'id="ordering" class="inputbox"',
        'value', 'text', $ordering);

    $lists['invert_search'] = '<input type="checkbox" class="inputbox" name="invert_search" '
     . ($invert_search ? ' checked ' : '')
     . '/>';
    $lists['reverse_order'] = '<input type="checkbox" class="inputbox" name="reverse_order" '
     . ($reverse_order ? ' checked ' : '')
     . '/>';

    $matches = array();
    if ($search_where && count($search_where) > 0) {
        foreach($search_where as $val) {
            $matches[ ] = mosHTML::makeOption($val, $val);
        }
    } else {
        $matches[] = mosHTML::makeOption('search_description', 'search_description');
    }

    $where = array();
    $where[] = mosHTML::makeOption('search_name' , _DML_NAME);
    $where[] = mosHTML::makeOption('search_description' , _DML_DESCRIPTION);
    $lists['search_where'] = mosHTML::selectList($where , 'search_where[]',
        'id="search_where" class="inputbox" multiple="multiple" size="2"' , 'value', 'text', $where);

    return HTML_DMSearch::searchForm($lists, $search_phrase);
}

function getSearchResult($gid, $itemid)
{
    global $search_mode, $ordering, $invert_search, $reverse_order, $search_where, $search_phrase, $search_catid;

    $search_mode = ($invert_search ? '-' : '') . $search_mode ;
    $searchList = array(
        array('search_mode' => $search_mode ,
            'search_phrase' => $search_phrase));
    $ordering = ($reverse_order ? '-' : '') . $ordering ;

    $rows = DOCMAN_Docs::search($searchList , $ordering , $search_catid , '', $search_where);

    // This acts as the search header - so they can perform search again
    if (count($rows) == 0) {
        $msg = _DML_NOKEYWORD ;
    } else {
        $msg = sprintf(_DML_SEARCH . ' ' . _DML_SEARCH_MATCHES , count($rows));
    }

    $items = array();
    if (count($rows) > 0)
    {
        foreach($rows as $row) {
            // onFetchDocument event, type = list
            $bot = new DOCMAN_mambot('onFetchDocument');
            $bot->setParm('id' , $row->id);
            $bot->copyParm('type' , 'list');
            $bot->trigger();
            if ($bot->getError()) {
                _returnTo('cat_view', $bot->getErrorMsg());
            }

            // load doc
            $doc = & DOCMAN_Document::getInstance($row->id);

            // process content mambots
            DOCMAN_Utils::processContentBots( $doc, 'dmdescription' );

            $item = new StdClass();
            $item->buttons = &$doc->getLinkObject();
            $item->paths = &$doc->getPathObject();
            $item->data = &$doc->getDataObject();
            $item->data->category = $row->section;

            $items[] = $item;
        }
    }

    return $items;
}

