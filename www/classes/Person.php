<?php

/**
* class for people
*/

class Person
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

    // get people with params
    public function getPeople($params = []) {
        $view = new View($this->settings);
        $result = $view->getView('people','all',$params);
        return $result;
    }

    // get people with params
    public function getPeopleVotedInOrganizations($params = []) {
        $view = new View($this->settings);
        $result = $view->getView('people_voted_in_organizations','all',$params);
        return $result;
    }

}
?>
