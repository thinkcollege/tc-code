<?php // @version $Id: default_form.php 10381 2008-06-01 03:35:53Z pasamio $
defined('_JEXEC') or die('Restricted access');
?>

<form action="<?php echo JRoute::_( 'index.php?option=com_search#content' ) ?>" method="post" class="search_result<?php echo $this->params->get('pageclass_sfx') ?>">
<a name="form1"></a>
<h3><?php echo JText::_('search_again'); ?></h3>
<fieldset class="word">
<label for="search_searchword"><?php echo JText::_('Search Keyword') ?> </label>
<input type="text" name="searchword" id="search_searchword"  maxlength="50" value="<?php echo $this->escape($this->searchword) ?>" class="inputbox" />
</fieldset>

<fieldset>
<legend><?php echo JText::_('Search Parameters') ?></legend>
<?php echo $this->lists['searchphrase']; ?>
<br /><br />
<label for="ordering" class="ordering"><?php echo JText::_('Ordering') ?>:</label>
<?php echo $this->lists['ordering']; ?>
</fieldset>


<p>
	<button name="Search" onClick="this.form.submit()" class="button"><?php echo JText::_( 'Search' );?></button>
</p>


<?php if (count($this->results)) : ?>
<div class="display">
<label for="limit"><?php echo JText :: _('Display Num') ?></label>
	<?php echo $this->pagination->getLimitBox(); ?>
	<p>
		<?php echo $this->pagination->getPagesCounter(); ?>
	</p>
</div>
<?php endif; ?>

<input type="hidden" name="task"   value="search" />
</form>