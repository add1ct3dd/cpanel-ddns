<?php
/**
 * cpanel-ddns
 * 
 * @author Joseph W. Becher <jwbecher@gmail.com>
 * @package cpanel-ddns
 */

/**
 * 
 * Checks an IP against an ACL
 * 
 * @param string $ip
 * @return boolean
 */
function cpanel_ddns_CheckClientACL($ip) {
    if (is_array(ALLOWED_IPS)) {
        // ALLOWED_IPS is an array of IP addresses
    } else {
        // ALLOWED IPS is a single IP
        if ($ip != ALLOWED_IPS) {
            return FALSE;
        }
    }
    return TRUE;
}

/**
 * Uses CURL to query the XML API of cpanel for the DNS zone records
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

/**
 * Retrieves a single DNS record from the zone file XML
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


?>
