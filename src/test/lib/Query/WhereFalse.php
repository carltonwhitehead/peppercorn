<?php

namespace Peppercorn\Test\Query;

use Peppercorn\St1\Line;
use Peppercorn\St1\Where;

class WhereFalse implements Where
{
    public function test(Line $line) {
        return false;
    }

}