<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header" id="infobox_header">
      <span>
        <img width="170" height="216" style="float:left;margin-right:10px" src="{$person['image']}" class="img-rounded">
      </span>
      <div class="row">
        <div class="col-md-8">  
          <ul id="infobox_info">
            <li><h1><strong>{$person['name']}</strong></h1></li>
            <li>{$text['party']}: <strong>{$person['party']}</strong></li>
            <li>{$text['term']}: <strong>{$person['term']}</strong></li>
           <li style="font-size:2em"><strong>{$text['score']}: {$person['score']}/100</strong></li>
          </ul>
        </div>
      </div>
    </div> <!-- /modal-header -->
      
    <div style="clear:both;"></div>
      
    <div class="infobox_content modal-body">
     {foreach $detailed_votes as $vote}
       {if $vote@iteration is div by 2}
         <div class="row">
       {/if}
       {include "person-detail-vote.tpl"}
       {if $vote@iteration is div by 2}
         </div>
       {/if} 
     {/foreach}
     <div style="clear:both;margin-bottom: 20px;"></div>
    </div> <!-- /modal-body -->
      
  </div>
</div>
