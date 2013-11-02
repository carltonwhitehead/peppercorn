<?php
namespace Peppercorn\St1;

class Category
{

    private $prefix;

    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }
}