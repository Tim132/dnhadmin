<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: leden-delete.inc.php
Doel  : "Template" voor het bevestigen van het verwijderen van Rubrieken. Bij het verwijderen moet de
        gebruiker wel aangeven wat er met gekoppelde transacties moet worden gedaan.
Auteur: BugSlayer
*******************************************************************************************************/

// Alle gemarkeerde leden in een array stoppen
$leden = Array();
if (isset($_GET['lid'])) {
	$value = $_GET['lid'];
	if (is_array($value)) {
		foreach ($value as $val) {
			$leden[] = sanitize_text_field($val);
		}
	} else {
		$leden[] = sanitize_text_field($value);
	}
}

// Lid-informatie ophalen
global $wpdb;
$ids = join(',',$leden);  
$myrows = $wpdb->get_results("SELECT * FROM dnh_lid WHERE LidID IN ($LidID)");

// TODO aantal gekoppelde transacties ophalen, maar ik heb nog geen transactie tabel :( / en ik zou ook niet weten hoe om dat te doen :(((
$trans_count = 352;

// Overige rubrieken ophalen om de <select> op te kunnen bouwen 
$options = $wpdb->get_results("SELECT * FROM dnh_lid WHERE LidID NOT IN ($LidID) ORDER BY LidID");

?>

<div class="wrap">
	<h2>Verwijder lid</h2>
	
	<ul> <?php
		foreach ($myrows as $row) {
			printf("<li>%s: %s</li>",$row->LidID, $row->LidID);
		}
	?></ul>

	<form method="post" action="admin-post.php"> 

		<!-- We create a hidden field named action with the value corresponding.
			 This value is important as we’ll be able to process the form. -->
		<input type="hidden" name="action" value="dnh_delete_Leden" />

		<?php
			//Hier hidden array-fields maken voor alle geselecteerde rubrieken
			foreach($leden as $lid) {
				printf('<input type="hidden" name="lid[]" value="%s" />', $lid);
			}
		?>

		<!-- This function is extremely useful and prevents your form from being submitted by a user other than an admin. 
	    	 It’s a security measure	-->
		<?php wp_nonce_field( 'dnh_verify' ); ?>

		<!-- En nu... de inhoud van het form -->
		<input type="radio" name="trans_action" value="empty">
			Bevestiging.
		</input>

		<p/>

		<input type="submit" value="verwijderen" class="button button-primary"/>
	</form>
</div>

