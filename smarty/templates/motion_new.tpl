{extends file='main.tpl'}
{block name=body}
    <h1>{$t->get('new_motion')}</h1>
    {if !$cityhall->selected}
        <div class="alert alert-danger" role="alert">
            {$t->get('cityhall_not_selected')}
        </div>
    {else}
        <h2><small>{$cityhall->name|htmlspecialchars}</small></h2>
        {if $user->logged and $user_has_author_privilages}    
            {include "motion_new_motion.tpl"}
        {else}
            <div class="alert alert-danger" role="alert">
                {$t->get('no_priviliges')}
            </div>
        {/if}
    {/if}
{/block}
