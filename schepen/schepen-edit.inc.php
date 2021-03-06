<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: schepen-edit.inc.php
Doel  : "Template" voor het bewerken van een bestaand schip
Auteur: BugSlayer
*******************************************************************************************************/
?>
<div class="wrap">
	<h2>Bewerk schip</h2>
	<!-- Our form is sending out data to admin-post.php. This is where you should send all your data.-->
	<form method="post" action="admin-post.php"> 

		<!-- We create a hidden field named action with the value corresponding.
			 This value is important as we’ll be able to process the form. -->
		<input type="hidden" name="action" value="dnh_save_schip" />

		<!-- This function is extremely useful and prevents your form from being submitted by a user other than an admin. 
	    	 It’s a security measure	-->
		<?php wp_nonce_field( 'dnh_verify' ); ?>

		<!-- En nu... de inhoud van het form -->
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row"><label for="schipID">eigenaar ID <span class="description">(verplicht)</span></label></th>
					<td><input name="schipID" type="number" id="schipID" value="<?php echo $item->schipID ?>" aria-required="true"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="naam">Naam <span class="description">(verplicht)</span></label></th>
					<td><input name="naam" type="text" id="naam" value="<?php echo $item->Naam ?>" aria-required="true"></td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="lengte">Lengte <span class="description">(verplicht)</span></label></th>
					<td><input name="lengte" type="text" id="lengte" value="<?php echo $item->Lengte ?>" aria-required="false"></td>
				</tr>
			</tbody>
		</table>

		<input type="submit" value="schip bijwerken" class="button button-primary"/>
	</form>
</div>
