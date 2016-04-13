<div class="col-md-6 each_vote_container">
  <div class="panel panel-{if $ve->ok == -1}danger{elseif $ve->ok == 1}success{else}default{/if}">
    <div class="panel-heading">
      <h3><a href="./vote-event.php?identifier={$ve->identifier}{$term_chunk}">{$ve->name}</a></h3>
{*      {$text['voted_neutral']}&nbsp;*}
{*        <i class="fa {if ($ve->result == 'pass')}fa-thumbs-o-up{else}fa-thumbs-o-down{/if}"></i>, *}
{*      {$issue->author} {$text['recommends']}&nbsp;*}
{*	    <i class="fa {if $ve->pro_issue == 1}fa-thumbs-o-up{elseif $ve->pro_issue == -1}fa-thumbs-o-down{else}fa-question{/if}"></i>*}
	    <div><small>
	      {$ve->start_date|date_format}
	      {if isset($ve->links[0]->url) and ($ve->links[0]->url != '')}, <a href="{$ve->links[0]->url}">{$ve->motion->text}</a>{/if}
          <br/>
{*	      {$text['weight']} ({$issue->author}): {$ve->weight}*}
           {$text['tags']}: {foreach $ve->subcategory as $subcategory}
                <span class="tag label label-warning"><a href="vote-event.php?tag={$subcategory|escape:'url'}{$term_chunk}">{$subcategory}</a>{if !$subcategory@last}</span>, {/if}
           {/foreach}
	      </small>
	    </div>
    </div>
    <div class="panel-body">
      <p>{$ve->description}</p>
    </div>
  </div>
</div>
