var ChronoTips = new Class({
		options: {
			onShow: function(tip){
				tip.setStyle('visibility', 'visible');
			},
			onHide: function(tip){
				tip.setStyle('visibility', 'hidden');
			},
			maxTitleChars: 30,
			showDelay: 100,
			hideDelay: 100,
			className: 'tool',
			offsets: {'x': 16, 'y': 16},
			fixed: false
		},
		initialize: function(elements, lasthope,options){
			this.setOptions(options);
			this.lasthope = lasthope;
			this.toolTip = new Element('div', {
				'class': 'cf_'+this.options.className + '-tip',
				'id': this.options.className + '-tip-' + this.options.elementid,
				'styles': {
					'position': 'absolute',
					'top': '0',
					'left': '0',
					'visibility': 'hidden'
				}
			}).inject(document.body);
			this.wrapper = new Element('div').inject(this.toolTip);
			$$(elements).each(this.build, this);
			if (this.options.initialize) this.options.initialize.call(this);
		},
	
		build: function(el){
			el.$tmp.myTitle = (el.href && el.getTag() == 'a') ? el.href.replace('http://', '') : (el.rel || false);
			if (el.title){
				var dual = el.title.split('::');
				if (dual.length > 1){
					el.$tmp.myTitle = dual[0].trim();
					el.$tmp.myText = dual[1].trim();
				} else {
					el.$tmp.myText = el.title;
				}
				el.removeAttribute('title');
			} else {
				var dual = this.lasthope.split('::');
				if (dual.length > 1){
					el.$tmp.myTitle = dual[0].trim();
					el.$tmp.myText = dual[1].trim();
				} else {
					el.$tmp.myText = el.title;
				}
			}
			if (el.$tmp.myTitle && el.$tmp.myTitle.length > this.options.maxTitleChars) el.$tmp.myTitle = el.$tmp.myTitle.substr(0, this.options.maxTitleChars - 1) + "&hellip;";
			el.addEvent('mouseenter', function(event){
				this.start(el);
				if (!this.options.fixed) this.locate(event);
				else this.position(el);
			}.bind(this));
			if (!this.options.fixed) el.addEvent('mousemove', this.locate.bindWithEvent(this));
			var end = this.end.bind(this);
			el.addEvent('mouseleave', end);
			el.addEvent('trash', end);
		},
		start: function(el){
			this.wrapper.empty();
			if (el.$tmp.myTitle){
				this.title = new Element('span').inject(new Element('div', {'class': 'cf_'+this.options.className + '-title'}).inject(this.wrapper)).setHTML(el.$tmp.myTitle);
			}
			if (el.$tmp.myText){
				this.text = new Element('span').inject(new Element('div', {'class': 'cf_'+this.options.className + '-text'}).inject(this.wrapper)).setHTML(el.$tmp.myText);
			}
			$clear(this.timer);
			this.timer = this.show.delay(this.options.showDelay, this);
		},
		end: function(event){
			$clear(this.timer);
			this.timer = this.hide.delay(this.options.hideDelay, this);
		},
	
		position: function(element){
			var pos = element.getPosition();
			this.toolTip.setStyles({
				'left': pos.x + this.options.offsets.x,
				'top': pos.y + this.options.offsets.y
			});
		},
	
		locate: function(event){
			var win = {'x': window.getWidth(), 'y': window.getHeight()};
			var scroll = {'x': window.getScrollLeft(), 'y': window.getScrollTop()};
			var tip = {'x': this.toolTip.offsetWidth, 'y': this.toolTip.offsetHeight};
			var prop = {'x': 'left', 'y': 'top'};
			for (var z in prop){
				var pos = event.page[z] + this.options.offsets[z];
				if ((pos + tip[z] - scroll[z]) > win[z]) pos = event.page[z] - this.options.offsets[z] - tip[z];
				this.toolTip.setStyle(prop[z], pos);
			};
		},
	
		show: function(){
			if (this.options.timeout) this.timer = this.hide.delay(this.options.timeout, this);
			this.fireEvent('onShow', [this.toolTip]);
		},
	
		hide: function(){
			this.fireEvent('onHide', [this.toolTip]);
		}
	});
	ChronoTips.implement(new Options);
	ChronoTips.implement(new Events);
	window.addEvent('domready', function() {
		$ES('.tooltipimg').each(function(ed){
			var Tips2 = new ChronoTips(ed, $E('div.tooltipdiv', ed.getParent().getParent()).getText(), {elementid:ed.getParent().getParent().getFirst().getNext().getProperty('id')+'_s'});
		});
	});