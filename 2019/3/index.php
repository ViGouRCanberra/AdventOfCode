<?php

ini_set('display_errors', '1');
$stars = new Space();
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

echo $stars->part($input) . PHP_EOL;

class Space
{
    private $currentX = 0;
    private $currentY = 0;
    private $currentWire = 1;
    private $intersections = [];
    private $steps = 0;

    /**
     * first key - left/right
     * nested key - up/down
     * @var array
     */
    private $grid = [];

    public function part(array $input): string
    {
        $input[0] = explode(',', $input[0]);
        $input[1] = explode(',', $input[1]);

        foreach ($input as $wire) {
            foreach ($wire as $section) {
                $number = (int) substr($section, 1);

                switch ($section[0]) {
                    case 'R':
                        self::goRight($number);
                        break;
                    case 'U':
                        self::goUp($number);
                        break;
                    case 'D':
                        self::goDown($number);
                        break;
                    case 'L':
                        self::goLeft($number);
                        break;
                }
            }

            ++$this->currentWire;
            $this->currentX = 0;
            $this->currentY = 0;
            $this->steps = 0;
        }

        /** @var Intersection $firstIntersection */
        $firstIntersection = array_shift($this->intersections);
        $currentShortest = $firstIntersection->distance();
        $currentLeast = $firstIntersection->steps;

        /** @var Intersection $intersection */
        foreach ($this->intersections as $intersection) {
            $distance = $intersection->distance();
            $steps = $intersection->steps;

            $currentShortest = $currentShortest > $distance ? $distance : $currentShortest;
            $currentLeast = $currentLeast > $steps ? $steps : $currentLeast;
        }

        return "Part1: $currentShortest, Part2: $currentLeast";
    }

    private function goUp(int $amount): void
    {
        for ($i = 0; $i < $amount; ++$i) {
            ++$this->currentY;
            self::addSection();
        }
    }

    private function goRight(int $amount): void
    {
        for ($i = 0; $i < $amount; ++$i) {
            ++$this->currentX;
            self::addSection();
        }
    }

    private function goDown(int $amount): void
    {
        for ($i = 0; $i < $amount; ++$i) {
            --$this->currentY;
            self::addSection();
        }
    }

    private function goLeft(int $amount): void
    {
        for ($i = 0; $i < $amount; ++$i) {
            --$this->currentX;
            self::addSection();
        }
    }

    private function addSection(): void
    {
        ++$this->steps;

        if (!array_key_exists($this->currentX, $this->grid)) {
            $this->grid[$this->currentX] = [];
        }

        if (!array_key_exists($this->currentY, $this->grid[$this->currentX])) {
            $this->grid[$this->currentX][$this->currentY] = new Wire();
        }

        1 === $this->currentWire
            ? $this->grid[$this->currentX][$this->currentY]->addWire1($this->steps)
            : $this->grid[$this->currentX][$this->currentY]->addWire2($this->steps);

        if ($this->grid[$this->currentX][$this->currentY]->isBothPresent()) {
            $this->intersections[] = new Intersection($this->currentX, $this->currentY, $this->grid[$this->currentX][$this->currentY]->getSteps());
        }
    }
}

class Wire
{
    public $wire1 = false;
    public $wire2 = false;
    public $wire1Steps = 99999999999999;
    public $wire2Steps = 99999999999999;

    public function isBothPresent(): bool
    {
        return $this->wire1 && $this->wire2;
    }

    public function addWire1(int $steps): void
    {
        $this->wire1 = true;
        $this->wire1Steps = $this->wire1Steps > $steps ? $steps : $this->wire1Steps;
    }

    public function addWire2(int $steps): void
    {
        $this->wire2 = true;
        $this->wire2Steps = $this->wire2Steps > $steps ? $steps : $this->wire2Steps;
    }

    public function getSteps(): int
    {
        return $this->wire1Steps + $this->wire2Steps;
    }
}

class Intersection
{
    private $x = null;
    private $y = null;

    public $steps = 0;

    public function __construct($x, $y, $steps)
    {
        $this->x = $x;
        $this->y = $y;
        $this->steps = $steps;
    }

    public function distance(): int
    {
        return abs($this->x) + abs($this->y);
    }
}
