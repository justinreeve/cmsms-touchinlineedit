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
 * @version 1.5
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

global $gCms;

if(isset($gCms->modules['touchInlineEdit']) 
	&& is_object($gCms->modules['touchInlineEdit']['object'])){

	$touchInlineEdit = $gCms->modules['touchInlineEdit']['object'];
	$smarty = &$gCms->smarty;
	$config = &$gCms->config;

	//Debug
	//$smarty->force_compile = true;

	if(check_login(true) && $this->CheckPermission('Use touchInlineEdit')){
		if($touchInlineEdit->isAJAXRequest()){
			switch ($_POST['method']) {
				case 'updateContent':
					die($touchInlineEdit->updateContent());
				case 'getContent':
					die($touchInlineEdit->getContent());
			}
		}
		// Assign prefs
		$smarty->assign('hasInlineEditRights',1);
		$smarty->assign('tieFeEditButton',$touchInlineEdit->GetPreference("touchInlineEdit.feEditButton"));
		$smarty->assign('tieFeFullPanel',$touchInlineEdit->GetPreference("touchInlineEdit.feFullPanel"));
		$smarty->assign('tieFeUpdateAlert',$touchInlineEdit->GetPreference("touchInlineEdit.feUpdateAlert"));
		$smarty->assign('tieJQueryLoad',$touchInlineEdit->GetPreference("touchInlineEdit.feJQueryLoad"));
		// Assign lang vars
		$lang = array();
		$lang['feInlineEditButton'] = $touchInlineEdit->Lang("feInlineEditButton");
		$lang['feUpdateAlert'] = $touchInlineEdit->Lang("feUpdateAlert");
		$smarty->assign('tieLang',$lang);
		// Process template from Db
		$smarty->assign('tieTemplateEditButton',$this->ProcessTemplateFromDatabase("touchInlineEditButton"));
	}
}

?>