<?php

/**
* class for vote events
*/

class VoteEvent extends Table
{
    public function __construct($settings) {
        parent::__construct($settings);
        $this->table = new Table($settings);
    }

    // get votes from vote event(s) with people and organizations
    public function getVoteEventVotes($params) {
        if (!isset($params['order'])) {
            $params['order'] = "vote_event_id.asc,vote_rank.asc,person_id.asc";
        } else {
            $params['order'] = $params['order'] . 'vote_event_id.asc,vote_rank.asc,person_id.asc';
        }
        $result = $this->table->getTable('votes_people_organizations','all',$params);
        return $result;
    }

    public function getVoteEvents($params) {
        if (!isset($params['order'])) {
            $params['order'] = "id.asc";
        } else {
            $params['order'] = $params['order'] . ',id.asc';
        }
        $result = $this->table->getTable('vote_events','all',$params);
        return $result;
    }

    public function getVoteEvent($id=NULL) {
        $params = ['id'=>'eq.'.$id];
        $result = $this->table->getTable('vote_events','one',$params);
        return $result;
    }

    public function getLastVoteEvent($params) {
        $params["order"] = "vote_event_start_date.desc";
        $result = $this->table->getTable('vote_events_information','one',$params);
        return $result;
    }

    public function parseForm($form) {
        $fields = ['id','default_option','rank','family_name','given_name','organization_abbreviation', 'organization_name', 'organization_color','option'];
        $onces = ['vote_event_identifier','vote_event_result','default_vote_event_result'];
        $data = ['rows'=>[]];
        foreach ($form as $key=>$value) {
            $key_arr = explode('-',$key);
            if (in_array($key_arr[0],$fields) and (isset($key_arr[1]))) {
                if (!isset($data['rows'][$key_arr[1]])) {
                    $data['rows'][$key_arr[1]] = [];
                }
                $data['rows'][$key_arr[1]][$key_arr[0]] = trim(htmlspecialchars($value));
            }
        }
        foreach ($onces as $o) {
            if (isset($form[$o])) {
                $data[$o] = trim(htmlspecialchars($form[$o]));
            }
        }
        return $data;
    }

    public function create($data) {
        return $this->table->creates('vote_events',$data);
    }

    public function update($data,$id) {
        return $this->table->updates('vote_events',$data,$id);
    }

    public function delete($id) {
        return $this->table->deletes('vote_events',$id);
    }

}
