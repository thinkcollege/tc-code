(function() {
	tinymce.PluginManager.requireLangPack('article');	  
	var each = tinymce.each;
	tinymce.create('tinymce.plugins.ArticlePlugin', {
		init : function(ed, url) {		
			var t = this;		
			
			t.editor = ed; 
			t.url = url;
			
			function isPageBreak(n) {
				return n.nodeName == 'IMG' && ed.dom.hasClass(n, 'mceItemPageBreak');
			};
			
			function isReadMore(n) {
				return n.nodeName == 'IMG' && ed.dom.hasClass(n, 'mceItemReadMore');
			};
			
			// Register commands
			ed.addCommand('mceReadMore', function() {
				if(ed.dom.get('system-readmore')){
					alert(ed.getLang('article.readmore_alert', 'There is already a Read More break inserted in this article. Only one such break is permitted. Use a Pagebreak to split the page up further.'))
					return false;
				}
				t._insertBreak('readmore', {id: 'system-readmore'});
			});
			ed.addCommand('mcePageBreak', function(ui, v) {
				if (ui) {
					ed.windowManager.open({
						file : url + '/pagebreak.htm',
						width : 400,
						height : 100,
						inline : 1
					}, {
						plugin_url : url
					});
				} else {
					tinymce.extend(v, {'id': 'system-pagebreak'});
					t._insertBreak('pagebreak', v);
				}
			});
			// Register buttons
			ed.addButton('readmore', {title : 'article.readmore', cmd : 'mceReadMore', image : url + '/img/readmore.gif'});
			ed.addButton('pagebreak', {title : 'article.pagebreak', cmd : 'mcePageBreak', image : url + '/img/pagebreak.gif', ui : true});

			ed.onInit.add(function() {
				ed.dom.loadCSS(url + "/css/content.css");
			});
			
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('readmore',  isReadMore(n));
				cm.setActive('pagebreak', isPageBreak(n));
			});
						
			ed.onSetContent.add(function() {
				var dom = ed.dom, p = ed.getBody();
				each(dom.select('HR', p), function(n) {
					if(ed.dom.getAttrib(n, 'id') == 'system-readmore' || ed.dom.hasClass(n, 'system-pagebreak')){
						dom.replace(t._createImg(n), n);
					}
				});
			});

			ed.onPreProcess.add(function(ed, o) {
				var dom = ed.dom;				
				if (o.get) {
					each(dom.select('IMG', o.node), function(n) {
						if(isReadMore(n) || isPageBreak(n)){
							dom.replace(t._buildHR(n), n);
						}
					});
				}
			});
		},
		_insertBreak : function(s, args){
			var t = this, ed = this.editor, n = ed.selection.getNode(), h, c, re, isBlock = false, desc = '';
			var bElm = 'P,DIV,ADDRESS,PRE,FORM,TABLE,OL,UL,CAPTION,BLOCKQUOTE,CENTER,DL,DIR,FIELDSET,NOSCRIPT,NOFRAMES,MENU,ISINDEX,SAMP';

			n = ed.dom.getParent(n, bElm, 'BODY') || n;
			
			tinymce.extend(args, {src: t.url + '/img/trans.gif', 'class': s == 'pagebreak' ? 'mceItemPageBreak mceItemNoResize' : 'mceItemReadMore mceItemNoResize'});
			
			// Insert initial node
			ed.selection.setContent(ed.dom.createHTML('img', args));
			
			if(ed.dom.isBlock(n)){			
				// Create new img
				r = ed.dom.create('img', args);
				// Put hr outside of these
				if(/^(TABLE|CAPTION|LI|OL|UL|TD|DL|DT|DD)$/.test(n.nodeName)){	
					// Remove old img
					ed.dom.remove('system-' + s);
					// Insert new img
					ed.dom.insertAfter(r, n);
					p = ed.dom.getParent(r, bElm, 'BODY');
					// If in block
					if(p){
						ed.dom.insertAfter(r, p);	
					}
				}else{
					// Split node html
					h = n.innerHTML.split(/<img[\s\S]+id="?system-(readmore|pagebreak)"?[^>]+>/i);					
					// Re-assign innerHTML
					n.innerHTML = h[0];
					
					// Insert second block element and img
					ed.dom.insertAfter(r, n);										
					if(h[2] && h[2] != '<br>'){
						c = n.cloneNode(true);							
						c.innerHTML = h[2];
						ed.dom.insertAfter(c, r);
					}
				}
			}
			ed.dom.setAttrib(ed.dom.get('system-pagebreak'), 'id', '');
		},
		// Private methods

		_buildHR : function(n) {
			var ed = this.editor, dom = ed.dom, args;

			if(dom.hasClass(n, 'mceItemPageBreak')){
				args = {
					title 	: dom.getAttrib(n, 'title', ''),
					alt		: dom.getAttrib(n, 'alt', ''),
					'class'	: 'system-pagebreak'
				};	
			}else{
				args = {
					id	 : 'system-readmore'
				};	
			}
			return dom.create('hr', args);		
		},

		_createImg : function(n) {
			var ed = this.editor, dom = ed.dom, t = this, args;
			
			args = {
				src		: t.url + '/img/trans.gif',
				'class' : 'mceItemReadMore'
			};
			
			if(dom.hasClass(n, 'system-pagebreak')){
				tinymce.extend(args, {'class' : 'mceItemPageBreak', title : dom.getAttrib(n, 'title', ''), alt : dom.getAttrib(n, 'alt', '')});
			}else{
				tinymce.extend(args, {id : 'system-readmore', alt : ed.getLang('article.readmore_title', 'Read More'), title : ed.getLang('article.readmore_title', 'Read More')});	
			}
			return dom.create('img', args);
		},
						
		getInfo : function() {
			return {
				longname : 'Article',
				author : 'Ryan Demmer',
				authorurl : 'http://www.joomlacontenteditor.net',
				infourl : 'http://www.joomlacontenteditor.net',
				version : '1.5.0'
			};
		}
	});
	// Register plugin
	tinymce.PluginManager.add('article', tinymce.plugins.ArticlePlugin);
})();