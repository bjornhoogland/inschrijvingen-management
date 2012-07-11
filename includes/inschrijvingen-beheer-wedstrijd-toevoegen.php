<?php
if ('inschrijvingen-beheer-wedstrijd-toevoegen.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('<h2>Direct File Access Prohibited</h2>');
if(!current_user_can('inschrijvingen_cap_admin')) {
	wp_die('Leuk geprobeerd :)');
	return;
}
?>
<div class="wrap">

	<?php //<div id="icon-wedstrijd" class="icon32"><br /></div>?>
	<h2>
		Nieuwe Wedstrijd Toevoegen<?php //<a href="user-new.php" class="add-new-h2">Nieuwe toevoegen</a>?>
	</h2>
	
	<form action="" method="post">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="wedstrijd_titel">Wedstrijdtitel:</label>
				</th>
				<td>
					<input type="text" name="wedstrijd_titel" id="wedstrijd_titel" value="Vul een wedstrijdtitel in" class="regular-text" />
				</td>
				<td>
					<span class="description">Geef een korte beschrijving de wedstrijd.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="plaats">Plaats:</label>
				</th>
				<td>
					<input type="text" name="plaats" id="plaats" value="Vul een plaats in" class="regular-text" />
				</td>
				<td>
					<span class="description">Vul hier de locatie van de wedstrijd in.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="prijs">Inschrijfkosten:</label>
				</th>
				<td>
					<input type="text" name="prijs" id="prijs" value="0" class="small-text" /> Euro
				</td>
				<td>
					<span class="description">Vul de prijs in dat een rijder moet betalen voor deelname.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="wedstrijd_datum">Wedstrijddatum:</label>
				</th>
				<td>
					<input type="text" name="wedstrijd_datum" id="wedstrijd_datum" class="date_field" value="DD-MM-JJJJ" class="regular-text" />
				</td>
				<td>
					<span class="description">Geef de datum waarop de wedstrijd plaatsvindt.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="sluitings_datum">Sluiting van inschrijving:</label>
				</th>
				<td>
					<input type="text" name="sluitings_datum" id="sluitings_datum" class="date_field" value="DD-MM-JJJJ" class="regular-text" />
				</td>
				<td>
					<span class="description">Geef de datum waarop het niet meer mogelijk is om in te schrijven.</span>
				</td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Voeg de wedstrijd toe" /></p>
	</form>
	
</div>