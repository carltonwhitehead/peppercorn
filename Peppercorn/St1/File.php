<?php
namespace Peppercorn\St1;

use Phava\Base;
use Phava\Base\Preconditions;
use Phava\Base\Strings;

class File
{

    /**
     * An array of Line objects, each representing a line from $content
     * @var array
     */
    private $lines = array();

    public function __construct($content)
    {
        $stringLines = self::splitContentIntoStringLines($content);
        self::buildAndSetLines($stringLines);
    }

    /**
     * Split the content passed to the constructor into an array of processed string lines
     *
     * @param array $content
     * @return array:
     */
    private static function splitContentIntoStringLines($content)
    {
        $rawLines = explode(PHP_EOL, $content);
        $stringLines = array();
        foreach ($rawLines as $rawLine) {
            $stringLine = trim($rawLine);
            if (Strings::isNotEmpty($line)) {
                $stringlines[] = $stringLine;
            }
        }
        return $stringLines;
    }

    /**
     * Build and set Line objects for each value in $stringLines
     * @param array $stringLines
     * @return array
     */
    private static function buildAndSetLines($stringLines)
    {
        foreach ($stringsLines as $stringLine) {
            $this->lines[] = new Line($this, $stringLine);
        }
    }

    public function getLineCount()
    {
        return count($this->lines);
    }

    public function getLine($index)
    {
        Preconditions::checkArgumentIsInteger($index);
        Preconditions::checkArgumentIsKeyInArray($index, $this->lines);

        return $this->lines[$index];
    }
}