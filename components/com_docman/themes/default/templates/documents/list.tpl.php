<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: list.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
* Display the documents list (required)
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*	$this->items (array)  : holds an array of dcoument items
*	$this->order (object) : holds the document list order information
*/
?>

<?php if(count($this->items)) { ?>
    <div id="dm_docs">
    <h3><?php echo _DML_TPL_DOCS;?><span><?php echo _DML_TPL_DATEADDED;?></span></h3>
    <?php
    /*
     * Include the documents list ordering template
    */
    ?>
    <?php include $this->loadTemplate('documents/list_order.tpl.php'); ?>
    <?php /*<dl >*/?>
    <?php
        /*
         * Include the list_item template and pass the item to it
        */
    	foreach($this->items as $item) :
    		$this->doc = &$item; //add item to template variables
    		include $this->loadTemplate('documents/list_item.tpl.php');
    	endforeach;
    ?>
    <?php /*</dl >*/?>
    </div>
<?php } else { ?>
    <br />
    <div id="dm_docs">
        <i><?php echo _DML_TPL_NO_DOCS ?></i>
    </div>
<?php } ?>