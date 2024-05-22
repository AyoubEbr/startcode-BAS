<?php
// auteur: studentnaam
// functie: definitie class Database
namespace Bas\classes;

use PDO;
use PDOException;


require_once "config.php";

class Database{
	// protected: binnen class en sub../classes
	// static omdat de connectie bewaard blijft
	protected static $conn = NULL;
	
	private $servername = SERVERNAME;
    private $username = USERNAME;
    private $password = PASSWORD;
    private $dbname = DATABASE;
	
	// Methods
	public function __construct(){
		//self::$conn = null;	
		// Test of de connectie al eerder gedaan is. Daarom static variabele
		if (!self::$conn) {
			try{
				 self::$conn = new PDO ("mysql:host=$this->servername;
						dbname=$this->dbname",
						$this->username,
						$this->password) ;

				 self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ;
				 self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				 //echo "Connectie is gelukt <br />" ;
			}

			catch(PDOException $e){
				 echo "Connectie mislukt: " . $e->getMessage() ;
			}
		} else {
			//echo "Database is al geconnected<br>";
		}
	}
	
	public function getConnection(){
		return self::$conn;
	}
	
}
?>