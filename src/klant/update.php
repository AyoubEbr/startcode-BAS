<?php
// Auteur: Ayoub
// Functie: Update Klant

// Autoloader classes via composer
require '../../vendor/autoload.php';
use Bas\classes\Klant;

$klant = new Klant();

if (isset($_POST["update"]) && $_POST["update"] == "Wijzigen") {
    // Validate input data
    $data = [
        'klantId' => isset($_POST['klantId']) ? (int)$_POST['klantId'] : null,
        'klantNaam' => trim($_POST['klantNaam']),
        'klantEmail' => trim($_POST['klantEmail']),
        'klantWoonplaats' => trim($_POST['klantWoonplaats']),
        'klantAdres' => trim($_POST['klantAdres']),
        'klantPostcode' => trim($_POST['klantPostcode'])
    ];

    // Check for required fields
    if ($data['klantId'] && $data['klantNaam'] && $data['klantEmail'] && $data['klantWoonplaats'] && $data['klantAdres'] && $data['klantPostcode']) {
        if ($klant->updateKlant($data)) {
            header("Location: read.php");
            exit;
        } else {
            echo "Er is een fout opgetreden bij het bijwerken van de klant.";
        }
    } else {
        echo "Alle velden zijn verplicht.";
    }
}

if (isset($_GET['klantId'])) {
    $klantId = (int)$_GET['klantId'];
    $row = $klant->getKlant($klantId);
    if (!$row) {
        echo "Klant niet gevonden.";
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Klant</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>CRUD Klant</h1>
<h2>Wijzigen</h2>
<form method="post">
    <input type="hidden" name="klantId" value="<?php echo htmlspecialchars($row['klantId']); ?>">
    <label for="klantNaam">Naam:</label>
    <input type="text" name="klantNaam" required value="<?php echo htmlspecialchars($row['klantNaam']); ?>"> *</br>
    <label for="klantEmail">Email:</label>
    <input type="email" name="klantEmail" required value="<?php echo htmlspecialchars($row['klantEmail']); ?>"> *</br>
    <label for="klantWoonplaats">Woonplaats:</label>
    <input type="text" name="klantWoonplaats" required value="<?php echo htmlspecialchars($row['klantWoonplaats']); ?>"> *</br>
    <label for="klantAdres">Adres:</label>
    <input type="text" name="klantAdres" required value="<?php echo htmlspecialchars($row['klantAdres']); ?>"> *</br>
    <label for="klantPostcode">Postcode:</label>
    <input type="text" name="klantPostcode" required value="<?php echo htmlspecialchars($row['klantPostcode']); ?>"> *</br></br>
    <input type="submit" name="update" value="Wijzigen">
</form></br>

<a href="read.php">Terug</a>

</body>
</html>

<?php
} else {
    echo "Geen klantId opgegeven<br>";
}
?>
