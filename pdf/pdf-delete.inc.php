<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: pdf-delete.inc.php
Doel  : "Template" voor het bevestigen van het verwijderen van pdf. Bij het verwijderen moet de
        gebruiker wel aangeven wat er met gekoppelde transacties moet worden gedaan.
Auteur: BugSlayer
*******************************************************************************************************/

// Alle gemarkeerde pdf in een array stoppen
$pdf = Array();
if (isset($_GET['pdf'])) {
	$value = $_GET['pdf'];
	if (is_array($value)) {
		foreach ($value as $val) {
			$pdf[] = sanitize_text_field($val);
		}
	} else {
		$pdf[] = sanitize_text_field($value);
	}
}

// pdf-informatie ophalen
global $wpdb;
$ids = join(',',$pdf);  
$myrows = $wpdb->get_results("SELECT * FROM dnh_pdf WHERE ID IN ($ids)");

// TODO aantal gekoppelde transacties ophalen, maar ik heb nog geen transactie tabel :(
$trans_count = 352;

// Overige pdf ophalen om de <select> op te kunnen bouwen 
$options = $wpdb->get_results("SELECT * FROM dnh_pdf WHERE ID NOT IN ($ids) ORDER BY ID");

?>

<div class="wrap">
	<h2>Verwijder pdf</h2>
	<p>Je hebt de volgende pdf gemarkeerd om te verwijderen:</p>
	<ul> <?php
		foreach ($myrows as $row) {
			printf("<li>%s: %s</li>",$row->ID, $row->Naam);
		}
	?></ul>
	<p>Aan deze pdf zitten echter <?php echo $trans_count ?> transacties verbonden. Het is niet toegestaan die te verwijderen. Je kunt ervoor kiezen om de pdfvan die transacties aan te passen. Daarvoor zijn een aantal opties beschikbaar. Kies er één uit, voordat je deze actie bevestigt: 
	</p>

	<form method="post" action="admin-post.php"> 

		<!-- We create a hidden field named action with the value corresponding.
			 This value is important as we’ll be able to process the form. -->
		<input type="hidden" name="action" value="dnh_delete_pdf" />

		<?php
			//Hier hidden array-fields maken voor alle geselecteerde pdf
			foreach($pdf as $pdf) {
				printf('<input type="hidden" name="pdf[]" value="%s" />', $pdf);
			}
		?>

		<!-- This function is extremely useful and prevents your form from being submitted by a user other than an admin. 
	    	 It’s a security measure	-->
		<?php wp_nonce_field( 'dnh_verify' ); ?>

		<!-- En nu... de inhoud van het form -->
		<input type="radio" name="trans_action" value="empty">
			Verwijder de pdf van deze transacties. Ik ge ze zelf handmatig opnieuw rubriceren
		</input>

		<p/>

		<input type="radio" name="trans_action" value="rubr">
			Koppel alle transacties aan de volgende pdf:
			 <select name="nwe_pdf">
				<option value="-1">-- Kies een pdf --</option>
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
