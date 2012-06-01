<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: README.php 655 2008-03-20 21:18:22Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');
?>

DOCman v1.4.0 README
--------------------

*** IMPORTANT ***

PLEASE READ THE UPGRADE INSTRUCTIONS BELOW WHEN UPGRADING FROM AN OLDER VERSION!

ALWAYS BACK UP YOUR SYSTEM'S FILES AND DATABASE BEFORE (UN)INSTALLING ANYTHING!

TO MAKE SURE THE FILES HAVEN'T BEEN TAMPERED WITH, DOWNLOAD DOCMAN, ADDONS AND
TRANSLATIONS FROM THE OFFICIAL LOCATION AT http://www.joomlatools.org/products/docman/downloads/


Table of Contents
-----------------
* About DOCman

* New Features in v1.4.0 RC3
* New Features in v1.4.0 RC2
* New Features in v1.4.0 RC1
* New Features in v1.4.0 BETA 2

* Installation in Joomla! 1.5.x
* Installation in Joomla! 1.0.x
* Upgrading from older versions of v1.4.x
* Upgrading from v1.3 RC1 or RC2
* Migrating DOCman from a Joomla! 1.0.x site to a Joomla! 1.5 site

* Official Addons
* Useful Links


About DOCman
------------
DOCman is an open source document management and download system for Joomla! v1.0.x and v1.5. With this component you can manage documents across categories and make them available for download.

These are the main features of DOCman:

* Infinite categories and subcategories. The documents can be organized across custom categories and subcategories;
* Files can be hosted locally or on a remote server
* Access control: Documents can be assigned to specific user or to custom groups of users
* Download counter and log. You can display a download counter per document and all the downloads can be logged (by user, IP, browser, date and hour);
* Own search system. Documents can be searched by name and/or description. The search system integrates with Joomla! using an optional mambot;
* Anti-leech system. The built-in anti-leech system avoids direct linking to documents;
* Path protection. Real paths to documents are never displayed to users;
* Themes: The layout can be changed using custom themes;
* ... and much more!



New features in  v1.4.0 RC3
---------------------------
* Scalability: DOCman is a lot smoother on sites with large userbases
  (when setting 'Allow individual user permissions' to off in the config)
* Shiny new Help button!


New features in  v1.4.0 RC2
---------------------------
* Edit the buttons (download, view, edit etc) in the new Button plugin
* Many small improvements and fixes


New features in  v1.4.0 RC1
--------------------------
* SEF / Human readable urls (J!1.5 only)
* The task buttons are now pluggable
* Checked out documents are now colour-coded
* Performance improvements
* Various fixes to improve Joomla! v1.5 support


New features in  v1.4.0 BETA2
----------------------------
* Compatible with Joomla! v1.5 (using legacy mode)
* Document and Category descriptions can use content mambots (optional)
* Reworked user interface
* Uninstalling or reinstalling preserves your data. Instead, data can be removed using the new 'Clear Data' feature
* Simple progress bar during upload
* Pathway integrates with Joomla
* 'Unapproved documents' admin module
* Themes can have separate icons for browsers that don't support PNG alpha transparency
* Huge performance improvements!
* Some minor security issues were fixed, about a hundred language strings were added and about a hundred other bugs were fixed. Please see the included CHANGELOG.php for a (near) complete list.


Installation in Joomla! 1.5.x
-----------------------------
DOCman only works with Joomla! v1.5RC3 or later.
1. Turn on legacy mode. [Extensions -> Plugin Manager, publish the item 'System - Legacy']
1. Check your writing permissions. [Help -> System Info -> Directory Permissions]
2. Install DOCman using Joomla's installer. [Extensions -> Install/Uninstall]
3. If you're new to DOCman, we highly recommend you click the 'Add sample data'-button.
4. Review the configuration settings and save.
5. To use the integrated search, install the optional search mambot and publish it.


Installation in Joomla! 1.0.x
-----------------------------
1. Check your writing permissions. [System -> System Info -> Permissions]
2. Install DOCman using Joomla's component installer. [Installers -> Components]
3. If you're new to DOCman, we highly recommend you click the 'Add sample data'-button.
4. Review the configuration settings and save.
5. To use the integrated search, install the optional search mambot and publish it.


Upgrading from older versions of v1.4.x
---------------------------------------
If you have any version of 1.4.x, you can always upgrade to the latest version without loosing your data, EXCEPT the config settings and the theme.
1. [Optional] Make a backup copy of /administrator/components/com_docman/docman.config.php to a different location
2. [Optional] If you have custom themes, make sure you backup these separately They're in /components/com_docman/themes
3. Uninstall DOCman 1.4.x Your files, documents, categories, licenses, logs and groups will be preserved.
4. Install the latest version of DOCman
5. [Optional] Restore docman.config.php and your theme
6. Review the configuration settings and save
7. [Optional] Uninstall old addons and install the new versions


Upgrading from v1.3 RC1 or RC2
------------------------------
Before you can upgrade to DOCman 1.4, you need to make install a patch. This patch prevents the Joomla! uninstaller from deleting your data.
1. Download docman_patch_for_v1.3rc1.zip or docman_patch_for_v1.3rc2.zip
2. Unzip
3. Install the patch (see the included README for detailed instructions)
4. Uninstall 1.3
5. Install 1.4

Note: Reverting to v1.3rcX after installing v1.4 will cause you to loose your data. Take precautions!


Migrating DOCman from a Joomla! 1.0.x site to a Joomla! 1.5 site
----------------------------------------------------------------
Download the file docman_migrator_plugins_[version].zip. Inside, there is a README file with instructions.


Official Addons
---------------
These are currently available from the official site:
* DOCLink
  Joomla editor plugin that allows the insertion of links to DOCman files using a popup dialog.
* DOCman Search
  Mambot for searching DOCman Files
* mod_docman_catdown
  Shows the documents from one specific category
* mod_docman_latestdown
  Shows the latest added documents
* mod_docman_lister
  Show a list of popular/latest documents
* mod_docman_mostdown
  Show the most downloaded documents


Useful Links
------------
* Site:
  http://www.joomlatools.org

* Blog:
  http://blog.joomlatools.org

* FAQ:
  http://www.joomlatools.org/products/docman/faq/

* Downloads:
  http://www.joomlatools.org/products/docman/downloads/

* Community Support:
  http://forum.joomlatools.org

* Bugs and feature requests:
  http://joomlacode.org/gf/project/docman/tracker/

* Vote at the Joomla! Extensions Directory:
  http://extensions.joomla.org/component/option,com_mtree/task,viewlink/link_id,82/Itemid,35/
