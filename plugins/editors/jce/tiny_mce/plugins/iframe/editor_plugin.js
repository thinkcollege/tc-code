/**
 * $Id: editor_plugin_src.js 763 2008-04-03 13:25:45Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	var each = tinymce.each;

	tinymce.create('tinymce.plugins.IFramePlugin', {
		init : function(ed, url) {
			var t = this;
			
			t.editor = ed;
			t.url = url;

			function isIFrame(n) {
				return /mceItemIFrame/.test(n.className);
			};

			// Register commands
			ed.addCommand('mceIFrame', function() {
				ed.windowManager.open({
					file : ed.getParam('site_url') + 'index.php?option=com_jce&task=plugin&plugin=iframe&file=iframe',
					width : 700 + parseInt(ed.getLang('iframe.delta_width', 0)),
					height : 320 + parseInt(ed.getLang('iframe.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('iframe', {title : 'iframe.desc', cmd : 'mceIFrame', image : url + '/img/iframe.gif'});

			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('iframe', n.nodeName == 'IMG' && isIFrame(n));
			});

			ed.onInit.add(function() {
				ed.dom.loadCSS(url + "/css/content.css");
			});

			ed.onSetContent.add(function() {
				var dom = ed.dom, p = ed.getBody();
				each(dom.select('IFRAME', p), function(n) {
					dom.replace(t._createImg(n), n);
				});
			});

			ed.onPreProcess.add(function(ed, o) {
				var dom = ed.dom;				
				if (o.get) {
					each(dom.select('IMG', o.node), function(n) {
						if(isIFrame(n)){
							dom.replace(t._buildIframe(n), n);
						}
					});
				}
			});
			
			ed.onPostProcess.add(function(ed, o) {
				// This is for IE
				o.content = o.content.replace(/_name=/g, 'name=');
			});
		},

		getInfo : function() {
			return {
				longname : 'IFrame',
				author : 'Moxiecode Systems AB / Ryan Demmer',
				authorurl : 'http://www.joomlacontenteditor.net',
				infourl : 'http://www.joomlacontenteditor.net',
				version : '1.5.0'
			};
		},
		
		// Private methods

		_buildIframe : function(n) {
			var ed = this.editor, dom = ed.dom, p = this._parse(n.title), iframe;

			p.width 	= dom.getStyle(n, 'width') || dom.getAttrib(n, 'width') || 100;
			p.height 	= dom.getStyle(n, 'height') || dom.getAttrib(n, 'height') || 100;
			
			if (p.src){
				p.src = ed.convertURL(p.src, 'src', n);
			}				
			if(p.name){
				p['_name'] = p.name.replace(/[^a-z0-9_:\-\.]/gi, '');
				delete p.name;
			}
			
			// Setup base parameters
			each(['id', 'class', 'style'], function(na) {
				var v = dom.getAttrib(n, na);				
				if (v)
					p[na] = v;
			});			
			iframe = dom.create('iframe', p);
			dom.removeClass(iframe, 'mceItemIFrame');
			if(p.width && p.height){
				dom.setStyles(iframe, {width: '', height: ''});
			}			
			return iframe;
		},

		_createImg : function(n) {
			var img, dom = this.editor.dom, pa = {}, w, h, t = this;

			w = dom.getStyle(n, 'width') || dom.getAttrib(n, 'width') || 100;
			h = dom.getStyle(n, 'height') || dom.getAttrib(n, 'height') || 100;
			
			img = dom.create('img', {
				src		: t.url + '/img/trans.gif',
				'class' : dom.getAttrib(n, 'class'),
				id		: dom.getAttrib(n, 'id'),
				style	: dom.getAttrib(n, 'style')
			});
			
			dom.addClass(img, 'mceItemIFrame');
			dom.setStyles(img, {width: w, height: h});

			// Setup base parameters
			each(['frameborder', 'src', 'marginheight', 'marginwidth', 'scrolling', 'title', 'name', 'longdesc'], function(na) {
				var v = dom.getAttrib(n, na);
				
				v = t._encode(v);
				
				if(na == 'name')
					v = v.replace(/[^a-z0-9_:\-\.]/gi, '');	
				
				if (v)
					pa[na] = v;
			});
			img.title = this._serialize(pa);

			return img;
		},
		
		_encode : function(s){
			s = s.replace(new RegExp('\\\\', 'g'), '\\\\');
			s = s.replace(new RegExp('"', 'g'), '\\"');
			s = s.replace(new RegExp("'", 'g'), "\\'");
	
			return s;
		},

		_parse : function(s) {
			return tinymce.util.JSON.parse('{' + s + '}');
		},

		_serialize : function(o) {
			return tinymce.util.JSON.serialize(o).replace(/[{}]/g, '').replace(/"/g, "'");
		}
	});

	// Register plugin
	tinymce.PluginManager.add('iframe', tinymce.plugins.IFramePlugin);
})();