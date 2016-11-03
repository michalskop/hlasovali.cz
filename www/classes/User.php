<?php

/**
* class about current user
*/

class User extends Table
{

    public function __construct($settings) {
        parent::__construct($settings);
        $this->table = new Table($settings);
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
        $params = ["id" => "eq." . $id];
        $result = $this->get_one("public_users",$params);
        return $result;
    }

    public function getUserMotionsCounts($id=NULL) {
        $params = [
            "user_id" => "eq." . $id,
            "order" => "count.desc,organization_name.asc"
        ];
        $result = $this->getTable("users_organizations_motions_counts",'all',$params);
        return $result;
    }

    // get basic info about logged in user
    public function getCurrentUser(){
        if (property_exists($this,'information'))
            return $this->information;
        if (isset($_COOKIE['auth_token']))
            $this->headers['Authorization'] = 'Bearer ' . $_COOKIE['auth_token'];

        //fix problem with invalid hanging JWT:
        $result = $this->api->get(
            "current_user",
            [],
            $this->headers
        );
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
                $this->information->exist = true;
                $this->information->logged = true;
                return $this->information;
            } else {
                unset($this->information);
                $res = new StdClass();
                $res->logged = false;
                $res->exist = false;
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
        $result = $this->table->get_one("organizations_users");
        if ($result->exist) {
            return true;
        } else {
            return false;
        }
    }

    public function canEditMotion($motion_id) {
        $user = $this->getCurrentUser();
        if (!$user->logged)
            return false;
        $params = [
            "user_id" => "eq." . $user->id,
            "id" => "eq." . $motion_id
        ];
        $result = $this->table->get_one("motions",$params);
        if ($result->exist) {
            return true;
        } else {
            return false;
        }
    }
}

?>
