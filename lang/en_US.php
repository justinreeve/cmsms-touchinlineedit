<?php

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

$lang['fePlugin_label'] = 'Which editor you want?';
$lang['fePlugin_help'] = 'Select an editor.';
$lang['feEditButton_label'] = 'Edit button in FE?';
$lang['feEditButton_help'] = 'Add enable/disable button for InlineEdit in frontend.';
$lang['feUpdateAlert_label'] = 'Update alert?';
$lang['feUpdateAlert_help'] = 'Enable update alert in frontend.';

$lang['touchInlineEditButton_label'] = 'InlineEdit button template:';
$lang['feEditOnDblClick_label'] = 'Activate InlineEdit on double click?';
$lang['feEditOnDblClick_help'] = 'Activate InlineEdit on double click in content area.';

$lang['feInlineEditButton'] = 'Edit';
$lang['feUpdateAlert'] = 'Content has been saved...';

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

$lang['niceditFullPanel_label'] = 'Extended toolbar?';
$lang['niceditFullPanel_help'] = 'Use extended toolbar.';
$lang['niceditJQueryLoad_label'] ='Load jQuery libary';
$lang['niceditJQueryLoad_help'] ='Let InlineEdit load required jQuery libary in header of pages.';

$lang['elrteToolbar_label'] = 'Which toolbar?';
$lang['elrteToolbar_help'] = 'Choose any toolbar you like.';
$lang['elrteJQueryLoad_label'] ='Load jQuery libary';
$lang['elrteJQueryLoad_help'] ='Let InlineEdit load required jQuery libary in header of pages.';

?>