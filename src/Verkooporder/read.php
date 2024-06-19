<!--
    Auteur: Ayoub
    Functie: home page CRUD VerkoopOrder
-->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VerkoopOrder</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <h1>VerkoopOrder</h1>
    <nav>
        <a class="tvgklant" href='../index.html'>Home</a><br>
        <a class="tvgklant" href='insert.php'>Toevoegen verkoop order</a><br>
    </nav>
    
    <?php
    // Autoloader classes via composer
    require '../../vendor/autoload.php';

    use Bas\classes\VerkoopOrder;

    // Create a VerkoopOrder object
    $verkoopOrder = new VerkoopOrder();

    // Check if delete is requested
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verkOrdId']) && isset($_POST['verwijderen'])) {
        $verkOrdId = (int) $_POST['verkOrdId'];
        if ($verkoopOrder->deleteVerkoopOrder($verkOrdId)) {
            echo '<script>alert("Verkooporder succesvol verwijderd.");</script>';
        } else {
            echo '<script>alert("Fout bij het verwijderen van de verkooporder.");</script>';
        }
    }

    // Execute CRUD operations and display verkooporders
    $verkoopOrder->crudVerkooporder();
    ?>
</body>
</html>
