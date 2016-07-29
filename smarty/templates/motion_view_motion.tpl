<h1>{$motion->name|htmlspecialchars}
    {if $user_can_edit}
        <button type="button" class="btn btn-xs" data-toggle="modal" data-target="#modal-edit">{$t->get('edit')}</button>
    {/if}
    {if $user->logged and $user_has_author_privilages}
        <a href="index.php?page=motion&action=new" type="button" class="btn btn-xs btn-success">{$t->get('new_motion')}</a>
    {/if}
</h1>
<div>
    {if $date_and_time['date']}
        {$date_and_time['date']|date_format:{$t->get('date_format')}}
    {/if}
    {if $date_and_time['time']}
        {$date_and_time['time']}
    {/if}
</div>
<div>
    {foreach $tags as $tag}
        <span class="label label-primary"><a href="#">{$tag->tag|htmlspecialchars}</a></span>
    {/foreach}
</div>
<div>
    {$motion->description}
</div>
<div>
    {if isset($motion->attributes->links)}
        <ul>
            {foreach $motion->attributes->links as $link}
                <li>{$link->text|htmlspecialchars}: <a href="{$link->url|urlencode}">{$link->url|htmlspecialchars}</a>
            {/foreach}
        </ul>
    {/if}
</div>

{if $author->exist}
    <div>
            {$t->get('created_by_author')}: {$author->name|htmlspecialchars}
    </div>
{/if}
