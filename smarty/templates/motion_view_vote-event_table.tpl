<table id="vote-event-table" class="table tablesorter">
    <thead>
        <tr>
          <th>{$t->get('name')}
          <th>{$t->get('party_name')}
          <th>{$t->get('voted')}
        </tr>
    </thead>
  <tbody>
  {foreach $table_rows as $row}
      <tr>
          <td>{$row->family_name} {$row->given_name}
          <td>{$row->organization_name} ({$row->organization_abbreviation})
          {$row_option = str_replace(' ','-',$row->option)}
          <td>{if $row->option=="yes"}<span class="text-success">{else}<span class="text-danger">{/if}{$t->get("ve_`$row_option`")}</span>
      </tr>
  {/foreach}
  </tbody>
</table>
<link href="{$settings->app_url}libs/tablesorter.css" rel="stylesheet">
<script src="{$settings->app_url}libs/jquery.tablesorter.min.js"></script>
<script>
$(document).ready(function(){
    $(function(){
        $("#vote-event-table").tablesorter();
    });
});
</script>
