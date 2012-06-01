(function() {
	tinymce.create('tinymce.plugins.Browser', {
		init : function(ed, url) {},

		getInfo : function() {
			return {
				longname : 'Browser',
				author : 'Ryan Demmer',
				authorurl : 'http://www.joomlacontenteditor.net',
				infourl : 'http://www.joomlacontenteditor.net/index.php?option=com_content&amp;view=article&amp;task=findkey&amp;tmpl=component&amp;lang=en&amp;keyref=browser.about',
				version : '1.5.0'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('browser', tinymce.plugins.Browser);
})();