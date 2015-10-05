<?php
namespace Peppercorn\St1;

interface Grouper
{
    public function getGroupKey(Line $line);
}