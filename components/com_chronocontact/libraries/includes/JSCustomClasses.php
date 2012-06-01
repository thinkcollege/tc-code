<script type="text/javascript">	
	window.addEvent('domready', function() { 
		<?php
		$datefieldsnames = explode(",", $MyForm->formparams('datefieldsnames'));
		if(count($datefieldsnames)){
			foreach($datefieldsnames as $datefieldsname){
				if(trim($datefieldsname)){
				?>
					myCal_<?php echo $datefieldsname; ?> = new Calendar({ <?php echo $datefieldsname; ?>: '<?php echo $MyForm->formparams('datefieldformat') ? $MyForm->formparams('datefieldformat') : 'd/m/Y'; ?>' }, { classes: ['dashboard'] }); 				
				<?php } ?>
			<?php } ?>
		<?php } ?>
	});
</script>