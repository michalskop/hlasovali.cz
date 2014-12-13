{extends file='page.tpl'}
{block name=body}
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header" id="infobox_header">
      <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {$text[$error['description']]}</div>
    </div> <!-- /modal-header -->

  </div> <!-- /modal content -->
</div> <!-- /modal -->  
{/block}

