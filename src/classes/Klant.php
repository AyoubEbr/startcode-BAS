<?php
// Auteur: Ayoub
// Functie: definitie class Klant
namespace Bas\classes;

use PDO;
use PDOException;
use Bas\classes\Database;

class Klant extends Database {
    private string $table_name = "Klant";

    // Methods

    /**
     * Haal alle klanten op uit de database mbv de method getKlanten()
     * en toon ze in een HTML-tabel
     */
    public function crudKlant(): void {
        try {
            $klanten = $this->getKlanten();
            $this->showTable($klanten);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Haal alle klanten op uit de database
     * @return array
     */
    public function getKlanten(): array {
        try {
            $sql = "SELECT * FROM $this->table_name";
            $stmt = self::$conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Haal een klant op basis van klantId
     * @param int $klantId
     * @return array
     */
    public function getKlant(int $klantId): array {
        try {
            $sql = "SELECT * FROM $this->table_name WHERE klantId = :klantId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Toon een dropdown met klanten
     * @param int $row_selected
     */
    public function dropDownKlant(int $row_selected = -1): void {
        try {
            $klanten = $this->getKlanten();
            $html = "<label for='Klant'>Choose a klant:</label>";
            $html .= "<select name='klantId'>";
            foreach ($klanten as $row) {
                $selected = ($row_selected == $row["klantId"]) ? "selected='selected'" : "";
                $html .= "<option value='{$row['klantId']}' $selected>{$row['klantNaam']} {$row['klantEmail']}</option>\n";
            }
            $html .= "</select>";
            echo $html;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Toon klanten in een HTML-tabel
     * @param array $klanten
     */
    private function showTable(array $klanten): void {
        $html = "<table>";
        if (!empty($klanten)) {
            $html .= $this->getTableHeader(array_keys($klanten[0]));
            foreach ($klanten as $row) {
                $html .= "<tr>";
                $html .= "<td>{$row['klantNaam']}</td>";
                $html .= "<td>{$row['klantEmail']}</td>";
                $html .= "<td>{$row['klantWoonplaats']}</td>";
                $html .= "<td>{$row['klantAdres']}</td>";
                $html .= "<td>{$row['klantPostcode']}</td>";
                $html .= "<td><form method='post' action='update.php?klantId={$row['klantId']}'><button name='update'>Wzg</button></form></td>";
                $html .= "<td><form method='post' action='delete.php?klantId={$row['klantId']}'><button name='verwijderen'>Verwijderen</button></form></td>";
                $html .= "</tr>";
            }
        } else {
            $html .= "<tr><td colspan='7'>Geen klanten gevonden</td></tr>";
        }
        $html .= "</table>";
        echo $html;
    }

    /**
     * Verwijder een klant op basis van klantId
     * @param int $klantId
     * @return bool
     */
    public function deleteKlant(int $klantId): bool {
        try {
            $sql = "DELETE FROM $this->table_name WHERE klantId = :klantId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update een klantgegevens
     * @param array $row
     * @return bool
     */
    public function updateKlant(array $row): bool {
        try {
            $sql = "UPDATE $this->table_name 
                    SET klantEmail = :klantEmail, klantNaam = :klantNaam, klantWoonplaats = :klantWoonplaats, 
                    klantAdres = :klantAdres, klantPostcode = :klantPostcode 
                    WHERE klantId = :klantId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':klantId', $row['klantId'], PDO::PARAM_INT);
            $stmt->bindParam(':klantEmail', $row['klantEmail'], PDO::PARAM_STR);
            $stmt->bindParam(':klantNaam', $row['klantNaam'], PDO::PARAM_STR);
            $stmt->bindParam(':klantWoonplaats', $row['klantWoonplaats'], PDO::PARAM_STR);
            $stmt->bindParam(':klantAdres', $row['klantAdres'], PDO::PARAM_STR);
            $stmt->bindParam(':klantPostcode', $row['klantPostcode'], PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Bepaal het volgende beschikbare klantId
     * @return int
     */
    private function BepMaxKlantId(): int {
        try {
            $sql = "SELECT MAX(klantId) + 1 AS nextId FROM $this->table_name";
            $nextId = self::$conn->query($sql)->fetchColumn();
            return $nextId ? (int)$nextId : 1;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 1;
        }
    }

    /**
     * Voeg een nieuwe klant toe aan de database
     * @param array $row Array met klantgegevens
     * @return bool True als het invoegen succesvol is, anders False
     */
    public function insertKlant(array $row): bool {
        try {
            self::$conn->beginTransaction();
            $klantId = $this->BepMaxKlantId();
            $sql = "INSERT INTO $this->table_name (klantId, klantEmail, klantNaam, klantWoonplaats, klantAdres, klantPostcode)
                    VALUES (:klantId, :klantEmail, :klantNaam, :klantWoonplaats, :klantAdres, :klantPostcode)";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
            $stmt->bindParam(':klantEmail', $row['klantEmail'], PDO::PARAM_STR);
            $stmt->bindParam(':klantNaam', $row['klantNaam'], PDO::PARAM_STR);
            $stmt->bindParam(':klantWoonplaats', $row['klantWoonplaats'], PDO::PARAM_STR);
            $stmt->bindParam(':klantAdres', $row['klantAdres'], PDO::PARAM_STR);
            $stmt->bindParam(':klantPostcode', $row['klantPostcode'], PDO::PARAM_STR);
            $stmt->execute();
            self::$conn->commit();
            return true;
        } catch (PDOException $e) {
            self::$conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Genereer de tabelkop voor de HTML-tabel
     * @param array $columns Kolomnamen
     * @return string
     */
    private function getTableHeader(array $columns): string {
        $header = "<tr>";
        foreach ($columns as $column) {
            $header .= "<th>{$column}</th>";
        }
        $header .= "<th>Acties</th></tr>";
        return $header;
    }
}
?>
