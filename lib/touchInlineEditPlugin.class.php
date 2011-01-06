<?php
/**
 * $Id$
 *
 * touchInlineEdit tinyMCE plugin
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

class touchInlineEditPlugin extends touchInlineEdit {

  /**
   * Plugin name.
   * @var string
   * @access public
   */
  var $name;

  /**
   * Plugin display name.
   * @var string
   * @access public
   */
  var $displayName;

  /**
   * Name of the plugin author.
   * @var string
   * @access public
   */
  var $author;

  /**
   * Plugin description.
   * @var string
   * @access public
   */
  var $description;
  
  /**
   * Plugin version.
   * @var string
   * @access public
   */
  var $version;
  
  /**
   * Plugin dir.
   * @var string
   * @access public
   */  
  var $pluginDir;

  /**
   * Is this editor plugin the current installed plugin?
   * @var boolean
   * @access public
   */  
  var $installed = false;

  /**
   * Plugin specific templates.
   * @var array
   * @access public
   */  
  var $templates = array();
  
  /**
   * Plugin specific settings.
   * @var array
   * @access public
   */  
  var $settings = array();  
  
  /**
   * Construct a new editor plugin.
   */
  function __construct(){

    $this->pluginDir = 'modules/' . $this->getName() . '/' 
      . TIE_PLUGIN_DIR . $this->name;
    
    // TODO: Call future parent get method
    $this->installed = $this->getPreference('touchInlineEdit.fePlugin') 
      == $this->name ? true : false;
    
    // TODO: Replace/Remove this
    parent::__construct($this->name);
  }

  /**
   * Plugin install method.
   */
  public function install(){
    //if(!$this->installed){
      $this->update($this->settings);
      foreach($this->templates as $name => $content){
        $this->setTemplate('touchInlineEdit.'.$this->name.'.'.$name, $content);
      }
    //}
  }
  
  /**
   * Plugin uninstall method.
   */
  public function uninstall(){
    /* not implemented */
  }
  
  /**
   * Set plugin config
   */
  public function set($name,$value){
    return $this->setPreference('touchInlineEdit.'.$this->name.'.'.$name,$value);
  }
  
  /**
   * Get plugin config
   */
  public function get($name,$default=null){
    return $this->getPreference('touchInlineEdit.'.$this->name.'.'.$name,$default);
  }
  
  /**
   * Fetches smarty template relative to $pluginDir.
   */
  public function fetch($template,$database=false){
    $config = $this->getCMSConfig();
    
    if($database){
      return $this->processTemplateFromDatabase('touchInlineEdit.'.$this->name.'.'.$template);
    }
    
    return $this->smarty->fetch($config['root_path'] . '/'
      . $this->pluginDir . '/templates/' . $template);
  }

  /**
   * Get all admin config for this plugin. 
   */
  public function getSettings($id,$returnid){}

  /**
   * Get html header infos for this plugin. 
   */
  public function getHeader(){}

  /**
   * Set or update admin config for this plugin. 
   */
  public function update($params){
    foreach($params as $name => $value){
      if(isset($this->settings[$name])){
        $this->set($name,$value);
      }
    }
  }
}

?>