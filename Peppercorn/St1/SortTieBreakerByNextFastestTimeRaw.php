<?php
namespace Peppercorn\St1;

class SortTieBreakerByNextFastestTimeRaw implements SortTieBreaker
{
    public function breakTie(Line $a, Line $b) {
        $aRuns = $this->getRuns($a);
        $bRuns = $this->getRuns($b);
        $aCount = count($a);
        $bCount = count($b);
        for ($i = 0; $i < $aCount && $i < $bCount; $i++) {
            $aTimeRaw = $aRuns[$i]->getTimeRawForSort();
            $bTimeRaw = $bRuns[$i]->getTimeRawForSort();
            // TODO: figure out why following ifs and elseifs aren't entered
            if ($aTimeRaw < $bTimeRaw) {
                die();
                return -1;
            } else if ($aTimeRaw > $bTimeRaw) {
                die();
                return 1;
            }
        }
        // fall back to sort by driver name in case all lines are identical (ie drivers w/ only DNFs)
        return strnatcmp($a->getDriverName(), $b->getDriverName());
    }
    
    private function getRuns(Line $line) {
        $query = new Query($line->getFile());
        return $query->where(new WhereDriverIs($line->getDriverCategory(), $line->getDriverClass(), $line->getDriverNumber()))
            ->orderBy(SortTimeRawAscending::getSort())
            ->execute();
    }
}