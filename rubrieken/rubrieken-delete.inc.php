<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: rubrieken-delete.inc.php
Doel  : "Template" voor het bevestigen van het verwijderen van Rubrieken. Bij het verwijderen moet de
        gebruiker wel aangeven wat er met gekoppelde transacties moet worden gedaan.
Auteur: BugSlayer
*******************************************************************************************************/

// Alle gemarkeerde rubrieken in een array stoppen
$rubrieken = Array();
if (isset($_GET['rubriek'])) {
	$value = $_GET['rubriek'];
	if (is_array($value)) {
		foreach ($value as $val) {
			$rubrieken[] = sanitize_text_field($val);
		}
	} else {
		$rubrieken[] = sanitize_text_field($value);
	}
}

// Rubriek-informatie ophalen
global $wpdb;
$ids = join(',',$rubrieken);  
$myrows = $wpdb->get_results("SELECT * FROM dnh_rubriek WHERE ID IN ($ids)");

// TODO aantal gekoppelde transacties ophalen, maar ik heb nog geen transactie tabel :(
$trans_count = 352;

// Overige rubrieken ophalen om de <select> op te kunnen bouwen 
$options = $wpdb->get_results("SELECT * FROM dnh_rubriek WHERE ID NOT IN ($ids) ORDER BY ID");

?>

<div class="wrap">
	<h2>Verwijder rubriek</h2>
	<p>Je hebt de volgende rubrieken gemarkeerd om te verwijderen:</p>
	<ul> <?php
		foreach ($myrows as $row) {
			printf("<li>%s: %s</li>",$row->ID, $row->Naam);
		}
	?></ul>
	<p>Aan deze rubrieken zitten echter <?php echo $trans_count ?> transacties verbonden. Het is niet toegestaan die te verwijderen. Je kunt ervoor kiezen om de rubriekvan die transacties aan te passen. Daarvoor zijn een aantal opties beschikbaar. Kies er één uit, voordat je deze actie bevestigt: 
	</p>

	<form method="post" action="admin-post.php"> 

		<!-- We create a hidden field named action with the value corresponding.
			 This value is important as we’ll be able to process the form. -->
		<input type="hidden" name="action" value="dnh_delete_rubrieken" />

		<?php
			//Hier hidden array-fields maken voor alle geselecteerde rubrieken
			foreach($rubrieken as $rubriek) {
				printf('<input type="hidden" name="rubriek[]" value="%s" />', $rubriek);
			}
		?>

		<!-- This function is extremely useful and prevents your form from being submitted by a user other than an admin. 
	    	 It’s a security measure	-->
		<?php wp_nonce_field( 'dnh_verify' ); ?>

		<!-- En nu... de inhoud van het form -->
		<input type="radio" name="trans_action" value="empty">
			Verwijder de rubrieken van deze transacties. Ik ge ze zelf handmatig opnieuw rubriceren
		</input>

		<p/>

		<input type="radio" name="trans_action" value="rubr">
			Koppel alle transacties aan de volgende rubriek:
			 <select name="nwe_rubriek">
				<option value="-1">-- Kies een rubriek --</option>
			 	<?php
					foreach ($options as $opt) {
						printf('<option value="%s">%s: %s</option>',$opt->ID, $opt->ID, $opt->Naam);
					}
				?>
			</select>
		</input>

		<p/>

		<input type="submit" value="Bevestig verwijderen" class="button button-primary"/>
	</form>
</div>
