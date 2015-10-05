<?php
namespace Peppercorn\St1;

interface SortProvider
{
    /**
     * @return callable a sort function suitable for use with usort
     */
    public static function getSort();
}