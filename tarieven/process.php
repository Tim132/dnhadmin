<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: tarieven/process.php
Doel  : Alles voor het verwerken van wijzigingen van Tarieven
Auteur: BugSlayer
*******************************************************************************************************/

/**************************************************************** 
TOEVOEGEN/BIJWERKEN VAN EEN TARIEF
Dit wordt aangeroepen zowel bij het aanmaken van een nieuw tarief
als het bijwerken van een bestaand tarief.
*****************************************************************/
// De Action Hook
add_action( 'admin_post_dnh_save_tarief', 'dnh_process_tarief' );
// De functie
function dnh_process_tarief() {
  // Controleer de rechten
  if ( !current_user_can( 'manage_options' ) )
  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  // Check that nonce field
  check_admin_referer( 'dnh_verify' );

  // Ophalen en valideren van de data
  $error_message = Array();
  $data = array();

  if ( !isset( $_POST['jaar'] ) ) {
    $error_message[] = 'Jaar veld is niet meegestuurd';
  } else {
    $data['Jaar'] = sanitize_text_field( $_POST['jaar'] );
    if (!is_numeric($data['Jaar'])) {
      $error_message[] = 'Jaar veld is niet mumeriek';
    }
  }

  if ( !isset( $_POST['contributie_leden'] ) ) {
    $error_message[] = 'Contributie_leden veld is niet meegestuurd';
  } else {
    $data['Contributie_leden'] = sanitize_text_field( $_POST['contributie_leden'] );
    if (!is_numeric($data['Contributie_leden'])) {
      $error_message[] = 'Contributie_leden veld is niet mumeriek';
    }
  }

  if ( !isset( $_POST['energietoeslag_leden'] ) ) {
    $error_message[] = 'Energietoeslag_leden veld is niet meegestuurd';
  } else {
    $data['Energietoeslag_leden'] = sanitize_text_field( $_POST['energietoeslag_leden'] );
    if (!is_numeric($data['Energietoeslag_leden'])) {
      $error_message[] = 'Energietoeslag_leden veld is niet mumeriek';
    }
  }

  if ( !isset( $_POST['liggeld_leden'] ) ) {
    $error_message[] = 'Liggeld_leden veld is niet meegestuurd';
  } else {
    $data['Liggeld_leden'] = sanitize_text_field( $_POST['liggeld_leden'] );
    if (!is_numeric($data['Liggeld_leden'])) {
      $error_message[] = 'Liggeld_leden veld is niet mumeriek';
    }
  }

  if ( !isset( $_POST['liggeld_passanten'] ) ) {
    $error_message[] = 'Liggeld_passanten veld is niet meegestuurd';
  } else {
    $data['Liggeld_passanten'] = sanitize_text_field( $_POST['liggeld_passanten'] );
    if (!is_numeric($data['Liggeld_passanten'])) {
      $error_message[] = 'Liggeld_passanten veld is niet mumeriek';
    }
  }

  $qvars = array( 'page' => 'dnh_tarieven');
  if(count($error_message) > 0) {
    if ( isset( $data['Jaar'] ) ) {
      // Probeer weer te redirecten naar de edit-pagina
      $qvars['page'] = 'dnh_tarieven_edit';
      $qvars['tarief'] = $data['Jaar'];
    }
    $qvars['dnh_ntc'] = 'error';
    $qvars['dnh_ntm'] = urlencode( join( ', ', $error_message ) );
  } else {
    global $wpdb; //This is used only if making any database queries
    $updates = $wpdb->replace('DNH_TARIEF', $data);
    if ($updates === FALSE) {
      $qvars['dnh_ntc'] = 'error';
      $qvars['dnh_ntm'] = urlencode( __( 'Could not execute query: ' ) . $wpdb->last_error );
    }
    // Redirect voorbereiden
    $qvars['dnh_ntc'] = 'updated';
    $qvars['dnh_ntm'] = urlencode( "Handeling succesvol. $updates rijen bijgewerkt/aangemaakt." );
  }
  wp_redirect( add_query_arg( $qvars, admin_url( 'admin.php' ) ) );
  exit;
}

/**************************************************************** 
VERWIJDEREN VAN EEN TARIEF
Dit wordt aangeroepen als één of meer tarieven moeten worden
verwijderd.
*****************************************************************/
// De Action Hook
add_action( 'admin_post_dnh_delete_tarieven', 'dnh_process_delete_tarieven' );
// De functie
function dnh_process_delete_tarieven() {
  // Controleer de rechten
  if ( !current_user_can( 'manage_options' ) )
  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  // Check that nonce field
  check_admin_referer( 'dnh_verify' );

  $error_message = Array();

  // Ophalen en valideren van de data
  // Alle gemarkeerde jaren in een array stoppen
  $jaren = Array();
  if (isset($_POST['jaar'])) {
    $value = $_POST['jaar'];
    if (is_array($value)) {
      foreach ($value as $val) {
        $jaren[] = sanitize_text_field($val);
      }
    } else {
      $jaren[] = sanitize_text_field($value);
    }
  } else {
    $error_message[] = 'Er zijn geen jaren meegestuurd';
  }
  foreach ($jaren as $jaar) {
    if (!is_numeric($jaar)) {
      $error_message[] = 'Rubriek $jaar is niet geldig';
    }
  }

  $qvars = array( 'page' => 'dnh_tarieven');
  if(count($error_message) > 0) {
    if ( isset( $data['Jaar'] ) ) {
      // Probeer weer te redirecten naar de edit-pagina
      $qvars['page'] = 'dnh_tarieven_edit';
      $qvars['tarief'] = $data['Jaar'];
    }
    $qvars['dnh_ntc'] = 'error';
    $qvars['dnh_ntm'] = urlencode( join( ', ', $error_message ) );
  } else {
    global $wpdb; //This is used only if making any database queries
        // verwijderen tarieven
    $updates = 0;
    foreach ($jaren as $jaar) {
      $update = $wpdb->delete( 'DNH_TARIEF', Array( 'Jaar' => $jaar ) );
      if ($update === FALSE) {
        $qvars['dnh_ntc'] = 'error';
        $qvars['dnh_ntm'] = urlencode( __( 'Could not execute query: ' ) . $wpdb->last_error );
        wp_redirect( add_query_arg( $qvars, admin_url( 'admin.php' ) ) );
        exit;
      }
      $updates += $update;
    }

   // Redirect voorbereiden
    $qvars['dnh_ntc'] = 'updated';
    $qvars['dnh_ntm'] = urlencode( "Handeling succesvol. $updates rijen aangedaan." );
  }

  wp_redirect( add_query_arg( $qvars, admin_url( 'admin.php' ) ) );
  exit;
}
