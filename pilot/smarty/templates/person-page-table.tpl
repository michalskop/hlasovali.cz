<div style="clear:both;"></div>

<div class="infobox_content modal-body">
 {foreach $detailed_votes as $vote}
   {if $vote@iteration is div by 2}
     <div class="row">
   {/if}
   {include "person-page-vote.tpl"}
   {if $vote@iteration is div by 2}
     </div>
   {/if} 
 {/foreach}
<div style="clear:both;margin-bottom: 20px;"></div>
