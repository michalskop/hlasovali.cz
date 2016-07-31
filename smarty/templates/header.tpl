<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">

      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
        <span class="sr-only">...</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand{if $page=='frontpage'} active{/if}" href="/"><span class="col-xs"><small>{$t->get('pre_name')} </small></span>{$t->get('app_name')}<span class="col-xs"><small> {$t->get('post_name')}</small></a>
    </div>

    <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                {if $cityhall->selected}
                  {$cityhall->name|htmlspecialchars}
                {else}
                  {$t->get('select_cityhall')}
                {/if}
                <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                      {foreach $cityhalls as $ch}
                          <li><a href="?page=organization&action=select&continue={$request_uri|urlencode}&org={$ch->id}">{$ch->name|htmlspecialchars}</a>
                      {/foreach}
                  </ul>
            </li>
            <li></li>
            {* <li ><a href="https://github.com/KohoVolit/activities#api" target="_blank">API</a></li> *}
        </ul>

      <ul class="nav navbar-nav navbar-right">

        {if $user->logged}
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{$user->name} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">{$t->get('my_page')}</a></li>
                        <li><a href="?page=logout&action=run&continue={$request_uri|urlencode}&u={$user->id}">{$t->get('logout')}</a></li>
                    </ul>
            </li>
        {else}
            <li><a href="?page=login">{$t->get('login')}</a></li>
        {/if}
      </ul>
    </div> <!-- /.navbar-collapse -->
  </div>
</nav>
