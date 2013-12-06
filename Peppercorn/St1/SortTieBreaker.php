<?php
namespace Peppercorn\St1;

interface SortTieBreaker
{
    /**
     * 
     * @param Line $a
     * @param Line $b
     * @return numeric The comparison function must return an integer less than, equal to, or greater than zero if the first argument is considered to be respectively less than, equal to, or greater than the second. 
     */
    public function breakTie(Line $a, Line $b);
}