<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: page_doclicense.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
*	$this->html->doclicense (string)(hardcoded, can change in future versions)
*   $this->html->license    (string)(the actual license text)
*/
?>

<?php echo $this->plugin('stylesheet', $this->theme->path."css/theme.css") ?>
<?php $theme = defined('_DM_J15') ? "css/theme15.css" : "css/theme10.css";
      echo $this->plugin('stylesheet', $this->theme->path . $theme) ?>

<h3 class="componentheading"><?php echo _DML_TPL_LICENSE_DOC;?></h3>

<div class="dm_license_body">
	<?php echo $this->license; ?>
</div>

<div class="dm_license_form">
<?php echo $this->html->doclicense ?>
</div>

<div class="dm_taskbar">
<ul>
    <li><a href="javascript: history.go(-1);"><?php echo _DML_TPL_BACK ?></a></li>
</ul>
</div>


