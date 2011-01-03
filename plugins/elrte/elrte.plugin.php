<?php
/**
 * $Id$
 *
 * touchInlineEdit elrte plugin
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

class elrte extends touchInlineEdit {

  var $name = 'elrte';
  var $pluginDir;

  function __construct(){

    $this->pluginDir = 'modules/' . $this->getName() . '/' 
      . TIE_PLUGIN_DIR . $this->name;

    parent::__construct($this->name);
  }

  public function install(){
    $this->SetPreference("touchInlineEdit.".$this->name.".Toolbar","compact");
    $this->SetPreference("touchInlineEdit.".$this->name.".JQueryLoad",1);
  }

  private function fetch($template){

    $config = $this->getCMSConfig();

    return $this->smarty->fetch($config['root_path'] . '/'
      . $this->pluginDir
      . '/templates/' . $template);
  }

  public function getAdminConfig($id,$returnid){

    $yn = array(
      $this->Lang("no") => 0,
      $this->Lang("yes") => 1
    );
    
    // Form start
    $this->smarty->assign('formstart',$this->CreateFormStart($id,"saveeditor",$returnid));

    $toolbars = array(
      'tiny','compact','normal','complete','maxi'
    );

    // Enable full panel in FE
    $this->smarty->assign($this->name.'Toolbar_label',$this->Lang($this->name."Toolbar_label"));
    $this->smarty->assign($this->name.'Toolbar_help',$this->Lang($this->name."Toolbar_help"));
    $this->smarty->assign($this->name.'Toolbar_input',$this->CreateInputDropdown($id,$this->name."Toolbar",
      array_combine($toolbars,$toolbars),"",$this->GetPreference("touchInlineEdit.".$this->name.".Toolbar","compact"),"\n"));

    // jquery lib
    $this->smarty->assign($this->name.'JQueryLoad_label',$this->Lang($this->name."JQueryLoad_label"));
    $this->smarty->assign($this->name.'JQueryLoad_help',$this->Lang($this->name."JQueryLoad_help"));
    $this->smarty->assign($this->name.'JQueryLoad_input',$this->CreateInputDropdown($id,$this->name."JQueryLoad",
      $yn,$this->GetPreference("touchInlineEdit.".$this->name.".JQueryLoad",1),"","\n"));

    // Submit / cancel
    $this->smarty->assign('submit',$this->CreateInputSubmit($id,"submit",$this->Lang("save")));
    $this->smarty->assign('cancel',$this->CreateInputSubmit($id,"cancel",$this->Lang("cancel")));

    // Form end
    $this->smarty->assign('formend',$this->CreateFormEnd());

    echo $this->fetch("admineditor.tpl");
  }

  public function saveAdminConfig($params){

    if(isset($params[$this->name."Toolbar"])){
      $this->SetPreference("touchInlineEdit.".$this->name.".Toolbar",$params[$this->name."Toolbar"]);
    }
    if(isset($params[$this->name."JQueryLoad"])){
      $this->SetPreference("touchInlineEdit.".$this->name.".JQueryLoad",intval($params[$this->name."JQueryLoad"]));
    }
  }

  public function getHeader(){

    $tiePref = $this->GetPrefVars();

    $tieLang = $this->GetLangVars();

    $head = '<!-- '.$this->getName().' module -->' . "\n";

    // jQuery
    if($this->GetPreference("touchInlineEdit.".$this->name.".JQueryLoad")){
      $head.= '<script src="'.$this->pluginDir.'/js/jquery-1.4.2.min.js" type="text/javascript"></script>' . "\n";
      $head.= '<script src="'.$this->pluginDir.'/js/jquery-ui-1.8.5.custom.min.js" type="text/javascript"></script>' . "\n";
      $head.= '<link rel="stylesheet" href="'.$this->pluginDir.'/js/ui-themes/smoothness/jquery-ui-1.8.5.custom.css" type="text/css" media="screen" charset="utf-8" />';
    }

    // elrte
    $head.= '<script src="'.$this->pluginDir.'/js/elrte.min.js" type="text/javascript"></script>' . "\n";
    $head.= '<script src="'.$this->pluginDir.'/js/i18n/elrte.ru.js" type="text/javascript"></script>' . "\n";
    $head.= '<link rel="stylesheet" href="'.$this->pluginDir.'/css/elrte.full.css" type="text/css" media="screen" charset="utf-8" />';

    // touchInlineEdit
    $head.= '<script src="'.$this->pluginDir.'/js/touchInlineEdit.js" type="text/javascript"></script>' . "\n";

    // Script
    $script = '<script type="text/javascript" charset="utf-8">' . "\n";
    $script.= '  var cBlockMain;' . "\n";
    $script.= '  var tieContentId = '.$this->getContentId().';' . "\n";
    $script.= '  var tieRequestUri = "'.$_SERVER["REQUEST_URI"].'";' . "\n";
    $script.= '  var tieUpdateAlert = '.$tiePref['feUpdateAlert'].';' . "\n";
    $script.= '  var tieUpdateAlertMessage = "'.$tieLang['feUpdateAlert'].'";' . "\n";
    $script.= '  var tieToolbar = "'.$this->GetPreference("touchInlineEdit.".$this->name.".Toolbar").'";' . "\n";
    $script.= '  var tieEditOnDblClick = '.$tiePref['feEditOnDblClick'].';' . "\n";
    $script.= '</script>' . "\n";
    $script.= '<!-- '.$this->getName().' module -->' . "\n";

    return $head . $script;
  }
}

?>