<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$insRead = new StreamReader($input);

echo "Part1: ";
echo "<br/>Part2: ";

class StreamReader
{
    public function __construct($input)
    {
    }
}
