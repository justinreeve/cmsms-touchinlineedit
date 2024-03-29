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

if(!$this->VisibleToAdminUser()){
  $this->ShowErrors($this->Lang("accessdenied"));
  return;
}

// Form start
$this->smarty->assign('formstart',$this->CreateFormStart($id,"savetemplate",$returnid));

$this->smarty->assign('template',$this->CreateInputHidden($id,"template",$params['template']));

$this->smarty->assign('template_name',$params['template']);
$this->smarty->assign('template_content',$this->CreateTextArea(false,$id,$this->GetTemplate($params['template']),
  'template_content',"pagesmalltextarea","","","",'40','5'));

// Submit / cancel
$this->smarty->assign('submit',$this->CreateInputSubmit($id,"submit",$this->Lang("save")));
$this->smarty->assign('reset',$this->CreateInputSubmit($id,"reset",$this->Lang("reset")));

// Form end
$this->smarty->assign('formend',$this->CreateFormEnd());

echo $this->ProcessTemplate("adminedittemplate.tpl");

?>