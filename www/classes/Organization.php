<?php

/**
* abstract class for reading views
*/

class Organization extends Table
{
    public function __construct($settings) {
        parent::__construct($settings);
        $this->table = new Table($settings);
    }

    // get organizations with params
    public function getOrganizations($params = []) {
        if (!isset($params['order'])) {
            $params['order'] = "id.asc";
        } else {
            $params['order'] = $params['order'] . ',id.asc';
        }
        $result = $this->table->getTable('organizations','all',$params);
        return $result;
    }

    // get first page of organizations with most votes
    //params, example: [['key': 'id', 'operator': 'eq', 'value': '1']]
    public function getOrganizationsWithVotes($params=[]) {
        if (!isset($params['order'])) {
            $params['order'] = "id.asc";
        } else {
            $params['order'] = $params['order'] . ',id.asc';
        }
        $result = $this->table->getTable('organizations_with_number_of_votes','all',$params);
        return $result;
    }

    public function create($data) {
        return $this->table->creates('organizations',$data);
    }

    public function update($data,$id) {
        return $this->table->updates('organizations', $data, $id);
    }
}
