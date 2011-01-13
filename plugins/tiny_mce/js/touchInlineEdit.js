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

function touchInlineEdit(id,request,message,onClick){

  /**
   * Self instance.
   * @var object
   * @access public
   */ 
  var self = this;
  
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
   * Hold each editor instance.
   * @var object
   * @access public
   */  
  this.instances = new Object();

  /**
   * Add editor instance.
   */
  this.addInstance = function(name){
    //console.debug('Add instance=' + name);
    self.parseContentId(name);
    if(!self.instances[self.block]){
      self.instances[self.block] = {
        editor: null,
        id: self.id,
        block: self.block,
        contentId: self.contentId
      };
    }
  }

  /**
   * Get curent editor instance.
   */
  this.getInstance = function(){
    //console.debug('Get instance for block=' + self.block);
    if(!self.instances[self.block]){
      self.instances[self.block] = self;
    }
    return self.instances[self.block];
  }
  
  /**
   *  current editor instance.
   */
  this.removeInstance = function(){
    //console.debug('Remove instance=' + self.getInstance().block);
    if(self.instances[self.getInstance().block]){
      self.instances[self.getInstance().block] = null;
    }
  }

  /**
   * Add editor instance.
   */
  this.add = function(){
    //console.debug('Add editor instance for id=' + self.getInstance().id + ', block=' + self.getInstance().block);
    //alert('Add editor instance for id=' + self.getInstance().id + ', block=' + self.getInstance().block);
    tinyMCE.init({
      mode: 'none',
      auto_focus: 'touchInlineEditId' + self.getInstance().contentId,
      // Theme
      theme: self.getParam('theme'),
      // Skin
      skin: self.getParam('skin'),
      skin_variant: self.getParam('skin_variant'),
      // Size
      width: self.getParam('width'),
      height: self.getParam('height'),
      // Plugins
      plugins: 'save,-cmslinker,' + self.getParam('plugins'),
      // Extras
      visual: true,  
      accessibility_warnings: false,
      fix_list_elements: true,
      verify_html: true,
      verify_css_classes: false,
      relative_urls: true,
      remove_script_host: true,
      // Save
      save_enablewhendirty: false,
      save_onsavecallback: 'touchInlineEditSaveMCE',
      // Basic buttons
      theme_advanced_buttons1_add_before: 'save,cmslinker',
      theme_advanced_toolbar_location: 'top',
      theme_advanced_toolbar_align: 'left',
      theme_advanced_statusbar_location: 'bottom',
      // Extra buttons
      theme_advanced_buttons1: self.getParam('buttons1'),
      theme_advanced_buttons2: self.getParam('buttons2'),
      theme_advanced_buttons3: self.getParam('buttons3'),
      // Options
      theme_advanced_resizing: self.getParam('theme_advanced_resizing'),
      // Newlines
      force_br_newlines: self.getParam('force_br_newlines'),
      force_p_newlines: self.getParam('force_p_newlines'),
      forced_root_block: self.getParam('forced_root_block'),
      // Encoding
      entity_encoding: self.getParam('entity_encoding'), 
      button_tile_map: true
    });
    self.instances[self.getInstance().block]['editor'] = tinyMCE.execCommand('mceAddControl', true, self.getInstance().contentId);
  }

  /**
   * Remove editor instance.
   */
  this.remove = function(){
    tinyMCE.execCommand('mceFocus', false, self.getInstance().contentId);  
    tinyMCE.execCommand('mceRemoveControl', true, self.getInstance().contentId);
    self.removeInstance();
  }
  
  /**
   * Toggle editor instance.
   */
  this.toggle = function(){
    //alert(self.instances[self.getInstance().block]['editor']);
    //console.debug('Toggle editor for instance=' + self.getInstance().block);
    if(!self.getInstance().editor){
      self.add();
      self.fetch();
/*       $('.touchInlineEditButton').attr('disabled', 'disabled');
      $('.touchInlineEditButton').unbind('click');
      $('.touchInlineEdit').unbind('dblclick'); */
    }else{
      self.remove();
      self.fetch();
/*       $('.touchInlineEditButton').removeAttr('disabled');
      $('.touchInlineEditButton').bind('click',onEvent);
      $('.touchInlineEdit').bind('dblclick',onEvent); */
    }
  }
  
  /**
   * Ajax fetch content.
   */
  this.fetch = function(){
    //console.debug('Read id=' + self.getInstance().id + ', block=' + self.getInstance().block);
    $.ajax({async:false,
      type: 'POST',
      url: self.requestUri,
      data: 'method=getContent&contentId=' + self.getInstance().contentId + '&id=' + self.getInstance().id + '&block=' + self.getInstance().block,
      success: function(data){
        $('#' + self.getInstance().contentId).html(data);
      }
    });
  }
  
  /**
   * Ajax save content.
   */
  this.save = function(id,content){
    //console.debug('Save contentId=' + self.getInstance().contentId + ', content=' + content);
    $.post(self.requestUri, { method: "updateContent", contentId: self.getInstance().contentId, id: self.getInstance().id, block: self.getInstance().block, content: content },
      function(data){
        if(self.message){
          alert(self.message);
        }
        self.toggle();
      }
    );
  }
  
  /**
   * Param setter.
   */
  this.setParam = function(name,value){
    //console.debug('Add param=' + name + ':' + value);
    if(name){
      self.params[name] = value;
    }
  }
  
  /**
   * Param getter.
   */
  this.getParam = function(name){
    //console.debug('Get param=' + name);
    if(self.params[name]){
      return self.params[name];
    }
  }
  
  /**
   * Set current content id.
   */
  this.parseContentId = function(name){
    //console.debug('Parse contentid=' + name);
    var ids = name.split(' ');
    self.id = ids[0].substr(1);
    self.block = ids[1].substr(1);
    self.contentId = 'touchInlineEditId-' + self.id + '-' + self.block;
    //console.debug('Parsed result id=' + self.id + ',block=' + self.block + ',contentid=' + self.contentId);
  }
}

// TODO: find a better w...
function touchInlineEditSaveMCE(){
  touchInlineEdit.save(touchInlineEdit.getInstance().contentId,
    tinyMCE.get(touchInlineEdit.getInstance().contentId).getContent());
}

var onEvent = function(){
  touchInlineEdit.addInstance($(this).attr('class'));
  touchInlineEdit.toggle();
  return false;
}

$(document).ready(function(){
  $('.touchInlineEditButton').click(onEvent);
  if(touchInlineEdit.onClick){
    $('.touchInlineEdit').dblclick(onEvent);
  }
});
