<?php
namespace Peppercorn\St1;

class Category
{

    /**
     * the prefix of the category as stored in the .st1 file
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    public function __construct($prefix)
    {
        assert('is_string($prefix)');
        $this->prefix = $prefix;
    }

    /**
     * get the prefix of the category as stored in the .st1 file
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    public function equals(Category $other)
    {
        return $this->prefix === $other->prefix;
    }
}