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

    public function __construct(File $file, $line)
    {
        Preconditions::checkArgument($file != null);
        $this->file = $file;
        $this->line = $line;
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
        return $this->parse('run');
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
        return $category;
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
        $classString = $this->getDriverClassRaw();
        $categoryPrefix = $this->getDriverCategory()->getPrefix();
        if (Strings::isNotEmpty($categoryPrefix)) {
            $classString = substr($classString, strlen($categoryPrefix));
        }
        if (Strings::isEmpty($classString)) {
            static $error = 'Invalid state file line is missing class.';
            throw new LineException($error);
        }
        return $classString;
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
        $classString = strtoupper($this->parse('class'));
        if (Strings::isEmpty($classString)) {
            static $error = 'Invalid state file line is missing class.';
            throw new LineException($error);
        }
        return $classString;
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
        $number = $this->parse('number');
        if (Strings::isEmpty($number)) {
            static $error = 'Invalid state file line is missing driver number.';
            throw new LineException($error);
        }
        return $number;
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
        return $this->parse('tm');
    }

    /**
     * Gets the raw time of the run with cone penalty seconds applied, if any.
     * @return string
     */
    public function getTimeRawWithPenalty()
    {
        return (string) $this->applyConePenaltyTo($this->getTimeRaw());
    }

    /**
     * Gets the raw time of the run for sort.
     * @return float the raw time of the run for sort. returns raw time with cone penalty if applicable, or if DNR, RRN, or otherwise disqualified, returns PHP_INT_MAX
     */
    public function getTimeRawForSort()
    {
        if ($this->hasRunNumber()
            and ($this->isClean() or $this->hasConePenalty())
            and Strings::isNotEmpty($this->getTimeRaw())
            and Strings::isNotEmpty($this->getTimePax())
            and is_numeric($this->getTimeRaw())) {
            return $this->getTimeRawWithPenalty();
        } else {
            return PHP_INT_MAX;
        }
    }

    /**
     * Find whether this run is clean
     * @return boolean true if no penalty, false if penalty
     */
    public function isClean()
    {
        return !$this->hasPenalty();
    }

    /**
     * Find whether this run has any penalty
     * @return boolean true if the run has a penalty, false if it does not
     */
    public function hasPenalty()
    {
        return strlen($this->getPenalty()) > 0;
    }

    /**
     * Find whether this run has a cone penalty
     * @return booean true if the run has a cone penalty, false if it does not (but may not necessarily be clean)
     */
    public function hasConePenalty()
    {
        return (int) $this->getPenalty() > 0;
    }

    /**
     * Find whether this run is a DNF
     * @return boolean true if the run is a DNF, false if it is not
     */
    public function isDnf()
    {
        return $this->getPenalty() === 'DNF';
    }

    /**
     * Find whether this run received a rerun call
     * @return boolean boolean true if the run is a rerun, false if it is not
     */
    public function isRerun()
    {
        return $this->getPenalty() === 'RRN';
    }

    /**
     * Get the penalty on this line, if any
     * @return string
     */
    public function getPenalty()
    {
        return strtoupper($this->parse('penalty'));
    }

    /**
     * Get the driver's name given to registration
     *
     * @throws LineException if the driver's name is missing
     * @return string
     */
    public function getDriverName()
    {
        $driverName = $this->parse('driver');
        if (Strings::isEmpty($driverName)) {
            static $error = 'Invalid state file line is missing driver name.';
            throw new LineException($error);
        }
        return $driverName;
    }

    /**
     * Get the year/make/model of the driver's registered car
     *
     * @return string
     */
    public function getCar()
    {
        return $this->parse('car');
    }

    /**
     * Get the color of the driver's registered car
     *
     * @return string
     */
    public function getCarColor()
    {
        return $this->parse('cc');
    }

    /**
     * Get the PAX time of the run.
     * AXware has already factored in cone penalty seconds, if any
     *
     * @return string
     */
    public function getTimePax()
    {
        return strtoupper($this->parse('paxed'));
    }

    /**
     * Gets the pax time of the run for sort.
     * @return float the pax time of the run for sort. returns pax time with cone penalty if applicable, or if DNF, RRN, or otherwise disqualified, returns PHP_INT_MAX
     */
    public function getTimePaxForSort()
    {
        if ($this->isClean() or $this->hasConePenalty()) {
            return $this->getTimePax();
        } else {
            return PHP_INT_MAX;
        }
    }

    /**
     * Get the UNIX timestamp of the run
     * @return number
     */
    public function getTimestamp()
    {
        return (int) $this->parse('tod');
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
    
    /**
     * Check if the line is valid
     * @return boolean true if the line is valid, false if invalid
     */
    public function isValid()
    {
        try {
            $this->getDriverClass();
            $this->getDriverNumber();
            $this->getDriverName();
        } catch (LineException $le) {
            return false;
        }
        return true;
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
