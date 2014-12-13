<div class="row">
  <div class="col-md-9">
    <ul id="infobox_info">
        <li><h1><strong>{$parliament['name']}</strong></h1></li>
        <li>{$text['term']}: <strong>{$parliament['term']}</strong></li>
        {if ($tag != '')}
          <li>{$text['tags']}: <strong>{$tag}</strong></li>
        {/if}
        <li style="font-size:2em"><strong>{$issue->score}: {$parliament['score']}&nbsp;%</strong></li>
    </ul>
    <div id="chart"></div>
  </div>
</div> <!-- /row -->
{if ($show_chart)}
<div class="row">
    {include "chart_legend.tpl"}
</div> <!-- /row -->
{/if}
