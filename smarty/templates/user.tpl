{extends file='main.tpl'}
{block name=body}
    {if (count($organizations) == 0)}
        <div class="alert alert-danger" role="alert">
            {$t->get('user_does_not_exist')}
        </div>
    {else}
        <h1>{$organizations[0]->user_name}</h1>
        <ul class="list-group">
        {foreach $organizations as $o}
            {$continue = "?page=motion&u=`$o->user_id`"}
                <div class="row">
                    <div class="col-sm-3">
                        <li class="list-group-item">
                        <a href="?page=organization&action=select&continue={$continue|urlencode}">{$o->organization_name}</a>
                        <span class="badge">{$o->count}</span>

                    </div>
                    <div class="col-sm-3">
                        {if ($user->exist) and ($user->id == $o->user_id) and $o->active}
                            {$continue = "?page=motion&action=new"}
                            <a href="?page=organization&action=select&continue={$continue|urlencode}" type="button" class="btn btn-sm btn-success"><i class="fa fa-plus-square" aria-hidden="true"></i> {$t->get('new_motion')}</a>
                        {/if}
                    </div>
                </div>
        {/foreach}
        </ul>
    {/if}
{/block}
