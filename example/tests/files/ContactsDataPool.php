<?php

/**
 * Domains datapool file for Testing pourposes
 *
 * PHP version 5.4
 *
 *
 * @category   DataPool
 * @package    Contacts
 * @author     Moises Barquin <moises.barquin@gmail.com>
 * @version    SVN: $Id$
 */

namespace Example\tests\files;

/**
 * Contacts datapool file for Testing pourposes
 */
class ContactsDataPool extends \DataPool\DataPool {

    /**
     * @var array Datapoolm fields definition to be merged with data
     */
    protected $definition = array(
        'name',
        'surname',
        'phone'
    );

    /**
     * @var array Data array to be merged with definition and returned to tests 
     */
    public $dataArray = array(
        'Test1' => array('Jack', 'Travis', '555999666', true),
        'Test2' => array('Mathew', 'Jones', '555888555', true),
        'NameSurnameEmpty' => array('', '', '555666555', false),
        'PhoneToLong' => array('Gregor', 'Jones', '5550005518899', false)
    );

}