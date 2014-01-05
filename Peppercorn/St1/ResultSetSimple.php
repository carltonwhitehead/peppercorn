<?php
namespace Peppercorn\St1;

class ResultSetSimple implements \Countable
{
    use ResultSetTrait;

    public function __construct(File $file, $lines)
    {
        $this->setFile($file);
        $this->buildResults($lines);
        $this->lock();
    }
    
    public function count()
    {
        return $this->getCount();
    }
}