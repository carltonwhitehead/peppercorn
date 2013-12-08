<?php
namespace Peppercorn\St1;

use Phava\Base\Preconditions;
class Result
{
    /**
     * the Line backing this result
     * @var Line
     */
    private $line;
    /**
     * the 1-indexed place of this Result
     * @var integer
     */
    private $place;
    
    public function __construct(Line $line, $i)
    {
        $this->line = $line;
        $this->place = $i + 1;
    }
    
    /**
     * get the Line represented by this Result
     * @return \Peppercorn\St1\Line
     */
    public function getLine()
    {
        return $this->line;
    }
    
    /**
     * get the 1-indexed place of this Result
     * @return number
     */
    public function getPlace()
    {
        return $this->place;
    }
    
}