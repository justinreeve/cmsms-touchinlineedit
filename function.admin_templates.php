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
 * @version 1.6
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
$this->smarty->assign('formstart',$this->CreateFormStart($id,"savetemplates",$returnid));

$this->smarty->assign('touchInlineEditButton_label',$this->Lang("touchInlineEditButton_label"));
$this->smarty->assign('touchInlineEditButton', $this->CreateTextArea(false,$id, $this->GetTemplate('touchInlineEditButton'),
	'touchInlineEditButton',"pagesmalltextarea","","","",'40','5'));

// Submit / cancel
$this->smarty->assign('submit',$this->CreateInputSubmit($id,"submit",$this->Lang("save")));
$this->smarty->assign('reset',$this->CreateInputSubmit($id,"reset",$this->Lang("reset")));

// Form end
$this->smarty->assign('formend',$this->CreateFormEnd());

echo $this->ProcessTemplate("admintemplates.tpl");

?>