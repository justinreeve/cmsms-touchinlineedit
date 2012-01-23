<?php
/**
 * $Id$
 *
 * touchInlineEdit Module
 *
 * Copyright (c) 2010 touchdesign, <www.touchdesign.de>
 *
 * @category Module
 * @author Christoph Gruber <www.touchdesign.de>
 * @version 1.8.3
 * @copyright 04.08.2010 touchdesign
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
  public $plugin;
  
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
  public $templates = array(
    'button' => '<button class="P{$content_id} B{$tieCurrentBlock} touchInlineEditButton">{$tie->touch->get(\'feEditButtonText\')}</button>'
  );
  
  /**
   * Constant has inline edit rights.
   * @var true,false,null
   * @access public
   */
  const ENABLED = null;

  /**
   * Construct a new module.
   */
  public function __construct()
  {
    parent::__construct();
    
    $this->touch = new touchModule($this);
    $this->smarty = $this->touch->cmsms('smarty');
    $this->smarty->force_compile = true;
    
    // TODO: Check if this the right place?
    $this->init();
  }

  /**
   * Init touchInlineEdit and do smarty assigns.
   */
  public function init()
  {
    if($this->isEnabled()){
      $this->smarty->assign('tie',&$this);
      $this->smarty->register_prefilter(array($this,'smartyPreCompile'));
    }
  }

  /**
   * Get module name.
   */
  public function GetName()
  {
    return 'touchInlineEdit'; 
  }

  /**
   * Get module friendly name.
   */
  public function GetFriendlyName()
  {
    return 'TouchInlineEdit'; 
  }
  
  /**
   * Get module version.
   */
  public function GetVersion()
  {
    return '1.8.3';
  }
  
  /**
   * Get module author.
   */
  public function GetAuthor()
  {
    return 'Christoph Gruber <touchdesign.de>';
  }

  /**
   * Get module author emailaddress.
   */
  public function GetAuthorEmail()
  {
    return 'c.gruber@touchdesign.de';
  }
  
  /**
   * Get module help and apppend static README.
   */
  public function GetHelp()
  {
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
  
  /**
   * Get module description.
   */
  function GetDescription()
  {
    return $this->Lang('description');
  }
  
  /**
   * Get module admin description.
   */
  function GetAdminDescription()
  {
    return $this->Lang('admdescription');
  }

  /**
   * Get module changelog and append static CHANGELOG.
   */
  function GetChangeLog()
  {
    $config = $this->getConfig();
    
    $html = '<pre>';
    $html.= file_get_contents($config['root_path'] 
      . '/modules/' . $this->getName() . '/CHANGELOG');
    $html.= '</pre>';
    
    return $html;
  }

  /**
   * Is plugin module.
   */
  public function IsPluginModule()
  {
    return true;
  }

  /**
   * Has admin.
   */
  public function HasAdmin()
  {
    return true; 
  }

  /**
   * Get admin section for module.
   */
  public function GetAdminSection()
  {
    return 'extensions';
  }

  /**
   * Set module paramaters.
   */
  public function SetParameters()
  {
    /* Nothing yet */
  }

  /**
   * Get post install message.
   */
  public function InstallPostMessage()
  {
    return $this->Lang('postinstall');
  }

  /**
   * Get post uninstall message.
   */
  public function UninstallPostMessage()
  {
    return $this->Lang('postuninstall');
  }

  /**
   * Get pre install message.
   */
  public function UninstallPreMessage()
  {
    return $this->Lang('preuninstall');
  }

  /**
   * Get cmsms minimum version for touchInlineEdit.
   */
  public function MinimumCMSVersion()
  {
    return "1.6.4";
  }

  /**
   * Get cmsms minimum version for touchInlineEdit.
   */
  public function MaximumCMSVersion()
  {
    return "1.10.9";
  }

  /**
   * Handle events.
   */
  public function HandlesEvents()
  {
    return true;
  }

  /**
   * Smarty pre compile event, touch in :) touchInlineEdit.
   */
  function smartyPreCompile($templateSource, &$smarty=null)
  {
    if($smarty === null){
      return $templateSource;
    }
    
    $result = explode(':', $smarty->_current_file);

    if($result[0] == 'content' && ($this->getPlugin()->supportsMultiple 
      || $result[1] == 'content_en')){

      if($this->touch->isAjaxRequest()){
        return $templateSource;
      }

      $smarty->assign('tieCurrentBlock',$result[1]);

      // Before content
      $contentBefore = '{if $tie->isEnabled()}';
      $contentBefore.= '  {if $tie->touch->get(\'feEditButton\')}';
      $contentBefore.= '    {$tie->touch->fetch(\'button\',true)}';
      $contentBefore.= '  {/if}';
      $contentBefore.= '  <div id="touchInlineEditId-{$content_id}-{$tieCurrentBlock}" class="P{$content_id} B{$tieCurrentBlock} touchInlineEdit">';
      $contentBefore.= '{/if}';

      // After content
      $contentAfter = '{if $tie->isEnabled()}';
      $contentAfter.= '  </div>';
      $contentAfter.= '{/if}';

      return $contentBefore . $templateSource . $contentAfter;

    }

    return $templateSource;
  }

  /**
   * Module specific event handling.
   */
  public function DoEvent($originator, $eventname, &$params)
  {
    parent::DoEvent($originator, $eventname, &$params);
  }

  /**
   * Check if the user is granted to use TouchInlineEdit.
   */
  public function isEnabled()
  {
    if(isset($this->ENABLED)){
      return $this->ENABLED;
    }

    $this->ENABLED = false;
    
    // Support for frontend users
    if($this->touch->get('feFEUallow')){
      $feu = $this->touch->getCMSModuleInstance('FrontEndUsers');
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

  /**
   * ------------------------------------------------------------------
   * Content operations for touchInlineEdit Ajax requests
   * ------------------------------------------------------------------
   */

  /**
   * Get current content Id.
   */
  public function getContentId()
  {
    if(function_exists('cmsms') && method_exists(cmsms(),'get_variable')){
      return cmsms()->get_variable('content_id');
    }else{
      global $gCms;
      return $gCms->variables['content_id'];
    }
  }

  /**
   * Get content object for current content id.
   */
  private function getContentObj($contentId=null)
  {
    if($contentId === null){
      $contentId = $this->getContentId();
    }

    $manager = &$this->touch->cmsms()->GetHierarchyManager();
    $node = &$manager->sureGetNodeById($contentId);

    if(!is_object($node)){
      return "Invalid ContentId: " . $contentId;
    }

    return $node->GetContent(true,true);
  }

  /**
   * Fetch content for current content object.
   */
  protected function getContent($block="content_en",$fetch=false)
  {
    $contentObj = &$this->getContentObj();
    
    if(isset($_POST['block'])){
      $block = $_POST['block'];
    }
    if(isset($_POST['fetch']) && !empty($_POST['fetch'])){
      $fetch = $_POST['fetch'];
    }
    
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

  /**
   * Update current content from POST.
   */
  protected function updateContent($block="content_en")
  {
    $contentObj = &$this->getContentObj();

    $block = $_POST['block'];
    $params[$block] = $_POST['content'];

    // Fix: Attempt to load admin realm from non admin action notice if alias already used
    // Ref: http://dev.cmsmadesimple.org/bug/view/5805
    @$contentObj->FillParams($params);

    $errors = $contentObj->ValidateData();
    if($errors !== false){
      // TODO: throw errors
      return "Invalid content";
    }
    
    $contentObj->LoadFromId($this->getContentId());
    
    $contentObj->Save();

    // Log update info
    $this->Audit(0, $this->GetFriendlyName(), 
      $this->Lang('postcontentupdate', 
      $this->getContentId()."{".$block."}"));

    return $this->getContent($block,true);
  }
  
  /**
   * ------------------------------------------------------------------
   * Helper Functions
   * ------------------------------------------------------------------
   */

  /**
   * Get available module as array.
   */
  protected function getPlugins()
  {
    $config = $this->touch->cmsms('config');

    $availablePlugins = array_diff(scandir($this->getModulePath().'/'
      .TIE_PLUGIN_DIR),array('.','..','.svn','.htaccess')); 

    $plugins = array();
    foreach($availablePlugins as $plugin){
      $plugin = $this->getPlugin($plugin);
      $plugins[$plugin->name] = $plugin->displayName;
    }

    return array_flip($plugins);
  }

  /**
   * Get plugin instance for given name, current or default.
   */
  protected function getPlugin($name=null)
  {
    $config = $this->touch->cmsms('config');
    
    if(!$name){
      $name = $this->touch->get('fePlugin');
    }
    
    if(!is_string($name) || !strlen($name)){
      $name = TIE_PLUGIN_DEFAULT;
    }

    if(file_exists($this->getModulePath().'/'.TIE_PLUGIN_DIR.$name.'/'.$name.'.plugin.php')){
      require_once $this->getModulePath().'/'.TIE_PLUGIN_DIR.$name.'/'.$name.'.plugin.php';
    }
    
    $this->plugin = new $name($this);
    
    return $this->plugin;
  }
  
  /**
   * Destruct a new module.
   */
  public function __destruct()
  {
    $this->smarty->force_compile = false;
  }
  
}

?>