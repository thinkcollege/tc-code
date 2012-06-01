<?php
/**
 * Language file
 * @author GranholmCMS
 * @link http://www.granholmcms.com
 */

# Don't allow direct linking
defined('_JEXEC') or die('Restricted access');

class definition_row {
	var $tname = '';
	var $tmail = '';
	var $tpage = '';
	var $tloca = '';
	var $tterm = '';
	var $tdefinition = '';
}

class HTML_definition {

	function deleteHTML ($letter, $catid) {
		$database =& JFactory::getDBO();
		$Itemid = JRequest::getInt('Itemid');
		$id = intval(mosGetParam( $_REQUEST, 'id', 0 ));
		$submit = mosGetParam($_POST,'submit','');

		# Main Part of Subfunction
		$definition =& definitionDefinition::getInstance();
		if ($definition->isEditor()){
			if ($submit) {
				$sql = "DELETE FROM #__definition WHERE id='$id'";
				$database->setquery($sql);
				$database->query();
				echo "<script type='text/javascript'> alert('"._DEFINITION_DELMESSAGE."'); document.location.href='index.php?option=com_definition&func=display&Itemid=$Itemid&letter=$letter&catid=$catid';</script>";
			}
			else {
				$sql="SELECT * FROM #__definition WHERE id = '$id'";
				$database->setQuery($sql);
				$row = $database->loadObject();
				#Show the Original Entry
				echo "<table width='100%' border='0' cellspacing='1' cellpadding='4'>";
				echo "<tr><td width='30%' height='20' class='sectiontableheader'>"._DEFINITION_TERM."</td>";
				echo "<td width='70%' height='20' class='sectiontableheader'>"._DEFINITION_DEFINITION."</td></tr>";
				echo "<tr class='sectiontableentry1'><td width='30%' valign='top'><b>$row->tname</b><br/>";
				if ($row->tloca) echo "<br /><span class='small'>"._DEFINITION_FROM." $row->tloca</span>";
				if ($row->tmail) echo "<a href='mailto:$row->tmail'><img src='components/com_definition/images/email.gif' alt='$row->tmail' hspace='3' border='0'></a>";
				if ($row->tpage) echo "<a href='$row->tpage' target='_blank'><img src='components/com_definition/images/homepage.gif' alt='$row->tpage' hspace='3' border='0'></a>";
				echo "$row->tdate</td>";
				$origtext = preg_replace("/(\015\012)|(\015)|(\012)/","&nbsp;<br />", $row->tdefinition);
				echo "<td width='70%' valign='top'><span class='small'>$row->tterm<hr></span>$origtext</td></tr>";
				echo "</table>";
				echo "<form method='post' action='index.php?option=com_definition&Itemid=$Itemid&func=delete&id=$id'>";
				echo "<input type='hidden' name='catid' value='$catid'>";
				echo "<input type='hidden' name='letter' value='$letter'>";
				echo "<input class='button' type='submit' name='submit' value='"._DEFINITION_ADELETE."'></form>";
			}
		}
		else {
			$url = sefRelToAbs("index.php?option=com_definition&Itemid=$Itemid");
			echo "<p><a href='$url'>Back</a></p>";
		}
	}
	
	function searchHTML () {
		
	}
	
	function commentHTML ($letter, $catid) {
		global $Itemid, $database;
		# Javascript for SmilieInsert and Form Check
		?>
		<script type="text/javascript">
		function validate(){
			if (document.definitionForm.tcomment.value==''){ }
			else {
				document.definitionForm.action = 'index.php';
				document.definitionForm.submit();
			}
		}
		</script>
		<tr><td colspan="2">
		<?php
		# Main Part of Subfunction
		$definition =& definitionDefinition::getInstance();
		if ($definition->isEditor()){
			$id=intval(JRequest::getVar('id',0 ));
			if (JRequest::getVar('opt','' )=='del'){
				$sql = "UPDATE #__definition SET tcomment='' WHERE id=$id";
				$database->setQuery($sql);
				$database->query();
				echo "<script> alert('"._DEFINITION_COMMENTDELETED."'); document.location.href='index.php?option=com_definition&func=display&letter=$letter&Itemid=$Itemid&catid=$catid';</script>";
			}
			else {
				$tcomment = JRequest::getVar('tcomment','');
				if ($tcomment) {
					$tcomment = $database->getEscaped($tcomment);
					$sql = "UPDATE #__definition SET tcomment='$tcomment' WHERE id=$id";
					$database->setQuery($sql);
					$database->query();
					echo "<script> alert('"._DEFINITION_COMMENTSAVED."'); document.location.href='index.php?option=com_definition&func=display&letter=$letter&Itemid=$Itemid&catid=$catid';</script>";
				}
				else {
					$tname = JRequest::getVar('tname','');
					$sql="SELECT * FROM #__definition WHERE id = '$id'";
					$database->setQuery($sql);
					$row = $database->loadObject();
					#Show the Original Entry
					echo "<table width='100%' border='0' cellspacing='1' cellpadding='4'>";
					echo "<tr><td width='30%' height='20' class='sectiontableheader'>"._DEFINITION_NAME."</td>";
					echo "<td width='70%' height='20' class='sectiontableheader'>"._DEFINITION_ENTRY."</td></tr>";
					echo "<tr class='sectiontableentry1'><td width='30%' valign='top'><b>".$row->tterm."</b>";
					if ($tname<>"") echo "<br /><span class='small'>"._DEFINITION_AUTHOR.": ".$row->tname."</span>";
					echo "</td>";

					echo "<td width='70%' valign='top'><span class='small'>"._DEFINITION_SIGNEDON." $row->tdate<hr></span>$row->tdefinition</td></tr>";
					echo "<tr class='sectiontableentry1'><td width='30%' valign='top'>";
					if ($row->tloca<>"") echo _DEFINITION_FROM."<span class='small'>: ".$row->tloca."</span><br>";
					if ($row->tmail<>"") echo "<a href='mailto:".$row->tmail."'><img src='components/com_definition/images/email.gif' alt='".$row->tmail."' hspace='3' border='0'></a>";
					if ($row->tpage<>"") echo "<a href='".$row->tpage."' target='_blank'><img src='components/com_definition/images/homepage.gif' alt='".$row->tpage."' hspace='3' border='0'></a>";
					echo "</td></tr>";
					# Admins Comment here
					echo "<form name='definitionForm' action='index.php' target='_top' method='post'>";
					echo "<input type='hidden' name='id' value='$id' />";
					echo "<input type='hidden' name='letter' value='$letter' />";
					echo "<input type='hidden' name='catid' value='$catid' />";
					echo "<input type='hidden' name='option' value='com_definition' />";
					echo "<input type='hidden' name='Itemid' value='$Itemid' />";
					echo "<input type='hidden' name='func' value='comment' />";
					echo "<tr class='sectiontableentry2'><td valign='top'><b>"._DEFINITION_ADMINSCOMMENT."</b><br /><br />";
					echo "</td>";
					echo "<td valign='top'><textarea cols='40' rows='8' name='tcomment' class='inputbox'>".$row->tcomment."</textarea></td></tr>";
					echo "<tr><td><input type='button' name='send' value='"._DEFINITION_SENDFORM."' class='button' onclick='submit()' /></td>";
					echo "<td align='right'><input type='reset' value='"._DEFINITION_CLEARFORM."' name='reset' class='button' /></td></tr></table></form>";
				}
			}
		}
		else echo "<p><a href='index.php?option=com_definition&Itemid=$Itemid'>Back</a></p>";
		echo '</td></tr>';
	}
	
	function submitHTML ($letter, $catid) {
		global $mainframe;
		$database =& JFactory::getDBO();
		$Itemid = JRequest::getInt('Itemid');
		
        require(JPATH_SITE."/administrator/components/com_definition/config.definition.php");
		$id= JRequest::getInt('id',0 );
		# Check if Registered Users only
		$definition =& definitionDefinition::getInstance();
		if (!$definition->gl_anonentry AND !$definition->isUser()) echo _DEFINITION_ONLYREGISTERED;
		else {
			# Javascript for SmilieInsert and Form Check
			?>
			<script type="text/javascript">
			function validate(){
				if ((document.definitionForm.tname.value=='') || (document.definitionForm.tterm.value=='') || (document.definitionForm.tdefinition.value=='') || (document.definitionForm.catid.value=='0')){
					alert("<?php echo _DEFINITION_VALIDATE; ?>");
					return false;
				}
				else {
					return true;
				}
			}
			</script>
			<tr><td colspan="2">
			<form name='definitionForm' action='index.php' target='_top' method='post' onsubmit='return validate()'>
			<table align='center' width='90%' cellpadding='0' cellspacing='4' border='0'>
			<?php
			# Check if User is Admin and if he wants to edit
			if ((($definition->isEditor()) OR ($definition->isAdmin())) AND ($id)) {
				echo "<tr><td colspan='2'><input type='hidden' name='id' value='$id' /></td></tr>";
				$sql="SELECT * FROM #__definition WHERE id='$id'";
				$database->setQuery($sql);
				$row = $database->loadObject();
			}
			// get list of categories
			$categories[] = JHTML::_( 'select.option', '0', _SEL_CATEGORY );
			$database->setQuery( "SELECT id AS value, title AS text FROM #__categories WHERE section='com_definition' ORDER BY ordering" );
			$categories = array_merge( $categories, $database->loadObjectList() );
			if (count( $categories ) < 1) {
				$mainframe->redirect( "index.php?option=com_definition&itemid=".$Itemid, 'No categories exist. They must be created first. Please notify the administrator.' );
			}
			if (!isset($row)) $row =& new definition_row;
			$clist = JHTMLSelect::genericlist( $categories, 'catid', 'class="inputbox" size="1"','value', 'text', intval($catid));
			echo "<tr><td><input type='hidden' name='option' value='com_definition' />";
			echo "<input type='hidden' name='letter' value='$letter' />";
			echo "<input type='hidden' name='Itemid' value='$Itemid' />";
			echo "<input type='hidden' name='func' value='entry' /></td></tr>";
			echo "<tr><td width='130'>"._DEFINITION_ENTERNAME."</td><td><input type='text' name='tname' style='width:245px;' class='inputbox' value='$row->tname' /></td></tr>";
			echo "<tr><td width='130'>"._DEFINITION_ENTERMAIL."</td><td><input type='text' name='tmail' style='width:245px;' class='inputbox' value='$row->tmail' /></td></tr>";
			echo "<tr><td width='130'>"._DEFINITION_ENTERPAGE."</td><td><input type='text' name='tpage' style='width:245px;' class='inputbox' value='$row->tpage' /></td></tr>";
			echo "<tr><td width='130'>"._DEFINITION_ENTERLOCA."</td><td><input type='text' name='tloca' style='width:245px;' class='inputbox' value='$row->tloca' /></td></tr>";
			echo "<tr><td width='130'>&nbsp;</td><td>&nbsp;</td></tr>";
			echo "<tr><td width='130'>"._DEFINITION_DEFINITION."</td><td>$clist</td></tr>";
			echo "<tr><td width='130'>"._DEFINITION_ENTERTERM."</td><td><input type='text' name='tterm' style='width:245px;' class='inputbox' value='$row->tterm' /></td></tr>";
			echo "<tr><td width='130' valign='top'>"._DEFINITION_ENTERDEFINITION."<br /><br />";
			echo "</td><td valign='top' width='420'>";
			
			if ($definition->gl_useeditor) {
			  getEditorContents( 'editor1', 'tdefinition' );
			  editorArea( 'editor1', $row->tdefinition, 'tdefinition', '400', '100', '50', '20' );
			} 
			else {		
			  echo "<textarea style='width:245px;' rows='8' cols='40' name='tdefinition' class='inputbox'>$row->tdefinition</textarea>";
			}
			echo "</td></tr>";
			echo "<tr><td width='130'><input type='submit' name='send' value='"._DEFINITION_SENDFORM."' class='button' /></td>";
			echo "<td align='right'><input type='reset' value='"._DEFINITION_CLEARFORM."' name='reset' class='button' /></td></tr></table></form>";
			echo '</td></tr>';
			# Close RegUserOnly Check
		}
	}
	
}

?>