<?php
namespace Peppercorn\St1;

use Phava\Base\Preconditions;
class ResultSetSimple implements \Countable
{
    /**
     * the File this object represents
     * @var File
     */
    private $file;
    
    /**
     * a 0-indexed array of Result objects
     * @var array
     */
    private $results;
    
    public function __construct(File $file, $lines)
    {
        $this->file = $file;
        $this->results = array();
        foreach ($lines as $i => $line) {
            $this->results[] = new Result($line, $i);
        }
    }
    
    /**
     * get the File this ResultSetSimple represents
     * @return \Peppercorn\St1\File
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * get the count of Result objects
     * @return number
     */
    public function getCount()
    {
        return count($this->results);
    }
    
    public function count()
    {
        return $this->getCount();
    }
    
    /**
     * Get the Result at index $i from the 0-indexed backing array
     * @param int $i
     * @return \Peppercorn\St1\Result
     */
    public function getIndex($i)
    {
        return $this->results[$i];
    }
    
    /**
     * Get the Result at 1-indexed $place
     * This is a convenience to encapsulate the 1-indexed nature of event result positions vs PHP's 0-indexed arrays
     * @param unknown $place
     * @return \Peppercorn\St1\Result
     */
    public function getPlace($place)
    {
        return $this->results[$place - 1];
    }
    
    /**
     * Get the Line of the result at $i in the 0-indexed backing array
     * @param int $i
     * @return \Peppercorn\St1\Line
     */
    public function getLine($i)
    {
        return $this->results[$i]->getLine();
    }
}