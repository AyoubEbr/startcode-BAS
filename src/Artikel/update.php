<?php
    // auteur: Ayoub
    // functie: update class Artikel

    // Autoloader classes via composer
    require '../../vendor/autoload.php';
    use Bas\classes\Artikel;
    
    $artikel = new Artikel;

    if(isset($_POST["update"]) && $_POST["update"] == "Wijzigen"){

        // Code voor een update
        
    }

    if (isset($_GET['artId'])){
        $row = $artikel->getArtikel($_GET['artId']);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>CRUD Artikel</h1>
<h2>Wijzigen</h2>    
<form method="post">
<input type="hidden" name="artId" 
    value="<?php if(isset($row)) { echo $row['artId']; } ?>">
<input type="text" name="artOmschrijving" required 
    value="<?php if(isset($row)) {echo $row['artOmschrijving']; }?>"> *</br>
<input type="text" name="artInkoop" required 
    value="<?php if(isset($row)) {echo $row["artInkoop"]; }?>"> *</br></br>
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
