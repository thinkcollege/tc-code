<?php

/**
* Outputs a tooltip.
*
* $Id: Savant2_Plugin_calendar.php 561 2008-01-17 11:34:40Z mjaz $
* @author Johan Janssens <johan.janssens@users.sourceforge.net>
* @package Savant2
* @license http://www.gnu.org/copyleft/lesser.html LGPL
*
*/

require_once dirname(__FILE__) . '/Plugin.php';

class Savant2_Plugin_calendar extends Savant2_Plugin
{
    function plugin()
    {
        DOCMAN_Compat::calendarJS();
    }
}

