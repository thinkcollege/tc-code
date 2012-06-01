<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: page_docupload.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
* Display the upload document form (required)
*
* This template is called when u user preform a upload operation on a document.
*
* General variables  :
*	$this->theme->path (string) : template path
*	$this->theme->name (string) : template name
*	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*	$this->step   (number)  : holds the current step
*   $this->update (boolean) : true, if we are updating a document
*   $this->method (string)  : hols the upload method used (http, link or transfer)
*
* Preformatted variables :
*	$this->html->docupload (string)(hardcoded, can change in future versions)
*
*/
?>

<?php $this->splugin('pagetitle', _DML_TPL_TITLE_UPLOAD ) ?>

<?php echo $this->plugin('stylesheet', $this->theme->path."css/theme.css") ?>
<?php $theme = defined('_DM_J15') ? "css/theme15.css" : "css/theme10.css";
      echo $this->plugin('stylesheet', $this->theme->path . $theme) ?>
<?php echo $this->plugin('overlib'); ?>

<?php echo $this->html->menu; ?>

<?php
if($this->update) :
?><h2 id="dm_title"><?php echo _DML_TPL_TITLE_UPDATE;?></h2><?php
else :
?><h2 id="dm_title"><?php echo _DML_TPL_TITLE_UPLOAD;?></h2><?php
endif;
?>

<?php
switch($this->step) :
	case '1' :  include $this->loadTemplate('upload/step_1.tpl.php');  break;
	case '2' :  include $this->loadTemplate('upload/step_2.tpl.php');  break;
	case '3' :  include $this->loadTemplate('upload/step_3.tpl.php');  break;
endswitch;
?>