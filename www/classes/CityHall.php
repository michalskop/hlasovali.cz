<?php

/**
* class about current city hall
*/

class CityHall {

    public function __construct($settings) {
        $this->api = new RestClient([
            'base_url' => $settings->api_url
        ]);
        if (isset($_COOKIE['auth_token']))
            $this->headers = ['Authorization' => 'Bearer ' . $_COOKIE['auth_token']];
        else {
            $this->headers = [];
        }
    }

    // set
    public function set($id) {
        if ($this->exist($id))
            setcookie('city_hall', $id);
    }

    //exist
    public function exist($id) {
        $result = $this->api->get(
            "organizations",
            ["id" => "eq.".$id, "classification" => "eq.city hall"],
            $this->headers
        );
        if($result->info->http_code == 200) {
            if (isset($result->decode_response()[0])) {
                return true;
            } else {
                return false;
            }
        }
    }

    // get info about current city hall
    public function info() {
        if (property_exists($this,'information'))
            return $this->information;
        if (isset($_COOKIE['city_hall'])) {
            $result = $this->api->get(
                "organizations",
                ["id" => "eq.".$_COOKIE['city_hall']],
                $this->headers
            );
            if($result->info->http_code == 200) {
                if (isset($result->decode_response()[0])) {
                    $this->information = $result->decode_response()[0];
                    $this->information->selected = true;
                    return $this->information;
                } else {
                    $res = new StdClass();
                    $res->selected = false;
                    return $res;
                }
            }
        } else {
            $this->information = new StdClass();
            $this->information->selected = false;
            // $res = new StdClass();
            // $res->selected = false;
            return $this->information;
            return $res;
        }
    }

    public function select_from() {
        if (property_exists($this,'select_from'))
            return $this->select_from;
        $result = $this->api->get_all(
            "organizations",
            ["classification" => "eq.city hall", "order" => "name.asc"],
            $this->headers
        );
        return $result;
    }

}

?>
