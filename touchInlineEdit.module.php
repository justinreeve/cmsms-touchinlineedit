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
 * @version 1.3
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

	var $defaultTemplate = array(
		'touchInlineEditButton' => '<button class="touchInlineEditButton">{$tieLang.feInlineEditButton}</button>'
	);

	function GetName(){ 
		return 'touchInlineEdit'; 
	}

	function GetFriendlyName(){ 
		return 'InlineEdit'; 
	}

	function GetVersion(){ 
		return '1.3';
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

	function MinimumCMSVersion(){
		return "1.6.8";
	}

	function SmartyPreCompile(&$content){
		global $gCms;

		// nicEdit
		$head = '{if $hasInlineEditRights}' . "\n";
		$head.= '	<!-- '.$this->getName().' module -->' . "\n";
		$head.= '	<script src="modules/'.$this->getName().'/js/nicEdit.js" type="text/javascript"></script>' . "\n";
		$head.= '	{if $tieJQueryLoad == "Y"}' . "\n";
		$head.= '		<script src="modules/'.$this->getName().'/js/jquery.js" type="text/javascript"></script>' . "\n";
		$head.= '	{/if}' . "\n";
		$head.= '{/if}' . "\n";

		$script = "
		{if \$hasInlineEditRights}
			{literal}
			<!-- ".$this->getName()." module -->
			<script type='text/javascript' charset='utf-8'>

			{/literal}
			var cBlockMain;
			var contentId = {\$gCms->variables.content_id};
			var requestUri = '{\$smarty.server.REQUEST_URI}';
			var updateAlert = '{\$tieFeUpdateAlert}';
			var updateAlertMessage = '{\$tieLang.feUpdateAlert}';
			{literal}

			function touchInlineEditInitEditor() {
				cBlockMain = new nicEditor({
					fullPanel : {/literal}{\$tieFeFullPanel}{literal},
					iconsPath : 'modules/".$this->getName()."/img/nicEditorIcons.gif',
					onSave:function(content,id,instance){touchInlineEditSave(id,content)}
				}).panelInstance(
					'touchInlineEditId' + contentId,
					{hasPanel : true}
				);
			}

			function touchInlineEditRemoveEditor() {
				cBlockMain.removeInstance('touchInlineEditId' + contentId);
				cBlockMain = null;
			}

			function toggleInlineEdit() {
				if(!cBlockMain) {
					touchInlineEditInitContent();
					touchInlineEditInitEditor();
				} else {
					touchInlineEditRemoveEditor();
				}
			}

			function touchInlineEditInitContent(id){
				$.ajax({async:false,
					type: 'POST',
					url: requestUri,
					data: 'method=getContent&id=' + id,
					success:function(data){
						$('#touchInlineEditId' + contentId).html(data);
				}
				});
			}

			function touchInlineEditSave(id,content){
				$.ajax({
					type: 'POST',
					url: requestUri,
					data: 'method=updateContent&id=' + contentId + '&content=' + content,
					success: function(msg){
						if(updateAlert == 'true'){
							alert(updateAlertMessage);
						}
						toggleInlineEdit();
					}
				});
			}

			$(document).ready(function(){
				$('.touchInlineEditButton').click(function(){
					toggleInlineEdit();
					return false;
				});
				$('.touchInlineEdit').dblclick(function(){
					toggleInlineEdit();
					return false;
				});
			});

			</script>
			{/literal}
		{/if}";

		$content = str_replace('</head>',$head . $script . '</head>',$content);

		// TODO: Grep blocks, content underscore blockname -> default is content_en
		//if(preg_match("/block=[\"|']\w+[\"|']/", $content, $match)){
		//	$block = $match{0};
		//}

		// Before content
		$contentBefore = '{if $hasInlineEditRights}';
		$contentBefore.= '	{if $tieFeEditButton == "Y"}';
		$contentBefore.= '		{$tieTemplateEditButton}';
		$contentBefore.= '	{/if}';
		$contentBefore.= '<div id="touchInlineEditId{\$gCms->variables.content_id}" class="touchInlineEdit">';
		$contentBefore.= '{/if}';

		// After content
		$contentAfter = '{if $hasInlineEditRights}';
		$contentAfter.= '</div>';
		$contentAfter.= '{/if}';

		// Basic content
		$content = preg_replace("/\{content(.*) iseditable=[\"|']true[\"']\}/", $contentBefore."{content \\1}".$contentAfter, $content);
	}

	function isAJAXRequest(){

		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) 
			== 'xmlhttprequest' ? true : false;
	}

	function getContent($block="content_en"){
		global $gCms;

		$pageInfo = $gCms->variables['pageinfo'];

		$contentId = $pageInfo->content_id;

		$manager =& $gCms->GetHierarchyManager();
		$node =& $manager->sureGetNodeById($contentId);

		if(!is_object($node)){
			return "Invalid ContentId: " . $contentId;
		}

		$contentObj =& $node->GetContent(true,true);

		$content = "Empty...";
		if($contentObj->HasProperty($block)){
			$content = $contentObj->GetPropertyValue($block);
		}

		return $content;
	}

	function updateContent($block="content_en"){
		global $gCms;

		$pageInfo = $gCms->variables['pageinfo'];

		$contentId = $pageInfo->content_id;
		$contentValue = $_POST['content'];

		$manager =& $gCms->GetHierarchyManager();
		$node =& $manager->sureGetNodeById($contentId);

		if(!is_object($node)){
			return "Invalid ContentId: " . $contentId;
		}

		$contentObj =& $node->GetContent(true,true);

		$params[$block] = $contentValue;

		$contentObj->FillParams($params);

		$contentObj->Update();

		return $contentValue;
	}

	function getDefaultTemplate($template){
		if(isset($this->defaultTemplate[$template])){
			return $this->defaultTemplate[$template];
		}
		return false;
	}
}

?>