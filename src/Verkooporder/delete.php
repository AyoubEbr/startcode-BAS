<?php
// auteur: Ayoub
// functie: delete VerkoopOrder

// Autoloader classes via composer
require '../../vendor/autoload.php';
use Bas\classes\VerkoopOrder;

// Controleer of verkOrdId is ingesteld via GET
if (isset($_GET['verkOrdId'])) {
    try {
        // Maak een nieuwe VerkoopOrder instantie aan
        $verkooporder = new VerkoopOrder();
        
        // Haal verkOrdId op uit GET parameters
        $verkOrdId = $_GET['verkOrdId'];
        
        // Probeer de verkooporder te verwijderen
        $success = $verkooporder->deleteVerkoopOrder($verkOrdId);

        // Controleer of de verwijdering succesvol was
        if ($success) {
            echo '<script>alert("Verkooporder succesvol verwijderd."); location.replace("read.php");</script>';
        } else {
            echo '<script>alert("Fout bij het verwijderen van de verkooporder."); location.replace("read.php");</script>';
        }
    } catch (Exception $e) {
        // Vang alle mogelijke uitzonderingen op en toon een foutmelding
        echo '<script>alert("Er is een fout opgetreden: ' . $e->getMessage() . '"); location.replace("read.php");</script>';
    }
} else {
    // Als verkOrdId niet is opgegeven, geef een melding en redirect naar read.php
    echo '<script>alert("Geen verkOrdId opgegeven."); location.replace("read.php");</script>';
}
?>
