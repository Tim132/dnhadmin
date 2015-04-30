<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: pdf/process.php
Doel  : Alles voor het verwerken van wijzigingen van pdf
Auteur: BugSlayer
*******************************************************************************************************/

/**************************************************************** 
TOEVOEGEN/BIJWERKEN VAN EEN pdf
Dit wordt aangeroepen zowel bij het aanmaken van een nieuwe pdf
als het bijwerken van een bestaande pdf.
*****************************************************************/
// De Action Hook
add_action( 'admin_post_dnh_save_pdf', 'dnh_process_pdf' );
// De functie
function dnh_process_pdf() {
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
    $qvars = array( 'page' => 'dnh_pdf', 
      'dnh_ntc' => 'error',
      'dnh_ntm' => urlencode( $error_message )
    );
  } else {
    global $wpdb; //This is used only if making any database queries
    $updates = $wpdb->replace('DNH_pdf', $data);
    // Redirect voorbereiden
    $qvars = array( 'page' => 'dnh_pdf', 
      'dnh_ntc' => 'updated',
      'dnh_ntm' => urlencode( 'pdf is succesvol aangemaakt/bijgewerkt' ) );
  }
  wp_redirect( add_query_arg( $qvars, admin_url( 'admin.php' ) ) );
  exit;
}

/**************************************************************** 
VERWIJDEREN VAN EEN pdf, EN BIJWERKEN VAN DAARAAN GEKOPPELDE 
TRANSACTIES
Dit wordt aangeroepen als één of meer pdf moeten worden
verwijderd.
*****************************************************************/
// De Action Hook
add_action( 'admin_post_dnh_delete_pdf', 'dnh_process_delete_pdf' );
// De functie
function dnh_process_delete_pdf() {
  // Controleer de rechten
  if ( !current_user_can( 'manage_options' ) )
  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  // Check that nonce field
  check_admin_referer( 'dnh_verify' );

  // TODO nog te implementeren

  // ophalen pdf, keuze en eventueel nieuwe pdf
  $error_message = "";
  // Ophalen en valideren van de data
  // Alle gemarkeerde pdf in een array stoppen
  $pdf = Array();
  if (isset($_POST['pdf'])) {
    $value = $_POST['pdf'];
    if (is_array($value)) {
      foreach ($value as $val) {
        $pdf[] = sanitize_text_field($val);
      }
    } else {
      $pdf[] = sanitize_text_field($value);
    }
  } else {
    $error_message .= 'Er zijn geen pdf meegestuurd';
  }

  foreach ($pdf as $pdf) {
    if (!is_numeric($pdf)) {
      $error_message .= 'pdf $pdf is niet geldig';
    }
  }

  if ( isset( $_POST['trans_action'] ) )
  {
    $what_to_do_with_transactions = sanitize_text_field( $_POST['trans_action'] );
  } else {
    $error_message .= 'Er is niet aangegeven wat er gedaan moet worden met de transacties';
  }
  if ( isset( $_POST['nwe_pdf'] ) )
  {
    $nwe_transactie = sanitize_text_field( $_POST['nwe_pdf'] );
  } else {
    if ($what_to_do_with_transactions==='rubr')
      $error_message .= 'Er is geen nieuwe pdf meegestuurd';
  }


  if(strlen($error_message) > 0) {
    // Redirect voorbereiden
    $qvars = array( 'page' => 'dnh_pdf', 
      'dnh_ntc' => 'error',
      'dnh_ntm' => urlencode( $error_message )
    );
  } else {
    global $wpdb; //This is used only if making any database queries
    // TODO aanpassen van de transacties, mbv SQL

    // verwijderen pdf
    foreach ($pdf as $pdf) {
      $wpdb->delete( 'DNH_pdf', Array( 'ID' => $pdf ) );
    }
    // Redirect voorbereiden
    $qvars = array( 'page' => 'dnh_pdf', 
      'dnh_ntc' => 'updated',
      'dnh_ntm' => urlencode( 'pdf(en) succesvol verwijderd' ) 
     );
  }
  //echo add_query_arg( $qvars, admin_url( 'admin.php' ));
  wp_redirect( add_query_arg( $qvars, admin_url( 'admin.php' ) ) );
  exit;
}

?>