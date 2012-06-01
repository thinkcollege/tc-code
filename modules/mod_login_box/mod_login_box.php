<?php
JHTML::_('behavior.mootools');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'media/system/css/modal.css');
$document->addScript(JURI::base() . 'media/system/js/modal.js');
$document->addScriptDeclaration("window.addEvent('domready', function() {SqueezeBox.initialize({});});");
$user = & JFactory::getUser();

$uri = JFactory::getURI();
$url = $uri->toString();
$return = base64_encode($url);

?>

<?php if ($user->get('guest')) :?>
   <a href="<?php echo JRoute::_('index.php?option=com_login_box&login_only=1')?>" 
      onclick="SqueezeBox.fromElement(this); return false;"  
      rel="{handler: 'iframe', size: {x: 400, y: 410}}"><?php echo "Members Log in" . '' ?></a>

	  
	  
   <?php else: ?>

   <a href="javascript:void(0);" onclick="LB_onLogout(); return false;"><?php echo JText::_('LOGOUT')?></a>
<?php endif; ?>
<script type="text/javascript">
function LB_onLogout() {
   var form = new Element('form');
   form.setProperty('method', 'POST');
   form.setProperty('target', '_self');
   form.setProperty('action', 'index.php');
   
   var input = new Element('input');
   input.setProperty('type', 'hidden');
   input.setProperty('name', 'option');
   input.setProperty('value', 'com_user');
   form.appendChild(input);
   
   var input = new Element('input');
   input.setProperty('type', 'hidden');
   input.setProperty('name', 'task');
   input.setProperty('value', 'logout');
   form.appendChild(input);
   
   var input = new Element('input');
   input.setProperty('type', 'hidden');
   input.setProperty('name', 'return');
   input.setProperty('value', '<?php echo $return; ?>');
   form.appendChild(input);
   
   $E('body').appendChild(form);
   form.submit();
}
</script>