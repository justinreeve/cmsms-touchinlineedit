<?php

$lang['description'] = 'A powerfully frontend inline editor module.';
$lang['admdescription'] = 'A powerfully frontend inline editor module.';

$lang['help'] = '<h3>Usage</h3>
<ul>
  <li>
    1) <span style="color:orange">Important!</span> Put the load module tag {cms_module module="touchInlineEdit"} into the head of each template you want to use the module directly after the {process_pagedata} tag.<br />
    2) Configure the group permissions for admin users (set permission: Use touchInlineEdit) or optional setup the front end user support (FEU) and take a look at the cmsms front end.
  </li>
</ul>
<h3>About</h3>
<ul>
  <li>Copyright by <a href="http://www.touchdesign.de/">touchDesign</a></li>
  <li>Author Christoph Gruber</li>
  <li>CMSms TouchInlineEdit <a href="http://dev.cmsmadesimple.org/projects/touchinlineedit">project page</a></li>
  <li>Support via <a href="http://www.homepage-community.de/index.php?topic=1680.0">HPC</a></li>
  <li>License GPL 2.0</li>
</ul>';

$lang['settings'] = 'Settings';
$lang['permissions'] = 'Permissions';
$lang['templates'] = 'Templates';
$lang['editor'] = 'Editor';

$lang['tablecoltemplate'] = 'Template';
$lang['yes'] = 'Yes';
$lang['no'] = 'No';
$lang['save'] = 'Save';
$lang['edit'] = 'Edit';
$lang['cancel'] = 'Cancel';
$lang['reset'] = 'Reset';
$lang['settingssaved'] = 'Settings saved.';
$lang['templatessaved'] = 'Template saved.';
$lang['settingsconfirm'] = 'On change the editor plugin (and only then!) all customized templates and settings for current edior plugin %s will be lost.';

$lang['fePlugin_label'] = 'Which editor you want?';
$lang['fePlugin_help'] = 'Select an editor.';

$lang['feEditButton_label'] = 'Edit button in FE?';
$lang['feEditButton_help'] = 'Add enable/disable button for InlineEdit in frontend.';

$lang['feEditButtonText_label'] = 'Edit button text';
$lang['feEditButtonText_help'] = 'Edit button text.';
$lang['feEditButtonText_default'] = 'TouchInlineEdit';

$lang['feUpdateAlertMessage_label'] = 'Update message?';
$lang['feUpdateAlertMessage_help'] = 'Update message on successfully content update. (Leave it empty for disabling)';
$lang['feUpdateAlertMessage_default'] = 'Update Success...';

$lang['touchInlineEditButton_label'] = 'InlineEdit button template:';
$lang['feEditOnDblClick_label'] = 'Activate InlineEdit on double click?';
$lang['feEditOnDblClick_help'] = 'Activate InlineEdit on double click in content area.';

$lang['feFEUallow_label'] = 'Allow FEU?';
$lang['feFEUallow_help'] = 'Allow frontend users to use InlineEdit.';

$lang['feFEUgroups_label'] = 'Set FEU group(s)?';
$lang['feFEUgroups_help'] = 'Set FEU group restrictions.';

$lang['feFEUdisabled_label'] = 'FrontEndUser (FEU) support is disabled.';
$lang['feFEUdisabled_help'] = 'To use InlineEdit with frontend user support you have to install the frontend users (FEU) module.';

$lang['feAdminAllow_label'] = 'Allow admins?';
$lang['feAdminAllow_help'] = 'Allow admin users (with "Use touchInlineEdit" permissions) to use InlineEdit.';

$lang['postinstall'] = 'Install success...';
$lang['postuninstall'] = 'Uninstall success...';
$lang['postupgrade'] = 'Upgrade success version %s...';
$lang['postcontentupdate'] = 'Content with content_id:%s updated.';
$lang['preuninstall'] = 'Are you sure you want to uninstall this module and destroy all settings/templates?';

/* ---- Plugins ---- */

// nicedit
$lang['nicedit_full_panel_label'] = 'Extended toolbar?';
$lang['nicedit_full_panel_help'] = 'Use extended toolbar.';
$lang['nicedit_jquery_load_label'] ='Load jQuery libary';
$lang['nicedit_jquery_load_help'] ='Let InlineEdit load required jQuery libary in header of pages.';
$lang['nicedit_button_list_label'] ='Load jQuery libary';
$lang['nicedit_button_list_help'] ='Let InlineEdit load required jQuery libary in header of pages.';
$lang['nicedit_height_label'] ='Maximum height';
$lang['nicedit_height_help'] ='Set maximum height in px default is "auto".';

// elrte
$lang['elrte_toolbar_label'] = 'Which toolbar?';
$lang['elrte_toolbar_help'] = 'Choose any toolbar you like.';
$lang['elrte_jquery_load_label'] ='Load jQuery libary';
$lang['elrte_jquery_load_help'] ='Let InlineEdit load required jQuery libary in header of pages.';

// tinymce
$lang['tiny_mce_jquery_load_label'] ='Load jQuery libary';
$lang['tiny_mce_jquery_load_help'] ='Let InlineEdit load required jQuery libary in header of pages.';
$lang['tiny_mce_theme_label'] ='Theme';
$lang['tiny_mce_theme_help'] ='Choose any theme you want.';
$lang['tiny_mce_skin_label'] ='Skin';
$lang['tiny_mce_skin_help'] ='Choose any skin you like.';
$lang['tiny_mce_skin_variant_label'] ='Skin variant';
$lang['tiny_mce_skin_variant_help'] ='Choose any skin variant you like.';
$lang['tiny_mce_width_label'] ='Width';
$lang['tiny_mce_width_help'] ='Editor width in pixel default auto.';
$lang['tiny_mce_height_label'] ='Height';
$lang['tiny_mce_height_help'] ='Editor height in pixel default auto.';
$lang['tiny_mce_plugins_label'] ='Plugins';
$lang['tiny_mce_plugins_help'] ='Check the plugins you want to use (strg for multiple plugins).';
$lang['tiny_mce_buttons1_label'] ='First toolbar';
$lang['tiny_mce_buttons1_help'] ='Customize and inert buttons for you first toolbar.';
$lang['tiny_mce_buttons2_label'] ='Second toolbar';
$lang['tiny_mce_buttons2_help'] ='Customize and inert buttons for you second toolbar.';
$lang['tiny_mce_forced_root_block_label'] ='Force root block';
$lang['tiny_mce_forced_root_block_help'] ='Enable focre root block.';
$lang['tiny_mce_force_br_newlines_label'] ='Newlines BR';
$lang['tiny_mce_force_br_newlines_help'] ='Enable break tag BR for newlines.';
$lang['tiny_mce_force_p_newlines_label'] ='Newlines P';
$lang['tiny_mce_force_p_newlines_help'] ='Enable paragraph tag P for newlines.';
$lang['tiny_mce_entity_encoding_label'] ='Entity encoding';
$lang['tiny_mce_entity_encoding_help'] ='Choose entity encoding.';
$lang['tiny_mce_theme_advanced_resizing_label'] ='Resizing';
$lang['tiny_mce_theme_advanced_resizing_help'] ='Enable resizing.';

?>