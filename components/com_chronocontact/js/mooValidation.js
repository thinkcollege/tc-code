/*
 * Really easy field validation with Prototype
 * http://tetlaw.id.au/view/blog/really-easy-field-validation-with-prototype
 * Andrew Tetlaw
 * Version 1.5.3 (2006-07-15)
 * 
 * Copyright (c) 2006 Andrew Tetlaw
 * http://www.opensource.org/licenses/mit-license.php
 */
Validator = new Class({
	initialize: function(className, error, test, options) {
		this.options = Object.extend({}, options || {});
		this._test = test ? test : function(v,elm){ return true };
		this.error = error ? error : 'Validation failed.';
		this.className = className;
	},
	test : function(v, elm) {
		return this._test(v,elm);
	}
});

var Validation = new Class({
	initialize : function(form, options){
		this.options = Object.extend({
			onSubmit : true,
			stopOnFirst : false,
			immediate : false,
			focusOnError : true,
			useTitles : false,
			onFormValidate : function(result, form) {},
			onElementValidate : function(result, elm) {}
		}, options || {});
		this.form = $(form);
		//Garbage.collect(this.form)
		if(this.options.onSubmit) this.form.addEvent('submit',this.onSubmit.bind(this));
		if(this.options.immediate) {
			var useTitles = this.options.useTitles;
			var callback = this.options.onElementValidate;
			this.form.getElementsBySelector("input, select, textarea").each(function(input) {
				input.addEvent('blur', function(ev) { Validation.validate(new Event(ev).target,{useTitle : useTitles, onElementValidate : callback}); });
			});
		}
	},
	onSubmit :  function(ev){
		if(!this.validate()) new Event(ev).stop();
	},
	validate : function() {
		var result = false;
		var useTitles = this.options.useTitles;
		var callback = this.options.onElementValidate;
		if(this.options.stopOnFirst) {
			result = this.form.getElementsBySelector("input, select, textarea").all(function(elm) { return Validation.validate(elm,{useTitle : useTitles, onElementValidate : callback}); });
		} else {
			result = this.form.getElementsBySelector("input, select, textarea").every(function(elm) { return Validation.validate(elm,{useTitle : useTitles, onElementValidate : callback}); });
		}
		if(!result && this.options.focusOnError) {
			this.form.getElement('.validation-failed').focus()
		}
		this.options.onFormValidate(result, this.form);
		return result;
	},
	reset : function() {
		this.form.getElementsBySelector("input, select, textarea").each(Validation.reset);
	}
});
Validation = Object.extend(Validation, {
	validate : function(elm, options){
		options = Object.extend({
			useTitle : false,
			onElementValidate : function(result, elm) {}
		}, options || {});
		elm = $(elm);
		//Garbage.collect(elm);
		var cn = elm.className.split(" ");
		return result = cn.every(function(value) {
			var test = Validation.test(value,elm,options.useTitle);
			options.onElementValidate(test, elm);
			return test;
		});
	},
	test : function(name, elm, useTitle) {
		var v = Validation.get(name);
		var prop = '__advice'+name.camelCase();
		if(Validation.isVisible(elm) && !v.test($(elm).getValue(), elm)) {
			if(!elm[prop]) {
				var advice = Validation.getAdvice(name, elm);
				if(!$pick(advice, false)) {
					var errorMsg = useTitle ? ((elm && elm.title) ? elm.title : v.error) : v.error;
					advice = new Element('div').addClass('validation-advice').setProperty(
						'id','advice-'+name+'-'+Validation.getElmID(elm)).setStyle('display','none').appendText(errorMsg);
					switch (elm.type.toLowerCase()) {
						case 'checkbox':
						case 'radio':
							var p = $(elm.parentNode);
							if(p) {
								p.adopt(advice);
							} else {
								advice.injectAfter($(elm));
							}
							break;
						default:
							advice.injectAfter($(elm));
				    }
					advice = $('advice-' + name + '-' + Validation.getElmID(elm));
				}
				try {
					$(advice).setStyles({
						'display':'block',
						'visibility':'hidden'
					}).effect('opacity').start(0,1)
				} catch(e){
					$(advice).setStyle('display','block');
				}
			}
			elm[prop] = true;
			elm.removeClass('validation-passed');
			elm.addClass('validation-failed');
			return false;
		} else {
			var advice = Validation.getAdvice(name, elm);
			if(advice) advice.setStyle('display','none');
			elm[prop] = '';
			elm.removeClass('validation-failed');
			elm.addClass('validation-passed');
			return true;
		}
	},
	isVisible : function(elm) {
		while(elm.tagName != 'BODY') {
			if($(elm).getStyle('display') == "none") return false;
			elm = elm.parentNode;
		}
		return true;
	},
	getAdvice : function(name, elm) {
		var returnVal = false;
		try{
			returnVal = $('advice-' + name + '-' + Validation.getElmID(elm))
		} catch(e){}
		if(!returnVal){
			try{
				returnVal = $('advice-' + Validation.getElmID(elm))
			} catch(e){}
		}
		return returnVal;
	},
	getElmID : function(elm) {
		return elm.id ? elm.id : elm.name;
	},
	reset : function(elm) {
		elm = $(elm);
		var cn = elm.className.split(" ");
		cn.each(function(value) {
			var prop = '__advice'+value.camelCase();
			if(elm[prop]) {
				var advice = Validation.getAdvice(value, elm);
				advice.setStyle('display','none');
				elm[prop] = '';
			}
			elm.removeClassName('validation-failed');
			elm.removeClassName('validation-passed');
		});
	},
	add : function(className, error, test, options) {
		var nv = {};
		nv[className] = new Validator(className, error, test, options);
		Validation.methods = Object.extend(Validation.methods, nv);
	},
	addAllThese : function(validators) {
		var nv = {};
		$A(validators).each(function(value) {
				nv[value[0]] = new Validator(value[0], value[1], value[2], (value.length > 3 ? value[3] : {}));
			});
		Validation.methods = Object.extend(Validation.methods, nv);
	},
	get : function(name) {
		return Validation.methods[name] ? Validation.methods[name] : new Validator();
	},
	methods : {}
});

Validation.add('IsEmpty', '', function(v) {
	return  ((v == null) || (v.length == 0)); // || /^\s+$/.test(v));
});

Validation.addAllThese([
	['required', 'This is a required field.', function(v) {
				return !Validation.get('IsEmpty').test(v);
			}],
	['validate-number', 'Please enter a valid number in this field.', function(v) {
				return Validation.get('IsEmpty').test(v) || (!isNaN(v) && !/^\s+$/.test(v));
			}],
	['validate-digits', 'Please use numbers only in this field. please avoid spaces or other characters such as dots or commas.', function(v) {
				return Validation.get('IsEmpty').test(v) ||  !/[^\d]/.test(v);
			}],
	['validate-alpha', 'Please use letters only (a-z) in this field.', function (v) {
				return Validation.get('IsEmpty').test(v) ||  /^[a-zA-Z]+$/.test(v)
			}],
	['validate-alphanum', 'Please use only letters (a-z) or numbers (0-9) only in this field. No spaces or other characters are allowed.', function(v) {
				return Validation.get('IsEmpty').test(v) ||  !/\W/.test(v)
			}],
	['validate-date', 'Please enter a valid date.', function(v) {
				var test = new Date(v);
				return Validation.get('IsEmpty').test(v) || !isNaN(test);
			}],
	['validate-email', 'Please enter a valid email address. For example fred@domain.com .', function (v) {
				return Validation.get('IsEmpty').test(v) || /\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(v)
			}],
	['validate-url', 'Please enter a valid URL.', function (v) {
				return Validation.get('IsEmpty').test(v) || /^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i.test(v)
			}],
	['validate-date-au', 'Please use this date format: dd/mm/yyyy. For example 17/03/2006 for the 17th of March, 2006.', function(v) {
				if(Validation.get('IsEmpty').test(v)) return true;
				var regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
				if(!regex.test(v)) return false;
				var d = new Date(v.replace(regex, '$2/$1/$3'));
				return ( parseInt(RegExp.$2, 10) == (1+d.getMonth()) ) && 
							(parseInt(RegExp.$1, 10) == d.getDate()) && 
							(parseInt(RegExp.$3, 10) == d.getFullYear() );
			}],
	['validate-currency-dollar', 'Please enter a valid $ amount. For example $100.00 .', function(v) {
				// [$]1[##][,###]+[.##]
				// [$]1###+[.##]
				// [$]0.##
				// [$].##
				return Validation.get('IsEmpty').test(v) ||  /^\$?\-?([1-9]{1}[0-9]{0,2}(\,[0-9]{3})*(\.[0-9]{0,2})?|[1-9]{1}\d*(\.[0-9]{0,2})?|0(\.[0-9]{0,2})?|(\.[0-9]{1,2})?)$/.test(v)
			}],
	['validate-selection', 'Please make a selection', function(v,elm){
				return elm.options ? elm.selectedIndex > 0 : !Validation.get('IsEmpty').test(v);
			}],
	['validate-one-required', 'Please select one of the above options.', function (v,elm) {
		var p = elm.parentNode;
		var options = p.getElementsByTagName('INPUT');
		for(i=0; i<options.length; i++){
			if(options[i].checked == true) {
			  return true;
			}
		}
	}]
]);