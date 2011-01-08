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

define('TIE_PLUGIN_DIR','plugins/');
define('TIE_PLUGIN_DEFAULT','tiny_mce');

global $gCms;
require_once cms_join_path($gCms->config['root_path'],'modules',
  'touchInlineEdit','lib','class.touchModule.php');

class touchInlineEdit extends CMSModule {

  /**
   * Plugin instance only for template calls (smarty).
   * @var object
   * @access public
   */
  public $editor;
  
  /**
   * Smarty reference.
   * @var object
   * @access public
   */
  public $smarty;
  
  /**
   * TouchModule object.
   * @var object
   * @access public
   */
  public $touch;
  
  /**
   * Templates for this module.
   * @var array
   * @access public
   */
  private $templates = array(
    'button' => '<button class="touchInlineEditButton">{$tie->touch->get(\'feEditButtonText\')}</button>'
  );
  
  /**
   * Constant has inline edit rights.
   * @var true,false,null
   * @access public
   */
  const ENABLED = null;
  
  public function __construct(){

    parent::__construct();
    
    $this->touch = new touchModule($this);
    $this->smarty = $this->touch->cmsms('smarty');
    $this->smarty->force_compile = true;
    
    // TODO: Check if this the right place?
    $this->init();

  }

  public function init(){

    if($this->isEnabled()){
      $this->smarty->assign('tie',&$this);
      $this->smarty->register_prefilter(array($this,'smartyPreCompile'));
    }
   
  }

  public function GetName(){ 
    return 'touchInlineEdit'; 
  }

  public function GetFriendlyName(){ 
    return 'TouchInlineEdit'; 
  }

  public function GetVersion(){ 
    return '1.8.0';
  }

  public function GetHelp(){
    $config = $this->touch->cmsms('config');
    
    // Get help string
    $html = $this->Lang('help');
    
    // Append included README
    $html.= '<h3>README</h3>';
    $html.= '<pre>';
    $html.= file_get_contents($config['root_path'] 
      . '/modules/' . $this->getName() . '/README');
    $html.= '</pre>';

    return $html;
  }
  
  function GetAdminDescription(){
    return $this->Lang('admdescription');
  }
  
  function GetChangeLog(){
    $config = $this->getConfig();
    
    $html = '<pre>';
    $html.= file_get_contents($config['root_path'] 
      . '/modules/' . $this->getName() . '/CHANGELOG');
    $html.= '</pre>';
    
    return $html;
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

  function smartyPreCompile($templateSource, &$smarty=null){

    if($smarty === null){
      return $templateSource;
    }
    
    $result = explode(':', $smarty->_current_file);

    // Only type:content,block:content_en yet
    // TODO: Support for multiple blocks and editors
    if($result[0] == 'content' && $result[1] == 'content_en'){

      if($this->touch->isAjaxRequest()){
        return $templateSource;
      }

      // Before content
      $contentBefore = '{if $tie->isEnabled()}';
      $contentBefore.= '  {if $tie->touch->get(\'feEditButton\')}';
      $contentBefore.= '    {$tie->touch->fetch(\'button\',true)}';
      $contentBefore.= '  {/if}';
      $contentBefore.= '  <div id="touchInlineEditId{$content_id}" class="touchInlineEdit">';
      $contentBefore.= '{/if}';

      // After content
      $contentAfter = '{if $tie->isEnabled()}';
      $contentAfter.= '  </div>';
      $contentAfter.= '{/if}';

      return $contentBefore . $templateSource . $contentAfter;

    }

    return $templateSource;
    
  }

  public function DoEvent( $originator, $eventname, &$params ){
    if($originator == 'Core' && $eventname == 'ContentPostRender'){
      if($this->isEnabled()){
        // Before close header
        $params['content'] = str_replace('</head>', $this->getPlugin()->getHeader() 
          . '</head>', $params['content']);
      }
    }
  }

  /* ----- Functions ----- */
 
  public function isEnabled(){

    if(isset($this->ENABLED)){
      return $this->ENABLED;
    }
    
    $this->ENABLED = false;

    // Support for frontend users
    if($this->touch->get('feFEUallow')){
      $feu = $this->getModuleInstance('FrontEndUsers');
      if($feu && $feu->loggedInId()){
        if($this->touch->get('feFEUgroups')){
          $allowedGroups = explode(',',$this->touch->get('feFEUgroups'));
          $memberGroups = $feu->getMemberGroupsArray($feu->loggedInId());
          foreach($memberGroups as $memberGroup){
            if(in_array($memberGroup['groupid'],$allowedGroups)){
              $this->ENABLED = true;
            }
          }
        }else{
          $this->ENABLED = true;
        }
      }
    }

    // Grant admin users
    if($this->touch->get('feAdminAllow') && check_login(true) 
      && $this->checkPermission('Use touchInlineEdit')){
      $this->ENABLED = true;
    }

    return $this->ENABLED;
  }

  protected function getDefaultTemplate($template){

    if(isset($this->templates[$template])){
      return $this->templates[$template];
    }
    return false;
  }

  static public function getCMSModuleInstance($name){

    if(function_exists('cmsms') && method_exists(cmsms(),'GetModuleInstance')){
      return cmsms()->GetModuleInstance($name);
    }else{
      global $gCms;
      return isset($gCms->modules[$name]) ? $gCms->modules[$name]['object'] : false;
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

  private function getContentObj($contentId=null){
    global $gCms;

    if($contentId === null){
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

    $contentObj = &$this->getContentObj();

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
  
  public function getRequestUri(){
    return $_SERVER["REQUEST_URI"];
  }
  
  /* ----- Plugin functions ----- */

  protected function getPlugins(){

    $config = $this->touch->cmsms('config');

    $availablePlugins = array_diff(scandir($config['root_path'] 
      . '/modules/' . $this->getName()
      .'/'.TIE_PLUGIN_DIR),array('.','..','.svn','.htaccess')); 

    $plugins = array();
    foreach($availablePlugins as $plugin){
      $plugin = $this->getPlugin($plugin);
      $plugins[$plugin->name] = $plugin->displayName;
    }

    return array_flip($plugins);
  }

  // TODO: Singleton...
  protected function getPlugin($plugin=null, $params=null){

    $config = $this->touch->cmsms('config');
    
    if($this->editor){
      $this->editor;
    }
    
    if(!is_string($plugin) || !strlen($plugin)){
      $plugin = TIE_PLUGIN_DEFAULT;
    }

    if(file_exists($this->getModulePath().'/'.TIE_PLUGIN_DIR.$plugin.'/'.$plugin.'.plugin.php')){
      require_once $this->getModulePath().'/'.TIE_PLUGIN_DIR.$plugin.'/'.$plugin.'.plugin.php';
    }
    
    $this->editor = new $plugin($this);
    
    return $this->editor;
    
  }
  
}

?>