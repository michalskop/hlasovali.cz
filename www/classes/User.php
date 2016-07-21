<?php

/**
* class about current user
*/

class User {

    public function __construct($settings) {
        $this->api = new RestClient([
            'base_url' => $settings->api_url
        ]);
    }

    // login
    public function login($email,$pass) {
        $result = $this->api->post(
            "rpc/login",
            json_encode(["email"=>$email,"pass"=>$pass]),
            ['Content-Type' => 'application/json']
        );
        if($result->info->http_code == 200) {
            if (isset($result->decode_response()->token))
                setcookie('auth_token', $result->decode_response()->token);
        }
    }

    // log out the user
    // note: they are not logged out until the next reload!
    public function logout() {
        if(isset($_COOKIE['auth_token'])) {
            setcookie("auth_token", "", time() - 3600);
        }
        unset($this->information);
    }

    // get info about logged in user
    public function info(){
        if (property_exists($this,'information'))
            return $this->information;
        if (isset($_COOKIE['auth_token']))
            $headers = ['Authorization' => 'Bearer ' . $_COOKIE['auth_token']];
        else {
            $headers = [];
        }
        $result = $this->api->get(
            "current_user",
            [],
            $headers
        );
        if($result->info->http_code == 200) {
            if (isset($result->decode_response()[0])) {
                $this->information = $result->decode_response()[0];
                $this->information->logged = true;
                return $this->information;
            } else {
                unset($this->information);
                $res = new StdClass();
                $res->logged = false;
                return $res;
            }
        }
    }
}

?>
