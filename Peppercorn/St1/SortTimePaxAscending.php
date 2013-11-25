<?php
namespace Peppercorn\St1;

class SortTimePaxAscending implements SortProvider
{
    public static function getSort()
    {
        return function(Line $a, Line $b)
        {
            $aTime = $a->getTimePaxForSort();
            $bTime = $b->getTimePaxForSort();
            if ($aTime === $bTime) {
                return 0;
            }
            return $aTime < $bTime
                ? -1 : 1;
        };
    }
}