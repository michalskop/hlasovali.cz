
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
            case 'page':
                $result = $this->get_page(
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

    //in case of no item, return object with x->exist === false
    //in case of a single item, returns object of the item, with x->exist === true
    //in case of multiple items, returns array of objects of the items
    public function creates($table,$data) {
        $headers = $this->headers;
        $headers['Prefer'] = 'return=representation';
        $result = $this->api->post(
            $table,
            json_encode($data),
            $headers
        );
        if ($this->is_success($result->info->http_code)) {
            $decoded = $result->decode_response();
            if (is_array($decoded)) {
                if (count($decoded) > 1) {
                    $res = $decoded;
                } else {
                    $res = $decoded[0];
                    $res->exist = TRUE;
                }
            } else {
                $res = $decoded;
            }
        } else {
            $res = new StdClass();
            $res->exist = FALSE;
        }
        return $res;
    }

    public function deletes($table,$id) {
        $this->api->delete(
            $table . '?id=eq.' . $id,
            [],
            $this->headers
        );
    }

    //in case of no item, return object with x->exist === false
    //in case of a single item, returns object of the item, with x->exist === true
    //in case of multiple items, returns array of objects of the items
    public function updates($table,$data,$id) {
        $headers = $this->headers;
        $headers['Prefer'] = 'return=representation';
        $result = $this->api->patch(
            $table . "?id=eq." . $id,
            json_encode($data),
            $headers
        );
        if ($this->is_success($result->info->http_code)) {
            $decoded = $result->decode_response();
            if (is_array($decoded)) {
                if (count($decoded) > 1) {
                    $res = $decoded;
                } else {
                    $res = $decoded[0];
                    $res->exist = TRUE;
                }
            } else {
                $res = $decoded;
            }
        } else {
            $res = new StdClass();
            $res->exist = FALSE;
        }
        return $res;
    }

    //note: based on cz.parldata.net api.py
    public function get_result_sizes($url, $parameters=[], $headers=[]) {
        $result = $this->api->get($url, $parameters, $headers);
        if ($this->is_success($result->info->http_code)) {
            $size_arr = explode('/',$result->headers->content_range);
            if (isset($size_arr[1])) {
                $size = explode('/',$result->headers->content_range)[1];
            }
            $last_arr = explode('-',explode('/',$result->headers->content_range)[0]);
            if (isset($last_arr[1])) {
                $last = (int) $last_arr[1];
                $first = (int) $last_arr[0];
            } else {
                $last = 0;
                $first = 0;
            }
            return ['size'=>$size, 'first'=>$first, 'last'=>$last];
        }
        return NULL;
    }

    //note: based on cz.parldata.net api.py
    public function get_one($url, $parameters=[], $headers=[]) {
        $headers['Prefer'] = 'plurality=singular';
        $result = $this->api->get($url, $parameters, $headers);
        if ($this->is_success($result->info->http_code)) {
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
        if($this->is_success($result->info->http_code)) {
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
                if($this->is_success($result->info->http_code)) {
                    $last = explode('-',explode('/',$result->headers->content_range)[0])[1];
                    $arr = array_merge($arr, $result->decode_response());
                }
            }
            return $arr;
        }
        return [];
    }

    public function get_page($url, $parameters=[], $headers=[]) {
        $result = $this->api->get($url, $parameters, $headers);
        if ($this->is_success($result->info->http_code)) {
            $item = $result->decode_response();
            return $item;
        }
        return [];
    }

    function is_success($code) {
        if (($code>=200) and ($code < 300)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
