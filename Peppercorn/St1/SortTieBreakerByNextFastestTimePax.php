<?php
namespace Peppercorn\St1;

class SortTieBreakerByNextFastestTimePax implements SortTieBreaker
{
    public function breakTie(Line $a, Line $b) {
        $aRuns = self::getRuns($a);
        $bRuns = self::getRuns($b);
        $aCount = $aRuns->getCount();
        $bCount = $bRuns->getCount();
        for ($i = 0; $i < $aCount && $i < $bCount; $i++) {
            $aTimePax = $aRuns->getLine($i)->getTimePaxForSort();
            $bTimePax = $bRuns->getLine($i)->getTimePaxForSort();
            if ($aTimePax < $bTimePax) {
                return -1;
            } else if ($bTimePax < $aTimePax) {
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
            ->orderBy(SortTimePaxAscending::getSort())
            ->executeSimple();
    }
}