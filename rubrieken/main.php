<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: rubrieken/main.php
Doel  : Hoofd bestand voor de rubrieken, combineert alle functionaliteit voor het tonen en bewerken van 
        rubrieken
Auteur: BugSlayer
*******************************************************************************************************/

// Include het script dat wijzigingen op de database verwerkt.
require_once('process.php');

/**
 * Aangeroepen tijdens de 'admin_menu' action
 */
function dnh_rubrieken_on_admin_menu() {
   /* Beschrijving van de parameters van de function add_submenu_page:
    * 1: De slug van het menu waaraan dit submenu aan gekoppeld moet zijn. Null als page niet in een menu komt, maar op een 
    *    andere manier kan worden opgeroepen.
    * 2: geen idee
    * 3: Titel van het menu
    * 4: Rechten om het menu zichtbaar te maken
    * 5: slug van deze page
    * 6: PHP functie die wordt aangeroepen als de gebruiker de page oproept.
    */
	add_submenu_page( 'dnh_menu', 'Beheren Rubrieken'  , 'Rubrieken'  , 'manage_options', 'dnh_rubrieken'       , 'dnh_rubrieken_list'   );
	add_submenu_page( null      , 'Nieuwe Rubriek'     , 'Nieuw'      , 'manage_options', 'dnh_rubrieken_create', 'dnh_rubrieken_create' );
	add_submenu_page( null      , 'Rubriek Bewerken'   , 'Bewerken'   , 'manage_options', 'dnh_rubrieken_edit'  , 'dnh_rubrieken_edit'   );
  add_submenu_page( null      , 'Rubriek Verwijderen', 'Verwijderen', 'manage_options', 'dnh_rubrieken_delete', 'dnh_rubrieken_delete' );

}

/**
 * Renderen van de table.
 */
function dnh_rubrieken_list() {
  // Beperk toegang
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  
  if(!class_exists('DNHRubrieken_List_Table')){
      require_once( 'rubrieken-list-table-class.php' );
  }
  //Create an instance of our package class...
	$myListTable = new DNHRubrieken_List_Table();
	//Fetch, prepare, sort, and filter our data...
	$myListTable->prepare_items();
	include( 'rubrieken-list.inc.php' );	
}

function dnh_rubrieken_create() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
	include( 'rubrieken-create.inc.php' );
}

function dnh_rubrieken_edit() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   if ( !isset( $_GET['rubriek'] ) )  {
      wp_die( __( 'You do not sent sufficient data to use this page.' ) );
   }
   
   $id = sanitize_text_field( $_GET['rubriek'] );
   global $wpdb;
   $item = $wpdb->get_row("SELECT * FROM DNH_RUBRIEK WHERE ID = $id");

	include( 'rubrieken-edit.inc.php' );
}

function dnh_rubrieken_delete() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   include( 'rubrieken-delete.inc.php' );
}

?>