<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: rubrieken-edit.inc.php
Doel  : "Template" voor het bewerken van een bestaande Rubiek
Auteur: BugSlayer
*******************************************************************************************************/

?>
<div class="wrap">
	<h2>Bewerk lid </h2>
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
					<th scope="row"><label for="id">lidID <span class="description">(verplicht)</span></label></th>
					<td><input name="id" type="text" id="id" value="<?php echo $item->lidID ?>" aria-required="true"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="naam">Naam <span class="description">(verplicht)</span></label></th>
					<td><input name="naam" type="text" id="naam" value="<?php echo $item->Naam ?>" aria-required="true"></td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="adres">Adres <span class="description">(verplicht)</span></label></th>
					<td><input name="adres" type="text" id="adres" value="<?php echo $item->Adres ?>" aria-required="false"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="telefoon">Telefoon <span class="description">(verplicht)</span></label></th>
					<td><input name="telefoon" type="text" id="telefoon" value="<?php echo $item->Telefoon ?>" aria-required="true"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="email">Email <span class="description">(verplicht)</span></label></th>
					<td><input name="email" type="text" id="email" value="<?php echo $item->Email ?>" aria-required="true"></td>
				</tr>

			</tbody>
		</table>

		<input type="submit" value="lid bijwerken" class="button button-primary"/>
	</form>
</div>
