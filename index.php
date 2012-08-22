<?php

/**
 * cpanel-ddns
 * 
 * @author Joseph W. Becher <jwbecher@gmail.com>
 * @package cpanel-ddns
 */
/**
 * See config.example.php for details on this file
 */
require_once 'config.php';

/*
 * See docs/functions.php.html for details on this file
 */
require_once 'functions.php';



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
