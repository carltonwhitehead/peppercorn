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
    private $sortFunctions = array();

    /**
     * Add a sort to the array. Sort operations will be called in order
     * 
     * @param callable|array $sort should be a callback
     * @return \Peppercorn\Util\ComposableMultiSort
     */
    public function addSort($sort)
    {
        $this->sortFunctions[] = $sort;
        return $this;
    }

    /**
     * This magic method is a hook only intended to be used by a PHP sort function such as
     * usort($data, $composableMultiSort). It might be more efficient to use the compare($a, $b) function
     * instead; see asSortCallable().
     * 
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    public function __invoke($a, $b)
    {
        return $this->compare($a, $b);
    }

    /**
     * Compare two values using all registered sort functions
     *
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    public function compare($a, $b)
    {
        foreach ($this->sortFunctions as $sortFunction) {
            $result = call_user_func($sortFunction, $a, $b);
            if ($result !== 0) {
                return $result;
            }
        }
        return 0;
    }

    /**
     * A convenience to get object method array callback formatted to call the compare($a, $b) method.
     *
     * While you can just pass this object as the user-supplied comparison function, that relies on calling through
     * __invoke($a, $b), which is supported, but is slightly less efficient than calling compare($a, $b) directly.
     *
     * @return callable
     */
    public function asSortCallable()
    {
        return array($this, 'compare');
    }

}
