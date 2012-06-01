<?php
$usersConfig = &JComponentHelper::getParams('com_users');
$return = '';
?>

<table cellpadding="5" cellspacing="0" border="0" align="center" height="300">
   <tr valign="top">
<?php if (!JRequest::getVar('register_only')) : ?>
      <td>
<?php $this->display('login')?>
      </td>
		<?php endif; ?>
		<?php
		if ($usersConfig->get('allowUserRegistration') && !JRequest::getVar('login_only')) : ?>
         <?php if (!JRequest::getVar('register_only')) : ?>
	<td valign="middle"><img src="<?php echo JURI::base()?>components/com_login_box/assets/images/or.jpg" border="0"></td>
   		<?php endif; ?>
	<td>
<?php $this->display('register')?>
	</td>
		<?php endif; ?>
   </tr>
</table>
<script type="text/javascript">

if ($('system-message')) {
   var LB_opa = 1;
   setTimeout(function() {
      var LB_timer = setInterval(function(){
         $('system-message').setOpacity(LB_opa);
         LB_opa -= 0.01;
         if (LB_opa <= 0) {
            clearInterval(LB_timer);
            $('system-message').remove();
         }
      }, 10);
   }, 1500);
}
</script>