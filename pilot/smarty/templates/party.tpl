  <div class="party">
    <div style="background-color:{$color}">
      <a href="{$link}">
        <h2 title="{$party['name']}">{$party['party']->abbreviation}</h2>
        <div>
           <img width="170" height="170" title="{$party['name']}" alt="{$party['name']}" class="" src="{$party['image']}" style="display: inline;">
            <div class="score">{$party['score']}</div>
        </div>
      </a>
      <div class="person-party">{$party['name']}</div>
    </div>
  </div>
