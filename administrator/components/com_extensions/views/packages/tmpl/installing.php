<? /* $Id: installing.php 555 2010-10-29 19:37:52Z stian $ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>
<h2 class="working">
	<?= sprintf(@text('Installing %s'), $package) ?>
	<? $b = $total + $i - 1; ?><span><?= sprintf(@text('Package %d of %d'), $i, $b) ?></span>
</h2>