<?php
/**
common part for person
*/


$vote_events = json_decode(file_get_contents($path . "json/vote-events.json"));
$option_meaning = json_decode(file_get_contents($path . "json/option-meaning.json"));
$people = json_decode(file_get_contents($path . "json/people.json"));
$partiesjson = json_decode(file_get_contents($path . "json/parties.json"));

if (isset($_GET['identifier']))
    $person_id = person_identifier2id($_GET['identifier'],$people);
else {
    $error = set_error('no_person_warning',$smarty,$text);
    return;
}
if (!$person_id) {
    $error = set_error('person_unknown_identifier_warning',$smarty,$text);
    return;
}


// term
$default_term = new StdClass();
$default_term->identifier = '';
$default_term->name = $text['all_terms'];
if (isset($_GET['term']))
  $term = term2term($_GET['term'],$header['terms'],$default_term);
else
  $term = $default_term;

//filter vote events
if (isset($term->start_date))
    $start_date = $term->start_date;
else
    $start_date = '1000-01-01 00:00:00';
if (isset($term->end_date))
    $end_date = $term->end_date;
else
    $end_date = '9999-12-31 23:59:59';   
$filtered_issue = filter_vote_events($issue, $vote_events, $start_date, $end_date);

// prepare voter's options (votes):
$requirements = new stdClass();
$options = new stdClass(); //for calculating score
$detailed_votes = []; //for detailed display
$party_ids = [];
foreach ($filtered_issue->vote_events as $vekey => $ve) {
  foreach($vote_events->$vekey->votes as $vkey => $v) {
    if ($v->voter_id == $person_id) {
      $options->$vekey = $v->option;
      $this_group_id = $v->group_id;
      $pobj = group2party($this_group_id,$partiesjson);
      $party_ids[$pobj->slug] = $this_group_id;
      
      $vote = (object)array_merge((array)$vote_events->$vekey, (array)$ve);
      unset($vote->votes);
      $vote->option = $v->option;
      $vote->single_match = single_match($vote->option,$vote->pro_issue,$option_meaning,$vote->motion->requirement);
      $req = $vote->motion->requirement;
      $vot = $v->option;
      $vote->option_meaning = $option_meaning->$req->options->$vot;

      $detailed_votes[] = $vote;
      break; 
    }
  }
  $requirements->$vekey = $vote_events->$vekey->motion->requirement;
  // for finding party:
  if (!isset($last_vote_event)) {
    $last_vote_event = $vote_events->$vekey;
    if (isset ($this_group_id))
      $last_group_id = $this_group_id;
  }
  if ($vote_events->$vekey->start_date > $last_vote_event->start_date)
    if (isset($this_group_id))
      $last_group_id = $this_group_id;
}
if (!isset($last_group_id)) {
    $error = set_error('person_no_vote-events_warning',$smarty,$text);
    return;
}
// last party
$party = group2party($last_group_id,$partiesjson);

//all parties
$parties = [];
foreach($party_ids as $party_id) {
  $p = group2party($party_id,$partiesjson);
  $p->link = ($widget ? '../': '') . 'party.php?party='.$p->slug.$term_chunk;
  $parties[] = $p;
}
// score 
$score = person_match($options, $filtered_issue->vote_events, $requirements, $option_meaning);

// sort detailed votes by date
usort($detailed_votes, function($a, $b) {
    return strtotime($b->start_date) - strtotime($a->start_date);
});

//person
$start_year = correct_year_for_photo(person_last_term_start_year($people->$person_id,$terms,$term));
$person = [
  'name' => $people->$person_id->name,
  'score' => round($score),
  'party' => $party->name,
  'party_link' =>  ($widget ? '../': '') . 'party.php?party='.$party->slug.$term_chunk,
  'image' => 'http://www.psp.cz/eknih/cdrom/'.$start_year.'ps/eknih/'.$start_year.'ps/poslanci/i'.$_GET['identifier'].".jpg",
  'term' => $term->name,
  'link' => ($widget ? '../': '') . 'person.php?identifier='.person_id2identifier($person_id,$people) . $term_chunk,
  'color' => score2color($score),
];
if (isset($people->$person_id->gender))
  $person['gender'] = $people->$person_id->gender;


// by years
$years = [];
$period_names = [];
foreach ($filtered_issue->vote_events as $vekey => $ve) {
  $y = date('Y', strtotime($vote_events->$vekey->start_date));
  $years[$y] = $y;
}
sort($years);
$year_scores = [];
foreach ($years as $year) {
  $filtered = filter_vote_events($filtered_issue, $vote_events, $year.'-01-01', $year.'-12-31');
  $sc = person_match($options, $filtered->vote_events, $requirements, $option_meaning);
  
  $ys = new stdClass();
  $ys->x= (integer) $year;
  $ys->y= round($sc);

  if ((count((array)$filtered->vote_events) > 0) and ($sc !== false)) {
    $ys->size = 50;
    $year_scores[] = $ys;
    $period_names[$year] = (string) $year;
  }
}

$ser = new stdClass();
$ser->name = $person['name'];
$ser->title = $person['name'];
$partyslug = $party->slug;
$ser->color = $partiesjson->$partyslug->color;
$ser->data = $year_scores;

$series = [];
$series[] = $ser;
$chart_options = new stdClass();
$chart_options->width = 500;
$chart_options->height = 100;
$chart_options->ylabel = '';//$issue->score;
$chart_options->xlabel = $text['year'];

if (count($year_scores) < 2)
    $show_chart = false;
else
    $show_chart = true;



//smarty
//print_r($start_year );die();
$smarty->assign('path',$path);
$smarty->assign('period_type', json_encode("year"));
$smarty->assign('period_names',json_encode($period_names));
$smarty->assign('series',json_encode($series));
$smarty->assign('chart_options',json_encode($chart_options));
$smarty->assign('show_chart',$show_chart);
$smarty->assign('issue',$issue);
$smarty->assign('person',$person);
$smarty->assign('parties',$parties);
$smarty->assign('detailed_votes',$detailed_votes);
$smarty->assign('title',$people->$person_id->name . ': ' .$person['score'] . '%' . ' | ' . $issue->title);
$smarty->assign('error',$error);







function group2party($group_id,$partiesjson) {
    foreach ($partiesjson as $pkey => $party) {
      foreach ($party->children as $ckey => $child) {
        if ($child == $group_id) {
          $party->slug = $pkey;
          return $party;
        }
      }
    }
    return false;
}
?>
