<?php
namespace Peppercorn\St1;

use Phava\Base\Preconditions;

/**
 * Test whether the line has the driver's category, class, and number
 */
class WhereDriverIs extends Where
{

    /**
     * @var Category
     */
    private $driverCategory;

    /**
     * @var string
     */
    private $driverClass;

    /**
     * @var string
     */
    private $driverNumber;

    public function __construct(Category $driverCategory, $driverClass, $driverNumber)
    {
        Preconditions::checkArgumentIsString($driverClass);
        Preconditions::checkArgumentIsString($driverNumber);
        $this->driverCategory = $driverCategory;
        $this->driverClass = $driverClass;
        $this->driverNumber = $driverNumber;
    }

    public function test($line)
    {
        return
            $line->getDriverNumber() === $this->driverNumber
            and $line->getDriverCategory()->getPrefix() === $this->driverCategory->getPrefix()
            and $line->getDriverClass() === $this->driverClass;
    }
}