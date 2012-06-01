<?php
/**
 * Language file
 * @author GranholmCMS
 * @link http://www.granholmcms.com
 */

defined('_JEXEC') or die('Restricted access');

class mosDefinition extends JTable {
  var $id=null;
  var $tname=null;
  var $tmail=null;
  var $tpage=null;
  var $tloca=null;
  var $tterm=null;
  var $tdefinition=null;
  var $tdate=null;
  var $tcomment=null;
  var $tedit=null;
  var $teditdate=null;
  var $published=null;
  var $catid=null;
  var $checked_out=null;

  function mosDefinition( &$db ) {
   parent::__construct( '#__definition', 'id', $db );
  }

}
?>