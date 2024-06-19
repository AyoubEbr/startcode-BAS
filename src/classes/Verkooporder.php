<?php
// Auteur: Ayoub
// Functie: definitie class VerkoopOrder
namespace Bas\classes;

use PDO;
use PDOException;
use Bas\classes\Database;

include_once "functions.php";

class VerkoopOrder extends Database {
    public $verkOrdId;
    public $klantId;
    public $artId;
    public $verkOrdDatum;
    public $verkOrdBestAantal;
    public $verkOrdStatus;
    private $table_name = "VerkoopOrder";   

    // Methods
    
    /**
     * Haal alle verkooporders op en toon ze in een HTML-tabel
     */
    public function crudVerkooporder(): void {
        $lijst = $this->getVerkoopOrders();
        $this->showTable($lijst);
    }

    /**
     * Haal alle verkooporders op uit de database
     * @return array
     */
    public function getVerkoopOrders(): array {
        try {
            $sql = "SELECT vo.*, k.klantNaam, a.artOmschrijving 
                    FROM $this->table_name vo
                    JOIN klant k ON vo.klantId = k.klantId
                    JOIN artikel a ON vo.artId = a.artId";
            $stmt = self::$conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Haal een specifieke verkooporder op basis van verkOrdId
     * @param int $verkOrdId
     * @return array
     */
    public function getVerkoopOrder(int $verkOrdId): array {
        try {
            $sql = "SELECT vo.*, k.klantNaam, a.artOmschrijving
                    FROM $this->table_name vo
                    JOIN klant k ON vo.klantId = k.klantId
                    JOIN artikel a ON vo.artId = a.artId
                    WHERE vo.verkOrdId = :verkOrdId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':verkOrdId', $verkOrdId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * Toon een dropdown met verkooporders
     * @param int $row_selected
     */
    public function dropDownVerkoopOrder(int $row_selected = -1): void {
        $lijst = $this->getVerkoopOrders();
        
        echo "<label for='VerkoopOrder'>Choose a verkooporder:</label>";
        echo "<select name='verkOrdId'>";
        foreach ($lijst as $row) {
            $selected = ($row_selected == $row["verkOrdId"]) ? "selected='selected'" : "";
            echo "<option value='{$row['verkOrdId']}' $selected>Order {$row['verkOrdId']}</option>\n";
        }
        echo "</select>";
    }

    /**
     * Toon de verkooporders in een HTML-tabel
     * @param array $lijst
     */
    public function showTable(array $lijst): void {
        echo "<table>";

        // Voeg de kolomnamen boven de tabel
        echo "<tr>
                <th>klantNaam</th>
                <th>artNaam</th>
                <th>verkOrdDatum</th>
                <th>verkOrdBestAantal</th>
                <th>verkOrdStatus</th>
                <th>Acties</th>
              </tr>";

        foreach ($lijst as $row) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["klantNaam"]) . "</td>
                    <td>" . htmlspecialchars($row["artOmschrijving"]) . "</td>
                    <td>" . htmlspecialchars($row["verkOrdDatum"]) . "</td>
                    <td>" . htmlspecialchars($row["verkOrdBestAantal"]) . "</td>
                    <td>" . htmlspecialchars($row["verkOrdStatus"]) . "</td>
                    <td>
                        <form method='post' action='update.php?verkOrdId={$row["verkOrdId"]}'>
                            <button name='update'>Wzg</button>
                        </form>
                        <form method='post' action='delete.php?verkOrdId={$row["verkOrdId"]}'>
                            <button name='verwijderen' onclick='return confirm(\"Weet je zeker dat je deze verkooporder wilt verwijderen?\");'>Verwijderen</button>
                        </form>
                    </td>
                  </tr>";
        }
        echo "</table>";
    }

    /**
     * Verwijder een verkooporder op basis van verkOrdId
     * @param int $verkOrdId
     * @return bool
     */
    public function deleteVerkoopOrder(int $verkOrdId): bool {
        try {
            $sql = "DELETE FROM $this->table_name WHERE verkOrdId = :verkOrdId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':verkOrdId', $verkOrdId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update een verkooporder
     * @param array $row
     * @return bool
     */
    public function updateVerkoopOrder(array $row): bool {
        try {
            $sql = "UPDATE $this->table_name 
                    SET klantId = :klantId, artId = :artId, verkOrdDatum = :verkOrdDatum, verkOrdBestAantal = :verkOrdBestAantal, verkOrdStatus = :verkOrdStatus 
                    WHERE verkOrdId = :verkOrdId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':verkOrdId', $row['verkOrdId'], PDO::PARAM_INT);
            $stmt->bindParam(':klantId', $row['klantId'], PDO::PARAM_INT);
            $stmt->bindParam(':artId', $row['artId'], PDO::PARAM_INT);
            $stmt->bindParam(':verkOrdDatum', $row['verkOrdDatum'], PDO::PARAM_STR);
            $stmt->bindParam(':verkOrdBestAantal', $row['verkOrdBestAantal'], PDO::PARAM_INT);
            $stmt->bindParam(':verkOrdStatus', $row['verkOrdStatus'], PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Bepaal het volgende beschikbare verkOrdId
     * @return int
     */
    private function BepMaxVerkOrdId(): int {
        try {
            $sql = "SELECT COALESCE(MAX(verkOrdId), 0) + 1 AS next_id FROM $this->table_name";
            return (int)self::$conn->query($sql)->fetchColumn();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }
    
    /**
     * Voeg een nieuwe verkooporder toe aan de database
     * @param array $verkoopordergegevens
     * @return bool
     */
    public function insertVerkoopOrder(array $verkoopordergegevens): bool {
        try {
            self::$conn->beginTransaction();

            $sql = "INSERT INTO $this->table_name (klantId, artId, verkOrdDatum, verkOrdBestAantal, verkOrdStatus) 
                    VALUES (:klantId, :artId, :verkOrdDatum, :verkOrdBestAantal, :verkOrdStatus)";
            
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':klantId', $verkoopordergegevens['klantId'], PDO::PARAM_INT);
            $stmt->bindParam(':artId', $verkoopordergegevens['artId'], PDO::PARAM_INT);
            $stmt->bindParam(':verkOrdDatum', $verkoopordergegevens['verkOrdDatum'], PDO::PARAM_STR);
            $stmt->bindParam(':verkOrdBestAantal', $verkoopordergegevens['verkOrdBestAantal'], PDO::PARAM_INT);
            $stmt->bindParam(':verkOrdStatus', $verkoopordergegevens['verkOrdStatus'], PDO::PARAM_STR);
            $stmt->execute();

            self::$conn->commit();
            return true;
        } catch(PDOException $e) {
            self::$conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
