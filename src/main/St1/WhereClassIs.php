<?php
namespace Peppercorn\St1;

class WhereClassIs implements Where
{
    private $category;
    private $class;

    public function __construct(Category $category, $class)
    {
        assert('is_string($class)');
        $this->category = $category;
        $this->class = $class;
    }

    public function test(Line $line)
    {
        return $this->category->equals($line->getDriverCategory())
            and $this->class === $line->getDriverClass();
    }
}