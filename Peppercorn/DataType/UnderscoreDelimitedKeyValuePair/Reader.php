<?php

namespace Peppercorn\DataType\UnderscoreDelimitedKeyValuePair;

interface Reader {
    public function get($pairs, $key);
}
