<table width="100%" cellpadding="0" cellspacing="0" border="0"  height="300">
   <tr valign="middle">
      <td>
<h3 style="text-align: center;">
<?php echo JText::_('TEXT_SUCCESS_LOGGEDIN')?>
</h3>
      </td>
   </tr>
</table>
<script type="text/javascript">
window.addEvent('domready', function() {
   setTimeout(function(){
window.parent.location.reload(true);
// Hack ** --to force relocation to forum on login
// window.parent.location.href="http://www.thinkcollege.net/members-forum?func=showcat&catid=2";
// end hack
   }, 1500);
});
</script>
