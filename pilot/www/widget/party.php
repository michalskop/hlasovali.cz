<?php

$widget = true;
include_once('../settings.php');
include_once('../include.php');

include_once('../party_common.php');
//print_r($party);die();
$smarty->display('party-widget.tpl');

// put full path to Smarty.class.php
#require('/usr/local/lib/php/Smarty/libs/Smarty.class.php');
#$smarty = new Smarty();
#$smarty->setTemplateDir('../../smarty/templates/');
#$smarty->setCompileDir('../../smarty/templates_c');

#include_once("../functions.php");
#include_once("../text.php");

#$vote_events = json_decode(file_get_contents("../json/vote-events.json"));
#$option_meaning = json_decode(file_get_contents("../json/option-meaning.json"));
#$issue = json_decode(file_get_contents("../json/issue.json"));
#$people = json_decode(file_get_contents("../json/people.json"));
#$partiesjson = json_decode(file_get_contents("../json/parties.json"));

#$requirements = new stdClass();
#$parties = new stdClass();

#$group2party = new stdClass();
#foreach ($partiesjson as $pkey => $party) {
#  foreach ($party->children as $ckey => $child) {
#    $group2party->$child = $pkey;
#  }
#}

#foreach ($issue->vote_events as $vekey => $ve) {
#  foreach($vote_events->$vekey->votes as $vkey => $v) {
#    $voter_id = $v->voter_id;
#    $group_id = $v->group_id;
#    $party_id = $group2party->$group_id;
#    if (!isset($parties->$party_id))
#      $parties->$party_id = new stdClass();
#    if (!isset($parties->$party_id->$vekey))
#      $parties->$party_id->$vekey = [];
#    array_push($parties->$party_id->$vekey, $v->option);
#  }
#  //print_r($vote_events->$vekey);die();
#  $requirements->$vekey = $vote_events->$vekey->motion->requirement;
#}

#$score = group_match($parties->$_GET['party'], $issue->vote_events, $requirements, $option_meaning);

#//print_r($parties);die();

#// party
#$party = [
#  'name' => $partiesjson->$_GET['party']->name,
#  'score' => round($score),
#  'party' => $partiesjson->$_GET['party'],
#  'image' => '../image/party/'.$_GET['party'].".png",
#];

#$smarty->assign('party',$party);
#$smarty->assign('text',$text);
#$smarty->assign('title',$partiesjson->$_GET['party']->name);
#$smarty->assign('color',score2color(round($score)));
#$smarty->assign('link',"#");

#$smarty->display('party-widget.tpl');



?>
