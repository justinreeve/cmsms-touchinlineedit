<div class="pageoverflow">
  <table cellspacing="0" class="pagetable">
  <thead>
  <tr>
    <th class="pagew20">{$table_col_template}</th>
    <th class="pagew10">&nbsp;</th>
  </tr>
  </thead>
  {foreach from=$items item=col}
  <tbody>
  <tr class="{$col->rowclass}">
    <td>{$col->name}</td>
    <td>{$col->editlink}</td>
  </tr>
  </tbody>
  {/foreach}
  </table>
</div>