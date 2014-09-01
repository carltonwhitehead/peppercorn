<?php
namespace Peppercorn\St1;

abstract class TimeSortTieBreaker extends SortTieBreaker 
{
    
    public function breakTie(Line $a, Line $b)
    {
        $aRuns = $this->getRuns($a);
        $bRuns = $this->getRuns($b);
        $aCount = $aRuns->getCount();
        $bCount = $bRuns->getCount();
        for ($i = 0; $i < $aCount && $i < $bCount; $i++) {
            $aLineForTieBreak = $aRuns->getLine($i);
            $aTime = $this->getTimeForTieBreak($aLineForTieBreak);
            $bLineForTieBreak = $bRuns->getLine($i);
            $bTime = $this->getTimeForTieBreak($bLineForTieBreak);
            if ($aTime < $bTime) {
                $reasonCode = $this->getReasonCodeForTieBreakByTimeDifference(
                    $aLineForTieBreak, $bLineForTieBreak);
                return TieBreak::goesToA($a, $b, $reasonCode);
            } else if ($bTime < $aTime) {
                $reasonCode = $this->getReasonCodeForTieBreakByTimeDifference(
                    $bLineForTieBreak, $aLineForTieBreak);
                return TieBreak::goesToB($b, $a, $reasonCode);
            }
        }
        
        $alphabeticalSort = strnatcmp($a->getDriverName(), $b->getDriverName());
        if ($alphabeticalSort < 0) {
            return TieBreak::goesToA($a, $b, TieBreak::REASON_CODE_NAME);
        } else if ($alphabeticalSort > 0) {
            return TieBreak::goesToB($b, $a, TieBreak::REASON_CODE_NAME);
        } else {
            // TODO: handle case where driver is registered by same name
            // TODO: perhaps sort by time of day of first run
        }
    }

    /**
     * Find the reason code for two runs with tie broken by time difference
     * @param \Peppercorn\St1\Line $winner
     * @param \Peppercorn\St1\Line $loser
     * @return int
     */
    protected function getReasonCodeForTieBreakByTimeDifference(Line $winner, Line $loser)
    {
        if ($winner->isClean() and $loser->isClean()) {
            return TieBreak::REASON_CODE_TIME;
        } else if ($winner->hasConePenalty() or $loser->hasConePenalty()) {
            return TieBreak::REASON_CODE_CONE;
        } else {
            return TieBreak::REASON_CODE_TIME_ARBITRARY;
        }
    }
    
    /**
     * Get all runs by the driver represented in $line
     * @return ResultSetSimple all runs by the same driver
     */
    abstract protected function getRuns(Line $line);
    
    /**
     * Get the time for sort from $line
     * @return numeric
     */
    abstract protected function getTimeForTieBreak(Line $line);
}
