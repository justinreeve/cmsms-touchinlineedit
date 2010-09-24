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
 * @version 1.7
 * @copyright 2010 touchDesign
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

if(!$this->CheckPermission('Use touchInlineEdit')){
	echo $this->lang("nopermission");
	return;
}

// Active tab
$activeTab = "";
if(!empty($params["tab"])){
	$activeTab = $params["tab"];
}

$yn = array(
	$this->Lang("yes") => 'Y',
	$this->Lang("no") => 'N'
);

$bool = array(
	$this->Lang("yes") => 'true',
	$this->Lang("no") => 'false'
);

echo $this->StartTabHeaders();

echo $this->SetTabHeader("settings",$this->Lang("settings"),($activeTab == 'settings' ? true : false));
echo $this->SetTabHeader("templates",$this->Lang("templates"),($activeTab == 'templates' ? true : false));
echo $this->SetTabHeader("editor",$this->Lang("editor"),($activeTab == 'editor' ? true : false));

echo $this->EndTabHeaders();

echo $this->StartTabContent();

// Tab settings
echo $this->StartTab("settings");
include(dirname(__FILE__).'/function.admin_settings.php');
echo $this->EndTab();

// Tab templates
if($this->CheckPermission('Modify Templates')){

	echo $this->StartTab("templates");
	include(dirname(__FILE__).'/function.admin_templates.php');
	echo $this->EndTab();

}

// Editor specific settings
if(method_exists($this->editor,'getAdminConfig')){

	echo $this->StartTab("editor");
	include(dirname(__FILE__).'/function.admin_editor.php');
	echo $this->EndTab();

}

echo $this->EndTabContent();

?>
