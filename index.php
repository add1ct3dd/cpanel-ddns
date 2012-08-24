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


$zone_record_to_update = cpanel_ddns_SearchForHostInZoneFile($dns_zones_XML, 'pinger.jwebnet.net');
if ($zone_record_to_update == FALSE) {
    cpanel_ddns_ErrorMessagesDisplay();
}

echo 'The host'.$zone_record_to_update['name'].' currently points to IP address '.$zone_record_to_update['address'].PHPBR;

//print_r($zone_record_to_update);
//echo $dns_records_count;
?>
