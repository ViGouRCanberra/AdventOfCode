<?php

ini_set('display_errors', '1');
$input = file("input2.txt", FILE_IGNORE_NEW_LINES);

$insRead = new InstructionReader($input);

echo "Part1: " . $insRead->getLargestRegisterValue();
echo "<br/>Part2: " . $insRead->getHighestVal();

class InstructionReader
{
    protected $register = [];
    protected $highestVal = 0;

    public function __construct($input)
    {
        $this->runAllInstructions($input);
    }

    public function getLargestRegisterValue(): int
    {
        return max($this->register);
    }

    public function getHighestVal(): int
    {
        return $this->highestVal;
    }

    private function runAllInstructions($instructions): void
    {
        foreach ($instructions as $instruction) {
            $this->evaluateInstruction($instruction);
        }
    }

    private function evaluateInstruction($instruction): void
    {
        $tokens = explode(' ', $instruction);

        $this->addRegisters($tokens[0], $tokens[4]);

        if ($this->evaluateCondition($tokens[4], $tokens[5], $tokens[6])) {
            $this->modifyRegister($tokens[0], $tokens[1], $tokens[2]);
        }
    }

    private function addRegisters(...$registers): void
    {
        foreach ($registers as $register) {
            if (!array_key_exists($register, $this->register)) {
                $this->register[$register] = 0;
            }
        }
    }

    private function evaluateCondition($register, $comparator, $value): bool
    {
        $value = (int) $value;

        switch ($comparator) {
            case '>':
                return $this->register[$register] > $value;
            case '<';
                return $this->register[$register] < $value;
            case '>=';
                return $this->register[$register] >= $value;
            case '<=';
                return $this->register[$register] <= $value;
            case '==';
                return $this->register[$register] == $value;
            case '!=';
                return $this->register[$register] != $value;
        }

        return false;
    }

    private function modifyRegister($register, $instruction, $value)
    {
        if ('inc' == $instruction) {
            $this->register[$register] += (int) $value;
        } elseif ('dec' == $instruction) {
            $this->register[$register] -= (int) $value;
        }

        if ($this->highestVal < $this->register[$register]) {
            $this->highestVal = $this->register[$register];
        }
    }
}
