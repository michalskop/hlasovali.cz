<?php

// purifies html
// results is html text with only allowed tags
function purify_html($dirty_html) {
    global $settings;

    //see http://htmlpurifier.org/live/INSTALL
    require_once($settings->app_path . 'www/classes/HTMLPurifier.standalone.php');
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Allowed', 'p[style],br,hr,b,i,u,a[href],span[style],ul,ol,li');
    $purifier = new HTMLPurifier($config);
    $clean_html = $purifier->purify($dirty_html);
    return $clean_html;
}

//check if the date is in correct format
function valid_date($value)
{
    if (!is_string($value)) {
        return false;
    }

    $dateTime = new DateTime($value);
    if ($dateTime) {
        return $dateTime->format("Y-m-d") === $value;
    }

    return false;
}

//calculate precision of time input
function time_precision($time) {
    // '00:34:59'
    $pattern="/([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])/";
    if(preg_match($pattern,$time))
        return 8;
    // '00:34'
    $pattern="/([01][0-9]|2[0-3]):([0-5][0-9])/";
    if(preg_match($pattern,$time))
        return 5;
    return 0;
}

// takes date in ISO format and date_precision and prepares data/time
// example return ['date' => '2012-01-01', 'time' => "10:00"]
function preformat_date($date,$date_precision) {
    $valid = substr($date,0,$date_precision);
    $out = [];
    try {
        $d = new DateTime($valid);
        $out['date'] = $d->format('Y-m-d');
        if ($date_precision > 11) {
            $out['time'] = substr($valid,11,$date_precision - 11);
        } else {
            $out['time'] = FALSE;
        }
    } catch (Exception $e) {
        $out = ['date' => FALSE, 'time' => FALSE];
    }
    return $out;
}

?>
