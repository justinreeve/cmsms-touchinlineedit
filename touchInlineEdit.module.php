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

class touchInlineEdit extends CMSModule {

	public $smarty;
	private $defaultTemplate = array(
		'touchInlineEditButton' => '<button class="touchInlineEditButton">{$tieLang.feInlineEditButton}</button>',
		'touchInlineEditContextMenu' => '<ul id="touchInlineEditContextMenu" class="contextMenu">
			<li class="edit"><a href="#edit">{$tieLang.feCmSwitchToBackend}</a></li>
			<li class="delete"><a href="#delete">{$tieLang.feCmDelete}</a></li>
			<li class="cut separator"><a href="#cut">{$tieLang.feCmCut}</a></li>
			<li class="copy"><a href="#copy">{$tieLang.feCmCopy}</a></li>
			<li class="paste"><a href="#paste">{$tieLang.feCmPaste}</a></li>
			<li class="settings separator"><a href="#modsettings">{$tieLang.feCmModSettings}</a></li>
		</ul>',
	);

	function __construct(){
		global $gCms;

		$this->smarty = &$gCms->smarty;

		// Debug
		//$this->smarty->force_compile = true;

		if($this->hasInlineEditRights()){
			// Assign vars
			$this->smarty->assign('hasInlineEditRights',1);
			$this->smarty->assign('tieLang',$this->GetLangVars());
			$this->smarty->assign('tiePref',$this->GetPrefVars());
			// Process template
			$this->smarty->assign('tieTemplateEditButton', $this->ProcessTemplateFromDatabase('touchInlineEditButton'));
		}
	}

	function GetName(){ 
		return 'touchInlineEdit'; 
	}

	function GetFriendlyName(){ 
		return 'InlineEdit'; 
	}

	function GetVersion(){ 
		return '1.6';
	}

	function GetHelp(){ 
		return $this->Lang('help');
	}

	function IsPluginModule(){
		return true;
	}
	
	function HasAdmin(){
		return true; 
	}

	function GetAuthor(){
		return 'Christoph Gruber <touchDesign.de>';
	}

	function GetAuthorEmail(){
		return 'c.gruber@touchdesign.de';
	}

	function GetAdminSection(){
		return 'extensions';
	}

	function SetParameters(){	  
		/* Nothing yet */
	}

	function InstallPostMessage(){
		return $this->Lang('postinstall');
	}
	
	function UninstallPostMessage(){
		return $this->Lang('postuninstall');
	}
	
	function UninstallPreMessage(){
		return $this->Lang('preuninstall');
	}

	function MinimumCMSVersion(){
		return "1.6.8";
	}
	
	function HandlesEvents() {
		return true;
	}
	
	function GetLangVars(){

		$lang = array();
		
		$lang['feInlineEditButton'] = $this->Lang("feInlineEditButton");
		$lang['feUpdateAlert'] = $this->Lang("feUpdateAlert");

		$lang['feCmSwitchToBackend'] = $this->Lang("feCmSwitchToBackend");
		$lang['feCmDelete'] = $this->Lang("feCmDelete");
		$lang['feCmCut'] = $this->Lang("feCmCut");
		$lang['feCmCopy'] = $this->Lang("feCmCopy");
		$lang['feCmPaste'] = $this->Lang("feCmPaste");
		$lang['feCmModSettings'] = $this->Lang("feCmModSettings");

		return $lang;
	}
		
	function DoEvent( $originator, $eventname, &$params ){
		global $gCms;

		if ($originator == 'Core' && $eventname == 'ContentPostRender'){
			if($this->hasInlineEditRights()){
				// Before close header
				$params['content'] = str_replace('</head>', $this->GetJavascripts() 
					. '</head>', $params['content']);
				// Before close body
				$params['content'] = str_replace('</body>', $this->ProcessTemplateFromDatabase('touchInlineEditContextMenu') 
					. '</body>', $params['content']);
			}
		}
		elseif ($originator == 'Core' && $eventname == 'SmartyPreCompile'){
			// TODO: Grep blocks, content underscore blockname -> default is content_en
			//preg_match_all("/\{content.*?block=[\"|']([^\"|']+)[\"|'].*?\}/", $content, $matches);

			// Before content
			$contentBefore = '{if $hasInlineEditRights}';
			$contentBefore.= '	{if $tiePref.feEditButton == "Y"}';
			$contentBefore.= '		{$tieTemplateEditButton}';
			$contentBefore.= '	{/if}';
			$contentBefore.= '	<div id="touchInlineEditId{$gCms->variables.content_id}" class="touchInlineEdit">';
			$contentBefore.= '{/if}';

			// After content
			$contentAfter = '{if $hasInlineEditRights}';
			$contentAfter.= '	</div>';
			$contentAfter.= '{/if}';

			// Main content
			$params['content'] = preg_replace("/\{content(.*) iseditable=[\"|']true[\"|']\}/", 
				$contentBefore."{content \\1}".$contentAfter, $params['content']);
		}
	}
	
	public function hasInlineEditRights(){

		if(check_login(true) && $this->CheckPermission('Use touchInlineEdit')){
			return true;
		}
		return false;
	}

	public function isAJAXRequest(){

		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) 
			== 'xmlhttprequest' ? true : false;
	}

	private function getContentObj($contentId=NULL){
		global $gCms;

		if($contentId === NULL){
			$pageInfo = &$gCms->variables['pageinfo'];
			$contentId = $pageInfo->content_id;
		}

		$manager = &$gCms->GetHierarchyManager();
		$node = &$manager->sureGetNodeById($contentId);

		if(!is_object($node)){
			return "Invalid ContentId: " . $contentId;
		}
		
		return $node->GetContent(true,true);
	}

	public function getContent($block="content_en",$fetch=false){

		$contentObj = &$this->getContentObj();

		$content = "Empty...";
		if($contentObj->HasProperty($block)){
			$content = $contentObj->GetPropertyValue($block);
			if($fetch){
				// TODO: clear modified template clear_compiled_tpl('content: ...')
				$this->smarty->clear_compiled_tpl();
				// Fetch content...
				$content = $this->smarty->fetch('content:' . $block, '', $contentId);
			}
		}

		return $content;
	}

	public function updateContent($block="content_en"){
		global $gCms;

		$contentObj = &$this->getContentObj();

		$params[$block] = $_POST['content'];

		$contentObj->FillParams($params);

		$errors = $contentObj->ValidateData();
		if($errors !== false){
			// TODO: throw errors
			return "Invalid content";
		}

		$contentObj->Update();

		// Log update info
		$this->Audit(0, $this->GetFriendlyName(), 
			$this->Lang('postcontentupdate', $contentId));

		return $this->getContent($block,true);
	}

	public function getDefaultTemplate($template){

		if(isset($this->defaultTemplate[$template])){
			return $this->defaultTemplate[$template];
		}
		return false;
	}

	private function getPrefVars(){

		$preferences = array();

		$preferences['feEditButton'] = $this->GetPreference("touchInlineEdit.feEditButton");
		$preferences['feEditOnDblClick'] = $this->GetPreference("touchInlineEdit.feEditOnDblClick");
		$preferences['feFullPanel'] = $this->GetPreference("touchInlineEdit.feFullPanel");
		$preferences['feUpdateAlert'] = $this->GetPreference("touchInlineEdit.feUpdateAlert");
		$preferences['jQueryLoad'] = $this->GetPreference("touchInlineEdit.feJQueryLoad");
		$preferences['feContextMenu'] = $this->GetPreference("touchInlineEdit.feContextMenu");

		return $preferences;
	}

	private function getJavascripts(){
		global $gCms;

		$tiePref = $this->GetPrefVars();
		$tieLang = $this->GetLangVars();

		// nicEdit
		$head = '<!-- '.$this->getName().' module -->' . "\n";
		$head.= '<script src="modules/'.$this->getName().'/js/nicEdit.js" type="text/javascript"></script>' . "\n";

		// jQuery
		if($tiePref['jQueryLoad'] == "Y"){
			$head.= '<script src="modules/'.$this->getName().'/js/jquery.js" type="text/javascript"></script>' . "\n";
		}

		// touchInlineEdit
		$head.= '<script src="modules/'.$this->getName().'/js/touchInlineEdit.js" type="text/javascript"></script>' . "\n";

		// jQuery context menu
		if((bool)$tiePref['feContextMenu']){
			$head.= '<script src="modules/'.$this->getName().'/js/jquery.contextMenu.js" type="text/javascript"></script>' . "\n";
			$head.= '<link href="modules/'.$this->getName().'/css/jquery.contextMenu.css" rel="stylesheet" type="text/css" />' . "\n";
		}

		// Script
		$script = '<script type="text/javascript" charset="utf-8">' . "\n";
		$script.= '	var cBlockMain;' . "\n";
		$script.= '	var tieContentId = '.$gCms->variables['content_id'].';' . "\n";
		$script.= '	var tieRequestUri = "'.$_SERVER["REQUEST_URI"].'";' . "\n";
		$script.= '	var tieUpdateAlert = '.$tiePref['feUpdateAlert'].';' . "\n";
		$script.= '	var tieUpdateAlertMessage = "'.$tieLang['feUpdateAlert'].'";' . "\n";
		$script.= '	var tieFullPanel = '.$tiePref['feFullPanel'].';' . "\n";
		$script.= '	var tieContextMenu = '.$tiePref['feContextMenu'].';' . "\n";
		$script.= '	var tieSecureKey = "'.$_SESSION[CMS_USER_KEY].'";' . "\n";
		$script.= '	var tieSecureKeyName = "'.CMS_SECURE_PARAM_NAME.'";' . "\n";
		$script.= '	var tieAdminDir = "'.$gCms->config['admin_dir'].'";' . "\n";
		$script.= '	var tieEditOnDblClick = '.$tiePref['feEditOnDblClick'].';' . "\n";
		$script.= '</script>' . "\n";
		$script.= '<!-- '.$this->getName().' module -->' . "\n";

		return $head . $script;
	}
}

?>