<?php

ini_set('display_errors', '1');
$stars = new Space();
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

echo "Part1: " . $stars->part1($input) . PHP_EOL;
echo "Part2: " . $stars->part2($input) . PHP_EOL;

class Space
{
    public function part1(array $input): int
    {
        $validOptions = -1;
        $lower = $input[0];
        $upper = $input[1];

        while ($lower < $upper) {
            $lower = self::incrementPassword($lower);
            ++$validOptions;
        }

        return $validOptions;
    }

    public function part2(array $input)
    {
        $validOptions = -1;
        $lower = $input[0] + 1;
        $upper = $input[1] - 1;

        while ($lower < $upper) {
            $lower = self::incrementSpecialPassword($lower, $upper);
            ++$validOptions;
        }

        return $validOptions;
    }

    private function incrementPassword(int $number): int
    {
        ++$number;

        while (!self::isNumberValid($number)) {
            ++$number;
        }

        return $number;
    }

    private function incrementSpecialPassword(int $number, int $upper): int
    {
        ++$number;

        while (!self::isNumberStillValid($number) && $number < $upper) {
            ++$number;
        }

        return $number;
    }

    private function isNumberValid(int $number): bool
    {
        $number = (string) $number;
        $prev = 0;
        $hasDoubleDigit = false;

        for ($i = 0; $i < strlen($number); ++$i) {
            $current = $number[$i];

            if ($current < $prev) {
                return false;
            }

            if ($current === $prev) {
                $hasDoubleDigit = true;
            }

            $prev = $current;
        }

        return $hasDoubleDigit;
    }

    private function isNumberStillValid(int $number): bool
    {
        $number = (string) $number;
        $prev = 0;
        $doubleMap = [];

        for ($i = 0; $i < strlen($number); ++$i) {
            $current = $number[$i];

            if ($current < $prev) {
                return false;
            }

            if ($current === $prev) {
                if (!array_key_exists($current, $doubleMap)) {
                    $doubleMap[$current] = 1;
                }

                ++$doubleMap[$current];
            }

            $prev = $current;
        }

        return in_array(2, $doubleMap);
    }
}
