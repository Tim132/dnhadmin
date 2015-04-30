<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: tarieven-create.inc.php
Doel  : "Template" voor het toevoegen van een nieuw Tarief
Auteur: BugSlayer
*******************************************************************************************************/
?>
<div class="wrap">
	<h2>Nieuw tarief</h2>
	<p>Nieuw tarief aanmaken voor een jaar zodat contributiefacturen kunnen worden aangemaakt.</p>
	<!-- Our form is sending out data to admin-post.php. This is where you should send all your data.-->
	<form method="post" action="admin-post.php"> 

		<!-- We create a hidden field named action with the value corresponding.
			 This value is important as we’ll be able to process the form. -->
		<input type="hidden" name="action" value="dnh_save_tarief" />

		<!-- This function is extremely useful and prevents your form from being submitted by a user other than an admin. 
	    	 It’s a security measure	-->
		<?php wp_nonce_field( 'dnh_verify' ); ?>


		<!-- En nu... de inhoud van het form -->
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row"><label for="jaar">Jaar <span class="description">(verplicht)</span></label></th>
					<td><input name="jaar" type="text" id="jaar" value="" aria-required="true"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="contributie_leden">Contributie leden <span class="description">(verplicht)</span></label></th>
					<td><input name="contributie_leden" type="text" id="contributie_leden" value="" aria-required="true"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="energietoeslag_leden">Energietoeslag leden <span class="description">(verplicht)</span></label></th>
					<td><input name="energietoeslag_leden" type="text" id="energietoeslag_leden" value="" aria-required="true"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="liggeld_leden">Liggeld leden, per meter lengte <span class="description">(verplicht)</span></label></th>
					<td><input name="liggeld_leden" type="text" id="liggeld_leden" value="" aria-required="true"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="liggeld_passanten">Liggeld passanten, per meter lengte per nacht <span class="description">(verplicht)</span></label></th>
					<td><input name="liggeld_passanten" type="text" id="liggeld_passanten" value="" aria-required="true"></td>
				</tr>
			</tbody>
		</table>

		<input type="submit" value="Nieuw tarief toevoegen" class="button button-primary"/>
	</form>
</div>
