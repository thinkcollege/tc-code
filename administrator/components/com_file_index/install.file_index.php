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

defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');
function com_install() {
  global $database, $mosConfig_absolute_path;

  # Show installation result to user
  ?>
  <center>
  <table width="100%" border="0">
    <tr>
      <td>
        <strong>PDF File Index</strong><br/><br/>
		
        <font class="small">&copy; Copyright 2007 by Nate Maxfield</font><br/>
		<br>
		<?php
		// detect if it is windows.
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		echo "<font color=\"#FF0000\">Your server is Windows based and requires a slight modification to the script.  Locate the includes directory under administrator/components/com_file_index and rename the pdftotext.txt to pdftotext.exe.  This file was renamed so it could easily be sent through email.</font>";
		}
		?>
      </td>
    </tr>
  </table>
  <?php
	// Everything for owner, read and execute for others
	chmod($mosConfig_absolute_path . "/administrator/components/com_file_index/includes/pdftotext", 0755);
	
	// Everything for others
	chmod($mosConfig_absolute_path . "/administrator/components/com_file_index/config.file_index.php", 0777);
?> 
  </center>
  <?php
}
?>