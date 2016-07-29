<?php

/**
* class about texts
*/

class Text {
    public function __construct($settings) {
        $this->settings = $settings;
        $locale = $this->getLocale();
        $this->text = json_decode(file_get_contents($this->settings->app_path . "www/locales/" . $locale . ".json"));
    }

    public function get($code) {
        if (isset($this->text->$code))
            return $this->text->$code;
        return '';
    }

    public function getAll() {
        return $this->text;
    }

    public function getLocale() {
        if (isset($_GET['locale']) and in_array($_GET['locale'], $this->settings->locales)) {
            $this->setLocale();
            return $_GET['locale'];
        }
        if (isset($_COOKIE['locale']))
            return $_COOKIE['locale'];
        $locales = $this->settings->locales;
        return $locales[0];
    }

    public function setLocale() {
        setcookie("locale", $_GET['locale']);
    }
}

?>
