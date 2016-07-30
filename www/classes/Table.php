
<?php

/**
* abstract class for tables/views
*/

class Table
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

    // get view/table with params
    public function getTable($view, $pagination='all', $params = []) {
        switch ($pagination) {
            case 'one':
                $result = $this->get_one(
                    $view,
                    $params,
                    $this->headers
                );
                return $result;
                break;
            case 'all':
            default:
                $result = $this->get_all(
                    $view,
                    $params,
                    $this->headers
                );
                return $result;
        }
    }

    public function creates($table,$data) {
        $headers = $this->headers;
        $headers['Prefer'] = 'return=representation';
        $r = $this->api->post(
            $table,
            json_encode($data),
            $headers
        );
        return $r->decode_response();
    }

    public function updates($table,$data,$id) {
        $headers = $this->headers;
        $headers['Prefer'] = 'return=representation';
        $r = $this->api->patch(
            $table . "?id=eq." . $id,
            json_encode($data),
            $headers
        );
        return $r->decode_response();
    }

    //note: based on cz.parldata.net api.py
    public function get_result_size($url, $parameters=[], $headers=[]) {
        $result = $this->api->get($url, $parameters, $headers);
        if($result->info->http_code == 200) {
            $size = explode('/',$result->headers->content_range)[1];
            return $size;
        }
        return NULL;
    }

    //note: based on cz.parldata.net api.py
    public function get_one($url, $parameters=[], $headers=[]) {
        $headers['Prefer'] = 'plurality=singular';
        $result = $this->api->get($url, $parameters, $headers);
        if ($result->info->http_code == 200) {
            $item = $result->decode_response();
            $item->exist = TRUE;
            return $item;
        } else {
            $item = new StdClass();
            $item->exist = FALSE;
            return $item;
        }
    }

    //note: based on cz.parldata.net api.py
    public function get_all($url, $parameters=[], $headers=[]) {
        $result = $this->api->get($url, $parameters, $headers);
        if($result->info->http_code == 200) {
            $size = explode('/',$result->headers->content_range)[1];
            $last_arr = explode('-',explode('/',$result->headers->content_range)[0]);
            if (isset($last_arr[1]))
                $last = (int) $last_arr[1];
            else {
                $last = 0;
            }
            $arr = $result->decode_response();
            while(($last + 1) < $size) {
                $headers['Range'] = ($last + 1) . '-';
                $result = $this->api->get($url, $parameters, $headers);
                if($result->info->http_code == 200) {
                    $last = explode('-',explode('/',$result->headers->content_range)[0])[1];
                    $arr = array_merge($arr, $result->decode_response());
                }
            }
            return $arr;
        }
        return [];
    }
}
