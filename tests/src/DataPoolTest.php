<?php
/**
 * DataPool object tests file
 */
namespace TestDataPool\src;

class DataPoolTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \DataPool\DataPool Static reference to dataPool in use
     */
    static private $dataPool = null;


    /**
     * @param array $definition Definition array to be combined with data array
     * @param array $dataArray  Definition array to be combined with data array
     *
     * @return \DataPool\DataPool|null
     */
    public function getDataPool($definition = array(), $dataArray = array()) {
        if(empty($definition) === true || empty($dataArray) === true) {
            return self::$dataPool;
        } else {
            self::$dataPool = new \DataPool\DataPool($definition, $dataArray);
        }

        return self::$dataPool;
    }


    /**
     * @expectedException \DataPool\DataPoolException
     */
    public function test__constructExceptionIndexEmpty() {
        $dataPool = new \DataPool\DataPool(array(), array('test', 'test1'));
    }

    /**
     * @expectedException \DataPool\DataPoolException
     */
    public function test__constructExceptionIndexNoArray() {
        $dataPool = new \DataPool\DataPool(1, array('test', 'test1'));
    }


    /**
     * @expectedException \DataPool\DataPoolException
     */
    public function test__constructExceptionIndexMultilevel() {
        $dataPool = new \DataPool\DataPool(array('test', array('test1')), array('test', 'test1'));
    }


    /**
     * @expectedException \DataPool\DataPoolException
     */
    public function test__constructExceptionDataEmpty() {
        $dataPool = new \DataPool\DataPool(array('test', 'test1'), array());
    }


    /**
     * @expectedException \DataPool\DataPoolException
     */
    public function test__constructExceptionDataNoArray() {
        $dataPool = new \DataPool\DataPool(array('test', 'test1'), 1);
    }


    /**
     * @expectedException \DataPool\DataPoolException
     */
    public function test__constructExceptionDistinctElements() {
        $dataPool = new \DataPool\DataPool(
            array('test', 'test1'), array('test')
            );
    }


    public function testLevelizeComposeReturn() {
        $definition = array('first', 'secod', 'third');
        $dataArray  = array('first', 'secod', 'third');

        $dataPool   = $this->getDataPool($definition, $dataArray);

        $data       = $dataPool->getData(0);
        $this->assertAttributeCount(1, 'dataArray', $dataPool);
        $this->assertCount(3, $data);
    }


    public function testComposeReturnArrayTrue() {
        $dataPool = $this->getDataPool();
        $dataPool->setReturnArray(true);
        $data = $dataPool->getData(0);
        $this->assertCount(1, $data);
        $this->assertCount(3, $data[0]);
    }


    public function testHeritage() {
        $extDataPool = new \TestsDataPool\Files\DataPoolHeritage();

        $this->assertAttributeCount(2, 'definition', $extDataPool);
        $this->assertAttributeCount(3, 'dataArray', $extDataPool);
    }


    public function test__construct() {
        $definition = array('VALUE1', 'VALUE2');
        $dataArray = array(
            'Test_1' => array('1.1', '1.2'),
            'Test_2' => array('2.1', '2.2'),
            'Test_3' => array('3.1', '3.2')
        );

        $dataPool = $this->getDataPool($definition, $dataArray);
        $this->assertAttributeCount(2, 'definition', $dataPool);
        $this->assertAttributeCount(3, 'dataArray', $dataPool);

    }


    /**
     * @depends test__construct
     */
    public function testGetFullDataPool() {
        $dataPool  = $this->getDataPool();
        $dataArray = $dataPool->getFullDataPool();

        // Check elements
        $this->assertCount(3, $dataArray);
        // Checks Reindexation
        $this->assertArrayHasKey('Test_2', $dataArray);
        $this->assertCount(2, $dataArray['Test_2']);
        $this->assertArrayHasKey('VALUE1', $dataArray['Test_2']);

        //Check Contents
        $this->assertEquals('2.1', $dataArray['Test_2']['VALUE1']);
        $this->assertEquals('3.2', $dataArray['Test_3']['VALUE2']);
    }


    /**
     * @depends test__construct
     */
    public function testGetData() {
        $dataPool  = $this->getDataPool();
        $return    = $dataPool->getData('Test_2');

        $this->assertArrayHasKey('VALUE1', $return);
        $this->assertArrayHasKey('VALUE2', $return);
        $this->assertEquals($return['VALUE1'], '2.1');
    }


    /**
     * @depends test__construct
     */
    public function testGetDataUnknown() {
        $dataPool  = $this->getDataPool();
        $return    = $dataPool->getData('Test_NOEX');

        $this->assertFalse($return);
    }


    /**
     * @depends test__construct
     */
    public function testCount() {
        $dataPool  = $this->getDataPool();
        $this->assertEquals(3, $dataPool->count());
    }


    /**
     * @depends test__construct
     */
    public function testNext() {
        $dataPool  = $this->getDataPool();
        $this->assertAttributeEquals(0, 'position', $dataPool);
        $dataPool->next();
        $this->assertAttributeEquals(1, 'position', $dataPool);
    }


    /**
     * @depends testNext
     */
    public function testRewindPosition() {
        $dataPool  = $this->getDataPool();
        $dataPool->next();
        $this->assertAttributeEquals(2, 'position', $dataPool);

        $dataPool->next();
        $dataPool->rewind();
        $this->assertAttributeEquals(0, 'position', $dataPool);
    }


    /**
     * @depends testRewindPosition
     */
    public function testCurrent() {
        $dataPool  = $this->getDataPool();
        $dataPool->next();
        $dataArray = $dataPool->current();
        $this->assertEquals( '2.1', $dataArray['VALUE1']);
        $this->assertEquals( '2.2', $dataArray['VALUE2']);
    }


    /**
     * @depends testCurrent
     */
    public function testKey() {
        $dataPool  = $this->getDataPool();
        $key = $dataPool->key();
        $this->assertEquals('Test_2', $key);

    }


    /**
     * @depends testCurrent
     */
    public function testValid() {
        $dataPool  = $this->getDataPool();
        $valid = $dataPool->valid();
        $this->assertEquals(true, $valid);
        $dataPool->next();
        $dataPool->next();
        $valid = $dataPool->valid();
        $this->assertEquals(false, $valid);
        $dataPool->rewind();
        $dataPool->next();
    }


    /**
     * @depends testCurrent
     */
    public function testActualLine() {
        $dataPool  = $this->getDataPool();
        $key = $dataPool->getActualLine();
        $this->assertEquals('Test_2', $key);
    }


    /**
     * @depends test__construct
     */
    public function testGetReturnIndexed() {
        $dataPool  = $this->getDataPool();
        $this->assertAttributeEquals(true, 'returnIndexes', $dataPool);
        $this->assertTrue($dataPool->hasToReturnIndexes());
    }


    /**
     * @depends testGetReturnIndexed
     */
    public function testSetGetReturnIndexed() {
        $dataPool  = $this->getDataPool();
        $this->assertAttributeEquals(true, 'returnIndexes', $dataPool);
        $dataPool->setReturnIndexes(false);
        $this->assertAttributeEquals(false, 'returnIndexes', $dataPool);
        $this->assertFalse($dataPool->hasToReturnIndexes());
    }


    /**
     * @depends test__construct
     */
    public function testSetReturnArray() {
        $dataPool  = $this->getDataPool();
        $this->assertAttributeEquals(false, 'returnArray', $dataPool);
        $dataPool->setReturnArray(true);
        $this->assertAttributeEquals(true, 'returnArray', $dataPool);
    }


    /**
     * @depends testSetReturnArray
     */
    public function testGetReturnArray() {
        $dataPool  = $this->getDataPool();
        $this->assertAttributeEquals(true, 'returnArray', $dataPool);
        $this->assertTrue($dataPool->hasToReturnArray());
        $dataPool->setReturnArray(false);
        $this->assertAttributeEquals(false, 'returnArray', $dataPool);
        $this->assertFalse($dataPool->hasToReturnArray());
    }


    /**
     * @depends test__construct
     */
    public function testGetDefinition() {
        $dataPool  = $this->getDataPool();
        $definition = $dataPool->getDefinition();
        $this->assertAttributeEquals($definition, 'definition', $dataPool);
    }


    /**
     * @depends test__construct
     */
    public function testGetDataArray() {
        $dataPool  = $this->getDataPool();
        $dataArray = $dataPool->getDataArray();
        $this->assertAttributeEquals($dataArray, 'dataArray', $dataPool);
    }

    /**
     * @expectedException \DataPool\DataPoolException
     */
    public function testCountRowsByException() {
        $dataPool  = $this->getDataPool();
        $this->assertTrue($dataPool->countRowsBy('a', 'b'));
    }

    /**
     * @depends test__construct
     */
    public function testCountRowsBy() {
        $dataPool  = $this->getDataPool();
        $this->assertEquals(3, $dataPool->countRowsBy('VALUE1', '.1'));
        $this->assertEquals(1, $dataPool->countRowsBy('VALUE2', '2.'));

    }

    /**
     * @depends test__construct
     */
    public function testGetRowsByIndexIndexed() {
        $dataPool  = $this->getDataPool();
        $dataPool->setReturnIndexes(true);
        $arrResult = $dataPool->getRowsByIndex('Tes');
        $this->assertCount(3, $arrResult);
        $this->assertArrayHasKey('Test_2', $arrResult);
        $this->assertEquals('2.1', $arrResult['Test_2']['VALUE1']);
    }

    /**
     * @depends test__construct
     */
    public function testGetRowsByIndexNotIndexed() {
        $dataPool  = $this->getDataPool();
        $dataPool->setReturnIndexes(false);
        $arrResult = $dataPool->getRowsByIndex('Tes');
        $this->assertCount(3, $arrResult);
        $this->assertArrayNotHasKey('Test_2', $arrResult);
        $this->assertEquals('2.1', $arrResult[1][0]);
    }

    /**
     * @depends test__construct
     */
    public function testGetRowsByIndexNumeric() {
        $dataPool  = new \DataPool\DataPool(array('a'), array(array(53), array(23), array(53)));
        $arrResult = $dataPool->getRowsByIndex(2);
        $this->assertCount(1, $arrResult);
    }

    /**
     * @depends test__construct
     */
    public function testRowsOnUniqueArray() {
        $dataPool  = $this->getDataPool();
        $dataPool->setReturnIndexes(true);
        $dataPool->setReturnArray(true);
        $arrResult = $dataPool->getRowsByIndex('Test_2');
        $this->assertCount(1, $arrResult['Test_2']);
        $this->assertEquals('2.1', $arrResult['Test_2'][0]['VALUE1']);
    }

    /**
     * @depends test__construct
     */
    public function testArrayAccess() {
        $dataPool  = $this->getDataPool();
        $dataPool->setReturnArray(false);
        $dataPool->setReturnIndexes(false);
        
        $this->assertCount(2, $dataPool['Test_1']);
        $this->assertEquals('2.1', $dataPool['Test_2'][0]);
        $dataPool->offsetUnset('Test_2');
        $this->assertNull($dataPool['Test_2']);
        $this->assertFalse($dataPool->offsetExists('Test_2'));
        $this->assertNull($dataPool['Test_KO']);
    }


    /**
     * @depends test__construct
     * @expectedException \DataPool\DataPoolException
     */
    public function testArrayReadOnly() {
        $dataPool  = $this->getDataPool();
        $dataPool['Test_1'] = array();
    }


    /**
     * @depends test__construct
     * @expectedException \DataPool\DataPoolException
     */
    public function testArrayReadOnlyNotNew() {
        $dataPool  = $this->getDataPool();
        $dataPool['Test_NEW'] = array();
    }

}
