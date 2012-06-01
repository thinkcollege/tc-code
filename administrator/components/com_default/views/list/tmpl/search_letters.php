<? /** $Id: search_letters.php 414 2010-08-06 09:48:31Z stian $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<ul class="search_letters">	
	<span><?= @text('Name'); ?>: </span>
	<? foreach($letters_name as $letter) : ?>
	<? $class = ($state->letter_name == $letter) ? 'class="active" ' : ''; ?>
	<li>
		<a href="<?= @route('letter_name='.$letter) ?>" <?= $class ?>>
			<?= $letter; ?>
		</a>
	</li>
	<? endforeach; ?>
	<li>
		<a href="<?= @route('letter_name=') ?>">
			<?= @text('Reset'); ?>
		</a>
	</li>
</ul>