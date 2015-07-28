<?php
namespace Example\tests\src;

class exampleContactsModelTest extends \PHPUnit_Framework_TestCase {

    static private $dataPool = null;


    /**
     *
     * @param array $definition Definition array to be combined with data array
     * @param array $dataArray Definition array to be combined with data array
     *
     * @return \Example\tests\files\ContactsDataPool
     */
    public function getDataPool() {
        if(is_object(self::$dataPool) === false) {
            self::$dataPool = new \Example\tests\files\ContactsDataPool();
        }
        return self::$dataPool;
    }


    public function testOneInsertOK() {
        $dataPool        = $this->getDataPool();
        $contModel       = new \Example\src\exampleContactsModel();

        $testInsertArray = $dataPool['Test1'];
        unset($testInsertArray['result']);

        $result          = $contModel->insert($testInsertArray);

        $this->assertTrue(is_integer($result));
    }

    /**
     * @dataProvider getDataPool
     */
    public function pureDataProviderInsert($name, $surname, $phone, $expected) {
        $reg['name']    = $name;
        $reg['surname'] = $surname;
        $reg['phone']   = $phone;

        $result = $contModel->insert($reg);
        if($result === false) {
            $this->assertEquals($expected, $result);
        } else {
            $this->assertTrue(is_integer($result));
        }
    }


    /**
     * @dataProvider getDataPoolAsArray
     */
    public function arrayDataProviderInsert($regData) {
        $expected = $regData['result'];
        unset($regData['result']);

        $result = $contModel->insert($regData);
        if($result === false) {
            $this->assertEquals($expected, $result);
        } else {
            $this->assertTrue(is_integer($result));
        }
    }
}
