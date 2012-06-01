(function() {
	tinymce.PluginManager.requireLangPack('caption');
	tinymce.create('tinymce.plugins.CaptionPlugin', {
		init : function(ed, url) {
			var t = this; t.ed = ed;
						
			function isMceItem(n) {
				return /mceItem/.test(n.className);
			};
			
			function isCaption(n) {
				n = ed.dom.getParent(n, 'DIV');
				return n ? /jce_caption/.test(n.className) : false;
			};
			
			ed.onInit.add(function() {
				ed.dom.loadCSS(url + "/css/content.css");
			});
			
			// Register commands
			ed.addCommand('mceCaption', function() {		
				var se = ed.selection;

				// No selection and not in link
				if (se.isCollapsed() && se.getNode().nodeName != 'IMG')
					return;
					
				if (isMceItem(se.getNode()))
					return;

				ed.windowManager.open({
					file : ed.getParam('site_url') + 'index.php?option=com_jce&tmpl=component&task=plugin&plugin=caption&file=caption',
					width : 475 + ed.getLang('caption.delta_width', 0),
					height : 540 + ed.getLang('caption.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url
				});
			});
			
			// Register commands
			ed.addCommand('mceCaptionDelete', function() {
				var d, c, se = ed.selection, n = se.getNode();

				// No selection and not in link
				if (se.isCollapsed() && n.nodeName != 'IMG')
					return;
					
				if (isMceItem(n))
					return;
				
				d = ed.dom.getParent(n, "DIV");
				if(d && /(jce_caption)/i.test(ed.dom.getAttrib(d, 'class'))){						
					tinymce.each(d.childNodes, function(c){
						if(c && c != 'undefined'){
							if(c.nodeName == 'DIV'){
								ed.dom.remove(c);
							}
							if(c.nodeName == 'IMG'){
								tinymce.each(['Top', 'Bottom'], function(e){
									s = ed.dom.getStyle(d, 'marginTop') || ed.dom.getStyle(d, 'marginBottom') || 0;
									ed.dom.setStyle(c, 'margin' + e, s);								 
								});
								tinymce.each(['Left', 'Right'], function(e){
									s = ed.dom.getStyle(d, 'marginLeft') || ed.dom.getStyle(d, 'marginRight') || 0;
									if(s){
										ed.dom.setStyle(c, 'margin' + e, s);
									}
								});
							}
						}
					});
					tinyMCE.execCommand("mceRemoveNode", false, d);
				}
			});
			// Register buttons
			ed.addButton('caption', {
				title : 'caption.desc',
				cmd : 'mceCaption',
				image : url + '/img/caption.gif'
			});			
			ed.addButton('caption_delete', {
				title : 'caption.delete',
				cmd : 'mceCaptionDelete',
				image : url + '/img/caption_delete.gif'
			});
			
			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setDisabled('caption', ((n.nodeName == 'IMG' && !isMceItem(n)) || isCaption(n)) == false);
				cm.setDisabled('caption_delete', isCaption(n) == false);
				
				cm.setActive('caption_delete', isCaption(n));
				cm.setActive('caption', isCaption(n));
			});
		},

		getInfo : function() {
			return {
				longname : 'Caption',
				author : 'Ryan Demmer',
				authorurl : 'http://www.joomlacontenteditor.net',
				infourl : 'http://www.joomlacontenteditor.net',
				version : '1.5.2'
			};
		}
	});
	// Register plugin
	tinymce.PluginManager.add('caption', tinymce.plugins.CaptionPlugin);
})();