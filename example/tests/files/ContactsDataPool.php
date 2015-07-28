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
class ContactsDataPool extends \DataPool\DataPool
{

    /**
     * @var array Datapool definition
     */
    protected $definition = array(
            'name',
            'surname',
            'phone'
        );

    public $dataArray = array(
        'Test1' => array('Jack', 'Travis', '555999666'),
        'Test2' => array('Mathew', 'Jones', '555888555'),
        'NameSurnameEmpty' => array('', '', '555666555'),
        'PhoneToLong' => array('Gregor', 'Jones', '5550005518899')
    );

}// End Class

