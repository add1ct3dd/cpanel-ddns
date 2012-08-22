<?php

/**
 * config.php is a file that defines the following constants:
 * 
 * define('CPANEL_DOMAIN', <the domain your cpanel server is located at>);
 * define('CPANEL_UN', <your cpanel username>);
 * define('CPANEL_PW', <your cpanel password>);
 *
 * define('ZONE_DOMAIN', <the domain of the dns zone you want to edit>);
 * 
 * It will need to be created and placed in the same directly as this file
 */
require_once 'config.php';

$process = curl_init(CPANEL_DOMAIN . '/xml-api/cpanel?cpanel_xmlapi_module=ZoneEdit&cpanel_xmlapi_func=fetchzone&domain=' . ZONE_DOMAIN);
curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', $additionalHeaders));
curl_setopt($process, CURLOPT_USERPWD, CPANEL_UN . ":" . CPANEL_PW);
curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
$return = curl_exec($process);

// Check if any error occured
if (curl_errno($process)) {
    print_r(curl_getinfo($process));
    die;
}

$dns_zones = simplexml_load_string($return)->data;

// Count the number of zone records
$dns_records_count = count($dns_zones->children()); // PHP < 5.3 version

/*
 * Loop though the zone records untul we find the one that contains the record 
 * we wish to update.
 */
for ($i = 0; $i <= $dns_records_count; $i++) {
    if ($dns_zones->record[$i]->name == 'pinger.jwebnet.net.' && $dns_zones->record[$i]->type == 'A') {
        $zone_number_to_update = $i;
//        print_r($dns_zones->record[$i]);
    }
}

echo ' % ' . (string) $dns_zones->record[$zone_number_to_update]->name . ' % ';
echo ' % ' . (string) $dns_zones->record[$zone_number_to_update]->Line . ' % ';
echo ' % ' . (string) $dns_zones->record[$zone_number_to_update]->ttl . ' % ';
echo ' % ' . (string) $dns_zones->record[$zone_number_to_update]->address . ' % ';
echo ' % ' . (string) $dns_zones->record[$zone_number_to_update]->class . ' % ';

//print_r($dns_zone_records);
//echo $dns_records_count;
//echo $return;
?>
