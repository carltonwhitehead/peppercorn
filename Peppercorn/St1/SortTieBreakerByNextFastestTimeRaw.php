<?php
namespace Peppercorn\St1;

class SortTieBreakerByNextFastestTimeRaw implements SortTieBreaker
{
    public function breakTie(Line $a, Line $b) {
        $aRuns = self::getRuns($a);
        $bRuns = self::getRuns($b);
        $aCount = count($aRuns);
        $bCount = count($bRuns);
        for ($i = 0; $i < $aCount && $i < $bCount; $i++) {
            $aTimeRaw = $aRuns[$i]->getTimeRawForSort();
            $bTimeRaw = $bRuns[$i]->getTimeRawForSort();
            if ($aTimeRaw < $bTimeRaw) {
                return -1;
            } else if ($bTimeRaw < $aTimeRaw) {
                return 1;
            }
        }
        // fall back to sort by driver name in case all lines are identical (ie drivers w/ only DNFs)
        return strnatcmp($a->getDriverName(), $b->getDriverName());
    }
    
    private static function getRuns(Line $line) {
        $query = new Query($line->getFile());
        return $query->where(new WhereDriverIs($line->getDriverCategory(), $line->getDriverClass(), $line->getDriverNumber()))
            ->orderBy(SortTimeRawAscending::getSort())
            ->execute();
    }
}