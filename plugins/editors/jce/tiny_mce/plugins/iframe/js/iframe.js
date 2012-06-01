var IFrameDialog = {
	init : function() {
		var ed = tinyMCEPopup.editor, n = ed.selection.getNode(), pl = "", val, fe, i, t = this;
		
		tinyMCEPopup.resizeToInnerSize();		
		TinyMCE_Utils.fillClassList('classlist');	
		
		// Init plugin
		this.iframe = initIFrame();
		
		fe = ed.selection.getNode();
		if(/mceItemIFrame/.test(ed.dom.getAttrib(fe, 'class'))) {
			pl = "x={" + fe.title + "};";
			dom.value('insert', tinyMCEPopup.getLang('update', 'Update'));
		}
		
		var d = this.iframe.getParam('defaults');
		Editor.utilities.setDefaults(d);
		
		// Setup form
		if (pl != "") {
			pl = eval(pl);
			// Special attributes
			tinymce.each(['src', 'frameborder', 'marginwidth', 'marginheight', 'scrolling', 'title', 'name'], function(v){
				t.setStr(pl, v);																										   
			});
			// Standard attributes
			tinymce.each(['class', 'width', 'height', 'style', 'id', 'longdesc', 'align'], function(k){	
				v = t.getAttrib(fe, k);
				if(k == 'class'){
					dom.value('classes', v);
					dom.value('classlist', v);
				}else{
					dom.value(k, v);	
				}
			});

			// Margin
			tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
				dom.value('margin_' + o, t.getAttrib(n, 'margin-' + o));
			});
		}	
		// TMP width/height
		dom.value('tmp_width', dom.value('width'));
		dom.value('tmp_height', dom.value('height'));
		// Setup browse button
		dom.html('longdesccontainer', TinyMCE_Utils.getBrowserHTML('longdescbrowser','longdesc','file','iframe'));
		
		// Setup margins
		this.setMargins(true);
		// Setup Styles
		this.updateStyles();
		TinyMCE_EditableSelects.init();
	},
	checkPrefix : function(n){
		var t = this;
		if(/^\s*www./i.test(n.value)){
			new Confirm(tinyMCEPopup.getLang('iframe_dlg.is_external', 'The URL you entered seems to be an external link, do you want to add the required http:// prefix?'), function(state){
				if(state){
					n.value = 'http://' + n.value;
				}
				t.insertAndClose();
			});
		}else{
			this.insertAndClose();
		}
	},
	insert : function(){
		var ed = tinyMCEPopup.editor, t = this;
		
		AutoValidator.validate(document);
		if(dom.value('src') === ''){
			new Alert(tinyMCEPopup.getLang('iframe_dlg.no_src', 'An IFrame src is required'));
			return false;		
		}
		return this.checkPrefix(dom.get('src'));
	},
	insertAndClose : function() {
		var fe, html, args = {}, el, ed = tinyMCEPopup.editor;	
		
		var w = dom.value('width') || 100;
		var h = dom.value('height') || 100;
		
		w = w + dom.value('width_type').replace(/pct/, '%');
		h = h + dom.value('height_type').replace(/pct/, '%');

		fe = ed.selection.getNode();
				
		tinymce.extend(args, {
			src 		: tinyMCEPopup.getWindowArg('plugin_url') + '/img/trans.gif',
			title 		: this.serializeParameters(),
			width		: w,
			height		: h,
			'class' 	: dom.value('classes'),
			style 		: dom.value('style'),
			id 			: dom.value('id'),
			longdesc 	: dom.value('longdesc')
		});
		
		if (fe != null && ed.dom.hasClass(fe, 'mceItemIFrame')) {
			ed.dom.setAttribs(fe, args);
			ed.dom.addClass(fe, 'mceItemIFrame');
			ed.dom.setStyles(fe, {width: w, height: h});
		} else {				
			ed.execCommand('mceInsertContent', false, '<img id="__mce_tmp" src="javascript:;" />', {skip_undo : 1});		
			el = ed.dom.get('__mce_tmp');
			ed.dom.setAttribs(el, args);
			ed.dom.setStyles(el, {width: w, height: h});
			ed.dom.addClass(el, 'mceItemIFrame');
			ed.undoManager.add();
		}
		tinyMCEPopup.close();
	},
	serializeParameters : function() {
		var s = '';			
		s += this.getStr('src');
		s += this.getInt('frameborder');
		s += this.getInt('marginwidth');
		s += this.getInt('marginheight');
		s += this.getStr('scrolling');
		s += this.getStr('title');
		s += this.getStr('name');
			
		s = s.length > 0 ? s.substring(0, s.length - 1) : s;
	
		return s;
	},
	setStr : function(pl, n) {
		var ed = tinyMCEPopup.editor, e = dom.get(n);
	
		if (typeof(pl[n]) == "undefined")
			return;
			
		if(n == 'src'){
			pl[n] = ed.documentBaseURI.toRelative(pl[n]);
		}
				
		if (e.type == "text"){
			e.value = pl[n];
		}else{
			for(var i=0; i<e.options.length; i++){
				var o = e.options[i];
				if(o.value == pl[n]) {
					o.selected = true;
				}else{
					o.selected = false;
				}
			}
		}
	},
	getStr : function(n) {
		var v = dom.value(n);
		return (v == '') ? '' : n + ":'" + this.jsEncode(v) + "',";
	},
	getInt : function(n) {
		var v = dom.value(n);
		return (v == '') ? '' : n + ":" + v.replace(/[^0-9]+/g, '') + ",";
	},
	getAttrib : function(e, k) {
		var ed = tinyMCEPopup.editor, v;
		switch (k) {
			case 'class':
				v = ed.dom.getAttrib(e, k);
				v = tinymce.trim(v.replace('mceItemIFrame', '').replace(/[\s]+/, ' '));
				break;
			case 'width':
			case 'height':
				v = ed.dom.getStyle(e, k) || ed.dom.getAttrib(e, k) || '';
				if(/(%|px)/.test(v)){
					dom.value(k + '_type', /%/.test(v) ? 'pct' : 'px');
					v = parseInt(v.replace(/[^0-9]/g, ''));
				}
				break;	
			case 'align':
				v = ed.dom.getAttrib(e, 'align') || ed.dom.getStyle(e, 'float') || ed.dom.getStyle(e, 'vertical-align') || '';
				break;
			case 'margin-top':
			case 'margin-bottom':
				v = ed.dom.getStyle(e, k) || ed.dom.getAttrib(e, 'vspace') || '';
				if(v) v = parseInt(v.replace(/[^0-9]/g, ''));
				break;
			case 'margin-left':
			case 'margin-right':
				v = ed.dom.getStyle(e, k) || ed.dom.getAttrib(e, 'hspace') || '';
				if(v) v = parseInt(v.replace(/[^0-9]/g, ''));
				break;
			case 'longdesc':
				v = ed.dom.getAttrib(e, k);
				v = ed.documentBaseURI.toRelative(v);
				break;
			default:
				v = ed.dom.getAttrib(e, k);
				break;
		}
		return v;
	},
	jsEncode : function(s) {	
		s = s.replace(new RegExp('\\\\', 'g'), '\\\\');
		s = s.replace(new RegExp('"', 'g'), '\\"');
		s = s.replace(new RegExp("'", 'g'), "\\'");
	
		return s;
	},
	setMargins : function(init){
		var x = false;
		if(init){
			tinymce.each(['right', 'bottom', 'left'], function(e){
				x = (dom.value('margin_' + e) == dom.value('margin_top'));
				dom.disable('margin_' + e, x);
			});
			dom.check('margin_check', x);
		}else{
			x = dom.ischecked('margin_check');		
			tinymce.each(['right', 'bottom', 'left'], function(e){
				if(x){
					dom.value('margin_' + e, dom.value('margin_top'));
				}
				dom.disable('margin_' + e, x);
			});
			this.updateStyles();
		}
	},
	setClasses : function(v){
		return Editor.utilities.setClasses(v);
	},
	setDimensions : function(a, b){
		var tmp, av = dom.value(a), bv = dom.value(b);
		//return Editor.utilities.setDimensions(a, b);
		if(dom.ischecked('constrain')){
			var at 	= 'tmp_' + a;
			if(dom.value(at) == '') dom.value(at, av);
			// set nt as other temp value
			var bt 	= 'tmp_' + b;
			// if no values, return
			if(av == '' || bv == ''){
				return;	
			}
			// If type values are % and are equal, value is original.
			if(dom.value(a + '_type') == 'pct' && dom.value(b + '_type') == 'pct'){
				tmp = av;
			}else if(dom.value(a + '_type')){
				tmp = Math.round(bv * av / 100);	
			}else{
				tmp = av / dom.value(at) * dom.value(bt);
				tmp = tmp.toFixed(0);
			}
			dom.value(b, tmp);
		}else{
			if(dom.value(a + '_type') == 'px'){
				dom.value('tmp_' + a, av);	
			}
		}
	},
	setDimensionType : function(a, b){
		if(dom.ischecked('constrain')){
			dom.value(b + '_type', dom.value(a + '_type'));
			if(dom.value(a) !== ''){
				switch(dom.value(a + '_type')){
					case 'px':
						dom.value(a, Math.round(dom.value(a) * dom.value('tmp_' + a) / 100));
						dom.value(b, Math.round(dom.value(b) * dom.value('tmp_' + b) / 100));
						break;
					case 'pct':
						dom.value(a, Math.round(dom.value(a) / dom.value('tmp_' + a) * 100));
						dom.value(b, Math.round(dom.value(b) / dom.value('tmp_' + b) * 100));
						break;
				}
			}
		}
	},
	setStyles : function(){
		var ed = tinyMCEPopup, img = dom.get('sample');
		ed.dom.setAttrib(img, 'style', dom.value('style'));
		
		// Margin
		tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
			dom.value('margin_' + o, ImageManagerDialog.getAttrib(img, 'margin-' + o));														  
		});													  
		// Align
		dom.setSelect('align', this.getAttrib(img, 'align'));
	},
	updateStyles : function() {
		var ed = tinyMCEPopup, st, v, br, img = dom.get('sample');
		ed.dom.setAttrib(img, 'style', dom.value('style'));
		
		ed.dom.setStyle(img, 'width', '');
		ed.dom.setStyle(img, 'height', '');
		// Handle align
		ed.dom.setStyle(img, 'float', '');
		ed.dom.setStyle(img, 'vertical-align', '');

		v = dom.value('align');
		if (/(left|right)/i.test(v)){				
			ed.dom.setStyle(img, 'float', v);
		}else{
			img.style.verticalAlign = v;
		}
		// Margin
		tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
			v = dom.value('margin_' + o);
			ed.dom.setStyle(img, 'margin-' + o,  /[^a-z]/i.test(v) ? v + 'px' : v);
		});
		// Merge
		ed.dom.get('style').value = ed.dom.serializeStyle(ed.dom.parseStyle(img.style.cssText));
	}
}
var IFrame = Plugin.extend({
	moreOptions : function(){
		return {};
	},
	initialize : function(options){
		this.setOptions(this.moreOptions(), options);
		this.parent('iframe', this.options);
	}
});
IFrame.implement(new Events, new Options);
tinyMCEPopup.onInit.add(IFrameDialog.init, IFrameDialog);