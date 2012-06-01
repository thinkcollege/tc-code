<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: page_docedit.tpl.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

/**
 * Default DOCman Theme
 *
 * Creator:  The DOCman Development Team
 * Website:  http://www.joomlatools.org/
 * Email:    support@joomlatools.org
 * Revision: 1.4
 * Date:     February 2007
 **/

/*
* Display the move document form (required)
*
* This template is called when u user preform a move operation on a document.
*
* General variables  :
*	$this->theme->path (string) : template path
*	$this->theme->name (string) : template name
*	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Preformatted variables :
*	$this->html->docedit (string)(hardcoded, can change in future versions)
*/
?>

<?php $this->splugin('pagetitle', _DML_TPL_TITLE_EDIT ) ?>

<?php echo $this->plugin('stylesheet', $this->theme->path."css/theme.css") ?>
<?php $theme = defined('_DM_J15') ? "css/theme15.css" : "css/theme10.css";
      echo $this->plugin('stylesheet', $this->theme->path . $theme) ?>
<?php echo $this->plugin('overlib'); ?>
<?php echo $this->plugin('calendar'); ?>
<?php echo $this->plugin('validator'); ?>

<?php echo $this->html->menu; ?>

<h2 id="dm_title"><?php echo _DML_TPL_TITLE_EDIT;?></h2>

<ul class="dm_toolbar">
<li><a title="Cancel" class="dm_btn" id="dm_btn_cancel" href="javascript:submitbutton('cancel');" ><?php echo _DML_CANCEL?></a></li>
<li><a title="Save"   class="dm_btn" id="dm_btn_save"   href="javascript:submitbutton('save');"><?php echo _DML_SAVE?></a></li>
</ul>

<?php echo $this->html->docedit ?>

<ul class="dm_toolbar">
<li><a title="Cancel" class="dm_btn" id="dm_btn_cancel" href="javascript:submitbutton('cancel');" ><?php echo _DML_CANCEL?></a></li>
<li><a title="Save"   class="dm_btn" id="dm_btn_save"   href="javascript:submitbutton('save');"><?php echo _DML_SAVE?></a></li>
</ul>

<div class="clr"></div>

<script language="javascript" type="text/javascript">
<!--
	list = document.getElementById('dmthumbnail');
	img  = document.getElementById('dmthumbnail_preview');
	list.onchange = function() {
		var index = list.selectedIndex;
		if(list.options[index].value!='') {
			img.src = 'images/stories/' + list.options[index].value;
		} else {
			img.src = 'images/blank.png';
		}
	}
//-->
</script>

