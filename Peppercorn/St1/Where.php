<?php
namespace Peppercorn\St1;

abstract class Where
{

    /**
     * Test the Line for inclusion in a Result
     *
     * @param Line $line
     * @return true if the Line passes, false if it fails
     */
    abstract public function test(Line $line);
}