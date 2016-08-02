<nav aria-label="pager">
    <ul class="pager">
        {if $pager['prev']['show']}
            <li><a href="{$pager['prev']['link']}">< {$t->get('previous')}</a>
        {/if}
        {if $pager['next']['show']}
            <li><a href="{$pager['next']['link']}">{$t->get('next')} ></a>
        {/if}
    </ul>
</nav>
