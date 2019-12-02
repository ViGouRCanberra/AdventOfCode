<?php

ini_set('display_errors', '1');
$stars = new Space();
$input = explode(',', file("input.txt", FILE_IGNORE_NEW_LINES)[0]);

echo "Part1: " . $stars->part1($input) . PHP_EOL;
echo "Part2: " . $stars->part2($input) . PHP_EOL;

class Space
{
    public function part1(array $input): int
    {
        $index = 0;
        $current = $input[0];

        self::restoreProgram($input);

        while ($current != 99) {
            switch ($current) {
                case 1:
                    self::addUp(++$index, $input);
                    break;
                case 2:
                    self::multiply(++$index, $input);
                    break;
                default:
                    throw new \Exception('HOUSTON, WE HAVE A PROBLEM');
            }

            $index += 3;
            $current = $input[$index];
        }

        return $input[0];
    }

    public function part2(array $input)
    {
        $target = 19690720;
        $index = 0;
        $current = $input[0];

        self::restoreProgram($input);

        while ($current != 99 && $input[0] !== $target) {
            switch ($current) {
                case 1:
                    self::addUp(++$index, $input);
                    break;
                case 2:
                    self::multiply(++$index, $input);
                    break;
                default:
                    throw new \Exception('HOUSTON, WE HAVE A PROBLEM');
            }

            $index += 3;
            $current = $input[$index];
        }

        return 100 * $input[1] + $input[2];
    }

    private function restoreProgram(array &$input): void
    {
        $input[1] = 12;
        $input[2] = 2;
    }

    private function addUp(int $index, array &$input): void
    {
        $input[$input[$index + 2]] = $input[$input[$index]] + $input[$input[$index + 1]];
    }

    private function multiply(int $index, array &$input): void
    {
        $input[$input[$index + 2]] = $input[$input[$index]] * $input[$input[$index + 1]];
    }
}
