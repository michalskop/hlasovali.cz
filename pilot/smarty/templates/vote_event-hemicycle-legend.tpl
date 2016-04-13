<div class="row">
  <div class="col-sm-6">
    <i class="fa fa-star ko-color"> </i>{$text['legend_ko']}
  </div>
  <div class="col-sm-6">
    <i class="fa fa-star ok-color"> </i>{$text['legend_ok']}
  </div>
</div>
 <hr/>
<div class="row" style="margin: 0">
{$text['legend']}:<br/>
<p class="text-center">
{foreach $parties as $party}
  <i class="fa fa-user" style="color:{$party->color}">&nbsp;</i>{$party->abbreviation}&nbsp;
{/foreach}
</p>
</div>
