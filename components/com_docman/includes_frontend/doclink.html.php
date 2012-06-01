<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: doclink.html.php 627 2008-02-23 00:36:37Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_JEXEC') or die('Restricted access');

class HTML_DMDoclink {
    function showDoclink($rows){
        global $mainframe;
    	?>
        <script>var editor = '<?php echo JRequest::getWord('e_name'); ?>';</script>

        <img src="<?php echo JURI::root()?>administrator/components/com_docman/images/dm_logo_small.png" alt="DOCman Logo" />
        <div id="loading" class="statusLayer">
          <div id= "loadingStatus"><?php echo _DML_DCL_LOADING; ?></div>
        </div>
        <form id="frminsertlink" >
        <table class="adminform">
            <thead><tr><td colspan="2"><?php echo _DML_DCL_MANAGER; ?></td></tr></thead>
            <tbody>
                <tr><td colspan="2">
                    <div id="selector">
                    <label for="listctrl"><?php echo _DML_DCL_CATEGORY; ?></label>
                    <?php echo HTML_DMDoclink::createListCtrl($rows, 'listctrl', 'listctrl'); ?>
                    <button class="button" type="button" "name="updir"  onClick="javascript:changeListCtrl('up');"><img src="components/com_docman/assets/images/btnFolderUp.gif" alt="<?php echo _DML_DCL_UP ?>"></button>
                    </div>
                    <div id="browser">
                        <iframe src="<?php echo JURI::base()?>index.php?option=com_docman&amp;task=doclink-listview" id="listview" name="listview" width="545" height="150" marginwidth="0" marginheight="0" align="top" scrolling="no" frameborder="0" hspace="0" vspace="0"></iframe>
                    </div>
                </td></tr>
            </tbody>
        </table>
        <table class="adminform">
            <thead><tr><td colspan="2"><?php echo _DML_DCL_SETTINGS; ?></td></tr></thead>
            <tbody>
                <tr>
                    <td><?php echo _DML_DCL_URL ?></td>
                    <td><input id="f_url" name="f_url" value="" size="50" /></td>
                </tr>
                <tr>
                    <td><?php echo _DML_DCL_CAPTION; ?></td>
                    <td><input id="f_caption" name="f_caption" value="" size="50" /></td>
                </tr>
                <tr>
                    <td><?php echo _DML_DCL_INSERTICON; ?></td>
                    <td><input id="f_addicon" name="f_addicon" type="checkbox" /></td>
                </tr>
                <tr>
                    <td><?php echo _DML_DCL_INSERTSIZE;; ?></td>
                    <td><input id="f_addsize" name="f_addsize" type="checkbox" /></td>
                </tr>
                <tr>
                    <td><?php echo _DML_DCL_INSERTDATE; ?></td>
                    <td><input id="f_adddate" name="f_adddate" type="checkbox" /></td>
                </tr>
                <tr><td colspan="2" />
                    <br />
                    <button type="button" id="ok" name="ok" onclick="_doclink_onok();"><?php echo _DML_OK ?></button>
                    <button type="button" id="cancel" name="cancel" onclick="window.parent.document.getElementById('sbox-window').close();"><?php echo _DML_CANCEL ?></button>
                </td></tr>
            <tbody>

        </table>

        <input id="f_icon" name="f_icon" type="hidden" value="" >
        <input id="f_size" name="f_size" type="hidden" value="" >
        <input id="f_date" name="f_date" type="hidden" value="" >
        <input id="f_cid"  name="f_cid"  type="hidden" value="" >
        <input id="f_pid"  name="f_pid"  type="hidden" value="" >
        </form><?php
    }

   function createListCtrl($rows, $name, $id){
        $select  =  "<select value=\"0:0\" name=\"$name\" id=\"$id\" onchange=\"onchangeListCtrl(this);\">\n";
        $select .=  "<option selected=\"selected\" value=\"0:0\">/</option>\n";

        foreach($rows as $row) {
            if (count($row) != "0") {
                $value = "$row->parent_id:$row->id";
                $text  = $row->treename;
                $select .= "<option value=\"$value\">$text</option>\n";
            }
        }
        $select .=  "</select>\n";
        echo $select;
    }

    function createHeader()
    {
        ?>
        <style>body {margin:0}</style>
        <table class="sort-table" id="tableHead" cellspacing="0" width="100%">
        <col style="width: 20px" />
        <col style="width: 220px" />
        <col style="width: 100px" />
        <col style="width: 130px" />
        <thead>
        <tr>
            <td>&nbsp;</td>
            <td id="sortmefirst" onclick="st.sort(2);" style="text-align:left"><?php echo _DML_NAME ?></td>
            <td onclick="st.sort(3);" style="text-align:left"><?php echo _DML_SIZE ?></td>
            <td onclick="st.sort(4);" style="text-align:left"><?php echo _DML_DATE_MODIFIED ?></td>
        </tr>
        </thead>
        <tbody style="display: none;"  >
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
        </table>

        <div id="tableContainer" class="tableContainer">
        <table class="sort-table" id="tableBody" cellspacing="0" width="100%" >
        <col style="width: 20px" />
        <col style="width: 220px;" />
        <col style="width: 100px;" />
        <col style="width: 130px;" />
        <thead style="display: none;">
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <?php
    }

    function createFooter()
    {
        ?>
        </tbody>
        </table>
        </div>
        <?php
    }

    function createFolders($rows, $cid )
    {
        if(!count($rows))
            return '';

        $html = '';
        foreach($rows as $row)
        {
            $cat = new DOCMAN_Category($row->id);

            $links   = $cat->getLinkObject();
            $paths   = $cat->getPathObject();
            $details = $cat->getDataObject();

            $cid    = $details->id;
            $pid    = $details->parent_id;

            $icon   = $cat->getPath('icon', 1, '16x16');
            $url    = DOCMAN_Utils::_rawLink('cat_view', $details->id);

            ?>
            <tr>
                <td><img src="<?php echo $icon ?>" alt="<?php echo $details->name ?>" /></td>
                <td><a href="<?php echo JURI::root()?>index.php?option=com_docman&amp;task=doclink-listview&amp;catid=<?php echo $cid ?>" onClick="onclickFolder(<?php echo $pid ?>, <?php echo $cid ?>, '<?php echo $details->name ?>', '<?php echo $url ?>', '<?php echo $icon ?>');"><?php echo $details->name ?></a></td>
                <td><?php echo _DML_FOLDER ?></td>
                <td>&nbsp;</td>
            </tr>
            <?php
        }
    }

    function createItems($rows)
    {
        global $_DOCMAN, $mosConfig_live_site;

        $html = '';
        foreach($rows as $row)
        {
            $doc = new DOCMAN_Document($row->id);

            $links   = $doc->getLinkObject();
            $paths   = $doc->getPathObject();
            $details = $doc->getDataObject();

            $icon   = $doc->getPath('icon', 1, '16x16');

            $params = array('Itemid' => DOCMAN_Utils::getItemid() );
            $url    = DOCMAN_Utils::_rawLink('doc_download', $details->id, $params);

            if ($details->dmlastupdateon<>"0000-00-00 00:00:00") {
                $itemtime  = $details->dmlastupdateon;
            } else {
                $itemtime  = $details->dmdate_published;
            }



            ?>
            <tr>
                <td><img src="<?php echo $icon ?>" alt="<?php echo $details->dmname ?>" /></td>
                <td><a href="javascript:;" onClick="onclickItem('<?php echo addslashes($details->dmname) ?>', '<?php echo $url ?>', <?php echo $details->catid ?>, '<?php echo $icon ?>', '<?php echo $details->filesize ?>', '<?php echo $itemtime ?>');"><?php echo $details->dmname ?></td>
                <td><?php echo $details->filesize ?></td>
                <td><?php echo $itemtime ?></td>
            </tr>
            <?php
        }

        return $html;
    }
}