<?php
namespace Peppercorn\St1;

/**
 * Test whether the line has the matching category
 */
class WhereCategoryIs implements Where
{

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function test(Line $line)
    {
        return $line->getDriverCategory()->equals($line->getDriverCategory());
    }
}