<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.view');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'components/com_login_box/assets/css/style1.css');

class LoginBoxViewLoginBox extends JView {
   function display($tmpl = null) {
      parent::display($tmpl);
   }
}
?>