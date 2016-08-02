{include "motion_view_modal-delete.tpl"}
<div class="row">
    <div class="col-sm-8">
        <h3><a href="?page=motion&action=view&m={$motion->motion_id}">{$motion->motion_name|htmlspecialchars}</a>
            {if ($user->exist and ($user->id == $motion->user_id))}
                <a href="?page=motion&action=view&m={$motion->motion_id}#edit" type="button" class="btn btn-xs btn-primary"><i class="fa fa-edit" aria-hidden="true"></i> {$t->get('edit')}</a>
                <button type="button" class="btn btn-xs btn-danger" data-toggle="modal"
                data-target="#modal-delete-{$motion->motion_id}"><i class="fa fa-exclamation" aria-hidden="true"></i> {$t->get('delete')}</button>
            {/if}
        </h2>

        <div>
            <p>
            {if $motion->formatted_datetime['date']}
                {$motion->formatted_datetime['date']|date_format:{$t->get('date_format')}}
            {/if}
            {if $motion->formatted_datetime['time']}
                {$motion->formatted_datetime['time']}
            {/if}
            {if $motion->vote_event_result}
                {if $motion->vote_event_result=="pass"}<span class="text-success">
                {else}<span class="text-danger">{/if}
                {$t->get($motion->vote_event_result)}
                </span>
            {/if}
        </div>
        <div>
            {if count($motion->motion_attributes->links)>0}
                <p>
                <i class="fa fa-tags" aria-hidden="true"></i>
                {foreach $motion->tags as $tag}
                    <a href="?page=motion&action=view&tag={$tag->tag|htmlspecialchars}"><span class="label label-primary">{$tag->tag|htmlspecialchars}</span></a>
                {/foreach}
            {/if}
        </div>
        <div>
            <p>
            {$motion->motion_description|truncate:200:"...":true}
        </div>
        {* <div>
            {if (isset($motion->motion_attributes->links) and (count($motion->motion_attributes->links)>0))}
                {$t->get('links')}:
                <ul>
                    {foreach $motion->motion_attributes->links as $link}
                        <li><a href="{$link->url|htmlspecialchars}">{$link->text|htmlspecialchars}</a>
                    {/foreach}
                </ul>
            {/if}
        </div> *}

        <div>
            <p>
            <small>{$t->get('created_by_author')}: {$motion->user_name|htmlspecialchars}</small>
        </div>
    </div>
    <div class="col-sm-4 center-block">
        {if in_array($motion->vote_event_result,["pass","fail"])}
            <a href="?page=motion&action=view&m={$motion->motion_id}"><img src="{$settings->app_url}pages/cache/png/compact_{$motion->vote_event_id}.png" alt=""></a>
        {/if}
    </div>
</div>
