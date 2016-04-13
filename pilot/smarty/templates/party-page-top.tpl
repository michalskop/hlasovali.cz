<div class="row">
  <div class="col-md-3">
    <img width="170" height="170" src="{$party['image']}" class="img-rounded" />
  </div>
  <div class="col-md-9">
    <ul id="infobox_info">
        <li><h1><strong>{$party['name']}</strong></h1></li>
        <li><strong>{$party['party']->abbreviation}</strong></li>
        <li>{$text['term']}: <strong>{$party['term']}</strong></li>
        <li style="font-size:2em"><strong>{$issue->score}: {$party['score']}&nbsp;%</strong></li>
    </ul>
    <div id="chart"></div>
  </div>
</div> <!-- /row -->
