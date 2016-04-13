<div class="col-md-6 each_vote_container">
  <div class="panel panel-{if $vote->single_match == -1}danger{elseif $vote->single_match == 1}success{else}default{/if}">
    <div class="panel-heading">
      <h3><a href="./vote-event.php?identifier={$vote->identifier}{$term_chunk}">{$vote->name}</a></h3>
      {if isset($person['gender'])}
        {if $person['gender'] == 'male'}{$text['voted_male']}{else}{$text['voted_female']}{/if}{else}{$text['voted']}{/if}&nbsp;
        <i class="fa {if $vote->option_meaning == 1}fa-thumbs-o-up{elseif $vote->option_meaning == -1}fa-thumbs-o-down{else}fa-question{/if}"></i><small>&nbsp;({$text['vote_options'][$vote->option]})</small>, 
      {$issue->author} {$text['recommends']}&nbsp;
	    <i class="fa {if $vote->pro_issue == 1}fa-thumbs-o-up{elseif $vote->pro_issue == -1}fa-thumbs-o-down{else}fa-question{/if}"></i>
	    <div><small>
	      {$vote->start_date|date_format}
	      {if isset($vote->links[0]->url) and ($vote->links[0]->url != '')}, <a href="{$vote->links[0]->url}">{$vote->motion->text}</a>{/if}
          <br/>
{*	      {$text['weight']} ({$issue->author}): {$vote->weight}*}
	      {$text['tags']}: {foreach $vote->subcategory as $subcategory}
                <span class="tag label label-warning"><a href="vote-event.php?tag={$subcategory|escape:'url'}{$term_chunk}">{$subcategory}</a>{if !$subcategory@last}</span>, {/if}
           {/foreach}
	      </small>
	    </div>
    </div>
    <div class="panel-body">
      <p>{$vote->description}</p>
    </div>
  </div>
</div>
