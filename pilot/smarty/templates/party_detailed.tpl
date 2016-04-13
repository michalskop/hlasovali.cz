<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header" id="infobox_header">
      <span>
        <img width="170" height="170" style="float:left;margin-right:10px" src="{$party['image']}">
      </span>
      <div class="row">
        <div class="col-md-8">  
          <ul id="infobox_info">
            <li><h1><strong>{$party['name']}</strong></h1></li>
            <li><strong>{$party['party']->abbreviation}</strong></li>
            <li>{$text['term']}: <strong>{$party['term']}</strong></li>
           <li style="font-size:2em"><strong>{$text['score']}: {$party['score']}/100</strong></li>
          </ul>
          <div id="chart"></div>
      {include "chart.tpl"}
        </div>
      </div>
      
    </div> <!-- /modal-header -->
    
    <div style="clear:both;"></div>
      
    <div class="infobox_content modal-body">
     {foreach $members as $person}
       {if $person@iteration is div by 4}
         <div class="row">
       {/if}
       <div class="col-md-3 each_vote_container">
         {include "person.tpl"}
       </div>
       {if $person@iteration is div by 4}
         </div>
       {/if} 
     {/foreach}
     <div style="clear:both;margin-bottom: 20px;"></div>
    </div> <!-- /modal-body -->

  </div>
</div>
