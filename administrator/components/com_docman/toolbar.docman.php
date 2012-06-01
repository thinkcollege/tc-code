<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: toolbar.docman.php 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

require_once($mainframe->getPath('toolbar_html'));
require_once($mainframe->getPath('toolbar_default'));

if(defined('_DM_J15')) {
    require_once dirname(__FILE__) . '/toolbar.docman.class15.php';
} else {
    require_once dirname(__FILE__) . '/toolbar.docman.class.php';
};

global $section;

if ($task == "cpanel") {
    TOOLBAR_docman::CPANEL_MENU ();
} elseif ($task=='credits'){
    TOOLBAR_docman::CREDITS_MENU ();
} else {
    switch ($section) {
        case "categories" : {
                switch ($task) {
                    case "new":
                    case "edit":
                        TOOLBAR_docman::EDIT_CATEGORY_MENU ();
                        break;

                    case "show" :
                    default :
                        TOOLBAR_docman::CATEGORIES_MENU ();
                }
            }
            break;

        case "documents" : {
                switch ($task) {
                    case "new":
                    case "edit":
                        TOOLBAR_docman::NEW_DOCUMENT_MENU();
                        break;
                    case "move_form":
                        TOOLBAR_docman::MOVE_DOCUMENT_MENU();
                        break;
                    case "copy_form":
                        TOOLBAR_docman::COPY_DOCUMENT_MENU();
                        break;
                    case "show":
                    default:
                        TOOLBAR_docman::DOCUMENTS_MENU();
                }
            }
            break;

        case "files" : {
                switch ($task) {
                    case "new":
                        TOOLBAR_docman::NEW_DOCUMENT_MENU();
                        break;
                    case "upload":
                        TOOLBAR_docman::UPLOAD_FILE_MENU();
                        break;
                    case "show":
                    default:
                        TOOLBAR_docman::FILES_MENU();
                        break;
                }
            }
            break;

        case "groups" : {
                switch ($task) {
                    case "emailgroup":
                        TOOLBAR_docman::EMAIL_GROUPS_MENU();
                        break;
                    case "new":
                    case "edit":
                        TOOLBAR_docman::EDIT_GROUPS_MENU();
                        break;
                    case "show":
                    default:
                        TOOLBAR_docman::GROUPS_MENU();
                }
            }
            break;

        case "licenses" : {
                switch ($task) {
                    case "new":
                    case "edit":
                        TOOLBAR_docman::EDIT_LICENSES_MENU();
                        break;
                    case "show":
                    default:
                        TOOLBAR_docman::LICENSES_MENU();
                }
            }
            break;

        case "logs" : {
                switch ($task) {
                    case "show":
                    default:
                        TOOLBAR_docman::LOGS_MENU();
                }
            }
            break;

        case "themes" : {
                switch ($task) {
                    case "new":
                        TOOLBAR_docman::NEW_THEME_MENU ();
                        break;
                    case "edit":
                        TOOLBAR_docman::EDIT_THEME_MENU();
                        break;
                    case "edit_css":
                        TOOLBAR_docman::CSS_THEME_MENU();
                        break;
                    case "show":
                    default :
                        TOOLBAR_docman::THEMES_MENU ();
                }
            }
            break;
        /* TEMPORARILY REMOVED UPDATES (mjaz)
        case "updates" : {
                switch ($task) {
                    default :
                        TOOLBAR_docman::UPDATES_MENU ();
                }
            }
            break;
           */
        case "config" : {
                switch ($task) {
                    case "show":
                    default:
                        TOOLBAR_docman::CONFIG_MENU ();
                }
            }
            break;

        case "cleardata":
            TOOLBAR_docman::CLEARDATA_MENU ();
            break;

        case "docman" :
        default : {
                switch ($task) {
                    case "stats":
                        TOOLBAR_docman::STATS_MENU ();
                        break;

                    case "cpanel":
                    default:
                        TOOLBAR_docman::CPANEL_MENU ();
                        break;
                }
            }
    }
}

