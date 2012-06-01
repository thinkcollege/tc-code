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

class MENU_file_index {

	function CONFIG_MENU() {

		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::saveedit();
		mosMenuBar::spacer();
		mosMenuBar::custom('index', '../components/com_file_index/images/index.png', '../components/com_file_index/images/index_f2.png', 'index', false);
        mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	
	function EDIT_MENU(){
	
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::save( 'filesave' );
		mosMenuBar::spacer();
		mosMenuBar::cancel( 'filecancel' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	
	function INDEX_MENU() {

		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::back();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
	
	function INDEXED_MENU() {

		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::custom('reindex', '../components/com_file_index/images/index.png', '../components/com_file_index/images/index_f2.png', 'Re-Index', false);
		mosMenuBar::spacer();
		mosMenuBar::custom('deleteindex', '../images/delete.png', '../images/delete_f2.png', 'Delete', false);
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function DEFAULT_MENU() {

		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
}
?>
