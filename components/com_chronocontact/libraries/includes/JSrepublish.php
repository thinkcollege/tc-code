<script type="text/javascript">			
	Element.extend({
		getInputByName1 : function(nome) {
			el = this.getFormElements().filterByAttribute('name','=',nome)
			return (el)?(el.length == 1)?el[0]:el:false;
		},
		setValue: function(value,append){ 
			if(value) { 
				value = value.toString(); 
				value = value.replace(/%25/g,"%"); 
				value = value.replace(/%26/g,"&"); 
				value = value.replace(/%2b/g,"+"); 
			} 
			switch(this.getTag()){ 
				case 'select': case 'select-one': 
					//this.value = value; 
					if ($type(value.split(","))=='array') value.split(",").each(function(v,i){value.split(",")[i]=v.toString()});
					sel = function(option) {
						if (($type(value.split(","))=='array'&&value.split(",").contains(option.value))||(option.value==value))option.selected = true
						else option.selected = false;
					}
					$each(this.options,sel);
					break; 
				case 'hidden': case 'text': case 'textarea': case 'input': 
					if(['checkbox', 'radio'].test(this.type)) {
						this.checked=(($type(value.split(","))=='array')?value.split(",").contains(this.value):(this.value==value));
					} else if(['file'].test(this.type)) { 
						//do nothing
					} else {
						if(append) this.value += value; else this.value = value; 
					} 
					break; 
				case 'img': 
					this.src = value; 
					break; 
				 
			} 
			return this; 
		}
	});
	window.addEvent('domready', function() {
		<?php 
		$post = $posted;
		$skiplist = explode(",", $MyForm->formparams('captcha_dataload_skip', ''));
		?>
		<?php foreach($post as $data => $value){ ?>
			<?php if(!in_array($data, $skiplist)){ ?>
				<?php if(is_array($value)){$value = "".implode(",", $value).""; $data = $data."[]";} ?>
				$('<?php echo "ChronoContact_".$MyForm->formrow->name; ?>').getInputByName1('<?php echo $data; ?>').setValue(<?php echo preg_replace('/[\n\r]+/', '\n', "'".$value."'"); ?>, '');
			<?php } ?>
		<?php } ?>			
	});
</script>