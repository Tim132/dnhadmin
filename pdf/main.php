<?php
/*******************************************************************************************************
Plugin: DNHAdmin
Script: pdf/main.php
Doel  : Hoofd bestand voor de pdf, combineert alle functionaliteit voor het tonen en bewerken van 
        pdf
Auteur: BugSlayer
*******************************************************************************************************/

// Include het script dat wijzigingen op de database verwerkt.
require_once('process.php');

/**
 * Aangeroepen tijdens de 'admin_menu' action
 */
function dnh_pdf_on_admin_menu() {
   /* Beschrijving van de parameters van de function add_submenu_page:
    * 1: De slug van het menu waaraan dit submenu aan gekoppeld moet zijn. Null als page niet in een menu komt, maar op een 
    *    andere manier kan worden opgeroepen.
    * 2: geen idee
    * 3: Titel van het menu
    * 4: Rechten om het menu zichtbaar te maken
    * 5: slug van deze page
    * 6: PHP functie die wordt aangeroepen als de gebruiker de page oproept.
    */
	add_submenu_page( 'dnh_menu', 'Pdf'  , 'pdf'  , 'manage_options', 'dnh_pdf'       , 'dnh_pdf_list'   );
	add_submenu_page( null      , 'Nieuwe pdf'     , 'Nieuw'      , 'manage_options', 'dnh_pdf_create', 'dnh_pdf_create' );
	add_submenu_page( null      , 'pdf Bewerken'   , 'Bewerken'   , 'manage_options', 'dnh_pdf_edit'  , 'dnh_pdf_edit'   );
  add_submenu_page( null      , 'pdf Verwijderen', 'Verwijderen', 'manage_options', 'dnh_pdf_delete', 'dnh_pdf_delete' );

}

/**
 * Renderen van de table.
 */
function dnh_pdf_list() {
  // Beperk toegang
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  
  if(!class_exists('DNHpdf_List_Table')){
      require_once( 'pdf-list-table-class.php' );
  }
  //Create an instance of our package class...
	$myListTable = new DNHpdf_List_Table();
	//Fetch, prepare, sort, and filter our data...
	$myListTable->prepare_items();
	include( 'pdf-list.inc.php' );	
}

function dnh_pdf_create() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
	include( 'pdf-create.inc.php' );
}

function dnh_pdf_edit() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   if ( !isset( $_GET['pdf'] ) )  {
      wp_die( __( 'You do not sent sufficient data to use this page.' ) );
   }
   
   $id = sanitize_text_field( $_GET['pdf'] );
   global $wpdb;
   $item = $wpdb->get_row("SELECT * FROM DNH_pdf WHERE ID = $id");

	include( 'pdf-edit.inc.php' );
}

function dnh_pdf_delete() {
   // Beperk toegang
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   include( 'pdf-delete.inc.php' );
}

?>