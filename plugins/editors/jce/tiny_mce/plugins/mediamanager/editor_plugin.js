/**
 * $Id: editor_plugin_src.js 296 2007-08-21 10:36:35Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	var each = tinymce.each;

	tinymce.create('tinymce.plugins.MediaManagerPlugin', {
		init : function(ed, url) {
			var t = this;
			
			t.editor = ed;
			t.url = url;

			function isMediaElm(n) {
				if(n.nodeName == 'IMG'){
					return /mceItem(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia|DivX)/.test(n.className);
				}
				return false;
			};
			
			function isPopup(n){
				if(n.nodeName == 'A' || t.editor.dom.getParent(n, 'A') != null){
					return /^(jcepopup)$/.test(n.className) && (/(flash|quicktime|director|shockwave|windowsmedia|mplayer|real|realaudio|divx)/.test(n.type) || /(youtube|google|metacafe)/.test(n.href));
				}
				return false;
			};

			// Register commands
			ed.addCommand('mceMediaManager', function() {				
				ed.windowManager.open({
					file : ed.getParam('site_url') + 'index.php?option=com_jce&task=plugin&plugin=mediamanager&file=mediamanager',
					width : 750 + ed.getLang('mediamanager.delta_width', 0),
					height : 640 + ed.getLang('mediamanager.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('mediamanager', {title : 'mediamanager.desc', cmd : 'mceMediaManager', image : url + '/img/mediamanager.gif'});

			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('mediamanager', isMediaElm(n) || isPopup(n));
				if(isPopup(n)){
					ed.selection.select(n);	
				}
			});

			ed.onInit.add(function() {
				if (ed && ed.plugins.contextmenu) {
					ed.plugins.contextmenu.onContextMenu.add(function(th, m, e) {
						//if (isMediaElm(e)) {
							m.add({title : 'mediamanager.desc', icon : 'media', cmd : 'mceMediaManager'});
						//}
					});
				}
			});
		}
	});

	// Register plugin
	tinymce.PluginManager.add('mediamanager', tinymce.plugins.MediaManagerPlugin);
})();