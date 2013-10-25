<?php
namespace Peppercorn\St1;

class File
{

    /**
     * Contains all lines from this file
     * @var array
     */
    private $lines;

    public function __construct($content)
    {
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
        assert('is_integer($index)');
        assert('$index >= 0');
        assert('$index < $this->getLineCount()');
        // TODO: confirm assert works with object scope in this way
        return $this->lines[$index];
    }

}