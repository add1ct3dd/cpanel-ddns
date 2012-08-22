<?php

/*
 * See docs/config.php.html for details on this file
 */
require_once 'config.php';

/**
 * Use CURL to query the XML API of cpanel for the DNS zone records
 * 
 * Returns the XML response
 * 
 * @return xml $xmlZone
 */
function cpanel_ddns_FetchDNSZoneFile() {

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

$dns_zones_XML = cpanel_ddns_FetchDNSZoneFile();

// Count the number of zone records
$dns_records_count = count($dns_zones_XML->children()); // PHP < 5.3 version

/*
 * Loop though the zone records until we find the one that contains the record 
 * we wish to update. Also locate the SOA record if exists.
 */
for ($i = 0; $i <= $dns_records_count; $i++) {
    // Search for the record we want to update
    if ($dns_zones_XML->record[$i]->name == 'pinger.jwebnet.net.' && $dns_zones_XML->record[$i]->type == 'A') {
        $zone_number_to_update = $i;
    }
    // Look for the SOA record
    if ($dns_zones_XML->record[$i]->type == 'SOA') {
        $zone_number_of_SOA_record = $i;
    }
}

/**
 * 
 * @param xml $zoneXML
 * @param int $recordNumber
 * @return array $zone_record
 */
function cpanel_ddns_FetchRecordFromXMLByNumber($zoneXML, $recordNumber) {
    $zone_record['type'] = (string) $zoneXML->record[$recordNumber]->type;
//    echo ' % ' . $zone_record['type'] . ' % ';
    /*
     * Check what type of record we are reading
     */
    switch ($zone_record['type']) {
        case 'SOA':

            $zone_record['serial'] = (string) $zoneXML->record[$recordNumber]->serial;
//            echo ' % ' . $zone_record['serial'] . ' % ';
            break;
        case 'A':
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
             * type
             * 
             */
            $zone_record['Line'] = (string) $zoneXML->record[$recordNumber]->Line;
            echo ' % ' . $zone_record['Line'] . ' % ';

            $zone_record['ttl'] = (string) $zoneXML->record[$recordNumber]->ttl;
            echo ' % ' . $zone_record['ttl'] . ' % ';

            $zone_record['address'] = (string) $zoneXML->record[$recordNumber]->address;
            echo ' % ' . $zone_record['address'] . ' % ';

            $zone_record['name'] = (string) $zoneXML->record[$recordNumber]->name;
            echo ' % ' . $zone_record['name'] . ' % ';

            $zone_record['class'] = (string) $zoneXML->record[$recordNumber]->class;
            echo ' % ' . $zone_record['class'] . ' % ';


            break;
        default:
            echo 'moo?';
            break;
    }
    return $zone_record;
}

/*
 * Check if we were able to locate an SOA record and return the serial if so
 */
if (!is_null($zone_number_of_SOA_record)) {
    // We were able to locate an SOA record
    $SOA_record = cpanel_ddns_FetchRecordFromXMLByNumber($dns_zones_XML, $zone_number_of_SOA_record);
    echo ' % ' . $SOA_record['serial'] . ' % ';
} else {
    // We were not able to locate an SOA record for this domain.
    echo 'SOA not found for this domain.';
    die;
}



$zone_record_to_update = cpanel_ddns_FetchRecordFromXMLByNumber($dns_zones_XML, $zone_number_to_update);

//print_r($dns_zones);
//echo $dns_records_count;
?>
