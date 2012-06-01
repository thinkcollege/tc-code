<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: CHANGELOG.php 661 2008-03-21 11:35:13Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');
?>

Changelog
---------
This is a non-exhaustive (but still near complete) changelog for
DOCman 1.x, including beta and release candidate versions.
Our thanks to all those people who've contributed bug reports and
code fixes.

Legend:
* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note

------------------ 1.4.0 RC3 Released ------------------------

21-Mar-2008 Mathias Verraes
 ! Final cleanup for RC3

20-Mar-2008 Mathias Verraes
 + Added Help button

18-Mar-2008 Mathias Verraes
 # Fixed : Language corrections, thanks dtech

17-Mar-2008 Mathias Verraes
 # Readded CSS .clr style
 # Fixed [#10086] Fatal error: Call to undefined method DOCMAN_users

01-Mar-2008 Mathias Verraes
 ^ http://forum.joomlatools.org/viewtopic.php?f=24&t=166 : Search form layout
 # Fixed [#9094] : Preview 'image' broken in upload page 3
 # Fixed [#7909], [#8103], [#9750], [#8842], [#9783], [#9953] : issues with quotes, slashes etc
 # Fixed [#6692] : Upload box is transparent
 # Fixed [#9952] : Invalid Token Error in Backend DOCman control panel: Unapproved Docs

29-Feb-2008 Mathias Verraes
 # Fixed [#9974] : Group error
 ^ Performance: Moved some require_once() statements to where they are needed

28-Feb-2008 Mathias Verraes
 # Fixed : Images sometimes weren't copied during install (J! bug?)

25-Feb-2008 Mathias Verraes
 ^ Improved 'Edit Group' UI
 # Fixed : 500 error when browsing categories
 # Fixed [#9918] : Update from backend results in token error
 # Fixed [t,681] : Logs module query, thanks slabbi and euro22

23-Feb-2008 Mathias Verraes
 # Fixed : DOClink + SEF http://forum.joomlatools.org/viewtopic.php?f=18&t=594

22-Feb-2008 Mathias Verraes
 # Various fixes
 # Fixed [#9766] Text alternative atribute is not show for thumbs and icons, thanks Fabio
 ^ Error messages for failed installation are bigger and more informative

------------------ 1.4.0 RC2 Released ------------------------

19-Feb-2008 Mathias Verraes
 ^ Updated version information
 ^ Updated feed url to http://feeds.joomlatools.org/docman

18-Feb-2008 Mathias Verraes
 ^ Updated feed urls to new blog
 * [SECURITY] [#9563] : Implemented tokens in client code

17-Feb-2008 Mathias Verraes
 * [SECURITY] [#9563] : Added DOCMAN_token class to help preventing CSRF (thanks Zinho)
 # Fixed : CSS improvements, thanks Andy

29-Jan-2008 Mathias Verraes
 # Fixed [#8239] : No scroll bar in DOClink popup, thanks Chris Jones

27-Jan-2008 Mathias Verraes
 # Fixed : Non-standard INSERTs causing incompatibility with 3PDs
 # Fixed : Buttons Plugin didn't get the parameters

10-Jan-2008 Mathias Verraes
 + Added confirmation to frontend 'delete' and 'reset' buttons
 # Fixed [t=431] : 'configuration.php unwritable' should be 'docman.config.php'

09-Jan-2008 Mathias Verraes
 # Fixed : Another little SEF url thingie

08-Jan-2008 Mathias Verraes
 + Added writable checks in installer
 # Fixed [#t=335] : Missing param check in buttons plugin, thanks cjcj01
 # Fixed [#5787] : State is lost in document edit when validation fails

07-Jan-2008 Mathias Verraes
 # Fixed [#8555] : Error occurs when saving document if using linked document and URL contains dashes
 ^ Changed [t=399] : Language strings referring to Details tab
 # Fixed [#9089] : Cannot Submit Uploads

06-Jan-2008 Mathias Verraes
 # Fixed [#7890] : Search form listed unpublished categories
 ^ Limited the number of search results returned
 # Fixed [#8078] : Save and Cancel buttons in frontend not working
 ^ Simplified document tooltips in frontend

05-Jan-2008 Mathias Verraes
 # Various small fixes and cleanup
 # Fixed [#8968] : Moved CSS calls to head
 - Removed admin menu separators
 # Fixed [#8838] : Url Length in Admin com_docman&section=documents
 # Fixed [#9041] : Back to downloads home after selecting tabs
 # Fixed : Buttons plugin not installed correctly in J!1.5

03-Jan-2008 Mathias Verraes
 # Fixed [t=365] : Issue with Community Builder

18-Dec-2007 Mathias Verraes
 - Removed 'Special Compatibility mode', too confusing
 # Fixed : Categories get renamed incorrectly http://forum.joomlatools.org/viewtopic.php?f=14&t=316

08-Dec-2007 Mathias Verraes
 ^ Performance : Added another index to #__docman
 # Fixed [#8070] : Installer DOCMAN.XML is failing on line 441 Reason: incorrect filename
 # Fixed partially : [#7972] Pathway links are incorrect / not generating properly

05-Dec-2007 Mathias Verraes
 # Fixed : 'File not found' when entering url with spaces

29-Nov-2007 Mathias Verraes
 # Fixed : Missing _DML

14-Nov-2007 Mathias Verraes
 # Fixed : SQL query error in filter of Download Logs

11-Nov-2007 Mathias Verraes
 ^ Moved buttons to a plugin

10-Nov-2007 Mathias Verraes
 # Fixed [#7999] and [#5779] : Searching with special characters
 # Fixed : Selecting multiple categories in mod_lister
   http://www.mambodocman.com/index.php?option=com_simpleboard&Itemid=35&func=view&catid=508&id=16088#16088
 # Fixed [#7977] : Errors in search results
 # Fixed : Image tags in description crashed the inputfilter

09-Nov-2007 Mathias Verraes
 ^ licenseDocumentProcess() uses $_REQUEST to allow for redirects in onBeforeDownload plugins

05-Nov-2007 Mathias Verraes
 # Fixed : ob_end_clean() doesn't exist in php < 4.3.0
 # Fixed  [#7925] : Unpublished categories accessible through guesswork

------------------ 1.4.0 RC1 Released ------------------------

01-Nov-2007 Mathias Verraes
 # Fixed : Some minor issues related to onFetchButtons

30-Oct-2007 Mathias Verraes
 # Fixed [#7857] : Upload Step 3 - JS file not loaded correctly (J15), thanks Stefano
 # Fixed tooltips in J15
 ^ Deprecated $_DOCMAN->getCfg('docman_version'), use _DM_VERSION instead
 ! Updated version information

29-Oct-2007 Mathias Verraes
 + Added onFetchButtons
 + Checked out items are now blue in frontend
 # Fixed : Missing language string in J15

26-Oct-2007 Mathias Verraes
 # Fixed : Tooltips in J15
 # Fixed : Notices when linking document in frontend
 # Fixed [#7205] : Layout issue with Beez template
 # Fixed : View button didn't work with SEF urls in J1.5

25-Oct-2007 Mathias Verraes
 * Hardened $direction in DOCMAN_Docs::getDocsByUserAccess()

18-Oct-2007 Mathias Verraes
 # Fixed : Added editor parameter for jInsertEditorText() in DOClink

16-Oct-2007 Mathias Verraes
 # Fixed [#7228] : Category image position doesn't work
 # Fixed [#7444] : Multiple identical entries for one download in download logs
 # Fixed [#7558] : Overlib javascript (thanks Reinhard)

13-Oct-2007 Mathias Verraes
 # Fixed : Deleting files during theme install

12-Oct-2007 Mathias Verraes
 + Added DOCMAN_Document::getInstance()

11-Oct-2007 Mathias Verraes
 + Added fetchdocument event

10-Oct-2007 Mathias Verraes
 # Fixed : $_DMUSER must be global
 # Fixed : Frontend uploading in J15RC3
 # Fixed : Missing icons in 1.5RC3 (note: this needs to be fixed as well: http://forum.joomla.org/index.php/topic,221525.0.html )
 # Further improvements for DOClink 1.5

26-Sept-2007 Mathias Verraes
 ^ Refactored DOClink to work with with J15

18-Sept-2007 Mathias Verraes
 # Fixed : Viewing docs in popup in J15

17-Sept-2007 Mathias Verraes
 + Added router for SEF / human readable urls in J1.5
 # Fixed : Sef Urls on redirect
 # Fixed : Updated news module to changes in SimplePie

15-Sept-2007 Mathias Verraes
 # Fixed : cleanupInstall() in theme installer in J15
 # Fixed [#7129] : Details Page css bug
 # Fixed [#7121] : Filter in "Download Logs" only filters using date column
 # Fixed : Browser logging

14-Sept-2007 Mathias Verraes
 # Fixed : Frontend menu outputs divs when no buttons present
 # Fixed : Sites with set_time_limit disabled get corrupted downloads

13-Sept-2007 Mathias Verraes
 # Fixed : Layout: category files didn't align to title
 # Fixed : mosTreeRecurse didn't support RTL

12-Sept-2007 Mathias Verraes
 # Fixed : No suffix when searching through mambot
 # Fixed : Typo in getItemid

11-Sept-2007 Mathias Verraes
 + Added "Special" compatibility mode for backwards compatibility with DOCman 1.3

07-Sept-2007 Mathias Verraes
 + Added check in installer to add indexes when upgrading from older versions
 + Performance : Added some indexes to #__docman
 ^ Reworked J! version determination
 # Fixed [#6936] : Faulty icon 32x32/no_alpha/pdf.png

06-Sep-2007 Mathias Verraes
 # Fixed : Plugins in J!1.5
 # Fixed : Using _DM_J15 in jbrowser class http://forum.joomla.org/index.php/topic,206650.msg980669.html#msg980669
 # Fixed [#6691] : Redirect to category or document details page

05-Sep-2007 Mathias Verraes
 # Fixed : Hit counter was reset when editing/updating a document

04-Sep-2007 Mathias Verraes
 # Performance : Unquoted integers in queries

03-Sep-2007 Mathias Verraes
 # Fixed : Comments in XML manifest can lead to problems
 # Fixed : Pathway issues: http://www.mambodocman.com/index.php?option=com_simpleboard&Itemid=0&func=view&id=14946&catid=7
 # Fixed : Error when two news modules are published
 # Fixed : JMenu::getInstance() now requires the client id

----------------- 1.4.0 BETA 2 Released ----------------------

01-Sep-2007 Mathias Verraes
 ^ Updated version info to v1.4.0BETA2

30-Aug-2007 Mathias Verraes
 # Fixed [#6775] : Single quote in title causes js error

29-Aug-2007 Mathias Verraes
 # Fixed : autoApprove and autoPublish
 # Fixed : Incorrect Itemid in some places
 - Removed : mosWarning for BC with J!1.5rc1

28-Aug-2007 Mathias Verraes
 # Fixed : Rewrote news module for J!1.5
 # Fixed : htmlspecialchars() is better than htmlentities()
 * Fixed : Hardened $dmdescription

27-Aug-2007 Mathias Verraes
 * Fixed : Hardened $direction (thanks Beat)
 # Fixed : $limit from request was used instead of the config setting (thanks Beat)
 + Added htmlentities() to search phrase output
 # Fixed : Missing } and echo in compat10
 # Fixed : Removed quotes from integers in queries, to improve performance
 + Added : Check if theme exists before saving or publishing
 # Fixed : Typo, $_GLOBALS should be $GLOBALS
 * Hardened mosDMDocuments against insecure 3PD extensions (thanks Beat)

22-Aug-2007 Mathias Verraes
 # Fixed [#5599] : Notice: ob_flush()
 # Fixed [#5951] : Missing language strings
 # Fixed : Calendars didn't work in J!1.5
 # Fixed : WYSIWYG editors don't work in J1.5
 # Fixed : 'Published' icon in Document list is too small
 # Fixed : Theme parameters didn't display
 # Fixed : Toolbar buttons didn't work after theme installation
 # Fixed : Notice in installer
 # Fixed : Tabs in the frontend edit form

21-Aug-2007 Mathias Verraes
 # Fixed : Dates in file list
 # Fixed : File list displays warnings
 # Fixed : Params file issue (http://forum.joomla.org/index.php/topic,203787.0.html)
 # Fixed : Admin modules weren't removed correctly during uninstallation

20-Aug-2007 Mathias Verraes
 - Removed mosWarning() workaround as this is fixed in J1.5
 # Fixed : Max Filesize setting behaved incorrectly
 ^ Adapted to changed globals handling in J1.5
 # Fixed : Search returned no results
 # Fixed : Notices in search form, added some language strings

19-Aug-2007 Mathias Verraes
 # Fixed : Messages appear incorrectly in J1.5
 + Added separate CSS for J1.5
 # Fixed : Clicking 'view' button shows license in full joomla page
 + Added $indexfile and $sef parameters to DOCMAN_Model::_formatLink()
 + Added $indexfile parameter to DOCMAN_Utils::taskLink() and _rawLink()

09-Aug-2007 Mathias Verraes
 # Fixed [#6455] : Incorrect Itemid when there are unpublished menu items
 # Fixed : dmpath was escaped incorrectly in J!1.5

08-Aug-2007 Mathias Verraes
 # Fixed : Various compatibility issues in J!1.5
 # Fixed : Editing groups in J!1.5
 # Fixed : Theme installer in J!1.5

07-Aug-2007 Mathias Verraes
 # Fixed : Various compatibility issues in J!1.5
 # Fixed : Various layout issues in J!1.5
 ^ Adapted all admin headings to J!1.5
 + Added : Email, template and group icons
 + Added : dmHTML::adminHeading() for easy admin heading rendering
 # Fixed : Missing mosWarning() in J!1.5
 # Fixed : Toolbar icons in J!1.5

06-Aug-2007 Mathias Verraes
 # Fixed : Missing icons in component menu in J!1.5
 + Added : toolbar.docman.class15.php for J!1.5 compatibility

03-Aug-2007 Mathias Verraes
 # Fixed : Issue with copying linked documents

09-July-2007 Mathias Verraes
 + 'dmcanel' is now added to the template positions during install
 + [#5965] Added language string: _DML_FILENAME_REQUIRED

04-July-2007 Mathias Verraes
 ^ Split up document state in separate 'new' and 'hot'
 ^ Moved LIMIT clauses out of the query, using setQuery() arguments instead

03-July-2007 Mathias Verraes
 ^ Made some changes to the default theme
 + Added DOCMAN_Utils::mosTreeRecurse for compatibility in 1.5
 + Added DMmosParameters for compatibility in 1.5
 ^ Changed to new official spelling: DOCman (instead of DOCMan)

26-June-2007 Mathias Verraes
 # Fixed : Runtime call by reference

23-June-2007 Mathias Verraes
 * Prevented XSS (thanks Omid)
 * Fixed possible SQL injection (thanks Omid)
 # Fixed : Prevent deletion of files that are linked to documents

21-June-2007 Mathias Verraes
 + Added config option to hide remote links from users in Details view

18-June-2007 Mathias Verraes
 # Fixed : Buttons now have dynamic width to cater for other languages
 # Fixed : Various language and layout issues (thanks Véronique)

11-June-2007 Mathias Verraes
 # Fixed [#5634] : Search result produces no message if nothing found
 + Added _DML_TPL_NO_ITEMS_FOUND to theme language
 # Fixed [#5600] : Undefined property: stdClass::$log_docidid in mod_docman_logs.php
 ^ Moved all language files to separate SVN folder

07-June-2007 Mathias Verraes
 ^ Updated Savant to v2.4.3

06-June-2007 Mathias Verraes
 # Fixed [#5556] : Missing mime icons (thanks montex)

30-May-2007 Mathias Verraes
 # Fixed : Issue with onAfterEditDocument

21-May-2007 Mathias Verraes
 # Fixed : Various J1.5 issues (WIP!)
 # Fixed : Installer now works in Joomla! 1.5
 + Added error message when typecasting fails
 # Improved Spanish language files (thanks Daniel Rodriguez)

20-May-2007 Mathias Verraes
 + Added some hardcoded language strings

19-May-2007 Mathias Verraes
 # Fixed [#5276] : Downloaded files are corrupted when using safe mode = on

16-May-2007 Mathias Verraes
 + Added Spanish language files (thanks Daniel Rodriguez)

14-May-2007 Mathias Verraes
 + Added mimetypes for OpenDocument files (thanks gvuille)

08-May-2007 Mathias Verraes
 + Added : IP's in Logs view and Logs module are now linkable (thanks Alexp)
 # Fixed [#590] : Upload permissions for front-end users
 # Fixed [#4280] : Download logs didn't display browser
 # Fixed [#2123] and others: Changed all occurrences of isAdmin to isSpecial
 # Fixed : Notices when uploading in front-end
 # Fixed [#4479] : Last Updated date not correct
 # Fixed : A bunch of improvements to the template

07-May-2007 Mathias Verraes
 # Fixed : getDocsByUserAccess didn't always return an array

02-May-2007 Mathias Verraes
 ^ DOCman_utils::_rawLink now gets the Itemid from the db

30-Apr-2007 Mathias Verraes
 + Added missing filetype icons

28-Apr-2007 Mathias Verraes
 + Document Details pagetitle now shows filename as well

25-Apr-2007 Mathias Verraes
 ^ Improved performance of mosDMCategory::getInstance()
 ^ Improved performance of DOCMAN_Cats::getAncestors()

21-Apr-2007 Mathias Verraes
 ^ Pathway now integrates with Joomla! pathway (quick fix) (thanks Ute Jacobi)
 + Added 'Copy Document' feature

20-Apr-2007 Mathias Verraes
 # Fixed [#253] as well as a whole bunch of imrpovements to the default theme

19-Apr-2007 Mathias Verraes
 # Fixed [#367] : Added $isSpecial in DOCMAN_User, category access is now conform with Joomla
 # Fixed : Edit Theme -> Published didn't work
 # Fixed : Team params layout issue
 # Fixed : Some more hardcoded language strings (sigh...)
 # Fixed : Backend menu is disabled during editing
 # Fixed [#243] : Reorganized 'Edit Document' screen in backend to fix editor issues
 + Added Sample License
 + Added Sample Group
 + Added 'Allow individual user permissions' config option for better perfomance with large userbases

3-Apr-2007 Mathias Verraes
 # Fixed : _DML_INTERRORMAMBOT typo

1-Apr-2007 Mathias Verraes
 # Fixed [#220] : Reorganized frontend 'edit document' layout
 + Added missing constant _DM_VALIDATE_USER_ALL
 + Added Save and Cancel buttons at the bottom of the frontend edit screen
 ^ Config option display_license now defaults to '1'
 - Removed : Obsolete config options: dmaccess, dmoptions
 # Fixed [#242] : Home Icon in Credits Screen didn't work
 # Fixed [#244] : Viewer didn't display correctly in documents overview

30-Mar-2007 Mathias Verraes
 # Fixed : Spelling, grammar and inconsistencies in the theme language (thanks Humvee)
 - Removed all closing php tags

28-Mar-2007 Mathias Verraes
 # Fixed : Notice: Constant _DML_VERSION already defined
 ^ Renamed release_notes.txt to README.php + updated some info
 # Fixed [#214] : Cancel Button in Configuration didn't return home
 # Fixed : Spelling, grammar and inconsistencies in the language files (thanks Humvee)
 # Fixed : Tab position is now remembered when clicking 'Apply'
 ^ Performance improvements: _formatUserName and getUserName
 ^ Performance improvements: mosDMCategory, canAccessCategory, categoryParentList

27-Mar-2007 Mathias Verraes
 # Fixed : Insufficient space on config pages to show tooltips (thanks Brian)
 # Fixed : Incorrect mosRedirect call
 + Added : Clicking on a tooltip icon shows a javascript alert
 - Removed : 'DOCman Tooltip...' title on tooltips
 # Fixed : Various HTML errors and warnings in config.html.php
 # Fixed : Toolbar buttons in 'Upload'
 # Fixed : Missing Home button in 'Credits'
 # Fixed : CSS editor now stretches to window
 ^ Performance improvements: DOCMAN_utils::countDocsInCatByUser()
 + [#173] : Added 'Apply' button and functionality to Documents, Categories,
            Groups, Licenses, Configuration, Themes, Themes CSS

26-Mar-2007 Mathias Verraes
 # Fixed : Linked documents always show generic icon (thanks hery)
 # Fixed : Constant name _DM_TYPE_UNKNOW(N)

23-Mar-2007 Mathias Verraes
 # Fixed : _COULDNOTCONNECT should be _DML_COULDNOTCONNECT

20-Mar-2007 Mathias Verraes
 # Some improvements to news module

19-Mar-2007 Mathias Verraes
 # Fixed : Unescaped quotes in docman.params.xml
 + dmHTML::viewerList and maintainerList now accept attributes and tab offset

17-Mar-2007 Mathias Verraes
 # Fixed : Performance improvement to dmHTML_UserSelect::addGroups()
 + Added DOCMAN_groups class
 # Fixed : Performance improvement to dmHTML_UserSelect::addUsers()
 + Added DOCMAN_users class
 ! Moved project to JoomlaCode.org

15-Mar-2007 Mathias Verraes
 + Added default category parameter (in component menu item)

13-Mar-2007 Mathias Verraes
 # Fixed : View-button didn't popup
 # Fixed : Updated XML files
 # Fixed : Hardened config class some more

12-Mar-2007 Mathias Verraes
 + Added 'Utopia' icons to theme
 + Themes can now have non-alpha transparent icons in /no_alpha
 # Fixed : Updated theme comments
 + Added theme object variable: 'png'
 + Added JBrowser and JObject classes from Joomla! 1.5
 + Added 'Edit Current Theme'-button to control panel

11-Mar-2007 Mathias Verraes
 ^ Adjusted default theme settings
 # Fixed : Empty categories now display normally
 # Fixed [artf3564] : Document name truncated on uploading new file (thanks Sam Lewis)
 # Fixed [artf1323] : Better fix for this issue, all characters should now be accepted properly

10-Mar-2007 Mathias Verraes
 # Fixed [artf1323] : 'Reject Filenames' config can now accept '$', TODO: quotes
 # Fixed : _DML_CFG_UPMETHODSTT: Admins always have all upload methods
 # Fixed : Unescaped quote in language file
 # Fixed : Section prefix didn't use searchbot parameter
 # Fixed : 'Search where' options are now all selected by default
 # Fixed : Incorrect use of offset in limit in setQuery()
 # Fixed : Hardcoded language strings in sample data
 # Fixed [artf7530] : Download statistics were incomplete

09-Mar-2007 Mathias Verraes
 # Javascript is stripped from tooltips
 + Mambots are now optional, added config value 'process_bots'

07-Mar-2007 Mathias Verraes
 - Removed index.html files
 + Installer automatically creates index.html files
 ^ Fixed : DmMainframe::getPath now returns false when the folder doesn't exist.
 # Fixed : Uninstaller didn't correctly drop empty tables
 # Fixed : Uninstaller now uses dmpath from config

06-Mar-2007 Mathias Verraes
 ^ [artf2382] Document descriptions now process OnPrepareContent mambots
 ^ [artf2382] Category descriptions now process OnPrepareContent mambots
 # Fixed : Hardcoded language strings in Themes
 # Fixed : Config file should never be saved during download

28-Feb-2007 Mathias Verraes
 # Fixed : @link in comments is now phpDoc-compatible
 # Fixed : Statistics layout issue
 # Fixed : Some PHP4/5 issues
 # Fixed [artf7558] : docman.config.php goes blank
 # Fixed [artf5292] : Search filter in Files section didn't work
 # Fixed [artf5945] : Status of blocked users is now indicated in the group member list
 # Fixed : Using mosParameters object as array in mosDMDocuments::_returnParam()
 # Fixed [artf7528] : Admin can't download unapproved or unpublished files

27-Feb-2007 Mathias Verraes
 # Fixed : Installer CSS issue
 + Replaced over 70 hardcoded language strings, too much to list
 ^ All language files now have consistent style

23-Feb-2007 Mathias Verraes
 # Fixed : Layout issue with 'Edit CSS'
 + Added sorting to config keys
 # Fixed : Moved DOCMAN_config::check() to dmMainFrame::_checkConfig()
 # Fixed : Moved some code from dmMainFrame::_setConfig to dmMainFrame::_checkConfig()
 # Fixed : DOCMAN_Config::check() checks if the var is set

22-Feb-2007 Mathias Verraes
 # Fixed : Mosmsg didn't display in some places
 # Fixed : $step in updateDocumentProcess
 # Fixed : $parms in HTML_DMUploadMethod::linkedFileForm
 # Fixed : $null in DOCMAN_Model::getPath
 # Fixed : $message in documents.php: saveDocument()
 # Fixed : $menus in HTML_DMCategories::edit
 # Fixed : $this->$_error and $this->$_errmsg in DOCMAN_mambot::trigger

20-Feb-2007 Mathias Verraes
 ^ The installer doesn't like multiple XML files
 ^ Updated comments
 - Removed $user_agent from DOCMAN_file.class.php
 - Removed $document, $document_name and $document_type from docman.php
 # Fixed [artf1843] : & symbols in URLs were returned &amp; when editing

19-Feb-2007 Mathias Verraes
 + Added unpublish and ordering buttons to the modules

18-Feb-2007 Mathias Verraes
 + Added parameters to all modules

17-Feb-2007 Mathias Verraes
 # Rewrote 'News' module
 # Fixed : Unuseable 'Logs' module
 # Module table headers now have column titles
 + Added 'Unapproved Documents' module
 # Fixed : Frontend form css issues

16-Feb-2007 Mathias Verraes
 + [artf3578] : Added progress bar during upload in front-end
 # Fixed [artf4956] : Buttons in 'Email to group' screen
 # Fixed [artf4961] : "Upload file & install" button can be pressed when Package File field empty
 # Fixed : Installer
 # Fixed : Parameter rendering

15-Feb-2007 Mathias Verraes
 # Fixed : Couldn't download files in backend document view
 + Improved install screen
 # Fixed : Minor issues with cleardata
 # Fixed [artf7340] : License didn't display prior to viewing a document
 - Removed references to legacy section 'Orphans'
 ^ Reworked logo
 ^ Renamed Control Panel to Home
 + Icons are now consistent throughout the whole backend
 + New Feature: Clear Data

14-Feb-2007 Mathias Verraes
 ! Received permission from http://www.iconaholic.com to include icons in package + adjusted credits
 ^ Sample data is now optional
 ^ Moved (un)installer logic to helper class
 ^ Only add sample data when no data is present
 + [artf3155] : Added confirmation when deleting items in the backend (thanks Sébastien Didier)

13-Feb-2007 Mathias Verraes
 * Fixed security issue with getPath()
 # Improvements in 'New document from file' behaviour
 # Clicking 'View' in front-end opened image in main window and popup
 + Fixed [artf5560] : You can now make new documents from within the 'Files' view

12-Feb-2007 Mathias Verraes
 # Fixed component menu separator
 # License page fixes and improvements
 # Missing $database global
 # Fixed news module layout
 # Wrong client_id for 'Top Downloads'-module
 - (Temporarily) removed Update feature
 ^ Added changelog to 'Credits' page
 ^ Reworked control panel
 * Renamed changelog.txt to php for better security
 # Missing global variable
 + Conditionally removing empty tables and folders during uninstall
 + Added sample category, file and document
 ^ Uninstalling/reinstalling no longer deletes data

11-Feb-2007 Mathias Verraes
 ^ Changed references to Mambo
 ^ Using date() in copyright notice

10-Feb-2007 Mathias Verraes
 * Hard-coded filename reject '.htaccess', added 'index.php' to default list
 # Fixed [artf7539] : Blank filelist
 ^ Reordered component menu
 # Fixed [artf7538] : components menu issue
 * Added .htaccess to protect /dmdocuments folder

09-Feb-2007 Mathias Verraes
 # Fixed : Inconsistent use of $document and $doc
 # Fixed [artf7518] : mambots get empty data
 # Fixed [artf1578] : Filesize() warning for deleted files
 + Added Language strings : _DML_BYTES, KB, MB, GB, TB (common)
 + Added Language strings : _DML_ERROR, _DML_EMPTY (common)

08-Feb-2007 Mathias Verraes
 # Fixed [artf1894] : Group permission don't show in document details
 + Added Language strings : _DML_GROUP_PUBLISHER, _DML_GROUP_EDITOR, _DML_GROUP_AUTHOR (common)
 # Fixed [artf7033] : Absolute URL's

06-Feb-2007 Mathias Verraes
 # Fixed : Bug in PEAR.php: @require_once('PEAR.php') error
 # Fixed : docman.xml didn't validate, replaced '&' with '&amp;'

------------------- Break in development ----------------------

19-Jul-2006
 # Fixed : select default upload method

23-Jun-2006
 # Fixed : mainmenu disappears click on category download mod
 # Fixed : Auto update and Apache/PHP error

20-Jun-2006
 # Fixed [artf4964] : Docman Documents section "Move" button problem
 # Fixed [artf4955] : Email Group language definitions and image
 # Fixed [artf4954] : Hardcoded language strings in config.html.php
 # Fixed [artf4671] : Language strings for emailing group not defined
 # Fixed [artf3299] : default theme not installable RC2
 # Fixed [artf4963] : Docman License page 404 error
 # Fixed [artf4960] : Docman Statistics "Cpanel" button is not functional
 # Fixed [artf4959] : Updates Section "Back" button is not functional
 # Fixed [artf4264] : Search Key Word and stripslashes

18-May-2006
 # Fixed : fixed check to see if config file is writable

06-May-2006
 ^ Changed version number to RC 3
 # Fixed : artf1327 : Fatal error: Cannot redeclare class pear in /usr/lib/php/PEAR.php
 # Fixed : links in admin lastest docs module not working
 # Fixed : frontend update always overwrites existing files

04-Apr-2006
 # Fixed : artf3167 : typo in documents.php

03-Apr-2006
 ^ Menu icons also clickable
 # Fixed : notices in frontend
 # Fixed : Use of undefined constant DM_VALIDATE_USER_ALL
 # Fixed : fatal error when adding a new document in backend

28-Mar-2006
 # Fixed IIS truncates file downloads after 2mb

25-Mar-2006
 # Fixed artf3601 : Step 3 of 3 front end problems, editor stretches out
 ! Changed copyright to (C) 2003 - 2006 The DOCman Development Team
 # Fixed : frontend forms using absolute action url

24-Sep-2005 Johan Janssens
 # Fixed : tooltips popup not always appearing
 # Fixed : Fatal error: Only variables can be passed by reference
 # Fixed : File does not exist ..../message.gif
 # Fixed artf1625 : undefined variable mosConfig_live_site
 # Fixed artf1389 : Uploaded files are not displayed

16-sep-2005 Johan Janssens
 # Fixed : cannot redeclare themeConfig
 # Fixed : search broken
 # Fixed artf1622 : hardcoded database table

------------------- 1.3 RC 2 Released ----------------------

30-sep-2005 Johan Janssens
 # Fixed : [#4921] Text editor (Tiny MCE) frozen - Cannot type file description in the ADD DOCUMENT view.

09-Aug-2005 Johan Janssens
 # Fixed : Minor warnings when using search module
 # Fixed : Description field selected by default in the 'search' option

08-Aug-2005 Johan Janssens
 # Fixed : [#5904] module don't show / load the correct docman template
   assigned in mos-administrator for docman
 # Fixed : [#6331] MODULES (both) and showing restricted file links.


07-Aug-2005 Johan Janssens
 # Fixed : "Update" creating new documents instead of updating

06-Aug-2005 Johan Janssens
 + Added CRC checksum parameter
 + Added MD5 checksum parameter

05-Aug-2005 Johan Janssens
 + Added dynamic page titles
 # Fixed : Linked documents info can't be changed in the frontend

04-Aug-2005 Johan Janssens
 # Fixed : Maintainers can't always download documents
 # Fixed : Overwrite config settings has no effect

29-July-2005 Johan Janssens
 # Fixed : Error while uploading documents

28-July-2005 Johan Janssens
 # Fixed : [#7324] Small bug in theme.class

11-July-2005 Johan Janssens
 # Fixed : [#6773] Undefined function: var_export
 # Fixed : [#6765] Savant2 template source file not found code 7
 # Fixed : [#6719] Frontend text fixed in file not in language file
 # Fixed : [#6476] listed files with an order don't work with more than 1 page
 # Fixed : [#6348] pear.php error in DocMan CP
 # Fixed : [#6329] Search result in categorie
 # Fixed : [#5981] $dmpath is not initialized
 # Fixed : [#5978] DOCman shows search categories incorrectly...

02-July-2005 Johan Janssens
 # Fixed : Can't download files larger than 2mb on PHP 5.

29-May-2005 Johan Janssens
 ! Improved frontend view document popup handling
 + Added parameters to document model
 + Added Savant2 document plugin

24-May-2005 Johan Janssens
 + Added support for document parameters
 # Fixed : [#6328] 1.3 rc1 - selecting multiple categories for files does not work

23-May-2005 Johan Janssens
 # Fixed : [#5388] undefined function is_a() error before download
 ! Moved dmParameters class to DOCman_params.class.php
 ! Added Language string : _DML_TITLE_DOCINFORMATION 	(backend)
 ! Added Language string : _DML_TITLE_DOCPERMISSIONS 	(backend)
 ! Added Language string : _DML_TITLE_DOCLICENSES 	(backend)
 ! Added Language string : _DML_TITLE_DOCDETAILS 	(backend)
 ! Added Language string : _DML_TITLE_DOCPARAMETERS 	(backend)

20-May-2005 Johan Janssens
 # Fixed : [#6286] Access to document category 'Not Authorized'
 # Fixed : [#6186] Lowercasing and converting spaces in filenames results in "un-linked" documents
 # Fixed : Upload file 'Not authorized' error
 # Fixed : [#5911] Category permissions function incorrectly

19-May-2005 Johan Janssens
 # Fixed : [#6267] empty preg_match() php warning message from DOCMAN_utils.class.php
 # Fixed : [#6240] Visitors browse only - error
 # Fixed : [#5912] Language variable missing
 ! Added Language string : _DML_RESET_COUNTER (frontent)

11-May-2004 Johan Janssens
 # Fixed : [#6135] A couple of small cosmetic bugs...
 # Fixed : [#6031] image path incorrect

11-May-2005 Johan Janssens
 # Fixed : [#5912] Language variable missing
 ! Added Language string : _DML_DELETED 	(common)
 ! Added Language string : _DML_DONT_AGREE 	(frontend)
 ! Added Language string : _DML_AGREE 		(frontend)
 ! Added Language string : _DML_TAB_PARAMS 	(backend)

------------------- 1.3 RC 1 Released ----------------------

22-Apr-2005 Johan Janssens
 # Fixed : [#4537] error when '&amp;' in filename
 # Fixed : [#3927] Saving without riquired fields
 # Fixed : [#3600] Failure to enter mainter causes loss of field data

21-Apr-2005 Johan Janssens
 # Fixed : [#4154] Files containing 'Typewriter' single quote (instead of apostrophy) get a slash in name.
 # Fixed : [#5124] Multiple categories

17-Apr-2005 Johan Janssens
 ! Moved doclink language defines to seperate file in language directory
 ! Moved modules language defines to seperate file in language directory

15-Apr-2005 Johan Janssens
 + Added frontend update feature

14-Apr-2005 Johan Janssens
 + Added Savant2 form validation plugin
 ! Moved edit form validation script to script_docedit.tpl.php
 ! Refactored search form to use structured, accessible markup
 ! Refactored upload wizard to use structured, accessible markup

12-Apr-2005 Johan Janssens
 ! Refactored edit document form to use structured, accessible markup

11-Apr-2005 Johan Janssens
 ! Cleaned and seperated language files
 + Moved frontend upload wizard to templates
 + Moved frontend edit task to templates

10-Apr-2005 Johan Janssens
 # Fixed : Orphan pagination problem

22-Mar-2005 Johan Janssens
 # Fixed : Default theme validates as xhtml transitional in Mambo 4.5.2

14-Mar-2005 Johan Janssens
 + Added category permissions checks to handle the different access types

12-Mar-2005 Johan Janssens
 # Fixed : [#4941] Error in Backend (Cannot redeclare mosmakepath())
 # Fixed : [#4806] same as [#4941]
 # Fixed : [#4612] same as [#4941]

11-Mar-2005 Johan Janssens
 # Fixed : date problems in edit document forms
 # Fixed : missing upload.png pointed to wrong file
 # Fixed : [#4877] Licence acceptation choice

01-Mar-2005 Johan Janssens
 # Fixed : [#4231] Can't create linked document
 # Fixed : [#4527] Search error
 # Fixed : [#4434] Search Failure
 + Added frontend delete document option

28-Feb-2005 Johan Janssens
 + Added support for Mambo frontend user groups (publishers, editors, authors).
 + Added publish permission setting
 # Fixed bug : [#4200] Bug in the permission system

23-Feb-2005 Johan Janssens
 + Added PEAR HTML/Common and HTML/Select packages

17-Feb-2005 Johan Janssens
 # Fixed bug : Publishers cannot download unpublished documents

17-Feb-2005 Ilias Ch
 # Fixed : [#4553] cannot redeclare mosmakepath() on install mambo 4.5.2
 # Fixed : [#4538] nothing to repeat at offset 12
 # Fixed : [#4341] search function not working

16-Feb-2005 Johan Janssens
 # Fixed bug : Headers already sent
 # Fixed : [#4132] Show unpublished items in frontend for admin
 # Fixed : [#4503] pic-path problem in 1.3 beta 4
 # Fixed : [#4368] Small cosmetic problem in docman
 # Fixed : [#4205] DOCMAN_html.class.php typo, defines.php missing def (beta4), SQL syntax error
 # Fixed : [#4175] Documents cannot be seen by even the admin when loggin in via the front end
 # Fixed : [#4130] No information in Tooltip

11-Feb-2005 Ilias Ch
 # Fixed : [#4133] Anti-leech doesn't function

10-Feb-2005 Ilias Ch
 ! Trivial changes in permissions

02-Feb-2005 Johan Janssens
 # Fixed [#4238] : Cannot upload/create linked document in backend
 # Fixed bug : No File extension
 # Fixed bug : Search links are not valid

01-Feb-2005 Johan Janssens
 ! Added 'Empty categories' parameter to default template
 + Added menu icons hide/show parameters to the default template.
 # Fixed bug : upload icon show when upload parameters are set to no access.

28-Jan-2005 Johan Janssens
 # Fixed : [#4157] Modules Strange Behaviour
 # Fixed datetime handling, added savant plugin to format dates.

27-Jan-2005 Johan Janssens
 # Fixed bug : Pathway links can't show a categories title
 ! Changed templates alternative php syntax
 ! Moved default theme language defines to theme specific language file.

26-Jan-2005 Charles Gentry
 # Fixed bug : AutoApprove not working.

25-Jan-2005 Johan Janssens
 # Fixed bug : Savant 2 : undefined PATH_SEPERATOR constant.

25-Jan 2005 Ilias Ch
 # Fixed : [#4166] Referer dir twice
 # Fixed : [#4168] dhtml.js doesn't exist in mambo 4.5.1a (only in the previous version)!
 # Fixed : [#4169] wrong path in groups.html.php for tabpane.js and tabpane.css

15-Jan-2005 Vasco Nunes
 ! Version information changed version to RC1
 + Added categories existence checking when creating new documents from backend.
   Redirects to categories management when at least one category defined is not founded.
 # Fixed typo on includes/files.html.php preventing upload tooltip to work as expected.
 # Fixed bug: deleting a license entry redirects to license management.

------------------- 1.3 Beta 4 Released ----------------------

12-Jan-2005 Johan Janssens
 # Fixed : [#4116] Typo in /themes/default/templates/categories/list_item.tpl.php
 # Fixed : [#4113] frontend: pathway not shown
 # Fixed : [#4115] Java script error in backend
 # Fixed bug : openbasedir restrictions problems in savant/recources files.
 # Fixed bug : Cannot instantiate non-existent class: dmparameters
 # Fixed bug : Redeclaration errors on certain Windows PHP version
 # Fixed bug : cannot redeclare dmConfig class

11-Jan-2005 Johan Janssens
 + Moved search output to template (page_docsearch.tpl.php)
 # Fixed bug : hardcoded mos_docman db name in DOCMAN_utils.class.php
 # Fixed bug : typo in pagenav.tpl.php

10-Jan-2005 Johan Janssens
 + Changed english terms:
   Author -> Creator; Editor -> Maintainer; Reader -> Viewer
   Terms for reading were changed (where applicable) to access or viewing.
 # Fixed nasty bug with uploads: didn't show error message and went into endless redirect.

10-Jan-2005 Johan Janssens
 ! Improved theme structure and template file naming.
 # Fixed bug : Frontend move form layout
 # Fixed bug : fixed undefined variables
 # Fixed bug : dm_cpanel.png missing on some systems,
   Extended the mosToolBar class to allow using of docman's own images folder.
 # Fixed bug : frontend publish/unpublish,
   Frontend publish/unpublish is now only possible by special users

07-Jan-2005 Charles Gentry
 + Added in Javascript to admin config to check values
 + Added in error text to english.php
 # Fixed bug in config where type = 'author' and it changes to 'admin'
 ! **above fixes bug report by Mark Semczyszyn: saw (-9) when uploading doc.

06-Jan-2005 Charles Gentry
 # Cleaned up grammer for english.php file
 # Added (back) the display of max php file (using ini_get)
 ! changed some of the terms (maintainer/creator) to make terms
   consistant.

05-Jan-2005 Johan Janssens
 + Added trimwhitespace Savant filter
 + Added config option to enable/disable the filter

04-Jan-2004 Johan Janssens
 # Fixed bug : frontend publish/unpublish only possible by special user
   Now any user with edit permissions can do this.
 + Added contributed packages to credits page
 ! restructered theme/templates

03-Jan-2005 Johan Janssens
 ! Moved frontend config options to theme params
 + Added theme edit/save functionality.
   Theme parameters are saved in themeConfig.php
 + Moved configuration class to docman.config.php
 ! Moved configuartion handling to DOCMAN_Config class

02-Jan-2005 Johan Janssens
 - Removed details mambot, functionality now handled by theme parameters
 + Moved frontend output to templates

01-Jan-2005 Johan Janssens
 + Added phpSavant library

29-Dec-2004 Johan Janssens
 + Added edit css feature to theme manager
 # Fixed bug : don't show empty categories
 # Fixed bug : show number of documents in a category

28-Dec-2004 Johan Janssens
 ! Created permissions conifguaration tab to improve usability
 + Added frontend 'approve' permissions setting

27-Dec-2004 Johan Janssens
 + Added configuaration options to show/hide the download/view links

26-Dec-2004 Johan Janssens
 ! Completely cleaned out the english language file
   Seperated defines into general, backend, frontend

23-Dec-2004 Johan Janssens
 - Removed xloadtree from repository
 ! Restyled document manager to improve usability
 + Splitted document approve and publish functionality into two seperate functions.

23-Dec-2004 Charles Gentry
 + Added max upload check and feedback on configuration
 + Allow users to enter K/M/G for upload size

22-Dec-2004 Johan Janssens
 + Added 'reset counter' option to frontend

21-Dec-2004 Charles Gentry
 + Remove basic email notification and move it to module dmebasic
 # Fixed document's triggers for proper labels.
 + Made email 'bots aware of each other.

18-Dec-2004 Charles Gentry
 # Fixed search engine to work properly with Mambo search 'bot.

17-Dec-2004 Johan Janssens
 ! Moved modules to 'modules' directory
 ! Moved document details to new details mambot

16-Dec-2004 Vasco Nunes
 + Auto-approve and basic email notification re-implemented. Needs to be tested yet.

15-Dec-2004 Charles Gentry
 + First pass at adding link and transfer to the front end. Many changes
   to both 'documents.*' and 'upload*', both cosmetic and major.
 + Added a few new strings to language file and corrected 'upload*' routines
   to use Vasco's additions.

15-Dec-2004 Johan Janssens
 # Fixed bug #3805 : XHTML Cosmetic Thing

14-Dec-2004 Charles Gentry
 + Added new update.* routines - uploaded only handlers not drivers. All
   stripped out. Will upload update.php stuff when fully tested (don't want
   to break the testers yet.)

13-Dec-2004 Vasco Nunes
 - Removed configuration options related with archiving/versioning.
 # Fixed permission bug. Anonymous users were editing documents even when not allowed.
 # Cancel button not working when editing groups. Fixed.

13-Dec-2004 Charles Gentry
 + Changed getCfg to allow default values. This will also create config
   variables on the fly if they don't exist and you have passed a default.
 + Changed setCfg to allow creation of variables. (Default = don't create)
   Added capability to save/restore arrays for config values.
 + Changed configuration routine to set most variables to defaults and
   then write them to configuration file. This allows you to create a
   config value without altering the distributed config file. (Which can
   cause sync problems.)
 + Eliminated two configuration values that were not being used

09-Dec-2004 Charles Gentry
 + Added 'Link' capability to the backend. Includes class changes
 + and addition of HTML code for javascrip testing of document.

07-Dec-2004 Vasco Nunes
 # Documents were not publishing/unpublishing from backend. Fixed.

05-Dec-2004 Johan Janssens
 + Added move funtionality to documents manager
 + Re-implemented frontend move feature

03-Dec-2004 Johan Janssens
 + Re-implemented frontend homepage and hits information

02-Dec-2004 Johan Janssens
 + Re-implemented frontend 'order by' feature.
   Documents can be ordered by name, date, hits, ascending and descending.

25-Nov-2004 Charles Gentry
 ! Major overhaul of the permissions scheme. This entailed making
   changes to a large number of modules, front and back. Most major
   changes have been in the backend classes.
 + Added DOCMAN_html to hold growing list of classes
 # Fixed 'cancel' for documents
 # Fixed group search problem for maintainer (all wrapped up in permissions)
 ! Made DOCMAN_config figure out path on initial install. (goes away on write)

25-Nov-2004 Johan Janssens
 ! Improved frontend xhtml structure.
   Categories and documents are rendered as definition lists.
 ! Moved hot/new document logic to document model class

23-Nov-2004 Vasco Nunes
 # Fixed batch uploading.
 + New and hot labels reimplemented.

21-Nov-2004 Charles Gentry
 # Documents.php had code regression: fix disable user assignation
 # Fix setting of filename for document edit
 # Fix log->delete and log.php deletion functions.
 # Fix email linkage and sending of email.
 + Added header text for email entry. (Admin form)

18-Nov-2004 Johan Janssens
 # Fixed : Cannot redeclare class bug in Win systems
 # Fixed PHP 5 Compatibility problems

18-Nov-2004 Charles Gentry
 + Added in 'Itemid=xx' for links (via rawlink)
 # Fixed download from non-pub items.
 # Fixed mambots class - allows reference or copy
 # Fixed util countDocsInCatByUser - sql was wrong and bombing.

17-Nov-2004 Vasco Nunes
 + Frontend page navigation implemented.

16-Nov-2004 Charles Gentry
 # Fixed backend document javascript to match frontend edits for documents.
 # Made SEF fix on links. (Needs relToAbs!)
 # Search engine fixed - sql and front end
 # Fixed anti-leech message
 # Fixed localhost determination

16-Nov-2004 Vasco Nunes
 ! Cleaned some hardcoded language strings
 # Fixed bug in backend licences management. Cancel button now works as expected.
 # Fixed bug with licenses main text when using tinyMCE. Now saves the content.

16-Nov-2004 Johan Janssens
 ! Version information changed to beta 4


------------------- 1.3 Beta 3 Released ----------------------

15-Nov-2004 Johan Janssens
 # Fixed : [#3349] Checkout/in function
 # Fixed : [#3096] Fatal error: Call to undefined function: getlistfooter()
 + Added thumbnail support to frontend 'edit document'

11-Nov-2004 Vasco Nunes
 # Fixed docman.xml to include cpanel png missing state
 # Fixed bad path to cpanel icon
 # Fixed syntax bug in frontend_includes/upload.php
 # Fixed bug with frontend uploading. Now proceeds to editing doc details.
 # Fixed frontend checkin/out operations

09-Nov-2004 Johan Janssens
 # Fixed [#3112] mouse-over tip is wrong
 ! Implemented a singleton pattern in dmMainFrame
 # Fixed [#3262] : Strange "cpanel" menu entries show up in backend
 # Fixed bug : No license text appears, only the Agree/Disagree/Submit buttons
 + Made mainframe responsible of loading the language file

08-Nov-2004 Johan Janssens
 + Added 'Category image' and 'Document image' configuration options
 - Removed 'Show icon' and 'Icon theme' configuaration options

06-Nov-2004 Johan Janssens
 ! Seperation between output and logic completed
 ! Frontend produces XHTML transitional 1.0 valid
 + Added : CSS classes and id's to allow easy styling

05-Nov-2004 Johan Janssens
 # Fixed bug : Thumbnails aren't saved in the backend document manager.
 + Added new DOCMAN_Model package, introduced basic MVC pattern in frontend code.

02-Nov-2004 Charles Gentry
 + Added new functions to search: invert order, negate search,
 +   regular expression. Cleaned up code and front end.

01-Nov-2004 Charles Gentry
 + Added in new search.

31-Oct-2004 Charles Gentry
 # Added quotes for name on download. (Mozilla rq'd)
 + Added new config option: Default maintainer (user/registered) and
 + view types. (for new download function)
 + Fixed SEF upload/downloads
 + Added 'mode=view' in file download method. Does inline instead of attachment.
 ! Changed _formatDoc function and toolbar display functions. More links.
 ! Changed css for add 3d button types.

30-Oct-2004 Charles Gentry
 # Fix mimetype and name on download. (Header)
 + Get rid of prompts for upload+login when user can't upload.
 # deleteGroups points to wrong routine (deleteUser)


27-Oct-2004 Charles Gentry
 + Added in Mambot triggers and lightweight mambo class.
 + Added in guest access: none/browse/browse+download
 # Fixed bugs: access, download, ownership, maintenance

27-Oct-2004 Johan Janssens
 ! Merged DOCMAN_docutils package into DOCMAN_utils package
 ! Renamed DOCMAN_permissions to DOCMAN_user package
 + Added getUser function to dmMainframe

------------------- 1.3 Beta 2 Released ----------------------


21-Oct-2004 Johan Janssens
 + Added publish/unpublish toolbar buttons to documents managaer
 - Removed thumbnail upload feature, thumbnail now use the media manager.

19-Oct-2004 Charles Gentry
 # Fixed frontend editing and saves for documents. Error messages now
   bunched together in one 'alert()' box.
 + Added in backend edits for documents in class. Now checks for all
   fields - regardless of what the javascript does.
 + Added '_LBL' in language. All LBL entries have colons/questions after.
 # Fixed bug in front end that didn't recognize who checked out source.
 + Added in checkin/checkout for frontend

18-Oct-2004 Charles Gentry
 + Added includes/defines.php - all static defines now moved there.
 # classes/DOCMAN_permissions: Group permissions now functioning
 + In classes/file - added in simpler mime testing. Reduces admin confusion.
 ! classes/DOCMAN_docutils: Tidy up SQL; allow 'mantainers' to be access
        files; allowed 'orphan' documents to be displayed
 ! includes/documents*: Moved some logic from html to driver; use defines
        for permissions (_DM_PERMIT_xxxx); track dmsubmittedby
 ! includes_frontend: added support for defines;
 ! NB: emailNotification needs fixing.


17-Oct-2004 Charles Gentry
 ! Changed docman.class.php: mosDMDocuments - added init_record(),
   check(). Standardized certain fields.
 ! Changed include/document*.php to use new class features. Always
   initializes submitted/updated fields.
 ! Changed frontend to use document class features.

16-Oct-2004 Charles Gentry
 ! Changed classes/DOCMAN_file.class.php - all functions now use new
   bit-mapped checking.
 ! Major changes to uploadURL - now uses PHP 'fopen' for access.
   Includes protocol filters (reject/accept) for security. Supports ftp,
   http, and https.
 ! Changes to all calls for uploadHTTP and uploadURL to support both
   Johan's passing of arrays for values and new bitmapped checking
   scheme.

15-Oct-2004 Vasco Nunes
 # Fixed small typo in 'DOCMAN_file.class.php'
 # Fixed bug with frontend icon types detection.

14-Oct-2004 Vasco Nunes
 # Fixed bug in 'includes_frontend/documents.php' to make it use new
   dmHTML::categoryList
 ! Changed themes directory structure to match new template schema

14-Oct-2004 Johan Janssens
 + Added DOCMAN_Install package with support for mambots, module and themes.
 ! Reworked theme installer and updater to use new install classes.

14-Oct-2004 Charles Gentry (luluware)
 + Added step 4 to upload: new display, options with icons.
 + Added option to upload with http but give new, localname. (Not on batch upload.)
 # Fixed minor typo in 'includes/document' for categoryList.
 ! changed includes/documents.php file list to ignore fname_reject list,
   any directories. Alter 'hidden file' method to consolidate tests.
 + Changed includes/config to include multiple host messages for anti-leech
 + Changed includes_frontend/download to test for multiple hosts in allowed
   downloads (allows shared files amongst servers).

12-Oct-2004 Johan Janssens
 + Added theme manager
 + Added static dmHTML class

12-Oct-2004 Vasco Nunes
 # Fixed thumbnail upload functionality. Working on both backend and frontend.

12-Oct-2004 Charles Gentry (luluware)
 # Fixed includes/categories: doesn't delete dependent categories,
   fixed new categories and parent display
 # Add back the FileUpload patches from 1.15 version to allow
   uploadURL to work amongst many others. Add in Vaso's mime changes
   to use type lookup rather than absolute file extentions.

12-Oct-2004 Vasco Nunes
 # Fixed DOCMAN_FileUpload::validateExt to perform validation of permited
   file extensions.
 ! Changed frontend uploading to handle new configuration options for
   filenames transformations.
 ! Better frontend downloads handling, log data insertion and antileech
   protection checking.
 + New email notification to superaministrators, administrators and editors
   of all submited documents, using new mambo 4.5.1 core functions, with direct
   link to approvement.
 + New details page per document
 + Added new configuration option to allow display document details as a tooltip
   or using a separate details page.

11-Oct-2004 Johan Janssens
 + Added support for module upgrades
 ! Cleaned up changelog

11-Oct-2004 Charles Gentry (luluware)
 ! make DOCMAN_file::getFile use 'fname_reject' configuration
 ! alter includes/files* to use new fname_reject, add in filter for
   document files, alter update process to include name with
   status and return to status display.

11-Oct-2004 Charles Gentry (luluware)
 # Fixed group functions: add new, display after
 # Fixed tags display on admin screen.
 ! NOTE: clicking on group name still doesn't bring up edit function for group

11-Oct-2004 Johan Janssens
 ! dmMainframe sets default dmpath and saves config.
 + Added saveConfig function to dmMainframe class
 + Moved funtions from admin.docman.php to includes/docman.php.
 ! Addes upport for default mambo installer types to updater.
 + Added mosPathName check to file package

11-Oct-2004 Charles Gentry
 + Added config options for filename handling
 ! Changed file upload to handle blanks, reject filenames and lowercase filenames

10-Oct-2004 Johan Janssens
 ! Moved file update feature to file manager.
 + Added check to deny deletion of file linked to document, only orphans
   can be deleted.

10-Oct-2004 Charles Gentry
 ! No longer display subdirectories or 'index.html' on Admin files display.
 ! Disallow uploads of index.html, index.htm and .htaccess.
 ! Went through a number of tooltips and changed some verbage.

09-Oct-2004 Johan Janssens
 ! Moved orphans functionality to file manager
 + Added missing language definitions

09-oct-2004 Vasco Nunes
 + Display unpublish documents at frontend when the user is a special user.
 + One click Publish/Unpublish operations implemented.
 ! Assign maintainers to any document.
 ! Removed strtolower from paths and filenames.
   Causing some problems over *nix systems.

08-oct-2004 Johan Janssens
 ! Added file overview to file manager
 + Added folder abstraction class to file package
 ! Moved upload function to new file manager
 ! Reworked upload functions

07-oct-2004 Vasco Nunes
 ! Frontend code changed to reflect new mainframe implementation

06-oct-2004 Johan Janssens
 + Reworked file package, added DOCMAN_File class.
   Integrated the download function.
 + Added mime mapping class
 ! Moved file utility functions to file class

05-oct-2004 Johan Janssens
 + Moved uploadwizard to includes dir
 ! Added config get functionality to dmMainFrame
 ! Category field shows full category name
 # Fixed [#2449] Files with Uppercase extensions show generic icon.
 ! Backend functionality seperated in different sections.

03-oct-2004 Johan Janssens
 ! Added multiple level category support to documents manager
 + Added dmMainFrame class and getPath functionality

30-sep-2004 Vasco Nunes
 ! New frontend navigation with multiple levek category support

29-sep-2004 Johan Janssens
 ! Improved code modularisation in documents manager
 + Added dbtable mos_docman access and attribs fields
 - Removed dbtbale mos_docman:id_subcategory field
 - Removed dbtable mos_docman_subcategories
 ! Changed category manager to handle multiple level subcategories

29-sep-2004 Vasco Nunes
 ! Theme directories moved to a unified one.
 + added a theme ccs file to be used by the frontend
 + added a new permissions class to handle categories/documents
   user permissions

28-sep-2004 Johan Janssens
 ! Restyled the backend dropdown menu to a more usable layout

25-sep-2004 Johan Janssens
 ! Moved general functions back in docman.php
 ! Changed version to 1.3.0
 ! Moved admin classes to includes directory
 + Moved document specific functions into document.php
 ! Renamed docman_utils and moved to includes/js dir.

25-sep-2004 Vasco Nunes
 ! Backend code separated in multiple, smaller files.

23-sep-2004 Johan Janssens
 # Fixed [ #2534 ] Corrupted files : XML / HTML appended to beginning of
   files (needs PHP > 4.2.0)

22-sep-2004 Johan Janssens
 ! Frontend cleanup and seperation between logic and html output

19-sep-2004 Vasco Nunes
 ! Generic db docman related operations functions moved to main class

19-sep-2004 Vasco Nunes
 + Documents thumbnail upload functionality implemented at backend

17-sep-2004 Vasco Nunes
 ! Web based update system changed to reflect directory structure changes
   in mambo 4.5.1rc4

13-sep-2004 Vasco Nunes
 + Initial support for multi-categories and document thumbnail added to db
   schema and main class

08-sep-2004 Vasco Nunes
 # Fixed bug with link to categories at cpanel

03-sep-2004 Vasco Nunes
 - Removed all language files but default english. language files should
   include now admin strings

02-sep-2004 Vasco Nunes
 + Started to add phpdocumentator remarks to docman classes

01-sep-2004 Vasco Nunes
 ! Admin language strings separated from code, added to language file

01-sep-2004 Vasco Nunes
 ! Changed docman's admin cpanel, added new switch handler

27-aug-2004 Johan Janssens
 ! Changed installer xml, added mambo version attribute

25-Aug-2004 Johan Janssens
 ! Changed installer xml to support mod_modules::client_id
 ! Cleanup changelog


------------------- 1.3 Beta 1 Released ----------------------

Aug-2004 Vasco

- RSS news feeds from www.joomlatools.org at DOCman's admin control panel
- New administration modules installed by default - latest, top and logs
- Control panel implementation at backend for easier navigation
- dhtml layer with animated gif while uploading/transfering files implemented.
- new dual select panel at groups administration
- batch uploading using HTTP protocol - experimental.
- better implementation of the documents forms.
- new upload wizard at backend, allowing HTTP upload, FTP upload and HTTP file transfer.
- new HTTP upload class implemented
- config transfered to own class with global initialization
- utils transfered to own class
- Experimental online web based updates implementation
- Turquish language file added
- New logo added
- Configuration reworked to match mambo 4.5.1
- Added direct link to documents at the backend again. Feature missed in previous release

