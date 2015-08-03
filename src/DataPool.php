<?php
/*
 * Data provider for PHPUnit Tests
 *
 * (c) Moisés Barquín <moises.barquin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DataPool;

/**
 * PHPUnit Data provider file
 *
 * PHP version 5.3
 *
 *
 * @category   DataPool
 * @package    DataPool
 * @author     Moises Barquin <moises.barquin@gmail.com>
 * @version    SVN: $Id$
 */
class DataPool extends \DataPool\implement\DataPoolImplements
{
    

    /**
     * Formatted data Array getter
     *
     * @return boolean|array
     */
    public function getFullDataPool(){
        $aux = array();
        $this->levelizeArray();

        foreach($this->dataArray as $rowKey => $rowArray) {
            $aux[$this->arrKeys[$rowKey]] = array_combine($this->definition, $rowArray);
        }
        return $aux;
    }// End getFullDataPool()


    /**
     * Counts how many rows have a determinated value in a property
     *
     * @param string $property Property name
     * @param mixed  $value    Value to be searched
     * 
     * @throws \DataPool\DataPoolException
     * @return integer
     */
    public function countRowsBy($property, $value) {
        $counter = 0;
        $index = array_search($property, $this->definition);
        if ($index !== false) {
            foreach($this->dataArray as $row) {
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
     * @param integer $index Read position
     *
     * @return boolean|array
     */
    public function getData($index) {
        $position = array_search($index, $this->arrKeys);
        if($position !== false && isset($this->dataArray[$position]) === true) {
            return $this->composeReturn($position);
        }
        return false;
    }// End getDataPool()
    
    
    /**
     * Return array of rows, the row index must contain the 
     *
     * @param mixed   $value   Index Value to be searched
     * @param boolean $indexedReturn Sets if returned array is indexed with test index
     * 
     * @return array
     */
    public function getRowsByIndex($value, $indexedReturn=true) {
        $return = array();
        foreach($this->arrKeys as $index => $key) {
            if (is_string($key) === true && strpos($key, $value) !== false) {
                $this->getRowsByIndexCumul($return, $key, $index, $indexedReturn);
            } elseif ($key === $value) {
                $this->getRowsByIndexCumul($return, $key, $index, $indexedReturn);
            }                
        }
        return $return;
    }

    
    /**
     * Populates getRowsByIndex return array
     * 
     * @param array $return        getRowsByIndex Returned array by reference
     * @param mixed $key           Indexation key
     * @param mixed $index         Position on dataArray
     * @param bool  $indexedReturn If return array must be indexed or not
     * 
     * @return void
     */
    protected function getRowsByIndexCumul(&$return, $key, $index, $indexedReturn) {
        if($indexedReturn === true) {
            $return[$key] = $this->combineReturn($index);
        } else {
            $return[]     = $this->combineReturn($index);
        }
    }
    
    
    /**
     * Datapool indexes array and dataArray elements must
     * have the same elements count
     *
     * @throws \DataPool\DataPoolException
     */
    protected function checkArrayElements() {
        $numFields = count($this->definition);
        foreach( $this->dataArray as $data) {
            if(count($data) !== (int)$numFields) {
                $mess = _('Datapool indexes array and dataArray elements'
                    .' must have the same elements count.');
                throw new \DataPool\DataPoolException($mess);
            }
        }
    }

}// End Class