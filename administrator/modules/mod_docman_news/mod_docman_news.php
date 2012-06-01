<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: mod_docman_news.php 616 2008-02-19 12:42:22Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

define('_DM_DEFAULT_FEED_URL', 'http://feeds.joomlatools.org/docman');

if( defined('_DM_J15')) {

    global $_DOCMAN, $mosConfig_absolute_path, $mosConfig_live_site, $mainframe;
    $_DOCMAN->setType(_DM_TYPE_MODULE);
    $_DOCMAN->loadLanguage('modules');
    require_once($_DOCMAN->getPath('classes', 'utils'));

    $imgpath        = $mosConfig_live_site.'/administrator/templates/'.$mainframe->getTemplate().'/images/';
    $limit          = $params->get('limit', 5 );
    $desc_truncate  = $params->get('desc_truncate', 200 );

    // check if cache directory is writeable
    $cacheDir = JPATH_BASE.DS.'cache'.DS;
    if ( !is_writable( $cacheDir ) ) {
        echo JText::_( 'Cache Directory Unwritable' );
        return;
    }

    $options = array();
    $options['rssUrl']      = $params->get('feed_url', _DM_DEFAULT_FEED_URL);
    $options['cache_time']  = $params->get('cachetime', 86400);

    $rss =& JFactory::getXMLparser('RSS', $options);
    if ( $rss== false ) {
        echo JText::_('Error: Feed not retrieved');
        return;
    }
    ?>

    <table class="adminlist">
    <tbody>
        <tr>
            <th><a href="<?php echo $rss->get_link() ?>" target="_blank"><?php echo $rss->get_title()?></a></th>
        </tr><?php
        $cntItems = $rss->get_item_quantity();
        if( !$cntItems ) {?>
            <tr><th><?php echo _DML_MOD_NEWS_NO_ITEMS?></th></tr><?php
        }else{
            $cntItems = ($cntItems > $limit) ? $limit : $cntItems;
            for( $j = 0; $j < $cntItems; $j++ ){
                $item = & $rss->get_item($j);?>
                <tr><td>
                    <a href="<?php echo $item->get_link()?>" target="_blank"><?php echo $item->get_title()?></a><?php
                    if( $description = DOCMAN_Utils::snippet($item->get_description(), $desc_truncate) ) {?>
                        <br /><?php echo $description?><?php
                    }?>
                </td></tr><?php
            }
        }?>
    </tbody>
    </table>
    <?php



} else {


    global $_DOCMAN;
    $_DOCMAN->setType(_DM_TYPE_MODULE);
    $_DOCMAN->loadLanguage('modules');
    require_once($_DOCMAN->getPath('classes', 'utils'));

    global $mosConfig_absolute_path, $mosConfig_live_site, $mainframe;
    $imgpath = $mosConfig_live_site.'/administrator/templates/'.$mainframe->getTemplate().'/images/';
    $limit = $params->get('limit', 5 );
    $desc_truncate = $params->get('desc_truncate', 200 );
    $cacheDir = $mosConfig_absolute_path . "/cache/";
    $LitePath = $mosConfig_absolute_path . "/includes/Cache/Lite.php";


    require_once($mosConfig_absolute_path . "/includes/domit/xml_domit_rss_lite.php");

    $rss = &new xml_domit_rss_document_lite();
    $rss->useCacheLite(true, $LitePath, $cacheDir, $params->get('cachetime', 86400) );
    $rss->loadRSS($params->get('feed_url', _DM_DEFAULT_FEED_URL));

    for ($i = 0; $i < $rss->getChannelCount(); $i++) {
        $channel = &$rss->getChannel($i);?>
        <table class="adminlist">
        <tbody>
            <tr>
                <th><a href="<?php echo $channel->getLink() ?>" target="_blank"><?php echo $channel->getTitle()?></a></th>
            </tr><?php
            $cntItems = $channel->getItemCount();
            if( !$cntItems ) {?>
                <tr><th><?php echo _DML_MOD_NEWS_NO_ITEMS?></th></tr><?php
            }else{
            	$cntItems = ($cntItems > $limit) ? $limit : $cntItems;
                for( $j = 0; $j < $cntItems; $j++ ){
                    $item = & $channel->getItem($j);?>
                	<tr><td>
                        <a href="<?php echo $item->getLink()?>" target="_blank"><?php echo $item->getTitle()?></a><?php
                        if( $description = DOCMAN_Utils::snippet($item->getDescription(), $desc_truncate) ) {?>
                            <br /><img src="<?php echo $imgpath?>arrow.gif" alt="Item" /><?php echo $description?><?php
                        }?>
                    </td></tr><?php
                }
            }?>
        </tbody>
        </table><?php
    }
    ?>
    <table class="adminlist"><tr><th colspan="1"><?php DOCMAN_Utils::getModuleButtons( $name ) ?></th></tr></table>
    <?php
}
