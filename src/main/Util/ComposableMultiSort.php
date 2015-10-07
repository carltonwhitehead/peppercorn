<?php

namespace Peppercorn\Util;

/**
 * A composable sort class allowing you to compose a multi-sort operation using simple individual sort functions
 */
class ComposableMultiSort
{
    /**
     *
     * @var array of callable sort functions
     */
    private $sorts;

    /**
     * Add a sort to the array. Sort operations will be called in order
     * 
     * @param callable|array $sort
     * @return \Peppercorn\Util\ComposableMultiSort
     */
    public function addSort($sort)
    {
        $this->sorts[] = $sort;
        return $this;
    }

    /**
     * This method should only be invoke by a PHP sort function such as usort($data, $composableMultiSort)
     * 
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    public function __invoke($a, $b)
    {
        foreach ($this->sorts as $sort) {
            $result = call_user_func($sort, $a, $b);
            if ($result !== 0) {
                return $result;
            }
        }
        return 0;
    }

}
