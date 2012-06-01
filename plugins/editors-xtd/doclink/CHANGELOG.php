<?php
/**
 * DOCLink 1.5.x
 * @version $Id: CHANGELOG.php 660 2008-03-21 11:33:04Z mjaz $
 * @package DOCLink_1.5
 * @copyright (C) 2003-2007 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');
?>

1. Changelog
------------
This is a non-exhaustive (but still near complete) changelog for
DOCLink, including beta and release candidate versions.
Our thanks to all those people who've contributed bug reports and
code fixes.

Legend:

# -> Bug Fix
+ -> Addition
! -> Change
- -> Removed
! -> Note

------------------ 1.5.0 RC1 Released ------------------------

22-Feb-2008 Mathias Verraes
 # Fixed  [#9801] 403 error when selecting doclink button in TinyMCE, thanks Ryan

------------------ 1.5.0 BETA2 Released ------------------------

06-Jan-2007 Mathias Verraes
  # Fixed [#8238] : 404 in 1.5RC4

------------------ 1.5.0 BETA Released ------------------------

26-Sept-2007 Mathias Verraes
 ! Branched DOClink 1.4 to /doclink/releases/1.4, started refactoring for DOClink 1.5

13-Sept-2007 Mathias Verraes (and others)
 + Various languages are added

----------------- 1.4.0 BETA 2 Released ----------------------

01-Sept-2007 Mathias Verraes
 ^ Updated version info to v1.4.0BETA2

19-June-2007 Mathias Verraes
 + Prepared German language files

16-May-2007 Mathias Verraes
 + Added Spanish language files (thanks Daniel Rodriguez)

02-May-2007 Mathias Verraes
 # Fixed : Window size in IE
 + Clicking a category now generates an icon for that category

30-Apr-2007 Mathias Verraes
 ^ Changed layout to Joomla style
 ^ Updated comments and renamed changelog.txt and readme.txt to .php
 # Fixed : Browser now shows document name instead of filename
 ^ Updated toolbar icons
 # Fixed : Icon had incorrect path in IE6
 # Fixed : Removed icon border

--------------------------------------------------------------

15-Apr-2006 Johan Janssens
 # Fixed issue with global hardening in Joomla! 1.0.11

08-Apr-2006 Johan Janssens
 # Fixed issues with Joomla! 1.0.8

29-Nov-2005 Johan Janssens
 # Fixed artf1459 : editor integration (Joomla)
 # Fixed artf1334 : Missing file for TMedit in install-script

------------------- 1.0 RC 2 Released ------------------------


31-Augst-2005 Johan Janssens
 + Added support for TMEdit
 + Added support for mosCE
 + Added [#6016] : Added support for no-editor

------------------- 1.0 RC 1 Released ------------------------

22-Apr-2005 Johan Janssens
 # Fixed : [#5784] Missing "H"

17-Apr-2005 Johan Janssens
 ! Moved doclink language defines to docman langauage directory

25-Mar-2005 Johan Janssens
 # Fixed : [#4326] DOCLink + Document permissions + IE6 makes DOCLink not work

21-Mar-2005 Johan Janssens
 + Feature [#4180] : Doclink support for TinyMCE-EXP
 # Fixed : [#5170] Ok button is inactive !

20-Mar-2005 Johan Janssens
 # Fixed : [#5027] Relative links for icons should be truly relative...
 # Fixed : [#4790] Doclink causes TinyMCE javascript errors on page save in v4.5.2 with FireFox

19-Mar-2005 Johan Janssens
 # Fixed : [#4412] 1.3.b4 Doclink button appearing twice in HTMLArea3XTD

16-Feb-2005 Johan Janssens
 # Fixed : [#4246] Text missing in Dialogwindow

------------------- 1.0 Beta 4 Released ----------------------

12-Jan-2005 Johan Janssens
 # Fixed bug : js errors in IE
 # Fixed bug : cannot include sef.php
 ! DOCLink doesn't produce SEF urls, making a content item url SEF
   is the responsibility of a content mambot

24-Dec-2004 Johan Janssens
 # Changed listview to show all document in a category (max 999).

23-Dec-2004 Johan Janssens
 # Fixed mambo session handling

21-Dec-2004 Johan Janssens
 + CSS improvements
 + Added scrollbar to document browser
 # Fixed dialog centering in Firefox

18-Dec-2004 Johan Janssens
 # Fixed bug : made links SEF compilant

08-Dec-2004 Johan Janssens
 + Added support for WysiwygPro 2.2.4

27-Nov-2004 Johan Janssens
 # Fixed problems with htmlarea3 XTD 1.0 beta in IE

18-Nov-2004 Johan Janssens
 # Fixed PHP 5 Compatibility problems

16-Nov-2004 Johan Janssens
 # Fixed support for HTMLArea3 XTD v1.0 beta in IE 6
 # Fixed : [#3390] Icon not present using TinyMCE on NT4 and IE6
 ! Moved version to beta 4

------------------- 1.0 Beta 3 Released ----------------------

09-Nov-2004 Johan Janssens
 + Added support for model classes
 ! Forced 16x16 icon output (need to be moved to a mambot paramater)

07-Nov-2004 Johan Janssens
 + Added support for HTMLArea3 XTD v1.0 beta
 ! changed events handling, implemented closures to associate
   events with object instance methods.


30-Oct-2004 Johan Janssens
 - Removed file extions images
 + Added support for DOCman themes
 # Fixed : Images are not viewed in frontend
 # Fixed : Error in IE 6.0 doclink-tinymce.js on line 42

------------------- 1.0 Beta 2 Released ----------------------

12-Oct-2004 Johan Janssens
 ! Added support for multiple level subcategories

03-Sep-2004 Johan Janssens
 ! Moved DOCLink to editors-xtd group

29-Aug-2004 Johan Janssens
 ! Moved DOCLink to a mambot format
 + TinyMCE template doesn't need to be changed anymore.

------------------- 1.0 Beta 1 Released ----------------------

14-Aug-2004 Johan Janssens
 # Fixed RTE Bug
 ! Code cleaning

09-Aug-2004 Johan Janssens
 ! Improved modal dialog box functionality

08-Aug-2004 Johan Janssens
 # Fixed IE support
 + Added styling

07-Aug-2004 Johan Janssens
 ! Initial import in cvs


2. Copyright and disclaimer
---------------------------
This application is opensource software released under the GPL.  Please
see source code and the LICENSE file