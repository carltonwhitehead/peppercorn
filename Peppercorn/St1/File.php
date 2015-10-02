<?php
namespace Peppercorn\St1;

class File
{

    /**
     * An array of Line objects, each representing a line from $content
     * @var array
     */
    private $lines = array();

    /**
     * An array of category prefix strings
     * @var array
     */
    private $categoryPrefixes = array();

    /**
     * An array of Category objects keyed by the Category's prefix
     * @var array
     */
    private $categoriesByPrefix = array();

    /**
     * The number of seconds per cone penalty
     * @var number
     */
    private $secondsPerCone;

    public function __construct($content, $categories, $secondsPerCone = 2)
    {
        assert('is_string($content)');
        assert('is_array($categories)');
        assert('count($categories) > 0');
        assert('is_integer($secondsPerCone)');

        $this->buildAndSetLines($content);

        $this->buildAndSetCategoryPrefixes($categories);
        $this->buildAndSetCategoriesByPrefix($categories);
        $this->secondsPerCone = $secondsPerCone;
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
            if (strlen($stringLine) > 0) {
                $stringLines[] = $stringLine;
            }
        }
        return $stringLines;
    }

    /**
     * Build and set Line objects for each value in $stringLines
     * @param array $stringLines
     * @return array
     */
    private function buildAndSetLines($content)
    {
        $stringLines = self::splitContentIntoStringLines($content);
        foreach ($stringLines as $stringLine) {
            $this->lines[] = new Line($this, $stringLine);
        }
    }

    private function buildAndSetCategoryPrefixes($categories)
    {
    	foreach ($categories as $category) {
    	    /* @var $category Category */
    	    $this->categoryPrefixes[] = $category->getPrefix();
    	}
    }

    private function buildAndSetCategoriesByPrefix($categories)
    {
        foreach($categories as $category) {
            /* @var $category Category */
            $this->categoriesByPrefix[$category->getPrefix()] = $category;
        }
    }

    public function getLineCount()
    {
        return count($this->lines);
    }

    /**
     * @param int $index
     * @return Line
     */
    public function getLine($index)
    {
        assert('is_integer($index)');
        assert('array_key_exists($index, $this->lines)');

        return $this->lines[$index];
    }

    public function getCategoryPrefixes() {
        return $this->categoryPrefixes;
    }

    public function getCategoryByPrefix($prefix) {
        return $this->categoriesByPrefix[$prefix];
    }

    public function getSecondsPerCone()
    {
        return $this->secondsPerCone;
    }
}