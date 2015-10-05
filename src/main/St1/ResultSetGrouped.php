<?php
namespace Peppercorn\St1;

class ResultSetGrouped implements \Countable
{
    /**
     * the File this object represents
     * @var File
     */
    private $file;
    
    /**
     * a groupKey-indexed array of ResultsGroup objects
     * @var array
     */
    private $resultsGroups = array();
    
    private $locked = false;
    
    public function __construct(File $file)
    {
        $this->file = $file;
    }
    
    /**
     * get the File
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
    
    public function isLocked()
    {
        return $this->locked;
    }
    
    public function lock()
    {
        if ($this->isLocked()) {
            return;
        }
        
        $this->locked = true;
        foreach ($this->resultsGroups as $resultsGroup /* @var $resultsGroup ResultsGroup */) {
            $resultsGroup->lock();
        }
    }
    
    public function addLine($groupKey, Line $line)
    {
        if ($this->isLocked()) {
            return;
        }
        
        if (!array_key_exists($groupKey, $this->resultsGroups)) {
            $this->resultsGroups[$groupKey] = new ResultsGroup($this->file, $groupKey);
        }
        
        $this->resultsGroups[$groupKey]->addLine($line);
    }
    
    public function getCount()
    {
        return count($this->resultsGroups);
    }
    
    public function count()
    {
        return $this->getCount();
    }
    
    public function getResultsGroupKeys()
    {
        return array_keys($this->resultsGroups);
    }
    
    /**
     * @param mixed $groupKey
     * @return ResultsGroup
     */
    public function getResultsGroup($groupKey)
    {
        return $this->resultsGroups[$groupKey];
    }
}
