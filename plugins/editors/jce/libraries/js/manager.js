/*
Class: Manager
	Base manager class for creating a JCE Manager object.

Arguments:
	options - optional, an object containing options.

Options:
	interface 		- various interface identifiers.
	filter 			- file extension filter list
	tree 			- use folder tree (requires tree.js)
	onDeleteFiles 	- Delete files callback function.
	onDeleteFolder 	- Delete folder callback function.
	onRename 		- Fodler / file rename callback function.
	onNewFolder 	- New folder callback function.
	onListComplete 	- File / folder list load complete callback function.
	onFileClick 	- File click callback function.
	onFileDetails	- File details callback function.

Example:
	var imgmanager = new Manager('imgmanager', src, args, {params: {'key': 'value'}});
*/
var Manager = Plugin.extend({
	_actions 		: [],
	_buttons 		: {
		'folder'	: [],
		'file'		: []
	},
	// Selected files array
	_selectedItems 	: [],
	_selectedIndex 	: [],
	_activeItem		: 0,	
	// Returned items array
	_returnedItems 	: [],
	// 'Clipboard'
	_pastefiles 	: '',
	_pasteaction	: '',	
	// Options
	moreOptions : function(){
		return {
			// Various dialog containers
			interface: {
				list: 		'dir-list',
				tree: 		'tree-body',
				info: 		'info-text',
				comments:	'info-comment',
				nav: 		'info-nav',
				status: 	'message-status',
				message:	'message-info',
				buttons: 	'buttons',
				actions: 	'actions',
				refresh: 	'refresh',
				search: 	'search',
				sortExt:	'sort-ext',
				sortName: 	'sort-name'
			},
			actions:		null,
			buttons:		null,
			tree:			true,
			upload:			{
				size: 1024,
				types: {},
				conflict: 'all',
				limit: false,
				onLoad: Class.empty
			},
			onInit:			Class.empty,
			onFileInsert:	Class.empty,
			onFileDelete: 	Class.empty,
			onFolderDelete: function(node){
				if(this.treeLoaded){
					// Remove tree node
					this.tree.removeNode(node);
				}
			}.bind(this),
			onFileRename: 	Class.empty,
			onFolderRename:	function(name){
				if(this.treeLoaded){
					// Rename tree node
					this.tree.renameNode(this.serializeSelectedItems(), name);
				}	
			}.bind(this),
			onFolderNew: 	Class.empty,
			onLoadList: 	function(o){
				o.folders.each(function(e){
					$('folder-list').adopt(
						new Element('li').addClass('folder').addClass(e.classes).setProperties({'id': encodeURI(e.id), 'title': e.name}).addEvent('click', function(event){
							this.setSelectedItems(event, false);
						}.bind(this)).adopt(
							new Element('a').setProperty('href', 'javascript:;').setHTML(e.name).addEvent('click', function(){
								this.changeDir(e.id);
							}.bind(this))
						)
					)
				}.bind(this));				
				$('file-list').empty();
				if(o.files.length){
					o.files.each(function(e){
						$('file-list').adopt(
							new Element('li').addClass('file').addClass(string.getExt(e.name)).addClass(e.classes).setProperties({'title': e.name}).addEvent('click', function(event){
								this.setSelectedItems(event, true);
							}.bind(this)).adopt(
								new Element('a').setProperty('href', 'javascript:void(0);').setHTML(e.name).addEvent('click', function(){
									this.fireEvent('onFileClick', [e.name]);
								}.bind(this))
							)
						)
					}.bind(this));
				}else{
					$('file-list').adopt(
						new Element('li').addClass('nofile').setHTML(tinyMCEPopup.getLang('dlg.no_files', 'No files'))
					)	
				}
			},
			onListComplete: Class.empty,
			onFileClick:	Class.empty,
			onFileDetails:	Class.empty
		};
	},
	initialize : function(plugin, src, vars, options){		
		// Set options
		this.setOptions(this.moreOptions(), options);
		this.parent(plugin, this.options);
		// Preload image files
		new Asset.images([this.getImage('libraries.icons.gif'), this.getImage('libraries.ext.gif')]);
		// Load theme language file
		this.loadLanguage();
		/* Setup default values */
		this._vars = vars;
		/* Create Actions and Button */
		this.addActions(this.options.actions);
		this.addButtons(this.options.buttons);
		
		/* Build file and folder lists */
		$(this.options.interface.list).adopt([
			new Element('ul').addClass('item-list').setProperty('id', 'folder-list'),
			new Element('ul').addClass('item-list').setProperty('id', 'file-list')
		])
		/* Info navigation buttons */
		$(this.options.interface.nav + '-left').addEvent('click', function(){
			this._activeItem--;
			if(this._activeItem < 0){
				this._activeItem = 0;	
			}
			this.showFileDetails();
		}.bind(this));
		$(this.options.interface.nav + '-right').addEvent('click', function(){
			this._activeItem++;
			var n = this._selectedItems.length;
			if(this._activeItem > n-1){
				this._activeItem = n-1;	
			}
			this.showFileDetails();
		}.bind(this));

		/* Sortables */
		new ListSorter(this.options.interface.sortExt, 'ext', ['file-list']);
		new ListSorter(this.options.interface.sortName, 'name', ['folder-list', 'file-list']);
		/* Searchables*/
		new Searchables(this.options.interface.search, this.options.interface.list, 'file-list', {
			onFind : function(el){
				if(el.length){
					this.selectNoItems();
					this.selectItems(el, true);	
				}else{				 	
					this.selectNoItems();
				}
			}.bind(this)
		});
		/* Setup refresh button */
		$(this.options.interface.refresh).addEvent('click', function(){
			this.refreshList();
		}.bind(this))
		
		this.setupDir(src);
		this.fireEvent('onInit');
	},
	/**
	 * Setup the current directory.
	 * @param {String} s current directory path.
	 */
	setupDir : function(src){
		var p = '/', f = '', base = this.getParam('base'), n = base.length;
		if(src){
			if(src.substring(0, n) == base){
				p = string.dirname(src).replace(base, '', 'g') || '/';
				f = string.basename(src) || '';
			}
		}else{
			p = Cookie.get("jce_" + this.getPlugin() + '_dir') || '/';
			f = '';
		}
		this._dir = string.path('/', p);
		this.addReturnedItem(f);
		
		if(this.treeLoaded){
			// Initialize tree view
			this.initTree();
		}else{
			// Load folder / file list
			this.getList();	
		}
	},
	treeLoaded : function(){
		return this.options.tree && typeof Tree != 'undefined';
	},
	initTree : function(){
		/* Initialise tree */
		this.setStatus(tinyMCEPopup.getLang('dlg.message_tree', 'Building tree list...'), true);
		this.tree = new Tree(this.options.interface.tree, {
			onInit : function(fn){
				this.xhr('getTree', this._dir, function(o){
					// Set default tree
					$(this.options.interface.tree).setHTML(o);
					// Init callback
					fn.apply();
					// Load folder / file list
					this.getList(this._dir);
				}.bind(this));
			}.bind(this),
			// When a node is clicked
			onNodeClick : function(el, node){
				this.changeDir(node.id);
			}.bind(this),
			// When a node is toggled and loaded
			onNodeLoad : function(node){
				this.tree.toggleLoader(node);
				this.xhr('getTreeItem', encodeURI(node.id), function(o){
					if(o){
						if(!o.error){
							var ul = $E('ul', node);
							if(ul){
								ul.remove();	
							}
							this.tree.createNode(o.folders, node.id);
							this.tree.toggleNodeState(node, true);
						}else{
							alert(o.error);	
						}
					}
					this.tree.toggleLoader(node);
				}.bind(this));
			}.bind(this)
		});
	},
	/**
	 * Reset the manager.
	 */
	resetManager : function(){
		// Clear selects
		this.selectNoItems();
		// Clear returns
		this._returnedItems	= [];
		// Close any dialogs
		this._dialog.each(function(dialog){
			if(typeof dialog.close() != 'undefined'){
				dialog.close();	
			}
		});
	},
	clearPaste : function(){
		// Clear paste 
		this._pasteaction = '';
		this._pastefiles  = '';
		this.hideButton(this.getButton('file', 'paste').element);
	},
	setStatus : function(message, loading){
		$(this.options.interface.status).className = loading ? 'load' : '';
		$(this.options.interface.status).setHTML('<span>' + message + '</span>');
	},
	setMessage : function(message, classname){
		$(this.options.interface.message).className = classname || 'info';
		$(this.options.interface.message).setHTML('<span>' + message + '</span>');
	},
	/**
	 * Sets a loading message
	 */
	setLoader : function(){
		this.setStatus(tinyMCEPopup.getLang('dlg.message_load', 'Loading...'), true);
	},
	/**
	 * Reset the message display
	 */
	resetMessage : function(){
		this.setMessage(tinyMCEPopup.getLang('dlg.file_select', 'Click on a file name to select for insert.'), 'info');
	},
	/**
	 * Reset the status display
	 */
	resetStatus : function(){
		this.setStatus(tinyMCEPopup.getLang('dlg.current_dir', 'Current directory is: ') + this._dir + ' ( ' + this._foldercount + ' ' + tinyMCEPopup.getLang('folders', 'folders') + ', ' + this._filecount + ' ' + tinyMCEPopup.getLang('files', 'files') + ' )');
	},
	/*
	 * Get the parent directory
	 * @return {String} s The parent/previous directory.
	*/
	getPreviousDir : function(){
		if(this._dir.length < 2){
    		return this._dir;
		}
		var dirs = this._dir.split('/');
		var s = '/';
		for(var i = 0; i < dirs.length-1; i++){
			s = string.path(s, dirs[i]);
		}
		return s;
	},
	/*
	 * Add an item to the returnedItems array
	 * @return {String} file The item name.
	*/
	addReturnedItem : function(file){
		this._returnedItems.include(file);
	},
	/*
	* Setup the returned file after upload
	* @param {String} file The returning file name.
	*/
	returnFile : function(file){
		this.addReturnedItem(string.basename(file));
		this.changeDir(string.dirname(file));
	},
	changeDir: function(dir){
		this.resetManager();
		this.setDir(dir);
		this.getList();
	},
	/*
	* Retrieve a list of files and folders
	* @param {String} dir  The directory to load files/folders from.
	* @param {String} args An array of optional arguments.
	*/
	getList : function(){
		this.action = $E('form').action + '&upload-dir=' + this._dir;
		Cookie.set("jce_" + this.getPlugin() + '_dir', this._dir, 1);
		this.setLoader();
		this.hideButtons('folder');
		this.hideButtons('file');
		this.xhr('getItems', [this._dir, this._vars], this.loadList);
	},
	refreshList : function(){
		this.resetManager();
		this.getList();
	},
	setDir: function(dir){
		this._dir = decodeURIComponent(dir);
	},
	/*
	* Get the current directory
	* @return {string} Current directory
	*/
	getDir: function(){
		return this._dir;
	},
	/*
	* Is the current directory the root directory
	* @return {boolean} true/false
	*/
	isRoot : function(){
		return this._dir == '' || this._dir == '/';
	},
	/*
	* Load the file/folder list into the container div
	* @param {Object} The folder/file JSON object
	*/
	loadList: function(o){	
		$('folder-list').empty();
		this._foldercount 	= o.folders.length;
		this._filecount 	= o.files.length;
		if(!this.isRoot()){
			$('folder-list').adopt(
				new Element('li').addClass('folder').addClass('folder-up').setProperties({'title': 'Up'}).adopt(
					new Element('a').setProperty('href', 'javascript:;').setHTML('...')
				).addEvent('click', function(){
					this.changeDir(this.getPreviousDir());
				}.bind(this))
			)
		}
		if(this.options.tree){
			this.tree.createNode(o.folders, this._dir);
		}
		// Alternate loadList function
		this.fireEvent('onLoadList', [o]);
		/*Hover fix for IE*/
		if(window.ie6){
			$ES('li', '.item-list').each(function(e){
				e.addEvent('mouseover', function(){
					e.addClass('hover');
				});
				e.addEvent('mouseout', function(){
					e.removeClass('hover');
				});
			});
		}
		/* Browser specific selection settings */
		$ES('li', this.options.interface.list).each(function(e){
			if(window.gecko){
				e.setStyle('-moz-user-select', 'none');
			}else{
				e.setProperty('unselectable', 'on');
			}
		});
		if(this._returnedItems.length){
			this.selectItemsByName(this._returnedItems);
			this._returnedItems = [];
		}
		if(this._pastefiles !== ''){
			this.showButton(this.getButton('file', 'paste').element);
		}
		this.resetStatus();
		this.resetMessage();
		this.fireEvent('onListComplete');
	},
	/*
	* Execute a command
	* @param {String} The command name
	* @param {String} The command type
	*/
	execCommand : function(name, type){		
		var dir 	= this._dir;
		var list	= this.serializeSelectedItems();
		switch(name){
			case 'help':
				this.openHelp('manager');
				break;
			case 'insert':
				this.fireEvent('onFileInsert', [list]);
				break;
			case 'view':
				var url 		= string.path(string.path(this.getSite(), this.getParam('base')), string.path(this._dir, this._selectedItems[0].title));	
				var name 		= string.basename(this._selectedItems[0].title);
				var viewable 	= this.getParam('viewable') || 'jpeg,jpg,gif,png';
				if(viewable.split(',').contains(string.getExt(name))){
					if(/\.(js|asp|php|vb|vbs|exe|ocx|dll)/i.test(name)) return;
					if(/\.(jpeg|jpg|gif|png|avi|wmv|wm|asf|asx|wmx|wvx|mov|qt|mpg|mp3|mp4|mpeg|swf|flv|xml|dcr|rm|ra|ram|divx)/i.test(name)){
						new mediaPreview(name, url, {
							width: 400,
							height: 400
						});
					}else{
						new iframeDialog(name, url, {
							width: 500,
							frameHeight: 500,
							modal: true
						});
					}
				}
				break;
			case 'upload':
				this._dialog['upload'] = new uploadDialog({
					extended : this.options.upload,
					onOpen : function(){
						// Set hidden dir value to current dir
						$('upload-dir').value = this._dir;
						// Set overwrite options
						var o = {
							'overwrite' : new Option(tinyMCEPopup.getLang('dlg.overwrite', 'Overwrite file'), 0),
							'unique'	: new Option(tinyMCEPopup.getLang('dlg.unique', 'Create unique name'), 1)
						}
						var s = $('upload-overwrite');
						var x = this.options.upload.conflict.split('|');
						
						x.each(function(e){
							s.options[s.options.length] = o[e];				
						});
						o = null;
						// Initialize uploader
						this.uploader = new Uploader('upload-queue', {															 
							url		:	$E('form').getProperty('action'),
							field	:	$E('input[name^=Filedata]'),
							size	:	this.options.upload.size,
							limit	: 	this.options.upload.limit,
							filter	:	this.options.upload.types,
							fileComplete : function(file, name){
								// Set uploaded files
								this.addReturnedItem(string.safe(name));
							}.bind(this),
							uploadComplete : function(){
								$('upload-submit').disabled = false;
								// Reset action
								this.action = $E('form').action;
								// Refresh file list
								this.getList();
								//this.refreshList();
							}.bind(this)
						});
						this.options.upload.onLoad.delay(10);
					}.bind(this),
					onUpload : function(){
						if(this.uploader.uploading) return false;
						this.uploader.upload();
						return false;
					}.bind(this)
				});
				break;
			case 'folder_new':
				this._dialog['folder_new'] = new Prompt(tinyMCEPopup.getLang('dlg.folder_new', 'New Folder'), {
					text: tinyMCEPopup.getLang('dlg.name', 'Name'),
					onConfirm: function(){
						var folder = $('prompt').value;
						if(folder){
							this.setLoader();
							this.xhr('folderNew', [dir, string.safe(folder)], function(o){		  															  
								if(!o.error){
									this.fireEvent('onFolderNew');
									this.refreshList();
									this._dialog['folder_new'].close();
								}else{
									this.raiseError(o.error);
								}
							})
						}
					}.bind(this)
				});
				break;
			// Cut / Copy operation
			case 'copy':
			case 'cut':
				this._pasteaction 	= name;
				this._pastefiles 	= list;
				this.showButton(this.getButton('file', 'paste').element, true);
				break;
			// Paste the file
			case 'paste':
				var fn = (this._pasteaction == 'copy') ? 'fileCopy' : 'fileMove';
				this.setLoader();
				this.xhr(fn, [this._pastefiles, dir], function(o){		  															  
					if(!o.error){
						this.fireEvent('onPaste');
						this.refreshList();
						this.clearPaste();
					}else{
						this.raiseError(o.error);
					}
				}.bind(this))
				break;
			// Delete a file or folder
			case 'delete':
				var msg = tinyMCEPopup.getLang('dlg.delete_folder_alert', 'Delete Folder?');
				var fn 	= 'folderDelete';
				if(type == 'file'){
					msg = tinyMCEPopup.getLang('dlg.delete_file_alert', 'Delete Files(s)?');
					fn 	= 'fileDelete';	
				}
				this._dialog['confirm'] = new Confirm(msg, function(state){
						if(state){
							this.setLoader();
							this.xhr(fn, list, function(o){		  															  
								if(!o.error){
									if(fn == 'folderDelete'){
										this.fireEvent('onFolderDelete', list);
									}else{
										this.fireEvent('onFileDelete');	
									}
									this.refreshList();
								}else{
									this.raiseError(o.error);
								}
							})
						}
					}.bind(this)
				);
				break;
			// Rename a file or folder
			case 'rename':
				var msg = tinyMCEPopup.getLang('dlg.rename_folder', 'Rename Folder');
				var fn 	= 'folderRename';
				var v	= string.basename(list);
				if(type == 'file'){
					msg = tinyMCEPopup.getLang('dlg.rename_file', 'Rename File');
					fn 	= 'fileRename';
					v	= string.basename(string.stripExt(list));
				}
				this._dialog['rename'] = new Prompt(msg, {
					text: tinyMCEPopup.getLang('name', 'Name'),
					value : v,
					onConfirm : function(){
						var name = string.safe($('prompt').value);
						this._dialog['confirm'] = new Confirm(tinyMCEPopup.getLang('dlg.rename_alert', 'Renaming files/folders will break existing links. Continue?'), function(state){
							if(state){
								this.setLoader();
								this.xhr(fn, [list, name], function(o){		  															  
									if(!o.error){
										this.resetManager();
										if(fn == 'renameFolder'){
											this.addReturnedItem(string.path(this._dir, name));
											this.fireEvent('onRenameFolder', string.path(this._dir, name));
										}else{
											this.addReturnedItem(string.path(this._dir, name + '.' + string.getExt(list)));
											this.fireEvent('onRenameFile');	
										}
										this._dialog['rename'].close();
										this.getList();
									}else{
										this.raiseError(o.error);
									}
								});
							}
						}.bind(this));
					}.bind(this)
				});
				break;
		}
	},
	raiseError : function(error){
		this._dialog['alert'] = new Alert(error, {
			onClose: function(){
				this.refreshList();	
			}.bind(this)
		});
	},
	addActions : function(actions){
		$each(actions, function(e){
			this.addAction(e);
		}.bind(this));
	},
	addAction : function(options){
		var name 	= options.name;
		var action	= eval(options.action) || this.execCommand;
		var atn = new Element('div', {'id': name, 'title': options.title}).addClass('action').addClass(name).setStyles({
			'cursor': 'pointer'
		});
		if(options.icon){
			btn.setStyle('background-image', string.path(this.getPluginUrl(), options.icon));	
		}
		if(options.name){
			atn.addEvent('click', function(){
				action.pass([name], this)();														   
			}.bind(this))	
		}
		if(window.ie){
			atn.addEvent('mouseover', function(){
				atn.addClass('hover');   
			}).addEvent('mouseout', function(){
				atn.removeClass('hover');   
			});
		}
		this._actions[name] = atn;
		$(this.options.interface.actions).adopt(atn);
	},
	getAction : function(name){
		return this._actions[name];
	},
	/* Button functions */
	/* addButtons
	** Add _buttons to the _buttons object
	** param btns object
	*/
	addButtons : function(btns){
		$each(btns.folder, function(e){
			this.addButton(e, 'folder');
		}.bind(this));
		$each(btns.file, function(e){
			this.addButton(e, 'file');
		}.bind(this));
	},
	addButton : function(options, type){
		var action	= options.action || this.execCommand;
		var btn = new Element('div').setProperty('title', options.title).addClass('button').addClass(options.name).addClass('hide').setStyles({
			'cursor': 'pointer'
		});
		if(options.icon){
			btn.setStyle('background-image', string.path(this.getPluginUrl(), options.icon));	
		}
		if(options.name){
			var n = options.name;
			btn.addEvent('click', function(){
				if(this._selectedItems){
					eval(action).pass([n, type], this)();
				}
			}.bind(this))	
		}
		if(window.ie){
			btn.addEvent('mouseover', function(){
				btn.addClass('hover');   
			}).addEvent('mouseout', function(){
				btn.removeClass('hover');   
			});
		}
		this._buttons[type].include({'name': options.name, 'element': btn, 'trigger': options.trigger, 'multiple': options.multiple});
		$(this.options.interface.buttons).adopt(btn);
	},
	hideAllButtons : function(){
		$$('div.button').each(function(e){
			this.hideButton(e);   
		}.bind(this));
	},
	hideButtons : function(type){
		this._buttons[type].each(function(e){
			this.hideButton(e.element);
		}.bind(this));
	},
	hideButton : function(button){
		if(button){
			if(button.hasClass('show')){
				button.removeClass('show');
				button.addClass('hide');
			}
		}
	},
	showButtons : function(type){
		this.hideAllButtons();
		this._buttons[type].each(function(e){
			if(!e.trigger){
				this.showButton(e.element, e.multiple);
			}
		}.bind(this));
	},
	showButton : function(button, multiple){
		if(button){
			if(button.hasClass('hide')){
				button.removeClass('hide');
				button.addClass('show');
			}
			if(this._selectedItems.length > 1 && !multiple){
				this.hideButton(button);
			}
		}
	},
	getButton : function(type, name){
		var btn;
		this._buttons[type].each(function(el){
			if(el.name == name){
				btn = el;
			}
		});
		return btn;
	},
	/* Selection functions */
	isSelectedItem : function(el){
		// Quick check
		if($type(el) == 'element'){
			return el.hasClass('selected') && this._selectedItems.contains(el);
		}
		// Check on element or element title/name
		this._selectedItems.each(function(e){
			return e.title == el;
		});
	},
	selectNoItems : function(){
		this._selectedItems.each(function(el){
			if($(el)){
				el.removeClass('selected');
			}
		});
		this._selectedItems = [];
		this._activeItem 	= 0;
		$(this.options.interface.info).empty();
		$(this.options.interface.comments).empty();
		// Shortcut for nav
		var nav = this.options.interface.nav;
		[nav + '-left', nav + '-right', nav + '-text'].each(function(el){
			$(el).setStyle('visibility', 'hidden');											  
		});
		this.hideAllButtons();
	},	
	selectItems : function(items, show){
		this._selectedItems.merge(items).each(function(el){
			if($(el)){
				el.addClass('selected');
			}
		}.bind(this));	
		if(show){
			this.showSelectedItems();
		}
	},
	removeSelectedItems : function(el, show){
		el.each(function(o){
			if(o){
				o.removeClass('selected');
				this._selectedItems.remove(o);	
			}
		}, this);
		if(show){
			this.showSelectedItems();
		}
	},
	/*
	* Return selected items array
	* @param {String} key An optional array key.
	* @return {Array} or {Object} Current file / folder li element selection.
	*/
	getSelectedItems : function(key){
		return this._selectedItems[key] || this._selectedItems;
	},
	/*
	* Process a selection click
	* @param {String} e The click event.
	* @param {Boolean} multiple Allow multiple selections.
	*/
	setSelectedItems : function(e, multiple){
		e = new Event(e);
		// the selected element
		var el = e.target;
			
		// If not li element, must be a so get parent li
		if(el.getTag() != 'li') el = el.getParent();
		
		// if folder selected, remove file selections & select folder
		if(el.hasClass('folder')){
			if(this.isSelectedItem(el)){
				//return this.removeSelectedItems([el], true);	
			}
			this.selectNoItems();
			this.selectItems([el], e.target.getTag() == 'a' ? false : true);
		// selection hit is a file
		}else{
			// Get file items
			var items = $ES('li', 'file-list');
			// Single click
			if(!e.control && !e.shift || !multiple){
				this._selectedIndex = items.indexOf(el);
				if(this.isSelectedItem(el) && this._selectedItems.length == 1){
					//return this.removeSelectedItems([el], true);
				}			
				// deselect all
				this.selectNoItems();
				if(this._selectedItems.length == 0){
					if(this.isSelectedItem(el)){
						this.removeSelectedItems([el], true);
					}else{
						this.selectItems([el], true);
					}
				}
			// ctrl & shift
			}else if(multiple && (e.control || e.shift)){
				// ctrl
				if(e.control){
					this._selectedIndex = items.indexOf(el);
					if(this.isSelectedItem(el)){
						this.removeSelectedItems([el], true);
					}else{
						this.selectItems([el], true);
					}
				}
				// shift
				if(e.shift){
					if(this._selectedItems.length){
						// selected item index
						var si 		= this._selectedIndex;
						// click item index
						var ci		= items.indexOf(el);				
						var selection 	= [];
						
						// Clear selection
						this.selectNoItems();
						// Clicked item further up list than selected item
						if(ci > si){
							for(var i=ci; i>=si; i--){
								selection.include(items[i]);
							}
						}else{
							// Clicked item further down list than selected item
							for(var i=si; i>=ci; i--){
								selection.include(items[i]);
							}	
						}
						this.selectItems(selection, true);
					}else{
						this.selectItems([el], true);	
					}
				}
			}
		}
	},
	/*
	* Show the selected items' details
	*/
	showSelectedItems : function(){
		var n = this._selectedItems.length;
		if(!n){
			this.resetManager();
		}else{
			if(this._selectedItems[0].hasClass('folder')){
				this.showFolderDetails();				
			}else{
				this._activeItem = n-1;
				this.showFileDetails();
			}
		}
	},
	/*
	* Select an item (file) by name
	* @param {String} name The file name.
	*/
	selectItemsByName : function(names, type){
		var items = [];
		if(!type) type = 'file';
		if($type(names) == 'string'){
			names = [names];
		}
		names.each(function(e){
			if(e){
				items.merge($ES('li[title='+ e +']', type + '-list'));
			}
		}.bind(this));
		if(items.length){
			// Set item index for last selection
			this._selectedIndex = $ES('li', type + '-list').indexOf(items[items.length-1]);
			// Scroll to first item in list
			new Fx.Scroll(this.options.interface.list, {
				wait: false,
				duration: 500
			}).toElement(items[0]);
		}
		// Select items and display properties
		this.selectItems(items, true);
	},
	/*
	* Serialize the current item selection, add current dir to path
	*/
	serializeSelectedItems : function(){
		var s = [];
		this._selectedItems.each(function(e){
			s.include(string.path(this._dir, e.title));
		}.bind(this));
		return s.join(',');
	},
	showFileNumber : function(){
		var n = this._selectedItems.length;
		// Shortcut for nav
		var nav = this.options.interface.nav;
		if(this._activeItem){
			$(nav + '-left').setStyle('visibility', 'visible');	
		}else{
			$(nav + '-left').setStyle('visibility', 'hidden');	
		}
		if(this._activeItem + 1 < n){
			$(nav + '-right').setStyle('visibility', 'visible');	
		}else{
			$(nav + '-right').setStyle('visibility', 'hidden');	
		}
		$(nav + '-text').setStyle('visibility', 'visible').setHTML(this._activeItem + 1 + ' of ' + n);
	},
	showFileDetails : function(){
		var n = this._selectedItems.length;
		// Shortcut for nav
		var nav = this.options.interface.nav;
		if(n < 2){
			[nav + '-left', nav + '-right', nav + '-text'].each(function(el){
				$(el).setStyle('visibility', 'hidden');											  
			});
		}
		this.getFileDetails();
		this.fireEvent('onFileDetails');
		
		if(n > 1){
			this.showFileNumber(n);
		}
		this.showButtons('file');
	},
	showFolderDetails : function(){
		var title = string.basename(this._selectedItems[0].title);
		var info = new Element('dl').adopt(
			new Element('dt').setHTML(title)					
		).adopt(
			new Element('dd').setHTML(tinyMCEPopup.getLang('dlg.folder', 'Folder'))
		)		
		$(this.options.interface.info).adopt(info).adopt(
			new Element('div').setProperty('id', 'loader')  
		)
		var comments = [];
		if($(this._selectedItems[0]).hasClass('notwritable')){
			comments.include(
				new Element('dd').addClass('comments').addClass('folder').addClass('notwritable').adopt(
					new Element('span').addClass('hastip').setProperty('title', tinyMCEPopup.getLang('dlg.notwritable_desc', 'Unwritable')).setHTML(tinyMCEPopup.getLang('dlg.notwritable', 'Unwritable'))
				)
			)
		}
		if($(this._selectedItems[0]).hasClass('notsafe')){
			comments.include(
				new Element('dd').addClass('comments').addClass('folder').addClass('notsafe').adopt(
					new Element('span').addClass('hastip').setProperty('title', tinyMCEPopup.getLang('dlg.bad_name_desc', 'Bad file or folder name')).setHTML(tinyMCEPopup.getLang('dlg.bad_name', 'Bad file or folder name'))
				)
			)
		}
		this.xhr('getFolderDetails', string.path(this._dir, this._selectedItems[this._activeItem].title), function(o){
			if($('loader')){
				$('loader').remove();
			}
			var props = [];
			$each(o, function(v, k){
				props.include(
					new Element('dd').setHTML(tinyMCEPopup.getLang('dlg.' + k, k) + ': ' + v)			  
				)					   
			}.bind(this));
			info.adopt(props);
			if(comments.length){
				$(this.options.interface.comments).adopt(
					new Element('dl').adopt(
						new Element('dt').setHTML(tinyMCEPopup.getLang('dlg.comments', 'Comments'))					
					).adopt(comments)			
				)
			}
			this.fireEvent('onFolderDetails', [o]);
		}.bind(this));
		this.showButtons('folder');
	},
	getFileDetails : function(){
		var file 	= this._selectedItems[this._activeItem];
		var title 	= $(file).title;
				
		// Clear the info and comment box
		$(this.options.interface.info).empty();
		$(this.options.interface.comments).empty();
		// Info list
		var info = new Element('dl').adopt([
			new Element('dt').setHTML(string.stripExt(title)), 
			new Element('dd').setHTML(string.getExt(title).toUpperCase() + ' ' + tinyMCEPopup.getLang('dlg.file', 'File')),
			new Element('dd').setProperty('id', 'loader')
		])		
		$(this.options.interface.info).adopt(info);
		
		var properties = [];
		this.xhr('getFileDetails', string.path(this._dir, this._selectedItems[this._activeItem].title), function(o){
			if($('loader')){
				$('loader').remove();
			}
			$each(o, function(v, k){
				// If a button trigger or triggers
				if(o.trigger){
					o.trigger.each(function(t){
						if(t !== ''){
							var b = this.getButton('file', t);
							if(b){
								this.showButton(b.element, b.multiple);	
							}
						}
					}.bind(this));
				}
				if(!/(trigger|preview)/i.test(k)){
					properties.include(
						new Element('dd').setProperty('id', 'info-' + k.toLowerCase()).setHTML(tinyMCEPopup.getLang('dlg.' + k, k) + ': ' + v)			  
					)
				}
			}.bind(this));
			// Show image preview
			if(o.preview){
				properties.include(
					new Element('dt').setHTML(tinyMCEPopup.getLang('dlg.preview', 'Preview') + ': ')
				).include(
					new Element('dd', {
						'id': 'info-preview',
						styles : {
							'height': o.preview.height || 100
						}
					// Create preview image and add loader
					}).addClass('loader')
				);					
			}
			// Comments
			var comments = [];
			if(/not(writable|safe)/i.test($(file).className)){
				comments.include(
					new Element('dt').setHTML(tinyMCEPopup.getLang('dlg.comments', 'Comments'))		   
				);
				// not writable
				if($(file).hasClass('notwritable')){
					comments.include(
						new Element('dd').addClass('comments').addClass('file').addClass('notwritable').adopt(
							new Element('span').addClass('hastip').setProperty('title', tinyMCEPopup.getLang('dlg.notwritable_desc', 'Unwritable')).setHTML(tinyMCEPopup.getLang('dlg.notwritable', 'Unwritable'))
						)
					)
				}
				// not safe
				if($(file).hasClass('notsafe')){
					comments.include(
						new Element('dd').addClass('comments').addClass('file').addClass('notsafe').adopt(
							new Element('span').addClass('hastip').setProperty('title', tinyMCEPopup.getLang('dlg.bad_name_desc', 'Bad file or folder name')).setHTML(tinyMCEPopup.getLang('dlg.bad_name', 'Bad file or folder name'))
						)
					)
				}
			}
			// Inject details
			if($(info)){ 
				$(info).adopt(properties);
				// show preview image if any
				if(o.preview){
					if(o.preview.src){
						$('info-preview').adopt(
							new Asset.image(o.preview.src, {
								width: o.preview.width || 100,
								height: o.preview.height || 100,
								title: 'Preview',
								onload: function(){
									// Show image and remove loader
									this.setStyle('display', '').getParent().removeClass('loader');
								},
								onerror : function(){
									this.setStyle('display', 'none').getParent().removeClass('loader').addClass('preview-error');
								}
							// Hide preview image while loading
							}).setStyle('display', 'none')				
						)
					}else{
						$('info-preview').removeClass('loader').addClass('preview-error');	
					}
				}
			}
			if(comments.length){
				$(this.options.interface.comments).adopt(
					new Element('dl').adopt(comments)			
				)
				// Add tooltip
				this.addToolTip($E('span.hastip', $$('dd.comments')));
			}
			// Fire event
			this.fireEvent('onFileDetails', [o]);
		}.bind(this));	
	}
});
Manager.implement(new Events, new Options);