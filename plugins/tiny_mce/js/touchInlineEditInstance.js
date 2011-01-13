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

function touchInlineEditInstance(id,block,selector){
  
  /**
   * Self instance.
   * @var object
   * @access public
   */ 
  var self = this;

  /**
   * Page id.
   * @var integer
   * @access public
   */ 
  this.id = id;
  
  /**
   * Page block.
   * @var name
   * @access public
   */ 
  this.block = block;
  
  /**
   * Selector html id.
   * @var integer
   * @access public
   */ 
  this.selector = selector;
  
  /**
   * Editor instance.
   * @var object
   * @access public
   */ 
  this.editor;

  /**
   * Construct editor instance.
   */
  this.__construct = function()
  {
    //console.debug('Construct ' + self.block);
    tinyMCE.init({
      mode: 'none',
      auto_focus: self.selector,
      // Theme
      theme: touchInlineEdit.getParam('theme'),
      // Skin
      skin: touchInlineEdit.getParam('skin'),
      skin_variant: touchInlineEdit.getParam('skin_variant'),
      // Size
      width: touchInlineEdit.getParam('width'),
      height: touchInlineEdit.getParam('height'),
      // Plugins
      plugins: 'save,-cmslinker,' + touchInlineEdit.getParam('plugins'),
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
      theme_advanced_buttons1: touchInlineEdit.getParam('buttons1'),
      theme_advanced_buttons2: touchInlineEdit.getParam('buttons2'),
      theme_advanced_buttons3: touchInlineEdit.getParam('buttons3'),
      // Options
      theme_advanced_resizing: touchInlineEdit.getParam('theme_advanced_resizing'),
      // Newlines
      force_br_newlines: touchInlineEdit.getParam('force_br_newlines'),
      force_p_newlines: touchInlineEdit.getParam('force_p_newlines'),
      forced_root_block: touchInlineEdit.getParam('forced_root_block'),
      // Encoding
      entity_encoding: touchInlineEdit.getParam('entity_encoding'), 
      button_tile_map: true
    });
    self.editor = tinyMCE.execCommand('mceAddControl', true, self.selector);
  }

  /**
   * Save contents.
   */
  this.save = function(content)
  {
    //console.debug('Save ' + self.block);
    touchInlineEdit.save(self.id,self.block,self.selector,content);
  }

  /**
   * Destruct editor instance.
   */
  this.__destruct = function()
  {
    //console.debug('Destruct ' + self.block);
    tinyMCE.execCommand('mceFocus', false, self.selector);  
    tinyMCE.execCommand('mceRemoveControl', true, self.selector);
  }

}

// TODO: find a better w...
function touchInlineEditSaveMCE(editor){
  var c = touchInlineEdit.findInstance(editor.id);
  c.save(tinyMCE.get(c.selector).getContent());
}
