<?php
namespace Peppercorn\St1;

use Phava\Base\Preconditions;
class TieBreak
{
    /**
     * Tie broken between runs where both tie breaking runs were clean.
     */
    const REASON_CODE_TIME = 0;
    /**
     * Tie broken between runs where a cone penalty was involved on the tie breaking run.
     */
    const REASON_CODE_CONE = 1;
    /**
     * Tie broken between runs where arbitrary sort difference was the cause of the tie break. ie DNF vs RRN
     */
    const REASON_CODE_TIME_ARBITRARY = 2;
    /**
     * Tie broken between runs failed over to alphabetical sort by name. ie DNF vs DNF
     */
    const REASON_CODE_NAME = 3;

    private static $WINNER_A = -1;
    private static $WINNER_B = 1;
    
    /**
     * the outcome of the sort oper
     * @var int
     */
    private $sortOutcome;
    
    /**
     * the winning line
     * @var Line
     */
    private $winner;
    
    /**
     * the losing line
     * @var Line
     */
    private $loser;
    
    /**
     * the reason code given for the tie break
     * @var numeric 
     */
    private $reasonCode;

    /**
     * 
     * @param int $sortOutcome
     * @param \Peppercorn\St1\Line $winner
     * @param \Peppercorn\St1\Line $loser
     */
    private function __construct($sortOutcome, Line $winner, Line $loser, $reasonCode)
    {
        Preconditions::checkArgumentIsInteger($sortOutcome);
        Preconditions::checkArgumentIsInteger($reasonCode);
        $this->sortOutcome = $sortOutcome;
        $this->winner = $winner;
        $this->loser = $loser;
        $this->reasonCode = $reasonCode;
    }
    
    /**
     * 
     * @param \Peppercorn\St1\Line $winner
     * @param \Peppercorn\St1\Line $loser
     * @param int $reasonCode
     * @return \Peppercorn\St1\TieBreak
     */
    public static function goesToA(Line $winner, Line $loser, $reasonCode)
    {
        return new TieBreak(self::$WINNER_A, $winner, $loser, $reasonCode);
    }
    
    /**
     * 
     * @param \Peppercorn\St1\Line $winner
     * @param \Peppercorn\St1\Line $loser
     * @param int $reasonCode
     * @return \Peppercorn\St1\TieBreak
     */
    public static function goesToB(Line $winner, Line $loser, $reasonCode)
    {
        return new TieBreak(self::$WINNER_B, $winner, $loser, $reasonCode);
    }
    
    /**
     * get the winner
     * @return Line
     */
    public function getWinner()
    {
        return $this->winner;
    }
    
    /**
     * get the loser
     * @return Line
     */
    public function getLoser()
    {
        return $this->loser;
    }
    
    /**
     * test if a won
     * @return boolean
     */
    public function wentToA()
    {
        return $this->sortOutcome === self::$WINNER_A;
    }
    
    /**
     * test if b won
     * @return boolean
     */
    public function wentToB()
    {
        return $this->sortOutcome === self::$WINNER_B;
    }
    
    /**
     * get the reason for the sort outcome
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }


}