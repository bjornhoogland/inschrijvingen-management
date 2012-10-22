<?php
if ('inschrijvingen-gebruiker-algemeen.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('<h2>Direct File Access Prohibited</h2>');
if(!current_user_can('inschrijvingen_cap_subs')) {
	wp_die('Leuk geprobeerd :)');
	return;
}


    $action = (isset($_GET['action']))? $_GET['action'] : 'view';
    switch($action) {
        default:
        case 'view' :
        	global $current_user; get_currentuserinfo();
        	$meta = get_user_meta($current_user->ID,'bmx_profiel_meta', true);
        	
        	//$year = (isset($_GET['year']))? $_GET['year'] : date('Y',current_time('timestamp',0));
			$allRacesFinished = inschrijvingen_laatste_wedstrijd_datum() <= current_time('mysql',0);
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
			
			<h3 id="overzicht-title" class="title">Inschrijvingen Overzicht 2012</h3>
		
			<div class="tablenav top">
				Hieronder staan alle NFF wedstrijden van dit jaar. Wil je meedoen aan een wedstrijd? Klik dan op aanmelden in de kolom onder de juiste klasse. Wil je daarna toch niet meedoen? Klik dan op afmelden.<br /><strong>Let op!</strong> Kostenloos afmelden kan niet meer na de sluitingsdatum.
				<div class="tablenav-pages">
					<!--<a class="disabled" title="Ga naar het vorige jaar" href="admin.php?page=inschrijvingen_gebruiker&year=2012">‹</a>
					Seizoen <strong>2012</strong>
					<a title="Ga naar het volgende jaar" href="admin.php?page=inschrijvingen_gebruiker&year=2012">›</a>-->
					&nbsp;
				</div>
			</div>
			
			<?php
			$wedstrijden = inschrijvingen_gebruiker_wedstrijd_lijst();
			if($wedstrijden) {
            ?>
			
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" class='manage-column' style="width:13%">Datum</th>
						<th scope="col" class='manage-column' style="width:21%">Wedstrijdnaam</th>
						<th scope="col" class='manage-column' style="width:14%">Plaats</th>
						<th scope="col" class='manage-column' style="width:16%">Sluiting van inschrijving</th>
						<th scope="col" class='manage-column' style="width:12%">Klasse: 20"</th>
						<th scope="col" class='manage-column' style="width:12%">Klasse: Cruisers</th>
						<th scope="col" class='manage-column' style="width:12%">Klasse: Promotie</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" class='manage-column'>Datum</th>
						<th scope="col" class='manage-column'>Wedstrijdnaam</th>
						<th scope="col" class='manage-column'>Plaats</th>
						<th scope="col" class='manage-column'>Sluiting van inschrijving</th>
						<th scope="col" class='manage-column'>Klasse: 20"</th>
						<th scope="col" class='manage-column'>Klasse: Cruisers</th>
						<th scope="col" class='manage-column'>Klasse: Promotie</th>
					</tr>
				</tfoot>
				<tbody id="the-list">
					<?php 
						if(!$allRacesFinished){
					?>
					<tr id="pastButton" style="text-align:center;line-height:32px;display:none;">
						<td colspan="7"><a href="javascript:showPast()" class="button">&uarr; Laat afgelopen wedstrijden zien &uarr;</a></td>
					</tr>
					<?php
						}
						
						$alternate = false;
						foreach($wedstrijden as $wedstrijd) {
							if($alternate) { $alternate = false; }else{ $alternate = true; }
						?>
						
						<tr class='format-default <?php if($alternate) echo "alternate "; if($wedstrijd->wedstrijd_datum <= current_time('mysql', 0) && !$allRacesFinished) echo "past"; ?>' valign="top"
						>
							<td class="date column-date"><?php echo mysql2date('j F', $wedstrijd->wedstrijd_datum); ?></td>
							<td><strong><?php echo $wedstrijd->wedstrijd_naam; ?></strong></td>
							<td><?php echo $wedstrijd->wedstrijd_plaats; ?></td>
							<?php/*<td>
								<?php
								if($wedstrijd->wedstrijd_kosten == -1.00)
									echo 'Onbekend';
								elseif($wedstrijd->wedstrijd_kosten == 0.00)
									echo 'Gratis';
								else
									echo $wedstrijd->wedstrijd_kosten . ' Euro';
								?>
							</td>*/?>
							<td>
								<?php
									if($wedstrijd->wedstrijd_sluiting !== '0000-00-00 00:00:00'){
										echo mysql2date('j F', $wedstrijd->wedstrijd_sluiting);
									}
								?>
							</td>
							<td>
								<?php
									if($wedstrijd->wedstrijd_eigen_deelname) {
										if($wedstrijd->inschrijving_eigen_id) {
											?>
											<strong>Aangemeld</strong></br>
											<?php
											if($wedstrijd->wedstrijd_sluiting <= current_time('mysql', 0)){
												echo 'Gesloten';
											} else {
												?>
												<span class='trash'><a href="admin.php?page=inschrijvingen_gebruiker&action=afmelden&item_id=<?php echo $wedstrijd->inschrijving_eigen_id; ?>" title="Afmelden voor deze wedstrijd.">Afmelden</a></span>
												<?php
											}
										}
										else {
											if($wedstrijd->wedstrijd_sluiting <= current_time('mysql', 0)){
												echo 'Inschrijven</br>Gesloten';
											} else {
												?>
												<a href="admin.php?page=inschrijvingen_gebruiker&action=inschrijven&item_id=<?php echo $wedstrijd->wedstrijd_ID; ?>&type=eigen" title="Aanmelden voor de eigen klasse." class="button-primary" style="line-height:32px">Inschrijven</a>
												<?php
											}
										}
									}
									else {
										//echo "-";
									}
								?>
							</td>
							<td>
								<?php
									if($wedstrijd->wedstrijd_cruiser_deelname) {
										if($wedstrijd->inschrijving_cruiser_id) {
											?>
											<strong>Aangemeld</strong></br>
											<?php
											if($wedstrijd->wedstrijd_sluiting <= current_time('mysql', 0)){
												echo 'Gesloten';
											} else {
												?>
												<span class='trash'><a href="admin.php?page=inschrijvingen_gebruiker&action=afmelden&item_id=<?php echo $wedstrijd->inschrijving_cruiser_id; ?>" title="Afmelden voor deze wedstrijd.">Afmelden</a></span>
												<?php
											}
										}
										else {
											if($wedstrijd->wedstrijd_sluiting <= current_time('mysql', 0)){
												echo 'Inschrijven</br>Gesloten';
											} else {
												?>
												<a href="admin.php?page=inschrijvingen_gebruiker&action=inschrijven&item_id=<?php echo $wedstrijd->wedstrijd_ID; ?>&type=cruiser" title="Aanmelden voor de cruiser klasse." class="button-primary" style="line-height:32px">Inschrijven</a>
												<?php
											}
										}
									}
									else {
										//echo "-";
									}
								?>
							</td>
							<td>
								<?php
									if($wedstrijd->wedstrijd_promotie_deelname) {
										if($wedstrijd->inschrijving_promotie_id) {
											?>
											<strong>Aangemeld</strong></br>
											<?php
											if($wedstrijd->wedstrijd_sluiting <= current_time('mysql', 0)){
												echo 'Gesloten';
											} else {
												?>
												<span class='trash'><a href="admin.php?page=inschrijvingen_gebruiker&action=afmelden&item_id=<?php echo $wedstrijd->inschrijving_promotie_id; ?>" title="Afmelden voor deze wedstrijd.">Afmelden</a></span>
												<?php
											}
										}
										else {
											if($wedstrijd->wedstrijd_sluiting <= current_time('mysql', 0)){
												echo 'Inschrijven</br>Gesloten';
											} else {
												?>
												<a href="admin.php?page=inschrijvingen_gebruiker&action=inschrijven&item_id=<?php echo $wedstrijd->wedstrijd_ID; ?>&type=promotie" title="Aanmelden voor de promotieklasse." class="button-primary" style="line-height:32px">Inschrijven</a>
												<?php
											}
										}
									}
									else {
										//echo "-";
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
				if (window.print) {
					jQuery("#overzicht-title").append(' <a href="admin-ajax.php?action=inschrijvingen_print_ajax" class="button-primary" target="_blank">Print</a>');
				}
				
				function showPast(){
					jQuery("#pastButton").hide(100);
					jQuery("tr.format-default").show(500);
				}
				
				jQuery(document).ready(function() {
					jQuery(".past").hide();
					jQuery("#pastButton").show();
				});
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
		break;
		case 'inschrijven' :
            if(!isset($_GET['item_id']) || !isset($_GET['type'])) {
                ?>
<div id="message" class="error">
	<p><strong>Verkeerde URL! Probeer het nog eens met de knoppen.</strong></p>
</div>
                <?php
            }
            else {
            	$verificatie = inschrijvingen_gebruiker_wedstrijd_verificatie($_GET['item_id'], $_GET['type']);
            	if($verificatie){
            		global $wpdb, $inschrijvingen_db_table_name, $current_user; get_currentuserinfo();
            		$date = current_time('mysql', 0);
            		$insert = array(
            			'wedstrijd_id' => $_GET['item_id'],
           				'user_id' => $current_user->ID,
           				'inschrijving_datum' => $date,
           				'inschrijving_type' => $_GET['type']
           			);
           			$insert_dt = array('%d', '%d', '%s', '%s');
           			if($wpdb->insert($inschrijvingen_db_table_name['reg_tabel'], $insert, $insert_dt)) {
           				$_GET['action'] = 'view';
           				include (plugin_dir_path(WP_INSCHRIJVINGEN_ABSFILE) . 'includes/inschrijvingen-gebruiker-algemeen.php');
           			} else {
           				?>
<div id="message" class="error">
	<p><strong>Inschrijven is mislukt!</strong> Neem contact op met help@lochsprinters.nl</p>
</div>
            			<?php
           			}
            	} else {
            		?>
<div id="message" class="error">
	<p><strong>Mislukt! Of je mag niet inschrijven of je hebt je al ingeschreven voor deze wedstrijd.</strong></p>
</div>
            		<?php
            	}
            }
		break;
		case 'afmelden' :
			if(!isset($_GET['item_id'])) {
                ?>
<div id="message" class="error">
	<p><strong>Verkeerde URL! Probeer het nog eens met de knoppen.</strong></p>
</div>
                <?php
            }
            else {
            	global $wpdb, $inschrijvingen_db_table_name;
            	$wedstrijd_id = $wpdb->get_var($wpdb->prepare("SELECT wedstrijd_id FROM {$inschrijvingen_db_table_name['reg_tabel']} WHERE inschrijving_ID = %d", $_GET['item_id']));
            	$verificatie = inschrijvingen_wedstrijd_open_verificatie($wedstrijd_id);
            	if($verificatie){
            		global $wpdb, $inschrijvingen_db_table_name;
            		$sql = $wpdb->prepare("DELETE FROM $inschrijvingen_db_table_name[reg_tabel] WHERE inschrijving_ID=%d", $_GET['item_id']);
            		if($wpdb->query($sql)) {
            			$_GET['action'] = 'view';
           				include (plugin_dir_path(WP_INSCHRIJVINGEN_ABSFILE) . 'includes/inschrijvingen-gebruiker-algemeen.php');
            		} else {
            			?>
<div id="message" class="error">
	<p><strong>Afmelden is mislukt!</strong> Neem contact op met help@lochsprinters.nl</p>
</div>
            			<?php
            		}
            	} else {
            		?>
<div id="message" class="error">
	<p><strong>Mislukt! Of je mag niet afmelden of je hebt je al afgemeld voor deze wedstrijd.</strong></p>
</div>
            		<?php
            	}
            }
		break;
	}
	?>