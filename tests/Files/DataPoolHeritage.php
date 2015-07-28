<?php
namespace TestsDataPool\Files;

class DataPoolHeritage extends \DataPool\DataPool {
    protected $definition = array('VALUE1', 'VALUE2');
    public $dataArray = array(
        'Test_1' => array('1.1', '1.2'),
        'Test_2' => array('2.1', '2.2'),
        'Test_3' => array('3.1', '3.2')
    );
}