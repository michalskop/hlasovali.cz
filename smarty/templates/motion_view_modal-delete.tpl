<div class="modal fade" id="modal-delete-{$motion->id}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{$t->get('delete?')}</h4>
      </div>
      <div class="modal-body">
        <p>{$t->get('really_delete_motion')}
        <p><strong>{$t->get('cannot_be_undone')}</strong>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-reply" aria-hidden="true"></i> {$t->get('do_not_want_delete')}</button>
        <a href="index.php?page=motion&action=delete&m={$motion->id}" type="button" class="btn btn-danger"><i class="fa fa-exclamation" aria-hidden="true"></i> {$t->get('delete!')}</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
