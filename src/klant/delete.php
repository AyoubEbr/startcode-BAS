<?php
// Auteur: Ayoub
// Functie: delete Klant

// Autoloader classes via Composer
require '../../vendor/autoload.php';
use Bas\classes\Klant;

if (isset($_GET['klantId'])) {
    $klant = new Klant();
    $klantId = $_GET['klantId'];
    $success = $klant->deleteKlant($klantId);

    if ($success) {
        echo '<script>alert("Klant succesvol verwijderd."); location.replace("read.php");</script>';
    } else {
        echo '<script>alert("Fout bij het verwijderen van de klant."); location.replace("read.php");</script>';
    }
} else {
    echo '<script>alert("Geen klantId opgegeven."); location.replace("read.php");</script>';
}
?>