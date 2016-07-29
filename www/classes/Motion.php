<?php

/**
* class for motions
*/

class Motion
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

    // get info about motion
    public function getMotion($id=NULL) {
        $view = new View($this->settings);
        $params = ["id"=>"eq.".$id];
        $result = $view->getView('motions','one',$params);
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
            $time_precision = time_precision(trim($_POST['time']));
            $data['date_precision'] = 10;
            if ($time_precision > 0) {
                $data['date'] = $data['date'] . 'T' . trim($_POST['time']);
                $data['date_precision'] = 11 + $time_precision;
            }
        } else {
            $date['date_precision'] = 0;
        }
        if (isset($_POST['links_links']) and isset($_POST['links_descriptions'])) {
            $attributes = ['links'=>[]];
            $n = count($_POST['links_links']);

            for ($i=0; $i<$n; $i++) {
                if (isset($_POST['links_links'][$i]) and isset($_POST['links_descriptions'][$i])) {
                    $link = trim(htmlspecialchars($_POST['links_links'][$i]));
                    $descr = trim(htmlspecialchars($_POST['links_descriptions'][$i]));
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
        return $data;
    }

    public function create($data) {
        $headers = $this->headers;
        $headers['Prefer'] = 'return=representation';
        $r = $this->api->post(
            "motions",
            json_encode($data),
            $headers
        );
        return $r->decode_response();
    }

    public function update($data,$id) {
        $headers = $this->headers;
        $headers['Prefer'] = 'return=representation';
        $r = $this->api->patch(
            "motions?id=eq." . $id,
            json_encode($data),
            $headers
        );
        return $r->decode_response();
    }
}
?>
