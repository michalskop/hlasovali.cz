<?php

/**
* class for vote events
*/

class VoteEvent
{
    public function __construct($settings) {
        $this->settings = $settings;
        $this->api = new RestClient([
            'base_url' => $settings->api_url
        ]);
        if (isset($_COOKIE['auth_token']))
            $this->headers = ['Authorization' => 'Bearer ' . $_COOKIE['auth_token']];
        else {
            $this->headers = [];
        }
        $this->headers['Content-Type'] = 'application/json';
    }

    // get votes from vote event(s) with people and organizations
    public function getVoteEventVotes($params) {
        $view = new View($this->settings);
        $result = $view->getView('votes_people_organizations','all',$params);
        return $result;
    }

    public function getVoteEvents($params) {
        $view = new View($this->settings);
        $result = $view->getView('vote_events','all',$params);
        return $result;
    }

    public function getVoteEvent($id=NULL) {
        $params = ['id'=>'eq.'.$id];
        $view = new View($this->settings);
        $result = $view->getView('vote_events','one',$params);
        return $result;
    }

    public function getLastVoteEvent($params) {
        $view = new View($this->settings);
        $params["order"] = "vote_event_start_date.desc";
        $result = $view->getView('vote_events_information','one',$params);
        return $result;
    }

}
