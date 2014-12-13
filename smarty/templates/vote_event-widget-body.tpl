<div class="vote-event">
  <h4>{$vote_event->name}</h4>
  {$vote_event->start_date|date_format}
  {if isset($vote_event->links[0]->url) and ($vote_event->links[0]->url != '')}, <a href="{$vote_event->links[0]->url}">{$vote_event->motion->text}&nbsp;<sup><i class="fa fa-external-link">&nbsp;</i></sup></a>{/if}, <a href="http://www.psp.cz/sqw/hlasy.sqw?g={$vote_event->identifier}">{$text['vote_event']}&nbsp;<sup><i class="fa fa-external-link">&nbsp;</i></sup></a>
  <div id="chart"></div>
  <div id="legend">
  {include "vote_event-hemicycle-legend.tpl"}
  </div>
</div>
  
{include "vote_event-hemicycle-script.tpl"}
