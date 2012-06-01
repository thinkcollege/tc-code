<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: pagenav.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
* Display the pagenav (required)
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*	$this->pagenav (object) : the pagenav object
*	$this->link    (nuber)  : the full page link
*
*/
?>

<div id="dm_nav">
<?php echo $this->pagenav->writePagesLinks( $this->link );?>
	<div>
	<?php echo $this->pagenav->writePagesCounter();?>
	</div>
</div>

