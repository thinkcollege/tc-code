<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: categories.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

include_once dirname(__FILE__) . '/categories.html.php';
require_once($_DOCMAN->getPath('classes', 'model'));
require_once($_DOCMAN->getPath('classes', 'theme'));

function fetchCategory($id)
{
    global $_DMUSER;

    $cat = new DOCMAN_Category($id);

    // if the user is not authorized to access this category, redirect
    if(!$_DMUSER->canAccessCategory($cat->getDBObject())) {
    	_returnTo('' , _DML_NOT_AUTHORIZED);
    }

    // process content mambots
    DOCMAN_Utils::processContentBots(  $cat, 'description' );

    return HTML_DMCategories::displayCategory($cat->getLinkObject(),
        $cat->getPathObject(),
        $cat->getDataObject());
}

function fetchCategoryList($id)
{
    global $_DOCMAN, $_DMUSER;

    $children = DOCMAN_Cats::getChildsByUserAccess($id);

    $items = array();
    foreach($children as $child)
    {
        $cat = new DOCMAN_Category($child->id);

        // process content mambots
        DOCMAN_Utils::processContentBots(  $cat, 'description' );

     	$item = new StdClass();
       	$item->links = &$cat->getLinkObject();
       	$item->paths = &$cat->getPathObject();
        $item->data = &$cat->getDataObject();

       	$items[] = $item;
    }
    // display the entries
    return HTML_DMCategories::displayCategoryList($items);
}
