<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: leden-delete.inc.php
Doel  : "Template" voor het bevestigen van het verwijderen van leden
Auteur: BugSlayer
*******************************************************************************************************/

// Alle gemarkeerde leden in een array stoppen
$leden = Array();
if (isset($_GET['user_ID'])) {
	$value = $_GET['user_ID'];
	if (is_array($value)) {
		foreach ($value as $val) {
			$leden[] = sanitize_text_field($val);
		}
	} else {
		$leden[] = sanitize_text_field($value);
	}
}

// Rubriek-informatie ophalen
global $wpdb;
$ids = join(',',$leden);  
$myrows = $wpdb->get_results("SELECT * FROM dnh_lid WHERE ID IN ($ids)");

?>

<div class="wrap">
	<h2>Verwijder Leden</h2>
	<p>Je hebt de volgende leden gemarkeerd om te verwijderen:</p>
	<ul> <?php
		foreach ($myrows as $row) {
			printf("<li>%s</li>",$row->Naam);
		}
	?></ul>
	<form method="post" action="admin-post.php"> 

		<!-- We create a hidden field named action with the value corresponding.
			 This value is important as we’ll be able to process the form. -->
		<input type="hidden" name="action" value="dnh_delete_leden" />

		<?php
			//Hier hidden array-fields maken voor alle geselecteerde leden
			foreach($leden as $rubriek) {
				printf('<input type="hidden" name="ID[]" value="%s" />', $rubriek);
			}
		?>

		<!-- This function is extremely useful and prevents your form from being submitted by a user other than an admin. 
	    	 It’s a security measure	-->
		<?php wp_nonce_field( 'dnh_verify' ); ?>


		<input type="submit" value="Bevestig verwijderen" class="button button-primary"/>
	</form>
</div>
