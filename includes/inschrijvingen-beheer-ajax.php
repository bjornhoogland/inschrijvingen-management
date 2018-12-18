<?php
if ('inschrijvingen-gebruiker-ajax.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('<h2>Direct File Access Prohibited</h2>');

/**
 * Deals with all AJAX requests
 */

/*
 * Function to generate print page
 */
function inschrijvingen_beheer_ajax_print_cb(){
	if(current_user_can('inschrijvingen_cap_admin')) {
		
		$wedstrijd = inschrijvingen_wedstrijd_details($_GET['id']);
			if($wedstrijd) {
            	?>
			
				<h2>
					<?php echo $wedstrijd->wedstrijd_naam; ?>
				</h2>
			
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<tr class='format-default iedit' valign="top">
					<td style="width:25%">
						<span class="description"><i>Wedstrijddatum:</i></span><br />
						<?php echo mysql2date('j F Y', $wedstrijd->wedstrijd_datum); ?></td>
					<td style="width:25%"><span class="description"><i>Plaats:</i></span><br /><?php echo $wedstrijd->wedstrijd_plaats; ?></td>
					<td style="width:25%"><span class="description"><i>Sluiting van inschrijving:</i></span><br />
						<?php
							if($wedstrijd->wedstrijd_sluiting !== '0000-00-00 00:00:00'){
								echo mysql2date('j F Y', $wedstrijd->wedstrijd_sluiting);
							}
						?></td>
					<td style="width:25%"><span class="description"><i>Status:</i></span><br />
						<?php
							if($wedstrijd->wedstrijd_sluiting <= current_time('mysql', 0)){
								echo "Inschrijving gesloten";
							} else {
								echo "Inschrijving open";
							}
						?></td>
				</tr>
			</table>
			
			<br class="clear" />
			
			<?php
			$inschrijvingen = inschrijvingen_admin_wedstrijd_inschrijvingen($_GET['id']);
			?>
			
			<h3 class="title"><?php echo count($inschrijvingen); ?> Inschrijvingen</h3>
			
			<?php
			if($inschrijvingen) {
            ?>
			
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" class='manage-column' style="text-align:left;width:21%">Stuurbordnummer</th>
						<th scope="col" class='manage-column' style="text-align:left;width:24%">Naam</th>
						<th scope="col" class='manage-column' style="text-align:left;width:20%">Licentienummer</th>
						<th scope="col" class='manage-column' style="text-align:left;width:15%">Klasse</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" class='manage-column' style="text-align:left;border-top:1px solid black">Stuurbordnummer</th>
						<th scope="col" class='manage-column' style="text-align:left;border-top:1px solid black">Naam</th>
						<th scope="col" class='manage-column' style="text-align:left;border-top:1px solid black">Licentienummer</th>
						<th scope="col" class='manage-column' style="text-align:left;border-top:1px solid black">Klasse</th>
					</tr>
				</tfoot>
				<tbody id="the-list">
					<?php
						$alternate = false;
						foreach($inschrijvingen as $inschrijving) {
							if($alternate) { $alternate = false; }else{ $alternate = true; }
						?>
						
						<tr class='<?php if($alternate) echo "alternate ";?>format-default' valign="top">
							<td style="border-top:1px solid black"><?php echo $inschrijving->stuurbord; ?>&nbsp;</td>
							<td style="border-top:1px solid black"><?php echo $inschrijving->username; ?>&nbsp;</td>
							<td style="border-top:1px solid black"><?php echo $inschrijving->licentie; ?>&nbsp;</td>
							<td style="border-top:1px solid black"><?php echo $inschrijving->klasse; ?>&nbsp;</td>
						</tr>
							
						<?php
						}
					?>
				</tbody>
			</table>
			
			<script type="text/javascript">
				window.print();
			</script>


				<?php
				} else {
            	?>
<div id="message" class="error">
	<p><strong>Geen inschrijvingen gevonden!</strong></p>
</div>
            	<?php
            	}
			} else {
            	?>
<div id="message" class="error">
	<p><strong>Er is iets verkeerd gegaan bij het weergeven van de wedstrijd!</strong> Probeer het later nog eens of neem contact op met help@lochsprinters.nl</p>
</div>
            	<?php
            }
		
	} else {
    	echo "Er is iets mis gegaan.";
    }
    die();
}

/**
 *Hook it
 */
add_action('wp_ajax_inschrijvingen_beheer_print_ajax', 'inschrijvingen_beheer_ajax_print_cb');
?>