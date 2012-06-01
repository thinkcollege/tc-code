/*
 * Smoothbox v20070814 by Boris Popoff (http://gueschla.com)
 *
 * Based on Cody Lindley's Thickbox, MIT License
 *
 * Licensed under the MIT License:
 *   http://www.opensource.org/licenses/mit-license.php
 */

// on page load call TB_init
window.addEvent('domready', TB_init);

// prevent javascript error before the content has loaded
TB_WIDTH = 0;
TB_HEIGHT = 0;
var TB_doneOnce = 0 ;

// add smoothbox to href elements that have a class of .smoothbox
function TB_init(){
	$$("a.smoothbox").each(function(el){el.onclick=TB_bind});
}

function TB_bind(event) {
	var event = new Event(event);
	// stop default behaviour
	event.preventDefault();
	// remove click border
	this.blur();
	// get caption: either title or name attribute
	var caption = this.title || this.name || "";
	// get rel attribute for image groups
	var group = this.rel || false;
	// display the box for the elements href
	TB_show(caption, this.href, group);
	this.onclick=TB_bind;
	return false;
}


// called when the user clicks on a smoothbox link
function TB_show(caption, url, rel) {
	// create iframe, overlay and box if non-existent

	if ( !$("TB_overlay") )
	{
		new Element('iframe').setProperty('id', 'TB_HideSelect').injectInside(document.body);
		$('TB_HideSelect').setOpacity(0);
		new Element('div').setProperty('id', 'TB_overlay').injectInside(document.body);
		$('TB_overlay').setOpacity(0);
		TB_overlaySize();
		new Element('div').setProperty('id', 'TB_load').injectInside(document.body);
		$('TB_load').innerHTML = "<img src='components/com_chronocontact/css/smoothbox/loading.gif' />";
		TB_load_position();
		new Fx.Style('TB_overlay', 'opacity',{duration: 400, transition: Fx.Transitions.sineInOut}).start(0,0.6);
	}
	
	if ( !$("TB_load") )
	{		
		new Element('div').setProperty('id', 'TB_load').injectInside(document.body);
		$('TB_load').innerHTML = "<img src='components/com_chronocontact/css/smoothbox/loading.gif' />";
		TB_load_position();
	}
	
	if ( !$("TB_window") )
	{
		new Element('div').setProperty('id', 'TB_window').injectInside(document.body);
		$('TB_window').setOpacity(0);
	}
	
	$("TB_overlay").onclick=TB_remove;
	window.onscroll=TB_positionEffect;

	// check if a query string is involved
	var baseURL = url.match(/(.+)?/)[1] || url;

	// regex to check if a href refers to an image
	var imageURL = /\.(jpe?g|png|gif|bmp)/gi;

	// check for images
	if ( baseURL.match(imageURL) ) {
		/*var dummy = { caption: "", url: "", html: "" };
		
		var prev = dummy,
			next = dummy,
			imageCount = "";
			
		// if an image group is given
		if ( rel ) {
			function getInfo(image, id, label) {
				return {
					caption: image.title,
					url: image.href,
					html: "<span id='TB_" + id + "'>&nbsp;&nbsp;<a href='#'>" + label + "</a></span>"
				}
			}
		
			// find the anchors that point to the group
			var imageGroup = [] ;
			$$("a.smoothbox").each(function(el){
				if (el.rel==rel) {imageGroup[imageGroup.length] = el ;}
			})

			var foundSelf = false;
			
			// loop through the anchors, looking for ourself, saving information about previous and next image
			for (var i = 0; i < imageGroup.length; i++) {
				var image = imageGroup[i];
				var urlTypeTemp = image.href.match(imageURL);
				
				// look for ourself
				if ( image.href == url ) {
					foundSelf = true;
					imageCount = "Image " + (i + 1) + " of "+ (imageGroup.length);
				} else {
					// when we found ourself, the current is the next image
					if ( foundSelf ) {
						next = getInfo(image, "next", "Next &gt;");
						// stop searching
						break;
					} else {
						// didn't find ourself yet, so this may be the one before ourself
						prev = getInfo(image, "prev", "&lt; Prev");
					}
				}
			}
		}
		
		imgPreloader = new Image();
		imgPreloader.onload = function() {
			imgPreloader.onload = null;

			// Resizing large images
			var x = window.getWidth() - 150;
			var y = window.getHeight() - 150;
			var imageWidth = imgPreloader.width;
			var imageHeight = imgPreloader.height;
			if (imageWidth > x) {
				imageHeight = imageHeight * (x / imageWidth); 
				imageWidth = x; 
				if (imageHeight > y) { 
					imageWidth = imageWidth * (y / imageHeight); 
					imageHeight = y; 
				}
			} else if (imageHeight > y) { 
				imageWidth = imageWidth * (y / imageHeight); 
				imageHeight = y; 
				if (imageWidth > x) { 
					imageHeight = imageHeight * (x / imageWidth); 
					imageWidth = x;
				}
			}
			// End Resizing
			
			// TODO don't use globals
			TB_WIDTH = imageWidth + 30;
			TB_HEIGHT = imageHeight + 60;
			
			// TODO empty window content instead
			$("TB_window").innerHTML += "<a href='' id='TB_ImageOff' title='Close'><img id='TB_Image' src='"+url+"' width='"+imageWidth+"' height='"+imageHeight+"' alt='"+caption+"'/></a>" + "<div id='TB_caption'>"+caption+"<div id='TB_secondLine'>" + imageCount + prev.html + next.html + "</div></div><div id='TB_closeWindow'><a href='#' id='TB_closeWindowButton' title='Close'>close</a></div>";
			
			$("TB_closeWindowButton").onclick = TB_remove;
			
			function buildClickHandler(image) {
				return function() {
					$("TB_window").remove();
					new Element('div').setProperty('id', 'TB_window').injectInside(document.body);
					
					TB_show(image.caption, image.url, rel);
					return false;
				};
			}
			var goPrev = buildClickHandler(prev);
			var goNext = buildClickHandler(next);
			if ( $('TB_prev') ) {
				$("TB_prev").onclick = goPrev;
			}
			
			if ( $('TB_next') ) {		
				$("TB_next").onclick = goNext;
			}
			
			document.onkeydown = function(event) {
				var event = new Event(event);
				switch(event.code) {
				case 27:
					TB_remove();
					break;
				case 190:
					if( $('TB_next') ) {
						document.onkeydown = null;
						goNext();
					}
					break;
				case 188:
					if( $('TB_prev') ) {
						document.onkeydown = null;
						goPrev();
					}
					break;
				}
			}
			
			// TODO don't remove loader etc., just hide and show later
			$("TB_ImageOff").onclick = TB_remove;
			TB_position();
			TB_showWindow();
		}
		imgPreloader.src = url;
		*/
	} else { //code to show html pages
		
		var queryString = url.match(/\?(.+)/)[1];
		var params = TB_parseQuery( queryString );
		
		TB_WIDTH = params['homeId'] ? ($(params['homeId']).getStyle('width').toInt() + 40) : ((params['width']*1) + 40);//(params['width']*1) + 30;
		TB_HEIGHT = 500;//(params['height']*1) + 40;
		
		//alert($E('input',  $(params['homeId'])).getProperty('name'));
		 params['homeId'] ? ($(params['homeId']).setStyle('display','none')) : '';
		if(caption == 'Form Preview'){
			Output = $(params['homeId']).clone();
			$ES('.delete_icon',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.slabel',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.drag',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.config_icon',Output).each(function(delete_div){
				delete_div.remove();
			});
			
			Output.getChildren().each(function(element){
				element.removeEvents();
			});
			$ES('.form_item',Output).each(function(element){
				element.setStyle('border','0px');
			});
			$ES('.cf_hidden',Output).each(function(element){
				element.setStyle('display','none');
			});
			$(params['inlineId']).innerHTML = Output.innerHTML;
			
			
			$ES('input[id^=date_]',$(params['inlineId'])).each(function(date_field){
				//date_field.setProperty('id',date_field.getProperty('id')+"_s");
				date_field.setProperty('onClick',"new Calendar(this);");
			});
			
		}else if(caption == 'HTML Source'){
			$(params['inlineId']).innerHTML = '';
			Output = $(params['homeId']).clone();
			$ES('.delete_icon',Output).each(function(delete_div){
				delete_div.remove();
			});
			
			$ES('.slabel',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.drag',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.config_icon',Output).each(function(delete_div){
				delete_div.remove();
			});
			
			$ES('',Output).each(function(element){
				element.removeEvents();
			});
			$ES('.form_item',Output).each(function(element){
				element.setStyle('border','0px');
			});
			var sourcecode = Output.innerHTML.replace(/\$included="null"/g,'').replace(/\$events="null"/g,'');//.replace(/style=".*?"/g,'');
			Output.innerHTML = sourcecode;
			$ES('.cf_hidden',Output).each(function(element){
				element.setStyle('display','none');
			});
			sourcearea = new Element('textarea');
			sourcearea.setText(Output.innerHTML.replace(/\$included="null"/g,'').replace(/\$events="null"/g,''));
			//$(params['inlineId']).innerHTML = sourcearea;
			sourcearea.setStyle('width', (TB_WIDTH - 60));
			sourcearea.setStyle('height', (TB_HEIGHT - 80));
			//alert($E('input',  Output).getProperty('name')); alert(sourcearea.getText());
			sourcearea.injectInside($(params['inlineId']));
		}else if(caption == 'Select Field'){
			$(params['inlineId']).innerHTML = '';
			$(params['inlineId']).setStyles({'display':'none'});
			Output = $(params['homeId']).clone();
			$ES('.delete_icon',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.slabel',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.drag',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.config_icon',Output).each(function(delete_div){
				delete_div.empty();									 	
				delete_div.injectHTML('<div class="config_icon"><img src="components/com_chronocontact/css/images/checkin.png" alt="Select" width="15" height="15"  /></div>', 'after');
			});
			$ES('',Output).each(function(element){
				element.removeEvents();
			});
			$ES('label',Output).each(function(element){
				element.setProperty('for', '');
			});
			$ES('.form_item',Output).each(function(element){
				element.setStyle('border','0px');
			});
			$ES('.cf_hidden',Output).each(function(element){
				element.setStyle('display','none');
			});
			
			if((params['sourceId']).contains('dto_')){
				var header = '<div style="color:#ff0000">Please select the field which will have the email address to be used as the TO EMAIL</div><br><br>';
			}else if((params['sourceId']).contains('dsubject_')){
				var header = '<div style="color:#ff0000">Please select the field which will have the text to be used as the Email Subject</div><br><br>';
			}else if((params['sourceId']).contains('dfromname_')){
				var header = '<div style="color:#ff0000">Please select the field which will have the text to be used as the Email From Name</div><br><br>';
			}else if((params['sourceId']).contains('dfromemail_')){
				var header = '<div style="color:#ff0000">Please select the field which will have the email to be used as the Email From Email</div><br><br>';
			}else if((params['sourceId']).contains('dcc_')){
				var header = '<div style="color:#ff0000">Please select the field which will have the email to be used as a CC Email</div><br><br>';
			}else if((params['sourceId']).contains('dbcc_')){
				var header = '<div style="color:#ff0000">Please select the field which will have the email to be used as a BCC Email</div><br><br>';
			}else{}
			
			$(params['inlineId']).innerHTML = header + Output.innerHTML.replace(/\$included="null"/g,'').replace(/\$events="null"/g,'');
			
		}else if(caption == 'Add Field'){
			/*if(!$chk($E('div[class=cf_email]', $('left_column2')))){
				alert('Sorry, you have not created any emails in Step 2 to choose fields!');
				TB_remove();
			}*/
			$(params['inlineId']).innerHTML = '';
			$(params['inlineId']).setStyles({'display':'none'});
			Output = $(params['homeId']).clone();
			$ES('.delete_icon',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.slabel',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.drag',Output).each(function(delete_div){
				delete_div.remove();
			});
			$ES('.config_icon',Output).each(function(delete_div){
				delete_div.empty();									 	
				delete_div.injectHTML('<div class="config_icon"><img src="components/com_chronocontact/css/images/checkin.png" alt="Select" width="15" height="15"  /></div>', 'after');
			});
			$ES('',Output).each(function(element){
				element.removeEvents();
			});
			$ES('label',Output).each(function(element){
				element.setProperty('for', '');
			});
			$ES('.form_item',Output).each(function(element){
				element.setStyle('border','0px');
			});
			$ES('.cf_hidden',Output).each(function(element){
				element.setStyle('display','none');
			});
			
			$(params['inlineId']).innerHTML = Output.innerHTML.replace(/\$included="null"/g,'').replace(/\$events="null"/g,'');
			
		}else{}

		var ajaxContentW = TB_WIDTH - 30,
			ajaxContentH = TB_HEIGHT - 45;
		
		if(url.indexOf('TB_iframe') != -1){				
			urlNoQuery = url.split('TB_');		
			$("TB_window").innerHTML += "<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton' title='Close'>close</a></div></div><iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;' onload='TB_showWindow()'> </iframe>";
		} else {
			$("TB_window").innerHTML += "<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton'>close</a></div></div><div id='TB_ajaxContent' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px;'></div>";
		}
				
		
	
				
		$("TB_closeWindowButton").onclick = TB_remove;
		
			if(url.indexOf('TB_inline') != -1){	
				$("TB_ajaxContent").innerHTML = ($(params['inlineId']).innerHTML);
				TB_position();
				TB_showWindow();
			}else if(url.indexOf('TB_iframe') != -1){
				TB_position();
				if(frames['TB_iframeContent'] == undefined){//be nice to safari
					$(document).keyup( function(e){ var key = e.keyCode; if(key == 27){TB_remove()} });
					TB_showWindow();
				}
			}else{
				var handlerFunc = function(){
					TB_position();
					TB_showWindow();
				};
				var myRequest = new Ajax(url, {method: 'get',update: $("TB_ajaxContent"),onComplete: handlerFunc}).request();
			}
	}

	window.onresize=function(){ TB_position(); TB_load_position(); TB_overlaySize();}  
	
	document.onkeyup = function(event){ 	
		var event = new Event(event);
		if(event.code == 27){ // close
			TB_remove();
		}	
	}
	
	
	//Insert Tooltip
	
	$ES('.tooltipimg',$("TB_ajaxContent")).each(function(ed){
		//ed.setProperty('title', $E('div.tooltipdiv', ed.getParent().getParent()).getText());
		if($chk($('tool-tip-'+ed.getParent().getParent().getFirst().getNext().getProperty('id')+'_s'))){
			$('tool-tip-'+ed.getParent().getParent().getFirst().getNext().getProperty('id')+'_s').remove();
		}
		var Tips2 = new Tips(ed, $E('div.tooltipdiv', ed.getParent().getParent()).getText(), {elementid:ed.getParent().getParent().getFirst().getNext().getProperty('id')+'_s'});
	});
	
	if(caption == 'Select Field'){
		$ES('.cf_inputbox', $("TB_ajaxContent")).each(function(element){
			element.addEvents({
				'click': function() {
					$(params['sourceId']).value = this.getProperty('name').replace('[]', '');
					TB_remove();
				},
				'mouseover': function() {
					element.getParent().getParent().effect('background-color', {wait: false, duration: 100}).start('E7DFE7','E7DFE7');							
				},
				'mouseout': function() {
					element.getParent().getParent().effect('background-color', {wait: false, duration: 100}).start('ffffff','ffffff');
				}
			})
		});
		
		$ES('.radio', $("TB_ajaxContent")).each(function(element){
			element.addEvents({
				'click': function() {
					$(params['sourceId']).value = this.getProperty('name').replace('[]', '');
					TB_remove();
				},
				'mouseover': function() {
					element.getParent().getParent().getParent().effect('background-color', {wait: false, duration: 100}).start('E7DFE7','E7DFE7');							
				},
				'mouseout': function() {
					element.getParent().getParent().getParent().effect('background-color', {wait: false, duration: 100}).start('ffffff','ffffff');
				}
			})
		});
		
		$ES('.config_icon', $("TB_ajaxContent")).each(function(element){
			element.addEvents({
				'click': function() {
					var name = '';
					if($chk(element.getParent().getFirst().getFirst().getNext())){
						if(element.getParent().getFirst().getFirst().getNext().getProperty('name') == null){
							if(element.getParent().getFirst().getFirst().getNext().getProperty('class') == 'float_left'){	
								name = element.getParent().getFirst().getFirst().getNext().getFirst().getProperty('name');
							}
						}else{
							name = element.getParent().getFirst().getFirst().getNext().getProperty('name');	
						}
					}
					$(params['sourceId']).value = name.replace('[]', '');
					TB_remove();
				},
				'mouseover': function() {
					element.getParent().effect('background-color', {wait: false, duration: 100}).start('E7DFE7','E7DFE7');							
				},
				'mouseout': function() {
					element.getParent().effect('background-color', {wait: false, duration: 100}).start('ffffff','ffffff');
				}
			})
		});
	}
	
	if(caption == 'Add Field'){
		$ES('.cf_inputbox', $("TB_ajaxContent")).each(function(element){
			element.addEvents({
				'click': function() {
					TB_remove();
					if(params['sourceId'] == 'onsubmitcode'){
						tinyMCE.get('onsubmitcode').focus();
						tinyMCE.get('onsubmitcode').selection.moveToBookmark(ieBookmark);
						tinyMCE.execCommand('mceInsertContent',false,'{'+this.getProperty('name').replace('[]', '')+'}');
					}else{
						tinyMCE.activeEditor.focus();
						tinyMCE.activeEditor.selection.moveToBookmark(ieBookmark);
						tinyMCE.execCommand('mceInsertContent',false,'{'+this.getProperty('name').replace('[]', '')+'}');
					}
				},
				'mouseover': function() {
					element.getParent().getParent().effect('background-color', {wait: false, duration: 100}).start('E7DFE7','E7DFE7');							
				},
				'mouseout': function() {
					element.getParent().getParent().effect('background-color', {wait: false, duration: 100}).start('ffffff','ffffff');
				}
			})
		});
		
		$ES('.radio', $("TB_ajaxContent")).each(function(element){
			element.addEvents({
				'click': function() {
					TB_remove();
					if(params['sourceId'] == 'onsubmitcode'){
						tinyMCE.get('onsubmitcode').focus();
						tinyMCE.get('onsubmitcode').selection.moveToBookmark(ieBookmark);
						tinyMCE.execCommand('mceInsertContent',false,'{'+this.getProperty('name').replace('[]', '')+'}');
					}else{
						tinyMCE.activeEditor.focus();
						tinyMCE.activeEditor.selection.moveToBookmark(ieBookmark);
						tinyMCE.execCommand('mceInsertContent',false,'{'+this.getProperty('name').replace('[]', '')+'}');
					}
				},
				'mouseover': function() {
					element.getParent().getParent().getParent().effect('background-color', {wait: false, duration: 100}).start('E7DFE7','E7DFE7');							
				},
				'mouseout': function() {
					element.getParent().getParent().getParent().effect('background-color', {wait: false, duration: 100}).start('ffffff','ffffff');
				}
			})
		});
		
		$ES('.config_icon', $("TB_ajaxContent")).each(function(element){
			element.addEvents({				
				'click': function() {
					TB_remove();
					var name = '';
					if($chk(element.getParent().getFirst().getFirst().getNext())){
						if(element.getParent().getFirst().getFirst().getNext().getProperty('name') == null){
							if(element.getParent().getFirst().getFirst().getNext().getProperty('class') == 'float_left'){	
								name = '{'+element.getParent().getFirst().getFirst().getNext().getFirst().getProperty('name').replace('[]', '')+'}';
							}
						}else{
							name = '{'+element.getParent().getFirst().getFirst().getNext().getProperty('name').replace('[]', '')+'}';	
						}
					}
					if(params['sourceId'] == 'onsubmitcode'){
						tinyMCE.get('onsubmitcode').focus();
						tinyMCE.get('onsubmitcode').selection.moveToBookmark(ieBookmark);
						tinyMCE.execCommand('mceInsertContent',false, name);
					}else{
						tinyMCE.activeEditor.focus();
						tinyMCE.activeEditor.selection.moveToBookmark(ieBookmark);
						tinyMCE.execCommand('mceInsertContent',false, name);
					}
				},
				'mouseover': function() {
					element.getParent().effect('background-color', {wait: false, duration: 100}).start('E7DFE7','E7DFE7');							
				},
				'mouseout': function() {
					element.getParent().effect('background-color', {wait: false, duration: 100}).start('ffffff','ffffff');
				}
			})
		});
	}
	
	
		
}

//helper functions below

function TB_showWindow(){
	//$("TB_load").remove();
	//$("TB_window").setStyles({display:"block",opacity:'0'});
	
	if (TB_doneOnce==0) {
		TB_doneOnce = 1;
		var myFX = new Fx.Style('TB_window', 'opacity',{duration: 250, transition: Fx.Transitions.sineInOut, onComplete:function(){if ($('TB_load')) { $('TB_load').remove();}} }).start(0,1);
	} else {
		$('TB_window').setStyle('opacity',1);
		if ($('TB_load')) { $('TB_load').remove();}
	}
}

function TB_remove() {
	$('left_column').setStyle('display','inline');
	$('temp_code2').innerHTML = '';
	
 	$("TB_overlay").onclick=null;
	document.onkeyup=null;
	document.onkeydown=null;
	
	if ($('TB_imageOff')) $("TB_imageOff").onclick=null;
	if ($('TB_closeWindowButton')) $("TB_closeWindowButton").onclick=null;
	if ( $('TB_prev') ) { $("TB_prev").onclick = null; }
	if ( $('TB_next') ) { $("TB_next").onclick = null; }

	new Fx.Style('TB_window', 'opacity',{duration: 250, transition: Fx.Transitions.sineInOut, onComplete:function(){$('TB_window').remove();} }).start(1,0);
	new Fx.Style('TB_overlay', 'opacity',{duration: 400, transition: Fx.Transitions.sineInOut, onComplete:function(){$('TB_overlay').remove();} }).start(0.6,0);

	window.onscroll=null;
	window.onresize=null;	
	
	$('TB_HideSelect').remove();
	TB_init();
	TB_doneOnce = 0;
	return false;
}

function TB_position() {
	$("TB_window").setStyles({width: TB_WIDTH+'px', 
				 left: (window.getScrollLeft() + (window.getWidth() - TB_WIDTH)/2)+'px',
				 top: (window.getScrollTop() + (window.getHeight() - TB_HEIGHT)/2)+'px'});
}

function TB_positionEffect() {
	new Fx.Styles('TB_window', {duration: 75, transition: Fx.Transitions.sineInOut}).start({
		'left':(window.getScrollLeft() + (window.getWidth() - TB_WIDTH)/2)+'px',
		'top':(window.getScrollTop() + (window.getHeight() - TB_HEIGHT)/2)+'px'});
}

function TB_overlaySize(){
	// we have to set this to 0px before so we can reduce the size / width of the overflow onresize 
	$("TB_overlay").setStyles({"height": '0px', "width": '0px'});
	$("TB_HideSelect").setStyles({"height": '0px', "width": '0px'});
	$("TB_overlay").setStyles({"height": window.getScrollHeight()+'px', "width": window.getScrollWidth()+'px'});
	$("TB_HideSelect").setStyles({"height": window.getScrollHeight()+'px',"width": window.getScrollWidth()+'px'});
}

function TB_load_position() {
	if ($("TB_load")) { $("TB_load").setStyles({left: (window.getScrollLeft() + (window.getWidth() - 56)/2)+'px', top: (window.getScrollTop() + ((window.getHeight()-20)/2))+'px',display:"block"}); }
}

function TB_parseQuery ( query ) {
	// return empty object
	if( !query )
		return {};
	var params = {};
	
	// parse query
	var pairs = query.split(/[;&]/);
	for ( var i = 0; i < pairs.length; i++ ) {
		var pair = pairs[i].split('=');
		if ( !pair || pair.length != 2 )
			continue;
		// unescape both key and value, replace "+" with spaces in value
		params[unescape(pair[0])] = unescape(pair[1]).replace(/\+/g, ' ');
   }
   return params;
}
