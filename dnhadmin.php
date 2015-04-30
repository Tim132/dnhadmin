<?php
/*
Plugin Name: DNHAdmin common
Description: Gezamelijke functionaliteit voor de Financiele adminstratie van DNH
Version: 0.0.1
Author: Bugslayer
Author URI: http://www.hzeeland.nl/~waar0003
License: GPLv2 or later
*/

/*** Toevoegen van alle scripts die onderdeel zijn van deze plugin ***/
require_once( 'rubrieken/main.php' );
require_once( 'tarieven/main.php'  );
require_once( 'leden/main.php'     );
require_once( 'pdf/main.php'       );

/*** Basisinstellingen van de plugin. Options zijn een soort constanten in Wordpress ***/
// TODO uitzoeken hoe deze options in een instellingenpagina van de plugin kunnen worden bewerkt
add_option('current_year', 2015);

/*** Configureren van het admin-menu voor deze plugin ***/
add_action( 'admin_menu', 'dnh_on_admin_menu');

function dnh_on_admin_menu() {
	add_menu_page( 'DNHAdmin instellingen', // Wat in de tab van je browser komt te staan
		           'DNHAdmin',              // Titel van het menu-item
		           'manage_options',        // Rechten
		           'dnh_menu',              // De slug (unieke naam binnen Wordpress om dit menu te identificeren)
		           'dnh_main',              // Naam van de php functie die wordt aangereoepen als gebruiker op de menu-link klikt
		           '',                      // ?
		           3                        // Plaats tov de andere menu-items
		           );

	// Hier alle on_admin_menu functies van de verschillende sub-onderdelen aanroepen
	dnh_rubrieken_on_admin_menu(); // Zelf bedacht. PHP functie van het sub-onderdeel dat menu-items aan het menu kan toevoegen.
	dnh_tarieven_on_admin_menu();  // Zelf bedacht. PHP functie van het sub-onderdeel dat menu-items aan het menu kan toevoegen.
	dnh_leden_on_admin_menu();	   // Zelf bedacht. PHP functie van het sub-onderdeel dat menu-items aan het menu kan toevoegen.
	dnh_pdf_on_admin_menu();
}

/************** ADMIN NOTICES *****************************
 * Laten zien van medlingen bovenaan het scherm na bij
 * voorbeeld het bijwerken van de gegevens uit de database.
 *
 * De process-functies kunnen twee query var toevoegen aan de URL onder de 
 * namen 'dnh_ntc' en 'dnh_ntm'. De waarden zijn:
 * dnh_ntc : naam van de class voor div van het notice-bericht (opties: updated, error of update-nag)
 * dnh_ntm : de tekst van het bericht. LET OP: bij het 'versturen' van het bericht niet vergeten de tekst te url-encoden
 *                                             dit gaat mbv de php-functie urlencode( $var );
 */

// Als eerste een hook toevoegen. Dit zorgt ervoor dat Wordpress op het juiste moment de functie dnh_admin_notice aanroept
add_action( 'admin_notices', 'dnh_admin_notice' );

// De functiondie de hook aanroept, de query vars controleert en de notice toevoegt.
function dnh_admin_notice() {	
  if (isset($_GET['dnh_ntc'])) {

  	$class = $_GET['dnh_ntc'];
  	$message = "< UNKNOWN MESSAGE >";
  	
  	if (isset($_GET['dnh_ntm'])) {
  		$message = $_GET['dnh_ntm'];
  	}
    
    echo "<div class='$class'><p>$message</p></div>";
  
  }

} 
//******************* Einde ADMIN NOTICES deel ************8


/*** Deze functie word aangeroepen als de admin gebruiker op de hoofdmenu-link klikt. 
     Hier kunnen dus basis instellingen worden gedaan                                  ***/
function dnh_main() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	// Includen van een script wat als een soort template dient. Houdt de code hier netjes
	include( 'dnhadmin.inc.php' );
}


?>