<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: download.html.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_HTML_DOWNLOAD')) {
    return;
} else {
    define('_DOCMAN_HTML_DOWNLOAD', 1);
}

class HTML_DMDownload
{
    function licenseDocumentForm(&$links, &$paths, &$data, $inline=0)
    {
        $action = _taskLink('license_result', mosGetParam( $_REQUEST, 'gid', 0) , array('bid' => $data->id));

        ob_start();
        ?>
		<form action="<?php echo $action;?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="inline" value="<?php echo $inline?>" />
			<input type="radio" name="agree" value="0" checked /><?php echo _DML_DONT_AGREE;?>
			<input type="radio" name="agree" value="1" /><?php echo _DML_AGREE;?>
			<input name="submit" value="<?php echo _DML_PROCEED;?>" type="submit" />
		</form>

		<?php

		$html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}

