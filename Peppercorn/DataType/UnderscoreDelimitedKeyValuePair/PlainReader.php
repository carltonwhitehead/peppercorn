<?php
namespace Peppercorn\DataType\UnderscoreDelimitedKeyValuePair;

class PlainReader implements Reader {
    
    /**
     *  retrieve a value from the line by its key
     * 
     * @param type $key
     */
    public function get($pairs, $key) {
        assert('is_string($key)');
        assert('strlen($key) > 0');
        
        $keyStartPos = strpos($pairs, $key);
        if ($keyStartPos !== false) {
            $keyLength = strlen($key);
            $valueStartPos = $keyStartPos + $keyLength + 1; // +1 to account for the trailing _
            $valueStopPos = strpos($pairs, '_', $valueStartPos);
            if ($valueStopPos !== false) {
                $valueLength = $valueStopPos - $valueStartPos;
                $value = substr($pairs, $valueStartPos, $valueLength);
            } else {
                $value = substr($pairs, $valueStartPos);
            }
        } else {
            $value = '';
        }
        return $value;
    }
}

