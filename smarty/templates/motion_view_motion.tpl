<div class="row">
    <div class="col-sm-6">
        {* <h3>{$cityhall->name}</h3> *}
        <h1>{$motion->name|htmlspecialchars|unescape}
            {if $user_can_edit}
                <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-edit"><i class="fa fa-edit" aria-hidden="true"></i> {$t->get('edit')}</button>
                <button type="button" class="btn btn-xs btn-danger" data-toggle="modal"
                data-target="#modal-delete-{$motion->id}"><i class="fa fa-exclamation" aria-hidden="true"></i> {$t->get('delete')}</button>
            {/if}
            {if $user->logged and $user_has_author_privilages}
                <a href="index.php?page=motion&action=new" type="button" class="btn btn-xs btn-success"><i class="fa fa-plus-square" aria-hidden="true"></i> {$t->get('new_motion')}</a>
            {/if}
        </h1>

        <div>
            <p>
            {if $date_and_time['date']}
                {$date_and_time['date']|date_format:{$t->get('date_format')}}
            {/if}
            {if $date_and_time['time']}
                {$date_and_time['time']}
            {/if}
            {if $result}
                {if $result=="pass"}<span class="text-success">
                {else}<span class="text-danger">{/if}
                {$t->get($result)}
                </span>
            {/if}
        </div>
        {if $vote_event->vote_event_identifier}
            <div>
                {$t->get('vote_event_identifier')}: {$vote_event->vote_event_identifier}
            </div>
        {/if}
        <div>
            <p>
            <i class="fa fa-tags" aria-hidden="true"></i>
            {* {$t->get('tags')}: *}
            {foreach $tags as $tag}
                <a href="?page=motion&action=view&tag={$tag->tag|htmlspecialchars}"><span class="label label-primary">{$tag->tag|htmlspecialchars}</span></a>
            {/foreach}
        </div>
        <div>
            <p>
            {$motion->description|htmlspecialchars|unescape}
        </div>
        <div>
            {if isset($motion->attributes->links)}
                {$t->get('links')}:
                <ul>
                    {foreach $motion->attributes->links as $link}
                        <li><a href="{$link->url|htmlspecialchars|unescape}">{$link->text|htmlspecialchars|unescape}</a>
                    {/foreach}
                </ul>
            {/if}
        </div>

        {if $author->exist}
            <div>
                <p>
                <small>{$t->get('created_by_author')}: <a href="?page=user&u={$author->id}">{$author->name|htmlspecialchars|unescape}</a></small>
            </div>
        {/if}
        {include "sharer.tpl"}
    </div>
    <div class="col-sm-6">
    {include "motion_view_hemicycle.tpl"}
    </div>
</div>
{include "motion_view_vote-event_table.tpl"}
