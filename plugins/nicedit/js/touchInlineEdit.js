/**
 * $Id: touchInlineEdit.js 51 2010-09-13 14:06:25Z touchdesign $
 *
 * touchInlineEdit Module
 *
 * Copyright (c) 2010 touchDesign, <www.touchdesign.de>
 *
 * @category Module
 * @author Christoph Gruber <www.touchdesign.de>
 * @version 1.6
 * @copyright Christoph Gruber touchDesign.de 04.08.2010
 * @link http://www.touchdesign.de/
 * @link http://www.homepage-community.de/index.php?topic=1680.0
 * @link http://dev.cmsmadesimple.org/projects/touchInlineEdit
 * @license http://www.gnu.org/licenses/licenses.html#GPL GNU General Public License (GPL 2.0)
 * @todo add content_block handling
 * 
 * --
 *
 * Usage: 
 *
 * {cms_module module="touchInlineEdit"}
 *
 * {content iseditable='true'}
 *
 * --
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 * Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
 *
 */

function touchInlineEditInitEditor(){

	cBlockMain = new nicEditor({
		fullPanel: tieFullPanel,
		iconsPath: tieIconsPath,
		onSave: function(content,id,instance){touchInlineEditSave(id,content)}
	}).panelInstance(
		'touchInlineEditId' + tieContentId,
		{hasPanel : true}
	);
}

function touchInlineEditRemoveEditor(){

	cBlockMain.removeInstance('touchInlineEditId' + tieContentId);
	cBlockMain = null;
}

function touchInlineEditToggleEditor(){

	if(!cBlockMain){
		touchInlineEditInitContent();
		touchInlineEditInitEditor();
	}else{
		touchInlineEditRemoveEditor();
	}
}

function touchInlineEditInitContent(){

	$.ajax({async:false,
		type: 'POST',
		url: tieRequestUri,
		data: 'method=getContent&id=' + tieContentId,
		success: function(data){
			$('#touchInlineEditId' + tieContentId).html(data);
		}
	});
}

function touchInlineEditSave(id,content){

	$.post(tieRequestUri, { method: "updateContent", id: tieContentId, content: content },
		function(data){
			if(tieUpdateAlert){
				alert(tieUpdateAlertMessage);
			}
			touchInlineEditToggleEditor();
			$('#touchInlineEditId' + tieContentId).html(data);
		}
	);
}

function functionExists(name){
	return (typeof name == 'function');
}

$(document).ready(function(){

	$('.touchInlineEditButton').click(function(){
		touchInlineEditToggleEditor();
		return false;
	});

	if(tieEditOnDblClick){
		$('.touchInlineEdit').dblclick(function(){
			touchInlineEditToggleEditor();
			return false;
		});
	}
});