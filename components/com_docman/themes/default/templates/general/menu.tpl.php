<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: menu.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
* Display the menu (required)
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*	$this->links (object) : holds the different menu links
*   $this->perms (number) : upload user permissions
*
*/

if( !$this->theme->conf->menu_home
    AND !$this->theme->conf->menu_search
    AND !$this->theme->conf->menu_upload
    AND $this->perms->upload != DM_TPL_AUTHORIZED) {
        // No buttons to show
    	return;
    }

?>

<div id="dm_header">
<?php
if($this->theme->conf->menu_home) :
	?>
	<div>
		<a href="<?php echo $this->links->home;?>">
			<img src="<?php echo $this->theme->icon;?>home.png" alt="<?php echo _DML_TPL_CAT_VIEW;?>" /><br />
			<?php echo _DML_TPL_CAT_VIEW;?>
		</a>
	</div>
	<?php
endif;
if($this->theme->conf->menu_search) :
	?>
	<div>
		<a href="<?php echo $this->links->search;?>">
			<img src="<?php echo $this->theme->icon;?>search.png" alt="<?php echo _DML_TPL_SEARCH_DOC;?>" /><br />
			<?php echo _DML_TPL_SEARCH_DOC;?>
		</a>
	</div>
	<?php
endif;
	/*
	 * Check to upload permissions and show the appropriate icon/text
	 * Values for $this->perms->upload
	 *		- DM_TPL_AUTHORIZED 	: the user is authorized to upload
	 *		- DM_TPL_NOT_LOGGED_IN  : the user isn't logged in
	 *		- DM_TPL_NOT_AUTHORIZED : the user isn't authorized to upload
	*/
if($this->theme->conf->menu_upload) :
	switch($this->perms->upload) :
		case DM_TPL_AUTHORIZED :
		?>
		<div>
			<a href="<?php echo $this->links->upload;?>">
				<img src="<?php echo $this->theme->icon;?>submit.png" alt="<?php echo _DML_TPL_SUBMIT;?>" /><br />
				<?php echo _DML_TPL_SUBMIT;?>
			</a>
		</div>
		<?php break;
	endswitch;
endif;
	?>
</div>

<div class="clr"></div>