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
 * @version 1.4
 * @copyright Christoph Gruber touchDesign.de 04.08.2010
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

if(!$this->CheckPermission('Modify Site Settings')){
	echo $this->lang("nopermission");
	return;
}

if(isset($params["feEditButton"]) && !empty($params["feEditButton"])){
	$this->SetPreference("touchInlineEdit.feEditButton",$params["feEditButton"]);
}
if(isset($params["feFullPanel"]) && !empty($params["feFullPanel"])){
	$this->SetPreference("touchInlineEdit.feFullPanel",$params["feFullPanel"]);
}
if(isset($params["feUpdateAlert"]) && !empty($params["feUpdateAlert"])){
	$this->SetPreference("touchInlineEdit.feUpdateAlert",$params["feUpdateAlert"]);
}
if(isset($params["feJQueryLoad"]) && !empty($params["feJQueryLoad"])){
	$this->SetPreference("touchInlineEdit.feJQueryLoad",$params["feJQueryLoad"]);
}

$this->Redirect($id, 'defaultadmin', '', array("module_message" => $this->Lang("settingssaved"),"tab" => "settings"));

?>