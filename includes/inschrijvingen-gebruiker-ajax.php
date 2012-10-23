<?php
if ('inschrijvingen-gebruiker-ajax.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('<h2>Direct File Access Prohibited</h2>');

/**
 * Deals with all AJAX requests
 */

/*
 * Function to generate print page
 */
function inschrijvingen_gebruiker_ajax_print_cb(){
	if(current_user_can('inschrijvingen_cap_subs')) {
        global $current_user; get_currentuserinfo();
        	$meta = get_user_meta($current_user->ID,'bmx_profiel_meta', true);
        	
        	$year = (isset($_GET['year']))? $_GET['year'] : date('Y',current_time('timestamp',0));
        	?>
        	
        	<div class="wrap">
        	
			<?php //<div id="icon-wedstrijd" class="icon32"><br /></div>?>
			<h2>
				<?php echo $current_user->display_name; ?>
			</h2>
		
			<?php
			if(!$meta || !$meta["bmx_Geslacht"] || !$meta["bmx_Stuurbordnummer"] || !$meta["bmx_Licentienummer"] || !$meta["bmx_Geboortedag"] || !$meta["bmx_Geboortemaand"] || !$meta["bmx_Geboortejaar"]){
				?><div id="message" class="error">
					<p><strong>Om in te kunnen schrijven ontbreken er nog BMX gegevens.</strong> Neem contact op met help@lochsprinters.nl</p>
				</div><?php
			}
			?>
			
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<tr class='format-default iedit' valign="top">
					<?php /*<td style="width:15%">
						<span class="description">Geboortedatum:</span><br />
						<abbr title="4 juli 2012">4 juli 2012</abbr></td>*/ ?>
					<td style="width:15%"><span class="description"><i>Geslacht:</i></span><br /><?php echo $meta["bmx_Geslacht"]; ?></td>
					<td style="width:18%"><span class="description"><i>Geboortedatum:</i></span><br />
					<?php echo $meta["bmx_Geboortedag"] . ' ' . $meta["bmx_Geboortemaand"] . ' ' . $meta["bmx_Geboortejaar"]; ?></td>
					<td style="width:18%"><span class="description"><i>Stuurbordnummer:</i></span><br /><?php echo $meta["bmx_Stuurbordnummer"]; ?></td>
					<td style="width:34%"><span class="description"><i>NFF licentienummer:</i></span><br /><?php echo $meta["bmx_Licentienummer"]; ?></td>
					<?php//<td style="width:34%"><span class="description">BMX gegevens wijzigen:</span><br /><a href="profile.php#BMX" title="Profiel wijzigen">Ga naar je profiel</a></td>?>
				</tr>
			</table>
			
			<br class="clear" />
			
			<h3 class="title">Inschrijvingen Overzicht <?php echo $year; ?></h3>
			
			<?php
			$wedstrijden = inschrijvingen_gebruiker_wedstrijd_lijst($year);
			if($wedstrijden) {
            ?>
			
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" class='manage-column' style="text-align:left;width:15%">Datum</th>
						<th scope="col" class='manage-column' style="text-align:left;width:31%">Wedstrijdnaam</th>
						<th scope="col" class='manage-column' style="text-align:left;width:21%">Plaats</th>
						<th scope="col" class='manage-column' style="text-align:left;width:11%">20"</th>
						<th scope="col" class='manage-column' style="text-align:left;width:11%">Cruisers</th>
						<th scope="col" class='manage-column' style="text-align:left;width:11%">Promotie</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" class='manage-column' style="text-align:left;border-top:1px solid black">Datum</th>
						<th scope="col" class='manage-column' style="text-align:left;border-top:1px solid black">Wedstrijdnaam</th>
						<th scope="col" class='manage-column' style="text-align:left;border-top:1px solid black">Plaats</th>
						<th scope="col" class='manage-column' style="text-align:left;border-top:1px solid black">20"</th>
						<th scope="col" class='manage-column' style="text-align:left;border-top:1px solid black">Cruisers</th>
						<th scope="col" class='manage-column' style="text-align:left;border-top:1px solid black">Promotie</th>
					</tr>
				</tfoot>
				<tbody id="the-list">
					<?php
						$alternate = false;
						foreach($wedstrijden as $wedstrijd) {
							if($alternate) { $alternate = false; }else{ $alternate = true; }
						?>
						
						<tr class='<?php if($alternate) echo "alternate ";?>format-default edit' valign="top">
							<td class="date column-date" style="border-top:1px solid black"><?php echo mysql2date('j F', $wedstrijd->wedstrijd_datum); ?>&nbsp;</td>
							<td style="border-top:1px solid black"><strong><?php echo $wedstrijd->wedstrijd_naam; ?></strong>&nbsp;</td>
							<td style="border-top:1px solid black"><?php echo $wedstrijd->wedstrijd_plaats; ?>&nbsp;</td>
							<?php/*<td>
								<?php
								if($wedstrijd->wedstrijd_kosten == -1.00)
									echo 'Onbekend';
								elseif($wedstrijd->wedstrijd_kosten == 0.00)
									echo 'Gratis';
								else
									echo $wedstrijd->wedstrijd_kosten . ' Euro';
								?>
							</td>
							<td style="border-top:1px solid black"><?php echo mysql2date('j F Y', $wedstrijd->wedstrijd_sluiting); ?></td>*/?>
							<td style="border-top:1px solid black">
								<?php
									if($wedstrijd->wedstrijd_eigen_deelname) {
										if($wedstrijd->inschrijving_eigen_id) {
											?>
											<strong>X</strong></br>
											<?php
										}
										else {
											echo "&nbsp;";
										}
									}
									else {
										echo "&nbsp;";
									}
								?>
							</td>
							<td style="border-top:1px solid black">
								<?php
									if($wedstrijd->wedstrijd_cruiser_deelname) {
										if($wedstrijd->inschrijving_cruiser_id) {
											?>
											<strong>X</strong></br>
											<?php
										}
										else {
											echo "&nbsp;";
										}
									}
									else {
										echo "&nbsp;";
									}
								?>
							</td>
							<td style="border-top:1px solid black">
								<?php
									if($wedstrijd->wedstrijd_promotie_deelname) {
										if($wedstrijd->inschrijving_promotie_id) {
											?>
											<strong>X</strong></br>
											<?php
										}
										else {
											echo "&nbsp;";
										}
									}
									else {
										echo "&nbsp;";
									}
								?>
							</td>
						</tr>
						
						<?php
						}
					?>
				</tbody>
			</table>
			</div>
			
			<script type="text/javascript">
				window.print();
			</script>
	
			<?php
			}
            else {
                ?>
<div id="message" class="error">
	<p><strong>Er is iets verkeerd gegaan bij het maken van de wedstrijdlijst!</strong> Probeer het later nog eens of neem contact op met help@lochsprinters.nl</p>
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
add_action('wp_ajax_inschrijvingen_print_ajax', 'inschrijvingen_gebruiker_ajax_print_cb');
?>