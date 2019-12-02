<?php

ini_set('display_errors', '1');
$stars = new Space();
$input = explode(',', file("input.txt", FILE_IGNORE_NEW_LINES)[0]);

echo "Part1: " . $stars->part1($input) . PHP_EOL;
echo "Part2: " . $stars->part2($input) . PHP_EOL;

class Space
{
    private $noun = 0;
    private $verb = 0;

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
                    echo "HOUSTON, WE HAVE A PROBLEM\n";
                    return -1;
            }

            $index += 3;
            $current = $input[$index];
        }

        return $input[0];
    }

    public function part2(array $input)
    {
        $target = 19690720;
        $answer = self::repeatRun($input);

        while ($target !== $answer[0]) {
            $answer = self::repeatRun($input);
        }

        return 100 * $answer[1] + $answer[2];
    }

    private function repeatRun($input): array
    {
        $index = 0;
        $current = $input[0];

        self::restoreProgramCounter($input);

        while ($current != 99) {
            switch ($current) {
                case 1:
                    self::addUp(++$index, $input);
                    break;
                case 2:
                    self::multiply(++$index, $input);
                    break;
                default:
                    die("Part2: HOUSTON, WE HAVE A PROBLEM\n");
            }

            $index += 3;
            $current = $input[$index];
        }

        return $input;
    }

    private function restoreProgram(array &$input): void
    {
        $input[1] = 12;
        $input[2] = 2;
    }

    private function restoreProgramCounter(array &$input): void
    {
        if (99 === $this->noun) {
            $input[1] = $this->noun = 0;
            $input[2] = ++$this->verb;

            if (100 === $this->verb) {
                die("Part2: Not Found\n");
            }
        }

        $input[2] = $this->verb;
        $input[1] = ++$this->noun;
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
