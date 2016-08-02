<?php

/**
* class for tags
*/

class Tag extends Table
{

    public function __construct($settings) {
        parent::__construct($settings);
        $this->table = new Table($settings);
    }

    //get tags
    // for no filter on "active" column, pass $active=0
    public function getTags($params=[]) {
        if (!isset($params['active'])) {
            $params['active'] = 'is.true';
        }
        if (!isset($params['order'])) {
            $params['order'] = "tag.asc,motion_id.asc";
        } else {
            $params['order'] = $params['order'] . ',tag.asc,motion_id.asc';
        }
        $result_arr = $this->get_all(
            "tags",
            $params,
            $this->headers
        );
        return $result_arr;
    }

    // parses form and prepares data for create
    function parseForm($form) {
        $data = [];
        if (isset($form['tags'])) {
            $extags = explode(',', $form['tags']);
            $tags = [];
            foreach ($extags as $ext) {
                if (trim($ext) != "")
                    $tags[] = purify_html(trim($ext));
            }
            return $tags;
        }
        return [];
    }

    //creates new tag(s)
    public function create($data) {
        return $this->table->creates('tags',$data);
    }

    //update tag(s)
    public function update($news,$motion_id){
        $olds_arr = $this->getTags(['motion_id' => "eq." . $motion_id]);
        $olds = [];
        foreach ($olds_arr as $oa)
            $olds[] = $oa->tag;
        $inactives_arr = $this->getTags(['motion_id' => "eq." . $motion_id],false);
        $inactives = [];
        foreach ($inactives_arr as $oa)
            $inactives[] = $oa->tag;
        // new ones
        $create = [];
        foreach($news as $new) {
            if (!in_array($new,$olds)) {
                if (in_array($new,$inactives)){
                    $this->api->patch(
                        "tags?tag=eq." . $new . "&motion_id=eq." . $motion_id,
                        json_encode(["active"=>TRUE]),
                        $this->headers
                    );
                } else {
                    $item = new StdClass();
                    $item->tag = $new;
                    $item->motion_id = $motion_id;
                    $item->active = TRUE;
                    $create[] = $item;
                }
            }
        }
        if (count($create) > 0)
            $this->create($create);

        //removed
        foreach($olds as $old) {
            if (!in_array($old,$news)) {
                echo $old;
                $r = $this->api->patch(
                    "tags?tag=eq." . $old . "&motion_id=eq." . $motion_id,
                    json_encode(["active"=>FALSE]),
                    $this->headers
                );
            }
        }
    }

}
