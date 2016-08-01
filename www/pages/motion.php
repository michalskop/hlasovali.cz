<?php

// motion

spl_autoload_register(function ($class) {
    global $settings;
    include $settings->app_path . 'www/classes/' . $class . '.php';
});

switch ($action) {
    case 'new':
        neww();
        break;
    case 'create':
        create();
        break;
    case 'update':
        update();
        break;
    case 'delete':
        deletee();
        break;
    case 'view':
    default:
        view();
}

// deletes motion and its vote_events and votes
// note: cannot just delete motion because of problems with triggers
function deletee() {
    global $user, $settings;

    if (isset($_GET['m'])) {
        $motion = new Motion($settings);

        $vote_event = new VoteEvent($settings);
        $vote = new Vote($settings);

        $params = [
            "motion_id" => "eq.". $_GET['m']
        ];
        $vote_events = $vote_event->getVoteEvents($params);

        foreach ($vote_events as $ve) {
            $params = [
                "vote_event_id" => "eq." . $ve->id
            ];
            $votes = $vote->getVotes($params);
            foreach ($votes as $v) {
                $vote->delete($v->id);
            }
            $vote_event->delete($ve->id);
        }
        $motion->delete($_GET['m']);
    }



    die();

    //go to all motions
    header("Location: index.php");

}

function update() {
    global $user, $cityhall, $settings;

    //update motion
    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');
    $motion = new Motion($settings);
    $data = $motion->parseForm($_POST);
    $data['organization_id'] = $cityhall->getCityHall()->id;
    $data['user_id'] = $user->getCurrentUser()->id;
    $new_motion = $motion->update($data,$_POST['motion_id']);

    //update tags
    $tags = new Tag($settings);
    $t_arr = $tags->parseForm($_POST);
    $tags->update($t_arr,$_POST['motion_id']);

    //update vote event
    $vote_event = new VoteEvent($settings);
    $data = [
        'motion_id' => $new_motion->id,
        'start_date' => $new_motion->date,
        'date_precision' => $new_motion->date_precision
    ];
    $parsed = $vote_event->parseForm($_POST);
    if (isset($parsed['vote_event_identifier'])) {
        $data['identifier'] = $parsed['vote_event_identifier'];
    }
    if (isset($parsed['vote_event_result'])){
        if (isset($parsed['default_vote_event_result'])) {
            if ($parsed['vote_event_result'] == 'on') {
                $parsed['vote_event_result'] = $parsed['default_vote_event_result'];
            }
        }
        $data['result'] = $parsed['vote_event_result'];
    }
    $new_vote_event = $vote_event->update($data,$_POST['vote_event_id']);

    //create/update organizations(parties)
    $table = new Table($settings);
    $organization = new Organization($settings);
    $organizations = _create_update_organizations($parsed,$table,$organization);

    //create/update people
    $person = new Person($settings);
    $people = _create_update_people($parsed,$table,$person);

    //update votes
    _update_votes($parsed, $organizations, $people, $new_vote_event->id);

    header("Location: " . $_GET['continue']);
}

function create() {
    global $user, $cityhall, $settings;

    //create motion
    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');
    $motion = new Motion($settings);
    $data = $motion->parseForm($_POST);
    $data['organization_id'] = $cityhall->getCityHall()->id;
    $data['user_id'] = $user->getCurrentUser()->id;
    $new_motion = $motion->create($data);

    //create tags
    $tags = new Tag($settings);
    $t_arr = $tags->parseForm($_POST);
    $tdata = [];
    foreach ($t_arr as $tag) {
        $item = new StdClass();
        $item->tag = $tag;
        $item->motion_id = $new_motion->id;
        $item->active = TRUE;
        $tdata[] = $item;
    }
    $tags->create($tdata);

    //create vote event
    $vote_event = new VoteEvent($settings);
    $data = [
        'motion_id' => $new_motion->id,
        'start_date' => $new_motion->date,
        'date_precision' => $new_motion->date_precision
    ];
    $parsed = $vote_event->parseForm($_POST);
    if (isset($parsed['vote_event_identifier'])) {
        $data['identifier'] = $parsed['vote_event_identifier'];
    }
    if (isset($parsed['vote_event_result']) and isset($parsed['default_vote_event_result'])) {
        if ($parsed['vote_event_result'] == 'on') {
            $parsed['vote_event_result'] = $parsed['default_vote_event_result'];
        }
        $data['result'] = $parsed['vote_event_result'];
    }
    $new_vote_event = $vote_event->create($data);

    //create/update organizations(parties)
    $table = new Table($settings);
    $organization = new Organization($settings);
    $organizations = _create_update_organizations($parsed,$table,$organization);

    //create/update people
    $person = new Person($settings);
    $people = _create_update_people($parsed,$table,$person);

    //create votes
    _create_votes($parsed, $organizations, $people, $new_vote_event->id);

    //go to the new motion
    header("Location: index.php?page=motion&action=view&m=" . $new_motion->id);
}

function neww() {
    global $user, $cityhall, $settings, $smarty, $t;
    if ($cityhall->information->selected) {
        $smarty->assign('user_has_author_privilages', $user->hasAuthorPrivilages($cityhall->information->id));

        //vote event
        $form = _vote_event_table('new');

        $smarty->assign('form_organizations',json_encode($form['organizations']));
        $smarty->assign('form_family',json_encode($form['family']));
        $smarty->assign('form_rows',json_encode($form['rows']));
        $smarty->assign('form_t',json_encode($form['t']));
    }

    $smarty->display('motion_new.tpl');
}

function view_list() {
    global $user, $settings, $cityhall, $smarty;

    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');

    $motion = new Motion($settings);

    //get list of motions
    $params = ["order" => "motion_date.desc,vote_event_identifier.desc,motion_id.desc"];
    $filters = [];
    if (isset($cityhall->id)) {
        $params['organization_id'] = 'eq.'.$cityhall->id;
        $filters[] = ['name'=> 'cityhall', 'value'=> $cityhall->name];
    }
    if (isset($_GET['since'])) {
        $params['motion_date'] = 'gte.'.$_GET['since'];
        $filters[] = ['name'=> 'since', 'value'=> $_GET['since']];
    }
    if (isset($_GET['until'])) {
        $params['motion_date'] = 'lt.'.$_GET['until'];
        $filters[] = ['name'=> 'until', 'value'=> $_GET['until']];
    }
    if (isset($_GET['u'])) {
        $params['user_id'] = 'eq.'.$_GET['u'];
        $u = $user->getUser($_GET['u']);
        if ($u->exist) {
            $filters[] = ['name'=> 'created_by_author', 'value'=> $u->name];
        } else {
            $filters[] = ['name'=> 'created_by_author', 'value'=> '?'];
        }
    }
    if (isset($_GET['start'])) {
        $params['offset'] = 'eq.'.$_GET['start'];
    }
    $tag = new Tag($settings);
    if (isset($_GET['tag'])) {
        $tags = $tag->getTags(['tag'=>'eq.'.$_GET['tag']]);
        $allowed = [];
        foreach ($tags as $ta) {
            $allowed[] = $ta->motion_id;
        }
        $params['motion_id'] = "in." . implode(',', $allowed);
        $filters[] = ['name'=> 'tag', 'value'=> $_GET['tag']];
    }

    $motions = $motion->getMotionsInfo($params);

    //preformat date/time
    foreach($motions as $m) {
        $m->formatted_datetime = preformat_date($m->motion_date,$m->motion_date_precision);
        $m->tags = $tag->getTags(["motion_id" => "eq.".$m->motion_id]);
        $m->id = $m->motion_id;
        //filter description even more
        $m->motion_description = purify_html($m->motion_description,'br,hr');
    }

    //author?
    if (isset($cityhall->information) and $cityhall->information->selected) {
        $smarty->assign('user_has_author_privilages', $user->hasAuthorPrivilages($cityhall->information->id));
    } else {
        $smarty->assign('user_has_author_privilages',false);
    }

    $smarty->assign('motions',$motions);
    $smarty->assign('filters',$filters);

    $smarty->display('motion_list.tpl');

    exit;
}

function view() {
    if (!isset($_GET['m'])) {
        view_list();
    }
    global $user, $settings, $cityhall, $smarty, $t;

    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');

    //motion + can edit/can create new

    $motion = new Motion($settings);
    $tags = new Tag($settings);
    $m = $motion->getMotion($_GET['m']);
    $smarty->assign('motion',$m);
    if ($m->exist) {
        $smarty->assign('date_and_time',preformat_date($m->date,$m->date_precision));
        $cityhall->setCookie($m->organization_id);
        $smarty->assign('author',$user->getUser($m->user_id));
        // cityhall can change:
        $smarty->assign('cityhall', $cityhall->getCityHall());
        $smarty->assign('cityhalls', $cityhall->selectFrom());
    } else {
        $smarty->assign('author',$user->getUser());
    }
    $smarty->assign('tags',$tags->getTags(['motion_id' => "eq." . $_GET['m']]));
    $smarty->assign('user_can_edit', $user->canEditMotion($_GET['m']));

    if (isset($cityhall->information) and $cityhall->information->selected) {
        $smarty->assign('user_has_author_privilages', $user->hasAuthorPrivilages($cityhall->information->id));
    } else {
        $smarty->assign('user_has_author_privilages',false);
    }

    //vote event
    $table = new Table($settings);
    if ($m->exist) {
        $params = ['motion_id'=>'eq.'.$m->id];
    } else {
        $params = ['motion_id'=>NULL];
    }
    $ve_info = $table->getTable('vote_events_information','one',$params);
    if ($ve_info->exist) {
        $form = _vote_event_table('view',$ve_info->vote_event_id);
        $smarty->assign('vote_event',$ve_info);
        $smarty->assign('result',$ve_info->vote_event_result);

    } else {
        $form = _vote_event_table('view');
    }

    $hemicycle = _hemicycle($form['rows']);

    $smarty->assign('hemicycle',$hemicycle);
    $smarty->assign('table_rows',$form['rows']);
    $smarty->assign('form_organizations',json_encode($form['organizations']));
    $smarty->assign('form_family',json_encode($form['family']));
    $smarty->assign('form_rows',json_encode($form['rows']));
    $smarty->assign('form_t',json_encode($form['t']));

    $smarty->display('motion_view.tpl');
}

function _hemicycle($rows) {
    global $settings;

    $data = [];
    $counts = ["against"=>0,"for"=>0,"all"=>0];
    $orloj_arr = [];
    foreach ($rows as $row) {
        $item = new StdClass();
        $item->color = $row->organization_color;
        $item->name = $row->given_name . ' ' . $row->family_name;
        $item->option_code = _hemicycle_option2code($row->option);
        if ($item->option_code == 1) {
            $counts['for']++;
        } elseif ($item->option_code == -1) {
            $counts['against']++;
        }
        $counts["all"]++;
        $item->option = $row->option;
        $item->party = $row->organization_abbreviation;
        $orloj_arr[$item->party] = [
            "color" => $item->color,
            "text" => $item->party
        ];
        $data[] = $item;
    }

    //change orloj to pure array
    $orloj = [];
    foreach ($orloj_arr as $o) {
        $orloj[] = $o;
    }

    // numbers in hemicycle rows
    $hems = json_decode(file_get_contents($settings->app_path . 'www/pages/hemicycle.data.json'));
    $n_str = (string) count($data);
    $ns = _hemicycle_optimal_rows(count($data),$hems->$n_str);

    //sort data
    foreach ($data as $key => $row) {
        $option_code[$key]  = -1*$row->option_code;
        $party[$key] = $row->party;
    }
    array_multisort($option_code, SORT_ASC, $party, SORT_ASC, $data);

    // //result
    // if ($counts['for']>$counts['against']) {
    //     $result = 'pass';
    // } elseif ($counts['all']>0) {
    //     $result = 'fail';
    // } else {
    //     $result = FALSE;
    // }

    return ['data'=>json_encode($data),'dat'=>json_encode($ns),'counts'=>json_encode($counts), 'orloj'=>json_encode($orloj)];
}

function _hemicycle_option2code($o) {
    if ($o == "yes") {
        return 1;
    }
    return -1;
}

function _hemicycle_optimal_rows($n,$adepts) {
    $best = floor(pow(((int)$n)/2 + 1, 0.48));
    $beststr = (string) $best;
    if (isset($adepts->$best)) {
        return $adepts->$best;
    }
    $closest_distance = $best;
    foreach($adepts as $key => $adept) {
        if (abs($key - $best) < $closest_distance) {
                $closest = $key;
        }
    }
    if (isset($adepts->$closest)) {
        return $adepts->$closest;
    }
    $out = new stdClass();
    $out->n = $n;
    return [$out];
}

//if id is null, get info about last vote event in city hall without votes
function _vote_event_table($action='edit',$id=NULL) {
    global $cityhall, $settings, $t, $user;

    $form_t = $t->getAll(); //texts for handlebars
    $organization = new Organization($settings);

    //for typeahead
    $form_organizations = [];
    $form_family = [];
    if (($action == 'new') or ($user->canEditMotion($_GET['m'])) ){
            //list of parties for typeahead
        $most = $organization->getOrganizationsWithVotes([
            "order"=>"count.desc"
        ]);
        $locals = $organization->getOrganizations([
            "parent_id"=>"eq.".$cityhall->getCityHall()->id,
            "classification"=>"eq.political group",
        ]);

        $org_names = [];
        foreach ($most as $o){
            if (!in_array($o->name,$org_names)) {
                $form_organizations[] = $o;
                $org_names[] = $o->name;
            }
        }
        foreach ($locals as $o){
            if (!in_array($o->name,$org_names)) {
                $form_organizations[] = $o;
                $org_names[] = $o->name;
            }
        }

        //family names
        $person = new Person($settings);
        $ever_voted = $person->getPeopleVotedInOrganizations([
            "organization_parent_id" => "eq.". $cityhall->getCityHall()->id
        ]);
        $form_family = [];
        foreach($ever_voted as $ev) {
            $form_family[] = $ev->person_family_name;
        }
    }

    $form_rows = [];
    $i = 0;
    $vote_event = new VoteEvent($settings);
    if ($action == 'new') {
        $ve_info = $vote_event->getLastVoteEvent([
            "organization_id" => "eq.". $cityhall->getCityHall()->id
        ]);
        if ($ve_info->exist) {
            $ve_id = $ve_info->vote_event_id;
        } else {
            $ve_id = NULL;
        }
    } else {
        $ve_info = $vote_event->getVoteEvent($id);
        if ($ve_info->exist) {
            $ve_id = $ve_info->id;
        } else {
            $ve_id = NULL;
        }
    }

    if ($ve_info->exist) {
        $ves = $vote_event->getVoteEventVotes([
            "vote_event_id"=> "eq.".$ve_id,
            "order" => "organization_name.asc,person_family_name.asc,person_given_name.asc"
        ]);
        foreach($ves as $ve){
            $item = new StdClass();
            $item->i = $i;
            $item->id = $ve->person_id;
            $item->family_name = $ve->person_family_name;
            $item->given_name = $ve->person_given_name;
            $item->organization_name = $ve->organization_name;
            if (isset($ve->organization_attributes->abbreviation)) {
                $item->organization_abbreviation = $ve->organization_attributes->abbreviation;
            } else {
                $item->organization_abbreviation = "";
            }
            if (isset($ve->organization_attributes->color)) {
                $item->organization_color = $ve->organization_attributes->color;
            } else {
                $item->organization_color = "#000000";
            }
            if ($action == 'new') {
                $item->option = 'nothing';
            } else {
                $item->option = $ve->vote_option;
            }
            _option4handlebars($item);
            $form_rows[] = $item;
            $i++;
        }
    }
    $form = [
        'organizations'=> $form_organizations,
        'family' => $form_family,
        'rows' => $form_rows,
        't' => $form_t
    ];
    return $form;
}

function _option4handlebars($item) {
    $item->option_is_yes = false;
    $item->option_is_no = false;
    $item->option_is_abstain = false;
    $item->option_is_absent = false;
    switch ($item->option) {
        case "yes":
            $item->option_is_yes = true;
            break;
        case "no":
            $item->option_is_no = true;
            break;
        case "abstain":
            $item->option_is_abstain = true;
            break;
        case "absent":
            $item->option_is_absent = true;
            break;
    }
}

function _update_votes($parsed, $organizations, $people, $vote_event_id) {
    global $settings;
    $vote = new Vote($settings);

    $params = ['vote_event_id' => 'eq.'.$vote_event_id];
    $existing = $vote->getVotes($params);

    $votes = [];
    foreach ($parsed['rows'] as $row) {
        if ($row['option'] == 'on') {
            $row['option'] = $row['default_option'];
        }

        if (in_array($row['option'],$vote->allowed_options)) {
            $v = new StdClass();
            $v->option = $row['option'];
            $name = $row['family_name'] . '+' . $row['given_name'];
            $v->person_id = $people[$name]->id;
            $v->organization_id = $organizations[$row['organization_name']]->id;
            $v->vote_event_id = $vote_event_id;
            $votes[] = $v;
        }
    }

    $existing_arr = [];
    foreach ($existing as $e) {
        $existing_arr[$e->person_id] = $e;
    }
    $votes_arr = [];
    foreach ($votes as $v) {
        $votes_arr[$v->person_id] = $v;
    }

    $changing = [];
    $deleting = [];
    $creating = [];
    foreach($votes_arr as $k=>$v) {
        if (isset($existing_arr[$k])) {
            $e = $existing_arr[$k];
            if (($e->organization_id == $v->organization_id) and
                ($e->option == $v->option)
            ) {
                //it is ok, kept
            } else {
                $it = $v;
                $it->id = $e->id;
                $changing[] = $it;
            }
        } else {
            $creating[] = $v;
        }
    }
    foreach($existing_arr as $k=>$e) {
        if (!isset($votes_arr[$k])) {
            $deleting[] = $e;
        }
    }

    foreach($changing as $v) {
        $vote->update($v,$v->id);
    }
    foreach($deleting as $v) {
        $vote->delete($v->id);
    }
    $vote->create($creating);
}

function _create_votes($parsed, $organizations, $people, $vote_event_id) {
    global $settings;
    $vote = new Vote($settings);

    $votes = [];
    foreach ($parsed['rows'] as $row) {
        if ($row['option'] == 'on') {
            $row['option'] = $row['default_option'];
        }
        if (in_array($row['option'],$vote->allowed_options)) {
            $v = new StdClass();
            $v->option = $row['option'];
            $name = $row['family_name'] . '+' . $row['given_name'];
            $v->person_id = $people[$name]->id;
            $v->organization_id = $organizations[$row['organization_name']]->id;
            $v->vote_event_id = $vote_event_id;
            $votes[] = $v;
        }
    }
    $vote->create($votes);
}

function _create_update_people($parsed, $table, $person) {
    global $cityhall;

    $people = [];
    $parsed_people = [];
    $people_out = [];
    foreach($parsed['rows'] as $row) {
        $params = [
            'person_family_name' => "eq." . $row['family_name'],
            'person_given_name' => "eq." . $row['given_name'],
            'organization_parent_id' => "eq." . $cityhall->getCityHall()->id
        ];
        $name = $row['family_name'] . '+' . $row['given_name'];
        $people[$name] = $table->get_one('people_voted_in_organizations',$params);
        $parsed_people[$name] = $row;
    }
    foreach ($people as $key=>$p) {
        if ($p->exist) {
            $new = new StdClass();
            $new->family_name = $p->person_family_name;
            $new->given_name = $p->person_given_name;
            $new->id = $p->person_id;
            $people_out[$key] = $new;
        } else {
            $new = [
                'family_name' => $parsed_people[$key]['family_name'],
                'given_name' => $parsed_people[$key]['given_name']
            ];
            $people_out[$key] = $person->create($new);
        }
    }
    return $people_out;

}

function _create_update_organizations($parsed, $table, $organization){
    global $cityhall, $user;

    $organizations = [];
    $parsed_orgs = [];
    foreach($parsed['rows'] as $row) {
        if (!isset($organizations[$row['organization_name']])) {
            $params = [
                'name' => "eq." . $row['organization_name'],
                'parent_id' => "eq." . $cityhall->getCityHall()->id,
                'classification' => 'eq.political group'
            ];
            $organizations[$row['organization_name']] = $table->get_one('organizations',$params);
            $parsed_orgs[$row['organization_name']] = $row;
        }
    }

    foreach ($organizations as $key => $org) {
        if ($org->exist) {
            if (!isset($org->attributes)) {
                $org->attributes = new StdClass();
            }
            if (!isset($org->attributes->abbreviation) or
                !isset($org->attributes->color) or
                ($org->attributes->abbreviation != $parsed_orgs[$org->name]['organization_abbreviation']) or
                ($org->attributes->color != $parsed_orgs[$org->name]['organization_color'])
                ) {
                $org->attributes->abbreviation = $parsed_orgs[$org->name]['organization_abbreviation'];
                $org->attributes->color = $parsed_orgs[$org->name]['organization_color'];
                unset($org->exist);
                $organizations[$org->name] = $organization->update((array) $org, $org->id);
            }
        } else {
            $attrs = new StdClass();
            $attrs->abbreviation = $parsed_orgs[$key]['organization_abbreviation'];
            $attrs->color = $parsed_orgs[$key]['organization_color'];
            $new = [
                'name' => $parsed_orgs[$key]['organization_name'],
                'classification'=> 'political group',
                'parent_id'=> $cityhall->getCityHall()->id,
                'attributes'=> $attrs
            ];
            $organizations[$key] = $organization->create($new);
            //and give rights to the current user:
            $item = [
                'organization_id' => $organizations[$key]->id,
                'user_id' => $user->getCurrentUser()->id,
                'active' => TRUE
            ];
            $table->creates('organizations_users',$item);
        }
    }
    return $organizations;
}


?>
