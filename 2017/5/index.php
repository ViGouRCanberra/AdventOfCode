<?php
ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$escapeMap = new EscapeMap($input);
echo "Part1: " . $escapeMap->getEscapeStepCount();

class EscapeMap
{
    protected $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function getEscapeStepCount(): int
    {
        $step = 0;
        $currentPosition = 0;
        $mazeSize = sizeof($this->input);

        while ($mazeSize > $currentPosition && $currentPosition >= 0) {
            $digit = $this->input[$currentPosition];

            $currentPosition = $this->jump($currentPosition, $digit);
            ++$step;
        }

        return $step;
    }

    protected function jump($currentPosition, $instruction): int
    {
        if (3 <= $instruction) {
            --$this->input[$currentPosition];
        } else {
            ++$this->input[$currentPosition];
        }

        $currentPosition += $instruction;

        return $currentPosition;
    }
}
