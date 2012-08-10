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
    switch($pmode) {
        default:
        case 'view' :
        	if(!isset($_GET['id'])) {
            	?>
        
			<?php //<div id="icon-wedstrijd" class="icon32"><br /></div>?>
			<h2>
				Wedstrijden Overzicht<?php // <a href="admin.php?page=inschrijvingen_beheer_toevoegen" class="add-new-h2">Nieuwe toevoegen</a>?>
			</h2>
		
			<?php
			$wedstrijden = inschrijvingen_admin_wedstrijd_lijst();
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
								<strong><a class="row-title" href="admin.php?page=inschrijvingen_beheer&amp;id=<?php echo $wedstrijd->wedstrijd_ID; ?>&amp;action=view" title="Bekijk &#8220;<?php echo $wedstrijd->wedstrijd_naam; ?>&#8221;"><?php echo $wedstrijd->wedstrijd_naam; ?></a></strong>
								<?php/*<div class="row-actions">
									<span class='edit'><a href="admin.php?page=inschrijvingen_beheer&amp;id=0&amp;action=edit" title="Deze wedstrijd bewerken">Bewerken</a> | </span>
									<span class='trash'><a class='submitdelete' title='Deze wedstrijd verwijderen.' href='admin.php?page=inschrijvingen_beheer&amp;id=0&amp;action=delete'>Verwijderen</a></span>
								</div>*/?>
							</td>
							<td><?php echo $wedstrijd->wedstrijd_plaats; ?></td>
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
					<?php echo $wedstrijd->wedstrijd_naam; ?>
				</h2>
			
				<?php/*<ul class='subsubsub'>
					<li><a href="admin.php?page=inschrijvingen_beheer&amp;id=0&amp;action=view" title="Deze wedstrijd bekijken" class="current">Bekijken</a> |</li>
					<li><a href="admin.php?page=inschrijvingen_beheer&amp;id=0&amp;action=edit" title="Deze wedstrijd bewerken">Bewerken</a> |</li>
					<li><a class='trash' title='Deze wedstrijd verwijderen.' href='admin.php?page=inschrijvingen_beheer&amp;id=0&amp;action=delete'>Verwijderen</a></li>
				</ul>*/?>
			
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
	}
	?>
</div>