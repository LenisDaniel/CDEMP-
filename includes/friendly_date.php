<?php

function friendly_date($date = ""){
    $friendly_date = date('d-M-Y h:i:s A', strtotime($date));
    return $friendly_date;
}

