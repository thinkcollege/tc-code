<script type="text/javascript">
	elementExtend();
	window.addEvent('domready', function() {	
	<?php if(str_replace(" ","",$MyForm->formparams('val_validate_confirmation'))){ ?>
		<?php
			$required_fields = explode(",", str_replace(" ","",$MyForm->formparams('val_validate_confirmation')));
			foreach($required_fields as $required_field){
				$required_field_pieces = explode("=", $required_field);
			?>
				var fMessage_val_validate_confirmation = 'Please make sure that the 2 fields are matching';
				if($('<?php echo "ChronoContact_".$MyForm->formrow->name; ?>').getInputByName('<?php echo $required_field_pieces[1]; ?>').getProperty('title')){
					fMessage_val_validate_confirmation = $('<?php echo "ChronoContact_".$MyForm->formrow->name; ?>').getInputByName('<?php echo $required_field_pieces[1]; ?>').getProperty('title');
				}
				var cfvalidate_<?php echo $required_field_pieces[1]; ?> = new LiveValidation($('<?php echo "ChronoContact_".$MyForm->formrow->name; ?>').getInputByName('<?php echo $required_field_pieces[1]; ?>'), { validMessage: " " });
				cfvalidate_<?php echo $required_field_pieces[1]; ?>.add( Validate.Confirmation, { match:$('<?php echo "ChronoContact_".$MyForm->formrow->name; ?>').getInputByName('<?php echo $required_field_pieces[0]; ?>'), failureMessage: fMessage_val_validate_confirmation } );
			<?php
			}
		?>
	<?php } ?>
	});
</script>