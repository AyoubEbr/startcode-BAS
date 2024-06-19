<?php
// auteur: ayoub
// functie: update class Artikel

// Autoloader classes via composer
require '../../vendor/autoload.php';
use Bas\classes\Artikel;

$artikel = new Artikel;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["update"]) && $_POST["update"] == "Wijzigen") {
    $data = [
        'artId' => $_POST['artId'],
        'artOmschrijving' => $_POST['artOmschrijving'],
        'artInkoop' => $_POST['artInkoop'],
        'artVerkoop' => $_POST['artVerkoop'],
        'artVoorraad' => $_POST['artVoorraad'],
        'artMinVoorraad' => $_POST['artMinVoorraad'],
        'artMaxVoorraad' => $_POST['artMaxVoorraad'],
        'artLocatie' => $_POST['artLocatie']
    ];
    if ($artikel->updateArtikel($data)) {
        header("Location: read.php");
        exit;
    } else {
        echo "Er is een fout opgetreden bij het bijwerken van het artikel.";
    }
}

if (isset($_GET['artId'])) {
    $row = $artikel->getArtikel((int)$_GET['artId']);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Artikel</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>CRUD Artikel</h1>
<h2>Wijzigen</h2>
<form method="post">
    <input type="hidden" name="artId" value="<?php echo htmlspecialchars($row['artId'], ENT_QUOTES, 'UTF-8'); ?>">
    <label for="artOmschrijving">Omschrijving:</label>
    <input type="text" name="artOmschrijving" id="artOmschrijving" required value="<?php echo htmlspecialchars($row['artOmschrijving'], ENT_QUOTES, 'UTF-8'); ?>"> *</br>
    <label for="artInkoop">Inkoop:</label>
    <input type="text" name="artInkoop" id="artInkoop" required value="<?php echo htmlspecialchars($row['artInkoop'], ENT_QUOTES, 'UTF-8'); ?>"> *</br>
    <label for="artVerkoop">Verkoop:</label>
    <input type="text" name="artVerkoop" id="artVerkoop" required value="<?php echo htmlspecialchars($row['artVerkoop'], ENT_QUOTES, 'UTF-8'); ?>"> *</br>
    <label for="artVoorraad">Voorraad:</label>
    <input type="text" name="artVoorraad" id="artVoorraad" required value="<?php echo htmlspecialchars($row['artVoorraad'], ENT_QUOTES, 'UTF-8'); ?>"> *</br>
    <label for="artMinVoorraad">Min Voorraad:</label>
    <input type="text" name="artMinVoorraad" id="artMinVoorraad" required value="<?php echo htmlspecialchars($row['artMinVoorraad'], ENT_QUOTES, 'UTF-8'); ?>"> *</br>
    <label for="artMaxVoorraad">Max Voorraad:</label>
    <input type="text" name="artMaxVoorraad" id="artMaxVoorraad" required value="<?php echo htmlspecialchars($row['artMaxVoorraad'], ENT_QUOTES, 'UTF-8'); ?>"> *</br>
    <label for="artLocatie">Locatie:</label>
    <input type="text" name="artLocatie" id="artLocatie" required value="<?php echo htmlspecialchars($row['artLocatie'], ENT_QUOTES, 'UTF-8'); ?>"> *</br></br>
    <input type="submit" name="update" value="Wijzigen">
</form></br>

<a href="read.php">Terug</a>

</body>
</html>

<?php
} else {
    echo "Geen artId opgegeven<br>";
}
?>
