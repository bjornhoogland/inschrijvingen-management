<?php
/*
Plugin Name: Inschrijvingen Management
Description: Plugin voor het beheren van wedstrijden en het inschrijven voor de wedstrijden.
Author: Bjorn Hoogland
Version: 0.4
*/

if ('inschrijvingen-management.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('<h2>Direct File Access Prohibited</h2>');

/** Define this file */
define('WP_INSCHRIJVINGEN_ABSFILE', __FILE__);
define('WP_INSCHRIJVINGEN_ABSPATH', dirname(__FILE__));

/** The activation function */
function inschrijvingen_plugin_act_hook() {
    global $charset_collate, $wpdb;
    
    include (plugin_dir_path(WP_INSCHRIJVINGEN_ABSFILE) . 'includes/inschrijvingen-install.php');
}
register_activation_hook(WP_INSCHRIJVINGEN_ABSFILE, 'inschrijvingen_plugin_act_hook');

/** Get Options Make it global*/
$inschrijvingen_db_table_name = get_option('inschrijvingen_db_table_name');

/** Include the core db functions */
include_once(plugin_dir_path(WP_INSCHRIJVINGEN_ABSFILE) . 'includes/inschrijvingen-core-db.php');

function inschrijvingen_admin_actions() {
	
	/** The main page */
    add_menu_page('Inschrijvingen Management', 'Inschrijvingen Beheer', 'inschrijvingen_cap_admin', 'inschrijvingen_beheer', 'inschrijvingen_beheer_menu_home');
    /** Home pagina */
    add_submenu_page('inschrijvingen_beheer', 'Overzicht Wedstrijden', 'Overzicht', 'inschrijvingen_cap_admin', 'inschrijvingen_beheer', 'inschrijvingen_beheer_menu_home');
    /** Wedstrijd toevoegen pagina */
    //add_submenu_page('inschrijvingen_beheer', 'Voeg een Wedstrijd toe', 'Wedstrijd Toevoegen', 'inschrijvingen_cap_admin', 'inschrijvingen_beheer_toevoegen', 'inschrijvingen_beheer_menu_toevoegen');
}

add_action('admin_menu', 'inschrijvingen_admin_actions');

/**
 * De admin menu functies
 */
/** Het hoofd menu */
function inschrijvingen_beheer_menu_home() {
    include('includes/inschrijvingen-beheer-algemeen.php');
}

/** Wedstrijd Toevoegen */
function inschrijvingen_beheer_menu_toevoegen() {
    include('includes/inschrijvingen-beheer-wedstrijd-toevoegen.php');
}

function inschrijvingen_gebruiker_actions() {

	/** The main page */
    add_menu_page('Inschrijvingen overzicht', 'Inschrijvingen', 'inschrijvingen_cap_subs', 'inschrijvingen_gebruiker', 'inschrijvingen_gebruiker_menu_home');
    /** Home pagina 
    add_submenu_page('inschrijvingen_gebruiker', 'Overzicht Wedstrijden', 'Overzicht', 'inschrijvingen_cap_subs', 'inschrijvingen_gebruiker', 'inschrijvingen_gebruiker_menu_home');
	/** Profiel informatie 
    add_submenu_page('inschrijvingen_gebruiker', 'Inschrijvinginformatie Rijder', 'Rijder Gegevens', 'inschrijvingen_cap_subs', 'inschrijvingen_gebruiker_profiel', 'inschrijvingen_gebruiker_menu_profiel');*/

}
add_action('admin_menu', 'inschrijvingen_gebruiker_actions');

/** Inschrijvingen overzicht */
function inschrijvingen_gebruiker_menu_home() {
    include('includes/inschrijvingen-gebruiker-algemeen.php');
}

/** Profiel pagina */
/*function inschrijvingen_gebruiker_menu_profiel() {
    include('includes/inschrijvingen-gebruiker-profiel.php');
}*/


/**
 * AJAX references
 */
include(plugin_dir_path(WP_INSCHRIJVINGEN_ABSFILE) . 'includes/inschrijvingen-gebruiker-ajax.php');
include(plugin_dir_path(WP_INSCHRIJVINGEN_ABSFILE) . 'includes/inschrijvingen-beheer-ajax.php');


/** Data weergave op profiel van gebruiker */
function bmx_profiel($user) {
	$meta = get_user_meta($user->ID,'bmx_profiel_meta', true);
	$can_change = current_user_can('administrator');
	?>
    <h3><a name="BMX"></a>BMX gegevens</h3>
    
    Onderstaande gegevens staan in het systeem en zijn nodig om in te kunnen schrijven.<?php if(!$can_change) echo " (Aanpassen uitgeschakeld)";?>
    
    <table class="form-table">
	 	<tr>
			<th><label>Geboortedatum</label></th>
			<td>
				<input <?php if(!$can_change){echo "disabled class=\"disabled\" ";}?>type="text" value="<?php echo $meta["bmx_Geboortedag"]; ?>" id="bmx_Geboortedag" name="bmx_Geboortedag" size="2" />
				<select <?php if(!$can_change){echo "disabled class=\"disabled\" ";}?>name="bmx_Geboortemaand" id="bmx_Geboortemaand">
					<option <?php if($meta["bmx_Geboortemaand"] === "januari"){echo "selected=\"selected\" ";}?>value='januari'>Januari</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "februari"){echo "selected=\"selected\" ";}?>value='februari'>Februari</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "maart"){echo "selected=\"selected\" ";}?>value='maart'>Maart</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "april"){echo "selected=\"selected\" ";}?>value='april'>April</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "mei"){echo "selected=\"selected\" ";}?>value='mei'>Mei</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "juni"){echo "selected=\"selected\" ";}?>value='juni'>Juni</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "juli"){echo "selected=\"selected\" ";}?>value='juli'>Juli</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "augustus"){echo "selected=\"selected\" ";}?>value='augustus'>Augustus</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "september"){echo "selected=\"selected\" ";}?>value='september'>September</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "oktober"){echo "selected=\"selected\" ";}?>value='oktober'>Oktober</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "november"){echo "selected=\"selected\" ";}?>value='november'>November</option>
					<option <?php if($meta["bmx_Geboortemaand"] === "december"){echo "selected=\"selected\" ";}?>value='december'>December</option>
				</select>
				<input <?php if(!$can_change){echo "disabled class=\"disabled\" ";}?>type="text" value="<?php echo $meta["bmx_Geboortejaar"]; ?>" id="bmx_Geboortejaar" name="bmx_Geboortejaar" size="4" />
			</td>
		</tr>
	 	<tr>
			<th><label for="bmx_Stuurbordnummer">Stuurbordnummer</label></th>
			<td>
				<input <?php if(!$can_change){echo "disabled class=\"disabled\" ";}?>type="text" value="<?php echo $meta["bmx_Stuurbordnummer"]; ?>" id="bmx_Stuurbordnummer" name="bmx_Stuurbordnummer" size="4" /> <span class="description">Het stuurbordnummer dat je van de vereniging hebt gekregen. Met dit nummer rijd je tijdens de wedstrijd.</span>
			</td>
		</tr>
		<tr>
			<th><label for="bmx_Licentienummer">NFF licentienummer</label></th>
			<td>
				<input <?php if(!$can_change){echo "disabled class=\"disabled\" ";}?>type="text" value="<?php echo $meta["bmx_Licentienummer"]; ?>" id="bmx_Licentienummer" name="bmx_Licentienummer" size="4" /> <span class="description">Het licentienummer waaronder je bekend bent bij de NFF.</span>
			</td>
		</tr>
		<tr>
		<th scope="row">Geslacht</th>
			<td>
				<fieldset><legend class="screen-reader-text"><span>Geslacht</span></legend>
					<label title='Man'><input <?php if(!$can_change){echo "disabled class=\"disabled\" ";}?>type='radio' name='bmx_Geslacht' value='Man' <?php
						if($meta["bmx_Geslacht"] === "Man"){echo "checked=\"checked\" ";}
						?>/> <span>Man</span></label><br />
					<label title='Vrouw'><input <?php if(!$can_change){echo "disabled class=\"disabled\" ";}?>type='radio' name='bmx_Geslacht' value='Vrouw' <?php
						if($meta["bmx_Geslacht"] === "Vrouw"){echo "checked=\"checked\" ";}
						?>/> <span>Vrouw</span></label>
				</fieldset>
			</td>
		</tr>
		<?php if($can_change){ ?>
		<tr>
		<th scope="row">Clubvertegenwoordiger</th>
			<td>
				<input type="checkbox" id="bmx_Clubvertegenwoordiger" name="bmx_Clubvertegenwoordiger" <?php if($meta["bmx_Clubvertegenwoordiger"] === "on") { echo "checked=\"checked\" ";}?>/> <span class="description">Clubvertegenwoordiger kan alle inschrijvingen per wedstrijd zien.</span>
			</td>
		</tr>
		<?php } ?>
	</table>
	<?php
	
}
function bmx_profiel_opslaan($user_id){
	if ( !current_user_can( 'administrator' ) ) { return false; }
	foreach($_POST as $key => $value) {
		if (preg_match('&bmx_&', $key) && $value) {
			$profiel[$key] = esc_attr($value);
		}
	}
	update_user_meta($user_id, 'bmx_profiel_meta', $profiel);
	
	$user = new WP_User( $user_id );
	if($profiel['bmx_Clubvertegenwoordiger'] === "on" && !$user->has_cap('inschrijvingen_cap_admin')){
		$user->add_cap('inschrijvingen_cap_admin');
	}
	if($profiel['bmx_Clubvertegenwoordiger'] !== "on" && $user->has_cap('inschrijvingen_cap_admin')){
		$user->remove_cap('inschrijvingen_cap_admin');
	}
}
add_action('profile_personal_options', 'bmx_profiel');
add_action('personal_options_update', 'bmx_profiel_opslaan');

// admin mag de gebruiker ook wijzigen:
add_action('edit_user_profile', 'bmx_profiel');
add_action('profile_update', 'bmx_profiel_opslaan');

?>