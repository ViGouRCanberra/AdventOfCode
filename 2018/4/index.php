<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);
//$input = file("test.txt", FILE_IGNORE_NEW_LINES);

$calc = new Tardis($input);

echo "Part1: " . $calc->strategy1();
//echo "<br/>Part2: " . $calc->();

class Tardis {
    private $input;
    private $format = '[Y-m-d H:i';
    private $guardTable = [];

    public function __construct(array $input)
    {
        $this->input = $this->orderInput($input);
    }

    public function strategy1(): int
    {
        $currentGuard = 0;

        foreach ($this->input as $entry) {
            $match = preg_match('/(?<=Guard #)(\d+)(?= begins)/', $entry, $guardMatch);
            $timestamp = DateTime::createFromFormat($this->format, explode(']', $entry)[0]);

            if ($match) {
                $currentGuard = $guardMatch[0];
                $this->guardTable[$currentGuard]['totalTime'] = $this->guardTable[$currentGuard]['totalTime'] ?? 0;
                $this->guardTable[$currentGuard]['timeBar'][] = [];
            } else {
                $this->calculateTotalHours($this->guardTable[$currentGuard], $timestamp, $entry);
            }

            $this->guardTable[$currentGuard]['currentTime'] = $timestamp;
        }

        $longestAsleep = $this->getLongestAsleepGuard($this->guardTable);
        $matchedHours = [];

        for ($i = 1; $i < sizeof($this->guardTable[$longestAsleep]['timeBar']); ++$i) {

            for ($j = 0; $j <= 60; ++$j) {
                if (isset($this->guardTable[$longestAsleep]['timeBar'][$i][$j]) && isset($this->guardTable[$longestAsleep]['timeBar'][0][$j])) {
                    if ('#' === $this->guardTable[$longestAsleep]['timeBar'][0][$j] && $this->guardTable[$longestAsleep]['timeBar'][$i][$j] === $this->guardTable[$longestAsleep]['timeBar'][0][$j]) {
                        $matchedHours[] = $j;
                    }
                }
            }
        }

        $values = array_count_values($matchedHours);
        arsort($values);

        return $longestAsleep * (array_values(array_flip($values))[0] ?? 0);
    }

    private function getLongestAsleepGuard(array $table): string
    {
        $longestAsleep = 0;
        $timeAsleep = 0;

        foreach ($table as $key => $guard) {
            if ($timeAsleep < $guard['totalTime']) {
                $longestAsleep = $key;
                $timeAsleep = $guard['totalTime'];
            }

            foreach ($guard['timeBar'] as $timeKey => $timeBar) {
                echo "Guard #$key: ";

                foreach ($timeBar as $mark) {
                    if ('x' === $mark) {
                        array_shift($this->guardTable[$key]['timeBar'][$timeKey]);
                    } else {
                        echo "<div style='width: 10px;display: inline-block'>$mark</div>";
                    }
                }

                echo '<br>';
            }
        }

        return $longestAsleep;
    }

    private function calculateTotalHours(array &$guardEntry, DateTime $timestamp, string $entry): void
    {
        $diffDate = $guardEntry['currentTime']->diff($timestamp);

        if (0 === sizeof($guardEntry['timeBar'][sizeof($guardEntry['timeBar'])-1])) {
            $midnight = DateTime::createFromFormat("Y-m-d H:i", $guardEntry['currentTime']->format('Y-m-d') . '00:00');

            if ('23' === $guardEntry['currentTime']->format('H')) {
                $midnight->modify('+1 day');
            }

            $minutesToMidnight = $midnight->diff($guardEntry['currentTime'])->i;

            if ($midnight > $guardEntry['currentTime']) {
                $this->addMarkersToTimeBar($guardEntry['timeBar'], 'x', $minutesToMidnight);
            } else {
                $this->addMarkersToTimeBar($guardEntry['timeBar'], '.', $minutesToMidnight);
            }
        }

        if (strpos($entry, ' wakes up')) {
            $this->addMarkersToTimeBar($guardEntry['timeBar'], '#', $diffDate->i);
            $guardEntry['totalTime'] += $diffDate->i;
        } else {
            $this->addMarkersToTimeBar($guardEntry['timeBar'], '.', $diffDate->i);
        }
    }

    private function addMarkersToTimeBar(array &$timeBar, string $marker, int $minutes): void
    {
        $key = sizeof($timeBar)-1;

        for ($i = 0; $i < $minutes; ++$i) {
            $timeBar[$key][] = $marker;
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