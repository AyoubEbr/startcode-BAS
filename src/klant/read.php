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
		<a href='../index.html'>Home</a><br>
		<a href='insert.php'>Toevoegen nieuwe klant</a><br><br>
	</nav>
	
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
