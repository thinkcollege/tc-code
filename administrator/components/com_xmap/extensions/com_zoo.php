<?php
/**
* @author Bojan Popic
* @email bojanpopic@gmail.com
* @version $Id: com_zoo.php
* @package Xmap
* @license GNU/GPL
* @description Xmap plugin for YooThemes Zoo component
*/

/* Usefull tips

Requirement:
- one menu item that point to frontpage of zoo application.

If you have menu items that points to zoo categories, it will duplicate entry since zoo
is "one item - multi categories" CCK.
You should exclude that menu items from map, and use the one from requirements. If you 
don't need that kind of menu item, just make one and unpublish it, it would serve fine 
for map, and it would not mess up your menu.

/**
CHANGELOG
BETA5
- found and fixed bug - using jos_ prefix in item fetch SQL code. Changed prefix to #_. Thanks thibaut.lauziere for founding that bug!

BETA4
- added news map creation. Previous versions didn't create news map at all. Thanks ioalex for tips about problem and solution!

BETA3
- Removed some redundant code
- Added possibility (2 new parameters) to print title in HTML map before listing categories/items.

BETA2
- Fixed printing links from all zoo applications. Now it prints what application is selected in menu item parameters.

BETA1
- Intial release
*/
defined( '_JEXEC' ) or die( 'Restricted access.' );

class xmap_com_zoo {

    /*
    * This function is called before a menu item is printed. We use it to set the
    * proper uniqueid for the item and indicate whether the node is expandible or not
    */
    function prepareMenuItem(&$node) {
        $link_query = parse_url( $node->link );
        parse_str( html_entity_decode($link_query['query']), $link_vars);
        $component = JArrayHelper::getValue($link_vars, 'option', '');
        $view = JArrayHelper::getValue($link_vars,'view','');
        // it only works if there is menu item that is set on zoo, and on frontpage view
        // it will parse that menu item and ignore others
        if ($component == 'com_zoo' && $view == 'frontpage' ) {
            $id = intval(JArrayHelper::getValue($link_vars,'id',0));
            if ( $id != 0 ) {
                $node->uid = 'com_zoo'.$id;
                $node->expandible = false;
            }
        } elseif ($view == 'item' ) {
            $menu = JSite::getMenu();
            $menuParams = $menu->getParams($node->id);
            $id = (int) $menuParams->get('item_id');
            if ($id) {
                $db = &JFactory::getDBO();
                $db->setQuery('SELECT UNIX_TIMESTAMP(publish_up) as publish_up FROM `#__zoo_item` where id='.$id);
                if ($item = $db->loadObject()) {
                    $node->modified = $item->publish_up;
                }
            }
            $node->uid = 'com_zooi'.$id;
            $node->expandible = false;
        }
    }

    function getTree( &$xmap, &$parent, &$params) {
        
        $link_query = parse_url( $parent->link );
        parse_str( html_entity_decode($link_query['query']), $link_vars );
        $view = JArrayHelper::getValue($link_vars,'view',0);
        
        if ($view == 'item')
            return true;

        $include_categories = JArrayHelper::getValue( $params, 'include_categories',1,'' );
        $include_categories = ( $include_categories == 1
                  || ( $include_categories == 2 && $xmap->view == 'xml')
                  || ( $include_categories == 3 && $xmap->view == 'html')
                  ||   $xmap->view == 'navigator');
        $params['include_categories'] = $include_categories;
                
        $include_items = JArrayHelper::getValue( $params, 'include_items',1,'' );
        $include_items = ( $include_items == 1
                  || ( $include_items == 2 && $xmap->view == 'xml')
                  || ( $include_items == 3 && $xmap->view == 'html')
                  ||   $xmap->view == 'navigator');
        $params['include_items'] = $include_items;

        $priority = JArrayHelper::getValue($params,'cat_priority',$parent->priority,'');
        $changefreq = JArrayHelper::getValue($params,'cat_changefreq',$parent->changefreq,'');
        if ($priority  == '-1')
            $priority = $parent->priority;
        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;

        $params['cat_priority'] = $priority;
        $params['cat_changefreq'] = $changefreq;

        $priority = JArrayHelper::getValue($params,'item_priority',$parent->priority,'');
        $changefreq = JArrayHelper::getValue($params,'item_changefreq',$parent->changefreq,'');
        if ($priority  == '-1')
            $priority = $parent->priority;

        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;

        $params['item_priority'] = $priority;
        $params['item_changefreq'] = $changefreq;

        xmap_com_zoo::getCategoryTree($xmap, $parent, $params);

    }

    function getCategoryTree ( &$xmap, &$parent, &$params) {
        $db = &JFactory::getDBO();
        
        // first we fetch what application we are talking about
        
        $menu =& JSite::getMenu();
        $menuparams = $menu->getParams($parent->id);
        $appid =  intval($menuparams->get('application', 0));

        // if selected, we print title category
        if ($params['include_categories']) {
            
            // get categories info from database
            $queryc = 'SELECT c.id, c.name '.
                     'FROM #__zoo_category c '.
                     ' WHERE c.application_id = '.$appid.' AND c.published=1 '.
                     ' ORDER by c.ordering';
            
            $db->setQuery($queryc);
            $cats = $db->loadObjectList();
            
            // now we print categories
            $xmap->changeLevel(1);
            foreach($cats as $cat) {
                $node = new stdclass;
                $node->id   = $parent->id;
                $node->uid  = 'com_zooc'.$cat->id;
                $node->name = $cat->name;
                $node->link = 'index.php?option=com_zoo&amp;task=category&amp;category_id='.$cat->id;
                $node->priority   = $params['cat_priority'];
                $node->changefreq = $params['cat_changefreq'];
                $node->expandible = true;
                $xmap->printNode($node);
            }
            $xmap->changeLevel(-1);
        }

        if ($params['include_items'] ){
            // get items info from database
            // basically it select those items that are published now (publish_up is less then now, meaning it's in past)
            // and not unpublished yet (either not have publish_down date set, or that date is in future)
            $queryi =  'SELECT i.id, i.name, i.publish_up'.
                            ' FROM #__zoo_item i'.
                            ' WHERE i.application_id= '.$appid.
                            ' AND ( i.publish_up = \'0000-00-00 00:00:00\' OR i.publish_up <= \'' . date('Y-m-d H:i:s', $xmap->now) . "' )".
                            ' AND IF( i.publish_down >0, DATEDIFF( i.publish_down, NOW( ) ) >0, true )'.
                            ' ORDER BY i.publish_up';
            $db->setQuery($queryi);
            $items = $db->loadObjectList();
            
            // now we print items
            $xmap->changeLevel(1);
            foreach($items as $item) {
                // if we are making news map, we should ignore items older then 3 days
                if ($xmap->isNews && strtotime($item->publish_up) < ($xmap->now - (3 * 86400))) {
                    continue;
                }
                $node = new stdclass;
                $node->id   = $parent->id;
                $node->uid  = 'com_zooi'.$item->id;
                $node->name = $item->name;
                $node->link = 'index.php?option=com_zoo&amp;task=item&amp;item_id='.$item->id;
                $node->priority   = $params['item_priority'];
                $node->changefreq = $params['item_changefreq'];
                $node->expandible = false;
                $node->modified = strtotime($item->publish_up);
                $node->newsItem = 1; // if we are making news map and it get this far, it's news
                $xmap->printNode($node);
                
                
            }
            $xmap->changeLevel(-1);
        }
    }

}