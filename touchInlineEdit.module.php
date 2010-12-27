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
 * @version 1.7.4
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

define('TIE_PLUGIN_DIR','plugins/');
define('TIE_PLUGIN_DEFAULT','nicedit');

class touchInlineEdit extends CMSModule {

  public $editor;
  public $smarty;
  private $defaultTemplate = array(
    'touchInlineEditButton' => '<button class="touchInlineEditButton">{$tieLang.feInlineEditButton}</button>'
  );
  private $hasInlineEditRights = null;

  public function __construct($name=NULL){

    $this->smarty = $this->getCMSSmarty();
    //$this->smarty->force_compile = true;
    if(!$name){
      $this->init();
    }
  }

  private function init(){

    $editor = $this->GetPreference("touchInlineEdit.fePlugin",TIE_PLUGIN_DEFAULT);
    if($editor){
      $this->editor = $this->getPluginInstance($editor);
      // Todo: Remove from cunstructor
      if($this->hasInlineEditRights()){
        // Assign vars
        $this->smarty->assign('hasInlineEditRights',1);
        $this->smarty->assign('tieLang',$this->GetLangVars());
        $this->smarty->assign('tiePref',$this->GetPrefVars());
        // Process template
        $this->smarty->assign('tieTemplateEditButton', $this->ProcessTemplateFromDatabase('touchInlineEditButton'));
      }
    }
  }

  public function GetName(){ 
    return 'touchInlineEdit'; 
  }

  public function GetFriendlyName(){ 
    return 'TouchInlineEdit'; 
  }

  public function GetVersion(){ 
    return '1.7.4';
  }

  public function GetHelp(){ 
    return $this->Lang('help');
  }

  public function IsPluginModule(){
    return true;
  }
  
  public function HasAdmin(){
    return true; 
  }

  public function GetAuthor(){
    return 'Christoph Gruber <touchDesign.de>';
  }

  public function GetAuthorEmail(){
    return 'c.gruber@touchdesign.de';
  }

  public function GetAdminSection(){
    return 'extensions';
  }

  public function SetParameters(){    
    /* Nothing yet */
  }

  public function InstallPostMessage(){
    return $this->Lang('postinstall');
  }

  public function UninstallPostMessage(){
    return $this->Lang('postuninstall');
  }

  public function UninstallPreMessage(){
    return $this->Lang('preuninstall');
  }

  public function MinimumCMSVersion(){
    return "1.6.4";
  }

  public function MaximumCMSVersion(){
    return "1.9.2";
  }

  public function HandlesEvents() {
    return true;
  }

  /* ----- Events ----- */

  public function DoEvent( $originator, $eventname, &$params ){

    if ($originator == 'Core' && $eventname == 'ContentPostRender'){
      if($this->hasInlineEditRights()){
        // Before close header
        $params['content'] = str_replace('</head>', $this->editor->getHeader() 
          . '</head>', $params['content']);
      }
    }
    elseif ($originator == 'Core' && $eventname == 'SmartyPreCompile'){
      // TODO: Grep blocks, content underscore blockname -> default is content_en
      //preg_match_all("/\{content.*?block=[\"|']([^\"|']+)[\"|'].*?\}/", $content, $matches);

      // Before content
      $contentBefore = '{if $hasInlineEditRights}';
      $contentBefore.= '  {if $tiePref.feEditButton == "Y"}';
      $contentBefore.= '    {$tieTemplateEditButton}';
      $contentBefore.= '  {/if}';
      $contentBefore.= '  <div id="touchInlineEditId{$content_id}" class="touchInlineEdit">';
      $contentBefore.= '{/if}';

      // After content
      $contentAfter = '{if $hasInlineEditRights}';
      $contentAfter.= '  </div>';
      $contentAfter.= '{/if}';

      // Main content
      $params['content'] = preg_replace("/\{content(.*) iseditable=[\"|']true[\"|']\}/", 
        $contentBefore."{content \\1}".$contentAfter, $params['content']);
    }
  }

  /* ----- Functions ----- */
 
  public function hasInlineEditRights(){

    if(isset($this->hasInlineEditRights)){
      return $this->hasInlineEditRights;
    }

    $this->hasInlineEditRights = false;

    // Support for frontend users
    if($this->GetPreference('touchInlineEdit.feFEUallow')){
      $feu = $this->getModuleInstance('FrontEndUsers');
      if($feu && $feu->LoggedInId()){
        if($this->GetPreference('touchInlineEdit.feFEUgroups')){
          $allowedGroups = explode(',',$this->GetPreference('touchInlineEdit.feFEUgroups'));
          $memberGroups = $feu->GetMemberGroupsArray($feu->LoggedInId());
          foreach($memberGroups as $memberGroup){
            if(in_array($memberGroup['groupid'],$allowedGroups)){
              $this->hasInlineEditRights = true;
            }
          }
        }else{
          $this->hasInlineEditRights = true;
        }
      }
    }

    // Grant admin users
    if($this->GetPreference('touchInlineEdit.feAdminAllow') && check_login(true) 
      && $this->CheckPermission('Use touchInlineEdit')){
      $this->hasInlineEditRights = true;
    }

    return $this->hasInlineEditRights;
  }

  public function isAJAXRequest(){

    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
      && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) 
      == 'xmlhttprequest' ? true : false;
  }

  protected function GetLangVars(){

    $lang = array();

    $lang['feInlineEditButton'] = $this->Lang("feInlineEditButton");
    $lang['feUpdateAlert'] = $this->Lang("feUpdateAlert");

    return $lang;
  }

  protected function getDefaultTemplate($template){

    if(isset($this->defaultTemplate[$template])){
      return $this->defaultTemplate[$template];
    }
    return false;
  }

  protected function getPrefVars(){

    $preferences = array();

    $preferences['feEditButton'] = $this->GetPreference("touchInlineEdit.feEditButton");
    $preferences['feEditOnDblClick'] = $this->GetPreference("touchInlineEdit.feEditOnDblClick");
    $preferences['feUpdateAlert'] = $this->GetPreference("touchInlineEdit.feUpdateAlert");
    $preferences['fePlugin'] = $this->GetPreference("touchInlineEdit.fePlugin");

    return $preferences;
  }

  static public function getCMSModuleInstance($name){

    if(function_exists('cmsms') && method_exists(cmsms(),'GetModuleInstance')){
      return cmsms()->GetModuleInstance($name);
    }else{
      global $gCms;
      return isset($gCms->modules[$name]) ? $gCms->modules[$name]['object'] : false;
    }
  }
  
  public function getCMSSmarty(){

    if(function_exists('cmsms') && method_exists(cmsms(),'getSmarty')){
      return cmsms()->getSmarty();
    }else{
      global $gCms;
      return $gCms->smarty;
    }
  }

  public function getCMSConfig(){

    if(function_exists('cmsms') && method_exists(cmsms(),'getConfig')){
      return cmsms()->getConfig();
    }else{
      global $gCms;
      return $gCms->config;
    }
  }

  /* ----- Content operations ----- */

  public function getContentId(){

    if(function_exists('cmsms') && method_exists(cmsms(),'get_variable')){
      return cmsms()->get_variable('content_id');
    }else{
      global $gCms;
      return $gCms->variables['content_id'];
    }
  }

  private function getContentObj($contentId=NULL){
    global $gCms;

    if($contentId === NULL){
      $contentId = $this->getContentId();
    }

    $manager = &$gCms->GetHierarchyManager();
    $node = &$manager->sureGetNodeById($contentId);

    if(!is_object($node)){
      return "Invalid ContentId: " . $contentId;
    }

    return $node->GetContent(true,true);
  }

  protected function getContent($block="content_en",$fetch=false){

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

  protected function updateContent($block="content_en"){
    global $gCms;

    $contentObj = &$this->getContentObj();

    $contentObj->DoReadyForEdit();

    $params[$block] = $_POST['content'];

    // Fix: Attempt to load admin realm from non admin action notice if alias already used
    // Ref: http://dev.cmsmadesimple.org/bug/view/5805
    @$contentObj->FillParams($params);

    $errors = $contentObj->ValidateData();
    if($errors !== false){
      // TODO: throw errors
      return "Invalid content";
    }

    // Fix: Alias rename
    // Ref: http://dev.cmsmadesimple.org/bug/view/5805
    $contentObj->mAlias = $contentObj->mOldAlias;

    $contentObj->Update();

    // Log update info
    $this->Audit(0, $this->GetFriendlyName(), 
      $this->Lang('postcontentupdate', 
      $this->getContentId()."{".$block."}"));

    return $this->getContent($block,true);
  }

  /* ----- Plugin functions ----- */

  protected function getPlugins(){

    $config = $this->getCMSConfig();

    $plugins = array_diff(scandir($config['root_path'].'/modules/'.$this->getName()
      .'/'.TIE_PLUGIN_DIR),array('.','..','.svn','.htaccess')); 

    return array_combine($plugins,$plugins);
  }

  private function getPluginInstance($plugin, $params = NULL){

    $config = $this->getCMSConfig();

    if(!is_string($plugin) || !strlen($plugin)){
      throw new exception('Unable to load plugin: ' . $plugin);
    }

    require_once $config['root_path'] . '/modules/' . get_class() 
      . '/'.TIE_PLUGIN_DIR  . $plugin . '/' . $plugin . '.plugin.php';

    return new $plugin($params);
  }
}

?>