<?php
namespace Peppercorn\St1;

use Phava\Base\Strings;
use Phava\Base\Preconditions;
/**
 * A line from an st1 file
 *
 * @TODO implement parsed value cache
 */
class Line
{
    
    protected static $PENALTY_DNF;
    protected static $PENALTY_RRN;
    protected static $PENALTY_DSQ;
    protected static $PENALTY_UNKNOWN;
    
    /**
     * the parent st1 file
     *
     * @var File
     */
    private $file;

    /**
     * the string representing this line
     *
     * @var string
     */
    private $line;
    
    // Generated, cached values for corresponding getters
    private $runNumber;
    private $driverCategory;
    private $driverClass;
    private $driverClassRaw;
    private $driverNumber;
    private $timeRaw;
    private $timeRawWithPenalty;
    private $timeRawForSort;
    private $isClean;
    private $hasPenalty;
    private $hasConePenalty;
    private $isDnf;
    private $isDsq;
    private $isRerun;
    private $penalty;
    private $driverName;
    private $car;
    private $carColor;
    private $timePax;
    private $timePaxForSort;
    private $timestamp;
    private $isValid;

    public function __construct(File $file, $line)
    {
        Preconditions::checkArgument($file != null);
        $this->file = $file;
        $this->line = $line;
    }
    
    /**
     * Get the file this run came from
     * @return \Peppercorn\St1\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * retrieve a value from the line by its key
     *
     * @param string $key
     * @return string
     */
    private function parse($key)
    {
        $keyStartPos = strpos($this->line, $key);
        if ($keyStartPos !== false) {
            $keyLength = strlen($key);
            $valueStartPos = $keyStartPos + $keyLength + 1; // +1 to account for the trailing _
            $valueStopPos = strpos($this->line, '_', $valueStartPos);
            if ($valueStopPos !== false) {
                $valueLength = $valueStopPos - $valueStartPos;
                $value = substr($this->line, $valueStartPos, $valueLength);
            } else {
                $value = substr($this->line, $valueStartPos);
            }
        } else {
            $value = '';
        }
        return $value;
    }

    /**
     * Get the run (sequence) number for the driver.
     * For each run a driver takes, this number increments by one.
     * Lines with a rerun (RRN) penalty are not assigned a run number.
     *
     * @return string
     */
    public function getRunNumber()
    {
        if ($this->runNumber === null) {
            $this->runNumber = $this->parse('run');
        }
        return $this->runNumber;
    }
    
    /**
     * Find whether the line has a run number
     * @return boolean true if the line has a run number, otherwise false
     */
    public function hasRunNumber()
    {
        return Strings::isNotEmpty($this->getRunNumber());
    }

    /**
     * Get the driver's Category
     * @return \Peppercorn\St1\Category
     */
    public function getDriverCategory()
    {
        if ($this->driverCategory === null) {
            $classString = strtoupper($this->parse('class'));
            /* @var $category Category */
            $category = null;
            foreach ($this->getCategoryPrefixes() as $categoryPrefix) {
                if (Strings::isNotEmpty($categoryPrefix) and substr($classString, 0, strlen($categoryPrefix)) == $categoryPrefix) {
                    $category = $this->getCategoryByPrefix($categoryPrefix);
                    break;
                }
            }
            if ($category === null) {
                $category = $this->getCategoryByPrefix('');
            }
            $this->driverCategory = $category;
        }
        
        return $this->driverCategory;
    }

    /**
     * Get the class of the driver.
     * Automatically trims the category if the driver is in a category with a prefix (such as NOV, X, RT, etc)
     *
     * @throws LineException if no class is present in the line
     * @return string
     */
    public function getDriverClass()
    {
        if ($this->driverClass === null) {
            $classString = $this->getDriverClassRaw();
            $categoryPrefix = $this->getDriverCategory()->getPrefix();
            if (Strings::isNotEmpty($categoryPrefix)) {
                $classString = substr($classString, strlen($categoryPrefix));
            }
            if (Strings::isEmpty($classString)) {
                static $error = 'Invalid state file line is missing class.';
                throw new LineException($error);
            }
            $this->driverClass = $classString;
        }
        return $this->driverClass;
    }
    
    /**
     * Get the raw class of the driver as listed in the st1 file
     * The st1 file combines the category and the class of the driver into a single string.
     * 
     * @throws LineException if no class is present in the line
     * @return string
     */
    public function getDriverClassRaw()
    {
        if ($this->driverClassRaw === null) {
            $classString = strtoupper($this->parse('class'));
            if (Strings::isEmpty($classString)) {
                static $error = 'Invalid state file line is missing class.';
                throw new LineException($error);
            }
            $this->driverClassRaw = $classString;
        }
        return $this->driverClassRaw;
    }

    /**
     * Get the driver's (registered) number.
     * This is the number that is displayed on the side of the car.
     *
     * @throws LineException if the driver's number is missing
     * @return string
     */
    public function getDriverNumber()
    {
        if ($this->driverNumber === null) {
            $number = $this->parse('number');
            if (Strings::isEmpty($number)) {
                static $error = 'Invalid state file line is missing driver number.';
                throw new LineException($error);
            }
            $this->driverNumber = $number;
        }
        return $this->driverNumber;
    }

    /**
     * Get the RAW time of the run.
     * AXware has NOT factored in cone penalty seconds, if any.
     * Be sure to call this method only if you don't need penalty seconds included.
     *
     * @return string
     */
    public function getTimeRaw()
    {
        if ($this->timeRaw === null) {
            $this->timeRaw = $this->parse('tm');
        }
        return $this->timeRaw;
    }

    /**
     * Gets the raw time of the run with penalty applied, if any
     * @return string
     */
    public function getTimeRawWithPenalty()
    {
        if ($this->timeRawWithPenalty === null) {
            if ($this->isDnf()) {
                $this->timeRawWithPenalty = static::$PENALTY_DNF;
            } else if ($this->isRerun()) {
                $this->timeRawWithPenalty = static::$PENALTY_RRN;
            } else if ($this->isDsq()) {
                $this->timeRawWithPenalty = static::$PENALTY_DSQ;
            } else {
                $this->timeRawWithPenalty = (string) $this->applyConePenaltyTo($this->getTimeRaw());
            }
        }
        return $this->timeRawWithPenalty;
    }

    /**
     * Gets the raw time of the run for sort.
     * @return float the raw time of the run for sort. returns raw time with cone penalty if applicable, or if DNR, RRN, or otherwise disqualified, returns PHP_INT_MAX
     */
    public function getTimeRawForSort()
    {
        if ($this->timeRawForSort === null) {
            if ($this->hasRunNumber()
                and ($this->isClean() or $this->hasConePenalty())
                and Strings::isNotEmpty($this->getTimeRaw())
                and Strings::isNotEmpty($this->getTimePax())
                and is_numeric($this->getTimeRaw())) {
                $timeRawForSort = $this->getTimeRawWithPenalty();
            } else if ($this->isDnf()) {
                $timeRawForSort = static::$PENALTY_DNF;
            } else if ($this->isRerun()) {
                $timeRawForSort = static::$PENALTY_RRN;
            } else if ($this->isDsq()) {
                $timeRawForSort = static::$PENALTY_DSQ;
            } else {
                $timeRawForSort = PHP_INT_MAX;
            }
            $this->timeRawForSort = $timeRawForSort;
        }
        return $this->timeRawForSort;
    }

    /**
     * Find whether this run is clean
     * @return boolean true if no penalty, false if penalty
     */
    public function isClean()
    {
        if ($this->isClean === null) {
            $this->isClean = !$this->hasPenalty(); 
        }
        return $this->isClean;
    }

    /**
     * Find whether this run has any penalty
     * @return boolean true if the run has a penalty, false if it does not
     */
    public function hasPenalty()
    {
        if ($this->hasPenalty === null) {
            $this->hasPenalty = strlen($this->getPenalty()) > 0;
        }
        return $this->hasPenalty;
    }

    /**
     * Find whether this run has a cone penalty
     * @return booean true if the run has a cone penalty, false if it does not (but may not necessarily be clean)
     */
    public function hasConePenalty()
    {
        if ($this->hasConePenalty === null) {
            $this->hasConePenalty = (int) $this->getPenalty() > 0;
        }
        return $this->hasConePenalty;
    }

    /**
     * Find whether this run is a DNF
     * @return boolean true if the run is a DNF, false if it is not
     */
    public function isDnf()
    {
        if ($this->isDnf === null) {
            $this->isDnf = $this->getPenalty() === 'DNF';
        }
        return $this->isDnf;
    }
    
    public function isDsq()
    {
        if ($this->isDsq === null) {
            $this->isDsq = $this->getPenalty() === 'DSQ';
        }
        return $this->isDsq;
    }

    /**
     * Find whether this run received a rerun call
     * @return boolean boolean true if the run is a rerun, false if it is not
     */
    public function isRerun()
    {
        if ($this->isRerun === null) {
            $this->isRerun = $this->getPenalty() === 'RRN';
        }
        return $this->isRerun;
    }

    /**
     * Get the penalty on this line, if any
     * @return string
     */
    public function getPenalty()
    {
        if ($this->penalty === null) {
            $this->penalty = strtoupper($this->parse('penalty'));
        }
        return $this->penalty;
    }

    /**
     * Get the driver's name given to registration
     *
     * @throws LineException if the driver's name is missing
     * @return string
     */
    public function getDriverName()
    {
        if ($this->driverName === null) {
            $driverName = $this->parse('driver');
            if (Strings::isEmpty($driverName)) {
                static $error = 'Invalid state file line is missing driver name.';
                throw new LineException($error);
            }
            $this->driverName = $driverName;
        }
        return $this->driverName;
    }

    /**
     * Get the year/make/model of the driver's registered car
     *
     * @return string
     */
    public function getCar()
    {
        if ($this->car === null) {
            $this->car = $this->parse('car');
        }
        return $this->car;
    }

    /**
     * Get the color of the driver's registered car
     *
     * @return string
     */
    public function getCarColor()
    {
        if ($this->carColor === null) {
            $this->carColor = $this->parse('cc');
        }
        return $this->carColor;
    }

    /**
     * Get the PAX time of the run.
     * AXware has already factored in cone penalty seconds, if any
     *
     * @throws LineException if no pax time is found
     * @return string
     */
    public function getTimePax()
    {
        if ($this->timePax === null) {
            $timePax = strtoupper($this->parse('paxed'));
            if (Strings::isEmpty($timePax)) {
                throw new LineException('Line is invalid without pax time');
            }
            $this->timePax = $timePax;
        }
        return $this->timePax;
    }

    /**
     * Gets the pax time of the run for sort.
     * @return float the pax time of the run for sort. returns pax time with cone penalty if applicable, or if DNF, RRN, or otherwise disqualified, returns PHP_INT_MAX
     */
    public function getTimePaxForSort()
    {
        if ($this->timePaxForSort === null) {
            if ($this->isClean() or $this->hasConePenalty()) {
                $this->timePaxForSort = $this->getTimePax();
            } else if ($this->isDnf()) {
                $this->timePaxForSort = static::$PENALTY_DNF;
            } else if ($this->isRerun()) {
                $this->timePaxForSort = static::$PENALTY_RRN;
            } else if ($this->isDsq()) {
                $this->timePaxForSort = static::$PENALTY_DSQ;
            } else {
                $this->timePaxForSort = PHP_INT_MAX;
            }
        }
        return $this->timePaxForSort;
    }

    /**
     * Get the UNIX timestamp of the run
     * @return number
     */
    public function getTimestamp()
    {
        if ($this->timestamp === null) {
            $this->timestamp = (int) $this->parse('tod');
        }
        return $this->timestamp;
    }

    /**
     * Not sure what this does, see todo
     *
     * @todo research meaning/behavior of AXware diff values
     *
     * @return string
     */
    public function getDiff()
    {
        return $this->parse('diff');
    }

    /**
     * Get the time difference from this run to the first place run of the moment
     *
     * @todo research whether this is RAW or PAX, and class or overall
     *
     * @return string
     */
    public function getDiffFromFirst()
    {
        return $this->parse('diff1');
    }
    
    public static function getPenaltyDnf()
    {
        return self::$PENALTY_DNF;
    }
    
    public static function getPenaltyRrn()
    {
        return self::$PENALTY_RRN;
    }
    
    public static function getPenaltyDsq()
    {
        return self::$PENALTY_DSQ;
    }
    
    public static function getPenaltyUnknown()
    {
        return self::$PENALTY_UNKNOWN;
    }
    
    /**
     * Check if the line is valid
     * @return boolean true if the line is valid, false if invalid
     */
    public function isValid()
    {
        if ($this->isValid === null) {
            $isValid = false;
            try {
                $this->getDriverClass();
                $this->getDriverNumber();
                $this->getDriverName();
                $this->getTimePax();
                $isValid = true;
            } catch (LineException $le) {
            }
            $this->isValid = $isValid;
        }
        return $this->isValid;
    }

    /**
     * apply the event's cone penalty to the time
     * @param numeric $time
     * @return numeric the time with cone penalty applied
     */
    private function applyConePenaltyTo($time)
    {
        if ($this->hasConePenalty()) {
            $time += ((float) $this->getPenalty() * (float) $this->getSecondsPerCone());
            $time = round($time, 3);
            $time = number_format($time, 3);
        }
        return $time;
    }

    /**
     * Convenience to get the array of category prefixes from the file
     * @return array:
     */
    private function getCategoryPrefixes()
    {
        return $this->file->getCategoryPrefixes();
    }

    /**
     * Convenience to get a Category from the file by prefix
     * @param string $prefix
     * @return Category
     */
    private function getCategoryByPrefix($prefix)
    {
        return $this->file->getCategoryByPrefix($prefix);
    }

    /**
     * Convenience to get the seconds per cone penalty from the file
     * @return int
     */
    private function getSecondsPerCone()
    {
        return $this->file->getSecondsPerCone();
    }
}
class LineStatic extends Line
{
    public function __construct() { throw new \Exception(); }
    public static function init()
    {
        static::$PENALTY_DNF = PHP_INT_MAX - 1000;
        static::$PENALTY_RRN = PHP_INT_MAX - 900;
        static::$PENALTY_DSQ = PHP_INT_MAX - 800;
        static::$PENALTY_UNKNOWN = PHP_INT_MAX;
    }
}
LineStatic::init();