<?php

/**
 * Example config.php file.
 * 
 * config.php is the file that defines the following settings<br><br>
 * Example file:
 * <code>
 * <?php
 * define('CPANEL_DOMAIN', 'cpanel.example.com');
 * define('CPANEL_UN', 'user');
 * define('CPANEL_PW', 'password');
 * define('ZONE_DOMAIN', 'example.com');
 * define('IP_ACCESS_MODE', 'single');
 * define('ALLOWED_IPS', '192.168.1.100');
 * ?>
 * </code>
 *   
 * @author Joseph W. Becher <jwbecher@gmail.com>
 * @package cpanel-ddns
 */
/**
 * the domain where your cpanel server is located
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
 * either a single ip or an array of ips that are allowed to access the dns records
 */
define('ALLOWED_IPS', '');

/**
 *             It will need to be created and placed in the same directory as index.php
 */
?>
