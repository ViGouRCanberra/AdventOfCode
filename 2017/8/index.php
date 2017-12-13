<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$insRead = new InstructionReader($input);

echo "Part1: ";

class InstructionReader
{
    protected $input;

    public function __construct($input)
    {
        $this->input = $input;
    }
}
