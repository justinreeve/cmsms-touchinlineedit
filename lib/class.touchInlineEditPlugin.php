<?php
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

class touchInlineEditPlugin {

  /**
   * Module parent instance.
   * @var object
   * @access public
   */
  var $module;

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
  var $path;

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
  public $templates = array(
    'header' => 
'<!-- {$tie->getName()} :: {$tie->plugin->displayName} module -->
<script src="{$tie->plugin->path}/js/{$tie->plugin->name}.js" type="text/javascript"></script>
{if $tie->plugin->get(\'jquery_load\')}
  <script src="{$tie->plugin->path}/js/jquery.js" type="text/javascript"></script>
{/if}
<script src="{$tie->plugin->path}/js/touchInlineEdit.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
  {$tieExtraScript}
  var touchInlineEdit = new touchInlineEdit(
    {$tie->getContentId()},
    "{$tie->touch->getRequestUri()}",
    "{$tie->touch->get(\'feUpdateAlertMessage\')}",
    {$tie->touch->get(\'feEditOnDblClick\')}
  );
{foreach from=$tie->plugin->getSettings() key=name item=value}
  touchInlineEdit.setParam("{$name}","{$value}");
{/foreach}
</script>
<!-- {$tie->getName()} :: {$tie->plugin->displayName} module -->');
  
  /**
   * Plugin specific settings.
   * @var array
   * @access public
   */  
  var $settings = array();  
  
  /**
   * Construct a new editor plugin.
   */
  function __construct(&$module)
  {
    $this->module = $module;
    $this->path = 'modules/' . $this->module->getName() . '/' 
      . TIE_PLUGIN_DIR . $this->name;
    $this->installed = $this->module->touch->get('fePlugin') 
      == $this->name ? true : false;
  }

  /**
   * Plugin install method.
   */
  public function install()
  {
    //if(!$this->installed){
      $this->update($this->settings);
      foreach($this->templates as $name => $content){
        $this->module->setTemplate('touchInlineEdit.'.$this->name.'.'.$name, $content);
      }
    //}
  }
  
  /**
   * Plugin uninstall method.
   */
  public function uninstall()
  {
    foreach($this->templates as $name => $content){
      $this->module->deleteTemplate('touchInlineEdit.'.$this->name.'.'.$name);
    }
  }
  
  /**
   * Set plugin config
   */
  public function set($name,$value)
  {
    return $this->module->touch->set($this->name.'.'.$name,$value);
  }
  
  /**
   * Get plugin config
   */
  public function get($name,$default=null)
  {
    return $this->module->touch->get($this->name.'.'.$name,$default);
  }
  
  /**
   * Fetch smarty template relative to $path.
   */
  public function fetch($template,$database=false)
  {
    if($database){
      return $this->module->touch->fetch($this->name.'.'.$template,$database);
    }
    $config = $this->module->touch->cmsms('config');
    $smarty = $this->module->touch->cmsms('smarty');
    
    return $smarty->fetch($config['root_path'] . '/'
      . $this->path . '/templates/' . $template);
  }

  /**
   * Get all settings for this plugin as array.
   */
  public function getSettings()
  {
    $settings=array();
    foreach($this->settings as $name => $value){
      $settings[$name] = $this->get($name,$this->settings[$name]);
    }
    
    return $settings;
  }
  
  /**
   * Get admin config tab code, override by plugin.
   */
  public function getAdminConfig($id,$returnid){}
  
  /**
   * Get html header infos for this plugin, override by plugin.
   */
  public function getHeader(){}

  /**
   * Set or update admin config for this plugin. 
   */
  public function update($params)
  {
    foreach($params as $name => $value){
      if(isset($this->settings[$name])){
        $this->set($name,$value);
      }
    }
  }

}

?>