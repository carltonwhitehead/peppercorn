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

    public function __construct($content, $categories)
    {
        Preconditions::checkArgumentIsString($content);
        Preconditions::checkArgument(is_array($categories));
        Preconditions::checkArgument(count($categories) > 0);

        $this->buildAndSetLines($content);

        $this->buildAndSetCategoryPrefixes($categories);
        $this->buildAndSetCategoriesByPrefix($categories);
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
            if (Strings::isNotEmpty($stringLine)) {
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

    public function getLine($index)
    {
        Preconditions::checkArgumentIsInteger($index);
        Preconditions::checkArgumentIsKeyInArray($index, $this->lines);

        return $this->lines[$index];
    }

    public function getCategoryPrefixes() {
        return $this->categoryPrefixes;
    }

    public function getCategoryByPrefix($prefix) {
        return $this->categoriesByPrefix[$prefix];
    }
}