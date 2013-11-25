<?php
namespace Peppercorn\St1;

class SortDriverNameAscending implements SortProvider
{
    public function getSort()
    {
        return function(Line $a, Line $b)
        {
            return strnatcasecmp($a->getDriverName(), $b->getDriverName());
        };
    }
}