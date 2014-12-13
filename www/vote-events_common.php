<?php

$vote_events = json_decode(file_get_contents($path . "json/vote-events.json"));

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
    //by tag
if (isset($_GET['tag']) and (trim($_GET['tag']) != '')) {
  $tag = urldecode(trim($_GET['tag']));
  $filtered_issue = filter_vote_events_by_tag($filtered_issue, $vote_events, $tag);
} else {
  $tag = '';
}

$filtered_issue_sorted = []; //for detailed display
foreach ($filtered_issue->vote_events as $vekey => $ve) {
  foreach($vote_events as $vkey => $v) {
    if ($v->identifier == $vekey) {
      $ve->start_date = $v->start_date;
#      if ($v->result == 'pass') $res = 1;
#      else $res = -1;
#      $ve->result = $v->result;
#      $ve->ok = $res*$ve->pro_issue;
       $ve->ok = 1;
      $ve->motion = $v->motion;
#      if ($vekey == '58281'){
#                  print_r($v);print_r($ve);die();}
      $filtered_issue_sorted[] = $ve;
    }
  }
}
// sort filtered issue by date
usort($filtered_issue_sorted, function($a, $b) {
    return strtotime($b->start_date) - strtotime($a->start_date);
});
$smarty->assign('issue',$issue);
$smarty->assign('vote_events',$filtered_issue_sorted);
$smarty->assign('tag',$tag);
$smarty->assign('term',$term);
$smarty->assign('title',$text['vote-events']);


?>
