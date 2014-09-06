<?php

namespace Peppercorn\St1;

class SortTieBreakerByNextFastestTimePax extends TimeSortTieBreaker
{
    
    private $runCache = array();

    protected function getRuns(Line $line)
    {
        $key = $line->getDriverClassRaw() . "_" . $line->getDriverNumber();
        $runs;
        if (isset($this->runCache[$key])) {
            $runs = $this->runCache[$key];
        } else {
            $query = new Query($line->getFile());
            $runs = $query->where(new WhereDriverIs($line->getDriverCategory(), $line->getDriverClass(), $line->getDriverNumber()))
                    ->orderBy(SortTimePaxAscending::getSort())
                    ->executeSimple();
            $this->runCache[$key] = $runs;
        }
        return $runs;
    }

    protected function getTimeForTieBreak(Line $line)
    {
        return $line->getTimePaxForSort();
    }

}
