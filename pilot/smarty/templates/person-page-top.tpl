<div class="row">
  <div class="col-md-3">
    <img width="170" height="216" src="{$person['image']}" class="img-rounded" />
  </div>
  <div class="col-md-9">  
    <ul id="infobox_info">
      <li><h1><strong>{$person['name']}</strong></h1></li>
      <li>{$text['party']}: 
        {foreach $parties as $party} 
          <strong><a href="{$party->link}">{$party->name}</a></strong>{if !$party@last}, {/if}
        {/foreach}
      </li>
      <li>{$text['term']}: <strong>{$person['term']}</strong></li>
      <li style="font-size:2em"><strong>{$issue->score}: {$person['score']}&nbsp;%</strong></li>
    </ul>
    <div id="chart"></div>
  </div>
</div> <!-- /row -->
