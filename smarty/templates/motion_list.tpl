{extends file='main.tpl'}
{block name=body}
    {if (count($filters)>0)}
        <div class="alert alert-warning" role="alert">
            <strong>{$t->get('filter')}:</strong>
            {foreach $filters as $filter}
                <span class="label label-warning">{$t->get($filter['name'])}: {$filter['value']}</span>
            {/foreach}
        </div>
    {/if}

    {foreach $motions as $motion}
        {include "motion_list_motion.tpl"}
    {/foreach}
    {include "motion_list_pager.tpl"}
{/block}
