<!--
	Auteur: Ayoub
	Function: home page CRUD Klant
-->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
	<h1>CRUD Klant</h1>
	<nav>
		<a class="tvgklant" href='../index.html'>Home</a><br>
		<a class="tvgklant" href='insert.php'>Toevoegen nieuwe klant</a><br><br>
	</nav>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klantenbeheer</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Klantenbeheer</h1>
    
    <form method="post">
        <label for="klantNaam">Zoek op klantnaam:</label>
        <input type="text" name="klantNaam" id="klantNaam">
        <input type="submit" name="search" value="Zoeken">
    </form>
    
    <br>
    <a class="tvgklant" href="insert.php">Nieuwe klant toevoegen</a>


<?php

// Autoloader classes via composer
require '../../vendor/autoload.php';

use Bas\classes\Klant;

// Maak een object Klant
$klant = new Klant;

// Start CRUD
$klant->crudKlant();

?>
</body>
</html>