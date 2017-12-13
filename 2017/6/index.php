<?php

//$input = [0, 2, 7, 0]; //5
$input = [4,1,15,12,0,9,9,5,5,8,7,3,14,5,12,3];

$mem = new Mem($input);
echo "Part1: " . $mem->calcCycles();

class Mem {
    protected $input;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function calcCycles()
    {
        $cycles = 0;
        $prevMemStates = [];
        $input = $this->input;

        while (true) {
            $input = $this->runCycle($input);
            ++$cycles;

            if (in_array($input, $prevMemStates)) {
                break;
            }

            $prevMemStates[] = $input;
        }

        $fisrtPos = array_search($input, $prevMemStates);
        echo "Part2: " . (sizeof($prevMemStates) - $fisrtPos) . "<br/>";

        return $cycles;
    }

    private function runCycle($input)
    {
        $highestValue = max($input);
        $currentKey = array_search($highestValue, $input);
        $input[$currentKey] = 0;

        while (0 < $highestValue) {
            $currentKey = $this->getNextKey($input, $currentKey);

            ++$input[$currentKey];
            --$highestValue;
        }


        return $input;
    }

    private function getNextKey($input, $currentKey)
    {
        $sizeOfInput = sizeof($input);
        $currentKey++;

        return $currentKey >= $sizeOfInput ? $currentKey - $sizeOfInput : $currentKey;
    }
}