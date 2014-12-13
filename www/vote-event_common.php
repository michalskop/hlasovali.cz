<?php
/**
common part for vote-event
*/



$vote_events = json_decode(file_get_contents($path . "json/vote-events.json"));
$option_meaning = json_decode(file_get_contents($path . "json/option-meaning.json"));
$people = json_decode(file_get_contents($path . "json/people.json"));
$partiesjson = json_decode(file_get_contents($path . "json/parties.json"));

if (!isset($_GET['identifier']) or $_GET['identifier'] == '') {
    $smarty->assign('error_message',$text['vote-event_no_identifier_message']);
    $smarty->display('error.tpl');
    die();
}
    
$ve_id = trim($_GET['identifier']);

$parties = [];

$group2party = new stdClass();
foreach ($partiesjson as $pkey => $party) {
  foreach ($party->children as $ckey => $child) {
    $group2party->$child = $pkey;
  }
}

if (!isset($vote_events->$ve_id) or !isset($vote_events->$ve_id->votes) or count($vote_events->$ve_id->votes) == 0) {
    $error = [
      'error' => true,
      'description' => 'vote-event_unknown_identifier_warning'
    ];
    $smarty->assign('title',$text[$error['description']]);
    $smarty->assign('error',$error);
    return;
}

foreach($vote_events->$ve_id->votes as $vkey => $v) {
  $voter_id = $v->voter_id;
  $group_id = $v->group_id;
  $party_id = $group2party->$group_id;
  if (!isset($parties[$party_id])) {
    $parties[$party_id] = $partiesjson->$party_id;
    $parties[$party_id]->people = [];
    $parties[$party_id]->link = $path . 'party.php?party=' . $party_id . $term_chunk;
  }
  $p = new stdClass();
  $p->link = $path . 'person.php?identifier='.person_id2identifier($voter_id,$people) . $term_chunk;
  $p->name = $people->$voter_id->name;
#  $p->family_name = $people->$voter_id->family_name;
#  $p->given_name = $people->$voter_id->given_name;
  //print_r($v);die();
  $p->single_match = single_match($v->option,1,$option_meaning,$vote_events->$ve_id->motion->requirement);
  $p->option = $text['vote_options'][$v->option];
  $p->background = single_match2color($p->single_match);
  $p->opacity = single_match2opacity($p->single_match);
  $parties[$party_id]->people[] = $p;
}

// sort parties by position
usort($parties, function($a, $b) {
  return $a->position - $b->position;
});

foreach ($parties as $key=>$party) {
  usort($parties[$key]->people, function($a, $b) {
    return $a->single_match - $b->single_match;
  });
}

//parties are not good enough (for chart), let's make virtual parties
$virtualparties = [];

foreach($parties as $party) {
  foreach ([-1,0,1] as $sm) {
    $nparty = clone $party;
    unset($nparty->people);
    $nparty->single_match = $sm;
    $virtualparties[] = $nparty;
  }
}

foreach ($parties as $key => $party) {
  $virtualparties[3*$key]->people = [];
  $virtualparties[3*$key+1]->people = [];
  $virtualparties[3*$key+2]->people = [];
  foreach ($party->people as $person) {
    $virtualparties[3*$key+1+$person->single_match]->people[] = $person;
  }
}

usort($virtualparties, function($a, $b) {
  return ($a->single_match - $b->single_match)*1000 + $a->position - $b->position;
});

usort($parties, function($a, $b) {
  return (count($b->people) - count($a->people));
});

foreach($parties as $key => $party) {
  usort($parties[$key]->people, function($a, $b) {
    return (strcoll($a->name,$b->name));
  });
}

//vote event
$vote_event = (object)array_merge((array)$issue->vote_events->$ve_id, (array)$vote_events->$ve_id);

$smarty->assign('title',$issue->vote_events->$ve_id->name);
$smarty->assign('vote_event',$vote_event);
$smarty->assign('issue',$issue);
$smarty->assign('virtualparties',json_encode($virtualparties));
$smarty->assign('parties',$parties);

?>
