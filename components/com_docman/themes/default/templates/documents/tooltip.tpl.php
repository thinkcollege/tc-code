<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: tooltip.tpl.php 561 2008-01-17 11:34:40Z mjaz $
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
* Display document details (called by document/list_item.tpl.php)
*
* General variables  :
*	$this->theme->path (string) : template path
* 	$this->theme->name (string) : template name
* 	$this->theme->conf (object) : template configuartion parameters
*	$this->theme->icon (string) : template icon path
*   $this->theme->png  (boolean): browser png transparency support
*
* Template variables :
*  $this->doc->data	 (object) : holds the document data
*  $this->doc->links (object) : holds the document operations
*  $this->doc->paths (object) : holds the document paths
*/

if($this->theme->conf->details_filename)
{
	echo $this->doc->data->filename;
}

if($this->theme->conf->details_filesize)
{
    echo ' ('.$this->doc->data->filesize.') ';
}

if($this->theme->conf->details_filetype)
{
    echo $this->doc->data->mime;
}