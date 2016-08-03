<?php

/**
* class about current city hall
*/

class CityHall extends Table
{

    public function __construct($settings) {
        parent::__construct($settings);
        $this->table = new Table($settings);
    }

    // set
    public function setCookie($id) {
        if ($this->exist($id)) {
            setcookie('city_hall', $id);
        }
    }

    //exist
    public function exist($id) {
        $params = ["id" => "eq.".$id, "classification" => "eq.city hall"];
        $result = $this->table->get_one("organizations",$params);
        if ($result->exist) {
            return true;
        } else {
            return false;
        }
    }

    // get info about current city hall
    public function getCityHall() {
        if (property_exists($this,'information'))
            return $this->information;
        if (isset($_COOKIE['city_hall'])) {
            $params = ["id" => "eq.".$_COOKIE['city_hall']];
            $result = $this->table->get_one("organizations",$params);
            if ($result->exist) {
                $this->information = $result;
                $this->information->selected = TRUE;
            } else {
                $this->information = new StdClass();
                $this->information->selected = false;
            }
        } else {
            $this->information = new StdClass();
            $this->information->selected = false;
        }
        return $this->information;
    }

    public function selectFrom() {
        if (property_exists($this,'selectFrom'))
            return $this->selectFrom;
        $result = $this->table->get_all(
            "organizations",
            ["classification" => "eq.city hall", "order" => "name.asc"]
        );
        $this->selectFrom = $result;
        return $result;
    }

}

?>
