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
        $table = new Table($this->settings);
        $result = $table->getTable('votes_people_organizations','all',$params);
        return $result;
    }

    public function getVoteEvents($params) {
        $table = new Table($this->settings);
        $result = $table->getTable('vote_events','all',$params);
        return $result;
    }

    public function getVoteEvent($id=NULL) {
        $params = ['id'=>'eq.'.$id];
        $table = new Table($this->settings);
        $result = $table->getTable('vote_events','one',$params);
        return $result;
    }

    public function getLastVoteEvent($params) {
        $table = new Table($this->settings);
        $params["order"] = "vote_event_start_date.desc";
        $result = $table->getTable('vote_events_information','one',$params);
        return $result;
    }

    public function parseForm($form) {
        $fields = ['id','default_option','family_name','given_name','organization_abbreviation', 'organization_name', 'organization_color','option'];
        $onces = ['vote_event_identifier'];
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

}
