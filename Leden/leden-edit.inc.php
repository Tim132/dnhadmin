<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: leden-edit.inc.php
Doel  : "Template" voor het bewerken van een bestaand lid
Auteur: BugSlayer
*******************************************************************************************************/
?>
<div class="wrap">
	<h2>Bewerk tarief</h2>
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
					<th scope="row"><label for="jaar">naam <span class="description">(verplicht)</span></label></th>
					<td><input name="jaar" type="text" id="jaar" value="<?php echo $item->naam ?>" aria-required="true"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="contributie_leden">adres<span class="description">(verplicht)</span></label></th>
					<td><input name="contributie_leden" type="text" id="contributie_leden" value="<?php echo $item->adres ?>" aria-required="true"></td>
				</tr>
				
			</tbody>
		</table>

		<input type="submit" value="lid bijwerken" class="button button-primary"/>
	</form>
</div>
