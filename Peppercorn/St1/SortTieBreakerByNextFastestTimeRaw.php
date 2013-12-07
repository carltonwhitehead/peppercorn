<?php
namespace Peppercorn\St1;

class SortTieBreakerByNextFastestTimeRaw implements SortTieBreaker
{
    public function breakTie(Line $a, Line $b) {
        $aRuns = self::getRuns($a);
        $bRuns = self::getRuns($b);
        $aCount = $aRuns->getCount();
        $bCount = $bRuns->getCount();
        for ($i = 0; $i < $aCount && $i < $bCount; $i++) {
            $aTimeRaw = $aRuns->getLine($i)->getTimeRawForSort();
            $bTimeRaw = $bRuns->getLine($i)->getTimeRawForSort();
            if ($aTimeRaw < $bTimeRaw) {
                return -1;
            } else if ($bTimeRaw < $aTimeRaw) {
                return 1;
            }
        }
        // fall back to sort by driver name in case all lines are identical (ie drivers w/ only DNFs)
        return strnatcmp($a->getDriverName(), $b->getDriverName());
    }
    
    /**
     * @param Line $line
     * @return \Peppercorn\St1\ResultSetSimple
     */
    private static function getRuns(Line $line) {
        $query = new Query($line->getFile());
        return $query->where(new WhereDriverIs($line->getDriverCategory(), $line->getDriverClass(), $line->getDriverNumber()))
            ->orderBy(SortTimeRawAscending::getSort())
            ->executeSimple();
    }
}