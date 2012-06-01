<?php defined('_JEXEC') or die('Restricted access');
// Hack ** to make published radio button work
if ($this->item instanceof stdClass) {
	$this->item = array($this->item);
}
 ?>
<form action="./" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<pre><?php # print_r($this->item[0]); ?></pre>
<?php 
// Hack ** added published option
$task = JRequest::getVar('task');
	echo JText::_( 'Published' );
echo JHTML::_( 'select.booleanlist',  'published', 'class="inputbox"', $this->item[0]->published );

// Hack **  to get addItem to work
foreach ($this->attrs as $a) {
	
	if($task == 'editItem') {
	$this->displayAttr($a, $this->item[0]);}
	 
	elseif($task == 'addItem') {
		$this->displayAttr($a, $this->item);
	}
	
} ?>
 <input type="hidden" name="option" value="<?php echo strtolower(JRequest::getWord('option')); ?>" />
 <input type="hidden" name="cid" value="<?php // echo $this->item->id;
// Hack **  form was creating a new item every time you edited an existing item.
$idvar = JRequest::getVar('cid',  0, ''); echo $idvar[0]; ?>" />
 <input type="hidden" name="task" value="" />
 <input type="hidden" name="typeId" value="<?php echo JRequest::getInt('typeId', $this->item->typeId); ?>" />
 <?php echo JHTML::_('form.token'); ?>
</form>
