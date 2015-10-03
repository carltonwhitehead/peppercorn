<?php
namespace Peppercorn\St1;

trait ResultSetTrait
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
    private $results = array();
    
    private $locked = false;
    
    protected function buildResults(array $lines)
    {
        if ($this->isLocked()) {
            return;
        }
        
        foreach ($lines as $i => $line) {
            $this->addLine($line, $i);
        }
    }
    
    public function addLine(Line $line)
    {
        if ($this->isLocked()) {
            return;
        }
        
        $i = count($this->results);
        $this->results[] = new Result($line, $i);
    }
    
    protected function setFile(File $file)
    {
        if ($this->isLocked()) {
            return;
        }
        
        $this->file = $file;
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
     * @param int $place
     * @return Result
     */
    public function getPlace($place)
    {
        return $this->results[$place - 1];
    }
    
    /**
     * Get the Line of the result at $i in the 0-indexed backing array
     * @param int $i
     * @return Line
     */
    public function getLine($i)
    {
        return $this->results[$i]->getLine();
    }
    
    public function isLocked()
    {
        return $this->locked;
    }
    
    /**
     * Lock the object in its current state
     */
    public function lock()
    {
        $this->locked = true;
    }
}
