<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: schepen/process.php
Doel  : Alles voor het verwerken van wijzigingen van schepen
Auteur: BugSlayer
*******************************************************************************************************/

/**************************************************************** 
TOEVOEGEN/BIJWERKEN VAN EEN schip
Dit wordt aangeroepen zowel bij het aanmaken van een nieuwe schip
als het bijwerken van een bestaande schip.
*****************************************************************/
// De Action Hook
add_action( 'admin_post_dnh_save_schip', 'dnh_process_schip' );
// De functie
function dnh_process_schip() {
  // Controleer de rechten
  if ( !current_user_can( 'manage_options' ) )
  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  // Check that nonce field
  check_admin_referer( 'dnh_verify' );

  // Ophalen en vaschiperen van de data
  $error_message = "";
  $data = array();
  if ( isset( $_POST['Naam'] ) )
  {
    $data['Naam'] = sanitize_text_field( $_POST['Naam'] );
  } else {
    $error_message .= 'naam veld is niet meegestuurd';
  }
  if ( isset( $_POST['Lengte'] ) )
  {
    $data['Lengte'] = sanitize_text_field( $_POST['Lengte'] );
	} else {
    $error_message .= 'Lengte veld is niet meegestuurd';
  }
	if ( isset( $_POST['lidID'] ) )
  {
    $data['lidID'] = sanitize_text_field( $_POST['lidID'] );
  }
  

  if(strlen($error_message) > 0) {
    // Redirect met foutbericht voorbereiden
    $qvars = array( 'page' => 'dnh_schepen', 
      'dnh_ntc' => 'error',
      'dnh_ntm' => urlencode( $error_message )
    );
  } else {
    global $wpdb; //This is used only if making any database queries
    $updates = $wpdb->replace('dnh_schepen', $data);
    // Redirect voorbereiden
    $qvars = array( 'page' => 'dnh_schepen', 
      'dnh_ntc' => 'updated',
      'dnh_ntm' => urlencode( 'schip is succesvol aangemaakt/bijgewerkt' ) );
  }
  wp_redirect( add_query_arg( $qvars, admin_url( 'admin.php' ) ) );
  exit;
}

/**************************************************************** 
VERWIJDEREN VAN EEN schip, EN BIJWERKEN VAN DAARAAN GEKOPPELDE 
TRANSACTIES
Dit wordt aangeroepen als één of meer schepen moeten worden
verwijderd.
*****************************************************************/
// De Action Hook
add_action( 'admin_post_dnh_delete_schepen', 'dnh_process_delete_schepen' );
// De functie
function dnh_process_delete_schepen() {
  // Controleer de rechten
  if ( !current_user_can( 'manage_options' ) )
  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  // Check that nonce field
  check_admin_referer( 'dnh_verify' );

  // TODO nog te implementeren

  // ophalen schepen, keuze en eventueel nieuwe schip
  $error_message = "";
  // Ophalen en vaschiperen van de data
  // Alle gemarkeerde schepen in een array stoppen
  $schepen = Array();
  if (isset($_POST['schip'])) {
    $value = $_POST['schip'];
    if (is_array($value)) {
      foreach ($value as $val) {
        $schepen[] = sanitize_text_field($val);
      }
    } else {
      $schepen[] = sanitize_text_field($value);
    }
  } else {
    $error_message .= 'Er zijn geen schepen meegestuurd';
  }

  foreach ($schepen as $schip) {
    if (!is_numeric($schip)) {
      $error_message .= 'schip $schip is niet geldig';
    }
  }

  if ( isset( $_POST['trans_action'] ) )
  {
    $what_to_do_with_transactions = sanitize_text_field( $_POST['trans_action'] );
  } else {
    $error_message .= 'Er is niet aangegeven wat er gedaan moet worden met de transacties';
  }
  if ( isset( $_POST['nwe_schip'] ) )
  {
    $nwe_transactie = sanitize_text_field( $_POST['nwe_schip'] );
  } else {
    if ($what_to_do_with_transactions==='rubr')
      $error_message .= 'Er is geen nieuwe schip meegestuurd';
  }


  if(strlen($error_message) > 0) {
    // Redirect voorbereiden
    $qvars = array( 'page' => 'dnh_schepen', 
      'dnh_ntc' => 'error',
      'dnh_ntm' => urlencode( $error_message )
    );
  } else {
    global $wpdb; //This is used only if making any database queries
    // TODO aanpassen van de transacties, mbv SQL

    // verwijderen schepen
    foreach ($schepen as $schip) {
      $wpdb->delete( 'DNH_schip', Array( 'ID' => $schip ) );
    }
    // Redirect voorbereiden
    $qvars = array( 'page' => 'dnh_schepen', 
      'dnh_ntc' => 'updated',
      'dnh_ntm' => urlencode( 'schip(en) succesvol verwijderd' ) 
     );
  }
  //echo add_query_arg( $qvars, admin_url( 'admin.php' ));
  wp_redirect( add_query_arg( $qvars, admin_url( 'admin.php' ) ) );
  exit;
}

?>