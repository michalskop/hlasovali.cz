  <div class="person">
    <div style="background-color:{$person['color']}">
      <a href="../{$person['link']}">
        <h2 title="{$person['name']}">{$person['name']}</h2>
        <div>
           <img width="170" height="216" title="{$person['name']}" alt="{$person['name']}" class="img-rounded" src="{$person['image']}" style="display: inline;">
            <div class="score">{$person['score']}<small>%</small></div>
        </div>
      </a>
      <div class="person-party">{$person['party']}</div>
    </div>
  </div>
