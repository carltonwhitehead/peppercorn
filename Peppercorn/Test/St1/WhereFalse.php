<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\Line;

/**
 * This is a mock class for testing purposes only.
 */
class WhereFalse extends \Peppercorn\St1\Where
{
    public function test(Line $line)
    {
        return false;
    }
}