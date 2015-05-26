<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: leden/main.php
Doel  : Hoofd bestand voor de leden, combineert alle functionaliteit voor het tonen en bewerken van 
        leden
Auteur: BugSlayer
*******************************************************************************************************/

// Include het script dat wijzigingen op de database verwerkt.
require_once('process.php');

/**
 * Aangeroepen tijdens de 'admin_menu' action
 */
function dnh_leden_on_admin_menu() {
   /* Beschrijving van de parameters van de function add_submenu_page:
    * 1: De slug van het menu waaraan dit submenu aan gekoppeld moet zijn. Null als page niet in een menu komt, maar op een 
    *    andere manier kan worden opgeroepen.
    * 2: geen idee
    * 3: Titel van het menu
    * 4: Rechten om het menu zichtbaar te maken
    * 5: slug van deze page
    * 6: PHP functie die wordt aangeroepen als de gebruiker de page oproept.
    */
	add_submenu_page( 'dnh_menu', 'Beheren leden'  , 'leden'      , 'manage_options', 'dnh_leden'        , 'dnh_leden_list'   );
	add_submenu_page( null      , 'Nieuwe lid'     , 'Nieuw'      , 'manage_options', 'dnh_leden_create' , 'dnh_leden_create' );
	add_submenu_page( null      , 'lid Bewerken'   , 'Bewerken'   , 'manage_options', 'dnh_leden_edit'   , 'dnh_leden_edit'   );
    add_submenu_page( null      , 'lid Verwijderen', 'Verwijderen', 'manage_options', 'dnh_leden_delete' , 'dnh_leden_delete' );

}

/**
 * Renderen van de table.
 */
function dnh_leden_list() {
  // Beperk toegang
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  
  if(!class_exists('DNHleden_List_Table')){
      require_once( 'leden-list-table-class.php' );
  }
  //Create an instance of our package class...
	$myListTable = new DNHleden_List_Table();
	//Fetch, prepare, sort, and filter our data...
	$myListTable->prepare_items();
	include( 'leden-list.inc.php' );	
}

function dnh_leden_create() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
	include( 'leden-create.inc.php' );
}

function dnh_leden_edit() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   if ( !isset( $_GET['lid'] ) )  {
      wp_die( __( 'You do not sent sufficient data to use this page.' ) );
   }
   
   $id = sanitize_text_field( $_GET['lid'] );
   global $wpdb;
   $item = $wpdb->get_row("SELECT * FROM lid WHERE LidID = $id");

	include( 'leden-edit.inc.php' );
}

function dnh_leden_delete() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   include( 'leden-delete.inc.php' );
}

?>