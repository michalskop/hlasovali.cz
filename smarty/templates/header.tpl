<div class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <a href="../" class="navbar-brand">{$header['name']}</a>
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="navbar-collapse collapse" id="navbar-main">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">{$text['terms']} <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{$header['link_without_term']}">{$text['all_terms']}</a></li>
            {$lasttype = ''}
            {foreach $header['terms'] as $key=>$term}
              {if ($lasttype != $term->type)}
              <li class="divider"></li>
              {$lasttype = $term->type}
              {/if}
            <li><a href="{$header['link_without_term']}&term={$term->identifier}">{$term->name}</a></li>
            {/foreach}
          </ul>
        </li>
        <li><a href="/?{$term_chunk}">{$text['parties']}</a></li>
        <li><a href="/vote-event.php?{$term_chunk}">{$text['vote_events']}</a></li>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <li><a href="/widgets/">{$text['widgets']}</a></li>
        <li><a href="/methodology/">{$text['methodology']}</a></li>
      </ul>
    </div>
  </div>
</div>
