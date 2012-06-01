var div = null, allAttrs = null, inAttrs = null, cid = 0, currentAttr = -1, maxSort = 1;
function saveAttr(e) {
	var elm = e.target, val = 0, sel = $('attrs'), opt = sel && currentAttr >= 0 ? sel.options[currentAttr] : null, name = elm.getTag();
	if (!opt) {
		return;
	}
	if (name == 'input' && elm.type == 'checkbox') {
		opt.attr.flags = elm.checked ? opt.attr.flags | elm.value : opt.attr.flags ^ elm.value;
	} else if (name in {'textarea':1, 'select':1} || (name == 'input' && elm.type == 'text')) {
		if (elm.id == 'asort') {
			var pos = elm.selectedIndex;
			if (opt.attr.sort == pos) {
				
			} else if (opt.attr.sort == 0) {
				elm.adopt(new Element('option').setHTML(maxSort));
				maxSort++;
				for (var opts = elm.getElements('option'), i = opts.length - 1; i > pos; i--) {
					opts[i].inAttrOpt = opts[i - 1].inAttrOpt;
					if (opts[i].inAttrOpt != null) {
						opts[i].inAttrOpt.attr.sort++;
					}
				}
				elm.childNodes[pos].inAttrOpt = opt;
				opt.attr.sort = pos;
			} else if (pos != 0) {
				for (var dir = opt.attr.sort > pos ? -1 : 1, i = opt.attr.sort,
						 opts = elm.getChildren();
					 i != pos + dir;
					 i += dir) {
						opts[i].inAttrOpt = opts[i + dir].inAttrOpt;
						opt.attr.sort = i;
				}
				opt.attr.sort = pos;
			} else if (pos == 0) {
				maxSort--;
				for (var opts = elm.getChlidren(), i = opt.attr.sort; (i + 1) < opts.length; i++) {
					opts[i].inAttrOpt = opts[i + 1].inAttrOpt;
				}
				opts[i].dispose();
				opt.attr.sort = 0;
			}
		}
		opt.attr[elm.id] = elm.value;
		opt.text = opt.attr.inputLabel ? opt.attr.inputLabel : (opt.attr.displayLabel ? opt.attr.displayLabel : opt.attr.adminLabel);
	}
	details({target:sel});
}

function details(e) {
	var elm = e.target, opt = -1, from = 0, detailsDiv = $('details');
	if (elm.selectedIndex < 0) {
		return;
	}
	opt = elm.options[elm.selectedIndex];
	for (; from < inAttrs.childNodes.length; from++) {
		if (from in inAttrs.childNodes && inAttrs.childNodes[from] === opt) {
			break;
		}
	}
	if (from == inAttrs.childNodes.length) {
		if (detailsDiv !== null) {
			detailsDiv.style.display = 'none';
		}
		return;
	}
	var iInputLabel = $('inputLabel'), iDisplayLabel = $('displayLabel'),
		iCompareLabel = $('compareLabel'), iPrefix = $('prefix'), iSuffix = $('suffix'),
		iSort = $('asort'), iRequired = $('required'), iInternal = $('internal'),
		iSummary = $('summary'), iMultiple = $('multiple'), iMerge = $('merge'), iNew = $('new'),
		iTitle = $('title'), iGroup = $('group');
	if (detailsDiv === null) {
		iInputLabel		= new Element('textarea', {id:'inputLabel', rows:2, cols:25, events:{change:saveAttr}});
		iDisplayLabel	= new Element('textarea', {id:'displayLabel', rows:2, cols:25, events:{change:saveAttr}});
		iCompareLabel	= new Element('textarea', {id:'compareLabel', rows:2, cols:25, events:{change:saveAttr}});
		iPrefix			= new Element('input', {type:'text', id:'prefix', value:'', size:5, events:{change:saveAttr}});
		iSuffix			= new Element('input', {type:'text', id:'suffix', value:'', size:5, events:{change:saveAttr}});
		iSort			= new Element('select', {id:'asort', events:{change:saveAttr}});
		iRequired		= new Element('input', {type:'checkbox', id:'required', value:FLAG_REQUIRED, events:{click:saveAttr}});
		iInternal		= new Element('input', {type:'checkbox', id:'internal', value:FLAG_INTERNAL, events:{click:saveAttr}});
		iSummary		= new Element('input', {type:'checkbox', id:'summary', value:FLAG_SUMMARY, events:{click:saveAttr}});
		iMultiple		= new Element('input', {type:'checkbox', id:'multiple', value:FLAG_MULTIPLE, events:{click:saveAttr}});
		iMerge			= new Element('input', {type:'checkbox', id:'merge', value:FLAG_MERGE, events:{click:saveAttr}});
		iNew			= new Element('input', {type:'checkbox', id:'new', value:FLAG_NEW, events:{click:saveAttr}});
		iTitle			= new Element('input', {type:'checkbox', id:'title', value:FLAG_TITLE, events:{click:saveAttr}});
		iGroup			= new Element('input', {type:'checkbox', id:'group', value:FLAG_GROUP, events:{click:saveAttr}});
		
		var lInputLabel		= new Element('label', {'for':iInputLabel.id}).setHTML(strings['inputLabelText']).adopt(new Element('br'), iInputLabel),
			lDisplayLabel	= new Element('label', {'for':iDisplayLabel.id}).setHTML(strings['displayLabelText']).adopt(new Element('br'), iDisplayLabel),
			lCompareLabel	= new Element('label', {'for':iCompareLabel.id}).setHTML(strings['compareLabelText']).adopt(new Element('br'), iCompareLabel),
			lPrefix			= new Element('label', {'for':iPrefix.id}).setHTML(strings['prefixText']),
			lSuffix			= new Element('label', {'for':iSuffix.id}).setHTML(strings['suffixText']),
			lSort			= new Element('label', {'for':iSort.id}).setHTML(strings['sortText']),
			lRequired		= new Element('label', {'for':iRequired.id}).setHTML(strings['requiredText']),
			lInternal		= new Element('label', {'for':iInternal.id}).setHTML(strings['internalText']),
			lSummary		= new Element('label', {'for':iSummary.id}).setHTML(strings['summaryText']),
			lMultiple		= new Element('label', {'for':iMultiple.id}).setHTML(strings['multipleText']),
			lMerge			= new Element('label', {'for':iMerge.id}).setHTML(strings['mergeText']),
			lNew			= new Element('label', {'for':iNew.id}).setHTML(strings['newText']),
			lTitle			= new Element('label', {'for':iTitle.id}).setHTML(strings['titleText']),
			lGroup			= new Element('label', {'for':iGroup.id}).setHTML(strings['groupText']),
			opts			= new Element('fieldset').adopt(new Element('legend').setHTML(strings['optsLegendText']),
					iRequired, lRequired, new Element('br'), iInternal, lInternal, new Element('br'),
					iSummary, lSummary, new Element('br'), iMultiple, lMultiple, new Element('br'),
					iMerge, lMerge, new Element('br'), iNew, lNew, new Element('br'), iTitle, lTitle, new Element('br'),
					iGroup, lGroup),
			glue			= new Element('div', {'class':'glue'}).adopt(new Element('div', {'class':'fix'}).adopt(lPrefix, iPrefix),
									new Element('div', {'class':'fix'}).adopt(lSuffix, iSuffix), new Element('br'), lSort, iSort);
		detailsDiv			= new Element('div', {id:'details'}).adopt(lInputLabel, lDisplayLabel,
				new Element('br'), lCompareLabel, glue, opts);
		$('attributes').adopt(detailsDiv);
		iSort.adopt(new Element('option', {value:0}).setHTML(' '), new Element('option').setHTML(1));
		maxSort++;
		for (var i = 0, opts = inAttrs.getChildren(); i < opts.length; i++) {
			var s = opts[i].attr.sort, o = null;
			for (; s >= (iSort.length - 1); maxSort++) {
				o = new Element('option').setHTML(maxSort);
				iSort.adopt(o);
				if (s > iSort.length) {
					for (var j = i + 1; j < opts.length; j++) {
						if (j == opts[j].attr.sort) {
							o.inAttrOpt = opts[j];
							break;
						}
					}
				}
			}
			//alert('sort:' + opts[i].attr.sort + "\nlen:" + iSort.length);
			if (s > 0 && opts[i].attr.sort >+ iSort.length) {
				o.inAttrOpt = opts[i];
			}
		}
		if (iSort.getChildren().length == 1) {
			var o = new Element('option').setHTML('1');
			o.inAttrOpt = null;
			iSort.adopt(o);
		}
	}
	
	iInputLabel.value	= opt.attr['inputLabel'];
	iDisplayLabel.value = opt.attr['displayLabel'];
	iCompareLabel.value	= opt.attr['compareLabel'];
	iPrefix.value		= opt.attr['prefix'];
	iSuffix.value		= opt.attr['suffix'];
	iRequired.checked	= (opt.attr['flags'] & FLAG_REQUIRED) && 1;
	iInternal.checked	= (opt.attr['flags'] & FLAG_INTERNAL) && 1;
	iSummary.checked	= (opt.attr['flags'] & FLAG_SUMMARY) && 1;
	iMultiple.checked	= (opt.attr['flags'] & FLAG_MULTIPLE) && 1;
	iTitle.checked		= (opt.attr['flags'] & FLAG_TITLE) && 1;
	iGroup.checked		= (opt.attr['flags'] & FLAG_GROUP) && 1;
	if (opt.attr['typeId'] <= 99) {
		iMerge.disabled = true;
		iNew.disabled	= true;
	} else {
		iMerge.disabled	= false;
		iNew.disabled	= false;
	}
	iMerge.checked		= (opt.attr['flags'] & FLAG_MERGE) && 1 && !iMerge.disabled;
	iNew.checked		= (opt.attr['flags'] & FLAG_NEW) && 1 && !iNew.disabled;
	detailsDiv.style.display = 'block';
	currentAttr = elm.selectedIndex;
	inAttrs.label = 'Attributes in Type: ' + currentAttr + 'sort:' + opt.attr['sort'];
	iSort.selectedIndex = opt.attr.sort;
}

function move(dir) {
	var opt = null, neoOpt = opt, src = dir ? allAttrs : inAttrs, dst = dir ? inAttrs : allAttrs;
	for (var i = 0; i < src.childNodes.length; i++) {
		opt = src.childNodes[i];
		if (opt.nodeName == 'OPTION' && opt.selected) {
			if (dir) {
				neoOpt = new Element('option', {value:opt.value}).setHTML(opt.text);
				neoOpt.attr = {};
				for (var j in opt.attr) {
					neoOpt.attr[j] = opt.attr[j];
				}
				neoOpt.attr.attrOfTypeId = cid;
				dst.appendChild(neoOpt);
				neoOpt.attr.ordering = inAttrs.childNodes.length;
			} else {
				src.removeChild(opt);
				i--;
			}
		}
	}
	return false;
}
function sort(dir) {
	var opt = null, opt2 = null, opt3 = null, i = 0;
	for (; i < inAttrs.childNodes.length; i++) {
		opt = inAttrs.childNodes[i];
		if (opt.nodeName == 'OPTION' && opt.selected && ((i > 0 && dir == -1) || (i < inAttrs.childNodes.length - 1 && dir == 1))) {
			opt2		= inAttrs.childNodes[i + dir];
			opt3		= opt.cloneNode(true);
			opt3.attr	= {};
			opt3.selected = true;
			for (var j in opt.attr) {
				opt3.attr[j] = opt.attr[j];
			}
			inAttrs.insertBefore(opt3, opt2);
			inAttrs.replaceChild(opt2, opt);
			opt3.attr.ordering += dir;
			opt2.attr.ordering -= dir;
		}
	}
	return false;
}

function loader() {
	cid			= $('cid').value;
	cid			= cid === 0 ? -1 : cid;
	inAttrs		= new Element('optgroup', {label:'Attributes in Type'});
	allAttrs	= new Element('optgroup', {label:'All Attributes'});
	
	var sel	= new Element('select', {id:'attrs', size:20, multiple:'multiple', events:{change:details}}),
		b1	= new Element('button', {type:'button', events:{click:function() {move(1);}}}).setHTML('<abbr title="add">+</abbr>'),
		b2	= new Element('button', {type:'button', events:{click:function() {move(0);}}}).setHTML('<abbr title="remove">-</abbr>'),
		b3	= new Element('button', {type:'button', events:{click:function() {sort(-1);}}}).setHTML('<abbr title="move up">&uarr;</abbr>'),
		b4	= new Element('button', {type:'button', events:{click:function() {sort(1);}}}).setHTML('<abbr title="move down">&darr;</abbr>');
	sel.adopt(inAttrs, allAttrs);
	
	$('attributes').adopt(new Element('div', {id:'list'}).adopt(sel, new Element('br'), b1, b2, b3, b4));
	
	for (var i in attrs) {
		//alert(attrs[i].adminLabel + "\ni:" + i);
		if (isNaN(parseInt(i, 10))) {
			continue;
		}
		var opt = new Element('option', {value:attrs[i].id}).setHTML(attrs[i].adminLabel);
		opt.attr = {};
		for (var j in attrs[i]) {
			opt.attr[j] = attrs[i][j];
		}
		if (cid > 0 && opt.attr.attrOfTypeId == cid) {
			inAttrs.adopt(opt);
			opt.text = 	attrs[i].inputLabel ? attrs[i].inputLabel : (attrs[i].displayLabel ? attrs[i].displayLabel : opt.text);
		} else {
			allAttrs.adopt(opt);
		}
	}
}

function saver() {
	for (var saveAttrs = [], i = 0; i < inAttrs.childNodes.length; i++) {
		saveAttrs[saveAttrs.length] = inAttrs.childNodes[i].attr;
	}
	$('adminForm').adopt(new Element('input', {name:'attrs', type:'hidden', 'value':Json.toString(saveAttrs)}));
	//alert($('adminForm').innerHTML);
}
window.addEvent("load", loader);
window.addEvent('unload', saver);
//This prototype is provided by the Mozilla foundation and
//is distributed under the MIT license.
//http://www.ibiblio.org/pub/Linux/LICENSES/mit.license 
function iOf(elt)  {
	var len = this.length, from = Number(arguments[1]) || 0;
	from = from < 0 ? Math.ceil(from) : Math.floor(from);
	if (from < 0) {
		from += len;
	}
	for (; from < len; from++) {
		if (from in this && this[from] === elt) {
			return from;
		}
	}
	return -1;
}
Array.prototype.indexOf = iOf;
function lIOf(elt) {
	var len = this.length, from = Number(arguments[1]) || len - 1;
	from = (from < 0) ? Math.ceil(from) : Math.floor(from);
	if (from < 0) {
		from = len + from;
	}
	if (from >= len || from < 0) {
		from = len - 1;
	}
	for (; from > 0; from--) {
		if (from in this && this[from] === elt) {
			return from;
		}
	}
	return -1;
}
Array.prototype.lastIndexOf = lIOf;
/*
FLAG_REQUIRED	= window['FLAG_REQUIRED'];
FLAG_INTERNAL	= window['FLAG_INTERNAL'];
FLAG_SUMMARY	= window['FLAG_SUMMARY'];
FLAG_MULTIPLE	= window['FLAG_MULTIPLE'];
FLAG_MERGE		= window['FLAG_MERGE'];
FLAG_NEW		= window['FLAG_NEW'];
*/