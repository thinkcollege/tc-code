/**
 * $Id: editor_plugin_src.js 615 2008-02-20 23:18:01Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */
 
/**
 * Slightly modified to act as object replacement plugin, removing dialog and code insert.
 * Ryan Demmer 04/03/2008
 */
(function() {
	var each = tinymce.each;

	tinymce.create('tinymce.plugins.MediaPlugin', {
		init : function(ed, url) {
			var t = this;
			
			t.editor = ed;
			t.url = url;

			function isObjectElm(n) {
				return /(mceItemFlash|mceItemShockWave|mceItemWindowsMedia|mceItemQuickTime|mceItemRealMedia|mceItemGeneric)/.test(n.className);
			};

			ed.onPreInit.add(function() {
				// Force in _value parameter this extra parameter is required for older Opera versions
				ed.serializer.addRules('param[name|value|_value]');
			});
			
			// -Removed command and button registration and nodechange function 

			ed.onInit.add(function() {
				var lo = {
					mceItemFlash : 'flash',
					mceItemShockWave : 'shockwave',
					mceItemWindowsMedia : 'windowsmedia',
					mceItemQuickTime : 'quicktime',
					mceItemRealMedia : 'realmedia',
					// Added DivX
					mceItemDivX : 'divx'
				};

				if (ed.settings.content_css !== false)
					ed.dom.loadCSS(url + "/css/content.css");

				if (ed.theme.onResolveName) {
					ed.theme.onResolveName.add(function(th, o) {
						if (o.name == 'img') {
							each(lo, function(v, k) {
								if (ed.dom.hasClass(o.node, k)) {
									o.name = v;
									o.title = ed.dom.getAttrib(o.node, 'title');
									return false;
								}
							});
						}
					});
				}
			});
			// -Removed ContextMenu addition
			
			ed.onBeforeSetContent.add(function(ed, o) {
				var h = o.content, w, h;
				// +Added DivX
				h = h.replace(/<script[^>]*>\s*write(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia|DivX)\(\{([^\)]*)\}\);\s*<\/script>/gi, function(a, b, c) {
					var o = t._parse(c);

					return '<img class="mceItem' + b + '" title="' + ed.dom.encode(c) + '" width="' + w + '" height="' + h + '" />';
				});

				h = h.replace(/<object([^>]*)>/gi, '<span class="mceItemObject" $1>');
				h = h.replace(/<embed([^>]*)\/?>/gi, '<span class="mceItemEmbed" $1></span>');
				h = h.replace(/<embed([^>]*)>/gi, '<span class="mceItemEmbed" $1>');
				h = h.replace(/<\/(object)([^>]*)>/gi, '</span>');
				h = h.replace(/<\/embed>/gi, '');
				h = h.replace(/<param([^>]*)>/gi, function(a, b) {return '<span ' + b.replace(/value=/gi, '_value=') + ' class="mceItemParam"></span>'});
				h = h.replace(/\/ class=\"mceItemParam\"><\/span>/gi, 'class="mceItemParam"></span>');

				o.content = h;
			});

			ed.onSetContent.add(function() {
				t._spansToImg(ed.getBody());
			});

			ed.onPreProcess.add(function(ed, o) {
				var dom = ed.dom, nodes = [];

				if (o.set) {
					t._spansToImg(o.node);
				}

				if (o.get) {
					each(dom.select('IMG', o.node), function(n) {
						var ci, cb, mt, pp;

						if (ed.getParam('media_use_script')) {
							if (isMediaElm(n))
								n.className = n.className.replace(/mceItem/g, 'mceTemp');

							return;
						}
						cl = n.className.match(/mceItem(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia|DivX|Generic)/);
						if(cl){
							switch (cl[0]) {
								// ^Updated Flash version to latest
								case 'mceItemFlash':
									ci = 'd27cdb6e-ae6d-11cf-96b8-444553540000';
									cb = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=' + ed.getParam('media_version_flash', '9,0,124,0');
									mt = 'application/x-shockwave-flash';
									pp = 'http://www.macromedia.com/go/getflashplayer';
									break;
								// ^Updated Shockwave version to latest
								case 'mceItemShockWave':
									ci = '166b1bca-3f9c-11cf-8075-444553540000';
									cb = 'http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=' + ed.getParam('media_version_shockwave', '11,0,0,458');
									mt = 'application/x-director';
									pp = '';
									break;
								case 'mceItemWindowsMedia':
									ci = '6bf52a52-394a-11d3-b153-00c04f79faa6';
									cb = 'http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=' + ed.getParam('media_version_windowsmedia', '5,1,52,701');
									mt = 'application/x-mplayer2';
									pp = 'http://www.microsoft.com/Windows/MediaPlayer/';
									break;
								case 'mceItemQuickTime':
									ci = '02bf25d5-8c17-4b23-bc80-d3488abddc6b';
									cb = 'http://www.apple.com/qtactivex/qtplugin.cab#version=' + ed.getParam('media_version_quicktime', '6,0,2,0');
									mt = 'video/quicktime';
									pp = 'http://www.apple.com/quicktime/';
									break;
								case 'mceItemRealMedia':
									ci = 'cfcdaa03-8be4-11cf-b84b-0020afbbccfa';
									cb = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=' + ed.getParam('media_version_realplayer', '7,0,0,0');
									mt = 'audio/x-pn-realaudio-plugin';
									pp = 'http://www.macromedia.com/go/getflashplayer';
									break;
								// +Added DivX
								case 'mceItemDivX':
									ci = '67dabfbf-d0ab-41fa-9c46-cc0f21721616';
									cb = 'http://go.divx.com/plugin/DivXBrowserPlugin.cab';
									mt = 'video/divx';
									pp = 'http://go.divx.com/plugin/download/';
									break;
								// +Added PDF
								case 'mceItemPDF':
									ci = 'ca8a9780-280d-11cf-a24d-444553540000';
									cb = '';
									mt = 'application/pdf';
									pp = '';
									break;
							}
							dom.replace(t._buildObj({
								classid : ci,
								codebase : cb,
								type : mt
							}, n), n);
						}
					});
				}
			});

			ed.onPostProcess.add(function(ed, o) {
				o.content = o.content.replace(/_value=/g, 'value=');
			});

			if (ed.getParam('media_use_script')) {
				function getAttr(s, n) {
					n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);

					return n ? ed.dom.decode(n[1]) : '';
				};
				ed.onPostProcess.add(function(ed, o) {
					o.content = o.content.replace(/<img[^>]+>/g, function(im) {
						var cl = getAttr(im, 'class');
						// +Added DivX
						if (/^(mceTempFlash|mceTempShockWave|mceTempWindowsMedia|mceTempQuickTime|mceTempRealMedia|mceTempDivX)$/.test(cl)) {
							at = t._parse(getAttr(im, 'title'));
							at.width = getAttr(im, 'width');
							at.height = getAttr(im, 'height');
							im = '<script type="text/javascript">write' + cl.substring(7) + '({' + t._serialize(at) + '});</script>';
						}

						return im;
					});
				});
			}
		},

		getInfo : function() {
			return {
				longname : 'Media',
				author : 'Moxiecode Systems AB',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/media',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		},
		
		_encode : function(s) {	
			s = s.replace(new RegExp('\\\\', 'g'), '\\\\');
			s = s.replace(new RegExp('"', 'g'), '\\"');
			s = s.replace(new RegExp("'", 'g'), "\\'");
		
			return s;
		},

		// Private methods

		_buildObj : function(o, n) {
			var ob, ed = this.editor, dom = ed.dom, p = this._parse(n.title), strict, generic, embed = true;
			
			strict = ed.getParam('media_strict', true) && o.type == 'application/x-shockwave-flash';
			
			p.width 	= o.width 	= dom.getAttrib(n, 'width') 	|| dom.getStyle(n, 'width') 	|| 100;
			p.height 	= o.height 	= dom.getAttrib(n, 'height') 	|| dom.getStyle(n, 'height') 	|| 100;
			
			// +Added style
			p.style = o.style = dom.getAttrib(n, 'style') || '';
			
			if (p.src)
				p.src = ed.convertURL(p.src, 'src', n);
				
			args = {
				mce_name 	: 'object',
				width		: o.width,
				height		: o.height,
				style 		: o.style
			};
			
			// Assume generic
			if(!o.type || !o.classid){
				o.type 		= p.type || '';
				o.classid 	= p.classid || '';
				o.codebase 	= p.codebase || '';
				delete p.type;
				delete p.classid;
				delete p.codebase;
				//embed = false;
				generic = true;
			}

			if (strict) {
				tinymce.extend(args, {
					type : 'application/x-shockwave-flash',
					data : p.src
				});
				embed = false;
				delete p.classid;
				delete p.codebase;
			} else {
				tinymce.extend(args, {
					//classid  : o.classid != '' && /clsid/i.test(o.classid) ? o.classid : 'clsid:' + o.classid,
					classid		: o.classid,
					codebase 	: o.codebase
				});
				if(generic){
					tinymce.extend(args, {
						type : o.type
					});	
				}
			}

			ob = dom.create('span', args);
			each (p, function(v, k) {
				// key to lowercase
				k = k.toLowerCase();
				// +Added style
				if (!/^(width|height|style|codebase|classid)$/.test(k)) {
					// Use url instead of src in IE for Windows media
					if (o.type == 'application/x-mplayer2' && k == 'src')
						k = 'url';

					dom.add(ob, 'span', {mce_name : 'param', name : k, '_value' : v});
				}
			});
			
			if (embed){
				dom.add(ob, 'span', tinymce.extend(p, {mce_name : 'embed', type : o.type}));
			}

			return ob;
		},

		_spansToImg : function(p) {
			var t = this, dom = t.editor.dom, ci;
			
			each(dom.select('span.mceItemObject, span.mceItemEmbed', p), function(n) {
				ci = dom.getAttrib(n, "classid") || dom.getAttrib(n, 'type');
				
				if(!ci && n.className == 'mceItemObject') {
					// Find embed
					each(n.childNodes, function(c){
						if(dom.hasClass(c, 'mceItemEmbed'))
							ci = dom.getAttrib(c, 'type');
					});
				}
				if(ci)
					ci = ci.toLowerCase().replace(/\s+/g, '');
				
				switch (ci) {
					case 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000':
					case 'application/x-shockwave-flash':
						dom.replace(t._createImg('mceItemFlash', n), n);
						break;

					case 'clsid:166b1bca-3f9c-11cf-8075-444553540000':
					case 'application/x-director':
						dom.replace(t._createImg('mceItemShockWave', n), n);
						break;

					case 'clsid:6bf52a52-394a-11d3-b153-00c04f79faa6':
					case 'clsid:22d6f312-b0f6-11d0-94ab-0080c74c7e95':
					case 'clsid:05589fa1-c356-11ce-bf01-00aa0055595a':
					case 'application/x-mplayer2':
						dom.replace(t._createImg('mceItemWindowsMedia', n), n);
						break;

					case 'clsid:02bf25d5-8c17-4b23-bc80-d3488abddc6b':
					case 'video/quicktime':
						dom.replace(t._createImg('mceItemQuickTime', n), n);
						break;

					case 'clsid:cfcdaa03-8be4-11cf-b84b-0020afbbccfa':
					case 'audio/x-pn-realaudio-plugin':
						dom.replace(t._createImg('mceItemRealMedia', n), n);
						break;
					// +Added DivX	
					case 'clsid:67dabfbf-d0ab-41fa-9c46-cc0f21721616':
					case 'video/divx':
						dom.replace(t._createImg('mceItemDivX', n), n);
						break;
					// Added PDF
					case 'clsid:ca8a9780-280d-11cf-a24d-444553540000':
					case 'application/pdf':
						dom.replace(t._createImg('mceItemPDF', n), n);
						break;

					default:
						dom.replace(t._createImg('mceItemGeneric', n), n);
						break;
				}
				return;	
			});
		},

		_createImg : function(cl, n) {
			var img, dom = this.editor.dom, pa = {}, t = this;

			w = dom.getAttrib(n, 'width') || 100;
			h = dom.getAttrib(n, 'height') || 100;
			
			// Create image
			img = dom.create('img', {
				src		: this.url + '/img/trans.gif',
				width	: w,
				height	: h,
				'class' : dom.getAttrib(n, 'class'),
				id		: dom.getAttrib(n, 'id'),
				style	: dom.getAttrib(n, 'style')
			});	
			
			dom.removeClass(img, 'mceItemObject');
			dom.removeClass(img, 'mceItemEmbed');
			dom.addClass(img, cl);

			// Setup base parameters
			each(['name', 'bgcolor', 'align', 'flashvars', 'src', 'wmode', 'title'], function(na) {
				var v = dom.getAttrib(n, na);
				if (v){
					pa[na] = t._encode(v);
				}
			});
			
			if(cl == 'mceItemGeneric'){
				each(['type', 'classid', 'codebase'], function(na){														   
					var v = dom.getAttrib(n, na);				
					if (v){
						pa[na] = t._encode(v);
					}
				});
			}

			// Add optional parameters
			each(dom.select('span.mceItemParam', n), function(n) {
				pa[dom.getAttrib(n, 'name')] = dom.getAttrib(n, '_value');
			});

			// Use src not movie
			if (pa.movie) {
				pa.src = pa.movie;
				delete pa.movie;
			}

			img.title = this._serialize(pa);

			return img;
		},

		_parse : function(s) {
			return tinymce.util.JSON.parse('{' + s + '}');
		},

		_serialize : function(o) {
			return tinymce.util.JSON.serialize(o).replace(/[{}]/g, '').replace(/"/g, "'");
		}
	});

	// Register plugin
	tinymce.PluginManager.add('media', tinymce.plugins.MediaPlugin);
})();