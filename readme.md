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
* Use composer

Usage
-----

The main use pourpose is via heritage, the final class will only containd definition
index and dataArray, and we can require it into our test case as dataprovider or as
a normal object which vill provide us with predefined test cases.

DataPool Example:

    namespace testApp\DataPools;
    use DataPool;

    contactsDataPool extends DataPool\DataPool {
        protected $definition = array(
                'id',
                'name',
                'phone1'
            );

        protected $dataArray = array(
            'Test1' => array('', 'Jack', '555999666'),
            'Test2' => array('', 'Mathew', '555888555'),
            'NameEmpty' => array('', '', '555666555'),
            'PhoneToLong' => array('', 'Gregor', '5550005518899')
        );
    }


In our test case:






