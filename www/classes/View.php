
<?php

/**
* class for organizations
*/

class View
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

    // get organizations with params
    public function getView($view, $pagination='all', $params = []) {
        switch ($pagination) {
            case 'one':
                $result = $this->api->get_one(
                    $view,
                    $params,
                    $this->headers
                );
                return $result;
                break;
            case 'all':
            default:
                $result = $this->api->get_all(
                    $view,
                    $params,
                    $this->headers
                );
                return $result;
        }
    }
}
