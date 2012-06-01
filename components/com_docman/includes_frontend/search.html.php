<?php
/**
* DOCman 1.4.x - Joomla! Document Manager
* @version $Id: search.html.php 642 2008-03-01 14:10:47Z mjaz $
* @package DOCman_1.4
* @copyright (C) 2003-2007 The DOCman Development Team
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.joomlatools.org/ Official website
**/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_HTML_SEARCH')) {
    return;
} else {
    define('_DOCMAN_HTML_SEARCH', 1);
}

class HTML_DMSearch
{
    function searchForm(&$lists, $search_phrase)
    {
        global $_DOCMAN;

        $action = _taskLink('search_result');

        ob_start();
        ?>
        <fieldset class="input">
        <table width="100%" class="contentpaneopen">
        <form action="<?php echo $action;?>" method="post" name="adminForm" id="dm_frmsearch" class="dm_searchform">
            <tr height="30px">
                <td nowrap="nowrap" width="150px">
                    <label for="search_phrase"><?php echo _DML_PROMPT_KEYWORD;?></label>:
                </td>
                <td nowrap="nowrap" width="150px">
                    <input type="text" class="inputbox" id="search_phrase" name="search_phrase"  value="<?php echo htmlspecialchars(stripslashes($search_phrase), ENT_QUOTES); ?>" />
                </td>
                <td nowrap="nowrap">
                </td>
            </tr>
            <tr height="30px">
                <td nowrap="nowrap">
                    <label for="catid"><?php echo _DML_SELECCAT;?></label>:
                </td>
                <td nowrap="nowrap">
                    <?php echo $lists['catid'] ;?>
                </td>
                <td nowrap="nowrap">
                </td>
            </tr>
            <tr height="30px">
                <td nowrap="nowrap">
                    <label for="ordering"><?php echo _DML_CMN_ORDERING;?></label>:
                </td>
                <td nowrap="nowrap">
                    <?php echo $lists['ordering'] ;?>
                </td>
                <td nowrap="nowrap">
                    <?php echo $lists['reverse_order'] . _DML_SEARCH_REVRS;?>
                </td>
            </tr>
            <tr height="30px">
                <td nowrap="nowrap">
                    <label for="search_mode"><?php echo _DML_SEARCH_MODE;?></label>:
                </td>
                <td>
                    <?php echo $lists['search_mode']?>
                </td>
                <td>
                    <?php echo $lists['invert_search'] . _DML_NOT ;?>
                </td>
            </tr>
            <tr height="30px">
                <td nowrap="nowrap">
                    <label for="search_where"><?php echo _DML_SEARCH_WHERE;?></label>:
                </td>
                <td>
                    <?php echo $lists['search_where'] ;?>
                </td>
                <td nowrap="nowrap">
                </td>
            </tr>
            <tr height="30px">
                <td nowrap="nowrap">
                </td>
                <td>
                    <input type="submit" class="button" value="<?php echo _DML_SEARCH;?>" />
                </td>
                <td>
                </td>
            </tr>
        </form>
        </table>
        </fieldset>
        <?php
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}