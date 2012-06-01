<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<table><tr><td>
<ul class="latest_comments<?php echo $params->get('moduleclass_sfx'); ?>" >
<?php foreach ($list as $item) :  ?>
	<li class="latest_comment<?php echo $params->get('moduleclass_sfx'); ?>">
		<a href="<?php echo $item->link; ?>" class="latest_comment<?php echo $params->get('moduleclass_sfx'); ?>">
			<?php echo $item->text; ?></a><br>
			<small><?php if (!empty($item->created_by)) echo '<a href="'.$item->userlink.'">'; ?><?php echo $item->name; ?>
			<?php if (!empty($item->created_by)) echo '</a>'; ?> | <?php echo $item->date; ?></small>
	</li>
<?php endforeach; ?>
</ul>
</td></tr></table>