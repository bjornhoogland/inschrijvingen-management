<?php
if ('inschrijvingen-install.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('<h2>Direct File Access Prohibited</h2>');

/**
 * This file deals with the first time installation options
 * 1. Adds capabilities
 * 3. Adds database
 */
 
 /**
 * Include the necessary files
 * Also the global options
 */
if (file_exists(ABSPATH . 'wp-admin/includes/upgrade.php')) {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
} else {
    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
}

global $charset_collate, $wpdb;

/**
 * Add capability
 * 1. inschrijvingen_cap_admin to the administrator
 * 2. inschrijvingen_cap_subs to the subscriber
 */
function inschrijvingen_user_cap() {
    /** The admin */
    $role = get_role('administrator');
    if(NULL !== $role) {
        $role->add_cap('inschrijvingen_cap_admin');
        $role->add_cap('inschrijvingen_cap_subs');
    }
    
    /** The editor */
    $role = get_role('editor');
    if(NULL !== $role) {
        $role->add_cap('inschrijvingen_cap_subs');
    }
    
    /** The author */
    $role = get_role('author');
    if(NULL !== $role) {
        $role->add_cap('inschrijvingen_cap_subs');
    }
    
    /** The contributor */
    $role = get_role('contributor');
    if(NULL !== $role) {
        $role->add_cap('inschrijvingen_cap_subs');
    }
    
    /** The subscriber */
    $role = get_role('subscriber');
    if(NULL !== $role) {
        $role->add_cap('inschrijvingen_cap_subs');
    }
}
inschrijvingen_user_cap();

/**
 * The Database structure
 */
/** Create and store the db table names */
$inschrijvingen_db_table_name = array(
    'wedstrijden' => $wpdb->prefix . 'inschrijvingen_wedstrijden',
    'reg_tabel' => $wpdb->prefix . 'inschrijvingen_reg'
);
add_option('inschrijvingen_db_table_name', $inschrijvingen_db_table_name, '', 'no');

/** Now create the tables */
if($wpdb->get_var("SHOW TABLES LIKE '$inschrijvingen_db_table_name[wedstrijden]'") != $inschrijvingen_db_table_name['wedstrijden']) {
    $sql =  "CREATE TABLE {$inschrijvingen_db_table_name[wedstrijden]} (
  wedstrijd_ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  wedstrijd_datum datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  wedstrijd_naam tinytext NOT NULL,
  wedstrijd_plaats tinytext NOT NULL,
  wedstrijd_beschrijving text NOT NULL,
  wedstrijd_eigen_deelname tinyint(1) NOT NULL DEFAULT '0',
  wedstrijd_cruiser_deelname tinyint(1) NOT NULL DEFAULT '0',
  wedstrijd_promotie_deelname tinyint(1) NOT NULL DEFAULT '0',
  wedstrijd_deelname_meta longtext,
  wedstrijd_sluiting datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (wedstrijd_ID)
) $charset_collate;";
    
    dbDelta($sql);
}

if($wpdb->get_var("SHOW TABLES LIKE '$inschrijvingen_db_table_name[reg_tabel]'") != $inschrijvingen_db_table_name['reg_tabel']) {
    $sql =  "CREATE TABLE {$inschrijvingen_db_table_name[reg_tabel]} (
  inschrijving_ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  inschrijving_datum datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  inschrijving_type varchar(255) NOT NULL,
  user_id bigint(20) unsigned NOT NULL DEFAULT '0',
  wedstrijd_id bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (inschrijving_ID)
) $charset_collate;";
    
    dbDelta($sql);
}
?>