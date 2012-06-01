<?php # Modifications for multi language Marek T.Purzyñski 2008.03.10?>
<h3><?php echo JText::_('TEXT_LOGIN')?></h3>
<form method="POST" action="<?php echo JRoute::_( 'index.php?option=com_login_box&task=login' ); ?>" target="_self">
   
   <div class="fields">
      <label><?php echo JText::_('TEXT_USERNAME')?></label>
<br>
      <input name="username" >      
   </div>

   <div class="fields">
      <label><?php echo JText::_('TEXT_PASS')?></label>
<br>
      <input name="passwd" type="password">      
   </div>

   <div class="fields">
<input  name="remember" type="checkbox" checked="checked" value="yes">      <label><?php echo JText::_('TEXT_REMEMBERME')?></label>
   </div>

	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
	<input type="submit" value="<?php echo JText::_('TEXT_LOGIN')?>!">
</form>
<br>
<br>
<a style="font-size: 11px;" href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>"  target="_top">
   <?php echo JText::_('TEXT_FORGOT_PASS'); ?>
</a>
<br>
<a style="font-size: 11px;"  href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>" target="_top">
   <?php echo JText::_('TEXT_FORGOT_USERNAME'); ?>
</a>
<p> Problems with login?  Contact <a href="mailto:paul.foos@umb.edu">paul.foos@umb.edu</a></p><br />
