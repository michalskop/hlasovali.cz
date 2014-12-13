<?php
/**
common part for party
*/

$vote_events = json_decode(file_get_contents($path . "json/vote-events.json"));
$option_meaning = json_decode(file_get_contents($path . "json/option-meaning.json"));
$people = json_decode(file_get_contents($path . "json/people.json"));
$partiesjson = json_decode(file_get_contents($path . "json/parties.json"));

if (!isset($_GET['party'])) {
    $error = set_error('no_party_warning',$smarty,$text);
    return;
}

$requirements = new stdClass();
$parties = new stdClass(); //for calculating score
$voters = new stdClass();
$parties_members = []; //for detailed display

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
if (isset($_GET['term']))
  $term = term2term($_GET['term'],$header['terms'],$default_term);
else
  $term = $default_term;

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
    array_push($parties->$party_id->$vekey, $v->option);
    $parties_members[$party_id][$voter_id] = $voter_id;
  }
  //print_r($vote_events->$vekey);die();
  $requirements->$vekey = $vote_events->$vekey->motion->requirement;
}

if (!isset($parties->$_GET['party'])) {
    $error = set_error('party_no_vote-events_warning',$smarty,$text);
    return;
}

$score = group_match($parties->$_GET['party'], $filtered_issue->vote_events, $requirements, $option_meaning);

// party
$party = [
  'name' => $partiesjson->$_GET['party']->name,
  'score' => round($score),
  'party' => $partiesjson->$_GET['party'],
  'image' => ($widget ? '../': '') . 'image/party/'.$_GET['party'].".jpg",
  'term' => $term->name,
  'link' => ($widget ? '../': '') . 'party.php?party='. $_GET['party'] . $term_chunk,
  'color' => score2color(round($score)),
];

// members
$members = [];
foreach ($parties_members[$_GET['party']] as $voter_id) {
  $start_year = correct_year_for_photo(person_last_term_start_year($people->$voter_id,$terms,$term));
  $p = [];
  $p['score'] = round(person_match($voters->$voter_id, $filtered_issue->vote_events, $requirements, $option_meaning));
  $identifier = person_id2identifier($voter_id,$people);
  $p['link'] = 'person.php?identifier='.$identifier . $term_chunk;
  $p['count'] = count((array)$voters->$voter_id);
  $p['name'] = $people->$voter_id->name;
  $p['color'] = score2color($p['score']);
  $p['image'] = 'http://www.psp.cz/eknih/cdrom/'.$start_year.'ps/eknih/'.$start_year.'ps/poslanci/i'.$identifier.".jpg";
  $p['party'] = '';//$partiesjson->$_GET['party']->abbreviation;
  $members[] = $p;
}

// sort detailed votes by date
usort($members, function($a, $b) {
    return ($b['count'] - $a['count'])*1000 + ($b['score'] - $a['score']) ;
});

// by years
$years = [];
foreach ($filtered_issue->vote_events as $vekey => $ve) {
  $y = date('Y', strtotime($vote_events->$vekey->start_date));
  $years[$y] = $y;
}
sort($years);
$year_scores = [];
$period_names = [];
foreach ($years as $year) {
  $filtered = filter_vote_events($filtered_issue, $vote_events, $year.'-01-01', $year.'-12-31');
  $sc = group_match($parties->$_GET['party'], $filtered->vote_events, $requirements, $option_meaning);
  //last vote event:
  $voted = 0;
  foreach ($filtered->vote_events as $vekey => $ve) {
    foreach ($vote_events->$vekey->votes as $vkey => $v) {
      if (in_array($v->group_id,$partiesjson->$_GET['party']->children))
        $voted++;
    }
  }
  
  $ys = new stdClass();
  $ys->x= (integer) $year;
  $ys->y= round($sc);
  if (count((array)$filtered->vote_events) and ($sc !== false)) {
    $ys->size= ceil($voted/count((array)$filtered->vote_events));
    $year_scores[] = $ys;
    $period_names[$year] = (string) $year;
  }
}

$ser = new stdClass();
$ser->name = $partiesjson->$_GET['party']->name;
$ser->title = $partiesjson->$_GET['party']->abbreviation;
$ser->color = $partiesjson->$_GET['party']->color;
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

$smarty->assign('path',$path);
$smarty->assign('period_type', json_encode("year"));
$smarty->assign('period_names',json_encode($period_names));
$smarty->assign('series',json_encode($series));
$smarty->assign('chart_options',json_encode($chart_options));
$smarty->assign('show_chart',$show_chart);
$smarty->assign('header',$header);
$smarty->assign('party',$party);
$smarty->assign('issue',$issue);
$smarty->assign('members',$members);
$smarty->assign('title',$partiesjson->$_GET['party']->name);

?>
