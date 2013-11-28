<?php
namespace Peppercorn\St1;

class GroupByDriver implements Grouper
{
    public function getGroupKey(Line $line)
    {
        $category = $line->getDriverCategory();
        $class = $line->getDriverClass();
        $number = $line->getDriverNumber();
        return "_class_{$category}{$class}_number_{$number}";
    }
}