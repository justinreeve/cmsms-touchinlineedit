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
    self.editor = new elRTE(document.getElementById(self.selector),
      {
        cssClass: 'el-rte',
        lang: 'en',
        toolbar: touchInlineEdit.getParam('toolbar'),
        cssfiles: ['css/elrte-inner.css']
      }
    );
    // Add save handler
    elRTE.prototype.save = function(){
      self.save(this.filter.source($(this.doc.body).html()));
    };
  }

  /**
   * Save contents.
   */
  this.save = function(content)
  {
    console.debug('Save ' + self.block);
    touchInlineEdit.save(self.id,self.block,self.selector,content);
  }

  /**
   * Destruct editor instance.
   */
  this.__destruct = function()
  {
    //console.debug('Destruct ' + self.block);
    self.editor.editor.hide(); // Remove editor
    self.editor.editor.prev().show(); // Display div
  }

}