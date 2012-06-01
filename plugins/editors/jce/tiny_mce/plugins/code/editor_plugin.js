(function() {
	var each = tinymce.each;

	tinymce.create('tinymce.plugins.CodePlugin', {
		init : function(ed, url) {
			var t = this;
			
			t.editor = ed;
			t.url = url;
			
			ed.onInit.add(function() {
				ed.dom.loadCSS(url + "/css/content.css");
			});
			
			ed.onBeforeSetContent.add(function(ed, o) {							   
				// Convert syntax
				if(/{php}/.test(o.content)){
					o.content = o.content.replace(/{php}([\s\S]*?){\/php}/g, '<?php$1?>');
				}
				// test for PHP
				if(/<\?(php)?|<script|<style/.test(o.content)){
					// Remove javascript if not enabled
					if(!ed.getParam('code_javascript')){
						o.content = o.content.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, '');
					}
					// Remove style if not enabled
					if(!ed.getParam('code_css')){
						o.content = o.content.replace(/<style[^>]*>([\s\S]*?)<\/style>/gi, '');
					}
					// Remove PHP if not enabled
					if(!ed.getParam('code_php')){
						o.content = o.content.replace(/<\?(php)?([\s\S]*?)\?>/gi, '');
					}

					// PHP code within an attribute
					o.content = o.content.replace(/"([^"]+)"/g, function(a, b){
						if(/<\?(php)?/.test(b)){
							b = ed.dom.encode(b);
						}
						return '"'+ b +'"';
					});
					// PHP code within a textarea
					if(/<textarea/.test(o.content)){
						o.content = o.content.replace(/<textarea([^>]*)>([\s\S]*?)<\/textarea>/g, function(a, b, c){
							if(/<\?(php)?/.test(c)){
								c = ed.dom.encode(c);
							}
							return '<textarea' + b + '>' + c + '</textarea>';															 
						});
					}
					// Private internal function
					function _trim(s) {
						// Remove prefix and suffix code for element
						s = s.replace(/(<!--\[CDATA\[|\]\]-->)/g, '\n');
						s = s.replace(/^[\r\n]*|[\r\n]*$/g, '');
						s = s.replace(/^\s*(\/\/\s*<!--|\/\/\s*<!\[CDATA\[|<!--|<!\[CDATA\[)[\r\n]*/g, '');
						s = s.replace(/\s*(\/\/\s*\]\]>|\/\/\s*-->|\]\]>|-->|\]\]-->)\s*$/g, '');

						return s;
					};
					// Preserve script elements
					o.content = o.content.replace(/<(script|style)([^>]+|)>([\s\S]*?)<\/(script|style)>/g, function(v, a, b, c) {
						a = a.toUpperCase();
						// Remove prefix and suffix code for script element
						c = _trim(c);
						
						c = c.replace(/<\?(php)?/g, '<span class="mcePHP">');
						c = c.replace(/\?>/g, '</span>');
						
						b = b.replace(/(language="[a-z]+")/g, '').replace(/\b(src|type|defer|charset|media)/gi, '_$1');	

						// Output fake element
						return '<span '+b+'class="mce'+ a +'"><!--'+ a + c + a +'--></span>';
					});
					// PHP code within an element
					o.content = o.content.replace(/<([^>]+)<\?(php)?(.+)\?>([^>]*)>/g, '<$1mce:php="$3"$4>');
					// PHP code other				
					o.content = o.content.replace(/<\?(php)?([\s\S]*?)\?>/g, '<span class="mcePHP"><!--PHP$2PHP--></span>');
					//o.content = o.content.replace(/\?>/g, 'PHP--></span>');
				}
			});
			
			ed.onPostProcess.add(function(ed, o) {
				if (o.get){
					// Test for converted php/css/javascript
					if(/mce(PHP|SCRIPT|STYLE)/.test(o.content) || /&lt;\?(php)?/.test(o.content)){
						o.content = o.content.replace(/"(.*?)&lt;\?(php)?([^"]+)\?&gt;(.*?)"/g, function(a, b, c, d, e){
							return '"' + b + '<?php' + t._decode(d) + '?>' + e + '"';
						});	
						o.content = o.content.replace(/<textarea([^>]*)>([\s\S]*?)<\/textarea>/g, function(a, b, c){
							if(/&lt;\?php/.test(c)){
								c = t._decode(c);	
							}
							return '<textarea' + b + '>' + c + '</textarea>';
						});
						o.content = o.content.replace(/mce:php="([^"]+)"/g, function(a, b){
							return '<?php' + t._decode(b) + '?>';
						});
						o.content = o.content.replace(/<span class="mcePHP">(<!--PHP)?([\s\S]*?)(PHP-->)?<\/span>/g, function(a, b, c, d){
							return '<?php' + t._decode(c) + '?>';																				   
						});
						o.content = o.content.replace(/<span([^>]*)class="mce(SCRIPT|STYLE)"><!--(SCRIPT|STYLE)([\s\S]*?)(SCRIPT|STYLE)--><\/span>/g, function(a, b, c, d, e){
							c = c.toLowerCase();
							b = b.replace(/_(src|type|defer|charset|media)/gi, '$1').replace(/\s$/, '');
							
							// Force type attribute
							if(!/(type)/gi.test(b)){
								b += (c == 'script') ? ' type="text/javascript"' : ' type="text/css"';	
							}
							
							e = t._decode(e).replace(/\s$/g, '');
							e = (c == 'script') ? '<!--\n' + e + '\n// -->' : '<!--\n' + e + '\n-->';
							
							return '<' + c + b + '>'+ e +'</' + c + '>';																				   
						});
					}
				}
			});
		},
		
		_decode : function(s){
			return s.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&').replace(/&quot;/g, '"');	
		},
		
		_encode : function(s){
			return s.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/&/g, '&amp;').replace(/"/g, '&quot;');	
		},

		getInfo : function() {
			return {
				longname : 'Code',
				author : 'Ryan Demmer',
				authorurl : 'http://www.joomlacontenteditor.net',
				infourl : 'http://www.joomlacontenteditor.net',
				version : '1.5.0'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('code', tinymce.plugins.CodePlugin);
})();