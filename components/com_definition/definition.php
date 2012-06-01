<?php
/**
 * Language file
 * @author GranholmCMS
 * @link http://www.granholmcms.com
 */

# Don't allow direct linking
defined('_JEXEC') or die('Restricted access');

global $mainframe;

$database =& JFactory::getDBO();

# Get the right language if it exists
$siteroot = JPATH_SITE;
$language =& JFactory::getLanguage();
$lang = $language->getBackwardLang();
if (file_exists($siteroot.'/components/com_definition/languages/'.$lang.'.php')) {
	require_once($siteroot.'/components/com_definition/languages/'.$lang.'.php');
	include_once($siteroot.'/components/com_definition/languages/english.php');
} else {
	require_once($siteroot.'/components/com_definition/languages/english.php');
}

# Variables - Don't change anything here!!!
require_once( $mainframe->getPath( 'front_html' ) );
require_once( $mainframe->getPath( 'class' ) );
$dfversion = "V1.0";
$definition =& definitionDefinition::getInstance();
if ($definition->gl_utf8) {
	header( 'Content-Type: text/html;charset=UTF-8' );
	$database->setQuery("SET NAMES 'utf8'");
	$database->query();
}


function DefinitionABC($letter, $catid, $page=1){
    $Itemid = JRequest::getVar('Itemid');
	$definition =& definitionDefinition::getInstance();
	$myabc = $definition->abcplus_key($catid);
	$nav = '<div align="center">';
	foreach ($definition->abcplus($catid) as $i=>$ltrval) {
		$key = $myabc[$i];
		if ($letter == $key) $nav .= "<b>$ltrval</b>";
		else {
			$urlkey = urlencode($key);
			$url = "index.php?option=com_definition&func=display&letter=$urlkey&Itemid=$Itemid&catid=$catid&page=$page";
			$nav .= "<a href='$url'>$ltrval</a>";
		}
		$nav .= ' | ';
	}
	return substr($nav,0,strlen($nav)-3)."\n</div>\n\n";  // end of HTML
}

# Functions of Definition
function DefinitionHeader($letter, $catid=0, $term='') {
    global $mainframe;
	
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid');
    ?>
   	<div class='contentpaneopen' style='margin-top: 15px;'> <h2>
    <?php
	if ($catid) {     
	    $database->setQuery("SELECT title FROM #__categories WHERE id = '$catid'");
	    $cat_title = $database->loadResult();
	    echo $cat_title;
		?>
		</h2>
		<?php
		$definition =& definitionDefinition::getInstance();
		if ($definition->gl_showcatdescriptions) {
		  echo "<br />";
		  $database->setQuery("SELECT description FROM #__categories WHERE id = '$catid'");
	      echo $database->loadResult();
		  echo "<br />";
		}
		?>		
		
	
		<ul class="contentpaneopen glosslist">
	
		<?php
	    if ($definition->gl_showcategories) {
	    	$url = "index.php?option=com_definition&Itemid=$Itemid";
      		echo'<a href="'.$url.'">'._DEFINITION_VIEW.'</a>';
      	}
		# BZE: only show, if entries are allowed or is_editor

	    if (($definition->gl_allowentry) OR ($definition->isEditor())) {
	    	$url_letter = urlencode($letter);
	    	$url = "index.php?option=com_definition&letter=$url_letter&catid=$catid&Itemid=$Itemid&func=submit";
			echo '<br /><a href="'.$url.'">'._DEFINITION_SUBMIT.'</a>';
		}
	    echo '</td></tr>';
	    if ($definition->gl_show_alphabet) {
		    echo '<tr><td colspan="2"><br />';
		    echo DefinitionABC($letter, $catid);
		    echo '<br /><br />';
	    }
	}
	else {
    	echo _DEFINITION_TITLE;
    	$cat_title = '';
    	?>
		</div>
		<?php
	}
	$title = _DEFINITION_TITLE;
	if ($cat_title) $title = $cat_title.' - '.$title;
	if ($term) $title = $term.' - '.$title;
	if ($letter) $title = $letter.' - '.$title;
	$mainframe->setPageTitle($title);
}

function DefinitionFooter($letter, $catid) {
    global $dfversion;
	
	$database =& JFactory::getDBO();
	$func = JRequest::getVar('func');
	
    echo '<tr><td colspan="2">';
    echo '<br /><br />';
	$definition =& definitionDefinition::getInstance();
	if ($catid AND $func <> 'submit' AND $definition->gl_show_alphabet) {
	    echo DefinitionABC($letter, $catid);
	    echo '<br/><br/>';
	}
	?>
    
	</div>
	<?php
}

function is_email($email) {
    return preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $email);
}

function textwrap($text, $width = 75) {
	if ($text) return preg_replace("/([^\n\r ?&\.\/<>\"\\-]{".$width."})/i"," \\1\n",$text);
}

function showTerms($catid, $letter) {
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid');
	$definition =& definitionDefinition::getInstance();
	if (!$letter AND !$definition->search) {
	  	switch ($definition->gl_beginwith){
			case 'all':
  				$letter='All';
  				echo "<h3>"._DEFINITION_ALL."</h3>";
  				break;
  			case 'first':
  				$sql="SELECT UPPER(SUBSTR(tterm,1,1)) AS tletter, tterm FROM #__definition WHERE published = 1 AND catid=$catid ORDER BY tterm ASC LIMIT 1";
  				$database->setquery($sql);
  				$row = $database->loadObject();
  				if ($row) echo "<h3>$row->tletter</h3>";
  				break;
  			default:
  				$letter='[nothing]';
  				if ($definition->gl_show_alphabet) {
  					echo '<tr><td colspan="2">';
  					echo "<font size='2'><strong>"._DEFINITION_SELECT."</strong></font>";
  					echo '</td></tr>';
  				}
  				return;
  				break;
  		}
  	}
	else {
		echo '';
  		if ($letter=='All') {
		  echo "<h3>Terms used on this site</h3>";
		}
  		elseif ($letter AND $letter != '[nothing]') {
			echo "<h3>".$letter."</h3>";
		}
		echo '';
	}
	# Feststellen der Anzahl der verfügbaren Datensätze
	$count_query  = "SELECT COUNT(id) FROM #__definition WHERE published = 1 AND catid=$catid";
	
  	if ($definition->search) {
  		if ($definition->search_type == 1) $definition->search = '^'.$definition->search;
  		if ($definition->search_type == 3) $sql_letter = " AND tterm = '$definition->search'";
  		else $sql_letter = " AND tterm RLIKE '$definition->search'";
  	}
	elseif ($letter AND $letter !='All' AND $letter != '[nothing]') $sql_letter = " AND ".$definition->letterSQL($letter);
	else $sql_letter = '';
	$count_query .= $sql_letter;
	$database->setquery($count_query);
	$count = $database->loadResult();
	# Manage page breaks
	if (!isset($definition->gl_perpage) OR $definition->gl_perpage < 2) $definition->gl_perpage = 20;
	$total_pages = floor($count / $definition->gl_perpage);
	$last_page   = $count % $definition->gl_perpage;
	if ($last_page>0) $total_pages++;
	# Finding actual page now
	$page = JRequest::getInt('page', 1);
	$page = max(min($page, $total_pages),1);
	# BZE show number of entries
	if ($definition->gl_shownumberofentries) {
		echo '<strong>';
	   	printf (_DEFINITION_BEFOREENTRIES.' %d '._DEFINITION_AFTERENTRIES, $count);
		echo '</strong><br /><ul>';
	}
	
	if ($letter != '[nothing]') {
		if ($definition->search) $start = 0;
		else {	
			echo '<tr><td>';
			echo _DEFINITION_PAGES.' ';
			# Determine the page
			$previous_page = $page - 1;
			$url_letter = urlencode($letter);
			if ($previous_page > 0) echo '<a href="index.php?option=com_definition&func=display&letter=$url_letter&page=$previous_page&catid=$catid&Itemid=$Itemid"><b>«</b></a>';
	  		
	  		#Ausgeben der einzelnen Seiten
	  		for ($i=1; $i <= $total_pages; $i++) {
				if ($i==$page) echo "$i ";
				else {
					$url = "index.php?option=com_definition&func=display&letter=$url_letter&page=$i&catid=$catid&Itemid=$Itemid";
					echo '<a href="'.$url."\">$i</a> ";
        		}
	  		}
	  		# Ausgeben der Seite vorwärts Funktion
	  		$seitevor = $page + 1;
	  		if ($seitevor<=$total_pages) {
				$url = "index.php?option=com_definition&func=display&letter=$url_letter&Itemid=$Itemid&catid=$catid&page=$seitevor";
				echo '<a href="'.$url.'"><b>»</b></a> ';
  	  		}
	  		# Limit und Seite Vor- & Rueckfunktionen
	  		$start = ( $page - 1 ) * $definition->gl_perpage;
			echo '</td></tr>';
		}
	  	// Database Query
		?>
	  	
		<?php
	}
	$line = 1;
	$sqlsuffix = "WHERE published=1 AND catid=$catid".$sql_letter;
  	// get the total number of records
  	if (!isset($start)) $start = 0;
	$query1=" SELECT * FROM #__definition $sqlsuffix ORDER BY tterm LIMIT $start,$definition->gl_perpage ";
	$database->setQuery($query1);
	$items = $database->loadObjectList();
	if ($items) {
		foreach ($items as $row1) {
			$linecolour = ($line % 2) + 1;
			showOneTerm($row1->id, $linecolour, $row1);
			$line++;
		}
	}
}

function showOneTerm ($id, $linecolour, &$row1) {
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid');
	if ($row1 == null) {
		$justone = true;
		$sql = "SELECT * FROM #__definition WHERE id=$id";
		$database->setQuery($sql);
		$database->loadObject($row1);
		echo "<ul>";
	}
	else $justone = false;
	if ($row1) {	
		$definition =& definitionDefinition::getInstance();
		/* $url = 'index.php?option=com_definition&func=view&Itemid='.$Itemid.'&catid='.$row1->catid.'&term='.urlencode($row1->tterm); */
		$showterm = $row1->tterm;
		echo "<li class='sectiontableentry".$linecolour."'><a name='$row1->id'></a><b>$showterm</b>";
		if($definition->gl_hideauthor){
			$row1->tname = textwrap($row1->tname,20);
			if ($row1->tname<>""){
				if ($row1->tpage<>"") {
					# Check if URL is in right format
					if (substr($row1->tpage,0,7)!="http://") $row1->tpage="http://$row1->tpage";
					echo "<br /><a href='$row1->tpage' target='_blank'><span class='small'>"._DEFINITION_AUTHOR.": $row1->tname</span></a>";
				}
				else echo '<br /><span class="small">'._DEFINITION_AUTHOR.": $row1->tname</span>";
			}
		}
		echo '&nbsp;&nbsp;&nbsp;';
		echo textwrap($row1->tdefinition,80);
		if ($row1->tcomment) {
			$origcomment = $row1->tcomment;
			echo "<hr /><span class='small'><b>"._DEFINITION_ADMINSCOMMENT.":</b> $origcomment</span>";
		}
		echo "";
		echo "";
		echo "";
		echo "</li>";
		if ($definition->isEditor()) {
			echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
			$letter = substr($row1->tterm,0,1);
			$url_letter = urlencode($letter);
			echo "<td align='left'><b>"._DEFINITION_ADMIN.":</b> ";
			echo "<a href='index.php?option=com_definition&Itemid=$Itemid&func=comment&letter=$url_letter&id=$row1->id&catid=$row1->catid'>"._DEFINITION_ACOMMENT."</a> - ";
			if ($row1->tcomment) echo "<a href='index.php?option=com_definition&letter=$url_letter&Itemid=$Itemid&func=comment&opt=del&id=$row1->id&catid=$row1->catid'>"._DEFINITION_ACOMMENTDEL."</a> - ";
			echo "<a href='index.php?option=com_definition&Itemid=$Itemid&func=submit&letter=$url_letter&id=$row1->id&catid=$row1->catid'>"._DEFINITION_AEDIT."</a> - ";
			echo "<a href='index.php?option=com_definition&Itemid=$Itemid&func=delete&letter=$url_letter&id=$row1->id&catid=$row1->catid'>"._DEFINITION_ADELETE."</a></td>";
			echo '</tr></table>';
		}
		echo '';
	}
	if ($justone) echo '</ul>';
}

function definitionUserSide () {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	$Itemid = JRequest::getInt('Itemid');
	$definition =& definitionDefinition::getInstance();

	$catid = JRequest::getInt('catid', $definition->gl_defaultcat );
	$func = JRequest::getVar('func','' );
	$letter = JRequest::getVar('letter', '');
	$letter = urldecode($letter);
	if ($letter) $letter = $database->getEscaped($letter);
	$definition->search = $database->getEscaped(JRequest::getVar('search', ''));
	$definition->search_type = JRequest::getInt('search_type', 1);
  	$definition->search_type = max(min(3, $definition->search_type),1);

    switch ($func) {
		case 'popup':
			// get the record
	        DefinitionHeader($letter);
			$term = $database->getEscaped(JRequest::getVar('term', ''));
			$database->setQuery( "SELECT tterm, tdefinition FROM #__definition WHERE id='$term' AND published=1");
			$rows = $database->loadObjectList();
	        $row = $rows[0];
	        ?>
			<h1 class='contentHeading'>
			<?php echo $row->tterm; ?>
			</h1>
	        <p>
			<?php echo $row->tdefinition; ?>
	        <p><a href='javascript:history.go(-1);'><span class="small">Back</span></a>
	        <?php
			break;
		#########################################################################################
		case 'search':
			DefinitionHeader($letter, $catid);
			HTML_definition::searchHTML();
			break;
		#########################################################################################
		case 'delete':
			DefinitionHeader($letter, $catid);
			HTML_definition::deleteHTML($letter, $catid);
			break;
		#########################################################################################
		case 'comment':
			DefinitionHeader($letter, $catid);
			HTML_definition::commentHTML($letter, $catid);
			break;
		#########################################################################################
		case 'entry':
			$id = JRequest::getInt('id', 0);
			if (!$definition->gl_anonentry AND !$definition->isUser()) die (_DEFINITION_ONLYREGISTERED);
			# Check if entry was edited by editor
			$tname = $database->getEscaped(JRequest::getVar('tname', ''));
			$tmail = $database->getEscaped(JRequest::getVar('tmail', ''));
			$tloca = $database->getEscaped(JRequest::getVar('tloca', ''));
			$tpage = $database->getEscaped(JRequest::getVar('tpage', ''));
			$tterm = JRequest::getVar('tterm', '');
			$tterm = $database->getEscaped($tterm);
			$tdefinition = $database->getEscaped(JRequest::getVar('tdefinition', ''));
			if ($tname == '' OR $tterm == '' OR $tdefinition == '' OR $catid ==0){
				DefinitionHeader($letter, $catid);
				echo '<p><strong>'._DEFINITION_VALIDATE.'</strong></p>';
				break;
			}
			if (($definition->isEditor()) AND ($id)) {
				$query1 = "UPDATE #__definition SET catid='$catid', tname='$tname', tmail='$tmail', tloca='$tloca', tpage='$tpage', tterm='$tterm', tletter=UPPER(SUBSTRING('$tterm',1,1)), tdefinition='$tdefinition' WHERE id=$id";
				$database->setQuery( $query1 );
				$database->query();
			}
			else {
				$tip   = getenv('REMOTE_ADDR');
				$tdate = date("y/m/d g:i:s");
				$query2 = "INSERT INTO #__definition SET catid='$catid',tname='$tname',tdate='$tdate',tmail='$tmail', tloca='$tloca', tpage='$tpage', tterm='$tterm', tletter=UPPER(SUBSTRING('$tterm',1,1)), tdefinition='$tdefinition'";
				if ($definition->gl_autopublish) $query2 .= ",published='1'";
				$database->setQuery( $query2 );
				$database->query();
				if ($definition->gl_notify AND is_email($definition->gl_notify_email) ) {
					$tmailtext = _DEFINITION_ADMINMAIL."\r\n\r\nName: ".$tname."\r\nText: ".$tterm."\r\n\r\n"._DEFINITION_MAILFOOTER;
					mail($definition->gl_notify_email,_DEFINITION_ADMINMAILHEADER,$tmailtext,"From: ".$definition->gl_notify_email);
				}
				if ($definition->gl_thankuser AND is_email($tmail) ) {
					$tmailtext = _DEFINITION_USERMAIL."\r\n\r\nName: ".$tname."\r\nText: ".$tterm."\r\n\r\n"._DEFINITION_MAILFOOTER;
					mail($tmail,_DEFINITION_USERMAILHEADER,$tmailtext,"From: ".$definition->gl_notify_email);
				}
			}
			$url_letter = urlencode($letter);
			echo "<script> alert('"._DEFINITION_SAVED."'); document.location.href='index.php?option=com_definition&func=display&letter=$url_letter&Itemid=$Itemid&catid=$catid';</script>";
			break;
		#########################################################################################
		case 'submit':
			DefinitionHeader($letter);
			if (($definition->gl_allowentry) OR ($definition->isEditor())) {
			  HTML_definition::submitHTML($letter, $catid);
			break;
			}
		#########################################################################################
		case 'display':
			DefinitionHeader($letter, $catid);
			showTerms($catid, $letter);
			break;
		#########################################################################################
		case 'view':
			$term = mosGetParam($_REQUEST, 'term', '');
			$term = urldecode($term);
			if ($term) {
				$term = $database->getEscaped($term);
				$sql = "SELECT * FROM #__definition WHERE tterm='$term' AND catid=$catid";
				$database->setQuery($sql);
				$database->loadObject($row);
				DefinitionHeader($letter, $catid, $term);
				if ($row) showOneTerm($row->id, 0, $row);
			}
			break;
		#########################################################################################
		default:
			$my = &JFactory::getUser(); 
			$func = '';
			if ($definition->gl_showcategories) {
				DefinitionHeader($letter);
				$database->setQuery( "SELECT * FROM #__categories WHERE section='com_definition' AND published=1 ORDER BY ordering" );
				$categories = $database->loadObjectList();
				if ($categories) {
					foreach ($categories as $row2) {
						if ($row2->access<=$my->gid) {
							echo "<img src='images/M_images/arrow.png' /> <a href='index.php?option=com_definition&func=display&Itemid=$Itemid&catid=$row2->id'>$row2->title</a><br />";
							# BZE, description for categories
							if ($definition->gl_showcatdescriptions) {
							  echo "$row2->description<br />";
							}
							if($row2->count > 0) echo "<i>(".$row2->numitems." "._CHECKED_IN_ITEMS.")</i>";
						}
						else {
							echo $row2->name.' - '._E_REGISTERED;
						} ?>
						<br />
						<?php
					}
				}
			}
			else{
				$catid = $definition->gl_defaultcat;
				if (!$catid) {
					$sql = "SELECT id FROM #__categories WHERE section='com_definition' ORDER BY id LIMIT 1";
					$database->setQuery($sql);
					$catid = $database->loadResult();
				}
				DefinitionHeader($letter, $catid);
				showTerms($catid, $letter);
			}
			break;
    }
    DefinitionFooter($letter, $catid);
}

definitionUserSide();

?>