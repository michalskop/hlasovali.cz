{extends file='page.tpl'}
{block name=body}
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header" id="infobox_header">
      {include "vote_events-page-top.tpl"}
    </div> <!-- /modal-header -->
    <div class="modal-body">
      {include "vote_events-page-table.tpl"}
    </div> <!-- /modal-body-->
  </div> <!-- /modal content -->
</div> <!-- /modal -->  
{/block}
