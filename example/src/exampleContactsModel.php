<?php
/**
 * Example fake model class file
 *
 * PHP version 5.3
 *
 *
 * @category   DataPool
 * @package    DataPool
 * @author     Moises Barquin <moises.barquin@gmail.com>
 * @version    SVN: $Id$
 */
namespace Example\src;

/**
 * Example fake model class for PHPUnit Testing
 */
class exampleContactsModel
{
    /**
     * @var array Data arrays store
     */
    private $data = array();


    /**
     * Emulates a validation and insert function
     *
     * @param array $regArray Array with data to be stored
     *
     * @return boolean|integer
     */
    public function insert($regArray) {
        if (is_array($regArray) === false) {
            return false;
        }
        // Name Mandatory
        if (isset($regArray['name']) === false ||
            empty($regArray['name']) === true) {
            return false;
        }
        if (isset($regArray['surname']) === false) {
            return false;
        }

        // Phone mandatory
        if (isset($regArray['phone']) === false
            || is_numeric($regArray['phone']) === false
            || strlen($regArray['phone']) > 9) {
            return false;
        }

        $data[] = $regArray;

        // Returns register ID
        return count($data);
    }
}