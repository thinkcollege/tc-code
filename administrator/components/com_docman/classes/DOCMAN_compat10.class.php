<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: DOCMAN_compat10.class.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class DOCMAN_Compat {
    function mosLoadAdminModules( $position='left', $style=0 ) {
        return mosLoadAdminModules($position, $style);
    }

    function mosReadDirectory($path, $filter='.', $recurse=false, $fullpath=false) {
    	return mosReadDirectory( $path, $filter, $recurse, $fullpath);
    }

    function editorArea($areaname, $content, $name, $width, $height, $rows, $cols) {
        editorArea($areaname, $content, $name, $width, $height, $rows, $cols);
    }

    function calendarJS() {
        global $mainframe, $mosConfig_live_site, $mosConfig_absolute_path, $mosConfig_locale;

        $tmp_locale = substr($mosConfig_locale, 0, 2);

        // now try to get the locale data
        if (file_exists($mosConfig_absolute_path.'/includes/js/calendar/lang/calendar-' . $tmp_locale . '.js')) {
            $tmp_cal_source = $mosConfig_live_site . '/includes/js/calendar/lang/calendar-' . $tmp_locale . '.js';
        } else {
            $tmp_cal_source = $mosConfig_live_site . '/includes/js/calendar/lang/calendar-en.js';
        }

        ob_start();
        ?>
        <!-- Begin Calendar -->
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo $mosConfig_live_site;?>/includes/js/calendar/calendar-mos.css" title="green" />
        <script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/calendar/calendar.js"></script>
        <script language="JavaScript" type="text/javascript" src="<?php echo $tmp_cal_source;?>"></script>
        <!-- End Calendar -->
        <?php

        $html = ob_get_contents();
        ob_end_clean();

        $mainframe->addCustomHeadTag($html);
    }

    function calendar($name, $value) {
        ?>
        <input class="inputbox" type="text" name="<?php echo $name?>" id="<?php echo $name?>" size="25" maxlength="19" value="<?php echo $value; ?>" />
        <input type="reset" class="button" value="..." onclick="return showCalendar('<?php echo $name?>', 'y-mm-dd');" />
        <?php
    }

    /**
     * Removes illegal tags and attributes from html input
     */
    function inputFilter($html){
        $filter = new InputFilter(array(), array(), 1, 1);
        return $filter->process( $html );
    }
}