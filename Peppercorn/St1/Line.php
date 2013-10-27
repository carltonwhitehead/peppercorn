<?php
namespace Peppercorn\St1;

use Phava\Base\Strings;
/**
 * A line from an st1 file
 *
 * @todo implement category lookups
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
    private $line = '';

    /**
     * query a state file line for value by key
     *
     * @return string
     */
    public function __construct(File $file, $line)
    {
        $this->file = $file;
    }

    /**
     * local static cache of category prefix strings
     *
     * @var array
     * @todo refactor to allow retrieve from an St1-level config object, falling back to global if no St1-level config object exists
     */
    private static $categoryPrefixes = array();

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

    public function getDriverCategory()
    {
        $categoryService = new AxIr_Model_CategoryService();
        if (! self::$categoryPrefixes) {
            self::$categoryPrefixes = $categoryService->getCategoryPrefixes();
        }
        $classString = strtoupper($this->parse('class'));
        $category = null;
        foreach (self::$categoryPrefixes as $categoryPrefix) {
            if ($categoryPrefix !== '' and substr($classString, 0, strlen($categoryPrefix)) == $categoryPrefix) {
                $category = $categoryService->getCategoryByPrefix($categoryPrefix);
                break;
            }
        }
        if ($category === null) {
            $category = $categoryService->getCategoryByPrefix('');
        }
        return $category->prefix;
    }

    public function getDriverClass()
    {
        $classString = strtoupper($this->parse('class'));
        if (Strings::isEmpty($classString)) {
            static $error = 'Invalid state file line is missing class.';
            throw new LineException($error);
        }
        $categoryPrefix = $this->getDriverCategory();
        if (Strings::isNotEmpty($categoryPrefix)) {
            $classString = substr($classString, strlen($categoryPrefix));
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

    private function getCategoryPrefixes()
    {
        if ($this->file->hasCategoryPrefixes()) {
            return $this->file->getCategoryPrefixes();
        } else {
            return self::$categoryPrefixes;
        }
    }
}
