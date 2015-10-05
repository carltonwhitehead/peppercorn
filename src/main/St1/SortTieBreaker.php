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
    abstract public function breakTie(Line $a, Line $b);
    
}