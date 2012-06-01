var JContentEditor = {
	options	: {
		pluginmode	: false,
		state 		: 'mceEditor',
		toggleText 	: '[show/hide]',
		allowToggle	: false
	},
	set : function(o){
		tinymce.extend(this.options, o);
	},
	cleanup : function(type, content){
		switch(type){
			case 'insert_to_editor':
				// Geshi
				content = content.replace(/<pre xml:\s*(.*?)>(.*?)<\/pre>/g, '<pre class="geshi-$1">$2</pre>');
				// Noscript
				content = content.replace(/<noscript>([\w\W\n\r\t]*)<\/noscript>/, '<mce:noscript>$1</mce:noscript>');
				break;
			case 'get_from_editor':
				// Geshi
				content = content.replace(/<pre class="geshi-(.*?)">(.*?)<\/pre>/g, '<pre xml:$1">$2</pre>');						
				// Correct b and i tags
				if(!tinymce.EditorManager.activeEditor.getParam('verify_html')){
					content = content.replace(/<(\/?)b\b>|<b\b( [^>]+)>/gi, '<$1strong$2>');
					content = content.replace(/<(\/?)i\b>|<i\b( [^>]+)>/gi, '<$1em$2>');
				}
				// Remove empty jceutilities anchors
				content = content.replace(/<a([^>]*)class="jce(box|popup|lightbox|tooltip|_tooltip)"([^>]*)><\/a>/gi, '');
				// Remove span elements with jceutilities classes
				content = content.replace(/<span class="jce(box|popup|lightbox|tooltip|_tooltip)">(.*?)<\/span>/gi, '$2');
				
				//mce stuff
				content = content.replace(/mce_(src|href|style|coords|shape)="([^"]+)"\s*/gi, '');
				
				break;
			case 'insert_to_editor_dom':
				break;
			case 'get_from_editor_dom':
				break;
		}
		return content;
	},
	save : function(content){
		if(this.options.pluginmode){
			content = content.replace(/&#39;/gi, "'");
			content = content.replace(/&apos;/gi, "'");
			content = content.replace(/&amp;/gi, "&");
			content = content.replace(/&quot;/gi, '"');
		}
		return content;
	},
	browser : function(field_name, url, type, win){	
		var ed = tinymce.EditorManager.activeEditor;
		ed.windowManager.open({
			file : ed.getParam('site_url') + 'index.php?option=com_jce&task=plugin&plugin=browser&file=browser&type=' + type,
			width : 750,
			height : 420,
			resizable : "yes",
        	inline : "yes",
        	close_previous : "no"
    	}, {
        	window : win,
        	input : field_name,
			url: url,
			type: type
    	});
		return false;
	},
	initEditorMode : function(id){
		var d = document;
		
		if(this.options.allowToggle){			
			d.getElementById('jce_editor_' + id + '_toggle').innerHTML = '<a href="javascript:JContentEditor.toggleEditor(\''+ id +'\');">'+ this.options.toggleText +'</a>';
		}
		d.getElementById(id).className = tinymce.util.Cookie.get('jce_editor_' + id + '_state') || this.options.state;
	},
	toggleEditor : function(id) {
		if(!tinyMCE.get(id)){
			tinyMCE.execCommand('mceAddControl', false, id);
			tinymce.util.Cookie.set('jce_editor_' + id + '_state', 'mceEditor');
			document.getElementById(id).className = 'mceEditor';
		}else{
			tinyMCE.execCommand('mceRemoveControl', false, id);
			tinymce.util.Cookie.set('jce_editor_' + id + '_state', 'mceNoEditor');
			document.getElementById(id).className = 'mceNoEditor';
		}
	},
	getContent : function(id){
		var d = document;
		if(tinyMCE.getInstanceById(id)){
			return tinyMCE.activeEditor.getContent();
		}
		return d.getElementById(id).innerHTML || d.getElementById(id).value || '';
	}/*,
	triggerSave : function(){		
		var o = {};
		tinymce.each(tinyMCE.editors, function(e) {
			o.joomla_save = true;
			return e.save(o);
		});
		//tinyMCE.triggerSave();
	}*/
};