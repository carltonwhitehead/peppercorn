<?php

namespace Peppercorn\St1;

class SortTieBreakerByNextFastestTimeRaw extends TimeSortTieBreaker
{

    protected function getRuns(Line $line)
    {
        $query = new Query($line->getFile());
        return $query->where(new WhereDriverIs($line->getDriverCategory(), $line->getDriverClass(), $line->getDriverNumber()))
                ->orderBy(SortTimeRawAscending::getSort())
                ->executeSimple();
    }

    protected function getTimeForTieBreak(Line $line)
    {
        return $line->getTimeRawForSort();
    }

}
