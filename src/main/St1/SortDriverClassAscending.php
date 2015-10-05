<?php
namespace Peppercorn\St1;

class SortDriverClassAscending implements SortProvider
{
    public static function getSort()
    {
        return function(Line $a, Line $b)
        {
            return strnatcasecmp($a->getDriverClass(), $b->getDriverClass());
        };
    }
}