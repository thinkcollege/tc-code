<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: list_item.tpl.php 625 2008-02-22 21:12:47Z mjaz $
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
* Display a documents list item (called by document/list.tpl.php)
*
* This template is called when u user preform browse the docman
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support


* Template variables :
*   $this->doc->data  (object) : holds the document data
*   $this->doc->links (object) : holds the document operations
*   $this->doc->paths (object) : holds the document paths
*/

if(!$this->doc->data->approved) {
	?><div class="dm_unapproved"><?php
} elseif(!$this->doc->data->published) {
	?><div class="dm_unpublished"><?php
} elseif($this->doc->data->checked_out) {
    ?><div class="dm_checked_out"><?php
} else {
	?><div><?php
}

//output document image
switch($this->theme->conf->doc_image) :
 	case 0 :  //none
		//do nothing
	break;

 	case 1 :   //icon
        if(isset($this->doc->buttons['download'])) {
            ?><a class="dm_icon" href="<?php echo $this->doc->buttons['download']->link;?>"><?php
        } else {
            ?><a class="dm_icon"><?php
        }
		?>
		<img src="<?php echo $this->doc->paths->icon;?>" alt="file icon" />
		</a>
		<?php
	break;

 	case 2  :  //thumb
 		if($this->doc->data->dmthumbnail) {
            if(isset($this->doc->buttons['download'])) {
                ?><a class="dm_thumb" href="<?php echo $this->doc->buttons['download']->link;?>"><?php
            } else {
                ?><a class="dm_thumb"><?php
            }
    		?>
            <img src="<?php echo $this->doc->paths->thumb; ?>" alt="<?php echo $this->doc->data->dmname ?>" />
    		</a>
     		<?php
        }
 	break;
endswitch;

//output document link
if(isset($this->doc->buttons['download'])) :
?><a class="dm_name" href="<?php echo $this->doc->buttons['download']->link;?>"><?php
else :
?><a class="dm_name"><?php
endif;
	echo $this->doc->data->dmname;
    if($this->doc->data->new) :
        ?><span class="dm_new"><?php echo $this->doc->data->new ?></span><?php
    endif;
 	if($this->doc->data->hot) :
 		?><span class="dm_hot"><?php echo $this->doc->data->hot ?></span><?php
 	endif;

 	if($this->theme->conf->item_tooltip) :
 		$this->item = &$this->doc;
 		$tooltip = $this->fetch('documents/tooltip.tpl.php');
 		$icon    = $this->theme->path."images/icons/16x16/tooltip.png";
 		$this->plugin('tooltip', $this->doc->data->id, '', $tooltip, $icon);
 	endif;
?>
</a>

<?php
//output document date
if ( $this->theme->conf->item_date ) :
    ?>
    <span class="dm_date">
       <?php $this->plugin('dateformat', $this->doc->data->dmdate_published, _DML_TPL_DATEFORMAT_SHORT); ?>
    </span>
    <?php
endif;

//output document counter
if ( $this->theme->conf->item_hits  ) :
    ?>
    <span class="dm_counter">
        <?php echo _DML_TPL_HITS;?>: <?php echo $this->doc->data->dmcounter;?>
    </span>
    <?php
endif;

?>


</div>

<?php

//output document description
if ( $this->theme->conf->item_description AND $this->doc->data->dmdescription ) :
	?>
	<div class="dm_description">
		<?php echo $this->doc->data->dmdescription;?>
	</div>
	<?php
endif;

//output document url
if ( $this->theme->conf->item_homepage && $this->doc->data->dmurl != '') :
	?>
 	<div class="dm_homepage">
		<?php echo _DML_TPL_HOMEPAGE;?>: <a href="<?php echo $this->doc->data->dmurl;?>"><?php echo $this->doc->data->dmurl;?></a>
	</div>
	<?php
endif;

?>
<div class="clr"></div>
<div class="dm_taskbar">
    <ul>
    <?php include $this->loadTemplate('documents/tasks.tpl.php');  ?>
    </ul>
</div>
<div class="clr"></div>
<div class="dm_separator"></div>
<div class="clr"></div>
