<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$input = [
    'abcdef',
    'bababc',
    'abbcde',
    'abcccd',
    'aabcdd',
    'abcdee',
    'ababab',
];

$calc = new ChecksumCalculator($input);

echo "Part1: " . $calc->getBasicChecksum();
//echo "<br/>Part2: " . $calc->();

class ChecksumCalculator {
    private $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function getBasicChecksum(): int
    {
        $doubles = 0;
        $triples = 0;

        foreach ($this->input as $input) {
            $charCount = $this->getCharCount($input);

            !$this->hasDouble($charCount) ?: ++$doubles;
            !$this->hasTriple($charCount) ?: ++$triples;
        }

        return $doubles * $triples;
    }

    private function getCharCount(string $string): array
    {
        $chars = [];

        for($i = 0; $i < strlen($string); ++$i) {
            isset($chars[$string[$i]]) ? $chars[$string[$i]] += 1 : $chars[$string[$i]] = 1;
        }

        return $chars;
    }

    private function hasDouble(array $charCount): bool
    {
        return array_search(2, $charCount) ? true : false;
    }

    private function hasTriple(array $charCount): bool
    {
        return array_search(3, $charCount) ? true : false;
    }
}