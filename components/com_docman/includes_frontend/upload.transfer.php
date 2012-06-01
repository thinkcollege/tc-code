<?php
/**
 * DOCman 1.4.x - Joomla! Document Manager
 * @version $Id: upload.transfer.php 608 2008-02-18 13:31:26Z mjaz $
 * @package DOCman_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/
defined('_VALID_MOS') or die('Restricted access');

if (defined('_DOCMAN_METHOD_TRANSFER')) {
    return true;
} else {
    define('_DOCMAN_METHOD_TRANSFER' , 1);
}

include_once dirname(__FILE__) . '/upload.transfer.html.php';

class DMUploadMethod
{
    function fetchMethodForm($uid, $step, $update = false)
    {
        global $task;

        switch ($step)
        {
            case 2: // Input the filename (Form)
            {
                $lists = array();
                $lists['action']    = _taskLink($task, $uid, array('step' => $step + 1), false);
                return HTML_DMUploadMethod::transferFileForm($lists);
            } break;

            case 3: // Copy the file and edit the document
            {
                $url   = stripslashes(mosGetParam($_REQUEST , 'url' , 'http://'));
                $file  = stripslashes(mosGetParam($_REQUEST , 'localfile' , ''));
                $err = DMUploadMethod::transferFileProcess($uid, $step, $url, $file);
                if($err['_error']) {
                	_returnTo($task, $err['_errmsg'], '', array("method" => 'transfer' , "step" => $step - 1 ,"localfile" => $file , "url" => DOCMAN_Utils::safeEncodeURL($url)));
                }

                $catid = $update ? 0 : $uid;
                $docid = $update ? $uid : 0;

                return fetchEditDocumentForm($docid , $file->name, $catid);
            } break;

            default: break;
        }
        return true;
    }

    function transferFileProcess($uid, $step, $url, &$file)
    {
        DOCMAN_token::check() or die('Invalid Token');
        global $_DMUSER, $_DOCMAN;



        if ($file == '') {
            return array(
				'_error' => 1,
				'_errmsg'=> _DML_FILENAME_REQUIRED
         	);
        }

        /* ------------------------------ *
     	*   MAMBOT - Setup All Mambots   *
     	* ------------------------------ */
        $logbot = new DOCMAN_mambot('onLog');
        $prebot = new DOCMAN_mambot('onBeforeUpload');
        $postbot = new DOCMAN_mambot('onAfterUpload');
        $logbot->setParm('filename' , $file);
        $logbot->setParm('user' , $_DMUSER);
        $logbot->copyParm('process' , 'upload');
        $prebot->setParmArray ($logbot->getParm()); // Copy the parms over
        $postbot->setParmArray($logbot->getParm());

        /* ------------------------------ *
     	*   Pre-upload                    *
     	* ------------------------------ */
        $prebot->trigger();
        if ($prebot->getError()) {
            $logbot->setParm('msg' , $prebot->getErrorMsg());
            $logbot->copyParm('status' , 'LOG_ERROR');
            $logbot->trigger();

            return array(
				'_error' => 1,
				'_errmsg'=> $prebot->getErrorMsg()
         	);
        }

		/* ------------------------------ *
     	*   Upload                        *
     	* ------------------------------ */

        $path = $_DOCMAN->getCfg('dmpath').'/';

   		//get file validation settings
   		if ($_DMUSER->isSpecial) {
      		$validate = _DM_VALIDATE_ADMIN;
   		} else {
     		if ($_DOCMAN->getCfg('user_all', false)) {
        		$validate = _DM_VALIDATE_USER_ALL ;
      		} else {
           		$validate = _DM_VALIDATE_USER;
       		}
  		}

  		//upload the file
  		$upload = new DOCMAN_FileUpload();
  		$file = $upload->uploadURL($url, $path, $validate, $file);

      /* -------------------------------- *
	 	 *    Post-upload                   *
	 	 * -------------------------------- */

        if (! $file) {
            $msg = _DML_ERROR_UPLOADING . " - " . $upload->_err;
            $logbot->setParm('msg' , $msg);
             $logbot->setParm('file', $url);
            $logbot->copyParm('status' , 'LOG_ERROR');
            $logbot->trigger();

             return array(
				'_error' => 1,
				'_errmsg'=> $msg
         	);
        }

       	$msg = "&quot;" . $file->name . "&quot; " . _DML_UPLOADED;

       	$logbot->copyParm(array('msg' => $msg ,'status' => 'LOG_OK'));
       	$logbot->trigger();

       	$postbot->setParm('file', $file);
       	$postbot->trigger();

      	if ($postbot->getError()) {
          	$logbot->setParm('msg' , $postbot->getErrorMsg());
          	$logbot->copyParm('status' , 'LOG_ERROR');
           	$logbot->trigger();

          	return array(
				'_error' => 1,
				'_errmsg'=> $postbot->getErrorMsg()
         	);
        }

       	return array(
			'_error' => 0,
			'_errmsg'=> $msg
        );
    }
}

