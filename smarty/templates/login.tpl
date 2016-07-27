{extends file='main.tpl'}
{block name=body}

    {if !$user->logged}
    <form action="index.php?page=login&action=run&continue={$request_uri|urlencode}" method="post">
        <div class="form-group">
            <label for="email">{$t->get('email_address')}</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
        </div>
        <div class="form-group">
            <label for="email">{$t->get('password')}</label>
            <input type="password" class="form-control" name="pass" id="pass" placeholder="Password">
        </div>
        <input type="submit" class="btn btn-success" value="{$t->get('login')}"/>
    </form>
    {else}
        {$t->get('already_logged_as')}: {$user->name|htmlspecialchars}
        <small>(<a href="?page=logout&action=run&continue={$request_uri|urlencode}&u={$user->id}">{$t->get('logout')}</a>)</small>
    {/if}

{/block}
