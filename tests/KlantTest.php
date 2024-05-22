<?php
// auteur: studentnaam
// functie: unitests class Klant

use PHPUnit\Framework\TestCase;
use Bas\classes\Klant;

// Filename moet gelijk zijn aan de classname KlantTest
class KlantTest extends TestCase{
    
	protected $klant;

    protected function setUp(): void {
        $this->klant = new Klant();
    }

	// Methods moeten starten met de naam test....
	public function testgetKlanten(){
		$klanten = $this->klant->getKlanten();
        $this->assertIsArray($klanten);
		$this->assertTrue(count($klanten) > 0, "Aantal moet groter dan 0 zijn");
	}

	public function testGetKlant(){
		$klantId = 1; // check of dit ook echt in de database bestaat!
		$klant = $this->klant->getKlant($klantId);
		$this->assertEquals($klantId, $klant['klantId']);
	}
	public function testInsertKlantTrue() {
        $testData = [
            'klantEmail' => 'test@hotmail.com',
            'klantNaam' => 'Tester123',
            'klantAdres' => 'Testing 222',
            'klantPostcode' => '5939',
            'klantWoonplaats' => 'TestWin'
        ];

        $result = $this->klant->insertKlant($testData);
        $this->assertTrue($result);
    }
}
	
?>