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

if(!isset($gCms)){ 
  exit;
}

if(!$this->VisibleToAdminUser()){
  $this->ShowErrors($this->Lang("accessdenied"));
  return;
}

// Form start
$this->smarty->assign('formstart',$this->CreateFormStart($id,"savesettings",$returnid));

// Select inline editor
$this->smarty->assign('fePlugin_label',$this->Lang("fePlugin_label"));
$this->smarty->assign('fePlugin_help',$this->Lang("fePlugin_help"));
$this->smarty->assign('fePlugin_input',$this->CreateInputDropdown($id,"fePlugin",$this->getPlugins(),
  "",$this->GetPreference("touchInlineEdit.fePlugin"),"\n"));

// Enable disable inlineEdit button in FE
$this->smarty->assign('feEditButton_label',$this->Lang("feEditButton_label"));
$this->smarty->assign('feEditButton_help',$this->Lang("feEditButton_help"));
$this->smarty->assign('feEditButton_input',$this->CreateInputDropdown($id,"feEditButton",
  $yn,$this->GetPreference("touchInlineEdit.feEditButton","Y"),"","\n"));

// Set edit button text
$this->smarty->assign('feEditButtonText_label',$this->Lang("feEditButtonText_label"));
$this->smarty->assign('feEditButtonText_help',$this->Lang("feEditButtonText_help"));
$this->smarty->assign('feEditButtonText_input',$this->CreateInputText($id,"feEditButtonText",
  $this->GetPreference("touchInlineEdit.feEditButtonText","EditMe"),"","\n"));
  
// Enable disable inlineEdit on double click
$this->smarty->assign('feEditOnDblClick_label',$this->Lang("feEditOnDblClick_label"));
$this->smarty->assign('feEditOnDblClick_help',$this->Lang("feEditOnDblClick_help"));
$this->smarty->assign('feEditOnDblClick_input',$this->CreateInputDropdown($id,"feEditOnDblClick",
  $yn,$this->GetPreference("touchInlineEdit.feEditOnDblClick","true"),"","\n"));

// Set update alert message content update
$this->smarty->assign('feUpdateAlertMessage_label',$this->Lang("feUpdateAlertMessage_label"));
$this->smarty->assign('feUpdateAlertMessage_help',$this->Lang("feUpdateAlertMessage_help"));
$this->smarty->assign('feUpdateAlertMessage_input',$this->CreateInputText($id,"feUpdateAlertMessage",
  $this->GetPreference("touchInlineEdit.feUpdateAlertMessage","Success..."),"","\n"));
  
// Submit / cancel
$this->smarty->assign('submit',$this->CreateInputSubmit($id,"submit",$this->Lang("save")
  ,"","",$this->Lang("settingsconfirm",$this->GetPreference("touchInlineEdit.fePlugin"))));
$this->smarty->assign('cancel',$this->CreateInputSubmit($id,"cancel",$this->Lang("cancel")));

// Form end
$this->smarty->assign('formend',$this->CreateFormEnd());

echo $this->ProcessTemplate("adminsettings.tpl");
echo $this->ProcessTemplate("ressources.tpl");

?>