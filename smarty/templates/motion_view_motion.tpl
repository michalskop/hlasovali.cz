<h1>{$motion->name|htmlspecialchars}
    {if $user_can_edit}
        <button type="button" class="btn btn-xs" data-toggle="modal" data-target="#modal-edit">{$t->get('edit')}</button>
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
