<?php
/**
 * $Id$
 *
 * touchInlineEdit Module nicEdit plugin
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

class nicedit extends touchInlineEditPlugin {

  /**
   * Construct a new plugin and call parent.
   */
  function __construct(&$module)
  {
    $this->name = 'nicedit';
    $this->displayName = 'nicEdit';
    $this->supportsMultiple = true;
    parent::__construct($module);
    $this->settings = array(
      'jquery_load' => 1,
      'button_list' => 'save,bold,italic,underline,left,center,right,justify,ol,ul,subscript,superscript,strikethrough,removeformat,indent,outdent,hr,image,forecolor,bgcolor,link,unlink,fontSize,fontFamily,fontFormat,xhtml',
      'height' => 'auto',
      'icons_path' => $this->path . '/img/icons.gif'
    );
  }

  /**
   * Get admin config tab code.
   */
  public function getAdminConfig($id,$returnid)
  {
    $yn = array(
      $this->module->lang("no") => 0,
      $this->module->lang("yes") => 1
    );

    // Form start
    $this->module->smarty->assign('formstart',$this->module->createFormStart($id,"saveeditor",$returnid));

    // jquery
    $name = 'jquery_load';
    $options = $yn;
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputDropdown($id,$name,
      $options,$this->get($name,$this->settings[$name]),$this->get($name,$this->settings[$name])));
    
    // Buttons
    $name = 'button_list';
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputText($id,$name,
      $this->get($name,$this->settings[$name]),80,255));
    
    // Height
    $name = 'height';
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputText($id,$name,
      $this->get($name,$this->settings[$name]),10,4));
    
    // Submit / cancel
    $this->module->smarty->assign('submit',$this->module->createInputSubmit($id,"submit",$this->module->lang("save")));
    $this->module->smarty->assign('cancel',$this->module->createInputSubmit($id,"cancel",$this->module->lang("cancel")));
    
    // Form end
    $this->module->smarty->assign('formend',$this->module->createFormEnd());
    
    return $this->fetch("admineditor.tpl");
  }

  /**
   * Get plugin header html.
   */
  public function getHeader()
  {
    return $this->fetch('header',true);
  }
  
}

?>
