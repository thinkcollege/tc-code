<?php
//
// Copyright (C) 2006 Vince Wooll
// All rights reserved.
// Modified for Joomla 1.5 compatibility by Rich Dorfman <webhead@web-feats.com>
// jombackup version 1.5.0, 11 May 2007
//
// ################################################################
// MOS Intruder Alerts
defined( '_JEXEC' ) or die( 'Restricted access.' );
// ################################################################

global $mainframe;
$mainframe->registerEvent( 'onAfterInitialise', 'botJombackup' );

/**
*  The backup process controller
*/
function botJombackup() 
	{
	global $mainframe;
	// Global stuff rewritten for 1.5
	$CONFIG = new JConfig();
	$database 		= &JFactory::getDBO();
	$jb_abspath		= substr($CONFIG->tmp_path,0,-4);
	$jb_host		= $mainframe->getCfg( 'host' );
	$jb_user		= $mainframe->getCfg( 'user' );
	$jb_password	= $mainframe->getCfg( 'password' );
	$jb_db			= $mainframe->getCfg( 'db' );
	$jb_mailfrom	= $mainframe->getCfg( 'mailfrom' );
	$jb_fromname	= $mainframe->getCfg( 'fromname' );
	$jb_livesite	= $mainframe->getCfg( 'live_site' );
	$testing		= false;
	// You can manually set the production flag here if you don't want the "testing" option to kick in at any point. Effectively it means that the mambot query will not be run until $okToContinue is true, which
	// only occurs if today's checkFile doesn't exist.
	// If you DO manually set this flag, then of course none of the testing data will be echoed to your browser
	$production		= false;

	/** 
	This query has been deliberately placed in this part of the code to allow the user to perform tests to ensure that the script is working, however it means that this script performs 1 sql query every time Joomla runs, unless $production is set to true.
	**/
	
	// Joomla 1.5. Get plugin parameters	
	if (!$production)
		{
		$plugin 		=& JPluginHelper::getPlugin('system', 'jombackup.systembot');
		$pluginParams 	= new JParameter( $plugin->params );
		$testing		= $pluginParams->def( 'testing', 0 );
		}
	/** Finish bot parameter loading **/
	
	$mediaPath		= $jb_abspath.DS.'media';
	$checkfileName	= 'jombackup_checkfile_';
	$today 			= date("Y-m-d");
	$dateCheckFile	= $checkfileName.$today;
	$okToContinue	= true;
	
	if ($testing)
		{
		$yesterday				= date("Y-m-d" ,strtotime("yesterday") );
		$yesterdaysCheckfile	= $checkfileName.$yesterday;
		if (is_file($mediaPath.DS.$dateCheckFile) && @is_writable($mediaPath.DS.$dateCheckFile) )
			{
			unlink($mediaPath.DS.$dateCheckFile);
			}
		}
	if (is_writable($mediaPath) )  // a couple of simple checks to see if we need to actually do anything
		{
		if (is_file($mediaPath.DS.$dateCheckFile) ) // The backup has already been done, no need to continue
			$okToContinue=false;
		else
			{
			if (!$testing)
				{
				if (!touch($mediaPath.DS.$dateCheckFile)) // Oops, we can't create the date check file, no point in continuing otherwise this plugin will run EVERY time a link is clicked in Joomla. Not good.
					{
					echo "Couldn't create check file. Please ensure that $mediaPath is writable by the web server";
					$okToContinue=false;
					}
				}
			}
		}
	else
		$okToContinue=false;
		
	if ($testing)
		{
		if ($okToContinue) 
			echo "Backing up and emailing system mysqldata<br />";
		else
			echo "Not backing up system data<br />";
			
		}
	if ($okToContinue) 
		{
		// No need to do the require beforehand if not ok to continue, so we'll do it here to save an eeny weeny amount of time
		require_once($jb_abspath.'/plugins/system/jombackup/mysql_db_backup.class.php');
		/** Alternative location for Bot query  **/
		if ($production)
			{
			$plugin 		=& JPluginHelper::getPlugin('system', 'jombackup.systembot');
			$pluginParams 	= new JParameter( $plugin->params );
			$testing		= $pluginParams->def( 'testing', 0 );
			}
		$deletefile		= $pluginParams->def( 'deletefile', false );
		$compress		= $pluginParams->def( 'compress', 0 );
		$backuppath		= $pluginParams->def( 'backuppath', 0 );
		
		// Ok, let's crack on. First we want to get rid of yesterday's jombackup_checkfile, no need to have that lying around now
		$yesterday				= date("Y-m-d" ,strtotime("yesterday") );
		$yesterdaysCheckfile	= $checkfileName.$yesterday;
		if (is_file($yesterdaysCheckfile) && @is_writable($yesterdaysCheckfile) )
			{
			unlink($yesterdaysCheckfile);
			}
		// Now we need to create the backup
		$backup_obj 	= new Jombackup_MySQL_DB_Backup();
		$result			= jombackupBackup($backup_obj,$jb_host,$jb_user,$jb_password,$jb_db,$pluginParams,$mediaPath,$jb_fromname,$compress,$backuppath);
		$backupFile		= $backup_obj->jombackup_file_name;
		// and email it to wherever
		$EmailResult	= jombackupEmail($pluginParams,$jb_mailfrom,$jb_fromname,$backupFile,$result['output'],$jb_livesite);
		if ($deletefile=="1" && !empty($backupFile) )
			{
			if ($testing)
				echo "Deleting backup file $backupFile";
			unlink($backupFile);	
			}
		else if ($testing)
			echo "Not deleting backup file $backup_obj->jombackup_file_name";
		// Job done
		}
	}

function jombackupEmail($pluginParams,$jb_mailfrom,$jb_fromname,$Attachment,$Body,$jb_livesite)
	{
	$ToEmail 		= $pluginParams->def( 'recipient', '' );
	$Subject 		= $pluginParams->def( 'subject', 'Mysql backup' );
	$FromName 		= $pluginParams->def( 'fromname', $jb_fromname );
	if (empty($ToEmail) )
		$ToEmail=$jb_mailfrom;
	JUtility::sendMail( $jb_mailfrom, $FromName, $ToEmail, $Subject.' '.$jb_livesite, $Body, $mode=0, $cc=NULL, $bcc=NULL, $Attachment, $replyto=NULL, $replytoname=NULL);
	}

function jombackupBackup(&$backup_obj,$jb_host,$jb_user,$jb_password,$jb_db,$pluginParams,$mediaPath,$jb_fromname,$compress,$backuppath)
	{
	$Body 				= $pluginParams->def( 'body', 'Mysql backup from '.$jb_fromname );
	$drop_tables 		= $pluginParams->def( 'drop_tables', 1 );
	$create_tables 		= $pluginParams->def( 'create_tables', 1 );
	$struct_only 		= $pluginParams->def( 'struct_only', 1 );
	$locks 				= $pluginParams->def( 'locks', 1 );
	$comments 			= $pluginParams->def( 'comments', 1 );
	$tables_to_ignore	= $pluginParams->def( 'tables_to_ignore', '' );
	
	// Let's set the tables to ignore array.
	if (strlen($tables_to_ignore)>0)
		{
		$ignoreArray=explode(",",$tables_to_ignore);
		$backup_obj->setTablesToIgnore($ignoreArray);
		}
		
	if (!empty($backuppath) && is_dir($backuppath) && @is_writable($backuppath)  )
		$backup_dir 		= $backuppath;
	else
		$backup_dir 		= $mediaPath;

	//----------------------- EDIT - REQUIRED SETUP VARIABLES -----------------------	
	$backup_obj->server 	= $jb_host;
	$backup_obj->port 		= 3306;
	$backup_obj->username 	= $jb_user;
	$backup_obj->password 	= $jb_password;
	$backup_obj->database 	= $jb_db;		
	//Tables you wish to backup. All tables in the database will be backed up if this array is null.
	$backup_obj->tables = array();
	//------------------------ END - REQUIRED SETUP VARIABLES -----------------------
	
	//-------------------- OPTIONAL PREFERENCE VARIABLES ---------------------
	//Add DROP TABLE IF EXISTS queries before CREATE TABLE in backup file.
	$backup_obj->drop_tables 	= $drop_tables;	
	//No table structure will be backed up if false
	$backup_obj->create_tables 	= $create_tables;
	//Only structure of the tables will be backed up if true.
	$backup_obj->struct_only 	= $struct_only;
	//Add LOCK TABLES before data backup and UNLOCK TABLES after
	$backup_obj->locks 			= $locks;
	//Include comments in backup file if true.
	$backup_obj->comments 		= $comments;
	//Directory on the server where the backup file will be placed. Used only if task parameter equals MSX_SAVE.
	$backup_obj->backup_dir 	= $backup_dir.DS;
	//Default file name format.
	$backup_obj->fname_format 	= 'd_m_Y';
	//Values you want to be intrerpreted as NULL
	$backup_obj->null_values 	= array( );
	
	$savetask = MSX_SAVE;		
	//Optional name of backup file if using 'MSX_APPEND', 'MSX_SAVE' or 'MSX_DOWNLOAD'. If nothing is passed, the default file name format will be used.
	$filename = '';
	//--------------------- END - REQUIRED EXECUTE VARIABLES ----------------------
	$result_bk = $backup_obj->Execute($savetask, $filename, $compress);
	if (!$result_bk)
		{
		$output = $backup_obj->error;
		}
	else
		{
		$output = $Body.': ' . strftime('%A %d %B %Y  - %T ') . ' ';
		}
	return array('result'=>$result_bk,'output'=>$output);
	}

?>