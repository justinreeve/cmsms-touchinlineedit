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

if(!isset($gCms)){ 
  exit;
}

if(!$this->VisibleToAdminUser()){
  $this->ShowErrors($this->Lang("accessdenied"));
  return;
}

// Form start
$this->smarty->assign('formstart',$this->CreateFormStart($id,"savepermissions",$returnid));

// Grant admins
$this->smarty->assign('feAdminAllow_label',$this->Lang("feAdminAllow_label"));
$this->smarty->assign('feAdminAllow_help',$this->Lang("feAdminAllow_help"));
$this->smarty->assign('feAdminAllow_input',$this->CreateInputRadioGroup($id,"feAdminAllow",
  $yesno,$this->GetPreference("touchInlineEdit.feAdminAllow",1),"","\n"));

// FEU support
if($this->getModuleInstance('FrontEndUsers')){
  // FEU allow
  $this->smarty->assign('feFEUallow_label',$this->Lang("feFEUallow_label"));
  $this->smarty->assign('feFEUallow_help',$this->Lang("feFEUallow_help"));
  $this->smarty->assign('feFEUallow_input',$this->CreateInputRadioGroup($id,"feFEUallow",
    $yesno,$this->GetPreference("touchInlineEdit.feFEUallow","false"),"","\n"));

  // FEU groups
  $this->smarty->assign('feFEUgroups_label',$this->Lang("feFEUgroups_label"));
  $this->smarty->assign('feFEUgroups_help',$this->Lang("feFEUgroups_help"));
  $this->smarty->assign('feFEUgroups_input',$this->CreateInputSelectList($id,'feFEUgroups[]',
    $this->getModuleInstance('FrontEndUsers')->GetGrouplist(),explode(',',$this->GetPreference("touchInlineEdit.feFEUgroups",""))));
}else{
  // FEU disabled
  $this->smarty->assign('feFEUdisabled_label',$this->Lang("feFEUdisabled_label"));
  $this->smarty->assign('feFEUdisabled_help',$this->Lang("feFEUdisabled_help"));
}

// Submit / cancel
$this->smarty->assign('submit',$this->CreateInputSubmit($id,"submit",$this->Lang("save")));
$this->smarty->assign('cancel',$this->CreateInputSubmit($id,"cancel",$this->Lang("cancel")));

// Form end
$this->smarty->assign('formend',$this->CreateFormEnd());

echo $this->ProcessTemplate("adminpermissions.tpl");

?>