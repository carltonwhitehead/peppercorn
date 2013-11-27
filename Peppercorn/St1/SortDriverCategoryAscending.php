<?php
namespace Peppercorn\St1;

class SortDriverCategoryAscending implements SortProvider
{
    public static function getSort()
    {
        return function(Line $a, Line $b)
        {
            return strnatcmp($a->getDriverCategory()->getPrefix(), $b->getDriverCategory()->getPrefix());
        };
    }
}