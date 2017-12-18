<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

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
        return $this->score;
    }

    private function cleanUpInput($input)
    {
        $cleanString = '';
        $bracketCount = 0;
        $garbageZone = false;

        for ($i = 0; $i < strlen($input); $i++) {
            $char = $input[$i];

            if ($this->isNotCancelled($input, $i)) {
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

    private function isNotCancelled(string $input, int $i): bool
    {
        $counter = $i;
        $exclamationCount = 0;

        while (true) {
            --$counter;
            $char = 0 <= $counter ? $input[$counter] : '';

            if ('!' == $char) {
                ++$exclamationCount;
            } else {
                break;
            }
        }

        return $exclamationCount % 2 == 0;
    }
}
