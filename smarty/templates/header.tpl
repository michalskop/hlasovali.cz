<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">

      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
        <span class="sr-only">...</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand{if $page=='frontpage'} active{/if}" href="/">
          {* <span class="col-xs"><small>{$t->get('pre_name')} </small> *}
      <i class="fa fa-hand-paper-o" aria-hidden="true"></i> {$t->get('app_name')}<span class="col-xs"><small><small> {$t->get('post_name')}</small></small></span></a>
    </div>

    <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                {if $cityhall->selected}
                    <strong>{$cityhall->name|htmlspecialchars}</strong>
                {else}
                    {$t->get('select_cityhall')}
                {/if}
                <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                      {foreach $cityhalls as $ch}
                          <li><a href="?page=organization&action=select&continue=?page=motion&org={$ch->id}">{$ch->name|htmlspecialchars}</a>
                      {/foreach}
                      <li role="separator" class="divider">
                      <li><a href="?page=about#new_authors">{$t->get('add_new_cityhall')}</a>
                  </ul>
            </li>
            <li><a href="?page=motion&action=view">{$t->get('list_of_motions')}</a>
        </ul>

      <ul class="nav navbar-nav navbar-right">
        {if $user->logged}
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><small>{$user->name}</small> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="?page=user&u={$user->id}">{$t->get('my_profile')}</a></li>
                        <li><a href="?page=logout&action=run&continue={$request_uri|urlencode}&u={$user->id}">{$t->get('logout')}</a></li>
                    </ul>
            </li>
        {else}
            <li><a href="?page=login"><i class="fa fa-user" aria-hidden="true"></i>  <small>{$t->get('login')}</small></a></li>
        {/if}
      </ul>
    </div> <!-- /.navbar-collapse -->
  </div>
</nav>
