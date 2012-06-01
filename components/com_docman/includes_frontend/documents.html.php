<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: documents.html.php 641 2008-03-01 13:41:54Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_HTML_DOCUMENTS'))
    return;
define('_DOCMAN_HTML_DOCUMENTS', 1);

require_once($_DOCMAN->getPath('classes', 'html'));

class HTML_DMDocuments
{
    function displayDocument(&$buttons, &$paths, &$data)
    {
        // modify links data
        unset($buttons['details']);
        $tpl = &new DOCMAN_Theme();

        // Assign values to the Savant instance.
        $tpl->assignRef('buttons', $buttons);
        $tpl->assignRef('paths', $paths);
        $tpl->assignRef('data', $data);

        // Display a template using the assigned values.
        return $tpl->fetch('documents/document.tpl.php');
    }

    function displayDocumentList(&$order, &$items)
    {
        $tpl = &new DOCMAN_Theme();

        // Assign values to the Savant instance.
        $tpl->assignRef('order', $order);
        $tpl->assignRef('items', $items);

        // Display a template using the assigned values.
        return $tpl->fetch('documents/list.tpl.php');
    }

    function editDocumentForm(&$row, &$lists, $last, $created, &$params)
    {
        global $Itemid;
        global $_DOCMAN, $_DMUSER;

        require_once($GLOBALS['mosConfig_absolute_path'] . '/includes/HTML_toolbar.php');

        mosMakeHtmlSafe($row);

        ob_start();
        ?>
        <form action="index.php" method="post" name="adminForm" onsubmit="javascript:setgood();" id="dm_frmedit" class="dm_form">
        <?php

        $tabs = new dmTabs(0);

        echo $tabs->startPane("content-pane");
        echo $tabs->startTab(_DML_DOCUMENT, "document-page");

        HTML_DMDocuments::_showTabDocument($row, $lists, $last, $created);

        echo $tabs->endTab();
        echo $tabs->startTab(_DML_TAB_PERMISSIONS, "permissions-page");

        HTML_DMDocuments::_showTabPermissions($row, $lists, $last, $created);

        echo $tabs->endTab();
        echo $tabs->startTab(_DML_TAB_LICENSE, "license-page");

        HTML_DMDocuments::_showTabLicense($row, $lists, $last, $created);

       	if(isset($params)) :
        echo $tabs->endTab();
        echo $tabs->startTab(_DML_TAB_DETAILS, "details-page");

        HTML_DMDocuments::_showTabDetails($row, $lists, $last, $created, $params);
        endif;

        echo $tabs->endTab();
        echo $tabs->endPane();
        ?>
        <br />
        <p>
            <label class="nofloat" for="dmdescription"><?php echo _DML_DESCRIPTION;?></label><br />
            <?php
            // parameters : areaname, content, hidden field, width, height, rows, cols
            DOCMAN_Compat::editorArea('editor1', $row->dmdescription, 'dmdescription', '550', '250', '50', '10');
            ?>
        </p>

        <input type="hidden" name="goodexit" value="0" />
		<input type="hidden" name="id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="option" value="com_docman" />
		<input type="hidden" name="task" value="doc_save" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
        <input type="hidden" name="dmcounter" value="<?php echo $row->dmcounter;?>" />
        <?php echo DOCMAN_token::render();?>
		</form>
        <?php
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    function _showTabDocument(&$row, &$lists, $last, $created)
    {
    	global $_DOCMAN, $_DMUSER;
        DOCMAN_Compat::calendarJS();

    	?>
        <table class="adminform">
        <tbody>
        <tr>
        <td>


            <label for="dmname"><?php echo _DML_TITLE;?></label><br />
            <input class="inputbox" type="text" name="dmname" size="50" maxlength="100" value="<?php echo $row->dmname;?>" />

        <p>
            <label for="catid"><?php echo _DML_CATEGORY;?></label><br />
            <?php echo $lists['catid'];?>
        </p>
        <p>
            <label for="dmdate_published"><?php echo _DML_DATE;?></label><br />
            <?php DOCMAN_Compat::calendar('dmdate_published', $row->dmdate_published);?>
        </p>

    	<p>
    		<label for="dmthumbnail"><?php echo _DML_THUMBNAIL;?></label><br />
			<?php echo $lists['dmthumbnail'];?>
            <?php $previewfull = $lists['dmthumbnail_preview'] ? "images/stories/".$lists['dmthumbnail_preview'] : "images/M_images/blank.png";?>
            <img src="<?php echo $previewfull ?> " id="dmthumbnail_preview" alt="Preview" />
		</p>
    	<p>
    		<label for="dmfilename"><?php echo _DML_FILE;?></label><br />
			<?php echo $lists['dmfilename'];?>
		</p>

    	<?php
        if (isset($row->dmlink)) :
   		?>
            <p>
            	<label for="dmfilename"><?php echo _DML_DOCURL;?></label><br />
				<input class="inputbox" type="text" name="document_url" size="50" maxlength="200" value="<?php echo $row->dmlink ?>" />
            	<?php echo DOCMAN_Utils::mosToolTip(_DML_DOCURL_TOOLTIP . '</span>', _DML_DOCURL);?>
            </p>
        <?php
        endif;
        ?>
        <p>
			<label class="nofloat" for="dmurl"><?php echo _DML_HOMEPAGE;?></label><br />
			<input class="inputbox" type="text" name="dmurl" size="50" maxlength="200" value="<?php echo $row->dmurl ?>" />
			<?php echo DOCMAN_Utils::mosToolTip(_DML_HOMEPAGE_TOOLTIP . '</span>', _DML_HOMEPAGE);?>
			<div><i>(<?php echo _DML_MAKE_SURE;?>)</i></div>
		</p>
    	<?php
    	if (!$row->approved && $_DMUSER->canApprove()) : ?>
    	<p>
			<label><?php echo _DML_APPROVED;?></label><br />
			<?php echo $lists['approved']; ?>
		</p>
    	<?php
    	endif;
        if ($row->approved && $_DMUSER->canPublish()) : ?>
        <p>
			<label><?php echo _DML_PUBLISHED;?></label><br />
			<?php echo $lists['published']; ?>
		</p>
		<?php
		endif;
		?>

        </td>
        </tr>
        </tbody>
        </table>
        <?php
    }

    function _showTabPermissions(&$row, &$lists, $last, $created)
    {
    	global $_DOCMAN, $_DMUSER;

    	?>
    	<fieldset class="input">
        <p>
			<label for="dmowner"><?php echo _DML_OWNER;?></label><br />
			<?php echo $lists['viewer'];?>
			<?php echo DOCMAN_Utils::mosToolTip(_DML_OWNER_TOOLTIP . '</span>',  _DML_OWNER); ?>
		</p>
    	<p>
			<label for="dmmaintainedby"><?php echo _DML_MAINTAINER;?></label><br />
			<?php echo $lists['maintainer']; ?>
			<?php echo DOCMAN_Utils::mosToolTip(_DML_MANT_TOOLTIP . '</span>', _DML_MAINTAINER); ?>
		</p>
    	<p>
			<label for="dmcreatedby"> <?php echo _DML_CREATED_BY;?></label><br />
			[<?php echo $created[0]->name;?>]&nbsp;
			<i>
			<?php echo _DML_ON . "&nbsp;"; ?>
			<?php
        	if ($row->dmdate_published) {
           	 	echo mosFormatDate($row->dmdate_published);
        	} else {
            	$date = date("Y-m-d H:i:s", time("Y-m-d g:i:s"));
            	echo  mosFormatDate($row->dmdate_published);
        	}
        	?>
   			</i>
		</p>
       	<p>
			<label for="dmupdatedby"> <?php echo _DML_UPDATED_BY;?></label><br />
			[<?php echo $created[0]->name;?>]&nbsp;

			<?php
        	if ($row->dmlastupdateon) {
            	echo "<i>" . _DML_ON . "&nbsp;" . mosFormatDate($row->dmlastupdateon) ."</i>" ;
        	} ?>
		</p>
  		</fieldset>
  		<?php
    }

    function _showTabLicense(&$row, &$lists, $last, $created)
    {
    	global $_DOCMAN, $_DMUSER;

    	?>
    	<fieldset class="input">
    	<p>
			<label for="dmlicense_id"><?php echo _DML_LICENSE_TYPE;?></label><br />
			<?php echo $lists['licenses']; ?>
			<?php echo DOCMAN_Utils::mosToolTip(_DML_LICENSE_TOOLTIP . '</span>',  _DML_LICENSE_TYPE); ?>
		</p>
    	<p>
			<label for="dmlicense_display"><?php echo _DML_DISPLAY_LICENSE;?></label><br />
			<?php echo $lists['licenses_display']; ?>
			<?php echo DOCMAN_Utils::mosToolTip(_DML_DISPLAY_LIC_TOOLTIP . '</span>', _DML_DISPLAY_LIC); ?>
		</p>
        </fieldset>
        <?php
    }

    function _showTabDetails(&$row, &$lists, $last, $created, &$params)
    {
    	global $_DOCMAN, $_DMUSER;

    	?>
    	<fieldset class="input">
		<?php echo $params->render('params', 'Tableless');?>
    	</fieldset>
    	<?php
    }

    function moveDocumentForm($lists, $links, $paths, $data)
    {
        $action = _taskLink('doc_move_process', $data->id);

		ob_start();
        ?>
		<form action="<?php echo $action ?>" method="post" id="dm_frmmove" class="dm_form" >
		<fieldset class="input">
			<p>
				<label for="name"><?php echo _DML_DOC;?></label><br />
				<span id="name"><?php echo $data->dmname;?> (<?php echo $data->filename;?>)</span>
			</p>
			<p>
				<label for="catid"><?php echo _DML_MOVETO;?></label><br />
				<?php echo $lists['categories'];?>
			</p>
		</fieldset>
		<fieldset class="dm_button">
 			<p>
 				<input name="submit" class="button" value="<?php echo _DML_MOVETHEFILES;?>" type="submit" />
 			</p>
 		</fieldset>
        <?php echo DOCMAN_token::render();?>
 		</form>
 		<?php

 		$html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}

