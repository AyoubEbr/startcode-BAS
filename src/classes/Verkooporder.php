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
     * Summary of crudVerkoopOrder
     * @return void
     */
    public function crudVerkoopOrder() : void {
        // Haal alle verkooporders op uit de database mbv de method getVerkoopOrders()
        $lijst = $this->getVerkoopOrders();

        // Print een HTML tabel van de lijst   
        $this->showTable($lijst);
    }

    /**
     * Summary of getVerkoopOrders
     * @return array
     */
    public function getVerkoopOrders() : array {
        try {
            // Update the query to join with klant and artikel tables
            $sql = "SELECT vo.*, k.klantNaam, a.artOmschrijving 
                    FROM $this->table_name vo
                    JOIN klant k ON vo.klantId = k.klantId
                    JOIN artikel a ON vo.artId = a.artId";
            $stmt = self::$conn->query($sql);
            $lijst = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $lijst;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Summary of getVerkoopOrder
     * @param int $verkOrdId
     * @return array
     */
    public function getVerkoopOrder(int $verkOrdId) : array {
        try {
            // Update the query to join with klant and artikel tables
            $sql = "SELECT vo.*, k.klantNaam, a.artOmschrijving
                    FROM $this->table_name vo
                    JOIN klant k ON vo.klantId = k.klantId
                    JOIN artikel a ON vo.artId = a.artId
                    WHERE vo.verkOrdId = :verkOrdId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':verkOrdId', $verkOrdId, PDO::PARAM_INT);
            $stmt->execute();
            $lijst = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $lijst ?: [];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
    public function dropDownVerkoopOrder($row_selected = -1){
        // Haal alle verkooporders op uit de database mbv de method getVerkoopOrders()
        $lijst = $this->getVerkoopOrders();
        
        echo "<label for='VerkoopOrder'>Choose a verkooporder:</label>";
        echo "<select name='verkOrdId'>";
        foreach ($lijst as $row){
            if($row_selected == $row["verkOrdId"]){
                echo "<option value='$row[verkOrdId]' selected='selected'> Order $row[verkOrdId]</option>\n";
            } else {
                echo "<option value='$row[verkOrdId]'> Order $row[verkOrdId]</option>\n";
            }
        }
        echo "</select>";
    }

    /**
     * Summary of showTable
     * @param array $lijst
     * @return void
     */
    public function showTable(array $lijst) : void {
        $txt = "<table>";

        // Voeg de kolomnamen boven de tabel
        $txt .= "<tr>";
        $txt .= "<th>klantNaam</th>";
        $txt .= "<th>artNaam</th>";
        $txt .= "<th>verkOrdDatum</th>";
        $txt .= "<th>verkOrdBestAantal</th>";
        $txt .= "<th>verkOrdStatus</th>";
        $txt .= "</tr>";

        foreach($lijst as $row){
            $txt .= "<tr>";
            $txt .= "<td>" . htmlspecialchars($row["klantNaam"]) . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["artOmschrijving"]) . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["verkOrdDatum"]) . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["verkOrdBestAantal"]) . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["verkOrdStatus"]) . "</td>";
            
            // Update
            // Wijzig knopje
            $txt .= "<td>
                <form method='post' action='update.php?verkOrdId={$row["verkOrdId"]}'>
                    <button name='update'>Wzg</button>
                </form>
            </td>";

            // Delete
            $txt .= "<td>
                <form method='post' action='delete.php?verkOrdId={$row["verkOrdId"]}'>
                    <button name='verwijderen'>Verwijderen</button>
                </form>
            </td>"; 
            $txt .= "</tr>";
        }
        $txt .= "</table>";
        echo $txt;
    }

    // Delete verkooporder
    /**
     * Summary of deleteVerkoopOrder
     * @param int $verkOrdId
     * @return bool
     */
    public function deleteVerkoopOrder(int $verkOrdId) : bool {
        try {
            // Doe een delete-query op basis van $verkOrdId
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

    public function updateVerkoopOrder($row) : bool {
        // Voer de update van de verkooporder uit
        try {
            $sql = "UPDATE $this->table_name SET klantId = :klantId, artId = :artId, verkOrdDatum = :verkOrdDatum, verkOrdBestAantal = :verkOrdBestAantal, verkOrdStatus = :verkOrdStatus WHERE verkOrdId = :verkOrdId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':verkOrdId', $row['verkOrdId'], PDO::PARAM_INT);
            $stmt->bindParam(':klantId', $row['klantId'], PDO::PARAM_INT);
            $stmt->bindParam(':artId', $row['artId'], PDO::PARAM_INT);
            $stmt->bindParam(':verkOrdDatum', $row['verkOrdDatum'], PDO::PARAM_STR);
            $stmt->bindParam(':verkOrdBestAantal', $row['verkOrdBestAantal'], PDO::PARAM_INT);
            $stmt->bindParam(':verkOrdStatus', $row['verkOrdStatus'], PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Summary of BepMaxVerkOrdId
     * @return int
     */
    private function BepMaxVerkOrdId() : int {
        // Bepaal uniek nummer
        $sql="SELECT MAX(verkOrdId)+1 FROM $this->table_name";
        return (int) self::$conn->query($sql)->fetchColumn();
    }
    
    /**
     * Summary of insertVerkoopOrder
     * Voeg een nieuwe verkooporder toe aan de database
     * @param mixed $row Array met verkoopordergegevens
     * @return bool True als het invoegen succesvol is, anders False
     */
    
}
?>
