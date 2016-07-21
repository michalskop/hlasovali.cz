<?php

/**
* class about texts
*/

class Text {
    public function __construct($settings) {
        $this->settings = $settings;
        $lang = $this->get_language();
        $this->text = json_decode(file_get_contents($this->settings->app_path . "www/locales/" . $lang . ".json"));
    }

    public function get($code) {
        if (isset($this->text->$code))
            return $this->text->$code;
        return '';
    }

    public function get_language() {
        if (isset($_GET['locale']) and in_array($_GET['locale'], $this->settings->locales)) {
            $this->set_language();
            return $_GET['locale'];
        }
        if (isset($_COOKIE['locale']))
            return $_COOKIE['locale'];
        $locales = $this->settings->locales;
        return $locales[0];
    }

    public function set_language() {
        setcookie("locale", $_GET['locale']);
    }
}

?>
