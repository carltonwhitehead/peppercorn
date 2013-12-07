<?php
namespace Peppercorn\St1;

use Phava\Base\Preconditions;
class ResultSetSimple
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
     * @return File
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
    
    /**
     * Get the Result at index $i from the 0-indexed backing array
     * @param int $i
     * @return Result
     */
    public function getIndex($i)
    {
        return $this->results[$i];
    }
    
    /**
     * Get the Result at 1-indexed $place
     * This is a convenience to encapsulate the 1-indexed nature of event result positions vs PHP's 0-indexed arrays
     * @param unknown $place
     * @return Result
     */
    public function getPlace($place)
    {
        return $this->results[$place - 1];
    }
}