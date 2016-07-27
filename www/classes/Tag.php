<?php

/**
* class for tags
*/

class Tag
{

    public function __construct($settings) {
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

    //get tags
    // for no filter on "active" column, pass $active=0
    public function getTags($motion_id=NULL,$active=TRUE) {
        $params = [];
        if ($motion_id) {
            $params['motion_id'] = "eq." . $motion_id;
        }
        if ($active) {
            $params['active'] = 'is.true';
        }
        if ($active === FALSE) {
            $params['active'] = 'is.false';
        }
        $result_arr = $this->api->get_all(
            "tags",
            $params,
            $this->headers
        );
        if ($result_arr) {
            return $result_arr;
        } else {
            return [];
        }
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
        $r = $this->api->post(
            "tags",
            json_encode($data),
            $this->headers
        );
    }

    //update tag(s)
    public function update($news,$motion_id){
        $olds_arr = $this->getTags($motion_id);
        $olds = [];
        foreach ($olds_arr as $oa)
            $olds[] = $oa->tag;
        $inactives_arr = $this->getTags($motion_id,false);
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
        print_r($olds);
        print_r($news);
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
