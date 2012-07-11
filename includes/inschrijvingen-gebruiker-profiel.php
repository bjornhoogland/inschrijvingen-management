<?php
if ('inschrijvingen-gebruiker-profiel.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('<h2>Direct File Access Prohibited</h2>');
if(!current_user_can('inschrijvingen_cap_subs')) {
	wp_die('Leuk geprobeerd :)');
	return;
}
?>
<div class="wrap">
    <h2>Rijder Gegevens <a href="profile.php" class="add-new-h2">Profiel wijzigen</a></h2>
    <p>
        Om in te kunnen schrijven voor wedstrijden moet je eerst onderstaande velden invullen en opslaan.
        <br />
        Voor het veranderen van je Profiel informatie, wachtwoord etc… ga je naar het Profiel menu. <a href="profile.php">Ga hier meteen naar je profiel.</a>
    </p>
    
    <form action="" method="post">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="stuurbord">Stuurbord nummer:</label>
				</th>
				<td>
					<input type="text" name="stuurbord" id="stuurbord" value="900" class="small-text" />
				</td>
				<td>
					<span class="description">Vul hier je stuurbordnummer in dat je van de vereniging hebt gekregen. Met dit nummer rijd je tijdens de wedstrijd.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="licentie">NFF licentie nummer:</label>
				</th>
				<td>
					<input type="text" name="licentie" id="licentie" value="####" class="small-text" />
				</td>
				<td>
					<span class="description">Vul je licentienummer in dat op je licentie staat vermeld. Dit nummer is nodig om in te kunnen schrijven.</span>
				</td>
			</tr>
			<tr>
				<th scope="row">Geslacht</th>
				<td>
					<fieldset><legend class="screen-reader-text"><span>Geslacht</span></legend>
						<label title='Man'><input type='radio' name='geslacht' value='m' checked='checked' /> <span>Man</span></label><br />
						<label title='Vrouw'><input type='radio' name='geslacht' value='v' /> <span>Vrouw</span></label>
					</fieldset>
				</td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Wijzigen" /></p>
	</form>
    
</div>