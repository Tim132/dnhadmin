<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: schepen-create.inc.php
Doel  : "Template" voor het toevoegen van een nieuwe lid
Auteur: BugSlayer
*******************************************************************************************************/
?>
<div class="wrap">
	<h2>Nieuwe schip</h2>
	<p>Nieuwe lid aanmaken waarop transacties kunnen worden geboekt.</p>
	<!-- Our form is sending out data to admin-post.php. This is where you should send all your data.-->
	<form method="post" action="admin-post.php"> 

		<!-- We create a hidden field named action with the value corresponding.
			 This value is important as we’ll be able to process the form. -->
		<input type="hidden" name="action" value="dnh_save_lid" />

		<!-- This function is extremely useful and prevents your form from being submitted by a user other than an admin. 
	    	 It’s a security measure	-->
		<?php wp_nonce_field( 'dnh_verify' ); ?>


		<!-- En nu... de inhoud van het form -->
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row"><label for="naam">Naam <span class="description">(verplicht)</span></label></th>
					<td><input name="naam" type="text" id="naam" value="" aria-required="true"></td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="adres">Lengte <span class="description">(verplicht)</span></label></th>
					<td><input name="adres" type="number" id="Lengte" value="" aria-required="false"></td>
				</tr>
			</tbody>
		</table>

		<input type="submit" value="Nieuwe lid toevoegen" class="button button-primary"/>
	</form>
</div>
