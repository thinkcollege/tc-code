// Global variables
var FileManager = Manager.extend({
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
		this.parent('filemanager', src, '', this.options);
		this.setSortables();
	},
	setSortables : function(){
		var enabled 	= FileManagerDialog.sortables;
		var sortlist 	= FileManagerDialog.sortlist || {};
		
		$each(sortlist, function(v, k){						
			new Element('li', {
				'class': 'sortItem',
				id: 'li-'+ k
			}).adopt(
				new Element('label', {'class': 'label'}).setHTML(this.getLang('filemanager_dlg.' + k, k))
			).adopt(
				new Element('input', {
					type: (k == 'size' || k == 'date') ? 'text' : 'hidden',
					id: k + '-value',
					value: v
				})
			).adopt(
				new Element('input', {
					'class': 'checkbox',
					type: 'checkbox',
					id: k + '-check',
					events : {
						click : function(e){
							e = new Event(e);
							v = e.target;
							if(v.disabled) return;
							var id = v.id.replace('-check', '');
							if(v.checked && dom.value(id+ '-value') == ''){
								var items = this.getSelectedItems();
								if(/(size|date)/i.test(v.id) && items.length){
									dom.disable('insert', true);
									$('li-' + id).addClass('loading');
									this.xhr('getProperties', string.path(this.getDir(), items[0].title), function(o){																	
										if(!o.error){
											dom.value(id+ '-value', o[id]);
										}
										dom.disable('insert', false);
										$('li-' + id).removeClass('loading');
									});
								}
							}
						}.bind(this)
					}
				})
			).injectInside($('sortGroup'))	
			$(k + '-check').checked = (v !== '' || k == 'text') ? true : false;
		}.bind(this));
				
		['size', 'date'].each(function(n){
			new Element('input', {
				'type': 'button',
				'id': n + '-refresh',
				'title': this.getLang('filemanager_dlg.reload_value', 'Reload Value'),
				events: {
					click: function(){
						var items = this.getSelectedItems();
						if(items.length){
							dom.disable('insert', true);
							$('li-' + n).addClass('loading');
							this.xhr('getProperties', string.path(this.getDir(), items[0].title), function(o){																	
								if(!o.error){
									dom.value(n+ '-value', o[n]);
								}
								dom.disable('insert', false);
								$('li-' + n).removeClass('loading');
							});	
						}
					}.bind(this)
				}
			}).injectBefore($(n + '-check'))
		}.bind(this));
		$('text-check').disabled = true;
		if(enabled){
			$('options-enabled').style.display 	= 'block';
			$('options-disabled').style.display = 'none';
		}else{
			$('options-enabled').style.display 	= 'none';
			$('options-disabled').style.display = 'block';	
		}
		$('options-enabled').style.display = enabled ? 'block' : 'none';
		new Sortables('sortGroup');
	},
	serializeSortables : function(){
		var items = [];
		// Serialize group layout
		$('sortGroup').getChildren().each(function(el){
			items.include(el.id.replace(/li-/gi, ''));	
		});
		return items;
	},
	selectFile : function(title){
		var name 	= string.basename(title);
		var href 	= string.path(this.getParam('base'), string.path(this.getDir(), name));	
		href		= href.charAt(0) == '/' ? href.substring(1) : href;
			
		dom.disable('insert', true);
		dom.value('href', href);
		
		if(dom.value('title') !== ''){
			new Confirm(tinyMCEPopup.getLang('filemanager_dlg.replace_text', 'Replace file link text with file name?'), function(state){
					if(state){
						dom.value('title', name);
					}
				}
			);
		}else{
			dom.value('title', name);
		}
					
		this.xhr('getProperties', title, function(o){
			if(!o.error){
				dom.value('size-value', o.size);
				dom.value('date-value', o.date);
			}
			dom.disable('insert', false);										
		});
	}
});
FileManager.implement(new Events, new Options);

var FileManagerDialog = {
	preInit : function() {
		tinyMCEPopup.requireLangPack();
	},
	init : function() {
		var ed = tinyMCEPopup.editor, s = ed.selection, n = s.getNode(), el, t = this, href = '', action = "insert";
		tinyMCEPopup.resizeToInnerSize();
		
		TinyMCE_Utils.fillClassList('date-class');
		TinyMCE_Utils.fillClassList('size-class');
		
		this.sortables = true;
	
		el = ed.dom.getParent(n, "A");
		if (el != null && el.nodeName == "A" && /jce_file/i.test(ed.dom.getAttrib(el, 'class'))){
			action = "update";
		}
		dom.value('insert', tinyMCEPopup.getLang(action, 'Insert', true));

		this.sortlist 		= {};
		var sa 				= ['icon', 'text', 'size', 'date'];

		if(action == "update") {
			// Get href and convert to relative
			href 		= ed.documentBaseURI.toRelative(ed.dom.getAttrib(el, 'href'));
			var child 	= (el.childNodes);
			tinymce.each(child, function(c, i){
				if(c.nodeName == 'IMG'){
					t.sortlist['icon'] = c.src;
				}
				if(c.nodeName == '#text' && /[\w]+/i.test(c.data)){
					t.sortlist['text'] = tinymce.trim(c.data);	
				}
				if(/jce_(fm_size|size)/i.test(c.className)){
					t.sortlist['size'] = tinymce.trim(c.innerHTML);	
					dom.setSelect('size-class', tinymce.trim(c.className.replace(/jce_(fm_size|size)/i, '')), true);
				}
				if(/jce_(fm_date|date)/i.test(c.className)){
					t.sortlist['date'] = tinymce.trim(c.innerHTML);
					dom.setSelect('date-class', tinymce.trim(c.className.replace(/jce_(fm_date|date)/i, '')), true);
				}
			});	
			tinymce.each(t.sortlist, function(v, k){
				sa.splice(tinymce.inArray(sa, k), 1);
			});
			for(i=0; i<sa.length; i++){
				this.sortlist[sa[i]] = '';	
			}
			dom.value('title', ed.dom.getAttrib(el, 'title'));
			dom.setSelect('targetlist', ed.dom.getAttrib(el, 'target'), true);	
			
			if(ed.dom.getAttrib(el, 'class') == 'jce_file_custom'){
				this.sortables = false;	
			}
		}else{		
			tinymce.extend(t.sortlist, {icon:'',text:'',size:'',date:''});
			var c = s.getContent();
			if(/[<>]+/g.test(c)){
				this.sortables = false;
			}else{
				dom.value('title', c);	
			}
		}
		this.filemanager = initManager(href);
		dom.value('href', href);
		TinyMCE_EditableSelects.init();
	},
	insert : function(){
		var ed = tinyMCEPopup.editor;
		
		AutoValidator.validate(document);
		if(dom.value('href') === ''){
			new Alert(tinyMCEPopup.getLang('filemanager_dlg.no_src', 'Please select a file or enter a file URL'));
			return false;		
		}
		if(dom.value('title') === ''){
			new Alert(tinyMCEPopup.getLang('filemanager_dlg.no_text', 'Text for the file link is required'));
			return false;		
		}
		this.insertAndClose();
	},
	insertAndClose : function(){
		var ed = tinyMCEPopup.editor, n = ed.selection.getNode(), v, el, selection, spans = {}, args = {}, html = '';

		// Fixes crash in Safari
		if (tinymce.isWebKit)
			ed.getWin().focus();
    
		el = ed.dom.getParent(n, "A");
		selection = ed.selection.getContent();

		var href = dom.value('href');
		// Add http
		if(/^\s*www./i.test(href)){
			href = 'http://' + href;	
		}

		var title 		= dom.value('title');
		var target 		= dom.getSelect('targetlist');
		var ext 		= string.getExt(href);
		
		var dateclass 	= dom.getSelect('date-class') + ' ' || '';
		var sizeclass 	= dom.getSelect('size-class') + ' ' || '';
		
		var opt 		= this.filemanager.serializeSortables();
		
		if(this.filemanager.getParam('icon_prefix').charAt(0) == '_'){
			var icon = this.getMappedIcon(ext) + this.filemanager.getParam('icon_prefix') + '.gif';
		}else{
			var icon = this.filemanager.getParam('icon_prefix') + this.getMappedIcon(ext) + '.gif';
		}
		icon = string.path(this.filemanager.getParam('icon_path'), icon);
		if(icon.charAt(0) == '/'){
			icon = icon.substring(1);	
		}
		tinymce.extend(spans, {
			icon : '<img class="jce_icon" src="' + icon + '" title="' + ext + '" style="border:0px;vertical-align:middle;" />',
			date : '<span class="'+ dateclass +'jce_date">' + dom.value('date-value') + '</span>',
			size : '<span class="'+ sizeclass +'jce_size">' + dom.value('size-value') + '</span>',
			text : title
		});
		
		tinymce.extend(args, {
			href 	: href,
			title 	: dom.value('title'),
			target	: dom.getSelect('targetlist'),
			'class' : 'jce_file'
		});	
		
		var h = [];
		tinymce.each(opt, function(v, k){
			if(dom.ischecked(v + '-check')){
				h.push(spans[v]);
			}
		});		
		tinyMCEPopup.execCommand("mceBeginUndoLevel");
		// Update anchor element
		if (el != null){
			// Is a file anchor
			if(ed.dom.hasClass(el, 'jce_file')){
				ed.dom.setAttribs(el, args);
				ed.dom.setHTML(el, tinymce.trim(h.join('&nbsp;')));
			// Is a normal anchor
			}else{
				ed.dom.setAttribs(el, args);	
			}
		}else{
			// Selection exists (text or html)
			if(/[<>]+/g.test(selection)){				
				tinymce.extend(args, {
					'class' : 'jce_file_custom'
				});
				tinyMCEPopup.execCommand("CreateLink", false, "#mce_temp_url#");
				elementArray = tinymce.grep(ed.dom.select("a"), function(n) {return ed.dom.getAttrib(n, 'href') == '#mce_temp_url#';});
				for (i=0; i<elementArray.length; i++) {
					el = elementArray[i];	
					if(el.childNodes.length != 1 || el.firstChild.nodeName != 'IMG') {
						ed.focus();
						ed.selection.select(el);
						ed.selection.collapse(0);
						tinyMCEPopup.storeSelection();
					}
					ed.dom.setAttribs(el, args);
					ed.dom.setHTML(tinymce.trim(el.innerHTML));
				}
			}else{
				ed.execCommand('mceInsertContent', false, '<a id="__mce_tmp" href="javascript:;">' + tinymce.trim(h.join('&nbsp;')) + '</a> ');
				ed.dom.setAttribs('__mce_tmp', args);
				ed.dom.setAttrib('__mce_tmp', 'id', '');
			}
		} 
		tinyMCEPopup.execCommand("mceEndUndoLevel");
		tinyMCEPopup.close();
	},
	getMappedIcon : function(ext){
		var fo, c, el;
		var map = {
			'flash' 		: 	'swf',
			'word'			:	'doc',
			'excel'			:	'xls',
			'text'			:	'txt',
			'image'			:	'jpg',
			'archive'		:	'zip',
			'acrobat'		:	'pdf',
			'winrar'		:	'rar',
			'quicktime'		:	'mov',
			'windowsmedia'	:	'wmv',
			'audio'			:	'wav'
		}		
		fo = this.filemanager.getParam('icon_map').split(';');
		for (i=0; i<fo.length; i++) {
			c = fo[i].split('=');
			el = c[1].split(',');
			for (x=0; x<el.length; x++){
				if (ext == el[x]){
					return map[c[0]] || c[0];
				}
			}
		}
		return 'def';
	}
}
FileManagerDialog.preInit();
tinyMCEPopup.onInit.add(FileManagerDialog.init, FileManagerDialog);