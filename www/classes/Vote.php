<?php

/**
* class for votes
*/

class Vote extends Table
{
    public function __construct($settings) {
        parent::__construct($settings);
        $this->table = new Table($settings);
    }

    public $allowed_options = ['yes','no','abstain','absent'];

    public function getVotes($params) {
        return $this->table->getTable('votes','all',$params);
    }

    public function create($data) {
        return $this->table->creates('votes',$data);
    }

    public function update($data,$id) {
        return $this->table->updates('votes',$data,$id);
    }

    public function delete($id) {
        $this->table->deletes('votes',$id);
    }
}
?>
