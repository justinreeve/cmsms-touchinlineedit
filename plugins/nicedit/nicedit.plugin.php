<?php
/**
 * $Id$
 *
 * touchInlineEdit nicedit plugin
 *
 * Copyright (c) 2010 touchDesign, <www.touchdesign.de>
 *
 * @category Module
 * @author Christoph Gruber <www.touchdesign.de>
 * @version 1.7.4
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
 * {content iseditable='true'}
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

class nicedit extends touchInlineEdit {

  var $name = 'nicedit';
  var $pluginDir;

  function __construct(){

    $this->pluginDir = 'modules/' . $this->getName() . '/' 
      . TIE_PLUGIN_DIR . $this->name;

    parent::__construct($this->name);
  }

  public function install(){
    $this->SetPreference("touchInlineEdit.".$this->name.".FullPanel","true");
    $this->SetPreference("touchInlineEdit.".$this->name.".JQueryLoad","true");
  }

  private function fetch($template){

    $config = $this->getCMSConfig();

    return $this->smarty->fetch($config['root_path'] . '/'
      . $this->pluginDir
      . '/templates/' . $template);
  }

  public function getAdminConfig($id,$returnid){

    $bool = array(
      $this->Lang("yes") => 'true',
      $this->Lang("no") => 'false'
    );

    // Form start
    $this->smarty->assign('formstart',$this->CreateFormStart($id,"saveeditor",$returnid));

    // Enable full panel in FE
    $this->smarty->assign($this->name.'FullPanel_label',$this->Lang($this->name."FullPanel_label"));
    $this->smarty->assign($this->name.'FullPanel_help',$this->Lang($this->name."FullPanel_help"));
    $this->smarty->assign($this->name.'FullPanel_input',$this->CreateInputRadioGroup($id,$this->name."FullPanel",
      $bool,$this->GetPreference("touchInlineEdit.".$this->name.".FullPanel","true"),"","\n"));

    // jquery lib
    $this->smarty->assign($this->name.'JQueryLoad_label',$this->Lang($this->name."JQueryLoad_label"));
    $this->smarty->assign($this->name.'JQueryLoad_help',$this->Lang($this->name."JQueryLoad_help"));
    $this->smarty->assign($this->name.'JQueryLoad_input',$this->CreateInputRadioGroup($id,$this->name."JQueryLoad",
      $bool,$this->GetPreference("touchInlineEdit.".$this->name.".JQueryLoad","true"),"","\n"));

    // Submit / cancel
    $this->smarty->assign('submit',$this->CreateInputSubmit($id,"submit",$this->Lang("save")));
    $this->smarty->assign('cancel',$this->CreateInputSubmit($id,"cancel",$this->Lang("cancel")));

    // Form end
    $this->smarty->assign('formend',$this->CreateFormEnd());

    echo $this->fetch("admineditor.tpl");
  }

  public function saveAdminConfig($params){

    if(isset($params[$this->name."FullPanel"]) && !empty($params[$this->name."FullPanel"])){
      $this->SetPreference("touchInlineEdit.".$this->name.".FullPanel",$params[$this->name."FullPanel"]);
    }
    if(isset($params[$this->name."JQueryLoad"]) && !empty($params[$this->name."JQueryLoad"])){
      $this->SetPreference("touchInlineEdit.".$this->name.".JQueryLoad",$params[$this->name."JQueryLoad"]);
    }
  }

  public function getHeader(){

    $tiePref = $this->GetPrefVars();

    $tieLang = $this->GetLangVars();

    $head = '<!-- '.$this->getName().' module -->' . "\n";

    // nicEdit
    $head.= '<script src="'.$this->pluginDir.'/js/nicEdit.js" type="text/javascript"></script>' . "\n";

    // jQuery
    if((bool)$this->GetPreference("touchInlineEdit.".$this->name.".JQueryLoad")){
      $head.= '<script src="'.$this->pluginDir.'/js/jquery.js" type="text/javascript"></script>' . "\n";
    }

    // touchInlineEdit
    $head.= '<script src="'.$this->pluginDir.'/js/touchInlineEdit.js" type="text/javascript"></script>' . "\n";

    // Script
    $script = '<script type="text/javascript" charset="utf-8">' . "\n";
    $script.= '  var cBlockMain;' . "\n";
    $script.= '  var tieContentId = '.$this->getContentId().';' . "\n";
    $script.= '  var tieRequestUri = "'.$_SERVER["REQUEST_URI"].'";' . "\n";
    $script.= '  var tieUpdateAlert = '.$tiePref['feUpdateAlert'].';' . "\n";
    $script.= '  var tieUpdateAlertMessage = "'.$tieLang['feUpdateAlert'].'";' . "\n";
    $script.= '  var tieFullPanel = '.$this->GetPreference("touchInlineEdit.".$this->name.".FullPanel").';' . "\n";
    $script.= '  var tieIconsPath = "'.$this->pluginDir.'/img/nicEditorIcons.gif"'. "\n";
    $script.= '  var tieEditOnDblClick = '.$tiePref['feEditOnDblClick'].';' . "\n";
    $script.= '</script>' . "\n";
    $script.= '<!-- '.$this->getName().' module -->' . "\n";

    return $head . $script;
  }
}

?>