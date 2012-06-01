<?php
defined('_JEXEC') or die('Restricted access');
$typeId = $this->getTypeIdByName('TTA Literature'); ?>
<div id="ttadatabase">
 <h1>Search the Training and Technical Assistance Database</h1>
 <p class="introText">This database contains resources for those developing or enhancing a Post Secondary Education initiative for students with Intellectual Disabilities. Choose from the features below to refine your search, or select Browse All to see all items. If you would like to contribute a resource to the Training and Technical Assistance database, click the "Contribute" link on the left.</p>
 <p class="search_result"><?php echo JHTML::link(JRoute::_('&task=search&typeId='. $typeId), 'Browse All TTA items', array('class' => 'allBut')); ?></p>
 <p class="search_result"><?php echo JHTML::link(JRoute::_('&task=search&typeId='. $typeId).'?refine=popular', 'View highest rated items', array('class' => 'allBut')); ?> | <?php echo JHTML::link(JRoute::_('&task=search&typeId='. $typeId).'?refine=recent', 'View recently updated items', array('class' => 'allBut')); ?></p>
 <form action="<?php echo JRoute::_('&task=search'); ?>" method="get">
  <input type="hidden" name="typeId" value="<?php echo $typeId ?>">
  <p><strong>Search:</strong></p>
 <div class="selectIndent"><?php // Hack ** --This set of attributes is actually sub-topics not topics
echo $this->displayTypeChoicesAs(constant(get_class($this) . '::SELECT'), "a59"); ?></div><?php
	$title = $this->getModel('Attrs')->getAttrOfById(41);
	$title->inputLabel = 'Title Keyword(s):';
	echo '<p>' . $this->displayAttrInput($title, array()) . '</p>'; ?>

<?php	#echo $this->displayTypeChoicesAs(constant(get_class($this) . '::SELECT'), 'Organization');
	$desc = $this->getModel('Attrs')->getAttrOfById('43');
	$desc->typeId = 2;
	$desc->inputLabel = 'Keyword(s) in description:';
	echo $this->displayAttrInput($desc, array()); ?>

	 
<div class="selectIndent"> <?php echo $this->displayTypeChoicesAs(constant(get_class($this) . '::SELECT'), "a57"); ?></div>
	<div class="selectIndent"><?php echo "<p>" . $this->displayTypeChoicesAs(constant(get_class($this) . '::SELECT'), "a63") . "</p>"; ?></div>
 <?php  // echo $this->displayTypeChoicesAs(constant(get_class($this) . '::SELECT'), "a59-a39"); ?>
<?php #echo $this->displayTypeChoicesAs(constant(get_class($this) . '::SELECT'), 'a59-Sub-topic'); ?>
 <?php #echo $this->displayTypeChoicesAs(constant(get_class($this) . '::SELECT'), 'File Type'); ?>
 
 	<p><input type="checkbox" name="updated[]" value="+<?php echo date('Y-m-d', strtotime('-3 months')); ?>" id="updated">
	<label for="updated">Only show recently added items (items added in last 3 months).</label></p>
 <p style="display: block; height: 20px;"><button type="submit" class="button" style="float: left;margin-left: 10px;width: 85px;"><strong>Search</strong></button><input type="reset" value="Clear the Form" style="float: left;margin-left: 140px;"></p>
</form><p class="introText">The materials in this online database have been reviewed and deemed appropriate for use. Presence in the database does not imply endorsement by ThinkCollege or any of its affiliates. The intent is to provide helpful information that supports your needs.</p>
</div>