<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: tarieven/main.php
Doel  : Hoofd bestand voor de tarieven, combineert alle functionaliteit voor het tonen en bewerken van 
        tarieven
Auteur: BugSlayer
*******************************************************************************************************/
// Include het script dat wijzigingen op de database verwerkt.
require_once('process.php');

/**
 * Aangeroepen tijdens de 'admin_menu' action
 */
function dnh_tarieven_on_admin_menu() {
   /* Beschrijving van de parameters van de function add_submenu_page:
    * 1: De slug van het menu waaraan dit submenu aan gekoppeld moet zijn. Null als page niet in een menu komt, maar op een 
    *    andere manier kan worden opgeroepen.
    * 2: geen idee
    * 3: Titel van het menu
    * 4: Rechten om het menu zichtbaar te maken
    * 5: slug van deze page
    * 6: PHP functie die wordt aangeroepen als de gebruiker de page oproept.
    */
	add_submenu_page( 'dnh_menu', 'Beheren Tarieven'  , 'Tarieven'   , 'manage_options', 'dnh_tarieven'       , 'dnh_tarieven_list'   );
	add_submenu_page( null      , 'Nieuw Tarief'      , 'Nieuw'      , 'manage_options', 'dnh_tarieven_create', 'dnh_tarieven_create' );
	add_submenu_page( null      , 'Tarief Bewerken'   , 'Bewerken'   , 'manage_options', 'dnh_tarieven_edit'  , 'dnh_tarieven_edit'   );
  add_submenu_page( null      , 'Tarief Verwijderen', 'Verwijderen', 'manage_options', 'dnh_tarieven_delete', 'dnh_tarieven_delete' );

}

/**
 * Renderen van de table.
 */
function dnh_tarieven_list() {
  // Beperk toegang
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  if(!class_exists('DNHTarieven_List_Table')){
      require_once( 'tarieven-list-table-class.php' );
  }
  //Create an instance of our package class...
	$myListTable = new DNHTarieven_List_Table();
	//Fetch, prepare, sort, and filter our data...
	$myListTable->prepare_items();
	include( 'tarieven-list.inc.php' );	
}

function dnh_tarieven_create() {
  // Beperk toegang
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
	include( 'tarieven-create.inc.php' );
}

function dnh_tarieven_edit() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   if ( !isset( $_GET['tarief'] ) )  {
      wp_die( __( 'You do not sent sufficient data to use this page.' ) );
   }
   
   $tarief = sanitize_text_field( $_GET['tarief'] );
   global $wpdb;
   $item = $wpdb->get_row("SELECT * FROM DNH_TARIEF WHERE Jaar = $tarief");

	include( 'tarieven-edit.inc.php' );
}

function dnh_tarieven_delete() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   include( 'tarieven-delete.inc.php' );
}

?>