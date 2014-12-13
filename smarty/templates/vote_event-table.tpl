
      <table id="vote-event-table" class="table tablesorter">
        <thead>
          <tr>
            <th>{$text['name']}</th>
            <th>{$text['party']}</th>
            <th>{$text['voted']}</th>
          </tr>
        </thead>
        <tbody>
        {foreach $parties as $party}
          {foreach $party->people as $person}
            <tr>
              <td><a href="{$person->link}">{$person->name}</a></td>
              <td><a href="{$party->link}">{$party->name}</a></td>
              <td>{$person->option|ucfirst}</td>
            </tr>
          {/foreach}
        {/foreach}
        </tbody>
      </table>

