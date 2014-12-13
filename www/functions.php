<?php
/**
Functions to calculate 
*/
/*$start = microtime(true);
// trial:
$vote_events = json_decode(file_get_contents("json/vote-events.json"));
$option_meaning = json_decode(file_get_contents("json/option-meaning.json"));
$issue = json_decode(file_get_contents("json/issue.json"));
$people = json_decode(file_get_contents("json/people.json"));
$partiesjson = json_decode(file_get_contents("json/parties.json"));
// about 0.08 sec.

echo microtime(true) - $start . "<br/>\n";

$voters = new stdClass();
$requirements = new stdClass();
$parties = new stdClass();

$group2party = new stdClass();
foreach ($partiesjson as $pkey => $party) {
  foreach ($party->children as $ckey => $child) {
    $group2party->$child = $pkey;
  }
}

$filtered = filter_vote_events($issue, $vote_events, "2013-01-01", "2013-12-31");

print_r($filtered);die();

foreach ($filtered->vote_events as $vekey => $ve) {
  foreach($vote_events->$vekey->votes as $vkey => $v) {
    $voter_id = $v->voter_id;
    $group_id = $v->group_id;
    $party_id = $group2party->$group_id;
    if (!isset($voters->$voter_id))
      $voters->$voter_id = new stdClass();
    if (!isset($parties->$party_id))
      $parties->$party_id = new stdClass();
    $voters->$voter_id->$vekey = $v->option;
    if (!isset($parties->$party_id->$vekey))
      $print_->$party_id->$vekey = [];
    array_push($parties->$party_id->$vekey, $v->option);
  }
  //print_r($vote_events->$vekey);die();
  $requirements->$vekey = $vote_events->$vekey->motion->requirement;
}
// about 0.005 sec.

$vv = "53e90837a874087103fa72aa";
print_r($parties->vv);die();

//print_r($voters);

foreach ($voters as $voter_id => $voter) {
  $m = person_match($voter, $issue->vote_events, $requirements, $option_meaning);
  //echo "{$voter_id}: {$m}<br/>\n";
}
// about 0.03 sec.

foreach ($parties as $party_id => $party) {
  $m = group_match($party, $issue->vote_events, $requirements, $option_meaning);
  echo "{$group_id}: {$m}<br/>\n";
}
*/

/**

*/
function person_identifier2id($identifier,$people) {
  foreach ($people as $person) {
    foreach ($person->identifiers as $ident) {
      if (($ident->scheme == "psp.cz/osoby") and ($ident->identifier == $identifier)) {
        return $person->id;
      }
    }
  }
  return False;
}

function person_id2identifier($id,$people) {
  return $id;
#  foreach ($people->$id->identifiers as $ident) {
#    if ($ident->scheme == "psp.cz/osoby")
#      return $ident->identifier;
#  }
  return False;
}

/**
Select subset of vote events

$start_date <= vote_event->start_date < $end_date
*/
function filter_vote_events($issue, $vote_events, $start_date, $end_date) {
  $out = new stdClass();
  $out->vote_events = new stdClass();
  foreach ($issue->vote_events as $vekey => $ve) {
    //print_r($ve);die();
    if (($vote_events->$vekey->start_date >= $start_date) and ($vote_events->$vekey->start_date < $end_date) and true)//($ve->available_vote_event))
      $out->vote_events->$vekey = $issue->vote_events->$vekey;
  }
  return $out;
}

/**
Select subset of vote events by tag
*/
function filter_vote_events_by_tag($issue, $vote_events, $tag) {
  $out = new stdClass();
  $out->vote_events = new stdClass();
  foreach ($issue->vote_events as $vekey => $ve) {
    //print_r($ve);die();
    if (isset($ve->subcategory)) {
      foreach ($ve->subcategory as $subcategory) {
        if ($subcategory == $tag)
          $out->vote_events->$vekey = $issue->vote_events->$vekey;
      }
    }
  }
  return $out;
}

/**
Calculates person's match

options: vote options by the person
    they does not have to be complete comparing issue's vote_events
example (json notation):
$options = {
  '56558': absent,
  '53601': yes,
  ...
}

vote_events: (issue's) vote_events that shall be taken into account
    weights have default 1
example (json notation):
$vote_events = {
  '56558': {
    "weight": 56,
    "pro_issue": -1,
  },
  '53601: {
    "pro_issue": 1
  },
  ...
}

requirements: requirement to pass the vote-event
example (json notation):
$requirements = {
  "56558": "simple majority",
  "53601": "all representatives majority",
  ...
}

option_meaning: meaning of options depending on the requirement
example (json notation):
$option_meaning = {
  "simple majority": {
    "options": {
      "yes": 1,
      "no": -1,
      "abstain": -1,
      "not voting": -1,
      "absent": 0
    }
  },
  ...
}

lo_limit: lower limit, excludes voters with too few vote-events (being a MP only a while)

*/
function person_match($options, $vote_events, $requirements, $option_meaning, $lo_limit=0) {
  //print_r($options);die();
  $match = 0;
  $voter_max = 0;
  $max = 0;
  foreach ($vote_events as $vekey => $ve) {
    $requirement = $requirements->$vekey;
    $weight = (isset($ve->weight) ? $ve->weight : 1);
    if (isset( $options->$vekey)) {
      //print_r($options->$vekey);die();
      $option = $options->$vekey;
      $match += $ve->pro_issue * $option_meaning->$requirement->options->$option * $weight;
      $voter_max += $weight;
    }
    $max += $weight;
  }
  if ($voter_max == 0) return false;
  if ($voter_max > $lo_limit * $max) {
    return ($match/$voter_max + 1)*50;
  } else {
    return false;
  }
}

/**
Calculates single match (one person, one vote)
*/
function single_match($option,$pro_issue,$option_meaning,$requirement) {
  return $pro_issue * $option_meaning->$requirement->options->$option;
}

/**
Calculates group's (party's) match

group_options: list of vote options by the persons of the group
    they does not have to be complete comparing issue's vote_events
example (json notation):
$group_options = {
  '56558': ['absent','yes','no',...],
  '53601': ['yes']
  ...
}

The rest is the same as it is for person_match()

*/

function group_match($group_options, $vote_events, $requirements, $option_meaning, $lo_limit=0) {
  $match = 0;
  $voter_max = 0;
  $max = 0;
  foreach ($vote_events as $vekey => $ve) {
    $requirement = $requirements->$vekey;
    $weight = (isset($ve->weight) ? $ve->weight : 1);
    if (isset( $group_options->$vekey)) {
      $c = count($group_options->$vekey); //so $voter_max is comparable with $max
      //echo "$c ";
      foreach ($group_options->$vekey as $option) {
        $match += $ve->pro_issue * $option_meaning->$requirement->options->$option * $weight / $c;
        $voter_max += $weight / $c;
      }
    }
    $max += $weight;
  }
  if ($voter_max > $lo_limit * $max) {
    return ($match/$voter_max + 1)*50;
  } else {
    return false;
  }
}




function strip_term($get) {
  $new = [];
  foreach ($get as $key => $g) {
    if ($key != 'term') $new[] = $key . '=' . $g;
  }
  return implode('&',$new);
}

function score2color($score) {
  if ($score >= 80)
    return "#2c851c;";
  if ($score >= 60)
    return "#70AA63;";
  if ($score >= 40)
    return "#CCC;";
  if ($score >= 20)
    return "#F89291;";
  return "#EC6863;";
}

function term2term($term_identifier,$terms,$default) {
    if (!isset($term_identifier)) return $default;
    foreach ($terms as $term) {
      if ($term_identifier == $term->identifier) return $term;  
    }
    return $default;
}

function single_match2color ($sm) {
  if ($sm == 1) return "ok-color";
  if ($sm == -1) return "ko-color";
  return "";
}

function single_match2opacity ($sm) {
  if ($sm == 0) return 0.1;
  return 1;
}

function person_last_term_start_year ($person, $terms, $term) {
  //if it is a term, use it, if it is a year, find it's corresponding
  if (isset($term->type)) {
    if($term->type == 'parliamentary_term') {
      $ar = explode('-',$term->start_date);
      return $ar[0];
    } else {
      foreach ($terms as $t) {
        if (isset($t->end_date)) {
          if (($t->start_date <= $term->start_date) and ($t->end_date >= $term->start_date)) {
            $ar = explode('-',$t->start_date);
            return $ar[0];
          }
        } else {
          if ($t->start_date <= $term->start_date) {
            $ar = explode('-',$t->start_date);
            return $ar[0];
          }
        }
      }
    }
  }
  //else find most recent photo
  $max = 0;
  foreach($person->identifiers as $ident) {
    if (strpos($ident->scheme,'psp.cz/poslanec') !== false) {
      $ar = explode('/',$ident->scheme);
      if ($ar[2] > $max)
        $max = $ar[2];
    }
  }
  foreach ($terms as $t) {
    if ($t->identifier == $max) {
      $ar = explode('-',$t->start_date);
      return $ar[0];
    }
  }
  //hot fix for last period:
  return '2013';
}

function correct_year_for_photo($year) {
  if ($year == '1992') return '1993';
  return $year;
}

function set_error($message,$smarty,$text) {
    $error = [
      'error' => true,
      'description' => $message
    ];
    $smarty->assign('title',$text[$error['description']]);
    $smarty->assign('error',$error);
    return $error;
}

/*$time_elapsed = microtime(true) - $start;
echo "time: " . $time_elapsed0 . " " .$time_elapsed . "<br/>\n";*/
?>
