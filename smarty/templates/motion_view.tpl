{extends file='main.tpl'}
{block name=body}
    {if !$motion->exist}
        <div class="alert alert-danger" role="alert">
            {$t->get('motion_does_not_exist')}
        </div>
    {else}
        {if $user_can_edit}
            {include "motion_view_modal.tpl"}
            {include "motion_view_modal-delete.tpl"}
        {/if}
        {include "motion_view_motion.tpl"}
    {/if}
{/block}
