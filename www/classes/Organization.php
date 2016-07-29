<?php

/**
* abstract class for reading views
*/

class Organization
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

    // get organizations with params
    public function getOrganizations($params = []) {
        $view = new View($this->settings);
        $result = $view->getView('organizations','all',$params);
        return $result;
    }

    // get first page of organizations with most votes
    //params, example: [['key': 'id', 'operator': 'eq', 'value': '1']]
    public function getOrganizationsWithVotes($params=[]) {
        $view = new View($this->settings);
        $result = $view->getView('organizations_with_number_of_votes','all',$params);
        return $result;
    }
}
