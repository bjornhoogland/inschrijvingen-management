<?php
if ('inschrijvingen-beheer-algemeen.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('<h2>Direct File Access Prohibited</h2>');
if(!current_user_can('inschrijvingen_cap_admin')) {
	wp_die('Leuk geprobeerd :)');
	return;
}
?>
<div class="wrap">

	<?php
    $action = (isset($_GET['action']))? $_GET['action'] : 'view';
    switch($action) {
        default:
        case 'view' :
        	if(!isset($_GET['id'])) {
        		$year = (isset($_GET['year']))? $_GET['year'] : date('Y',current_time('timestamp',0));
            	?>
        
			<h2>
				Wedstrijden Overzicht <?php echo $year; ?> <a href="admin.php?page=inschrijvingen_beheer&action=new" class="add-new-h2">Nieuwe toevoegen</a>
			</h2>
			<div class="tablenav top" style="position:absolute;top:8px;right:23px;">
				<div class="tablenav-pages">
					<?php if(mysql2date('Y',inschrijvingen_eerste_wedstrijd_datum()) < $year){$previousYear = $year - 1; $previousDisabled = false;} else { $previousYear = $year; $previousDisabled = true;} ?>
					<a <?php if($previousDisabled){ echo "class=\"disabled\""; } ?>title="Ga naar het vorige jaar" href="admin.php?page=inschrijvingen_beheer&year=<?php echo $previousYear; ?>">‹</a>
					Seizoen <strong><?php echo $year;?></strong>
					<?php if(mysql2date('Y',inschrijvingen_laatste_wedstrijd_datum()) > $year){$nextYear = $year + 1; $nextDisabled = false;} else { $nextYear = $year; $nextDisabled = true;} ?>
					<a <?php if($nextDisabled){ echo "class=\"disabled\""; } ?>title="Ga naar het volgende jaar" href="admin.php?page=inschrijvingen_beheer&year=<?php echo $nextYear; ?>">›</a>
					&nbsp;
				</div>
			</div>
		
			<?php
			$wedstrijden = inschrijvingen_admin_wedstrijd_lijst($year);
			if($wedstrijden) {
            ?>
            
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" class='manage-column' style="width:14%">Datum</th>
						<th scope="col" class='manage-column' style="width:35%">Wedstrijdnaam</th>
						<th scope="col" class='manage-column' style="width:15%">Plaats</th>
						<th scope="col" class='manage-column' style="width:17%">Sluiting van inschrijving</th>
						<th scope="col" class='manage-column' style="width:19%">Status</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" class='manage-column'>Datum</th>
						<th scope="col" class='manage-column'>Wedstrijdnaam</th>
						<th scope="col" class='manage-column'>Plaats</th>
						<th scope="col" class='manage-column'>Sluiting van inschrijving</th>
						<th scope="col" class='manage-column'>Status</th>
					</tr>
				</tfoot>
				<tbody id="the-list">
					<?php
						$alternate = false;
						foreach($wedstrijden as $wedstrijd) {
							if($alternate) { $alternate = false; }else{ $alternate = true; }
						?>
						
						<tr class='<?php if($alternate) echo "alternate ";?>format-default' valign="top">
							<td class="date column-date"><?php echo mysql2date('j F', $wedstrijd->wedstrijd_datum); ?></td>
							<td>
								<strong><a class="row-title" href="admin.php?page=inschrijvingen_beheer&amp;id=<?php echo $wedstrijd->wedstrijd_ID; ?>&amp;action=view" title="Bekijk &#8220;<?php echo esc_html($wedstrijd->wedstrijd_naam); ?>&#8221;"><?php echo esc_html($wedstrijd->wedstrijd_naam); ?></a></strong>
							</td>
							<td><?php echo esc_html($wedstrijd->wedstrijd_plaats); ?></td>
							<td>
								<?php
									if($wedstrijd->wedstrijd_sluiting !== '0000-00-00 00:00:00'){
										echo mysql2date('j F', $wedstrijd->wedstrijd_sluiting);
									}
								?>
							</td>
							<td>
								<?php
								if($wedstrijd->wedstrijd_sluiting <= current_time('mysql', 0)){
									echo "Inschrijving gesloten";
								} else {
									echo "Inschrijving open";
								}
								?>
							</td>
						</tr>
							
						<?php
						}
					?>
				</tbody>
			</table>
	
	
			<?php
			}
            else {
                ?>
<div id="message" class="error">
	<p><strong>Er is iets verkeerd gegaan bij het maken van de wedstrijdlijst!</strong> Probeer het later nog eens of neem contact op met help@lochsprinters.nl</p>
</div>
                <?php
            }
		}
		else {
			$wedstrijd = inschrijvingen_wedstrijd_details($_GET['id']);
			if($wedstrijd) {
            	?>
			
				<?php //<div id="icon-wedstrijd" class="icon32"><br /></div>?>
				<h2>
					<?php echo esc_html($wedstrijd->wedstrijd_naam); ?>
				</h2>
			
				<ul class='subsubsub'>
					<li><a href="admin.php?page=inschrijvingen_beheer&amp;id=<?php echo $_GET['id']; ?>&amp;action=view" title="Deze wedstrijd bekijken" class="current">Bekijken</a> |</li>
					<li><a href="admin.php?page=inschrijvingen_beheer&amp;id=<?php echo $_GET['id']; ?>&amp;action=edit" title="Deze wedstrijd bewerken">Bewerken</a> |</li>
					<li><a class='trash' title='Deze wedstrijd verwijderen.' href='admin.php?page=inschrijvingen_beheer&amp;id=<?php echo $_GET['id']; ?>&amp;action=delete'>Verwijderen</a></li>
				</ul>
			
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<tr class='format-default iedit' valign="top">
					<td style="width:20%">
						<span class="description"><i>Wedstrijddatum:</i></span><br />
						<?php echo mysql2date('j F Y', $wedstrijd->wedstrijd_datum); ?></td>
					<td style="width:20%"><span class="description"><i>Wedstrijdplaats:</i></span><br /><?php echo esc_html($wedstrijd->wedstrijd_plaats); ?></td>
					<td style="width:20%"><span class="description"><i>Sluiting van inschrijving:</i></span><br />
						<?php
							if($wedstrijd->wedstrijd_sluiting !== '0000-00-00 00:00:00'){
								echo mysql2date('j F Y', $wedstrijd->wedstrijd_sluiting);
							}
						?></td>
					<td style="width:20%"><span class="description"><i>Klassen:</i></span><br />
						<?php if($wedstrijd->wedstrijd_eigen_deelname) echo "20\""; ?>
						<?php if($wedstrijd->wedstrijd_eigen_deelname && $wedstrijd->wedstrijd_cruiser_deelname) echo " / "; ?>
						<?php if($wedstrijd->wedstrijd_cruiser_deelname) echo "Cruiser"; ?>
						<?php if($wedstrijd->wedstrijd_cruiser_deelname && $wedstrijd->wedstrijd_promotie_deelname) echo " / "; ?>
						<?php if($wedstrijd->wedstrijd_promotie_deelname) echo "Promotie"; ?></td>
					<td style="width:20%"><span class="description"><i>Status:</i></span><br />
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
			
			<h3 class="title"><?php echo count($inschrijvingen); ?> Inschrijvingen<?php /* <a href="admin.php?page=inschrijvingen_beheer_exporteren" class="button-primary">Exporteren</a>*/?>
			<script type="text/javascript">
				if (window.print) {
					document.write('<a href="admin-ajax.php?action=inschrijvingen_beheer_print_ajax&id=<?php echo $_GET['id']; ?>" class="button-primary" target="_blank">Print</a>');
				}
			</script>
			</h3>
			
			<?php
			if($inschrijvingen) {
            ?>
			
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" class='manage-column' style="width:20%">Stuurbordnummer</th>
						<th scope="col" class='manage-column'>Naam</th>
						<th scope="col" class='manage-column' style="width:20%">Inschrijfdatum</th>
						<th scope="col" class='manage-column' style="width:20%">Licentienummer</th>
						<th scope="col" class='manage-column' style="width:20%">Klasse</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" class='manage-column'>Stuurbordnummer</th>
						<th scope="col" class='manage-column'>Naam</th>
						<th scope="col" class='manage-column'>Inschrijfdatum</th>
						<th scope="col" class='manage-column'>Licentienummer</th>
						<th scope="col" class='manage-column'>Klasse</th>
					</tr>
				</tfoot>
				<tbody id="the-list">
					<?php
						$alternate = false;
						foreach($inschrijvingen as $inschrijving) {
							if($alternate) { $alternate = false; }else{ $alternate = true; }
						?>
						
						<tr class='<?php if($alternate) echo "alternate ";?>format-default' valign="top">
							<td><?php echo $inschrijving->stuurbord; ?></td>
							<td><?php echo $inschrijving->username; ?></td>
							<td><?php echo mysql2date('j F', $inschrijving->inschrijving_datum); ?></td>
							<td><?php echo $inschrijving->licentie; ?></td>
							<td><?php echo $inschrijving->klasse; ?></td>
						</tr>
							
						<?php
						}
					?>
				</tbody>
			</table>


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
		}
		break;
		case 'new':
			include (plugin_dir_path(WP_INSCHRIJVINGEN_ABSFILE) . 'includes/inschrijvingen-beheer-wedstrijd-toevoegen.php');
		break;
		case 'edit':
			include (plugin_dir_path(WP_INSCHRIJVINGEN_ABSFILE) . 'includes/inschrijvingen-beheer-wedstrijd-bewerken.php');
		break;
		case 'delete':
			if(!isset($_GET['id'])) {
				?>
				<div class="error"><p><strong>Verkeerde URL! Probeer het nog eens met de knoppen.</strong></p></div>
				<?php
			} else {
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					$wedstrijd = inschrijvingen_wedstrijd_details($_GET['id']);
					if($wedstrijd) {
						$inschrijvingen = inschrijvingen_admin_wedstrijd_inschrijvingen($_GET['id']);
						
						global $inschrijvingen_db_table_name, $wpdb;
						
						/* Verwijder de inschrijvingen voor deze wedstrijd */
		                if($wpdb->query($wpdb->prepare("DELETE FROM $inschrijvingen_db_table_name[reg_tabel] WHERE wedstrijd_id = %d", $_POST['id']))) {
		                    $message = count($inschrijvingen) . " Inschrijvingen voor deze wedstrijd zijn verwijderd.";
		                }
		                else {
		                    $message = "Er waren geen inschrijvingen voor deze wedstrijd.";
		                }
						
						/* Verwijder deze wedstrijd */
						$sql = $wpdb->prepare("DELETE FROM $inschrijvingen_db_table_name[wedstrijden] WHERE wedstrijd_ID=%d", $_POST['id']);
	                
		                if($wpdb->query($sql)) {
		                    ?>
		                    <div id="message" class="updated"><p><strong>Wedstrijd "<?php echo esc_html($wedstrijd->wedstrijd_naam); ?>" is verwijderd.</strong><br /><?php echo $message; ?></p></div>
		                    <?php
		                }
		                else {
		                    ?>
		                    <div class="error fade">Could not delete! Please check database.</div>
		                    <?php
		                    ?><div class="error"><p><strong>Wedstrijd kan niet worden verwijderd! Controleer de database.</strong><br /><?php echo $message; ?></p></div><?php
		                }
		                
		                // show overzicht pagina
		                $_GET['action'] = 'view';
						$_GET['id'] = null;
	           			include (plugin_dir_path(WP_INSCHRIJVINGEN_ABSFILE) . 'includes/inschrijvingen-beheer-algemeen.php');
           			}
				
				} else {
					$wedstrijd = inschrijvingen_wedstrijd_details($_GET['id']);
					if($wedstrijd) {
						$inschrijvingen = inschrijvingen_admin_wedstrijd_inschrijvingen($_GET['id']);
						?>
				    	<div id="message" class="updated">
				        	<p>
				        		<strong>Weet je zeker dat je wedstrijd "<?php echo esc_html($wedstrijd->wedstrijd_naam); ?>" wilt verwijderen?</strong><br /><?php echo count($inschrijvingen); ?> Inschrijvingen worden ook verwijderd.<br />
				        	</p>
				        			
			        		<form action="" method="post">
								<input type="hidden" name="id" id="id" value="<?php echo $wedstrijd->wedstrijd_ID; ?>" />
			        			<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Wedstrijd verwijderen" /> <a href="admin.php?page=inschrijvingen_beheer&id=<?php echo $wedstrijd->wedstrijd_ID; ?>&action=view">Annuleren</a></p>
							</form>
				        	
				    	</div>
			    		<?php
			    		// show normale wedstrijdview
			    		$_GET['action'] = 'view';
           				include (plugin_dir_path(WP_INSCHRIJVINGEN_ABSFILE) . 'includes/inschrijvingen-beheer-algemeen.php');
			    	}
		    	}
			}
		break;
	}
	?>
</div>