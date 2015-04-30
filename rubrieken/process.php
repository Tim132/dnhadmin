<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: rubrieken/process.php
Doel  : Alles voor het verwerken van wijzigingen van Rubrieken
Auteur: BugSlayer
*******************************************************************************************************/

/**************************************************************** 
TOEVOEGEN/BIJWERKEN VAN EEN RUBRIEK
Dit wordt aangeroepen zowel bij het aanmaken van een nieuwe rubriek
als het bijwerken van een bestaande rubriek.
*****************************************************************/
// De Action Hook
add_action( 'admin_post_dnh_save_rubriek', 'dnh_process_rubriek' );
// De functie
function dnh_process_rubriek() {
  // Controleer de rechten
  if ( !current_user_can( 'manage_options' ) )
  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  // Check that nonce field
  check_admin_referer( 'dnh_verify' );

  // Ophalen en valideren van de data
  $error_message = "";
  $data = array();
  if ( isset( $_POST['id'] ) )
  {
    $data['ID'] = sanitize_text_field( $_POST['id'] );
    if (!is_numeric($data['ID'])) {
      $error_message .= 'Id veld is niet mumeriek';
    }
  } else {
    $error_message .= 'Id veld is niet meegestuurd';
  }
  if ( isset( $_POST['naam'] ) )
  {
    $data['Naam'] = sanitize_text_field( $_POST['naam'] );
  } else {
    $error_message .= 'Naam veld is niet meegestuurd';
  }
  if ( isset( $_POST['omschrijving'] ) )
  {
    $data['Omschrijving'] = sanitize_text_field( $_POST['omschrijving'] );
  }

  if(strlen($error_message) > 0) {
    // Redirect met foutbericht voorbereiden
    $qvars = array( 'page' => 'dnh_rubrieken', 
      'dnh_ntc' => 'error',
      'dnh_ntm' => urlencode( $error_message )
    );
  } else {
    global $wpdb; //This is used only if making any database queries
    $updates = $wpdb->replace('DNH_RUBRIEK', $data);
    // Redirect voorbereiden
    $qvars = array( 'page' => 'dnh_rubrieken', 
      'dnh_ntc' => 'updated',
      'dnh_ntm' => urlencode( 'Rubriek is succesvol aangemaakt/bijgewerkt' ) );
  }
  wp_redirect( add_query_arg( $qvars, admin_url( 'admin.php' ) ) );
  exit;
}

/**************************************************************** 
VERWIJDEREN VAN EEN RUBRIEK, EN BIJWERKEN VAN DAARAAN GEKOPPELDE 
TRANSACTIES
Dit wordt aangeroepen als één of meer rubrieken moeten worden
verwijderd.
*****************************************************************/
// De Action Hook
add_action( 'admin_post_dnh_delete_rubrieken', 'dnh_process_delete_rubrieken' );
// De functie
function dnh_process_delete_rubrieken() {
  // Controleer de rechten
  if ( !current_user_can( 'manage_options' ) )
  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  // Check that nonce field
  check_admin_referer( 'dnh_verify' );

  // TODO nog te implementeren

  // ophalen rubrieken, keuze en eventueel nieuwe rubriek
  $error_message = "";
  // Ophalen en valideren van de data
  // Alle gemarkeerde rubrieken in een array stoppen
  $rubrieken = Array();
  if (isset($_POST['rubriek'])) {
    $value = $_POST['rubriek'];
    if (is_array($value)) {
      foreach ($value as $val) {
        $rubrieken[] = sanitize_text_field($val);
      }
    } else {
      $rubrieken[] = sanitize_text_field($value);
    }
  } else {
    $error_message .= 'Er zijn geen rubrieken meegestuurd';
  }

  foreach ($rubrieken as $rubriek) {
    if (!is_numeric($rubriek)) {
      $error_message .= 'Rubriek $rubriek is niet geldig';
    }
  }

  if ( isset( $_POST['trans_action'] ) )
  {
    $what_to_do_with_transactions = sanitize_text_field( $_POST['trans_action'] );
  } else {
    $error_message .= 'Er is niet aangegeven wat er gedaan moet worden met de transacties';
  }
  if ( isset( $_POST['nwe_rubriek'] ) )
  {
    $nwe_transactie = sanitize_text_field( $_POST['nwe_rubriek'] );
  } else {
    if ($what_to_do_with_transactions==='rubr')
      $error_message .= 'Er is geen nieuwe rubriek meegestuurd';
  }


  if(strlen($error_message) > 0) {
    // Redirect voorbereiden
    $qvars = array( 'page' => 'dnh_rubrieken', 
      'dnh_ntc' => 'error',
      'dnh_ntm' => urlencode( $error_message )
    );
  } else {
    global $wpdb; //This is used only if making any database queries
    // TODO aanpassen van de transacties, mbv SQL

    // verwijderen rubrieken
    foreach ($rubrieken as $rubriek) {
      $wpdb->delete( 'DNH_RUBRIEK', Array( 'ID' => $rubriek ) );
    }
    // Redirect voorbereiden
    $qvars = array( 'page' => 'dnh_rubrieken', 
      'dnh_ntc' => 'updated',
      'dnh_ntm' => urlencode( 'Rubriek(en) succesvol verwijderd' ) 
     );
  }
  //echo add_query_arg( $qvars, admin_url( 'admin.php' ));
  wp_redirect( add_query_arg( $qvars, admin_url( 'admin.php' ) ) );
  exit;
}

?>