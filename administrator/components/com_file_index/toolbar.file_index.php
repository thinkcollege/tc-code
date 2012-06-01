<?php
/**
* PDF Index - A Mambo/Joomla PDF Indexing Component

* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* @version 2.4
* @package File Index
* @copyright (C) 2008 by Nate Maxfield
**/
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );
require_once( $mainframe->getPath( 'toolbar_default' ) );

switch ($task) {
	
	case "config":
		MENU_file_index::CONFIG_MENU();
		break;
		
	case "index":
		MENU_file_index::INDEX_MENU();
		break;
		
	case "fileindexed":
		MENU_file_index::INDEXED_MENU();
		break;
		
	case "edit":
		MENU_file_index::EDIT_MENU();
		break;
	
	default:
		MENU_file_index::DEFAULT_MENU();
		break;

}
?>