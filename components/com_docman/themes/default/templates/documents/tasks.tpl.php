<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: tasks.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
* Display the document tasks (called by document/list_item.tpl.php and documents/document.tpl.php)
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*	$this-	>doc->buttons (array) : holds the tasks a user can preform on a
*document
*/

foreach($this->doc->buttons as $button) {
    $popup = ($button->params->get('popup', false)) ? 'type="popup"' : '';
    $attr = '';
    if($class = $button->params->get('class', '')) {
    	$attr = 'class="' . $class . '"';
    }
	?><li <?php echo $attr?>>
        <a href="<?php echo $button->link?>" <?php echo $popup?>>
            <?php echo $button->text ?>
        </a>
    </li><?php
}