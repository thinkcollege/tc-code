<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="programsdatabase">
<form id="adminForm" action="<?php echo JRoute::_('&task=save'); ?>" method="post">
 <h1>Contribute a Program to the Database</h1>
 <jdoc:include type="message" />
 <?php
	JRequest::setVar('cid', 0);
	$this->addItemForm($this->getModel('Type')->getIdByName('tta literature'), $this->get('Data', 'Item'));
 ?>
 <p><input type="submit" value="Contribute" /></p>
 <input type="hidden" name="cid" value="0" />
</form>
</div>