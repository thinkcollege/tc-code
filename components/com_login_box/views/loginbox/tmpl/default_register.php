<?php
if (JRequest::getVar('go')) {
   $data = JRequest::get('post');
} else {
   $data = array();
   $data['name'] = '';
   $data['username'] = '';
   $data['email'] = '';
}
?>
<h3><?php echo JText::_('TEXT_REGISTER')?></h3>
<form method="POST" action="<?php echo JRoute::_( 'index.php?option=com_login_box&task=register' ); ?>" target="_self">
   
   <div class="fields">
      <label><?php echo JText::_('TEXT_SURENAME')?></label>
<br>
      <input name="name" value="<?php echo htmlspecialchars($data['name'])?>">      
   </div>
   
   <div class="fields">
      <label><?php echo JText::_('TEXT_USERNAME')?></label>
<br>
      <input name="username"  value="<?php echo htmlspecialchars($data['username'])?>">
   </div>
   
   <div class="fields">
      <label><?php echo JText::_('TEXT_EMAIL')?></label>
<br>
      <input name="email"  value="<?php echo htmlspecialchars($data['email'])?>">      
   </div>

   <div class="fields">
      <label><?php echo JText::_('TEXT_PASS')?></label>
<br>
      <input name="password" type="password">      
   </div>

   <div class="fields">
      <label><?php echo JText::_('TEXT_CONFIRMPASS')?></label>
<br>
      <input name="password2" type="password">      
   </div>

	<input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
	<input type="submit" value="<?php echo JText::_('TEXT_REGISTER')?>" name="go">
</form>