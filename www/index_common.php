<?php
$vote_events = json_decode(file_get_contents($path . "json/vote-events.json"));
$option_meaning = json_decode(file_get_contents($path . "json/option-meaning.json"));
$people = json_decode(file_get_contents($path . "json/people.json"));
$partiesjson = json_decode(file_get_contents($path . "json/parties.json"));

$requirements = new stdClass();
$parties = new stdClass(); //for calculating score
$ps = []; //for output
$parliament = new stdClass(); //for calculating score
$parl = []; //for output;

$group2party = new stdClass();
foreach ($partiesjson as $pkey => $party) {
  foreach ($party->children as $ckey => $child) {
    $group2party->$child = $pkey;
  }
}

// term
$default_term = new StdClass();
$default_term->identifier = '';
$default_term->name = $text['all_terms'];
if (isset($_GET['term'])) {
  $term = term2term($_GET['term'],$header['terms'],$default_term);
  $term_year = 'year'; 
} else {
  $term = $default_term;
  $term_year = 'term';
}

//filter vote events
if (isset($term->start_date))
    $start_date = $term->start_date;
else
    $start_date = '1000-01-01';
if (isset($term->end_date))
    $end_date = $term->end_date;
else
    $end_date = '9999-12-31';   
$filtered_issue = filter_vote_events($issue, $vote_events, $start_date, $end_date);
    //by tag
if (isset($_GET['tag']) and (trim($_GET['tag']) != '')) {
  $tag = urldecode(trim($_GET['tag']));
  $filtered_issue = filter_vote_events_by_tag($filtered_issue, $vote_events, $tag);
} else {
  $tag = '';
}

$voters = new StdClass();
foreach ($filtered_issue->vote_events as $vekey => $ve) {
  foreach($vote_events->$vekey->votes as $vkey => $v) {
    $voter_id = $v->voter_id;
    $group_id = $v->group_id;
    $party_id = $group2party->$group_id;
    if (!isset($voters->$voter_id))
      $voters->$voter_id = new stdClass();
    $voters->$voter_id->$vekey = $v->option;
    if (!isset($parties->$party_id)) {
      $parties->$party_id = new stdClass();
      $parties_members[$party_id] = [];
    }
    if (!isset($parties->$party_id->$vekey))
      $parties->$party_id->$vekey = [];
    if (!isset($parliament->$vekey))
      $parliament->$vekey = [];
    array_push($parties->$party_id->$vekey, $v->option);
    array_push($parliament->$vekey, $v->option);
    $parties_members[$party_id][$voter_id] = $voter_id;
  }
  //print_r($vote_events->$vekey);die();
  $requirements->$vekey = $vote_events->$vekey->motion->requirement;
}

$parl['score']  = round(group_match($parliament, $filtered_issue->vote_events, $requirements, $option_meaning));
$parl['name'] = $issue->organization;
$parl['term'] = $term->name;

//parties
foreach($parties as $party_slug => $party) {
  $p = [];
  $p['name'] = $partiesjson->$party_slug->name;
  $p['party'] = $partiesjson->$party_slug;
  $p['score'] = round(group_match($party, $filtered_issue->vote_events, $requirements, $option_meaning));
  $p['image'] = ($widget ? '../': '') . 'image/party/'.$party_slug.".jpg";
  $p['link'] = ($widget ? '../': '') . 'party.php?party='. $party_slug . $term_chunk;
  $p['color'] = score2color(round($p['score']));
  $count = 0;
  foreach($party as $item) {
    $count = $count + count($item);
  }
  $p['count'] = $count;
  $ps[$party_slug] = $p;
}
//sort by count
foreach ($ps as $key => $row) {
  $sorting[$key] = $row['count'];
}
array_multisort($sorting, SORT_DESC, $ps);

//by parties by years or terms
$series = [];
$years = [];
if ($term_year == 'year') {
    foreach ($filtered_issue->vote_events as $vekey => $ve) {
      $y = date('Y', strtotime($vote_events->$vekey->start_date));
      $years[$y] = $y;
    }
    sort($years);
}

$max_year_scores = 0;
foreach ($parties as $party_slug => $party) {
    $year_scores = [];
    $period_names = [];
    $count = 0;
    if ($term_year == 'year') {
        foreach ($years as $year) {
          $filtered = filter_vote_events($filtered_issue, $vote_events, $year.'-01-01', $year.'-12-31');
          $sc = group_match($party, $filtered->vote_events, $requirements, $option_meaning);
          //last vote event:
          $voted = 0;
          foreach ($filtered->vote_events as $vekey => $ve) {
            foreach ($vote_events->$vekey->votes as $vkey => $v) {
              if (in_array($v->group_id,$partiesjson->$party_slug->children))
                $voted++;
            }
          }
          
          $ys = new stdClass();
          $ys->x= (integer) $year;
          $ys->y= round($sc);
          if (count((array)$filtered->vote_events) and ($sc !== false)) {
            $ys->size= ceil($voted/count((array)$filtered->vote_events));
            $year_scores[] = $ys;
            $count += $voted;
          }
          $period_names[$year] = (string) $year;
        }
    } else {
      foreach ($terms as $term) {
         if ($term->type == 'parliamentary_term') {
          $filtered = filter_vote_events($filtered_issue, $vote_events, $term->start_date, (isset($term->end_date) ? $term->end_date : '9999-12-31'));
          $sc = group_match($party, $filtered->vote_events, $requirements, $option_meaning);
          //last vote event:
          $voted = 0;
          foreach ($filtered->vote_events as $vekey => $ve) {
            foreach ($vote_events->$vekey->votes as $vkey => $v) {
              if (in_array($v->group_id,$partiesjson->$party_slug->children))
                $voted++;
            }
          }
          
          $ys = new stdClass();
          $ys->x= (integer) date('Y', strtotime($term->start_date));
          $ys->y= round($sc);
          if (count((array)$filtered->vote_events) and ($sc !== false)) {
            $ys->size= ceil($voted/count((array)$filtered->vote_events));
            $year_scores[] = $ys;
            $count += $voted;
          }
          if (count((array)$filtered->vote_events)) {
            $sd = (string) $ys->x;
            $period_names[$sd] = $term->name;
          }
        }
      }
    }
    $ser = new stdClass();
    $ser->name = $partiesjson->$party_slug->name;
    $ser->abbreviation = $partiesjson->$party_slug->abbreviation;
    $ser->title = $partiesjson->$party_slug->abbreviation;
    $ser->color = $partiesjson->$party_slug->color;
    $ser->data = $year_scores;
    $ser->count = $count;
    $ser->period = $term_year;
    $series[] = $ser;
    
    if (count($year_scores) > $max_year_scores)
        $max_year_scores = count($year_scores);
}
//sort by party:
usort($series,function($a,$b) {
  return ($a->count < $b->count ? 1 : ($a->count > $b->count ? -1 : 0));
});

$chart_options = new stdClass();
$chart_options->width = 800;
$chart_options->height = 250;
$chart_options->ylabel = '';//$issue->score;
$chart_options->xlabel = $text['year'];

if ($max_year_scores < 2)
    $show_chart = true;//false;
else
    $show_chart = true;

$smarty->assign('path',$path);    
$smarty->assign('period_type', json_encode($term_year));
$smarty->assign('period_names',json_encode($period_names));
$smarty->assign('rawseries',$series);
$smarty->assign('series',json_encode($series));
$smarty->assign('chart_options',json_encode($chart_options));
$smarty->assign('show_chart',$show_chart);
$smarty->assign('header',$header);
$smarty->assign('parties',$ps);
$smarty->assign('parliament',$parl);
$smarty->assign('issue',$issue);
$smarty->assign('title',$issue->title);
$smarty->assign('tag',$tag);
$smarty->assign('color',score2color(round($parl['score'])));


?>
