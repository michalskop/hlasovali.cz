https://twitter.com/intent/tweet?text={$title|escape:'url'}&via={$t->get('twitter_handle')|escape:'url'}{if $cityhall->exist}&hashtags={$cityhall->name|escape:'url'}{/if}
