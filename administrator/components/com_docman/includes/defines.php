<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: defines.php 655 2008-03-20 21:18:22Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

// Put all static value defines here that are neither language nor
// configuration specific

// Paths
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

// Version
define('_DM_VERSION', '1.4.0 RC3');

// Help URL
define('_DM_HELP_URL', 'http://www.joomlatools.org/products/docman/faq/');

// DOCMAN mainframe types
define('_DM_TYPE_UNKNOW'		, 0);
define('_DM_TYPE_UNKNOWN'       , 0);
define('_DM_TYPE_SITE'			, 1);
define('_DM_TYPE_ADMIN'			, 2);
define('_DM_TYPE_MODULE'		, 3);
define('_DM_TYPE_DOCLINK'		, 4);

// Permissions for Documents
// NB: There one special flags used (don't use!):
// _NOOWNER is a special flag that is used to tell us they
// didn't pick from a list.

define('_DM_PERMIT_GROUP' 		, -10); // Docman groups (if < _DM_OWNER_GROUP)
define('_DM_PERMIT_NOOWNER' 	, -9); 	// Special flag

define('_DM_PERMIT_PUBLISHER'  	, -3);  //Joomla publisher group
define('_DM_PERMIT_EDITOR'    	, -6);  //Joomla editor group
define('_DM_PERMIT_AUTHOR'   	, -4);  //Joomla author group

define('_DM_PERMIT_CREATOR'   	, -2); 	// Permit the creator only
define('_DM_PERMIT_EVERYONE' 	, -1);
define('_DM_PERMIT_EVERYBODY' 	, -1); 	// Alias...
define('_DM_PERMIT_NOACCESS' 	, _DM_PERMIT_EVERYBODY);

define('_DM_PERMIT_REGISTERED'	, 0);
define('_DM_PERMIT_USER' 		, 0); // if > _DM_PERMIT_USER

// Permissions for Category Access Level (1.2+)
define('_DM_ACCESS_PUBLIC' 		, 0);
define('_DM_ACCESS_REGISTERED' 	, 1);
define('_DM_ACCESS_SPECIAL' 	, 2);

// Grant GUEST Users access (Against config 'registered')
define('_DM_GRANT_NO' 	, 0);
define('_DM_GRANT_X' 	, 1); // Execute == browse (like unix)
define('_DM_GRANT_RX' 	, 2); // Read/Exe == download/browse

define('_DM_GRANT_NONE' , _DM_GRANT_NO);

define('_DM_ASSIGN_NONE' 			 , 0);
define('_DM_ASSIGN_BY_AUTHOR' 		 , 0x0001);
define('_DM_ASSIGN_BY_EDITOR' 		 , 0x0002);
define('_DM_ASSIGN_BY_AUTHOR_EDITOR' , 0x0003);

define('_DM_AUTHOR_NONE' 			, 0);
define('_DM_AUTHOR_CAN_READ' 		, 0x0001);
define('_DM_AUTHOR_CAN_EDIT' 		, 0x0002);
define('_DM_AUTHOR_CAN_READ_EDIT' 	, 0x0003);

// special compatibility mode
define('_DM_SPECIALCOMPAT_DM13'     , 0);
define('_DM_SPECIALCOMPAT_J10'      , 1);

// Validation for uploads
define('_DM_VALIDATE_NAME' 		, 0x0001);
define('_DM_VALIDATE_PATH' 		, 0x0002);
define('_DM_VALIDATE_EXT' 		, 0x0004); // Extension
define('_DM_VALIDATE_SIZE' 		, 0x0008);
define('_DM_VALIDATE_EXISTS'	, 0x0010);
define('_DM_VALIDATE_PROTO' 	, 0x0020); // Protocol (URL transfer )

// Hard-coded filename regexes to reject, separate by '|'.
define('_DM_FNAME_REJECT'       , "\.htaccess");

// Meta-validate values
define('_DM_VALIDATE_ADMIN' 	, _DM_VALIDATE_NAME | _DM_VALIDATE_PATH | _DM_VALIDATE_PROTO | _DM_VALIDATE_EXISTS);
define('_DM_VALIDATE_USER' 		, 0x00ff);
define('_DM_VALIDATE_ALL' 		, 0x00ff);
define('_DM_VALIDATE_USER_ALL'       , 0x00ff); // alias
define('_DM_VALIDATE_DEFAULT'	, 0x00ff);

// Special tags for files:
define('_DM_DOCUMENT_LINK' , "Link: ");
define('_DM_DOCUMENT_LINK_LNG', 6);

// Images
define( '_DM_ICONPATH', '/administrator/components/com_docman/images/');
