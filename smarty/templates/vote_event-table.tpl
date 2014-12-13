
      <table id="vote-event-table" class="table tablesorter">
        <thead>
          <tr>
            <th>{$text['name']}</th>
            <th>{$text['party']}</th>
            <th>{$text['voted']}</th>
            <th>{$text['ok?']}</th>
          </tr>
        </thead>
        <tbody>
        {foreach $parties as $party}
          {foreach $party->people as $person}
            <tr>
              <td><a href="{$person->link}">{$person->name}</a></td>
              <td><a href="{$party->link}">{$party->name}</a></td>
              <td>{$person->option|ucfirst}</td>
              <td>{if ($person->single_match == 1)}{$text['ok']}{elseif ($person->single_match == -1)}{$text['ko']}{else}{$text['neutral']}{/if}
            </tr>
          {/foreach}
        {/foreach}
        </tbody>
      </table>

