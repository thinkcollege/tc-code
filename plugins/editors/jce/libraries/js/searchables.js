/*
Class: Searchables
	Creates an interface for <Drag.Base> and drop, resorting of a list.

Note:
	The Sortables require an XHTML doctype.

Arguments:
	input 	- the input element
	list 	- the target list to scroll.
	items	- the target items to search
	options - an Object, see options below.

Options:
	
Events:
	onFind - function executed when an item is found
*/
var Searchables = new Class({
	getOptions : function(){
		return {
			onFind: Class.empty
		};
	},	
	initialize : function(input, list, items, options){
		this.setOptions(this.getOptions(), options);
		var i = $(input), x = [];
		var scroller = new Fx.Scroll($(list), {
			wait: false,
			duration: 500
		});
		i.addEvent('keyup', function(e){
			var s = i.value;
			if(/[a-z0-9_\.-]/i.test(s)){
				$(items).getChildren().each(function(el){
					if(string.basename(el.title).substring(0, s.length) == s){
						x.include(el);
					}else{	
						x.remove(el);
					}
				}.bind(this));
			}else{
				x = [];	
			}
			if(x.length){
				scroller.toElement(x[0]);
			}else{
				scroller.toTop();	
			}
			this.fireEvent('onFind', [x]);
		}.bind(this));
	}					
});
Searchables.implement(new Events, new Options);