<?php
/**
 * $Id$
 *
 * touchInlineEdit Module tinyMCE plugin
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

class tiny_mce extends touchInlineEditPlugin {

  /**
   * Plugin specific templates.
   * @var array
   * @access public
   */  
  public $templates = array(
    'header' => 
'<!-- {$tie->getName()} :: {$tie->plugin->displayName} module -->
<script src="{$tie->plugin->path}/js/{$tie->plugin->name}/{$tie->plugin->name}.js" type="text/javascript"></script>
{if $tie->plugin->get(\'jquery_load\')}
<script src="{$tie->plugin->path}/js/jquery.js" type="text/javascript"></script>
{/if}
<script src="{$tie->touch->path}/js/touchInlineEdit.js" type="text/javascript"></script>
<script src="{$tie->plugin->path}/js/touchInlineEditInstance.js" type="text/javascript"></script>
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
   * Construct a new plugin and call parent.
   */
  function __construct(&$module)
  {
    $this->name = 'tiny_mce';
    $this->displayName = 'tinyMCE';
    parent::__construct($module);
    $this->settings = array(
      'jquery_load' => 1,
      'theme' => 'advanced',
      'skin' => 'o2k7',
      'skin_variant' => 'silver',
      'buttons1' => 'print,cut,paste,pastetext,pasteword,copy,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,fontselect,fontsizeselect',
      'buttons2' => 'bold,italic,underline,strikethrough,advhr,separator,bullist,numlist,separator,outdent,indent,separator,undo,redo,separator,link,unlink,anchor,image,media,charmap,separator,forecolor,backcolor',
      'buttons3' => 'cmslinker,search,replace,separator,separator,fullscreen,preview,code,styleprops,visualchars,cleanup,separator,tablecontrols,separator,help',
      'force_br_newlines' => 0,
      'force_p_newlines' => 1,
      'forced_root_block' => 'p',
      'entity_encoding' => 'raw',
      'theme_advanced_resizing' => 1,
      'plugins' => 'searchreplace,fullscreen,inlinepopups,media,preview,print,style,table,visualchars',
      'width' => 'auto',
      'height' => 'auto'
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
      
    // theme
    $name = 'theme';
    $options = array('advanced' => 'advanced','simple' => 'simple');
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputDropdown($id,$name,
      $options,$this->get($name,$this->settings[$name]),$this->get($name,$this->settings[$name])));
    
    // Skin
    $name = 'skin';
    $options = array('default' => 'default','o2k7' => 'o2k7');
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputDropdown($id,$name,
      $options,$this->get($name,$this->settings[$name]),$this->get($name,$this->settings[$name])));
    
    // Skin variant
    $name = 'skin_variant';
    $options = array('default' => '', 'silver' => 'silver','black' => 'black');
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputDropdown($id,$name,
      $options,$this->get($name,$this->settings[$name]),$this->get($name,$this->settings[$name])));

    // Width
    $name = 'width';
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputText($id,$name,
      $this->get($name,$this->settings[$name]),10,3));
    
    // Height
    $name = 'height';
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputText($id,$name,
      $this->get($name,$this->settings[$name]),10,3));
      
    // Plugins
    $name = 'plugins';
    $options = $this->getPlugins();
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputSelectList($id,$name.'[]',
      $options,explode(',',$this->get($name,$this->settings[$name])),10,'style="width:200px"'));
      
    // Buttons1
    $name = 'buttons1';
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputText($id,$name,
      $this->get($name,$this->settings[$name]),80,255));
    
    // Buttons2
    $name = 'buttons2';
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputText($id,$name,
      $this->get($name,$this->settings[$name]),80,255));
    
    // Buttons3
    $name = 'buttons3';
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputText($id,$name,
      $this->get($name,$this->settings[$name]),80,255));
    
    // Newlines p
    $name = 'force_p_newlines';
    $options = $yn;
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputDropdown($id,$name,
      $options,$this->get($name,$this->settings[$name]),$this->get($name,$this->settings[$name])));
    
    // Newlines br
    $name = 'force_br_newlines';
    $options = $yn;
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputDropdown($id,$name,
      $options,$this->get($name,$this->settings[$name]),$this->get($name,$this->settings[$name])));
      
    // Newlines br
    $name = 'entity_encoding';
    $options = array('raw' => 'raw', 'named' => 'named', 'numeric' => 'numeric');
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputDropdown($id,$name,
      $options,$this->get($name,$this->settings[$name]),$this->get($name,$this->settings[$name])));
    
    // Resizing
    $name = 'theme_advanced_resizing';
    $options = $yn;
    $this->module->smarty->assign($this->name.'_'.$name.'_label',$this->module->lang($this->name.'_'.$name.'_label'));
    $this->module->smarty->assign($this->name.'_'.$name.'_help',$this->module->lang($this->name.'_'.$name.'_help'));
    $this->module->smarty->assign($this->name.'_'.$name.'_input',$this->module->createInputDropdown($id,$name,
      $options,$this->get($name,$this->settings[$name]),$this->get($name,$this->settings[$name])));
    
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
/*     $cTiny = $this->module->touch->getCMSModuleInstance('TinyMCE');
    if($cTiny){
      $this->module->smarty->assign('tieExtraScript',$cTiny->GetCMSLinker());
    } */
    
    return $this->fetch('header',true);
  }
  
  /**
   * Get available module as array.
   */
  protected function getPlugins()
  {
    $config = $this->module->touch->cmsms('config');

    $availablePlugins = array_diff(scandir($config['root_path']. '/' . $this->path 
      . '/js/tiny_mce/plugins'),array('.','..','.svn','.htaccess')); 

    $plugins = array();
    foreach($availablePlugins as $plugin){
      $plugins[$plugin] = $plugin;
    }

    return array_flip($plugins);
  }
  
}

?>