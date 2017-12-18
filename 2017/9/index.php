<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);
//$input= ['{a!{}{{}<{}!>{}>{}}}{<!!!><!a!>!!!r<!!>}'];

$insRead = new StreamReader($input);

echo "Part1: " . $insRead->getGroupScore();
echo "<br/>Part2: ";

class StreamReader
{
    protected $cleanInput = [];
    protected $score = 0;

    public function __construct($input)
    {
        $this->cleanUpInput($input[0]);
    }

    public function getGroupScore(): int
    {
        var_dump($this->cleanInput);

        return $this->score;
    }

    private function cleanUpInput($input)
    {
        $cleanString = '';
        $bracketCount = 0;
        $garbageZone = false;

        for ($i = 0; $i < strlen($input); $i++) {
            $char = $input[$i];
            $prevChar = 0 <= ($i-1) ? $input[$i-1] : '';
            $prevPrevChar = 0 <= ($i-2) ? $input[$i-2] : '';

            if ('!' != $prevChar || ('!' == $prevChar && '!' == $prevPrevChar)) {
                switch ($char) {
                    case '!':
                        break;
                    case '<':
                        $garbageZone = true;
                        break;
                    case '>':
                        $garbageZone = false;
                        break;
                    case '{':
                        if (!$garbageZone) {
                            $cleanString = $this->addChar($cleanString, '{');
                            ++$bracketCount;
                            $this->score += $bracketCount;
                        };
                        break;
                    case '}':
                        if (!$garbageZone) {
                            $cleanString = $this->addChar($cleanString, '}');
                            --$bracketCount;
                        };
                        break;
                }

                if (0 === $bracketCount && !empty($cleanString)) {
                    $this->cleanInput[] = $cleanString;
                    $cleanString = '';
                } elseif (0 > $bracketCount) {
                    $cleanString = '';
                    $bracketCount = 0;
                }
            }
        }
    }

    private function addChar(string $cleanString, string $char): string
    {
        $cleanString .= $char;

        return $cleanString;
    }
}
