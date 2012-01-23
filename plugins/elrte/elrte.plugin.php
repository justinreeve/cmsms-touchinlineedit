<?php
/**
 * $Id$
 *
 * touchInlineEdit Module
 *
 * Copyright (c) 2010 touchdesign, <www.touchdesign.de>
 *
 * @category Module
 * @author Christoph Gruber <www.touchdesign.de>
 * @version 1.8.3
 * @copyright 04.08.2010 touchdesign
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

class elrte extends touchInlineEditPlugin {

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
<script src="{$tie->plugin->path}/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="{$tie->plugin->path}/js/jquery-ui-1.8.5.custom.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="{$tie->plugin->path}/js/ui-themes/smoothness/jquery-ui-1.8.5.custom.css" type="text/css" media="screen" charset="utf-8" />
{/if}
<script src="{$tie->plugin->path}/js/elrte.min.js" type="text/javascript"></script>
<script src="{$tie->plugin->path}/js/i18n/elrte.ru.js" type="text/javascript"></script>
<link rel="stylesheet" href="{$tie->plugin->path}/css/elrte.full.css" type="text/css" media="screen" charset="utf-8" />
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
    $this->name = 'elrte';
    $this->displayName = 'elRTE';    
    parent::__construct($module);
    $this->settings = array(
      'jquery_load' => 1,
      'toolbar' => 'complete'
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
    
    // Toolbar
    $name = 'toolbar';
    $options = array('tiny' => 'tiny','compact' => 'compact','normal' => 'normal','complete' => 'complete','maxi' => 'maxi');
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
    return $this->fetch('header',true);
  }
  
}

?>