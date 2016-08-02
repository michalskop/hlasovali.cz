<?php

/**
* class for people
*/

class Person extends Table
{
    public function __construct($settings) {
        parent::__construct($settings);
        $this->table = new Table($settings);
    }

    // get people with params
    public function getPeople($params = []) {
        if (!isset($params['order'])) {
            $params['order'] = "id.asc";
        } else {
            $params['order'] = $params['order'] . ',id.asc';
        }
        $result = $this->table->getTable('people','all',$params);
        return $result;
    }

    // get people with params
    public function getPeopleVotedInOrganizations($params = []) {
        if (!isset($params['order'])) {
            $params['order'] = "person_id.asc,organization_id.asc";
        } else {
            $params['order'] = $params['order'] . ',person_id.asc,organization_id.asc';
        }
        $result = $this->table->getTable('people_voted_in_organizations','all',$params);
        return $result;
    }

    public function create($data) {
        return $this->table->creates('people',$data);
    }

    public function update($data,$id) {
        return $this->table->updates('people',$data,$id);

    }

}
?>
