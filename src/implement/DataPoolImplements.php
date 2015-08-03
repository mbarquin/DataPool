<?php

/*
 * Data provider Class implementations for PHPUnit Tests
 *
 * (c) Moisés Barquín <moises.barquin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DataPool\implement;

/**
 * PHPUnit Data provider file
 *
 * PHP version 5.3
 *
 *
 * @category   DataPool
 * @package    Implements
 * @author     Moises Barquin <moises.barquin@gmail.com>
 * @version    SVN: $Id$
 */
abstract class DataPoolImplements implements \Iterator, \Countable, \ArrayAccess {

    /**
     * @var array Unidimensional Definition array, contains all indexes names
     */
    protected $definition = array();

    /**
     * @var array Array with all values
     */
    protected $dataArray = array();

    /**
     * @var int Last read position
     */
    protected $position = 0;

    /**
     * @var array Tests array keys
     */
    protected $arrKeys = array();

    /**
     *
     * @var bool True if returned content must be encapsulated ub an array
     */
    protected $returnArray = false;

    /**
     * @var bool Sets when the iterator must combine return array with indexes
     */
    protected $returnIndexes = true;

    /**
     * main constructor
     *
     * @param array $definition Definition array to be combined with data array
     * @param array $dataArray Definition array to be combined with data array
     */
    public function __construct($definition = array(), $dataArray = array()) {
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
     * @throws \Exception
     * @return void
     */
    protected function levelizeArray() {
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
     * @return bool
     */
    public function getReturnIndexes() {
        return $this->returnIndexes;
    }

    
    /**
     * Sets when the iterator must combine return array with indexes
     *
     * @param bool $returnIndexes
     */
    public function setReturnIndexes($returnIndexes = true) {
        $this->returnIndexes = $returnIndexes;
    }

    
    /**
     * True if object returns all params encapsulated in an array
     *
     * @return bool
     */
    public function getReturnArray() {
        return $this->returnArray;
    }

    
    /**
     * Sets when object returns all params encapsulated in an array
     *
     * @param bool $returnArray Set this value
     */
    public function setReturnArray($returnArray) {
        $this->returnArray = $returnArray;
    }

    
    /**
     * Returns field index defintion array
     *
     * @return array
     */
    public function getDefinition() {
        return $this->definition;
    }

    
    /**
     * Returns field data array
     *
     * @return array
     */
    public function getDataArray() {
        return $this->dataArray;
    }

    
    /**
     * Checks if definition array is setted
     *
     * @throws \DataPool\DataPoolException
     */
    protected function checkDefinition() {
        $message = _('Definition array must be an unidimensional array');
        if (is_array($this->definition) === false) {
            throw new \DataPool\DataPoolException($message);
        }

        if (count($this->definition) === 0) {
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
     */
    protected function checkDataArray() {
        $message = _('Data array must be an ARRAY');
        if (is_array($this->dataArray) === false) {
            throw new \DataPool\DataPoolException($message);
        }
        if (count($this->dataArray) === 0) {
            throw new \DataPool\DataPoolException($message);
        }
    }

    
    /**
     * Sets field index defintion array
     *
     * @return array
     */
    public function setDefinition($definition) {
        $this->definition = $definition;
        $this->checkDefinition();
    }

    
    /**
     * Returns data array
     *
     * @param array
     */
    public function setDataArray($dataArray) {
        $this->dataArray = $dataArray;
        $this->checkDataArray();
    }

    
    /**
     * Returns how many elements we have
     *
     * @return integer
     */
    public function count() {
        $this->levelizeArray();
        return count($this->dataArray);
    }

    
    /**
     * Reinits array position
     */
    public function rewind() {
        $this->levelizeArray();
        $this->position = 0;
    }

    
    /**
     * Gets actual row
     *
     * @return array
     */
    public function current() {
        return array_combine($this->definition, $this->dataArray[$this->position]);
    }


    /**
     * Gets actual pointer position
     *
     * @return mixed
     */
    public function key() {
        return $this->arrKeys[$this->position];
    }
    

    /**
     * Next row
     *
     * @return boolean
     */
    public function next() {
        $this->position++;
    }
    

    /**
     * Returns true if actual position element is setted
     *
     * @return boolean
     */
    public function valid() {
        if (isset($this->dataArray[$this->position]) === true) {
            return true;
        } else {
            return false;
        }
    }
    

    /**
     * Returns actual read line number
     *
     * @return int
     */
    public function getActualLine() {
        return $this->arrKeys[$this->position];
    }
    

    /**
     * ArrayAccess implementation, but our pool is readOnly
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @throws \DataPool\DataPoolException
     */
    public function offsetSet($offset, $value) {
        $message = _('DataPool array access is readOnly');
        throw new \DataPool\DataPoolException($message);
    }

    
    /**
     * Checks if that position is defined in the dataArray
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset) {
        $index = $this->getIndexPosition($offset);
        return isset($this->dataArray[$index]);
    }

    
    /**
     * Deletes one dataArray entry
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset) {
        $index = $this->getIndexPosition($offset);
        unset($this->dataArray[$index]);
    }

    
    /**
     * Returns a part of dataArray
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset) {
        $index = $this->getIndexPosition($offset);
        if ($index === false) {
            return null;
        }
        if (isset($this->dataArray[$index])) {
            return $this->composeReturn($index);
        }else{
            return null;
        }
    }
    
    /**
     * Creates data array to be returned
     * 
     * @param mixed $position Index in array
     * 
     * @return array
     */
    protected function composeReturn($position) {
        if($this->getReturnArray() === true) {
            if($this->returnIndexes===true) {
                return array(
                    $this->arrKeys[$position] => $this->combineReturn($position)
                );
            }
            return array($this->combineReturn($position));
        }
        return $this->combineReturn($position);
    }
    
    /**
     * Merges data with index array if necessary
     * 
     * @param type $position
     * @return type
     */
    protected function combineReturn($position){
        if($this->returnIndexes===true) {
            return array_combine($this->definition, $this->dataArray[$position]);
        } else {
            return $this->dataArray[$position];
        }
    }

    
    /**
     * Gets index position on internal array
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    protected function getIndexPosition($offset) {
        return array_search($offset, $this->arrKeys);
    }

}

// End Class