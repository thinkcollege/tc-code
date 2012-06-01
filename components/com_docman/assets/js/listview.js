/**
 * DOCLink 1.4.x
 * @version $Id: listview.js 561 2008-01-17 11:34:40Z mjaz $
 * @package DOCLink_1.4
 * @copyright (C) 2003-2008 The DOCman Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.joomlatools.org/ Official website
 **/

var st, st1, st2; //sortable tables identifiers

//Initialise listview
function _listview_init()	{

	if(document.getElementById("tableItems") != null)	{
		st = new SortableTable(
			document.getElementById("tableItems"),
			["None", "CaseInsensitiveString", "Number", "Number", "None"]
		);
	}
}

function onclickFolder(parid, catid, name, url, icon)	{
	window.parent.setFields(name, url, catid, icon, '', '');
	window.parent.setListCtrl(parid, catid);
}

function onclickItem(name, id, cid, ext, size, time)	{
	window.parent.setFields(name, id, cid, ext, size, time);
}

function setListView(catid) {
	location.href = "index.php?option=com_docman&task=doclink-listview&catid="+catid;
}

window.onload = _listview_init
//always hide the loading status
window.parent.changeDialogStatus('load');