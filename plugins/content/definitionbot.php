<?php
/**
 * Definition file - By GranholmCMS.com - www.granholmcms.com
 
 */

defined('_JEXEC') or die('Restricted access');

echo overlibInitCall();

$mainframe->registerEvent( 'onPrepareContent', 'plgdefinitionbot' );

function plgdefinitionbot(&$page) {

if (!is_callable('getDefinitionbotParams')) {
	
	function getDefinitionbotParams () {
		$plugin =& JPluginHelper::getPlugin( 'content', 'definitionbot' );
		$pluginParams = new JParameter( $plugin->params );
		return $pluginParams;
	}
	
	function getDefaultHTML () {
		$plugin =& JPluginHelper::getPlugin( 'content', 'definitionbot' );
		$pluginParams = new JParameter( $plugin->params );
		return $pluginParams->get('defaultHTML');
	}
	
	function popupCheck (&$text, $symbol, $value, $default) {
		$newtext = str_replace($symbol, '', $text);
		if ($newtext == $text) return $default;
		else {
			$text = $newtext;
			return $value;
		}
	}
	
	function makeSearchable (&$param) {
		$database =& JFactory::getDBO();
		$find_exact = $param->get('find_exact', 1);
		$database->setQuery('SELECT id,tterm,tdefinition FROM #__definition WHERE published=1 ORDER BY length(tterm) DESC');
		$rows = $database->loadObjectList();
		
		if ($rows) {
			foreach ($rows as $row) {
				$keyword = htmlspecialchars(trim($row->tterm));
				if ($keyword) {
					$escaped_keyword = str_replace('/','\\/',$keyword);
					if ($find_exact) $regex='/(^|\W)'.$escaped_keyword.'($|\W)/';
					else $regex='/'.$escaped_keyword.'/';
					$key = strtoupper($keyword);
                	$definition = preg_replace("/(\015\012)|(\015)|(\012)/",'&nbsp;<br />',$row->tdefinition);
					$definitiony[$key]=array('id' => $row->id,'term' => $keyword, 'desc' => $definition, 'regex' => $regex, 'found' => false);
				}
			}
			if (isset($definitiony)) return $definitiony;
		}
		return array();
	}
	
	function buildContent (&$definitiony, $content, $times) {
		static $noChange = true;
		
		foreach ($definitiony as $gkey=>$entry) {  // run through all definitiony entries
			if ($times == 1 AND $entry['found']) continue;
			$id = $entry['id'];
			$newcontent = preg_replace($entry['regex'],"\$1{ShowDefinition:$id}\$2",$content,$times);
			if ($newcontent != $content) {
				$definitiony[$gkey]['found'] = true;
				$content = $newcontent;
				if ($noChange AND JRequest::getVar('task', '') != 'edit') {
					$noChange = false;
					JHTML::_('behavior.tooltip');
				}
			}
		}
		return $content;
	}
	
	function linkParams (&$param) {
		$keys = array('fgcolor','bgcolor','txtcolor','capcolor','width','position','alignment','offset_x','offset_y','outputmode','css');
		$defaults = array('#CCCCFF','#333399','#000000','#FFFFFF',300,'BELOW','RIGHT',10,10,0,'');
		foreach ($keys as $i=>$key) $parm[$key] = $param->get($key, $defaults[$i]);
		
		if ($param->get('show_image', 1)) $parm['image'] = '<img src="' . JURI::base( true ) . '/plugins/content/definitionbot/'.$param->get('icon').'" border="0" align="top">';
		else $parm['image'] = '';
		
		/* Create CSS */
		?>
		<style type="text/css" media="all">
		<!--
		.ol-background { 
			background-color:  <?php echo $parm['bgcolor']; ?>;
			color:  <?php echo $parm['capcolor']; ?>;
		}
		.ol-foreground { 
			background-color: <?php echo $parm['fgcolor']; ?>;
			
		}
		.ol-textfont { color:  <?php echo $parm['txtcolor']; ?>; }
		
		-->
		</style>
		<?php
		return $parm;
	}
	
	function makeLink (&$entry, &$parm, $defaultHTML) {
		$orgTerm = $entry['term'];
		$desc = $entry['desc'];
		$id = $entry['id'];
		$trimTerm = ltrim($orgTerm);
		switch ($parm['outputmode']) {
			case 1:
				$desc= ereg_replace("<br />|<p>", " ", $desc);
				$link = '<a style="'.$parm['css'].'" href="javascript:void(0)" title="'.strip_tags($desc).'">' . $parm['image'] .' '. $trimTerm.'</a>';
				break;
			case 2:
				//Get the default text of the module
				$link = '<a class="mosinfopop" style="'.$parm['css'].'" href="javascript:void(0)" onmouseover="javascript:document.getElementById(&quot;definitionbox&quot;).innerHTML=\'<b><u>'.addslashes(htmlspecialchars($orgTerm)).'</u></b><br>'.addslashes(htmlspecialchars($desc)).'\'" onmouseout="javascript:document.getElementById(&quot;definitionbox&quot;).innerHTML=\''.$defaultHTML.'\'">'.$parm['image'] . $trimTerm . '</a>';
				break;
			case 3:
				$link = '<a class="mosinfopop" style="'.$parm['css'].'" href="javascript:void(0)" onclick="return overlib(\'' . addslashes(htmlspecialchars($desc)) . '\', STICKY, CLOSECLICK, CAPTION, \'' . addslashes(htmlspecialchars($orgTerm)) . '\',' . strtoupper($parm['position']) . ',' . strtoupper($parm['alignment']) . ', WIDTH, ' . $parm['width'] . ', FGCOLOR, \'' . $parm['fgcolor'] . '\', BGCOLOR, \'' . $parm['bgcolor'] . '\', TEXTCOLOR, \'' . $parm['txtcolor'] . '\', CAPCOLOR, \'' . $parm['capcolor'] . '\', OFFSETX, ' . $parm['offset_x'] . ', OFFSETY, ' . $parm['offset_y'] . ');">' . $parm['image'] .' '. $trimTerm . '</a>';
				break;
			case 4:
				$link = '<a class="mosinfopop" style="'.$parm['css'].'" href="javascript:void(0)" onmouseover="return overlib(\'' . addslashes(htmlspecialchars($desc)) . '\', CAPTION, \'' . addslashes(htmlspecialchars($orgTerm)) . '\',' . strtoupper($parm['position']) . ',' . strtoupper($parm['alignment']) . ', WIDTH, ' . $parm['width'] . ', FGCOLOR, \'' . $parm['fgcolor'] . '\', BGCOLOR, \'' . $parm['bgcolor'] . '\', TEXTCOLOR, \'' . $parm['txtcolor'] . '\', CAPCOLOR, \'' . $parm['capcolor'] . '\', OFFSETX, ' . $parm['offset_x'] . ', OFFSETY, ' . $parm['offset_y'] . ');" onmouseout="return nd();"
    			onclick="return overlib(\'' . addslashes(htmlspecialchars($desc)) . '\', STICKY, CLOSECLICK, CAPTION, \'' . addslashes(htmlspecialchars($orgTerm)) . '\',' . strtoupper($parm['position']) . ',' . strtoupper($parm['alignment']) . ', WIDTH, ' . $parm['width'] . ', FGCOLOR, \'' . $parm['fgcolor'] . '\', BGCOLOR, \'' . $parm['bgcolor'] . '\', TEXTCOLOR, \'' . $parm['txtcolor'] . '\', CAPCOLOR, \'' . $parm['capcolor'] . '\', OFFSETX, ' . $parm['offset_x'] . ', OFFSETY, ' . $parm['offset_y'] . ');">' . $parm['image'] .' '. $trimTerm . '</a>';
				break;
			default:
			
				$desc= ereg_replace("<br />|<p>", " ", $desc);
				$link = '<a class="glossTip" style="'.$parm['css'].'" href="/glossary#'.$id.'" title="'.strip_tags($desc).'">' . $parm['image'] .' '. $trimTerm.'</a>';
//				$link = '<a class="glossTip"  href="#" >' . $parm['image'] .' '. $trimTerm . '<span>' . addslashes(htmlspecialchars($desc)) . '</span></a>';
				break;
		}
	    return $link;
	}

	function convertEntries ($content, &$param, &$definitiony) {
		// replace temporary {showdesc:id} tasks
		$before = array();
		$after = array();
		$parm = linkParams($param);
		$defaultHTML = getDefaultHTML();
		foreach ($definitiony as $gkey=>$entry) {
			if ($entry['found']) {
				$before[] = '/{ShowDefinition:'.$entry['id'].'}/';
				$after[] = makeLink($entry, $parm, $defaultHTML);
			}
		}
		if (count($before)) return preg_replace($before, $after, $content);
		return $content;
	}

	function showDefinition (&$definitiony, &$param, $content) {
		foreach ($definitiony as $gkey=>$entry) {
			if ($entry['found']) $temparray[$entry['term']] = $entry['desc'];
		}
		if (count($temparray) == 0) return;
		ksort($temparray);

		define ('_headline', $param->get('headline', "Nomenclature"));
		define ('_head_term',$param->get('head_term', "Term"));
		define ('_head_explanation', $param->get('head_explanation', "Description"));

		$show_headline = $param->get('show_headline', 1);
		$style = '2';
		if ($_SERVER['SCRIPT_NAME'] == "/index2.php") {
			$border = '1';
			$cellspacing = '0';
			$cellpadding = '2';
		}
		else {
			$border = '0';
			$cellspacing = '2';
			$cellpadding = '2';
		}
		// --- Array Sorting by Keyword

		$definitiontable = '<table width="100%" cellpadding="1" cellspacing="0">';
		if ($show_headline) $definitiontable .= '<tr><td width="100%" style="border-bottom: 1px solid #C9C9C9;border-top: 1px solid #C9C9C9;"><b>' . _headline . '</b></td></tr>';
		$definitiontable .= '<tr><td><br><table border="' . $border . '" align="center" style="border: 1px solid #C9C9C9;" width="100%" cellpadding="' . $cellpadding . '" cellspacing="' . $cellspacing . '"><tr class="sectiontableheader"><td><b>' . _head_term . '</b></td><td><b>' . _head_explanation . '</b></td></tr>';
		foreach ($temparray as $keyword=>$definition) {
			$definitiontable .= '<tr class="sectiontableentry' . $style . '"><td width="auto">' . $keyword . '&nbsp;</td><td>' . $definition . '</td></tr>';
			$style = ($style % 2) + 1;
		}
		$definitiontable .= '</td></tr></table></td></tr><tr><td align="right"><a href="http://www.remository.com" target="_blank">&copy;2005 remository.com</a></td></tr></table>';
		return str_replace('{definition}', $definitiontable, $content);
	}
}
	$language =& JFactory::getLanguage();
    setlocale (LC_ALL, $language->getLocale());

	$param = getDefinitionbotParams();

	$show_frontpage = $param->get('show_frontpage', 1);
	$run = $param->get('run_default', 1);
	$show_once_only = $param->get('show_once_only', 1);
	if ($show_once_only) $times = 1;
	else $times = -1;
	
	// checking if definitionbot creates popups
	$run = popupCheck($page->text, '{mosinfopop=enable}', 1, $run);
	$run = popupCheck($page->text, '{definitionbot=enable}', 1, $run);
	$run = popupCheck($page->text, '{mosinfopop=disable}', 0, $run);
	$run = popupCheck($page->text, '{definitionbot=disable}', 0, $run);
	
	if ($run == 0) return true;
	$option = JRequest::getVar('option', '');
	if ($option == 'com_frontpage') {
		if (!$show_frontpage) return true;
	}

	$definitiony = makeSearchable($param);
	if (count($definitiony)) {
		foreach ($definitiony as $key=>$entry) $length[] = $key;
		$minkeylen = min($length);
		$newContent = '';
		$htmlregex = '#(<a\s+.*?</a\s*>|<script\s+.*?</script\s*>|<style\s+.*?</style\s*>|</?.*?\>|\<!--.*?-->)#is';
		$bits = preg_split($htmlregex, $page->text);
		preg_match_all($htmlregex, $page->text, $matches);
		foreach ($bits as $i=>$bit) {
			if (strlen($bit) < $minkeylen) $newContent .= $bit;
			else $newContent .= buildContent ($definitiony, $bit, $times);
			if (isset($matches[0][$i])) $newContent .= $matches[0][$i];
		}
		$newContent = convertEntries ($newContent, $param, $definitiony);
		if (eregi('\{definition\}',$newContent)) $page->text = showDefinition ($definitiony, $param, $newContent);
		else $page->text = $newContent;
	}
	return true;
}

function overlibInitCall () {
	$txt = "";
	$txt .= '<script type="text/javascript"> <![CDATA[
'."\n";
	$txt .= " if ( !document.getElementById('overDiv') ) { "."\n";
	$txt .= " document.writeln('<div id=\"overDiv\" style=\"position:absolute; visibility:hidden; z-index:10000;\"></div>'); "."\n";
	$txt .= " document.writeln('<scr'+'ipt language=\"Javascript\" src=\"" .JURI::base(). "includes/js/overlib_mini.js\"></scr'+'ipt>'); "."\n";
	$txt .= " } "."\n";
	$txt .= "]]></script> "."\n";
	return $txt;
}

?>
