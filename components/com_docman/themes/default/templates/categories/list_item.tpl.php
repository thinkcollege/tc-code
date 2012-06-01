<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: list_item.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
* Display a category list item (called by categories/list.tpl.php)
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*	$item->data		(object) : holds the category data
*  $item->links 	(object) : holds the category operations
*  $item->paths 	(object) : holds the category paths
*/

?>
<div class="dm_row">
<?php
switch ($this->theme->conf->cat_image) :
	case 0 : //none

		//do nothing
	break;

	case 1 : //icon
		?><a class="dm_icon" href="<?php echo $item->links->view;?>"><img src="<?php echo $item->paths->icon;?>" alt="folder icon" /></a><?php
	break;

	case 2 : //thumb
		if($item->data->image) :
		?><a class="dm_thumb" href="<?php echo $item->links->view;?>"><img src="<?php echo $item->paths->thumb;?>" alt="<?php echo $item->data->name;?>" /></a><?php
		endif;
	break;
endswitch;
?>
    <span class="dm_files"><?php echo $item->data->files;?></span>
	<a class="dm_name" href="<?php echo $item->links->view;?>"><?php echo $item->data->name;?></a>


    <?php
    if($item->data->description) :
        ?><div class="dm_description"><?php echo $item->data->description;?></div><?php
    endif;
    ?>

</div>

<div class="clr"></div>
