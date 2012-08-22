<?php

/**
 * cpanel-ddns
 * 
 * @author Joseph W. Becher <jwbecher@gmail.com>
 * @package cpanel-ddns
 */
/**
 * 
 * config.php is a file that defines the following constants:
 */
/**
 * the domain your cpanel server is located
 */
define('CPANEL_DOMAIN', '');

/**
 * your cpanel username
 */
define('CPANEL_UN', '');
/**
 * your cpanel password
 */
define('CPANEL_PW', '');
/**
 * the domain of the dns zone you want to edit
 */
define('ZONE_DOMAIN', '');
/**
 *             What type of ACL are we using?
 *             
 *             single = a single IP address
 *             multi = an array of single ip addresses
 *             subnet = an array of host/mask subnet pairs.
 */
/**
 * single, multi, subnet
 */
define('IP_ACCESS_MODE', '');
/**
 * either a single ip or an array of ips that are 
 * allowed to access the dns records
 */
define('ALLOWED_IPS', '');

/**
 *             It will need to be created and placed in the same directory as index.php
 */
?>
