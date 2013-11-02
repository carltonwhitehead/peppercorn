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
        $classString = strtoupper($this->parse('class'));
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
     * @throws LineException if the line is missing a raw time
     * @return string
     */
    public function getTimeRaw()
    {
        $timeRaw = $this->parse('tm');
        if (Strings::isEmpty($timeRaw)) {
            static $error = 'Invalid state file line is missing raw time.';
            throw new LineException($error);
        }
        return $timeRaw;
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
     *
     * @throws LineException if the run is missing a PAX time
     * @return string
     */
    public function getTimePax()
    {
        $timePax = strtoupper($this->parse('paxed'));
        if (Strings::isEmpty($timePax)) {
            static $error = 'Invalid state file line is missing pax time.';
            throw new LineException($error);
        }
        return $timePax;
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
}
