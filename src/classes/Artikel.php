<?php
// auteur: Ayoub
// functie: Class Artikel
namespace Bas\classes;

use PDO;

include_once "functions.php";

class Artikel extends Database {
    public $artId;
    public $artOmschrijving;
    public $artInkoop;
    public $artVerkoop;
    public $artVoorraad;
    public $artMinVoorraad;
    public $artMaxVoorraad;
    public $artLocatie;
    private $table_name = "Artikel";

    // Retrieve all articles and display them in a HTML table
    public function crudArtikel(): void {
        $lijst = $this->getArtikelen();
        $this->showTable($lijst);
    }

    // Fetch all articles from the database
    public function getArtikelen(): array {
        $sql = "SELECT artId, artOmschrijving, artInkoop, artVerkoop, artVoorraad, artMinVoorraad, artMaxVoorraad, artLocatie FROM " . $this->table_name;
        $stmt = self::$conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a specific article by ID
    public function getArtikel(int $artId): array {
        $sql = "SELECT artId, artOmschrijving, artInkoop, artVerkoop, artVoorraad, artMinVoorraad, artMaxVoorraad, artLocatie FROM " . $this->table_name . " WHERE artId = :artId";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':artId', $artId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    // Generate a dropdown menu of articles
    public function dropDownArtikel(int $row_selected = -1): void {
        $lijst = $this->getArtikelen();
        echo "<label for='Artikel'>Choose an artikel:</label>";
        echo "<select name='artId'>";
        foreach ($lijst as $row) {
            $selected = $row_selected == $row["artId"] ? "selected='selected'" : "";
            echo "<option value='{$row["artId"]}' $selected> {$row["artOmschrijving"]}</option>\n";
        }
        echo "</select>";
    }

    // Display a list of articles in a HTML table
    public function showTable(array $lijst): void {
        if (empty($lijst)) {
            echo "<p>No articles found.</p>";
            return;
        }
        $txt = "<table>";
        $header = array_keys($lijst[0]);
        $txt .= "<tr>";
        foreach ($header as $col) {
            $txt .= "<th>" . htmlspecialchars($col) . "</th>";
        }
        $txt .= "<th>Action</th>";
        $txt .= "</tr>";

        foreach ($lijst as $row) {
            $txt .= "<tr>";
            foreach ($row as $key => $value) {
                $txt .= "<td>" . htmlspecialchars($value) . "</td>";
            }
            $txt .= "<td>
                        <form method='post' action='update.php?artId={$row["artId"]}'>
                            <button name='update'>Wzg</button>
                        </form>
                        <form method='post' action='delete.php?artId={$row["artId"]}'>
                            <button name='verwijderen'>Verwijderen</button>
                        </form>
                    </td>";
            $txt .= "</tr>";
        }
        $txt .= "</table>";
        echo $txt;
    }

    // Delete a specific article by ID
    public function deleteArtikel(int $artId): bool {
        $sql = "DELETE FROM " . $this->table_name . " WHERE artId = :artId";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':artId', $artId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Update a specific article
    public function updateArtikel(array $data): bool {
        $sql = "UPDATE " . $this->table_name . " 
                SET artOmschrijving = :artOmschrijving, 
                    artInkoop = :artInkoop,
                    artVerkoop = :artVerkoop,
                    artVoorraad = :artVoorraad,
                    artMinVoorraad = :artMinVoorraad,
                    artMaxVoorraad = :artMaxVoorraad,
                    artLocatie = :artLocatie
                WHERE artId = :artId";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':artId', $data['artId'], PDO::PARAM_INT);
        $stmt->bindParam(':artOmschrijving', $data['artOmschrijving'], PDO::PARAM_STR);
        $stmt->bindParam(':artInkoop', $data['artInkoop'], PDO::PARAM_STR);
        $stmt->bindParam(':artVerkoop', $data['artVerkoop'], PDO::PARAM_STR);
        $stmt->bindParam(':artVoorraad', $data['artVoorraad'], PDO::PARAM_INT);
        $stmt->bindParam(':artMinVoorraad', $data['artMinVoorraad'], PDO::PARAM_INT);
        $stmt->bindParam(':artMaxVoorraad', $data['artMaxVoorraad'], PDO::PARAM_INT);
        $stmt->bindParam(':artLocatie', $data['artLocatie'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Determine the next available article ID
    private function BepMaxArtId(): int {
        $sql = "SELECT MAX(artId)+1 FROM " . $this->table_name;
        return (int) self::$conn->query($sql)->fetchColumn();
    }

    // Insert a new article into the database
    public function insertArtikel(array $row): bool {
        $artId = $this->BepMaxArtId();
        $sql = "INSERT INTO " . $this->table_name . " (artId, artOmschrijving, artInkoop, artVerkoop, artVoorraad, artMinVoorraad, artMaxVoorraad, artLocatie) 
                VALUES (:artId, :artOmschrijving, :artInkoop, :artVerkoop, :artVoorraad, :artMinVoorraad, :artMaxVoorraad, :artLocatie)";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':artId', $artId, PDO::PARAM_INT);
        $stmt->bindParam(':artOmschrijving', $row['artOmschrijving'], PDO::PARAM_STR);
        $stmt->bindParam(':artInkoop', $row['artInkoop'], PDO::PARAM_STR);
        $stmt->bindParam(':artVerkoop', $row['artVerkoop'], PDO::PARAM_STR);
        $stmt->bindParam(':artVoorraad', $row['artVoorraad'], PDO::PARAM_INT);
        $stmt->bindParam(':artMinVoorraad', $row['artMinVoorraad'], PDO::PARAM_INT);
        $stmt->bindParam(':artMaxVoorraad', $row['artMaxVoorraad'], PDO::PARAM_INT);
        $stmt->bindParam(':artLocatie', $row['artLocatie'], PDO::PARAM_STR);
        return $stmt->execute();
    }
}
?>
