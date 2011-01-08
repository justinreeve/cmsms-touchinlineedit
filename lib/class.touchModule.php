<?php
/**
 * $Id$
 *
 * touchModule base class
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
  
class touchModule {

  /**
   * CMSms module instance.
   * @var object
   * @access public
   */
  var $module;

  /**
   * Module name.
   * @var string
   * @access public
   */
  var $name;
  
  /**
   * Module path.
   * @var string
   * @access public
   */
  var $path;
  
  /**
   * Constructor.
   */
  function __construct(&$module){
    $this->module = $module;
    $this->name = $module->getName();
    $this->path = 'modules/' . $module->getName();
  }
  
  /**
   * Set plugin config.
   */
  public function set($name,$value){
    return $this->module->setPreference($this->name.'.'.$name,$value);
  }
  
  /**
   * Get module config.
   */
  public function get($name,$default=null){
    return $this->module->getPreference($this->name.'.'.$name,$default);
  }
  
  /**
   * Fetch smarty template relative to module.
   */
  public function fetch($template,$database=false){
    
    $config = $this->cmsms('config');
    $smarty = $this->cmsms('smarty');
    
    if($database){
      return $this->module->processTemplateFromDatabase($this->name.'.'.$template);
    }
    
    return $smarty->fetch($config['root_path'] . '/'
      . $this->path . '/templates/' . $template);
  }

  /**
   * Set or update config for this module. 
   */
  public function update($params){
    foreach($params as $name => $value){
      if(isset($this->settings[$name])){
        $this->set($name,$value);
      }
    }
  }
  
  /**
   * Check for AJAX request. 
   */
  public function isAJAXRequest(){

    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
      && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) 
      == 'xmlhttprequest' ? true : false;
  }

  /**
   * Get magic cmsms objects.
   */
  public function cmsms($case=null){
    
    if(function_exists('cmsms')){
      $cmsms = cmsms();
    }else{
      global $gCms;
      $cmsms =& $gCms;
    }
    
    switch($case){
      case 'smarty':
        if(method_exists($cmsms,'getSmarty')){
          return $cmsms->getSmarty();
        }
        global $gCms;
        return $gCms->smarty;
      case 'config':
        if(method_exists($cmsms,'getConfig')){
          return $cmsms->getConfig();
        }else{
          global $gCms;
          return $gCms->config;
        }
      default:
        return $cmsms;
    }

  }
}

?>