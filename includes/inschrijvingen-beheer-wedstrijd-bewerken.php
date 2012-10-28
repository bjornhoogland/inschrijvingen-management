<?php
if ('inschrijvingen-beheer-wedstrijd-toevoegen.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('<h2>Direct File Access Prohibited</h2>');
if(!current_user_can('inschrijvingen_cap_admin')) {
	wp_die('Leuk geprobeerd :)');
	return;
}

/**
 * Initialize the global variables
 */
global $inschrijvingen_db_table_name, $wpdb;


/**
 * Wedstrijd bewerken
 * wedstrijd id moet bekend zijn
 * als de request method post is
 * gebruik $wpdb->update
 */
if(!isset($_GET['id'])) {
	?>
	<div class="error"><p><strong>Verkeerde URL! Probeer het nog eens met de knoppen.</strong></p></div>
	<?php
} else {

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
    	/** Strip slash */
    	$_POST = stripslashes_deep( $_POST );
        
    	/**
    	 * Wijzig de data
    	 */
    	if(strtotime($_POST['wedstrijd_datum']) < strtotime(current_time('mysql', 0))){
        	$error = "<strong>Je hebt een wedstrijd in het verleden bewerkt.</strong>";
    	}
    	$wedstrijd_datum_raw = strtotime($_POST['wedstrijd_datum']);
        $wedstrijd_datum = date('Y-m-d', $wedstrijd_datum_raw);

    	if(!$_POST['sluitings_datum']){
        	$sluitings_datum = "0000-00-00";
    	} else {
        	$sluitings_datum_raw = strtotime($_POST['sluitings_datum']);
        	$sluitings_datum = date('Y-m-d 23:59:59', $sluitings_datum_raw);
    	}
    	
    	// Geen lege titels
	    if(!$_POST['wedstrijd_titel']){
	        $_POST['wedstrijd_titel'] = "?";
	    }

		/** Update de huidige wedstrijd in de db */
    	$wpdb->update($inschrijvingen_db_table_name['wedstrijden'], array(
        	'wedstrijd_datum' => $wedstrijd_datum,
        	'wedstrijd_naam' => $_POST['wedstrijd_titel'],
        	'wedstrijd_plaats' => $_POST['plaats'],
        	'wedstrijd_eigen_deelname' => isset($_POST['eigen_klasse']),
        	'wedstrijd_cruiser_deelname' => isset($_POST['cruiser_klasse']),
        	'wedstrijd_promotie_deelname' => isset($_POST['promotie_klasse']),
        	'wedstrijd_sluiting' => $sluitings_datum
    	), array('wedstrijd_ID' => $_POST['id']), array('%s', '%s', '%s', '%d', '%d', '%d', '%s'), array('%d'));
        
    	/** Output error */
    	if('' != $error) {
        	?>
        	<div class="error">
            	<p><?php echo $error; ?></p>
        	</div>
        	<?php
    	}
    	?>
    	<div id="message" class="updated">
        	<p><strong>Wedstrijd "<?php echo esc_html($_POST['wedstrijd_titel']); ?>" is bewerkt.</strong> <a href="admin.php?page=inschrijvingen_beheer&action=view&id=<?php echo $_POST['id']; ?>" class="button-secondary">Wedstrijd bekijken</a></p>
    	</div>
    	<?php
	}
	
	$wedstrijd = inschrijvingen_wedstrijd_details($_GET['id']);
	if($wedstrijd) {
		?>
		
		<?php //<div id="icon-wedstrijd" class="icon32"><br /></div>?>
		<h2>
			Wedstrijd "<?php echo esc_html($wedstrijd->wedstrijd_naam); ?>" bewerken
		</h2>
		
		<form action="" method="post">
			<input type="hidden" name="id" id="id" value="<?php echo $wedstrijd->wedstrijd_ID; ?>" />
	        <table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="wedstrijd_titel">Wedstrijdnaam:</label>
					</th>
					<td>
						<input type="text" name="wedstrijd_titel" id="wedstrijd_titel" value="<?php echo esc_html($wedstrijd->wedstrijd_naam); ?>" class="regular-text" />
					</td>
					<td>
						<span class="description">Geef een korte beschrijving van de wedstrijd.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="plaats">Wedstrijdplaats:</label>
					</th>
					<td>
						<input type="text" name="plaats" id="plaats" value="<?php echo esc_html($wedstrijd->wedstrijd_plaats); ?>" class="regular-text" />
					</td>
					<td>
						<span class="description">Vul hier de locatie van de wedstrijd in.</span>
					</td>
				</tr>
				<!--<tr valign="top">
					<th scope="row">
						<label for="prijs">Inschrijfkosten:</label>
					</th>
					<td>
						<input type="text" name="prijs" id="prijs" value="0" class="small-text" /> Euro
					</td>
					<td>
						<span class="description">Vul de prijs in dat een rijder moet betalen voor deelname.</span>
					</td>
				</tr>-->
				<tr valign="top">
					<th scope="row">
						<label for="wedstrijd_datum">Wedstrijddatum:</label>
					</th>
					<td>
						<input type="text" name="wedstrijd_datum" id="wedstrijd_datum" class="date_field" value="<?php echo mysql2date('d-m-Y', $wedstrijd->wedstrijd_datum); ?>" class="regular-text" />
					</td>
					<td>
						<span class="description">Geef de datum waarop de wedstrijd plaatsvindt. (DD-MM-JJJJ)</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="sluitings_datum">Sluiting van inschrijving (t/m):</label>
					</th>
					<td>
						<input type="text" name="sluitings_datum" id="sluitings_datum" class="date_field" value="<?php if($wedstrijd->wedstrijd_sluiting != "0000-00-00 00:00:00") echo mysql2date('d-m-Y', $wedstrijd->wedstrijd_sluiting); ?>" class="regular-text" />
					</td>
					<td>
						<span class="description">Geef de datum waarop inschrijven niet meer mogelijk is. (DD-MM-JJJJ)</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="eigen_klasse">20" klasse inschrijving:</label>
					</th>
					<td>
						<input type="checkbox" name="eigen_klasse" id="eigen_klasse" class="regular-text" <?php if($wedstrijd->wedstrijd_eigen_deelname === "1") { echo "checked=\"checked\" ";}?>/>
					</td>
					
					<td>
						<span class="description">Vink aan of inschrijven voor de eigen klasse mogelijk is.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="cruiser_klasse">Cruiser klasse inschrijving:</label>
					</th>
					<td>
						<input type="checkbox" name="cruiser_klasse" id="cruiser_klasse" class="regular-text" <?php if($wedstrijd->wedstrijd_cruiser_deelname === "1") { echo "checked=\"checked\" ";}?>/>
					</td>
					
					<td>
						<span class="description">Vink aan of inschrijven voor de cruiser klasse mogelijk is.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="promotie_klasse">Promotie klasse inschrijving:</label>
					</th>
					<td>
						<input type="checkbox" name="promotie_klasse" id="promotie_klasse" class="regular-text" <?php if($wedstrijd->wedstrijd_promotie_deelname === "1") { echo "checked=\"checked\" ";}?>/>
					</td>
					
					<td>
						<span class="description">Vink aan of inschrijven voor de promotie klasse mogelijk is.</span>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Wedstrijd bewerken" /> <a href="admin.php?page=inschrijvingen_beheer&id=<?php echo $wedstrijd->wedstrijd_ID; ?>&action=view">Annuleren</a></p>
		</form>
	
	<?php
	}
}
?>