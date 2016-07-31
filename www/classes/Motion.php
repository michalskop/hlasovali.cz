<?php

/**
* class for motions
*/

class Motion extends Table
{
    public function __construct($settings) {
        parent::__construct($settings);
        $this->table = new Table($settings);
    }

    // get info about motion
    public function getMotion($id=NULL) {
        $params = ["id"=>"eq.".$id];
        $result = $this->table->getTable('motions','one',$params);
        return $result;
    }

    // parses form and prepares data for create
    function parseForm($form) {
        $data = [];
        $data['name'] = htmlspecialchars(trim($form['name']));
        if (isset($form['description']) and (trim($form['description']) != '')) {
            $data['description'] = purify_html(trim($form['description']));
        }
        if (valid_date(trim($form['date']))) {
            $data['date'] = trim($form['date']);
            $time_precision = time_precision(trim($form['time']));
            $data['date_precision'] = 10;
            if ($time_precision > 0) {
                $data['date'] = $data['date'] . 'T' . trim($form['time']);
                $data['date_precision'] = 11 + $time_precision;
            }
        } else {
            $date['date_precision'] = 0;
        }
        if (isset($form['links_links']) and isset($form['links_descriptions'])) {
            $attributes = ['links'=>[]];
            $n = count($form['links_links']);

            for ($i=0; $i<$n; $i++) {
                if (isset($form['links_links'][$i]) and isset($form['links_descriptions'][$i])) {
                    $link = trim(htmlspecialchars($form['links_links'][$i]));
                    $descr = trim(htmlspecialchars($form['links_descriptions'][$i]));
                    if (($link != '') and ($descr != '')) {
                        $item = [];
                        $item['url'] = $link;
                        $item['text'] = $descr;
                        if (!isset($attributes['links']))
                            $attributes['links'] = [];
                        $attributes['links'][] = $item;
                    }
                }
            }
            $data['attributes'] = $attributes;
        }
        $vote_event = new VoteEvent($this->settings);
        $vote_event->parseForm($form);
        return $data;
    }

    public function create($data) {
        return $this->table->creates('motions',$data);
    }

    public function update($data,$id) {
        return $this->table->updates('motions',$data,$id);
    }
}
?>
