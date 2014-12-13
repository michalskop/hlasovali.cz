      <div class="row">
        <div class="col-md-6">  
          <ul id="infobox_info">
            <li><h1><strong>{$vote_event->name}</strong></h1></li>
            <li><strong>{$vote_event->description}</strong></li>
            <li><strong>{$vote_event->start_date|date_format}</strong></li>
            {if isset($vote_event->links[0]->url) and ($vote_event->links[0]->url != '')}<li> <a href="{$vote_event->links[0]->url}">{$vote_event->motion->text}&nbsp;<sup><i class="fa fa-external-link">&nbsp;</i></sup></a></li>{/if}
            <li><a href="http://www.psp.cz/sqw/hlasy.sqw?g={$vote_event->identifier}">{$text['vote_event']}&nbsp;<sup><i class="fa fa-external-link">&nbsp;</i></sup></a></li>
            {$ve_id = $vote_event->identifier}
            {if isset($issue->vote_events->$ve_id->subcategory)}
              <li> {$text['tags']}:
              {foreach $issue->vote_events->$ve_id->subcategory as $subcategory}
                <span class="tag label label-warning"><a href="vote-event.php?tag={$subcategory|escape:'url'}{$term_chunk}">{$subcategory}</a></span>{if !$subcategory@last}, {/if}
              {/foreach}
              </li>
            {/if}
          </ul>
        </div> <!-- /col -->
        <div class="col-md-6">
          <div id="chart"></div>
          <div id="legend">
            {include "vote_event-hemicycle-legend.tpl"}
          </div>
        </div> <!-- /col -->
      </div> <!-- /row -->

