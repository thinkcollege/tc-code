var PageBreakDialog = {
	preInit : function() {
		tinyMCEPopup.requireLangPack();
		tinyMCEPopup.resizeToInnerSize();
	},
	init : function() {
		var d = document, ed = tinyMCEPopup.editor, s = ed.selection, n = s.getNode(), action = 'insert';
		
		if(n.nodeName == 'IMG' && ed.dom.hasClass(n, 'mceItemPageBreak')){
			action = 'update';
			
			d.getElementById('title').value = ed.dom.getAttrib(n, 'title', '');
			d.getElementById('alt').value 	= ed.dom.getAttrib(n, 'alt', '');
		}
		d.getElementById('insert').value = tinyMCEPopup.getLang(action, 'Insert', true); 
	},
	insert : function(){		
		var d = document, ed = tinyMCEPopup.editor, s = ed.selection, n = s.getNode();
		
		var v = {title : d.getElementById('title').value, alt : d.getElementById('alt').value};
		tinyMCEPopup.execCommand('mcePageBreak', false, v);
		tinyMCEPopup.close();
	}
}
PageBreakDialog.preInit();
tinyMCEPopup.onInit.add(PageBreakDialog.init, PageBreakDialog);