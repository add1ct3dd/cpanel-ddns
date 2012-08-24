<?php
/**
 * fetchDNS
 * 
 * This file will search for a dns record and return the IP address.
 * 
 * @author Joseph W. Becher <jwbecher@gmail.com>
 * @package cpanel-ddns
 */
/**
 * See config.example.php for details on this file
 */
require_once 'config.php';
require_once 'functions.php';


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

/*
 * Was a host provided as part of the update request?
 */
if (!$the_request['host']) {
    echo 'No host was provided.';
    die;
} else {
    $host_to_update = $the_request['host'];
}

/*
 * Is the requesting client allowed to perform lookups?
 */
$is_client_allowed = cpanel_ddns_CheckClientACL($_SERVER['REMOTE_ADDR']);

if (!$is_client_allowed) {
    // Client is not allowed to perform updates.
    echo 'Access denied.';
    die;
}

/*
 * Does the requested domain exist?
 */
$dns_zones_XML = cpanel_ddns_FetchDNSZoneFile();
$zone_record_to_update = cpanel_ddns_SearchForHostInZoneFile($dns_zones_XML, $host_to_update);
if ($zone_record_to_update == FALSE) {
    cpanel_ddns_ErrorMessagesDisplay();
}

echo 'The requested host record for '.$host_to_update.' was found in the zone file pointing to IP address '.$zone_record_to_update['address'].'.'.PHPBR;

//echo $updated_record;

?>
