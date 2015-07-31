<?php

namespace Example\tests\src;

class exampleContactsModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \DataPool\DataPool Instance to be used while testing
     */
    static private $dataPool = null;

    /**
     * Static instance of ContactsDataPool
     * 
     * @return \Example\tests\files\ContactsDataPool
     */
    public function getDataPool() {
        // If not instanciated jet...
        if (is_object(self::$dataPool) === false) {
            self::$dataPool = new \Example\tests\files\ContactsDataPool();
        }
        // Avoid mixing behaviors during tests
        self::$dataPool->setReturnArray(false);
        self::$dataPool->setReturnIndexes(true);
        
        return self::$dataPool;
    }

    public function testOneInsertOK() {
        $dataPool = $this->getDataPool();
        $contModel = new \Example\src\exampleContactsModel();

        $testInsertArray = $dataPool['Test1'];
        var_dump($testInsertArray);
        unset($testInsertArray['result']);

        $result = $contModel->insert($testInsertArray);

        $this->assertTrue(is_integer($result));
    }

    /**
     * @dataProvider getDataPool
     */
    public function pureDataProviderInsert($name, $surname, $phone, $expected) {
        $reg['name'] = $name;
        $reg['surname'] = $surname;
        $reg['phone'] = $phone;

        $result = $contModel->insert($reg);
        if ($result === false) {
            $this->assertEquals($expected, $result);
        } else {
            $this->assertTrue(is_integer($result));
        }
    }

    public function getDataPoolAsArray() {
        $dataPool = $this->getDataPool();
        $dataPool->setReturnArray(true);
        $dataPool->setReturnIndexes(true);
        
        return $dataPool;
    }
    
    /**
     * @dataProvider getDataPoolAsArray
     */
    public function arrayDataProviderInsert($regData) {
        $expected = $regData['result'];
        unset($regData['result']);

        $result = $contModel->insert($regData);
        if ($result === false) {
            $this->assertEquals($expected, $result);
        } else {
            $this->assertTrue(is_integer($result));
        }
    }

}
