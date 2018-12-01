<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$calc = new FrequencyCalculator($input);

echo "Part1: " . $calc->getFrequency();
echo "<br/>Part2: " . $calc->getFirstDuplicate();

class FrequencyCalculator {
    private $input;

    private $listOfFrequencies = [0];

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function getFrequency(): int
    {
        $frequency = 0;

        foreach ($this->input as $input) {
            $number = preg_split('#[+\-]#', $input);

            if ('+' === $input[0]) {
                $frequency += $number[1];
            } else {
                $frequency -= $number[1];
            }

            $this->listOfFrequencies[] = $frequency;
        }

        array_pop($this->listOfFrequencies);

        return $frequency;
    }

    public function getFirstDuplicate(): ?int
    {
        $currentFrequency = $this->getFrequency();
        $currentHighestKey = sizeof($this->listOfFrequencies)-1;

        while (true) {
            foreach ($this->input as $input) {
                $number = preg_split('#[+\-]#', $input);

                if ('+' === $input[0]) {
                    $currentFrequency += $number[1];
                } else {
                    $currentFrequency -= $number[1];
                }

                $thisKey = array_search($currentFrequency, $this->listOfFrequencies);

                if ($thisKey < $currentHighestKey && $thisKey !== false) {
                    return $this->listOfFrequencies[$thisKey];
                }
            }
        }

        return null;
    }
}