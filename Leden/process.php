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
  $error_message = "";
  $data = array();
  if ( isset( $_POST['Naam'] ) )
  {
    $data['Naam'] = sanitize_text_field( $_POST['Naam'] );
  } else {
    $error_message .= 'naam veld is niet meegestuurd';
  }
  if ( isset( $_POST['Adres'] ) )
  {
    $data['Adres'] = sanitize_text_field( $_POST['Adres'] );
	} else {
    $error_message .= 'Adres veld is niet meegestuurd';
  }
  
  if(strlen($error_message) > 0) {
    // Redirect met foutbericht voorbereiden
    $qvars = array( 'page' => 'dnh_leden', 
      'dnh_ntc' => 'error',
      'dnh_ntm' => urlencode( $error_message )
    );
  } else {
    global $wpdb; //This is used only if making any database queries
    $updates = $wpdb->replace('lid', $data);
    // Redirect voorbereiden
    $qvars = array( 'page' => 'dnh_leden', 
      'dnh_ntc' => 'updated',
      'dnh_ntm' => urlencode( 'lid is succesvol aangemaakt/bijgewerkt' ) );
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
  $IDs = Array();
  if (isset($_POST['user_ID'])) {
    $value = $_POST['user_ID'];
    if (is_array($value)) {
      foreach ($value as $val) {
        $IDs[] = sanitize_text_field($val);
      }
    } else {
      $IDs[] = sanitize_text_field($value);
    }
  } else {
    $error_message[] = 'Er is geen ID meegestuurd';
  }
  foreach ($jaren as $jaar) {
    if (!is_numeric($jaar)) {
      $error_message[] = 'ID $user_ID is niet geldig';
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
    foreach ($IDs as $user_ID) {
      $update = $wpdb->delete( 'DNH_lid', Array( 'user_ID' => $user_ID ) );
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
