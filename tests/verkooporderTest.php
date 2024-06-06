<?php
// auteur: Ayoub
// functie: unitests class Insertverkooporder

use PHPUnit\Framework\TestCase;
use Bas\classes\Verkooporder;

// Filename moet gelijk zijn aan de classname verkooporderTest
class verkooporderTest extends TestCase{
    
    protected $verkooporder;

    protected function setUp(): void {
        $this->verkooporder = new Verkooporder();
    }

    public function testInsertverkooporderTrue() {
        // Test data
        $testData = [
            'klantId' => 1,
            'artId' => 1,
            'verkOrdDatum' => '2023-01-01',
            'verkOrdBestAantal' => 10,
            'verkOrdStatus' => 1
        ];
        
        $result = $this->verkooporder->insertverkooporder($testData);
        $this->assertTrue($result);
    }
}    
?>