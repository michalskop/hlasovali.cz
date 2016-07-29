<?php

/**
* class about current user
*/

class User {

    public function __construct($settings) {
        $this->api = new RestClient([
            'base_url' => $settings->api_url
        ]);
        $this->headers = [];
        $this->headers['Content-Type'] = 'application/json';
    }

    // login
    public function login($email,$pass) {
        $result = $this->api->post(
            "rpc/login",
            json_encode(["email"=>$email,"pass"=>$pass]),
            $this->headers
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

    // get publicly available info about user
    public function getUser($id=NULL) {
        $headers = $this->headers;
        $headers['Prefer'] = 'plurality=singular';
        $result = $this->api->get(
            "public_users",
            ["id" => "eq." . $id],
            $headers
        );
        if ($result->info->http_code == 200) {
            $item = $result->decode_response();
            $item->exist = True;
            return $item;
        } else {
            $item = new StdClass();
            $item->exist = FALSE;
            return $item;
        }
    }

    // get basic info about logged in user
    public function getCurrentUser(){
        if (property_exists($this,'information'))
            return $this->information;
        if (isset($_COOKIE['auth_token']))
            $this->headers['Authorization'] = 'Bearer ' . $_COOKIE['auth_token'];

        $result = $this->api->get(
            "current_user",
            [],
            $this->headers
        );
        //fix problem with invalid hanging JWT:
        if (isset($result->response)) {
            try {
                $resp = json_decode($result->response);
                if (isset($resp->message) and $resp->message == "Invalid JWT") {
                    $this->logout();
                }
            } catch (Exception $e) {

            }
        }
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
        } else {
            unset($this->information);
            $res = new StdClass();
            $res->logged = false;
            return $res;
        }
    }

    // check if the user has rights as an author for a city hall
    public function hasAuthorPrivilages($organization_id) {
        $user = $this->getCurrentUser();
        if (!$user->logged)
            return false;
        // $this->headers['Authorization'] = 'Bearer ' . $_COOKIE['auth_token'];
        $result = $this->api->get(
            "organizations_users",
            ["user_id" => "eq." . $user->id,
             "organization_id" => "eq." . $organization_id,
             "active" => "is.true"],
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

    public function canEditMotion($motion_id) {
        $user = $this->getCurrentUser();
        if (!$user->logged)
            return false;
        $headers = $this->headers;
        $headers['Prefer'] = 'plurality=singular';
        $result = $this->api->get(
            "motions",
            ["user_id" => "eq." . $user->id,
            "id" => "eq." . $motion_id],
            $headers
        );
        if ($result->info->http_code == 200) {
            return true;
        } else {
            return false;
        }
    }
}

?>
