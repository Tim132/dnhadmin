<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: tarieven-delete.inc.php
Doel  : "Template" voor het bevestigen van het verwijderen van Tarieven
Auteur: BugSlayer
*******************************************************************************************************/

// Alle gemarkeerde tarieven in een array stoppen
$tarieven = Array();
if (isset($_GET['tarief'])) {
	$value = $_GET['tarief'];
	if (is_array($value)) {
		foreach ($value as $val) {
			$tarieven[] = sanitize_text_field($val);
		}
	} else {
		$tarieven[] = sanitize_text_field($value);
	}
}

// Rubriek-informatie ophalen
global $wpdb;
$ids = join(',',$tarieven);  
$myrows = $wpdb->get_results("SELECT * FROM dnh_tarief WHERE Jaar IN ($ids)");

?>

<div class="wrap">
	<h2>Verwijder rubriek</h2>
	<p>Je hebt de volgende tarieven gemarkeerd om te verwijderen:</p>
	<ul> <?php
		foreach ($myrows as $row) {
			printf("<li>%s</li>",$row->Jaar);
		}
	?></ul>
	<form method="post" action="admin-post.php"> 

		<!-- We create a hidden field named action with the value corresponding.
			 This value is important as we’ll be able to process the form. -->
		<input type="hidden" name="action" value="dnh_delete_tarieven" />

		<?php
			//Hier hidden array-fields maken voor alle geselecteerde tarieven
			foreach($tarieven as $rubriek) {
				printf('<input type="hidden" name="jaar[]" value="%s" />', $rubriek);
			}
		?>

		<!-- This function is extremely useful and prevents your form from being submitted by a user other than an admin. 
	    	 It’s a security measure	-->
		<?php wp_nonce_field( 'dnh_verify' ); ?>


		<input type="submit" value="Bevestig verwijderen" class="button button-primary"/>
	</form>
</div>
