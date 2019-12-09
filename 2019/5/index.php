<?php

ini_set('display_errors', '1');
$stars = new Space();
$input = explode(',', file("input.txt", FILE_IGNORE_NEW_LINES)[0]);

echo "Part1: " . $stars->part1($input) . PHP_EOL;
echo "Part2: " . $stars->part2($input) . PHP_EOL;

class Space
{
    private int $noun = 0;
    private int $verb = 0;

    public function part1(array $input): int
    {
        $current = $input[0];

        self::restoreProgram($input);
        self::run($current, $input);

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

    private function run(int $current, array $input): void
    {
        $pointer = 0;
        $instruction = new Instruction($current);
        $opCode = $instruction->getOpCode();

        while ($opCode != 99) {
            switch ($opCode) {
                case 1:
                    self::addUp($pointer, $input, $instruction);
                    break;
                case 2:
                    self::multiply($pointer, $input, $instruction);
                    break;
                case 3:
                    self::move($pointer, $input, $instruction);
                    break;
                case 4:
                    self::output($pointer, $input, $instruction);
                    break;
                default:
                    die("HOUSTON, WE HAVE A PROBLEM - opCode: $opCode (current: $current)\n");
            }

            $pointer += $instruction->getIncreasePointerBy();
            $instruction = new Instruction($input[$pointer]);
        }
    }

    private function repeatRun(array $input): array
    {
        $current = $input[0];

        self::restoreProgramCounter($input);
        self::run($current, $input);

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

    private function addUp(int $index, array &$input, Instruction $opCode): void
    {
        $param1 = $input[$input[$index + 1]];
        $param2 = $input[$input[$index + 2]];

        $input[$input[$index + 3]] = $param1 + $param2;
    }

    private function multiply(int $index, array &$input, Instruction $opCode): void
    {
        $param1 = $input[$input[$index + 1]];
        $param2 = $input[$input[$index + 2]];

        $input[$input[$index + 3]] = $param1 * $param2;
    }

    private function move(int $index, array &$input, Instruction $opCode): void
    {
        $param1 = $input[$index + 1];

        $input[$input[$index + 2]] = $param1;
    }

    private function output(int $index, array &$input, Instruction $opCode): int
    {
        return $input[$input[$index + 2]];
    }
}

class Instruction
{
    private int $increasePointerBy = 0;
    private int $opCode = 0;
    private array $parameters = [];

    public function __construct(int $current)
    {
        $current = (string) $current;
        $length = strlen($current);

        $this->increasePointerBy = $length;
        $this->opCode = 1 === $length ? $current : (int) ($current[$length - 2] . $current[$length - 1]);
    }

    public function getIncreasePointerBy(): int
    {
        return $this->increasePointerBy;
    }

    public function getOpCode(): int
    {
        return $this->opCode;
    }

    public function getParams(): array
    {
        return $this->parameters;
    }
}
