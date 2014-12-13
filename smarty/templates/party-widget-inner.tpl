  <div class="party">
    <div style="background-color:{$party['color']}">
      <a href="{$party['link']}">
        <h2 title="{$party['name']}">{$party['party']->abbreviation}</h2>
        <div>
           <img title="{$party['name']}" alt="{$party['name']}" class="img-rounded"  src="{$party['image']}" style="display: inline;">
            <div class="score">{$party['score']}<small>%</small></div>
        </div>
      </a>
      <div class="person-party">{$party['name']|truncate:50:"..."}</div>
    </div>
  </div>
