/**
 * $Id$
 *
 * touchInlineEdit Module
 *
 * Copyright (c) 2010 touchDesign, <www.touchdesign.de>
 *
 * @category Module
 * @author Christoph Gruber <www.touchdesign.de>
 * @version 1.8.0
 * @copyright 04.08.2010 touchDesign
 * @link http://www.touchdesign.de/
 * @link http://www.homepage-community.de/index.php?topic=1680.0
 * @link http://dev.cmsmadesimple.org/projects/touchInlineEdit
 * @license http://www.gnu.org/licenses/licenses.html#GPL GNU General Public License (GPL 2.0)
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
 
function touchInlineEdit(id,request,message,onClick){

  /**
   * Self instance.
   * @var object
   * @access public
   */ 
  var self = this;
  
  /**
   * Editor instance.
   * @var object
   * @access public
   */
  this.editor;
  
  /**
   * Content id for current block.
   * @var integer
   * @access public
   */
  this.contentId = id;
  
  /**
   * Request uri.
   * @var string
   * @access public
   */
  this.requestUri = request;
  
  /**
   * Alert message on save success.
   * @var string
   * @access public
   */
  this.message = message;
  
  /**
   * Init on dbl click.
   * @var integer
   * @access public
   */
  this.onClick = onClick;
  
  /**
   * Optional params.
   * @var object
   * @access public
   */  
  this.params = new Object();
  
  /**
   * Add editor instance.
   */
  this.add = function(){
    self.editor = new nicEditor({
      iconsPath: self.getParam('icons_path'),
      buttonList: self.getParam('button_list').split(','),
      maxHeight: self.getParam('height'),
      onSave: function(content,id,instance){self.save(id,content)}
    }).panelInstance(
      'touchInlineEditId' + self.contentId,
      {hasPanel : true}
    );
  }

  /**
   * Remove editor instance.
   */
  this.remove = function(){
    self.editor.removeInstance('touchInlineEditId' + self.contentId);
    self.editor = null;
  }
  
  /**
   * Toggle editor instance.
   */
  this.toggle = function(){
    if(!self.editor){
      self.fetch();
      self.add();
    }else{
      self.remove();
    }
  }
  
  /**
   * Ajax fetch content.
   */
  this.fetch = function(){
    //console.debug('Read self.contentId:' + self.contentId);
    $.ajax({async:false,
      type: 'POST',
      url: self.requestUri,
      data: 'method=getContent&id=' + self.contentId,
      success: function(data){
        $('#touchInlineEditId' + self.contentId).html(data);
      }
    });
  }
  
  /**
   * Ajax save content.
   */
  this.save = function(id,content){
    //console.debug('Save self.contentId:' + self.contentId + ', content:' + content);
    $.post(self.requestUri, { method: "updateContent", id: self.contentId, content: content },
      function(data){
        if(self.message){
          alert(self.message);
        }
        self.toggle();
        $('#touchInlineEditId' + self.contentId).html(data);
      }
    );
  }
  
  /**
   * Param setter.
   */
  this.setParam = function(name,value){
    //console.debug('Add param ' + name + '=' + value);
    if(name){
      self.params[name] = value;
    }
  }
  
  /**
   * Param getter.
   */
  this.getParam = function(name){
    if(self.params[name]){
      return self.params[name];
    }
  }
}

$(document).ready(function(){
  
  $('.touchInlineEditButton').click(function(){
    touchInlineEdit.toggle();
    return false;
  });
  
  if(touchInlineEdit.onClick){
    $('.touchInlineEdit').dblclick(function(){
      touchInlineEdit.toggle();
      return false;
    });
  }

});
