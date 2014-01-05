<?php
namespace Peppercorn\St1;

class ResultsGroup implements \Countable
{
    use ResultSetTrait;
    /**
     * the group key shared by each Result
     * @var string
     */
    private $groupKey;
    
    public function __construct(File $file, $groupKey)
    {
        $this->setFile($file);
        $this->groupKey = $groupKey;
    }

    public function count()
    {
        return $this->getCount();
    }
}
