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

    public function getRunNumber()
    {
        return $this->parse('run');
    }

    /**
     * get the driver's Category
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

    public function getPenalty()
    {
        return strtoupper($this->parse('penalty'));
    }

    public function getDriverName()
    {
        $driverName = $this->parse('driver');
        if (Strings::isEmpty($driverName)) {
            static $error = 'Invalid state file line is missing driver name.';
            throw new LineException($error);
        }
        return $driverName;
    }

    public function getCar()
    {
        return $this->parse('car');
    }

    public function getCarColor()
    {
        return $this->parse('cc');
    }

    public function getTimePax()
    {
        $timePax = strtoupper($this->parse('paxed'));
        if (Strings::isEmpty($timePax)) {
            static $error = 'Invalid state file line is missing pax time.';
            throw new LineException($error);
        }
        return $timePax;
    }

    public function getTimestamp()
    {
        return (int) $this->parse('tod');
    }

    public function getDiff()
    {
        return $this->parse('diff');
    }

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
