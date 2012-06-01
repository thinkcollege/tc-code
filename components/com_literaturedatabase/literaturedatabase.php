<?php
/**
 * @package     Joomla.Tutorials
 * @subpackage  Components
 * components/com_literature/search.php
 * @link        http://docs.joomla.org/Category:Development
 * @license     GNU/GPL
 */
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Require the base controller
 
require_once( JPATH_COMPONENT.DS.'controller.php' );

define('LIVE', $_SERVER['SERVER_ADDR'] == '72.3.249.15');
define('PRE_TABLE',					 '#__litdb_');
define('TBL_TYPE',					 PRE_TABLE . 'ArticleType');
define('TBL_AUDIENCE',				 PRE_TABLE . 'Audience');
define('TBL_BOOK_TYPE',				 PRE_TABLE . 'BookType');
define('TBL_CONTRIBUTOR',			 PRE_TABLE . 'Contributor');
define('TBL_CONTRIBUTOR_TYPE',		 PRE_TABLE . 'ContributorType');
define('TBL_FORMAT',				 PRE_TABLE . 'Format');
define('TBL_JOURNAL',				 PRE_TABLE . 'Journal');
define('TBL_LITERATURE',			 PRE_TABLE . 'Literature');
define('TBL_LITERATURE_AUDIENCE',	 PRE_TABLE . 'LiteratureAudience');
define('TBL_LITERATURE_CONTRIBUTOR', PRE_TABLE . 'LiteratureContributor');
define('TBL_LITERATURE_FORMAT',		 PRE_TABLE . 'LiteratureFormat');
define('TBL_LITERATURE_SUBJECT',	 PRE_TABLE . 'LiteratureSubject');
define('TBL_PUB_IN_PRINT',			 PRE_TABLE . 'PrintPubs');
define('TBL_PUB_ONLINE',			 PRE_TABLE . 'OnlinePubs');
define('TBL_REPORT_TYPE',			 PRE_TABLE . 'ReportType');
define('TBL_SUBJECT',				 PRE_TABLE . 'Subject');

define('TYPE_BOOK',				1);
define('TYPE_JOURNAL_ARTICLE',	2);
define('TYPE_MAGAZINE_ARTICLE',	3);
define('TYPE_REPORT',			4);
define('TYPE_NEWSPAPER_ARTICLE',5);
define('TYPE_OTHER',			8);
define('TYPE_NEWSLETTER',		9);
define('TYPE_PAMPHLETBROCHURE',	10);
define('TYPE_POSITION_PAPER',	11);
define('TYPE_MEDIA',			12);
define('TYPE_PUBLICATION',		13);
define('TYPE_WEBPAGE', 			14);

define('CONTRIB_TYPE_AUTHOR',			1);
define('CONTRIB_TYPE_EDITOR',			2);
define('CONTRIB_TYPE_ORG_AUTHOR',		3);
define('CONTRIB_TYPE_CHAPTER_AUTHOR',	4);
define('CONTRIB_TYPE_SECTION_EDITOR',	5);
define('CONTRIB_TYPE_COMPILER',			15);
define('CONTRIB_TYPE_TRANSLATOR',		16);
define('CONTRIB_TYPE_DIRECTOR',			26);
define('CONTRIB_TYPE_Producer',			27);

if (!isset($_SESSION['literature'])) {
	$_SESSION['literature'] = array();
}
// Require specific controller if requested
if ($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
 
// Create the controller
$classname    = 'LiteraturedatabaseController'.$controller;
$controller   = new $classname( );
 
// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();

class Literature {
	protected $_LitID = 0, $_TypeID = 0, $_ReportType = 0, $_BookType = 0, $_AdditionalPubInfo = '',
		$_Audience = array(), $_Subject = array(), $_Format = array(), $_TTA = 0, $_PDFfile = '',
		$_CopyrightCheck = 0, $_FullTextAvailable= 0, $_Annotation = '', $_DateEntered = 0,
		$_InPrintID = 0, $_IPSourceTitle = '', $_ChapterSecArticleTitle = '', $_JournalTitle = '',
		$_ISBNISSN = '', $_NewsSection = '', $_IPYear = 0, $_IPMonth = 0, $_IPDay = 0, $_IPUnpublished = 0,
		$_WebsiteTitle = '', $_URL = '', $_DOI = '', $_OriginallyPrint = 0, $_BookTypeID = 0,
		$_RptTypeID = 0, $_Type = '', $_OnlineJournal = '', $_Contributor = array(), $_APA = '';
	
	public function __construct($LitID) {
		#throw new Exception('Boo!');
		$this->setLitId($LitID);
	}
	
	public function setLitID($LitID) {
		$LitID = intval($LitID);
		if ($LitID == 0) {
			return false;
		}
		$query = 'SELECT l.TypeID, t.Label AS `Type`, l.ReportType, rt.Label AS `ReportType`, l.BookType, bt.Label AS `BookType`, `AdditionalPubInfo`, `TTA`, `CopyrightCheck`, `FullTextAvailable`, `Annotations`, `IPSourceTitle`, `ChapterSecArticleTitle`, `ISBNISSN`, `NewsSection`, `IPYear`, `IPMonth`, `IPDay`, `IPUnpublished`, `PDFfile`, `WebsiteTitle`, `URL`, `DOI`, `ElecPubYear`, `ElecPubMonth`, `ElectPubDay`, `OriginallyPrint`, `APA1`, `APA2`, `APA3`
					FROM ' . TBL_LITERATURE . ' l
			  LEFT JOIN ' . TBL_BOOK_TYPE . ' bt ON l.BookType = bt.ID
			  LEFT JOIN ' . TBL_REPORT_TYPE . ' rt ON l.ReportType = rt.ID
			  LEFT JOIN ' . TBL_TYPE . ' t ON l.TypeID = t.ID
				  WHERE l.LitID = '.$LitID;
		$db = JFactory::getDBO();
		$db->setQuery($query, 0, 1);
		$lit = $db->loadAssoc();
		if ($db->getAffectedRows() < 1 || $db->getAffectedRows() > 1) {
			return;
		}
		$this->_LitID = $LitID;
		foreach ($lit as $key => $value) {
			$key = "_$key";
			if ($key == '_LitID' || empty($value)) {
				continue;
			} else if (in_array($key, array('_APA1', '_APA2', '_APA3', '_LitID')) && $value != '') {
				$this->_APA = $value;
				continue;
			}
			
			if (is_int($this->$key)) {
				$this->$key = intval($value);
			} else {
				$search = array(chr(145) => "'", chr(146) => "'", chr(147) => '"', chr(148) => '"', chr(151) => '-'); 
 				$this->$key = strtr($value, $search);
			}
		}
		$joins = array('Audience', 'Subject', 'Format');
		foreach ($joins as $join) {
			$key = "_$join";
			$this->$key = array();
			$db->setQuery('SELECT ID, Label FROM `'. PRE_TABLE . "Literature$join` j
					   INNER JOIN `" . PRE_TABLE . "$join` lu ON lu.ID = j.`{$join}ID`
							WHERE j.LitID = $LitID");
			$rows = $db->loadAssocList();
			foreach ($rows as $row) {
				$this->{$key}[$row['ID']] = $row['Label'];
			}
		}
		$qContribs = 'SELECT c.`ID`, ct.Label AS `Type`, ct.`ID` AS `TypeID`,
							 CONCAT(c.LastNameCorp,
							 		IFNULL(CONCAT(\', \', c.FirstName), \'\'),
							 		IFNULL(CONCAT(\' \', c.Middle), \'\')) AS `Name`
						FROM ' . TBL_CONTRIBUTOR . ' c
				  INNER JOIN ' . TBL_LITERATURE_CONTRIBUTOR . ' lc ON lc.ContributorID = c.`ID`
				  INNER JOIN ' . TBL_CONTRIBUTOR_TYPE . ' ct ON ct.ID = lc.ContributorTypeID
					   WHERE lc.LitID = ' . $this->_LitID . ' ORDER BY ct.Label';
		$db->setQuery($qContribs);
		$this->_Contributor = array();
		$contribs = $db->loadAssocList();
		foreach ($contribs as $contrib) {
			$this->_Contributor[] = $contrib;
		}
		$_SESSION['literature'][$this->_LitID] = $this;
	}
	
	public function getLitId() {
		return $this->_LitID;
	}
	
	static public function getLiterature($LitID) {
		if (isset($_SESSION['literature'][$LitID]) && $_SESSION['literature'][$LitID] instanceof self) {
			return $_SESSION['literature'][$LitID];
		} else {
			return new self($LitID);
		}
	}
	
	public function getLiteratureTitle() {
		if (!empty($this->_IPSourceTitle)) {
			$this->_literatureTitle = $this->_IPSourceTitle;
		}
		if (!empty($this->_ChapterSecArticleTitle)) {
			$this->_literatureTitle .= (!empty($this->_IPSourceTitle) ? ': ' : '')
				. $this->_ChapterSecArticleTitle;
		}
		if (!empty($this->_JournalTitle)) {
			$this->_literatureTitle .= (!empty($this->_ChapterSecArticleTitle) ? ' - ' : '')
				. $this->_JournalTitle;
		}
		if (!empty($this->_WebsiteTitle) && empty($this->_JournalTitle)) {
			$this->_literatureTitle .= (!empty($this->_ChapterSecArticleTitle) ? ' - ' : '')
				. $this->_WebsiteTitle;
		}
		return isset($this->_literatureTitle) ? $this->_literatureTitle : '[UNTITLED]';
 	}
 	
	public function getBlurb() {
		return $this->_Annotations;
	}
	
	public function getFormat() {
		return $this->_TypeID;
	}
	
	public function getCreator() {}
	public function getPublishedDate() {}
	
	public function getSynopsis() {
		$synopsis = JHTML::link('./?option=com_literaturedatabase&view=search&task=literature&id='.$this->_LitID, self::ellipse($this->getLiteratureTitle(), 80), array('class' => 'literature'))
				  . '<br />';
		if ($this->getBlurb() != '') {
			$synopsis .= self::ellipse($this->getBlurb(), 160) . '<br />';
		}
		$synopsis .= '<span class="category">';
		if (count($this->_Audience)) {
			$synopsis .= 'Audience: '
					   . JHTML::link('./?option=com_literaturedatabase&view=search&task=search&audience='.key($this->_Audience), reset($this->_Audience))
					   . (count($this->_Audience) > 1 ? ' &hellip;' : '');
		}
		if (count($this->_Subject)) {
			$synopsis .= ' Topic: '
					   . JHTML::link('./?option=com_literaturedatabase&view=search&task=search&subject='.key($this->_Subject), reset($this->_Subject))
					   . (count($this->_Subject) > 1 ? ' &hellip;' : '');
		}
		switch ($this->getFormat()) {
			case TYPE_BOOK:				 $type = 'Book';				break;
			case TYPE_JOURNAL_ARTICLE:	 $type = 'Journal Article';		break;
			case TYPE_MAGAZINE_ARTICLE:	 $type = 'Magazine Article';	break;
			case TYPE_REPORT:			 $type = 'Report';				break;
			case TYPE_NEWSPAPER_ARTICLE: $type = 'Newspaper Article';	break;
			case TYPE_OTHER:			 $type = 'Other';				break;
			case TYPE_NEWSLETTER:		 $type = 'NewsLetter';			break;
			case TYPE_PAMPHLETBROCHURE:	 $type = 'Pamphlet/Brochure';	break;
			case TYPE_POSITION_PAPER:	 $type = 'Position Paper';		break;
			case TYPE_MEDIA:			 $type = 'Media';				break;
			case TYPE_PUBLICATION:		 $type = 'Publication';			break;
			case TYPE_WEBPAGE:			 $type = 'Webpage';				break;
			default:					 $type = 'Other';
		}
		return $synopsis . ' Format: '.$type . '</span>';
	}	

	public function getDetails() {
		$details = '<h1>' . $this->getLiteratureTitle() . '</h1>';
		$details .= '<p><strong>Author(s):</strong> ';
		$sep = '';
		$i = 1;
		foreach ($this->_Contributor as $contrib) {
			if ($contrib['TypeID'] == CONTRIB_TYPE_AUTHOR) {
				$details .= $sep . $contrib['Name'];
				$i++;
				$sep = count($this->_Contributor) == $i ? ' &amp; ' : ', ';
			}
		}
		$details .= "</p><p><strong>Abstract:</strong> {$this->getBlurb()}</p><p><strong>Format:</strong> $this->_Type</p>";
	
		if (count($this->_Audience)) {
			$details .= '<p><strong>Audience(s):</strong> ';
			$i = 1;
			$sep = '';
			foreach ($this->_Audience as $id => $aud) {
				$details .= "$sep<a href=\"/?option=com_literaturedatabase&view=search&task=search&audience=$id\">$aud</a>";
				$i++;
				$sep = $i == count($this->_Audience) ? ' &amp; ' : ', ';
			}
			$details .= '</p>';
		}
		if (count($this->_Subject)) {
			$details .= '<p><strong>Topic(s):</strong> ';
			$i = 1;
			$sep = '';
			foreach ($this->_Subject as $id => $subject) {
				$details .= "$sep<a href=\"/?option=com_literaturedatabase&view=search&task=search&subject=$id\">$subject</a>";
				$i++;
				$sep = $i == count($this->_Subject) ? ' &amp; ' : ', ';
			}
		}
		if (count($this->_Format)) {
			$details .= '<p><strong>Available in:</strong> '. implode(', ', $this->_Format) . '</p>';
		}
		$details .= '<p class="apa"><strong>Suggested Citation:</strong> <p>' . $this->APAStyle() . '</p></p>';
		if ($this->_URL) {
			$details .= '<p><strong>Web Resource:</strong> <a href="' . htmlentities($this->_URL, ENT_COMPAT, 'UTF-8')
				. '">' . htmlentities($this->_WebsiteTitle ? $this->_WebsiteTitle : $this->_URL, ENT_COMPAT, 'UTF-8') . '</a>.</p>';
		}
		if (($this->_PDFfile) && ($this->_LitID > 1150)) {
			$details .= '<p><a href="/components/com_literaturedatabase/pdf/' . urlencode($this->_PDFfile) . '"><strong>Download PDF version of this Article.</strong></a></p>';
		}
	/*	if (!LIVE) {
			$vars = get_object_vars($this);
			$details .= '<p><pre>'.print_r($vars, true).'</pre></p>';
		}	*/
		return $details; 
	} 
	
	protected function APAstyle() {
		return $this->_APA;
		switch($this->_TypeID) {
			case TYPE_BOOK:				 return $this->APAStyleBook();
			case TYPE_JOURNAL_ARTICLE:
			case TYPE_MAGAZINE_ARTICLE:
			case TYPE_NEWSPAPER_ARTICLE: return $this->APAStyleJournalArticle();
			case TYPE_OTHER:			 return $this->APAStyleOther();
			case TYPE_PAMPHLETBROCHURE:	 return $this->APAStylePamphletBrochure();
			case TYPE_POSITION_PAPER:	 return $this->APAStylePostitionPaper();
			case TYPE_MEDIA:			 return $this->APAStyleMedia();
			case TYPE_NEWSLETTER:
			case TYPE_REPORT:			 return $this->APAStyleReport();
			case TYPE_PUBLICATION:		 return $this->APAStylePublication();
			case TYPE_WEBPAGE:			 return $this->APAStyleWebpage();
			default:					 return array();
		}
	}
	
	protected function APAStyleBook() {
		$authors = $editors = $orgAuthors = $chapAuthors = '';
		$i = 1;
		foreach ($this->_Contributor as $contrib) {
			if ($contrib['TypeID'] == CONTRIB_TYPE_AUTHOR) {
				$authors .= trim($contrib['APAName']) . ', ' . ($i + 1 == count($this->_Contributor) ? '&amp; ' : '');
			}
			if ($contrib['TypeID'] == CONTRIB_TYPE_ORG_AUTHOR) {
				$orgAuthors .= trim($contrib['APAName']) . ', ' . ($i + 1 == count($this->_Contributor) ? '&amp; ' : '');
			}
			if ($contrib['TypeID'] == CONTRIB_TYPE_CHAPTER_AUTHOR) {
				$chapAuthors .= trim($contrib['APAName']) . ', ' . ($i + 1 == count($this->_Contributor) ? '&amp; ' : '');
			}
			if ($contrib['TypeID'] == CONTRIB_TYPE_EDITOR) {
				$editors .= trim($contrib['APAName']) . ', ' . ($i + 1 == count($this->_Contributor) ? '&amp; ' : '');
			}
			$i++;
		}
		if (!empty($chapAuthors)) {
			$ret = sustr($chapAuthors, 0, -2);
		} else {
			$ret = substr(!empty($authors) ? $authors : $editors, 0, -2);
		}
		$ret .= ' ('. $this->_IPYear . '). <em>' . $this->_IPSourceTitle 
			  . ($this->_ChapterSecArticleTitle ? ': ' . $this->_ChapterSecArticleTitle : '') . '</em>'
			  . ((!empty($authors) || !empty($chapAuthors)) && !empty($editors) ? ' ('.substr($editors, 0, -8). ' Eds.)': '')
			  .  '. ' . $this->_IPCity . ', ' . $this->_IPState . ': ' . $this->_IPPublisher . '.';
		return $ret;	
	}
	
	protected function APAStyleJournalArticle() {
		$authors = $editors = $orgAuthors = $chapAuthors = '';
		$i = 1;
		foreach ($this->_Contributor as $contrib) {
			if ($contrib['TypeID'] == CONTRIB_TYPE_AUTHOR) {
				$authors .= $contrib['APAName'] . ', ' . ($i + 1 == count($this->_Contributor) ? '' : '&amp; ');
			}
			if ($contrib['TypeID'] == CONTRIB_TYPE_ORG_AUTHOR) {
				$orgAuthors .= $contrib['APAName'] . ', ' . ($i + 1 == count($this->_Contributor) ? '' : '&amp; ');
			}
			if ($contrib['TypeID'] == CONTRIB_TYPE_EDITOR) {
				$editors .= $contrib['APAName'] . ', ' . ($i + 1 == count($this->_Contributor) ? '' : '&amp; ');
			}
		}
		$mag = in_array($this->_TypeID, array(TYPE_MAGAZINE_ARTICLE, TYPE_NEWSPAPER_ARTICLE));
		$authors = (!empty($orgAuthors) ? "$orgAuthors, &amp; " : '') . $authors; 
		$ret = substr(!empty($authors) ? $authors : $editors, 0, -6) . ' ('. $this->_IPYear
			 . ($mag ? ', ' . date('F', mktime(0,0,0,$this->_IPMonth)) . " $this->_IPDay" : '') . '). '
			 . '<em>' . $this->_ChapterSecArticleTitle . '. ' . $this->_JournalTitle . ', ' . $this->_IPVolume . '</em>'
			 . (!empty($this->_IPIssue) ? "($this->_IPIssue)" : '') . ' ' . ($mag ? 'pp. ' : '') . $this->_IPStartPage 
			 . ($this->_IPEndPage ? "-$this->_IPEndPage" : '') . '.';
		return $ret;
	}

	protected function APAStyleReport() {
		$authors = $editors = $orgAuthors = $chapAuthors = '';
		$i = 1;
		foreach ($this->_Contributor as $contrib) {
			if ($contrib['TypeID'] == CONTRIB_TYPE_AUTHOR) {
				$authors .= trim($contrib['APAName']) . ', ' . ($i + 1 == count($this->_Contributor) ? '&amp; ' : '');
			}
			if ($contrib['TypeID'] == CONTRIB_TYPE_ORG_AUTHOR) {
				$orgAuthors .= trim($contrib['APAName']) . ', ' . ($i + 1 == count($this->_Contributor) ? '&amp; ' : '');
			}
			if ($contrib['TypeID'] == CONTRIB_TYPE_CHAPTER_AUTHOR) {
				$chapAuthors .= trim($contrib['APAName']) . ', ' . ($i + 1 == count($this->_Contributor) ? '&amp; ' : '');
			}
			if ($contrib['TypeID'] == CONTRIB_TYPE_EDITOR) {
				$editors .= trim($contrib['APAName']) . ', ' . ($i + 1 == count($this->_Contributor) ? '&amp; ' : '');
			}
			$i++;
		}
		$ret = $this->_IPPublisher . ' ('. $this->_IPYear . '). <em>' . $this->_ChapterSecArticleTitle 
			 . '</em>. ' . $this->_IPCity . ', ' . $this->_IPState . ': Author.';
		return $ret;
	}
	
	protected function APAStyleOther() {}
	protected function APAStyleNewsletter() {}
	protected function APAStylePamphletBrochure() {}
	protected function APAStylePostitionPaper() {}
	protected function APAStyleMedia() {}
	protected function APAStylePublication() {}
	protected function APAStyleWebpage() {}
	
	static public function ellipse($str, $len) {
		$len = intval($len);
		if ($len < 1) {
			throw new Exception('ellipse(): Bad Length!');
		}
		if (strlen($str) > $len) {
			$str = substr($str, 0, $len);
			$space = strrpos($str, ' ');
			$str = ($space !== false ? substr($str, 0, $space) : $str) . ' &hellip;';
		}
		return $str;
	}
}?>