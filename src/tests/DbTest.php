<?php

namespace KrytenTest;

use PHPUnit\Framework\TestCase;
use PDOStatement;
use Kryten\Db;
use stdClass;

/**
 * Unit test of database methods
 *
 * @author Garry Childs <info@freedomwebservices.net>
 */
class DbTest extends TestCase
{
    
    private string $rfid = '142594708f3a5a3ac2980914a0fc954f';

    private array $testData = [
        [
            'employee_id' => 1,
            'name' => 'Julius Caesar',
            'department' => 'Director',
        ],
        [
            'employee_id' => 1,
            'name' => 'Julius Caesar',
            'department' => 'Development',
        ],
    ];

    public function testCheckRfidCardSucceeds()
    {
        $pdoStatement = $this->createMock(PDOStatement::class);
        $pdoStatement->expects($this->once())
                ->method('execute')
                ->with($this->arrayHasKey('rfid'))
                ->willReturn(true);
        $pdoStatement->expects($this->once())
                ->method('fetchAll')
                ->willReturn($this->testData);
        
        $db = new Db();
        
        $result = $db->checkRfidCard($this->rfid, $pdoStatement);
        
        $this->assertInstanceOf(stdClass::class, $result);
        $this->assertEquals('Julius Caesar', $result->full_name);
        $this->assertIsArray($result->department);
        $this->assertEquals('Director', $result->department[0]);
        $this->assertEquals('Development', $result->department[1]);
    }

    public function testCheckRfidCardFails()
    {
        $pdoStatement = $this->createMock(PDOStatement::class);
        $pdoStatement->expects($this->once())
                ->method('execute')
                ->with($this->arrayHasKey('rfid'))
                ->willReturn(true);
        $pdoStatement->expects($this->once())
                ->method('fetchAll')
                ->willReturn([]);
        
        $db = new Db();
        
        $result = $db->checkRfidCard('falsevalue', $pdoStatement);

        $this->assertNull($result);
    }

}
