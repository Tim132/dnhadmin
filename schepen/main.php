<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: schepen/main.php
Doel  : Hoofd bestand voor de schepen, combineert alle functionaliteit voor het tonen en bewerken van 
        schepen
Auteur: BugSlayer
*******************************************************************************************************/

// Include het script dat wijzigingen op de database verwerkt.
require_once('process.php');

/**
 * Aangeroepen tijdens de 'admin_menu' action
 */
function dnh_schepen_on_admin_menu() {
   /* Beschrijving van de parameters van de function add_submenu_page:
    * 1: De slug van het menu waaraan dit submenu aan gekoppeld moet zijn. Null als page niet in een menu komt, maar op een 
    *    andere manier kan worden opgeroepen.
    * 2: geen idee
    * 3: Titel van het menu
    * 4: Rechten om het menu zichtbaar te maken
    * 5: slug van deze page
    * 6: PHP functie die wordt aangeroepen als de gebruiker de page oproept.
    */
	add_submenu_page( 'dnh_menu', 'Beheren schepen'  , 'schepen'  , 'manage_options', 'dnh_schepen'       , 'dnh_schepen_list'   );
	add_submenu_page( null      , 'Nieuwe schip'     , 'Nieuw'      , 'manage_options', 'dnh_schepen_create', 'dnh_schepen_create' );
	add_submenu_page( null      , 'Testen schip'     , 'Test'      , 'manage_options', 'dnh_schepen_schepen', 'dnh_schepen_schepen' );
	add_submenu_page( null      , 'schip Bewerken'   , 'Bewerken'   , 'manage_options', 'dnh_schepen_edit'  , 'dnh_schepen_edit'   );
    add_submenu_page( null      , 'schip Verwijderen', 'Verwijderen', 'manage_options', 'dnh_schepen_delete', 'dnh_schepen_delete' );

}

/**
 * Renderen van de table.
 */
function dnh_schepen_list() {
  // Beperk toegang
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  
  if(!class_exists('DNHschepen_List_Table')){
      require_once( 'schepen-list-table-class.php' );
  }
  //Create an instance of our package class...
	$myListTable = new DNHschepen_List_Table();
	//Fetch, prepare, sort, and filter our data...
	$myListTable->prepare_items();
	include( 'schepen-list.inc.php' );	
}

function dnh_schepen_create() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
	include( 'schepen-create.inc.php' );
}

function dnh_schepen_edit() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   if ( !isset( $_GET['schip'] ) )  {
      wp_die( __( 'You do not sent sufficient data to use this page.' ) );
   }
   
   $id = sanitize_text_field( $_GET['schip'] );
   global $wpdb;
   $item = $wpdb->get_row("SELECT * FROM DNH_schip WHERE ID = $id");

	include( 'schepen-edit.inc.php' );
}

function dnh_schepen_delete() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   include( 'schepen-delete.inc.php' );
}

function dnh_schepen_schepen() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   include( 'schepen-schepen.inc.php');
}

?>