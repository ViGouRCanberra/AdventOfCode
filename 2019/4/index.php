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
        $validOptions = 0;
        $lower = $input[0];
        $upper = $input[1];

        while ($lower < $upper) {
            $lower = self::incrementSpecialPassword($lower);
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

    private function incrementSpecialPassword(int $number): int
    {
        ++$number;

        while (!self::isNumberStillValid($number)) {
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
        $hasDoubleDigit = false;

        for ($i = 0; $i < strlen($number); ++$i) {
            $current = $number[$i];

            if ($current < $prev) {
                return false;
            }

            if ($current === $prev) {
                $hasDoubleDigit = true;

                if ($i > 1) {
                    $prevPrev = $number[$i - 2] ?? null;
                    $nextNext = $number[$i + 1] ?? null;

                    if (null !== $prevPrev && null !== $nextNext) {
                        if ($prevPrev === $nextNext && $prev === $prevPrev) {
                            ++$i;
                        } if ($prevPrev !== $nextNext && ($prevPrev === $prev || $current === $nextNext)) {
                            $hasDoubleDigit = false;
                        }
                    }
                }
            }

            $prev = $current;
        }

        return $hasDoubleDigit;
    }
}
;
