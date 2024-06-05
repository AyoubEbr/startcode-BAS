<?php
// Auteur: Ayoub
// Functie: Insert

require '../../vendor/autoload.php';
use Bas\classes\Klant;
use Bas\classes\Artikel;
use Bas\classes\Verkooporder;

$message = "";

if (isset($_POST["insert"]) && $_POST["insert"] == "Toevoegen") {
    if (
        isset($_POST['klantnaam']) && isset($_POST['klantemail']) && isset($_POST['klantadres']) && isset($_POST['klantpostcode']) &&
        isset($_POST['artikelnaam']) && isset($_POST['artikelprijs']) && isset($_POST['verkOrdDatum']) &&
        isset($_POST['verkOrdBestAantal']) && isset($_POST['verkOrdStatus'])
    ) {

        // Maak een nieuw Klant object aan
        $klant = new Klant();
        // Maak een nieuw Artikel object aan
        $artikel = new Artikel();
        // Maak een nieuw Verkooporder object aan
        $verkooporder = new Verkooporder();

        // Bereid de klantgegevens voor
        $klantgegevens = [
            'klantNaam' => $_POST['klantnaam'],
            'klantEmail' => $_POST['klantemail'],
            'klantWoonplaats' => $_POST['klantwoonplaats'],
            'klantAdres' => $_POST['klantadres'],
            'klantPostcode' => $_POST['klantpostcode']
        ];

        // Bereid de artikelgegevens voor
        $artikelgegevens = [
            'artikelNaam' => $_POST['artikelnaam'],
            'artikelPrijs' => $_POST['artikelprijs']
        ];

        // Voeg de klantgegevens toe aan de database
        if ($klant->insertKlant($klantgegevens)) {
            // Haal het laatste klant ID op
            $klantId = $klant->getLastInsertedId();

            // Voeg de artikelgegevens toe aan de database
            if ($artikel->insertArtikel($artikelgegevens)) {
                // Haal het laatste artikel ID op
                $artikelId = $artikel->getLastInsertedId();

                // Voeg de verkooporder toe aan de database
                $verkoopordergegevens = [
                    'klantId' => $klantId,
                    'artikelId' => $artikelId,
                    'verkOrdDatum' => $_POST['verkOrdDatum'],
                    'verkOrdBestAantal' => $_POST['verkOrdBestAantal'],
                    'verkOrdStatus' => $_POST['verkOrdStatus']
                ];
                if ($verkooporder->insertVerkooporder($verkoopordergegevens)) {
                    $message = "Verkooporder succesvol toegevoegd!";
                } else {
                    $message = "Er is een fout opgetreden bij het toevoegen van de verkooporder.";
                }
            } else {
                $message = "Er is een fout opgetreden bij het toevoegen van het artikel.";
            }
        } else {
            $message = "Er is een fout opgetreden bij het toevoegen van de klant.";
        }
    } else {
        $message = "Vul alstublieft alle vereiste velden in.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toevoegen Klant en Artikel</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <h1>CRUD Verkooporder</h1>
    <h2>Toevoegen</h2>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="nv">Klantnaam:</label>
        <input type="text" id="nv" name="klantnaam" placeholder="Klantnaam" required/>
        <br>
        <label for="an">Klantemail:</label>
        <input type="email" id="an" name="klantemail" placeholder="Klantemail" required/>
        <br>
        <label for="an">KlantWoonplaats:</label>
        <input type="text" id="an" name="klantwoonplaats" placeholder="Klantwoonplaats" required/>
        <br>
        <label for="an">KlantPostcode:</label>
        <input type="text" id="an" name="klantpostcode" placeholder="Klantpostcode" required/>
        <br>
        <label for="an">KlantAdres:</label>
        <input type="text" id="an" name="klantadres" placeholder="Klantadres" required/>
        <br>
        <h3>Artikelgegevens</h3>
        <label for="nv">Artikelnaam:</label>
        <input type="text" id="nv" name="artikelnaam" placeholder="Artikelnaam" required/>
        <br>
        <label for="an">Artikelprijs:</label>
        <input type="number" id="an" name="artikelprijs" placeholder="Artikelprijs" step="0.01" required/>
        <br>
        <h3>Verkoopordergegevens</h3>
        <label for="vd">Verkooporder Datum:</label>
        <input type="date" id="vd" name="verkOrdDatum" required/>
        <br>
        <label for="vb">Verkooporder Bestel Aantal:</label>
        <input type="number" id="vb" name="verkOrdBestAantal" required/>
        <br>
        <label for="vs">Verkooporder Status:</label>
        <input type="number" id="vs" name="verkOrdStatus" required/>
        <br><br>
        <input type='submit' name='insert' value='Toevoegen'>
    </form></br>

    <a href='read.php'>Terug</a>

</body>
</html>
