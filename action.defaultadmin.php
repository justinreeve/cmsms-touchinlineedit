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

if(!isset($gCms)){
  exit;
}

if(!$this->CheckPermission('Modify touchInlineEdit Preferences')){
  echo $this->lang("nopermission");
  return;
}

// Active tab
$activeTab = "";
if(!empty($params["tab"])){
  $activeTab = $params["tab"];
}

// TODO: remove, replace
$yn = array(
  $this->Lang("yes") => 'Y',
  $this->Lang("no") => 'N'
);
// TODO: remove, replace
$bool = array(
  $this->Lang("yes") => 'true',
  $this->Lang("no") => 'false'
);

$yn = array(
  $this->Lang("no") => 0,
  $this->Lang("yes") => 1
);

echo $this->StartTabHeaders();

echo $this->SetTabHeader("settings",$this->Lang("settings"),($activeTab == 'settings' ? true : false));
echo $this->SetTabHeader("permissions",$this->Lang("permissions"),($activeTab == 'permissions' ? true : false));
if($this->CheckPermission('Modify Templates')){
  echo $this->SetTabHeader("templates",$this->Lang("templates"),($activeTab == 'templates' ? true : false));
}
echo $this->SetTabHeader("editor",$this->Lang("editor"),($activeTab == 'editor' ? true : false));

echo $this->EndTabHeaders();

echo $this->StartTabContent();

// Tab settings
echo $this->StartTab("settings");
include(dirname(__FILE__).'/function.admin_settings.php');
echo $this->EndTab();

// Tab permissions
echo $this->StartTab("permissions");
include(dirname(__FILE__).'/function.admin_permissions.php');
echo $this->EndTab();

// Tab templates
if($this->CheckPermission('Modify Templates')){

  echo $this->StartTab("templates");
  include(dirname(__FILE__).'/function.admin_templates.php');
  echo $this->EndTab();

}

// Editor specific settings
if(method_exists($this->getPlugin(),'getAdminConfig')){

  echo $this->StartTab("editor");
  include(dirname(__FILE__).'/function.admin_editor.php');
  echo $this->EndTab();

}

echo $this->EndTabContent();

?>
