{$i = 0}
<div class="chart-legend">
{$text['legend']}:
{foreach $rawseries as $serie}
  {if ($i<10)}
    <svg height="10" width="10"><circle cx="5" cy="5" r="5" fill="{$serie->color}" style="fill-opacity:1;"></circle></svg> {$serie->abbreviation} &nbsp;&nbsp;
    {$i = $i + 1}
  {elseif ($i == 10)}
    ...
    {$i = $i + 1}
  {/if}
{/foreach}
</div>
