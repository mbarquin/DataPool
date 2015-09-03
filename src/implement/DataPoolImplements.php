<?php
/**
 * Data provider Class implementations for PHPUnit Tests
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
 * @version    GIT: $Id$
 */

namespace DataPool\implement;

/**
 * PHPUnit Data provider file
 */
abstract class DataPoolImplements extends \DataPool\implement\DataPoolParentImplements implements \Iterator, \Countable, \ArrayAccess
{

    /**
     * Returns how many elements we have
     *
     * @return integer
     */
    public function count()
    {
        $this->levelizeArray();
        return count($this->dataArray);
    }


    /**
     * Reinits array position
     *
     * @return void
     */
    public function rewind()
    {
        $this->levelizeArray();
        $this->position = 0;
    }


    /**
     * Gets actual row
     *
     * @return array
     */
    public function current()
    {
        return array_combine($this->definition, $this->dataArray[$this->position]);
    }


    /**
     * Gets actual pointer position
     *
     * @return mixed
     */
    public function key()
    {
        return $this->arrKeys[$this->position];
    }


    /**
     * Next row
     *
     * @return void
     */
    public function next()
    {
        $this->position++;
    }


    /**
     * Returns true if actual position element is setted
     *
     * @return boolean
     */
    public function valid()
    {
        if (isset($this->dataArray[$this->position]) === true) {
            return true;
        } else {
            return false;
        }

    }


    /**
     * ArrayAccess implementation, but our pool is readOnly
     *
     * @param mixed $offset Declared to keep consistency but not used.
     * @param mixed $value  Declared to keep consistency but not used.
     *
     * @throws \DataPool\DataPoolException Via array access class is read-only.
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $message = _('DataPool array access is readOnly');
        throw new \DataPool\DataPoolException($message);
    }


    /**
     * Checks if that position is defined in the dataArray
     *
     * @param mixed $offset Array key to be obtained from array.
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        $index = $this->getIndexPosition($offset);
        return isset($this->dataArray[$index]);
    }


    /**
     * Deletes one dataArray entry
     *
     * @param mixed $offset Array key to be obtained from array.
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $index = $this->getIndexPosition($offset);
        unset($this->dataArray[$index]);
    }


    /**
     * Returns a part of dataArray
     *
     * @param mixed $offset Array key to be obtained from array.
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $index = $this->getIndexPosition($offset);
        if ($index === false) {
            return null;
        }

        if (isset($this->dataArray[$index]) === true) {
            return $this->composeReturn($index);
        } else {
            return null;
        }
    }
}// End Class.
