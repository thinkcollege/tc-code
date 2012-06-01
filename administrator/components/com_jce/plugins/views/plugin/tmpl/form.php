<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JToolBarHelper::title( JText::_( 'JCE Plugin' ) .': <small><small>[' .JText::_('Edit'). ']</small></small>', 'plugin.png' );
	JToolBarHelper::save();
	JToolBarHelper::apply();
	JToolBarHelper::cancel( 'cancelEdit', 'Close' );
	JToolBarHelper::help( 'screen.plugins.edit' );
?>

<?php
	// clean item data
	JFilterOutput::objectHTMLSafe( $this->plugin, ENT_QUOTES, '' );
?>

<?php
	$this->plugin->nameA = '';
	if ( $this->plugin->id ) {
		$row->nameA = '<small><small>[ '. $this->plugin->name .' ]</small></small>';
	}
	$icon_disabled 		= $this->plugin->row 	== '0' ? ' disabled="disabled"' : '';
	$layout_disabled 	= $this->plugin->row 	== '0' ? ' disabled="disabled"' : '';
?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		if (pressbutton == "cancelEdit") {
			submitform(pressbutton);
			return;
		}
		// validation
		var form = document.adminForm;
		if (form.title.value == "") {
			alert( "<?php echo JText::_( 'Plugin must have a title', true ); ?>" );
		} else if (form.name.value == "") {
			alert( "<?php echo JText::_( 'Plugin must have a name', true ); ?>" );
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="index.php" method="post" name="adminForm">
<div class="col width-50">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Details' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="100" class="key">
				<label for="name">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="35" value="<?php echo $this->plugin->title; ?>" />
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'Published' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<label for="folder">
					<?php echo JText::_( 'Type' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->plugin->type; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<label for="file">
					<?php echo JText::_( 'Plugin name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="35" value="<?php echo $this->plugin->name; ?>" />
			</td>
		</tr>
        <tr>
			<td valign="top" class="key">
				<label for="icon">
					<?php echo JText::_( 'Plugin Icon' ); ?>:
				</label>
			</td>
			<td>
                <input class="text_area" type="text" name="icon" id="icon" size="35" value="<?php echo $this->plugin->icon; ?>"<?php echo $icon_disabled;?> />.gif
			</td>
		</tr>
        <tr>
			<td valign="top" class="key">
				<label for="layout">
					<?php echo JText::_( 'Layout Icon' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="layout" id="layout" size="35" value="<?php echo $this->plugin->layout; ?>"<?php echo $layout_disabled;?> />.gif
			</td>
		</tr>
        <tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'Row' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['row']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'Order' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['ordering']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'Description' ); ?>:
			</td>
			<td>
				<?php echo JText::_( html_entity_decode( $this->plugin->description ) ); ?>
			</td>
		</tr>
        <tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'Preview' ); ?>:
			</td>
			<td>
				<?php echo html_entity_decode( $this->plugin->preview );?>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<?php
/*
<div class="col width-50">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Parameters' ); ?></legend>
	<?php
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('sliders');
		echo $pane->startPane("plugin-pane");
		if($this->params->getNumParams('standard')) {
			echo $pane->startPanel(JText :: _('STANDARD'), "standard-page");
			if($output = $this->params->render('params', 'standard')) :
				echo $output;
			else :
				echo "<div style=\"text-align: center; padding: 5px; \">".JText::_('There are no parameters for this item')."</div>";
			endif;
			echo $pane->endPanel();
		}

		if ($this->params->getNumParams('advanced')) {
			echo $pane->startPanel(JText :: _('ADVANCED'), "advanced-page");
			if($output = $this->params->render('params', 'advanced')) :
				echo $output;
			else :
				echo "<div  style=\"text-align: center; padding: 5px; \">".JText::_('There are no advanced parameters for this item')."</div>";
			endif;
			echo $pane->endPanel();
		}
				
		if ($this->params->getNumParams('defaults')) {
			echo $pane->startPanel(JText :: _('DEFAULTS'), "defaults-page");
			if($output = $this->params->render('params', 'defaults')) :
				echo $output;
			else :
				echo "<div  style=\"text-align: center; padding: 5px; \">".JText::_('There are no defaults parameters for this item')."</div>";
			endif;
			echo $pane->endPanel();
		}
		
		if ($this->params->getNumParams('access')) {
			echo $pane->startPanel(JText :: _('PERMISSIONS'), "access-page");
			if($output = $this->params->render('params', 'access')) :
				echo $output;
			else :
				echo "<div  style=\"text-align: center; padding: 5px; \">".JText::_('There are no access parameters for this item')."</div>";
			endif;
			echo $pane->endPanel();
		}

		echo $pane->endPane();
	?>
	</fieldset>
</div>*/
?>
<div class="clr"></div>
	<input type="hidden" name="option" value="com_jce" />
	<input type="hidden" name="id" value="<?php echo $this->plugin->id; ?>" />
	<input type="hidden" name="cid[]" value="<?php echo $this->plugin->id; ?>" />
	<input type="hidden" name="client" value="<?php echo $this->plugin->client_id; ?>" />
    <input type="hidden" name="type" value="plugin" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>