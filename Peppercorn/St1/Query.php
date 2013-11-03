<?php
namespace Peppercorn\St1;

class Query
{

    /**
     * The file whose lines will be queried
     *
     * @var File
     */
    private $file;

    /**
     * An array of Where objects to test for the inclusion of each line in the Result
     *
     * @var array
     */
    private $wheres = array();

    private static $TEST_WHERES_ASCENDING = 'ascending';
    private static $TEST_WHERES_DESCENDING = 'descending';

    /**
     * Controls the direction of Line evaluation against the where tests
     * @var unknown
     */
    private $testWheresDirection = self::TEST_WHERES_ASCENDING;

    public function __construct(File $file)
    {
        $this->file;
    }

    /**
     * Add a Where condition
     * @param Where $where
     * @return \Peppercorn\St1\Query
     */
    public function where(Where $where)
    {
        $this->wheres[] = $where;
        return $this;
    }

    /**
     * Set the execution of Where tests against each Line in ascending order
     *
     * @return \Peppercorn\St1\Query
     */
    public function setTestWheresAscending()
    {
        $this->testWheresDirection = self::$TEST_WHERES_ASCENDING;
        return $this;
    }

    /**
     * Set the execution of Where tests against each Line in descending order.
     * This is useful for finding only the last run of a given driver, which would contain their final personal best time and finishing positions within their class.
     *
     * @return \Peppercorn\St1\Query
     */
    public function setTestWheresDescending()
    {
        $this->testWheresDirection = self::$TEST_WHERES_DESCENDING;
        return $this;
    }

    /**
     * @return array Line objects
     */
    public function execute()
    {
        switch ($this->testWheresDirection) {
        	case self::$TEST_WHERES_DESCENDING:
        	    $result = $this->testWheresDescending();
        	    break;
        	case self::$TEST_WHERES_ASCENDING:
        	default:
        	    $result = $this->testWheresAscending();
        	    break;
        }
        return $result;
    }

    /**
     * Test each line in ascending order
     * @return array only Line objects which passed all Where tests
     */
    private function testWheresAscending()
    {
        $result = array();
        for ($i = 0; $i < $this->file->getLineCount(); $i++) {
            $line = $this->file->getLine($i);
            if (!$this->testLine($line)) {
                continue;
            }
            $result[] = $line;
        }
        return $result;
    }

    /**
     * Test each line in descending order
     * @return array only Line objects which passed all Where tests
     */
    private function testWheresDescending()
    {
        $result = array();
        for ($i = $this->file->getLineCount() - 1; $i >= 0; $i--) {
            $line = $this->file->getLine($i);
            if (!$this->testLine($line)) {
                continue;
            }
            $result = $line;
        }
    }

    /**
     * Test the Line with each Where from $this->wheres
     * @param Line $line
     * @return boolean false if any test failed, true if all tests passed
     */
    private function testLine(Line $line)
    {
        foreach ($this->wheres as /* @var $where Where */ $where) {
            if ($where->test($line) === false) {
                return false;
            }
        }
        return true;
    }

}