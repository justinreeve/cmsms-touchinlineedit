<div id="touchForm">
{$formstart}
  {if $feFEUallow_input && $feFEUgroups_input}
  <div class="pageoverflow">
    <p class="pagetext">{$feFEUallow_label}</p>
    <p class="pageinput">{$feFEUallow_input}<br/>{$feFEUallow_help}</p>
  </div>
  <div class="pageoverflow">
    <p class="pagetext">{$feFEUgroups_label}</p>
    <p class="pageinput">{$feFEUgroups_input}<br/>{$feFEUgroups_help}</p>
  </div>
  {else}
  <div class="pageoverflow">
    <p class="pagetext">{$feFEUdisabled_label}</p>
    <p class="pageinput">{$feFEUdisabled_help}</p>
  </div>
  {/if}
  <div class="pageoverflow">
    <p class="pagetext">{$feAdminAllow_label}</p>
    <p class="pageinput">{$feAdminAllow_input}<br/>{$feAdminAllow_help}</p>
  </div>
  <div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
  </div>
{$formend}
</div>