<?php
namespace Peppercorn\St1;

class SortTimeRawAscending implements SortProvider
{
    public static function getSort()
    {
        return function(Line $a, Line $b)
        {
            $aTime = $a->getTimeRawForSort();
            $bTime = $b->getTimeRawForSort();
            if ($aTime === $bTime) {
                return 0;
            }
            return $aTime < $bTime
                ? -1 : 1;
        };
    }
}