<?php

namespace Peppercorn\St1;

class SortTieBreakerByNextFastestTimePax extends SortTieBreaker
{

    protected static function getRuns(Line $line)
    {
        $query = new Query($line->getFile());
        return $query->where(new WhereDriverIs($line->getDriverCategory(), $line->getDriverClass(), $line->getDriverNumber()))
                ->orderBy(SortTimePaxAscending::getSort())
                ->executeSimple();
    }

    protected static function getTimeForTieBreak(Line $line)
    {
        return $line->getTimePaxForSort();
    }

}
