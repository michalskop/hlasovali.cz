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
    case 'view':
    default:
        view();
}

function update() {
    global $user, $cityhall, $settings;

    //update motion
    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');
    $motion = new Motion($settings);
    $data = $motion->parseForm($_POST);
    $data['organization_id'] = $cityhall->getCityHall()->id;
    $current_user = $user->getCurrentUser();
    if ($current_user->logged){
        $data['user_id'] = $current_user->id;
    }
    $new_motion = $motion->update($data,$_POST['motion_id']);

    //update tags
    $tags = new Tag($settings);
    $t_arr = $tags->parseForm($_POST);
    $tags->update($t_arr,$_POST['motion_id']);
    header("Location: " . $_GET['continue']);
}

function create() {
    global $user, $cityhall, $settings;

    //create motion
    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');
    $motion = new Motion($settings);
    $data = $motion->parseForm($_POST);
    $data['organization_id'] = $cityhall->getCityHall()->id;
    $data['user_id'] = $user->getUser()->id;
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

    header("Location: index.php?page=motion&action=view&m=" . $new_motion->id);
}

function neww() {
    global $user, $cityhall, $settings, $smarty, $t;
    if ($cityhall->information->selected) {
        $smarty->assign('user_has_author_privilages', $user->hasAuthorPrivilages($cityhall->information->id));
    }

    //vote event
    $form = _vote_event_table('new');

    $smarty->assign('form_organizations',json_encode($form['organizations']));
    $smarty->assign('form_family',json_encode($form['family']));
    $smarty->assign('form_rows',json_encode($form['rows']));
    $smarty->assign('form_t',json_encode($form['t']));
    $smarty->display('motion_new.tpl');
}

function view() {
    global $user, $settings, $cityhall, $smarty, $t;

    require_once($settings->app_path . 'www/helpers/globalfunctions_helper.php');

    //motion + can edit/can create new
    $motion = new Motion($settings);
    $tags = new Tag($settings);
    if (isset($_GET['m'])) {
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
        $smarty->assign('tags',$tags->getTags($_GET['m']));
        $smarty->assign('user_can_edit', $user->canEditMotion($_GET['m']));
    } else {
        $motion->getMotion();
        $smarty->assign('motion',$m);
        $smarty->assign('tags',[]);
        $smarty->assign('user_can_edit', false);
        $smarty->assign('author',$user->getUser());
    }
    if (isset($cityhall->information) and $cityhall->information->selected) {
        $smarty->assign('user_has_author_privilages', $user->hasAuthorPrivilages($cityhall->information->id));
    } else {
        $smarty->assign('user_has_author_privilages',false);
    }

    //vote event
    $view = new View($settings);
    if ($m->exist) {
        $params = ['motion_id'=>'eq.'.$m->id];
    } else {
        $params = ['motion_id'=>NULL];
    }
    $ve_info = $view->getView('vote_events_information','one',$params);
    if ($ve_info->exist) {
        $form = _vote_event_table('view',$ve_info->vote_event_id);
    } else {
        $form = _vote_event_table('view');
    }

    $smarty->assign('form_organizations',json_encode($form['organizations']));
    $smarty->assign('form_family',json_encode($form['family']));
    $smarty->assign('form_rows',json_encode($form['rows']));
    $smarty->assign('form_t',json_encode($form['t']));

    $smarty->display('motion_view.tpl');
}

//if id is null, get info about last vote event in city hall without votes
function _vote_event_table($action='edit',$id=NULL) {
    global $cityhall, $settings, $t;

    $form_t = $t->getAll(); //texts for handlebars
    $organization = new Organization($settings);

        //list of parties for typeahead
    $most = $organization->getOrganizationsWithVotes([
        "order"=>"count.desc"
    ]);
    $locals = $organization->getOrganizations([
        "parent_id"=>"eq.".$cityhall->getCityHall()->id,
        "classification"=>"eq.political group",
    ]);
    $form_organizations = [];
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

    $person = new Person($settings);
    $ever_voted = $person->getPeopleVotedInOrganizations([
        "organization_parent_id" => "eq.". $cityhall->getCityHall()->id
    ]);
    $form_family = [];
    foreach($ever_voted as $ev) {
        $form_family[] = $ev->person_family_name;
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
            $item->family_name = $ve->person_family_name;
            $item->given_name = $ve->person_given_name;
            $item->organization_name = $ve->organization_name;
            if (isset($ve->attributes->abbreviation)) {
                $item->organization_abbreviation = $ve->attributes->abbreviation;
            } else {
                $item->organization_abbreviation = "";
            }
            if (isset($ve->attributes->color)) {
                $item->organization_color = $ve->attributes->color;
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


?>
