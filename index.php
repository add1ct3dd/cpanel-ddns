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

/**
 * Use CURL to query the XML API of cpanel for the DNS zone records
 * 
 * Returns the XML response
 * 
 * @return xml $xmlZone
 */
function cpanel_ddns_zone_fetch() {

    $additionalHeaders = '';
    $process = curl_init(CPANEL_DOMAIN . '/xml-api/cpanel?cpanel_xmlapi_module=ZoneEdit&cpanel_xmlapi_func=fetchzone&domain=' . ZONE_DOMAIN);
    curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', $additionalHeaders));
    curl_setopt($process, CURLOPT_USERPWD, CPANEL_UN . ":" . CPANEL_PW);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    $tmpData = curl_exec($process);

// Check if any error occured
    if (curl_errno($process)) {
        //TODO: Handle errors cleanly
        print_r(curl_getinfo($process));
        die;
    }
    $zoneXML = simplexml_load_string($tmpData)->data;
    return $zoneXML;
}

$dns_zones = cpanel_ddns_zone_fetch();

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

/*
 * We need to obtain the following values from the current record 
 * in order to safely update it:
 * 
 * Line
 * ttl
 * address
 * 
 * The following additional values may be used later:
 * 
 * name
 * class
 * 
 */
$zone_record['name'] = (string) $dns_zones->record[$zone_number_to_update]->name;
echo ' % ' . $zone_record['name'] . ' % ';

$zone_record['Line'] = (string) $dns_zones->record[$zone_number_to_update]->Line;
echo ' % ' . $zone_record['Line'] . ' % ';

$zone_record['ttl'] = (string) $dns_zones->record[$zone_number_to_update]->ttl;
echo ' % ' . $zone_record['ttl'] . ' % ';

$zone_record['address'] = (string) $dns_zones->record[$zone_number_to_update]->address;
echo ' % ' . $zone_record['address'] . ' % ';

$zone_record['class'] = (string) $dns_zones->record[$zone_number_to_update]->class;
echo ' % ' . $zone_record['class'] . ' % ';

//print_r($dns_zone_records);
//echo $dns_records_count;
//echo $return;
?>
