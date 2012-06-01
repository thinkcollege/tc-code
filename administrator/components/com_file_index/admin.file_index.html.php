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
include 'config.file_index.php';
class HTML_file_index {
	
	
 	function showFileIndex( $option,&$results ) {
	?><form action="index2.php" method="post" name="adminForm" id="adminForm">
	<table class="adminheading">
		<tr>
			<td><img src="components/com_file_index/images/PDF-indexer.png" alt="" width="173" height="100" border="0"> Indexing Files</td>
		</tr>
	</table>
	<table class="adminlist">
	  	<tr>
			<th align="left" width="250">File Name</th>
			<th align="left" width="250">Results</th>
			<th align="left">Errors</th>
		</tr>
		<?php echo $results; ?>
	</table>
	<?php
	}
	function FileEdit( &$option, &$row, &$pageNav, $lists  ) {
	global $database;
	
	$tabs = new mosTabs(1);
	mosMakeHtmlSafe( $row );
	mosCommonHTML::loadOverlib();
	?><script language="javascript" type="text/javascript">
	    function submitbutton(pressbutton) {
	      var form = document.adminForm;
	      if (pressbutton == 'filecancel') {
		  	//alert('Pressbutton = ' + pressbutton);
	        submitform( pressbutton );
	        return;
	      } else {
		  	//alert('Pressbutton = ' + pressbutton);
	        submitform( pressbutton );
	      }
	    }
	    </script>
		<form action="index2.php" method="post" name="adminForm" id="adminForm">
		<table class="adminheading">
			<tr>
				<td><img src="components/com_file_index/images/PDF-indexer.png" alt="" width="173" height="100" border="0"> Indexing Files</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td width="60%" valign="top">
						<table width="100%" class="adminform">
							<tr>
								<td width="100%">
									<table cellspacing="0" cellpadding="0" border="0" width="100%">
										<tr>
											<th colspan="2">
											PDF Details
											</th>
										</tr>
										<tr>
											<td>
											File Name:
											</td>
											<td>
											<input class="text_area" type="text" name="title" size="60" value="<?php echo $row[1]; ?>" readonly /> <font color="#FF0000">(Non-editable)</font>
											</td>
										</tr>
										<tr>
											<td>
											Path:
											</td>
											<td>
											<input class="text_area" type="text" name="location" size="80" value="<?php echo $row[2]; ?>" readonly /> <font color="#FF0000">(Non-editable)</font>
											</td>
										</tr>
										
										<tr>
											<td colspan="2">
											Index:<br><textarea cols="80" rows="30" name="description"><?php echo $row[3]; ?></textarea>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table></td>
				<td valign="top" width="40%">
				<?php
				$tabs->startPane("content-pane");
				$tabs->startTab("Publishing","publish-page");
				?>
				<table class="adminform">
				<tr>
					<th colspan="2">
					Security Info
					</th>
				</tr>
				<tr>
					<td valign="top" align="right">
					Access Level:
					</td>
					<td>
					<?php echo $lists['access']; ?> 
					</td>
				</tr>
				<tr>
					<td>
					Password:
					</td>
					<td>
					<input class="password" type="text" name="password" size="20" value="<?php echo $row[7]; ?>"  />
					</td>
				</tr>
				</table>
				<?php
				$tabs->endTab();
				$tabs->startTab("Link to Menu","link-page");
				?>
				<table class="adminform">
				<tr>
					<th colspan="2">
					Link to Menu
					</th>
				</tr>
				<tr>
					<td colspan="2">
					This will create a 'Link - URL item' in the menu you select
					<br /><br />
					</td>
				</tr>
				<tr>
					<td valign="top" width="90">
					Select a Menu
					</td>
					<td>
					<?php echo $lists['menuselect']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" width="90">
					Menu Item Name
					</td>
					<td>
					<input type="text" name="link_name" class="inputbox" value="" size="30" />
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
					<input name="menu_link" type="button" class="button" value="Link to Menu" onclick="submitbutton('menulink');" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
					</td>
				</tr>
				</table>
				<?php
				$tabs->endTab();
				$tabs->endPane();
				?>
			</td>
		</tr>
		</table>
		<input type="hidden" name="id" value="<?php echo $row[0]; ?>" />
		<input type="hidden" name="mask" value="0" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
	<?php
	}
	function showFileIndexed( &$option,&$rows,&$pageNav,&$task ) {
	global $my, $database;
	?>
	<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			if (pressbutton == 'reindex' || pressbutton == 'deleteindex') {
				if (document.adminForm.boxchecked.value == 0) {
					alert('Please make a selection from the list');
				} else {
				submitform(pressbutton);
				}
			}
		}
		</script>
	<form action="index2.php" method="post" name="adminForm" id="adminForm">
	<table class="adminheading">
		<tr>
			<td><img src="components/com_file_index/images/PDF-indexer.png" alt="" width="173" height="100" border="0"> Indexed Files</td>
		</tr>
	</table>
	<table class="adminlist">
	  	<tr>
			<th width="5">#</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th align="left">File Name</th>
			<th align="center" width="50" nowrap>Access</th>
			<th align="left" width="100" nowrap>File Size</th>
			<th align="center" width="15" nowrap>Password</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			$mid = $row->id;
			$link 	= 'index2.php?option=com_file_index&task=edit&id='. $mid;
			
			$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->title;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="Edit PDF Index" <?php
					if ($row->description=='' || strstr ( $row->description, "Error: " ) == TRUE){
					echo "style=\"color:red;\"";
					}else{
					echo "style=\"color:green;\"";
					}
					?>>
					<?php 
					echo $row->title; 
					if ($row->description=='' || strstr ( $row->description, "Error: " ) == TRUE){
					echo " (Error)";
					}
					?> 
					</a>
					<?php
				}
				?></td>
				<td align="center" nowrap><?php echo $row->restricted; ?></td>
				<td align="left" nowrap><?php echo $row->filesize; ?></td>
				<td align="center" nowrap><?php echo ($row->password)? "<font color=\"#FF0000\">Yes</font>":"No"; ?></td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
	</table>
	<?php echo $pageNav->getListFooter(); ?>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="<?php echo $task; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="hidemainmenu" value="0">
	</form>
	<?php
	}
	function showConfiguration( $option, &$indexManager, &$file_index_Public, &$file_index_Private ) {
		global $mosConfig_live_site;
		$act = $_POST['act'];
		//include 'config.file_index.php';
	?>
	    <script language="javascript" type="text/javascript">
	    function submitbutton(pressbutton) {
	      var form = document.adminForm;
	      if (pressbutton == 'cancel') {
	        submitform( pressbutton );
	        return;
	      } else {
	        submitform( pressbutton );
	      }
	    }
	    </script>
	
	  <form action="index2.php?task=save" method="post" name="adminForm" id="adminForm">
	  <table class="adminheading">
		<tr>
			<td><img src="components/com_file_index/images/PDF-indexer.png" alt="" width="173" height="100" border="0"> Configuration</td>
		</tr>
	  </table>
	  <table cellspacing="2" cellpadding="0" width="100%">
		  <tr>
			  <td width="70%" valign="top">
			  <table class="adminlist">
			  	<tr>
					<th align="left">
					Directory
					</th>
					<th align="left">
					Permissions
					</th>
				</tr>
			    <?php 
					$PublicArry = explode(",",$file_index_Public);
					$PrivateArry = explode(",",$file_index_Private);
					$directory = searchdir("../", "-1", "DIRS", 0);
					$countDirs = count($directory)-1;
					$k = 0;
					
					for ( $i = 0; $i <= $countDirs; $i++ ){
					$directory[$i] = substr($directory[$i], 3);  
					
					// if the directory contains a PDF then
					if ($handle = opendir('../'.$directory[$i])) {
					$countPDF = 0;
		    		while (false !== ($file = readdir($handle))) { 
						if (substr($file, -4) == '.pdf' || substr($file, -4) == '.PDF') { 
		            		$countPDF++; 
		        		} 
		    		}
		    		closedir($handle); 
					}
					if ($countPDF > 0){
				?> 
				<tr class="<?php echo "row$k"; ?>">
					<td><strong><?php echo $directory[$i]; ?></strong></td>
					<td><input type="radio" name="Index[<?php echo $i; ?>]" value="<?php echo $directory[$i]; ?>|0" <?php 
						if( in_array($directory[$i], $PublicArry) == true ){
						echo "checked";
						} ?>>Public <input type="radio" name="Index[<?php echo $i; ?>]" value="<?php echo $directory[$i]; ?>|1" <?php 
						if( in_array($directory[$i], $PrivateArry) == true ){
						echo "checked";
						} ?>>Registered <input type="radio" name="Index[<?php echo $i; ?>]" value="<?php echo $directory[$i]; ?>|2" <?php 
						if( in_array($directory[$i], $PublicArry) == true || in_array($directory[$i], $PrivateArry) == true ){
						
						}else{
						echo "checked";
						} ?>><font color="#FF0000">Do Not Index</font></td>
				</tr>
				<?php
					$k = 1 - $k;
				}
					}
				
				?> 
			  </table></td>
			  <td valign="top" width="30%">
				<table class="adminform">
					<tr>
						<th colspan="2">
						File Permissions Check
						</th>
					</tr>
					<tr>
						<td colspan="2">The following files must be CHMODed correctly</td>
					</tr>
					<?php 
					if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
					check_perms("config.file_index.php","0666","Writeable","Unwriteable");
					}else{
					check_perms("config.file_index.php","0777","Writeable","Unwriteable");
					}
					if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
					check_perms("includes/pdftotext.exe","0755","Executable","Unexecutable");
					}else{
					check_perms("includes/pdftotext","0755","Executable","Unexecutable");
					}
					$errors = 0;
					?>
					<tr>
						<th colspan="2">
						Server Settings
						</th>
					</tr>
					<tr>
						<td><strong>Operating System:</strong></td>
						<td><?php if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
						   echo "<strong><font color=\"#008000\">".PHP_OS."</font></strong>";
						}else{
						   echo "<strong><font color=\"#FF0000\">".strtoupper(substr(PHP_OS, 0, 3))."</font></strong>";
						   $errors++;
						}
						 ?></td>
					</tr>
					<tr>
						<td><strong>WWW Server:</strong></td>
						<td><?php if( php_sapi_name() == 'apache' || php_sapi_name() == 'apache2handler' || php_sapi_name() == 'cgi'){
						   echo "<strong><font color=\"#008000\">".php_sapi_name()."</font></strong>";
						}else{
						   echo "<strong><font color=\"#FF0000\">".php_sapi_name()."</font></strong>";
						   $errors++;
						}
						 ?></td>
					</tr>
					<tr>
						<td><strong>Safe Mode:</strong></td>
						<td><?php if( ini_get('safe_mode') ){
						   echo "<strong><font color=\"#FF0000\">ON</font></strong>";
						   $majorError = "yes";
						}else{
						   echo "<strong><font color=\"#008000\">OFF</font></strong>";					   
						}
						 ?></td>
					</tr>
					<?php if ($errors > 0){ ?>
					<tr>
						<td colspan="2"><div style="background-color:#EEEEEE ;	border: thin solid Red;	margin: 10px 10px 10px 10px; padding: 5px 5px 5px 5px;"><strong><font color="#FF0000">WARNING:</font></strong> <br>Your hosting environment <strong>may not</strong> meet the minimum requirements.  If you find that PDF indexer is not working properly can you contact <a href="mailto:nate@natemaxfield.com?subject=Service Request (<?php echo $mosConfig_live_site; ?>)">Nate Maxfield</a> for assistance.  This is only a warning and is not an indicator that anything is wrong.  Please try indexing before contacting me.</div></td>
					</tr>
					<?php } ?>
					<?php if ($majorError == "yes"){ ?>
					<tr>
						<td colspan="2"><div style="background-color:#EEEEEE ;	border: thin solid Red;	margin: 10px 10px 10px 10px; padding: 5px 5px 5px 5px;"><strong><font color="#FF0000">ERROR:</font></strong> <br>Your hosting environment <strong>does not</strong> meet the minimum requirements.  Safe Mode is enabled on your server and this script will not run in Safe Mode.  Please contact <a href="mailto:nate@natemaxfield.com?subject=Service Request (<?php echo $mosConfig_live_site; ?>)">Nate Maxfield</a> for assistance.  If you can easily enable Safe Mode, please do so now and refresh this page.</div></td>
					</tr>
					<?php } ?>
				</table>
		 	</td>
		 </tr>
	 </table>
	  
	  <input type="hidden" name="task" value="" />
	  <input type="hidden" name="act" value="<?php echo $act; ?>" />
	  <input type="hidden" name="option" value="<?php echo $option; ?>" />
	</form>
	<?php
	}
	
	
}
function searchdir ( $path , $maxdepth = -1 , $mode = "DIRS" , $d = 0 )
{
   if ( substr ( $path , strlen ( $path ) - 1 ) != '/' ) { $path .= '/' ; }      
   $dirlist = array () ;
   if ( $mode != "FILES" ) { $dirlist[] = $path ; }
   if ( $handle = opendir ( $path ) )
   {
       while ( false !== ( $file = readdir ( $handle ) ) )
       {
           if ( $file != '.' && $file != '..' )
           {
               $file = $path . $file ;
               if ( ! is_dir ( $file ) ) { if ( $mode != "DIRS" ) { $dirlist[] = $file ; } }
               elseif ( $d >=0 && ($d < $maxdepth || $maxdepth < 0) )
               {
                   $result = searchdir ( $file . '/' , $maxdepth , $mode , $d + 1 ) ;
                   $dirlist = array_merge ( $dirlist , $result ) ;
               }
       }
       }
       closedir ( $handle ) ;
   }
   if ( $d == 0 ) { natcasesort ( $dirlist ) ; }
   return ( $dirlist ) ;
}

function check_perms($path,$perm,$good,$bad)
{
	$filename = $path;
	$path = "components/com_file_index/" . $path;
    clearstatcache();
    $configmod = substr(sprintf('%o', fileperms($path)), -4); 
    $trcss = (($configmod == $perm || $configmod == "0777") ?  "<b><font color=\"green\">$good</font></b>" : "<font color=\"red\"><b>$bad</b> (please set to $perm)</font>");
    echo "<tr>"; 
    echo "<td style=\"border:0px;\"><b>". $filename ."</b></td>"; 
    echo "<td style=\"border:0px;\">". $trcss ."</td>"; 
    echo "</tr>";  
} 

?>