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
 * @todo add content_block handling
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

global $gCms;
require_once cms_join_path($gCms->config['root_path'],'modules',
  'touchInlineEdit','lib','touchInlineEditPlugin.class.php');

class tiny_mce extends touchInlineEditPlugin {

  var $templates = array(
    'header' => 
'<!-- {$tie->getName()} :: {$tie->editor->displayName} module -->
<script src="{$tie->editor->pluginDir}/js/tiny_mce/tiny_mce.js" type="text/javascript"></script>
{if $tie->editor->get(\'JQueryLoad\')}
  <script src="{$tie->editor->pluginDir}/js/jquery.js" type="text/javascript"></script>
{/if}
<script src="{$tie->editor->pluginDir}/js/touchInlineEdit.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
  var touchInlineEdit = new touchInlineEdit(
    {$tie->getContentId()},
    "{$tie->getRequestUri()}",
    "{$tie->touch->get(\'feUpdateAlertMessage\',\'Content saved...\')}",
    {$tie->touch->get(\'feEditOnDblClick\')}
  );
  touchInlineEdit.setParam(\'theme\',\'advanced\');
</script>
<!-- {$tie->getName()} :: {$tie->editor->displayName} module -->');

  function __construct(){
    $this->name = 'tiny_mce';
    $this->displayName = 'tinyMCE';
    $this->settings = array(
      'JQueryLoad' => 1,
    );
    parent::__construct($this->name);
  }

  public function install(){
    parent::install($this->settings);
  }

  public function getAdminConfig($id,$returnid){

    $yn = array(
      $this->lang("no") => 0,
      $this->lang("yes") => 1
    );

    // Form start
    $this->smarty->assign('formstart',$this->createFormStart($id,"saveeditor",$returnid));

    // jquery lib
    $this->smarty->assign($this->name.'JQueryLoad_label',$this->lang($this->name."JQueryLoad_label"));
    $this->smarty->assign($this->name.'JQueryLoad_help',$this->lang($this->name."JQueryLoad_help"));
    $this->smarty->assign($this->name.'JQueryLoad_input',$this->createInputDropdown($id,'JQueryLoad',
      $yn,$this->get('JQueryLoad',1),"","\n"));

    // Submit / cancel
    $this->smarty->assign('submit',$this->createInputSubmit($id,"submit",$this->lang("save")));
    $this->smarty->assign('cancel',$this->createInputSubmit($id,"cancel",$this->lang("cancel")));

    // Form end
    $this->smarty->assign('formend',$this->createFormEnd());

    return $this->fetch("admineditor.tpl");
  }

  // TODO: Rename
  public function getHeader(){
    return $this->fetch('header',true);
  }
}

?>