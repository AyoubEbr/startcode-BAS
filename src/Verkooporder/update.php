<?php
// Auteur: Ayoub
// Functie: Update VerkoopOrder

// Autoloader classes via Composer
require '../../vendor/autoload.php';
use Bas\classes\VerkoopOrder;

$verkooporder = new VerkoopOrder;

// Verwerken van formulierinzending
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"]) && $_POST["update"] == "Wijzigen") {
    // Data voorbereiden voor update
    $row = [
        'verkOrdId' => $_POST['verkOrdId'],
        'klantId' => $_POST['klantId'],
        'artId' => $_POST['artId'],
        'verkOrdDatum' => $_POST['verkOrdDatum'],
        'verkOrdBestAantal' => $_POST['verkOrdBestAantal'],
        'verkOrdStatus' => $_POST['verkOrdStatus']
    ];

    // Verkooporder bijwerken
    $success = $verkooporder->updateVerkoopOrder($row);

    // Bericht weergeven afhankelijk van succes van de update
    if ($success) {
        echo "Verkooporder succesvol bijgewerkt.<br>";
    } else {
        echo "Fout bij het bijwerken van de verkooporder.<br>";
    }
}

// Als verkOrdId is opgegeven, haal verkoopordergegevens op en toon formulier
if (isset($_GET['verkOrdId'])) {
    $row = $verkooporder->getVerkoopOrder($_GET['verkOrdId']);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verkooporder Wijzigen</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Verkooporder Wijzigen</h1>
    <form method="post">
        <input type="hidden" name="verkOrdId" value="<?= isset($row['verkOrdId']) ? htmlspecialchars($row['verkOrdId']) : '' ?>">
        <label for="klantId">Klant:</label>
        <input type="text" name="klantId" required value="<?= isset($row['klantId']) ? htmlspecialchars($row['klantId']) : '' ?>"> *</br>
        <label for="artId">Artikel:</label>
        <input type="text" name="artId" required value="<?= isset($row['artId']) ? htmlspecialchars($row['artId']) : '' ?>"> *</br>
        <label for="verkOrdDatum">Datum:</label>
        <input type="date" name="verkOrdDatum" required value="<?= isset($row['verkOrdDatum']) ? htmlspecialchars($row['verkOrdDatum']) : '' ?>"> *</br>
        <label for="verkOrdBestAantal">Bestel Aantal:</label>
        <input type="number" name="verkOrdBestAantal" required value="<?= isset($row['verkOrdBestAantal']) ? htmlspecialchars($row['verkOrdBestAantal']) : '' ?>"> *</br>
        <label for="verkOrdStatus">Status:</label>
        <select name="verkOrdStatus" required>
            <option value="Verzonden" <?= isset($row['verkOrdStatus']) && $row['verkOrdStatus'] == 'Verzonden' ? 'selected' : '' ?>>Verzonden</option>
            <option value="Niet Verzonden" <?= isset($row['verkOrdStatus']) && $row['verkOrdStatus'] == 'Niet Verzonden' ? 'selected' : '' ?>>Niet Verzonden</option>
            <option value="Onderweg" <?= isset($row['verkOrdStatus']) && $row['verkOrdStatus'] == 'Onderweg' ? 'selected' : '' ?>>Onderweg</option>
        </select> *</br></br>
        <input type="submit" name="update" value="Wijzigen">
    </form></br>

    <a href="read.php">Terug</a>
</body>
</html>

<?php
} else {
    echo "Geen verkOrdId opgegeven<br>";
}
?>
