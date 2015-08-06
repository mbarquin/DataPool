DataPool
========

Introduction
------------
This library is intended to be used with PHPUnit tool. It provides a little
more control on dataproviders, allowing use the DataPool as iterable object
for standard @dataprovider tag or to be used in individual tests for getting
all necessary data sets.

Installation
------------

You can install the component in the following ways:

* Use the official Github repository (https://github.com/mbarquin/DataPool)
* Use composer : 

Usage
-----

The main use pourpose is via heritage, the final class will only containd definition
index and dataArray, and we can require it into our test case as dataprovider or as
a normal object which vill provide us with predefined test cases.

DataPool Example:

    /**
     * Contacts datapool file for Testing pourposes
     */
    class ContactsDataPool extends \DataPool\DataPool
    {

        /**
         * @var array Datapool definition
         */
        protected $definition = array(
                'name',
                'surname',
                'phone',
                'result'
            );

        public $dataArray = array(
            'Test1' => array('Jack', 'Travis', '555999666', true),
            'Test2' => array('Mathew', 'Jones', '555888555', true),
            'NameSurnameEmpty' => array('', '', '555666555', false),
            'PhoneToLong' => array('Gregor', 'Jones', '5550005518899', false)
        );

    }


In our test case:

    namespace Example\tests\src;

    class exampleContactsModelTest extends \PHPUnit_Framework_TestCase {

        static private $dataPool = null;


        /**
         * Static instance of ContactsDataPool
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
 
