<?php
/**
 * $Id$
 *
 * touchInlineEdit Module
 *
 * Copyright (c) 2010 touchdesign, <www.touchdesign.de>
 *
 * @category Module
 * @author Christin Gruber <www.touchdesign.de>
 * @version 1.8.3
 * @copyright 04.08.2010 touchdesign
 * @link http://www.touchdesign.de/
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

if(!$this->CheckPermission('Modify touchInlineEdit Preferences')){
  echo $this->lang("nopermission");
  return;
}

if(isset($params["feEditButton"])){
  $this->SetPreference("touchInlineEdit.feEditButton",intval($params["feEditButton"]));
}
if(isset($params["feEditOnDblClick"])){
  $this->SetPreference("touchInlineEdit.feEditOnDblClick",intval($params["feEditOnDblClick"]));
}
if(isset($params["feUpdateAlertMessage"])){
  $this->SetPreference("touchInlineEdit.feUpdateAlertMessage",$params["feUpdateAlertMessage"]);
}
if(isset($params["feEditButtonText"])){
  $this->SetPreference("touchInlineEdit.feEditButtonText",$params["feEditButtonText"]);
}
if(isset($params["fePlugin"]) && !empty($params["fePlugin"])){
  $editor = $this->getPlugin($params["fePlugin"]);
  if(method_exists($editor,'install')){
    if($editor->install()){
      $this->getPlugin()->uninstall();
    }
  }
  $this->SetPreference("touchInlineEdit.fePlugin",$params["fePlugin"]);
}

$this->Redirect($id, 'defaultadmin', '', array("module_message" => $this->Lang("settingssaved"),"tab" => "settings"));

?>