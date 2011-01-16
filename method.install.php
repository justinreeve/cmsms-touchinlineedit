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
 * @version 1.8.0
 * @copyright 04.08.2010 touchDesign
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

$smarty->clear_compiled_tpl();
$smarty->clear_all_cache();

$this->CreatePermission('Use touchInlineEdit', 'Use touchInlineEdit');
$this->SetPreference('touchInlineEdit.feEditButton', 1);
$this->SetPreference('touchInlineEdit.feEditButtonText', $this->Lang('feEditButtonText_default'));
$this->SetPreference('touchInlineEdit.feEditOnDblClick', 1);
$this->SetPreference('touchInlineEdit.feUpdateAlertMessage', $this->Lang('feUpdateAlertMessage_default'));
$this->SetPreference('touchInlineEdit.feFEUallow', 0);
$this->SetPreference('touchInlineEdit.feFEUgroups', '');
$this->SetPreference('touchInlineEdit.feAdminAllow', 1);
if(method_exists($this->getPlugin(),'install')){
  $this->getPlugin()->install(TIE_PLUGIN_DEFAULT);
}
$this->SetPreference('touchInlineEdit.fePlugin', TIE_PLUGIN_DEFAULT);
$this->SetTemplate('touchInlineEdit.button', $this->touch->getTemplate('button'));
$this->AddEventHandler('Core', 'ContentPostRender', false);

// Log install info
$this->Audit(0, $this->GetFriendlyName(), $this->Lang('postinstall'));

?>