<?php

/**
 * cpanel-ddns
 * 
 * @author Joseph W. Becher <jwbecher@gmail.com>
 * @package cpanel-ddns
 */
/**
 * This file will serve as the server endpoint that the client connects to.
 * 
 * Will take both GET and POST requests for plain-text and authenticated updates
 */
/**
 * See config.example.php for details on this file
 */
require_once 'config.php';


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // The request was sent via GET. Plain-text authentication
        $the_request = &$_GET;
        break;
    case 'POST':
        // The update was sent via POST. It likely contains a username and password
        $the_request = &$_POST;
        break;
    default:
        // Unknown request type
        die;
}

// Is an IP included?
if (!$the_request['ip']) {
    // Attempt to auto-guess the IP address
    //TODO: Auto-guess the IP
} else {
    /*
     * Sanitize the IP and check if it's valid.
     * 
     */
    //TODO: Sanitize the IP
    $ip_to_update = $the_request['ip'];
}

/*
 * Is the requesting client allowed to perform updates?
 */
$is_client_allowed = cpanel_ddns_CheckClientACL($_SERVER['REMOTE_ADDR']);

if (!$is_client_allowed) {
    // Client is not allowed to perform updates.
    echo 'Access denied.';
    die;
}



echo 'Updated.';
?>
