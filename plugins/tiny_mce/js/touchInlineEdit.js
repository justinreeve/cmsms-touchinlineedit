/**
 * $Id: touchInlineEdit.js 104 2010-12-28 16:49:25Z touchdesign $
 *
 * touchInlineEdit Module
 *
 * Copyright (c) 2010 touchDesign, <www.touchdesign.de>
 *
 * @category Module
 * @author Christoph Gruber <www.touchdesign.de>
 * @version 1.7.4
 * @copyright 04.08.2010 touchDesign
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
 *
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

  tinyMCE.init({
    mode: 'none',
    theme: 'advanced',
    // Save
    plugins: 'save',
    save_enablewhendirty: false,
    save_onsavecallback: 'touchInlineEditSaveMCE',
    // Buttons
    theme_advanced_buttons3_add: 'save',
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
    // Skin
    skin : "o2k7",
    skin_variant : "silver"
  });

  cBlockMain = tinyMCE.execCommand('mceAddControl', true, 'touchInlineEditId' + tieContentId);
}

function touchInlineEditRemoveEditor(){

  tinyMCE.execCommand('mceFocus', false, 'touchInlineEditId' + tieContentId);  
  tinyMCE.execCommand('mceRemoveControl', true, 'touchInlineEditId' + tieContentId);
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
  //console.debug('Read tieContentId:' + tieContentId);
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
  //console.debug('Save tieContentId:' + tieContentId + ', content:' + content);
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
// Wrapper for tinyMCE
function touchInlineEditSaveMCE(){
  touchInlineEditSave(tieContentId,tinyMCE.get('touchInlineEditId' + tieContentId).getContent());
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