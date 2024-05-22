<?php
// Auteur: Ayoub
// Functie: definitie class Klant
namespace Bas\classes;

use PDO;
use PDOException;
use Bas\classes\Database;

include_once "functions.php";

class Klant extends Database {
    public $klantId;
    public $klantemail = null;
    public $klantnaam;
    public $klantwoonplaats;
    public $klantAdres;
    public $klantPostcode;
    private $table_name = "Klant";   

    // CRUD Methods
    /**
     * Summary of crudKlant
     * @return void
     */
    public function crudKlant() : void {
        // Haal alle klanten op uit de database mbv de method getKlanten()
        $lijst = $this->getKlanten();
        // Print een HTML tabel van de lijst   
        $this->showTable($lijst);
    }

    /**
     * Voeg een nieuwe klant toe aan de database
     * @param mixed $row Array met klantgegevens
     * @return bool True als het invoegen succesvol is, anders False
     */
    public function insertKlant($row) : bool {
        try {
            // Begin een transactie
            self::$conn->beginTransaction();
            // Bepaal een unieke klantId
            $klantId = $this->BepMaxKlantId();
            // SQL-query voor het invoegen van een nieuwe klant
            $sql = "INSERT INTO $this->table_name (klantId, klantEmail, klantNaam, klantWoonplaats, klantAdres, klantPostcode) 
                    VALUES (:klantId, :klantEmail, :klantNaam, :klantWoonplaats, :klantAdres, :klantPostcode)";
            // Bereid de query voor
            $stmt = self::$conn->prepare($sql);
            // Bind de parameters
            $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
            $stmt->bindParam(':klantEmail', $row['klantEmail'], PDO::PARAM_STR);
            $stmt->bindParam(':klantNaam', $row['klantNaam'], PDO::PARAM_STR);
            $stmt->bindParam(':klantWoonplaats', $row['klantWoonplaats'], PDO::PARAM_STR);
            $stmt->bindParam(':klantAdres', $row['klantAdres'], PDO::PARAM_STR);
            $stmt->bindParam(':klantPostcode', $row['klantPostcode'], PDO::PARAM_STR);
            // Voer de query uit
            $stmt->execute();
            // Commit de transactie
            self::$conn->commit();
            return true; // Succesvol ingevoegd
        } catch(PDOException $e) {
            // Rol de transactie terug bij een fout
            self::$conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false; // Fout bij het invoegen
        }
    }

    /**
     * Summary of updateKlant
     * Voer de update van de klant uit
     * @param array $row
     * @return bool
     */
    public function updateKlant($row) : bool {
        try {
            $sql = "UPDATE $this->table_name SET klantEmail = :klantEmail, klantNaam = :klantNaam, klantWoonplaats = :klantWoonplaats, klantAdres = :klantAdres, klantPostcode = :klantPostcode WHERE klantId = :klantId";
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
     * Summary of deleteKlant
     * Verwijder klant uit de database
     * @param int $klantId
     * @return bool
     */
    public function deleteKlant(int $klantId) : bool {
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

    // Retrieve Methods
    /**
     * Summary of getKlanten
     * @return array
     */
    public function getKlanten() : array {
        try {
            $sql = "SELECT * FROM $this->table_name";
            $stmt = self::$conn->query($sql);
            $lijst = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $lijst;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Summary of getKlant
     * @param int $klantId
     * @return array
     */
    public function getKlant(int $klantId) : array {
        try {
            $sql = "SELECT * FROM $this->table_name WHERE klantId = :klantId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
            $stmt->execute();
            $lijst = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lijst ?: [];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Utility Methods
    /**
     * Summary of dropDownKlant
     * @param int $row_selected
     * @return void
     */
    public function dropDownKlant($row_selected = -1){
        $lijst = $this->getKlanten();
        echo "<label for='Klant'>Choose a klant:</label>";
        echo "<select name='klantId'>";
        foreach ($lijst as $row){
            if($row_selected == $row["klantId"]){
                echo "<option value='$row[klantId]' selected='selected'> $row[klantNaam] $row[klantEmail]</option>\n";
            } else {
                echo "<option value='$row[klantId]'> $row[klantNaam] $row[klantEmail]</option>\n";
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
        $txt .= getTableHeader($lijst[0]);
        foreach($lijst as $row){
            $txt .= "<tr>";
            $txt .=  "<td>" . $row["klantId"] . "</td>";
            $txt .=  "<td>" . $row["klantNaam"] . "</td>";
            $txt .=  "<td>" . $row["klantEmail"] . "</td>";
            $txt .=  "<td>" . $row["klantWoonplaats"] . "</td>";
            $txt .=  "<td>" . $row["klantAdres"] . "</td>";
            $txt .=  "<td>" . $row["klantPostcode"] . "</td>";
            $txt .=  "<td>";
            $txt .= " 
            <form method='post' action='update.php?klantId=$row[klantId]' >       
                <button name='update'>Wzg</button>    
            </form> </td>";
            $txt .=  "<td>";
            $txt .= " 
            <form method='post' action='delete.php?klantId=$row[klantId]' >       
                <button name='verwijderen'>Verwijderen</button>    
            </form> </td>"; 
            $txt .= "</tr>";
        }
        $txt .= "</table>";
        echo $txt;
    }

    /**
     * Bepaal uniek nummer
     * @return int
     */
    private function BepMaxKlantId() : int {
        $sql="SELECT MAX(klantId)+1 FROM $this->table_name";
        return (int) self::$conn->query($sql)->fetchColumn();
    }
}
?>
