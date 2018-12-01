<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$calc = new FrequencyCalculator($input);

echo "Part1: " . $calc->getFrequency();
echo "<br/>Part2: ";

class FrequencyCalculator {
    private $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function getFrequency(): int
    {
        $frequency = 0;

        foreach ($this->input as $input) {
            $number = preg_split('#[+\-*]#', $input);

            if ('+' === $input[0]) {
                $frequency += $number[1];
            } else {
                $frequency -= $number[1];
            }
        }

        return $frequency;
    }
}