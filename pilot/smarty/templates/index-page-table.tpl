<div style="clear:both;"></div>
<div class="infobox_content modal-body">
 {foreach $parties as $party}
   {if $party@iteration is div by 6}
     <div class="row">
   {/if}
   <div class="col-md-2 each_vote_container">
     {include "party-widget-inner.tpl"}
   </div>
   {if $party@iteration is div by 6}
     </div>
   {/if} 
 {/foreach}
<div style="clear:both;margin-bottom: 20px;"></div>
