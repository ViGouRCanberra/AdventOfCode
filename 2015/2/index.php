<?php

ini_set('display_errors', '1');
$stars = new Stars();
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

echo "Part1: " . $stars->part1($input) . PHP_EOL;
echo "Part2: " . $stars->part2($input) . PHP_EOL;

class Stars
{
    public function part1(array $input): int
    {
        $total = 0;

        foreach ($input as $sizes) {
            $sizes = explode('x', $sizes);

            $pieces = [
                $sizes[0] * $sizes[1],
                $sizes[1] * $sizes[2],
                $sizes[0] * $sizes[2],
            ];

            $total += min($pieces);

            foreach ($pieces as $piece) {
                $total += $piece * 2;
            }
        }

        return $total;
    }

    public function part2(array $input)
    {
        $total = 0;

        foreach ($input as $sizes) {
            $sizes = explode('x', $sizes);
            $length = 1;

            foreach ($sizes as $piece) {
                $length *= $piece;
            }

            $total += $length;

            sort($sizes);

            $total += $sizes[0] + $sizes[0] + $sizes[1] + $sizes[1];
        }

        return $total;
    }
}
