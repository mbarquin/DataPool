<?php
/**
 * Data provider for PHPUnit Tests
 *
 * (c) Moisés Barquín <moises.barquin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 5.3
 *
 * @package    DataPool
 * @subpackage DataPool
 * @author     Moises Barquin <moises.barquin@gmail.com>
 * @copyright  (c) 2015, Moisés Barquín <moises.barquin@gmail.com>
 * @version    SVN: $Id$
 */

namespace DataPool;

/**
 * PHPUnit Data provider file
 */
class DataPool extends \DataPool\implement\DataPoolImplements
{


    /**
     * Formatted data Array getter
     *
     * @return boolean|array
     */
    public function getFullDataPool()
    {
        $aux = array();
        $this->levelizeArray();

        foreach ($this->dataArray as $rowKey => $rowArray) {
            $aux[$this->arrKeys[$rowKey]] = array_combine($this->definition, $rowArray);
        }

        return $aux;
    }


    /**
     * Counts how many rows have a determinated value in a property
     *
     * @param string $property Property name.
     * @param mixed  $value    Value to be searched.
     *
     * @throws \DataPool\DataPoolException If property is not found on definition.
     * @return integer
     */
    public function countRowsBy($property, $value)
    {
        $counter = 0;
        $index   = array_search($property, $this->definition);
        if ($index !== false) {
            foreach ($this->dataArray as $row) {
                if (isset($row[$index]) === true && strpos($row[$index], $value) !== false) {
                    $counter++;
                }
            }
        } else {
            throw new \DataPool\DataPoolException('Index not defined');
        }

        return $counter;
    }


    /**
     * Formatted data Array getter
     *
     * @param integer $index Read position.
     *
     * @return boolean|array
     */
    public function getData($index)
    {
        $position = array_search($index, $this->arrKeys);
        if ($position !== false && isset($this->dataArray[$position]) === true) {
            return $this->composeReturn($position);
        }

        return false;
    }


    /**
     * Return array of rows, the row index must contain the
     *
     * @param mixed $value Index Value to be searched.
     *
     * @return array
     */
    public function getRowsByIndex($value)
    {
        $return = array();
        foreach ($this->arrKeys as $index => $key) {
            if (is_string($key) === true && strpos($key, $value) !== false) {
                $this->getRowsByIndexCumul($return, $key, $index);
            } elseif ($key === $value) {
                $this->getRowsByIndexCumul($return, $key, $index);
            }
        }

        return $return;
    }


    /**
     * Populates getRowsByIndex return array
     *
     * @param array $return GetRowsByIndex Returned array by reference.
     * @param mixed $key    Indexation key.
     * @param mixed $index  Position on dataArray.
     *
     * @return void
     */
    protected function getRowsByIndexCumul(&$return, $key, $index)
    {
        if ($this->returnIndexes === true) {
            $return[$key] = $this->composeReturn($index);
        } else {
            $return[] = $this->composeReturn($index);
        }

    }


    /**
     * Datapool indexes array and dataArray elements must
     * have the same elements count
     *
     * @throws \DataPool\DataPoolException Wrong definition and data fields number.
     */
    protected function checkArrayElements()
    {
        $numFields = count($this->definition);
        foreach ($this->dataArray as $data) {
            if (count($data) !== (int) $numFields) {
                $mess = _(
                    'Datapool indexes array and dataArray elements'
                    .' must have the same elements count.'
                );
                throw new \DataPool\DataPoolException($mess);
            }
        }

    }
}// End class.
