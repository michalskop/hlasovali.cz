{extends file='main.tpl'}
{block name=body}

    {if !$user->logged}
    <form>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="password" class="form-control" name="pass" id="pass" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-success">Login</button>
    </form>
    {else}
        Already logged as {$user->name}
        <small>(<a href="#">Logout</a>)</small>
    {/if}

{/block}
