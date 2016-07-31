<div class="row">
    <div class="col-sm-6">
        <h3>{$cityhall->name}</h3>
        <h1>{$motion->name|htmlspecialchars}
            {if $user_can_edit}
                <button type="button" class="btn btn-xs btb-primary" data-toggle="modal" data-target="#modal-edit">{$t->get('edit')}</button>
                <button type="button" class="btn btn-xs btn-danger" data-toggle="modal"
                data-target="#modal-delete">{$t->get('delete')}</button>
            {/if}
            {if $user->logged and $user_has_author_privilages}
                <a href="index.php?page=motion&action=new" type="button" class="btn btn-xs btn-success">{$t->get('new_motion')}</a>
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
        </div>
        <div>
            <p>
            {$t->get('tags')}:
            {foreach $tags as $tag}
                <span class="label label-info">{$tag->tag|htmlspecialchars}</span>
            {/foreach}
        </div>
        <div>
            <p>
            {$motion->description}
        </div>
        <div>
            {if isset($motion->attributes->links)}
                {$t->get('links')}:
                <ul>
                    {foreach $motion->attributes->links as $link}
                        <li><a href="{$link->url|htmlspecialchars}">{$link->text|htmlspecialchars}</a>
                    {/foreach}
                </ul>
            {/if}
        </div>

        {if $author->exist}
            <div>
                <p>
                <small>{$t->get('created_by_author')}: {$author->name|htmlspecialchars}</small>
            </div>
        {/if}
    </div>
    <div class="col-sm-6">
    </div>
</div>
{include "motion_view_vote-event_table.tpl"}
