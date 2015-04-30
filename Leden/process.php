<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: leden/process.php
Doel  : Alles voor het verwerken van wijzigingen van leden
Auteur: BugSlayer
*******************************************************************************************************/

/**************************************************************** 
TOEVOEGEN/BIJWERKEN VAN EEN lid
Dit wordt aangeroepen zowel bij het aanmaken van een nieuw lid
als het bijwerken van een bestaand lid.
*****************************************************************/
// De Action Hook
add_action( 'admin_post_dnh_save_lid', 'dnh_process_lid' );
// De functie
function dnh_process_lid() {
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

  if ( !isset( $_POST['naam'] ) ) {
    $error_message[] = 'naam veld is niet meegestuurd';
  } 
    if ( !isset( $_POST['adres'] ) ) {
    $error_message[] = 'adres veld is niet meegestuurd';
  } 
  

  $qvars = array( 'page' => 'dnh_leden');
  if(count($error_message) > 0) {

    $qvars['dnh_ntc'] = 'error';
    $qvars['dnh_ntm'] = urlencode( join( ', ', $error_message ) );
  } else {
    global $wpdb; //This is used only if making any database queries
    $updates = $wpdb->replace('DNH_lid', $data);
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
VERWIJDEREN VAN EEN lid
Dit wordt aangeroepen als één of meer leden moeten worden
verwijderd.
*****************************************************************/
// De Action Hook
add_action( 'admin_post_dnh_delete_leden', 'dnh_process_delete_leden' );
// De functie
function dnh_process_delete_leden() {
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

  $qvars = array( 'page' => 'dnh_leden');
  if(count($error_message) > 0) {
    $qvars['dnh_ntc'] = 'error';
    $qvars['dnh_ntm'] = urlencode( join( ', ', $error_message ) );
  } else {
    global $wpdb; //This is used only if making any database queries
        // verwijderen leden
    $updates = 0;
    foreach ($jaren as $jaar) {
      $update = $wpdb->delete( 'DNH_lid', Array( 'Jaar' => $jaar ) );
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