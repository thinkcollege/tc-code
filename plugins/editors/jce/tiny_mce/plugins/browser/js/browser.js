var BrowserDialog = {
	preInit : function() {
		tinyMCEPopup.requireLangPack();
	},
	init : function(ed) {
		var action = "insert";
		tinyMCEPopup.resizeToInnerSize();
		
		var win 	= tinyMCEPopup.getWindowArg("window");

        dom.disable('insert', true);
		var src = tinyMCEPopup.getWindowArg("url");
		if(src){
			src = tinyMCEPopup.editor.documentBaseURI.toRelative(src);
			action = "update";
			dom.disable('insert', false);
		}
		dom.value('insert', tinyMCEPopup.getLang('lang_' + action, 'Insert', true));
		
		if(/(:\/\/|www|index.php(.*)\?option)/gi.test(src)){
			src = '';	
		}
		this.browser = initManager(src);
		dom.value('src', src);
	},
	insert : function(){
		var win = tinyMCEPopup.getWindowArg("window");

        // insert information now
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = dom.value('src');

        // close popup window
        tinyMCEPopup.close();
	}
};
var Browser = Manager.extend({
	otherOptions : function(){
		return {
			onFileClick : function(file){
				this.selectFile(file);
			},
			onFileInsert : function(file){
				this.selectFile(file);	
			}.bind(this)
		};
	},
	initialize : function(src, options){
		this.setOptions(this.otherOptions(), options);
		this.parent('browser', src, '', this.options);
	},
	selectFile : function(title){
		var name 	= string.basename(title);
		var src 	= string.path(this.getParam('base'), string.path(this.getDir(), name));	
		src			= src.charAt(0) == '/' ? src.substring(1) : src;
			
		dom.value('src', src);
		dom.disable('insert', false);
	}
});
Browser.implement(new Events, new Options);
BrowserDialog.preInit();
tinyMCEPopup.onInit.add(BrowserDialog.init, BrowserDialog);