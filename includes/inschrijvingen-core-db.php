<?php
if ('inschrijvingen-core-db.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('<h2>Direct File Access Prohibited</h2>');

/**
 * Lijst van alle wedstrijden voor de gebruiker
 * @global object $wpdb
 * @global array $inschrijvingen_db_table_name
 * @return object lijst van rijen
 */
function inschrijvingen_gebruiker_wedstrijd_lijst($year) {
    global $wpdb, $inschrijvingen_db_table_name, $current_user; get_currentuserinfo();
    
    /** Get the basic list */
    $wedstrijd_lists = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$inschrijvingen_db_table_name['wedstrijden']} WHERE YEAR(wedstrijd_datum) = %d ORDER BY wedstrijd_datum ASC", $year));
    
    /** Extend it for action */
    foreach($wedstrijd_lists as $wedstrijd_list) {
        $inschrijvingen = $wpdb->get_results($wpdb->prepare("SELECT inschrijving_ID,inschrijving_type FROM {$inschrijvingen_db_table_name['reg_tabel']} WHERE user_id = %d AND wedstrijd_id = %d", $current_user->ID, $wedstrijd_list->wedstrijd_ID));
        
        foreach($inschrijvingen as $inschrijving) {
        	if($inschrijving->inschrijving_type == "eigen"){
        		$wedstrijd_list->inschrijving_eigen_id = $inschrijving->inschrijving_ID;
        	}
        	if($inschrijving->inschrijving_type == "cruiser"){
        		$wedstrijd_list->inschrijving_cruiser_id = $inschrijving->inschrijving_ID;
        	}
        	if($inschrijving->inschrijving_type == "promotie"){
        		$wedstrijd_list->inschrijving_promotie_id = $inschrijving->inschrijving_ID;
        	}
        }
    }
    return $wedstrijd_lists;
}

/**
 * Jaar van eerste wedstrijd in de db
 * @global object $wpdb
 * @global array $inschrijvingen_db_table_name
 * @return mysql date
 */
function inschrijvingen_eerste_wedstrijd_datum() {
	global $wpdb, $inschrijvingen_db_table_name;
	
	$datum = $wpdb->get_var("SELECT min(`wedstrijd_datum`) FROM {$inschrijvingen_db_table_name['wedstrijden']}");
	
	return $datum;
}

/**
 * Jaar van laatste wedstrijd in de db
 * @global object $wpdb
 * @global array $inschrijvingen_db_table_name
 * @return mysql date
 */
function inschrijvingen_laatste_wedstrijd_datum() {
	global $wpdb, $inschrijvingen_db_table_name;
	
	$datum = $wpdb->get_var("SELECT max(`wedstrijd_datum`) FROM {$inschrijvingen_db_table_name['wedstrijden']}");
	
	return $datum;
}

function inschrijvingen_gebruiker_wedstrijd_verificatie($id, $type) {
	global $wpdb, $inschrijvingen_db_table_name, $current_user; get_currentuserinfo();
	
	/**
	 * Level 1: If he is not registered yet
	 */
	$verificatie = $wpdb->get_var($wpdb->prepare("SELECT COUNT(inschrijving_ID) FROM {$inschrijvingen_db_table_name['reg_tabel']} WHERE user_id = %d AND wedstrijd_id = %d AND inschrijving_type = %s", $current_user->ID, $id, $type));
	if($verificatie) {
		return false;
	}
	else {
		/**
		 * Level 2: If the event is upcoming
		 */
		return inschrijvingen_wedstrijd_open_verificatie($id);
	}
}

function inschrijvingen_wedstrijd_open_verificatie($id) {
	global $wpdb, $inschrijvingen_db_table_name;
	
	$verificatie = $wpdb->get_var($wpdb->prepare("SELECT COUNT(wedstrijd_ID) FROM {$inschrijvingen_db_table_name['wedstrijden']} WHERE wedstrijd_sluiting <= %s AND wedstrijd_ID = %d", current_time('mysql', 0), $id));
	if($verificatie) {
		return false;
	} else {
		return true;
	}
}

/**
 * Lijst van alle wedstrijden voor de admin
 * @global object $wpdb
 * @global array $inschrijvingen_db_table_name
 * @return object lijst van rijen
 */
function inschrijvingen_admin_wedstrijd_lijst($year){
	global $wpdb, $inschrijvingen_db_table_name;
    
    /** Get the basic list */
    $wedstrijd_lists = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$inschrijvingen_db_table_name['wedstrijden']} WHERE YEAR(wedstrijd_datum) = %d ORDER BY wedstrijd_datum ASC", $year));
    
    return $wedstrijd_lists;
}

/**
 * Details van 1 wedstrijd
 * @global object $wpdb
 * @global array $inschrijvingen_db_table_name
 * @return object 1 wedstrijd
 */
function inschrijvingen_wedstrijd_details($id){
	global $wpdb, $inschrijvingen_db_table_name;
    
    /** Get a single row */
    $wedstrijd = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$inschrijvingen_db_table_name['wedstrijden']} WHERE wedstrijd_ID = %d", $id));
    
    return $wedstrijd;
}

function inschrijvingen_admin_wedstrijd_inschrijvingen($id){
	global $wpdb, $inschrijvingen_db_table_name;
    
    /** Get the basic list */
    $inschrijving_list = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$inschrijvingen_db_table_name['reg_tabel']} WHERE wedstrijd_id = %d", $id));
    
    if($inschrijving_list) {
    	foreach($inschrijving_list as $inschrijving) {
    		$inschrijving_info = get_userdata($inschrijving->user_id);
    		$inschrijving->username = $inschrijving_info->user_login;
    		$meta = get_user_meta($inschrijving_info->ID,'bmx_profiel_meta', true);
    		$inschrijving->stuurbord = $meta["bmx_Stuurbordnummer"];
    		$inschrijving->licentie = $meta["bmx_Licentienummer"];
    		if($meta["bmx_Geslacht"] === "Man") {
    			if($inschrijving->inschrijving_type === "eigen") {
    				$inschrijving->klasse = "J";
    			}elseif($inschrijving->inschrijving_type === "cruiser") {
    				$inschrijving->klasse = "C";
    			}elseif($inschrijving->inschrijving_type === "promotie") {
    				$inschrijving->klasse = "PJ";
    			}
    		} elseif($meta["bmx_Geslacht"] === "Vrouw") {
    			if($inschrijving->inschrijving_type === "eigen") {
    				$inschrijving->klasse = "M";
    			}elseif($inschrijving->inschrijving_type === "cruiser") {
    				$inschrijving->klasse = "D";
    			}elseif($inschrijving->inschrijving_type === "promotie") {
    				$inschrijving->klasse = "PM";
    			}
    		} else {
    			$inschrijving->klasse = $inschrijving->inschrijving_type;
    		}
    	}
    }
    
    usort($inschrijving_list, 'compare_stuurbord');
    
    return $inschrijving_list;
}
function compare_stuurbord($first, $second){
	return $first->stuurbord > $second->stuurbord;
}

?>