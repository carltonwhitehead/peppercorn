<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\Line;
use Peppercorn\St1\Where;

/**
 * This is a mock class for testing purposes only.
 */
class WhereFalse implements Where
{
    public function test(Line $line)
    {
        return false;
    }
}