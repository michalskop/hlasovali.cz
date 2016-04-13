<div style="clear:both;"></div>

<div class="infobox_content modal-body">
 {foreach $vote_events as $ve}
   {if $ve@iteration is div by 2}
     <div class="row">
   {/if}
   {include "vote_events-page-vote_event.tpl"}
   {if $ve@iteration is div by 2}
     </div>
   {/if} 
 {/foreach}
<div style="clear:both;margin-bottom: 20px;"></div>
