<?php

/**
 * This file will serve as the server endpoint that the client connects to.
 * 
 * Will take both GET and POST requests for plain-text and authenticated updates
 */

switch($_SERVER['REQUEST_METHOD'])
{
case 'GET': 
    // The request was sent via GET. Plain-text authentication
    $the_request = &$_GET; 
    break;
case 'POST': 
    // The update was sent via POST. It likely contains a username and password
    $the_request = &
    $_POST; 
    break;
default:
    // Unknown request type
}

?>
