<?php
namespace Peppercorn\St1;

abstract class SortTieBreaker
{
    /**
     * @param callable $sort
     * @param array $lines
     * @return array
     */
    public final function findAndBreakTies(callable $sort, array &$lines)
    {
        $tiesBroken = [];
        $startI = 0;
        $stopI = count($lines) - 1;
        for ($i = $startI; $i < $stopI; $i++) {
            $a = $lines[$i];
            $b = $lines[$i + 1];
            if ($sort($a, $b) === 0) {
                $tieBreak = $this->breakTie($a, $b);
                if ($tieBreak->wentToB()) {
                    $lines[$i] = $b;
                    $lines[$i + 1] = $a;
                    $i = max(0, $i - 2);
                    continue;
                }
                $tiesBroken[] = $tieBreak;
            }
        }
        return $tiesBroken;
    }
    
    /**
     * 
     * @param Line $a
     * @param Line $b
     * @return TieBreak
     */
    public final function breakTie(Line $a, Line $b)
    {
        $aRuns = static::getRuns($a);
        $bRuns = static::getRuns($b);
        $aCount = $aRuns->getCount();
        $bCount = $bRuns->getCount();
        for ($i = 0; $i < $aCount && $i < $bCount; $i++) {
            $aLineForTieBreak = $aRuns->getLine($i);
            $aTime = static::getTimeForTieBreak($aLineForTieBreak);
            $bLineForTieBreak = $bRuns->getLine($i);
            $bTime = static::getTimeForTieBreak($bLineForTieBreak);
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
    private static function getReasonCodeForTieBreakByTimeDifference(Line $winner, Line $loser)
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
    protected abstract static function getRuns(Line $line);
    
    /**
     * Get the time for sort from $line
     * @return numeric
     */
    protected abstract static function getTimeForTieBreak(Line $line);
}