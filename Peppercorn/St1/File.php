<?php
namespace Peppercorn\St1;

use Phava\Base;
use Phava\Base\Preconditions;
use Phava\Base\Strings;

class File
{

    /**
     * Contains all lines from this file
     * @var array
     */
    private $lines;

    public function __construct($content)
    {
        Preconditions::checkArgument(Strings::isNotEmpty($content));
        $this->lines = self::splitContentIntoLines($content);
    }

    private static function splitContentIntoLines($content) {
        $rawLines = explode("\n", $content);
        $lines = array();
        foreach ($rawLines as $rawLine) {
            $line = trim($rawLine);
            if (strlen($line) > 0) { // omit empty lines
                $lines[] = $line;
            }
        }
        return $lines;
    }

    public function getLineCount() {
        return count($this->lines);
    }

    public function getLine($index) {
        Preconditions::checkArgumentIsInteger($index);
        Preconditions::checkArgumentIsKeyInArray($index, $this->lines);

        return $this->lines[$index];
    }

}