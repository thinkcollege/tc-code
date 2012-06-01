<?php defined('_JEXEC') or die('Restricted access');
$attrs = JRequest::getVar('attrs', null);
if ($attrs !== null) {
	if (json_encode(json_decode($attrs)) === false) {
		$attrs = json_encode(array());
	}
} else {
	foreach ($this->data->attrs as $i => &$a) {
		unset($a->attrs);
		$a->id				= intval($a->id);
		$a->typeId			= intval($a->typeId);
		$a->attrId			= intval($a->id);
		$a->attrOfId		= intval($a->attrOfId);
		$a->ordering		= intval($a->ordering > 0 ? $a->ordering : $i + 1);
		$a->sort			= intval($a->sort);
		$a->flags			= intval($a->flags);
		$a->attrOfTypeId	= intval($a->attrOfTypeId);
		$a->validation		= intval($a->validation);
	}
	$attrs = json_encode($this->data->attrs);
}
$attrs = str_replace(',{"id"', ",\n{\"id\"", $attrs);
print JRequest::getVar('nojs', 0) == 0 ? "<script type=\"text/javascript\">attrs = $attrs;</script>" : '';
$this->printJSConstant('FLAG_REQUIRED',	TableAttrOf::FLAG_REQUIRED);
$this->printJSConstant('FLAG_INTERNAL',	TableAttrOf::FLAG_INTERNAL);
$this->printJSConstant('FLAG_SUMMARY',	TableAttrOf::FLAG_SUMMARY);
$this->printJSConstant('FLAG_MULTIPLE',	TableAttrOf::FLAG_MULTIPLE);
$this->printJSConstant('FLAG_MERGE',	TableAttrOf::FLAG_MERGE);
$this->printJSConstant('FLAG_NEW',		TableAttrOf::FLAG_NEW);
$this->printJSConstant('FLAG_TITLE',	TableAttrOf::FLAG_TITLE);
$this->printJSConstant('FLAG_GROUP',	TableAttrOf::FLAG_GROUP);

print "<!--[if IE]><script language=\"VBScript\">Const MSIE = True</script><![endif]-->"
	. "<!--[if !IE]> --><script type=\"text/javascript\">const MSIE = false;</script><!-- <![endif]-->"
	. "<script type=\"text/javascript\">strings = {"
	. "'inputLabelText':'" . JText::_('Text shown on input form:')
	. "','displayLabelText':'" . JText::_('Text shown on profile:')
	. "','compareLabelText':'" . JText::_('Text shown on comparison table:')
	. "','prefixText':'" . JText::_('Prefix:')
	. "','suffixText':'" . JText::_('Suffix:')
	. "','sortText':'" . JText::_('Sort priority:')
	. "','optsText':'" . JText::_('Options:')
	. "','requiredText':'" . JText::_('This attribute is required for this type.')
	. "','internalText':'" . JText::_('This attribute is for internal only use for this type.')
	. "','summaryText':'" . JText::_('Use this attribute to summarize the type. E.g. a company\\\'s name.')
	. "','multipleText':'" . JText::_('Allow multiple values for this attribute for this type.')
	. "','mergeText':'" . JText::_('Merge this derived attribute\\\'s attributes')
	. "','newText':'" . JText::_('Allow users to add new values for this attribute while contributing/editing this type')
	. "','titleText':'" . JText::_('Use this field in the title of the item.')
	. "','groupText':'" . JText::_('Group this type by this attribute when it\\\'s profile')
	. "'};</script>";
?><form action="./" method="post" name="adminForm" id="adminForm" onsubmit="javascript:saver();">
 <p><label for="label">Name:</label>
	<input type="text" id="label" name="label" value="<?php echo JRequest::getVar('label', $this->data->label); ?>" /></p>
 <p><input type="checkbox" id="sort" name="sort" value="1" <?php echo JRequest::getInt('sort', $this->data->sort) == 1 ? 'checked="checked" ' : ''; ?> />
	<label for="sort">Sort this type Alphabetically.</label></p>
 <h2>Attributes:</h2>
 <noscript class="error">Javascript must be enabled to edit the type's attributes.</noscript>
 <div id="attributes"></div>
 <input type="hidden" name="option" value="<?php echo strtolower(JRequest::getWord('option', '')); ?>" />
 <input type="hidden" id="cid" name="cid" value="<?php echo $this->data->id; ?>" />
 <input type="hidden" name="task" value="" />
</form>