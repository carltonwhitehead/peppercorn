<?php
namespace Peppercorn\St1;

use Phava\Base\Preconditions;
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
        Preconditions::checkArgumentIsString($prefix);
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
}