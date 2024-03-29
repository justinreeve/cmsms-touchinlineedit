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

$this->smarty->clear_compiled_tpl();
$this->smarty->clear_all_cache();

if(version_compare($oldversion, '1.6', '<')){
  $this->SetPreference('touchInlineEdit.feContextMenu', 'false');
  $this->SetPreference('touchInlineEdit.feEditOnDblClick', 'true');
  $this->AddEventHandler('Core', 'ContentPostRender', false);
  $this->AddEventHandler('Core', 'SmartyPreCompile', false);
  $this->SetTemplate('touchInlineEditContextMenu', $this->touch->getTemplate('touchInlineEditContextMenu'));
}
if(version_compare($oldversion, '1.7', '<')){
  $this->SetPreference('touchInlineEdit.fePlugin', 'nicedit');
  if(method_exists($this->getPlugin(),'install')){
    $this->getPlugin()->install();
  }
  $this->DeleteTemplate('touchInlineEditContextMenu');
  $this->RemovePreference('touchInlineEdit.feContextMenu');
  $this->RemovePreference('touchInlineEdit.feFullPanel');
  $this->RemovePreference('touchInlineEdit.feJQueryLoad');
}
if(version_compare($oldversion, '1.7.4', '<')){
  $this->SetPreference('touchInlineEdit.feFEUallow', 0);
  $this->SetPreference('touchInlineEdit.feFEUgroups', '');
  $this->SetPreference('touchInlineEdit.feAdminAllow', 1);
  
  $this->SetPreference('touchInlineEdit.feEditButton', 
    $this->GetPreference('touchInlineEdit.feEditButton') == 'true' ? 1 : 0);
  $this->SetPreference('touchInlineEdit.feEditOnDblClick',
    $this->GetPreference('touchInlineEdit.feEditOnDblClick') == 'true' ? 1 : 0);
  $this->SetPreference('touchInlineEdit.feUpdateAlert',
    $this->GetPreference('touchInlineEdit.feUpdateAlert') == 'true' ? 1 : 0);
  
  if(method_exists($this->getPlugin(),'install')){
    $this->getPlugin()->install();
  }

  $this->RemoveEventHandler('Core', 'ContentPostRender');
  $this->RemoveEventHandler('Core', 'SmartyPreCompile');
}
if(version_compare($oldversion, '1.8.0', '<')){
  $this->DeleteTemplate('touchInlineEditButton');
  $this->SetTemplate('touchInlineEdit.button', $this->touch->getTemplate('button'));
  $this->RemovePreference('touchInlineEdit.feUpdateAlert');
  $this->SetPreference('touchInlineEdit.feEditButtonText', $this->Lang('feEditButtonText_default'));
  $this->SetPreference('touchInlineEdit.feUpdateAlertMessage', $this->Lang('feUpdateAlertMessage_default'));
  $this->AddEventHandler('Core', 'ContentPostRender', false);
}
if(version_compare($oldversion, '1.8.2', '<')){
  $this->CreatePermission('Modify touchInlineEdit Preferences', 'Modify touchInlineEdit Preferences');
}
// Log upgrade info
$this->Audit(0, $this->GetFriendlyName(), 
  $this->Lang('postupgrade', $this->GetVersion()));

?>