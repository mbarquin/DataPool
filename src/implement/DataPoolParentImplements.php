<?php
/**
 * Data provider Class parent implementations for PHPUnit Tests
 *
 * (c) Moisés Barquín <moises.barquin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @package    DataPool
 * @subpackage Implements
 * @author     Moises Barquin <moises.barquin@gmail.com>
 * @copyright  (c) 2015, Moisés Barquín <moises.barquin@gmail.com>
 * @version    SVN: $Id$
 */

namespace DataPool\implement;

/**
 * PHPUnit Data provider file
 */
abstract class DataPoolParentImplements
{

    /**
     * Unidimensional Definition array, contains all indexes names
     * @var array
     */
    protected $definition = array();

    /**
     * Array with all values
     * @var array
     */
    protected $dataArray = array();

    /**
     * Tests array keys
     * @var array
     */
    protected $arrKeys = array();

    /**
     * True if returned content must be encapsulated ub an array
     * @var boolean
     */
    protected $returnArray = false;

    /**
     * Sets when the iterator must combine return array with indexes
     * @var boolean
     */
    protected $returnIndexes = true;

    /**
     * Last read position
     * @var integer
     */
    protected $position = 0;


    /**
     * Main constructor
     *
     * @param array $definition Definition array to be combined with data array.
     * @param array $dataArray  Data array to be combined with definition array.
     *
     * @return void
     */
    public function __construct($definition = array(), $dataArray = array())
    {
        if (empty($definition) === true) {
            $this->checkDefinition();
        } else {
            $this->setDefinition($definition);
        }

        if (empty($dataArray) === true) {
            $this->checkDataArray();
        } else {
            $this->setDataArray($dataArray);
        }

        $this->levelizeArray();
    }


    /**
     * Sets up an upper array level if there is only one element
     *
     * @return void
     */
    protected function levelizeArray()
    {
        if (count($this->arrKeys) === 0) {
            $this->arrKeys = array_keys($this->dataArray);
            // If first element is not an array insert it into one.
            if (is_array($this->dataArray[$this->arrKeys[0]]) === false) {
                $this->dataArray = array($this->dataArray);
            } else {
                $this->dataArray = array_values($this->dataArray);
            }
        }

        $this->checkArrayElements();
    }


    /**
     * Sets when the iterator must combine return array with indexes
     *
     * @return boolean
     */
    public function hasToReturnIndexes()
    {
        return $this->returnIndexes;
    }


    /**
     * Sets when the iterator must combine return array with indexes
     *
     * @param boolean $returnIndexes If indexes for each tests case must be setted on return.
     *
     * @return void
     */
    public function setReturnIndexes($returnIndexes = true)
    {
        $this->returnIndexes = $returnIndexes;
    }


    /**
     * True if object returns all params encapsulated in an array
     *
     * @return boolean
     */
    public function hasToReturnArray()
    {
        return $this->returnArray;
    }


    /**
     * Sets when object returns all params encapsulated in an array
     *
     * @param boolean $returnArray Set this value.
     *
     * @return void
     */
    public function setReturnArray($returnArray)
    {
        $this->returnArray = $returnArray;
    }


    /**
     * Returns field index defintion array
     *
     * @return array
     */
    public function getDefinition()
    {
        return $this->definition;
    }


    /**
     * Returns field data array
     *
     * @return array
     */
    public function getDataArray()
    {
        return $this->dataArray;
    }


    /**
     * Checks if definition array is setted
     *
     * @throws \DataPool\DataPoolException If definition array has bad format.
     *
     * @return void
     */
    protected function checkDefinition()
    {
        $message = _('Definition array must be an unidimensional array');
        if (is_array($this->definition) === false || count($this->definition) === 0) {
            throw new \DataPool\DataPoolException($message);
        }

        foreach ($this->definition as $defUnit) {
            if (is_array($defUnit) === true) {
                throw new \DataPool\DataPoolException($message);
            }
        }

    }


    /**
     * Checks if data array contents is an array
     *
     * @throws \DataPool\DataPoolException If data array has bad format.
     *
     * @return void
     */
    protected function checkDataArray()
    {
        $message = _('Data array must be an ARRAY');
        if (is_array($this->dataArray) === false || count($this->dataArray) === 0) {
            throw new \DataPool\DataPoolException($message);
        }
    }


    /**
     * Sets field index defintion array
     *
     * @param array $definition Array with data names to be combined with data.
     *
     * @return void
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
        $this->checkDefinition();
    }


    /**
     * Returns data array
     *
     * @param array $dataArray Array with tests data.
     *
     * @return void
     */
    public function setDataArray($dataArray)
    {
        $this->dataArray = $dataArray;
        $this->checkDataArray();
    }


    /**
     * Returns actual read line number
     *
     * @return integer
     */
    public function getActualLine()
    {
        return $this->arrKeys[$this->position];
    }


    /**
     * Creates data array to be returned
     *
     * @param mixed $position Array Offset.
     *
     * @return array
     */
    protected function composeReturn($position)
    {
        if ($this->hasToReturnArray() === true) {
            return array($this->combineReturn($position));
        }

        return $this->combineReturn($position);
    }


    /**
     * Gets index position on internal array
     *
     * @param mixed $index Test data array related index.
     *
     * @return mixed
     */
    protected function getIndexPosition($index)
    {
        return array_search($index, $this->arrKeys);
    }


    /**
     * Merges data with index array if necessary
     *
     * @param mixed $position Real position to be read on dataArray.
     *
     * @return array
     */
    protected function combineReturn($position)
    {
        if ($this->returnIndexes === true) {
            return array_combine($this->definition, $this->dataArray[$position]);
        }

        return $this->dataArray[$position];
    }
}// End Class.
