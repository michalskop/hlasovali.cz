<div class="modal fade" tabindex="-1" role="dialog" id="modal-edit">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{$t->get('edit')} <small>{$cityhall->name|htmlspecialchars}</small></h4>
            </div>

            <form action="index.php?page=motion&action=update&continue={$request_uri|urlencode}" method="post" name="motion_form">
                <div class="modal-body row">
                    <div class="container col-sm-12">
                        {include "motion_view_modal_motion.tpl"}


                        {include "motion_new_vote-event.tpl"}
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{$t->get('cancel')}</button>
                    <input type="submit" class="btn btn-success" value="{$t->get('save')}"/>
                </div>
            </form>
        </div>
    </div>
</div>
