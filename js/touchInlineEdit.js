/**
 * $Id: touchInlineEdit.js 141 2011-01-09 19:18:57Z touchdesign $
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
 
function touchInlineEdit(id,uri,message,event){

  /**
   * Self instance.
   * @var object
   * @access public
   */ 
  var self = this;

  /**
   * Optional params.
   * @var object
   * @access public
   */  
  this.params = new Object();

  /**
   * Hold each editor instance.
   * @var object
   * @access public
   */  
  this.instances = new Object();

  /**
   * Request uri.
   * @var string
   * @access public
   */
  this.uri = uri;

  /**
   * Alert message on save success.
   * @var string
   * @access public
   */
  this.message = message;

  /**
   * Init on event.
   * @var boolean
   * @access public
   */
  this.event = event;
  
  /**
   * Add editor instance.
   */
  this.addInstance = function(name)
  {
    //console.debug('Add instance=' + name);
    if(!self.instances[name]){
      self.instances[name] = new touchInlineEditInstance(
        ids.id, ids.block, ids.selector);
      self.instances[name].__construct();
    }
  }

  /**
   * Count instances.
   */
  this.countInstances = function()
  {
    //console.debug('Count instances');
    var count = 0;
    for(var prop in self.instances){
      count++;
    }
    return count;
  }

  /**
   * Find instance.
   */
  this.findInstance = function(selector)
  {
    //console.debug('Find instance by selector=' + selector);
    for(var prop in self.instances){
      if(self.instances[prop].selector == selector){
        return self.getInstance(self.instances[prop].block);
      }
    }
  }
  
  /**
   * Get editor instance.
   */
  this.getInstance = function(name)
  {
    //console.debug('Get instance for block=' + name);
    if(!self.isInstance(name)){
      return false;
    }
    return self.instances[name];
  }

  /**
   *  Check if instance exists.
   */
  this.isInstance = function(name)
  {
    //console.debug('Check if instance exists for block=' + self.block);
    if(!self.instances[name]){
      return false;
    }
    return true;
  }

  /**
   *  current editor instance.
   */
  this.removeInstance = function(name)
  {
    //console.debug('Remove instance=' + name);
    self.getInstance(name).__destruct();
    if(self.isInstance(name)){
      delete(self.instances[name]);
    }
  }

  /**
   * Toggle editor instance.
   */
  this.toggle = function(name)
  {
    //console.debug('Toggle editor for instance=' + name);
    if(!self.isInstance(name)){
      self.fetch(ids.id, ids.block, ids.selector, 0);
      self.addInstance(name);
    }else{
      self.removeInstance(name);
      self.fetch(ids.id, ids.block, ids.selector, 1);
    }
  }

  /**
   * Ajax save content.
   */
  this.save = function(id,block,selector,content)
  {
    $.post(self.uri, { method: "updateContent", id: id, block: block, content: content },
    function(data){
      if(self.message){
        alert(self.message);
      }
      self.toggle(block);
    });
  }

  /**
   * Ajax fetch content.
   */
  this.fetch = function(id,block,selector,fetch)
  {
    $.ajax({async:false,
      type: 'POST',
      url: self.uri,
      data: 'method=getContent&id=' + id + '&block=' + block + '&fetch=' + fetch,
      success: function(data){
        $('#' + selector).html(data);
      }
    });
  }

  /**
   * Param setter.
   */
  this.setParam = function(name,value)
  {
    //console.debug('Add param=' + name + ':' + value);
    if(name){
      self.params[name] = value;
    }
  }

  /**
   * Param getter.
   */
  this.getParam = function(name)
  {
    //console.debug('Get param=' + name);
    if(self.params[name]){
      return self.params[name];
    }
  }

  /**
   * Parse ids from css selector.
   */
  this.splitId = function(name)
  {
    //console.debug('Parse contentid=' + name);
    ids = name.split(' ');
    return result = {
      id: ids[0].substr(1),
      block: ids[1].substr(1),
      selector: 'touchInlineEditId-' + ids[0].substr(1) + '-' + ids[1].substr(1)
    }
  }

}

var onEvent = function(){
  ids = touchInlineEdit.splitId($(this).attr('class'));
  touchInlineEdit.toggle(ids.block);
  return false;
}

$(document).ready(function(){
  $('.touchInlineEditButton').click(onEvent);
  if(touchInlineEdit.event){
    $('.touchInlineEdit').dblclick(onEvent);
  }
});
