var CaptionDialog = {
	init : function() {
		var ed = tinyMCEPopup.editor, n = ed.selection.getNode(), im = n, action = 'insert', w, h, t = this;
		tinyMCEPopup.resizeToInnerSize();
		
		n = ed.dom.getParent(n, "A") || n;
		
		if(n.nodeName == 'A'){
			im = n.firstChild;
		}

		el = ed.dom.getParent(n, "DIV");
		if(el && /jce_caption/i.test(el.className)){
			action = "update";
		}
		
		TinyMCE_Utils.fillClassList('classlist');
		TinyMCE_Utils.fillClassList('text_classlist');
		
		// Init plugin
		this.caption = initCaption();
		
		// Get/Set defaults
		var defaults = this.caption.getParam('defaults');
		tinymce.each(defaults, function(v, k){	
			if(/^(margin|padding|text_padding|text_margin)$/.test(k)){
				tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
					if(v == 'default') v = '';
					dom.value(k + '_' + o, v);
				});	
			}else{
				dom.value(k, v);	
			}
		});
		
		dom.value('insert', tinyMCEPopup.getLang(action, 'Insert', true)); 
		
		var iw = ed.dom.getStyle(im, 'width') || ed.dom.getAttrib(im, 'width');
		var ih = ed.dom.getStyle(im, 'height') || ed.dom.getAttrib(im, 'height');
		
		iw = iw.replace(/[^0-9]/g, '');
		ih = ih.replace(/[^0-9]/g, '');
						
		w = ( iw >= 120 ) ? 120 : iw;
		h = ( w / iw ) * ih;			
		if( h > 120 ){
			h = 120;
			w = ( h / ih ) * iw;
		}
		img 		= dom.get('caption_image');
		img.src 	= im.src;
		img.width 	= w;
		img.height 	= h;
		
		ed.dom.setStyle(dom.get('caption'), 'width', w || 120);
		//ed.dom.setStyle(dom.get('caption_text'), 'width', w || 120);
		
		// We have a caption!
		if(el != null){
			// Margin
			tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
				dom.value('margin_' + o, t.getAttrib(el, 'margin-' + o));														  
			});
			// Padding
			tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
				dom.value('padding_' + o, t.getAttrib(el, 'padding-' + o));														  
			});	
			// Border
			dom.setSelect('border_width', this.getAttrib(el, 'border-width'), true);
			dom.value('border_style', this.getAttrib(el, 'border-style'));
			dom.value('border_color', this.getAttrib(el, 'border-color'));
			dom.setSelect('align', this.getAttrib(el, 'align'));
			// Background Color
			dom.value('bgcolor', t.getAttrib(el, 'background-color'));

			tinymce.each(el.childNodes, function(c){
				if(c.nodeName == 'DIV'){
					dom.value('text_align', ed.dom.getStyle(c, 'text-align'));	
					// Padding
					tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
						dom.value('text_padding_' + o, t.getAttrib(c, 'padding-' + o));														  
					});
					// Margin
					tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
						dom.value('text_margin_' + o, t.getAttrib(c, 'margin-' + o));														  
					});
					dom.value('text_color', t.getAttrib(c, 'color'));
					dom.value('text_bgcolor', t.getAttrib(c, 'background-color'));
					dom.value('text', c.innerHTML || '');	
				}
			});
			// Class
			var cls = ed.dom.getAttrib(el, 'class');
			cls = tinymce.trim(cls.replace(new RegExp("(^|\\s+)jce_caption(\\s+|$)", "g"), ' '));
			dom.value('classes', cls);
			dom.value('classlist', cls);
		}else{
			// Image Margin
			tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
				dom.value('margin_' + o, t.getAttrib(im, 'margin-' + o));
			});	
			// Image Align
			dom.value('align', this.getAttrib(im, 'align'));
			// Image title
			dom.value('text', ed.dom.getAttrib(im, 'title') || ed.dom.getAttrib(im, 'alt') || tinyMCEPopup.getLang('caption_dlg.text', 'Caption Text'));
		}
		// Update / Build color pickers
		tinymce.each(['border_color', 'text_color', 'text_bgcolor', 'bgcolor'], function(k){
			dom.html(k + '_pickcontainer', TinyMCE_Utils.getColorPickerHTML(k));
			TinyMCE_Utils.updateColor(k);					  
		});
		
		// Setup border
		this.setBorder();
		// Setup margins/padding/text padding
		tinymce.each(['margin', 'padding', 'text_padding', 'text_margin'], function(k){
			t.setSpacing(k, true);
		});
		
		this.updateText();
		this.updateCaption();
		
		TinyMCE_EditableSelects.init();
	},
	insert : function(){
		var ed = tinyMCEPopup.editor, el, s = ed.selection, n = s.getNode(), im = n, c, iw, txt;
		
		n = ed.dom.getParent(n, "A") || n;
		
		if(n.nodeName == 'A'){
			im = n.firstChild;
		}
		
		el = ed.dom.getParent(n, "DIV");
		iw = ed.dom.getStyle(im, 'width') || ed.dom.getAttrib(im, 'width');
						
		var ce = {
			style : ed.dom.serializeStyle(ed.dom.parseStyle(dom.get('caption').style.cssText)),
			'class' : dom.value('classes')
		};		
		var ct = {
			style : ed.dom.serializeStyle(ed.dom.parseStyle(dom.get('caption_text').style.cssText)),
			'class' : dom.value('text_classes')
		};
		
	
		tinyMCEPopup.execCommand("mceBeginUndoLevel");
		// Remove image margins
		tinymce.each(['top', 'right', 'bottom', 'left'], function(e){
			ed.dom.setStyle(im, 'margin-' + e, '');														  
		});

		txt = dom.value('text');

		// Update
		if(el && /jce_caption/i.test(el.className)){
			ed.dom.setAttribs(el, ce);
			
			c = ed.dom.select('div', el);
			
			if(c == ''){
				if(txt){
					ed.dom.insertAfter(ed.dom.create('div', ct, txt), n);
					ed.dom.setStyle(c, 'clear', 'both');
				}
			}else{
				if(txt){
					ed.dom.setAttribs(c, ct);
					ed.dom.setStyle(c, 'clear', 'both');
					ed.dom.setHTML(c, txt);
				}else{
					ed.dom.remove(c);
				}
			}
		// Create new
		}else{
			el = ed.dom.create('DIV', ce);
			el.appendChild(n.cloneNode(true));
			n.parentNode.replaceChild(el, n);
			
			if(txt){
				c = ed.dom.create('div', ct, txt);
				ed.dom.setStyle(c, 'clear', 'both');
				el.appendChild(c);
			}
		}
		
		ed.dom.addClass(el, 'jce_caption');
		ed.dom.setStyles(el, {'display': 'inline-block', 'width': iw});
		
		tinyMCEPopup.execCommand("mceEndUndoLevel");
		tinyMCEPopup.close();	
	},
	updateText : function(v){
		if(!v) v = dom.value('text');
		dom.html('caption_text', v);	
	},
	updateCaption : function() {
		var ed = tinyMCEPopup, st, v, br, c = dom.get('caption'), ct = dom.get('caption_text');
		ed.dom.setAttrib(img, 'style', dom.value('style'));
		
		if(dom.value('text')){
			// Text align
			ed.dom.setStyle(ct, 'text-align', dom.value('text_align'));
			// Text Padding
			tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
				v = dom.value('text_padding_' + o);
				ed.dom.setStyle(ct, 'padding-' + o,  /[^a-z]/i.test(v) ? v + 'px' : v);
			});
			// Text Margin
			tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
				v = dom.value('text_margin_' + o);
				ed.dom.setStyle(ct, 'margin-' + o,  /[^a-z]/i.test(v) ? v + 'px' : v);
			});
			// Text Color
			if(tc = dom.value('text_color'))
				ed.dom.setStyle(ct, 'color', tc);
				
			// Text box background color
			if(tc = dom.value('text_bgcolor'))
				ed.dom.setStyle(ct, 'background-color', tc);
		}else{
			ed.dom.setAttrib(ct, 'style', 'clear: both;');
		}
			
		// Box background color
		ed.dom.setStyle(c, 'background-color', dom.value('bgcolor'));
			
		// Handle align
		ed.dom.setStyle(c, 'float', '');
		ed.dom.setStyle(c, 'vertical-align', '');

		v = dom.value('align');
		k = /(left|right)/.test(v) ? 'float' : 'vertical-align';
		ed.dom.setStyle(c, k, v);
		
		// Handle border	
		tinymce.each(['width', 'color', 'style'], function(o){
			if(dom.ischecked('border')){
				v = dom.value('border_' + o);
			}else{
				v = '';	
			}
			ed.dom.setStyle(c, 'border-' + o, v);
		});
		// Margin
		tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
			v = dom.value('margin_' + o);
			ed.dom.setStyle(c, 'margin-' + o,  /[^a-z]/i.test(v) ? v + 'px' : v);
		});
		// Padding
		tinymce.each(['top', 'right', 'bottom', 'left'], function(o){
			v = dom.value('padding_' + o);
			ed.dom.setStyle(c, 'padding-' + o,  /[^a-z]/i.test(v) ? v + 'px' : v);
		});
	},
	getAttrib : function(e, at) {
		var ed = tinyMCEPopup.editor, v, v2;
		switch (at) {
			case 'width':
			case 'height':
				return ed.dom.getAttrib(e, at) || ed.dom.getStyle(e, at) || '';
				break;	
			case 'align':
				if(v = ed.dom.getAttrib(e, 'align')){
					return v;	
				}
				if(v = ed.dom.getAttrib(e, 'text-align')){
					return v;	
				}
				if(v = ed.dom.getStyle(e, 'float')){
					return v;
				}
				if(v = ed.dom.getStyle(e, 'vertical-align')){
					return v;
				}
				break;
			case 'margin-top':
			case 'margin-bottom':
			case 'padding-top':
			case 'padding-bottom':
				if(v = ed.dom.getStyle(e, at)){
					return parseInt(v.replace(/[^0-9]/g, ''));
				}
				if(v = ed.dom.getAttrib(e, 'vspace')){
					return parseInt(v.replace(/[^0-9]/g, ''));
				}
				break;
			case 'margin-left':
			case 'margin-right':
			case 'padding-left':
			case 'padding-right':
				if(v = ed.dom.getStyle(e, at)){
					return parseInt(v.replace(/[^0-9]/g, ''));
				}
				if(v = ed.dom.getAttrib(e, 'hspace')){
					return parseInt(v.replace(/[^0-9]/g, ''));
				}
				break;
			case 'border-width':
			case 'border-style':
				v = '';
				tinymce.each(['top', 'right', 'bottom', 'left'], function(n) {
					s = at.replace(/-/, '-' + n + '-');
					sv = ed.dom.getStyle(e, s);
					// False or not the same as prev
					if(sv !== '' || (sv != v && v !== '')){
						v = '';
					}
					if (sv){
						v = sv;
					}
				});
				if(at == 'border-width' && v !== ''){
					dom.check('border', true);
					return parseInt(v.replace(/[^0-9]/g, ''));
				}
				return v;
				break;
			case 'color':
			case 'border-color':
			case 'background-color':
				v = ed.dom.getStyle(e, at);
				return string.toHex(v);			
				break;
		}
	},
	setSpacing : function(k, init){
		var x = false;
		if(init){
			tinymce.each(['right', 'bottom', 'left'], function(e){
				x = (dom.value(k + '_' + e) == dom.value(k + '_top'));
				dom.disable(k + '_' + e, x);
			});
			dom.check(k + '_check', x);
		}else{
			x = dom.ischecked(k + '_check');		
			tinymce.each(['right', 'bottom', 'left'], function(e){
				if(x){
					dom.value(k + '_' + e, dom.value(k + '_top'));
				}
				dom.disable(k + '_' + e, x);
			});
			this.updateCaption();
		}
	},
	setBorder : function(){
		tinymce.each(['width', 'style', 'color'], function(v){
			dom.disable('border_' + v, dom.ischecked('border') == false);												   
		});
		this.updateCaption();
	},
	setClasses : function(o, v){
		//return Editor.utilities.setClasses(v);
		var c = Editor.dom.value(o).split(' ');
		if(tinymce.inArray(c, v) == -1){
			c.push(v);	
		}
		Editor.dom.value(o, tinymce.trim(c.join(' ')));
	},
	openHelp : function(){
		this.caption.openHelp();	
	}
}
tinyMCEPopup.onInit.add(CaptionDialog.init, CaptionDialog);