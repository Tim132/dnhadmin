<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: schepen-create.inc.php
Doel  : "Template" voor het toevoegen van een nieuwe schip
Auteur: BugSlayer
*******************************************************************************************************/
?>
<div class="wrap">
	<h2>Nieuwe schip</h2>
	<p>Nieuwe schip aanmaken.</p>
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
				<?php //if(!isset( $_GET['lid'])) { ?>
				<tr class="form-field form-required">
					<th scope="row"><label for="naam">Eigenaar ID </label></th>
					<td><input name="lidID" type="Text" id="lidID" value="<?php echo $_GET["lid"] ?>" aria-required="true"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="naam">Naam <span class="description">(verplicht)</span></label></th>
					<td><input name="Naam" type="Text" id="Naam" value="" aria-required="true"></td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="adres">Lengte <span class="description">(verplicht)</span></label></th>
					<td><input name="Lengte" type="number" id="Lengte" value="" aria-required="false"></td>
				</tr>
			</tbody>
		</table>

		<input type="submit" value="Nieuwe schip toevoegen" class="button button-primary"/>
	</form>
</div>
