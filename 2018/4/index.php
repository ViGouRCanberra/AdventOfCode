<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);
//$input = file("test.txt", FILE_IGNORE_NEW_LINES);

$calc = new Tardis($input);

//echo "Part1: " . $calc->strategy1();
echo "<br/>Part2: " . $calc->strategy2();

class Tardis {
    private $input;
    private $format = '[Y-m-d H:i';
    private $guardTable = [];

    public function __construct(array $input)
    {
        $this->input = $this->orderInput($input);

        $this->calculateTimetable();
    }

    public function calculateTimetable(): void
    {
        $currentGuard = 0;

        foreach ($this->input as $entry) {
            $match = preg_match('/(?<=Guard #)(\d+)(?= begins)/', $entry, $guardMatch);
            $timestamp = DateTime::createFromFormat($this->format, explode(']', $entry)[0]);

            if ($match) {
                $currentGuard = $guardMatch[0];
            } else {
                if (strpos($entry, ' wakes up')) {
                    $this->addMinutesAsleep($this->guardTable[$currentGuard], $timestamp);
                }
            }

            $this->guardTable[$currentGuard]['currentTime'] = $timestamp;
        }
    }

    public function strategy2(): int
    {
        $highestMinute = 0;
        $maxGuard = 0;
        $maxMinute = 0;

        foreach ($this->guardTable as $guard => $table) {
            if (isset($table['timeBar'])) {
                $guardMaxMinute = array_search(max($table['timeBar']), $table['timeBar']);

                if ($table['timeBar'][$guardMaxMinute] > $highestMinute) {
                    $highestMinute = $table['timeBar'][$guardMaxMinute];
                    $maxGuard = $guard;
                    $maxMinute = $guardMaxMinute;
                }
            }
        }

        return $maxGuard * $maxMinute;
    }

    private function addMinutesAsleep(array &$guardTable, DateTime $timestamp): void
    {
        $interval = $guardTable['currentTime']->diff($timestamp);
        $startMinute = (int) $guardTable['currentTime']->format('i');

        for ($i = $startMinute; $i < ($interval->i + $startMinute); ++$i) {
            $guardTable['timeBar'][$i] = $guardTable['timeBar'][$i] ?? 0;
            $guardTable['timeBar'][$i] = ++$guardTable['timeBar'][$i];
        }
    }

    private function orderInput(array $input): array
    {
        usort($input, function(string $dateLeft, string $dateRight): int
        {
            $dateLeft = DateTime::createFromFormat($this->format, explode(']', $dateLeft)[0]);
            $dateRight = DateTime::createFromFormat($this->format, explode(']', $dateRight)[0]);

            return ($dateLeft < $dateRight) ? -1 : 1;
        });

        return $input;
    }
}