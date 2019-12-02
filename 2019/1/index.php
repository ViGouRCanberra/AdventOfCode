<?php

ini_set('display_errors', '1');
$stars = new Space();
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

echo "Part1: " . $stars->part1($input) . PHP_EOL;
echo "Part2: " . $stars->part2($input) . PHP_EOL;

class Space
{
    public function part1(array $input): int
    {
        $fuel = 0;

        foreach ($input as $amount) {
            $fuel += self::getReqFuel($amount);
        }

        return $fuel;
    }

    public function part2(array $input)
    {
        $totalFuel = 0;

        foreach ($input as $amount) {
            $fuel = self::getReqFuel($amount);
            $fuelsFuel = self::getRecursiveFuel(0, $fuel);

            $totalFuel += ($fuel + $fuelsFuel);
        }

        return $totalFuel;
    }

    private function getReqFuel(int $amount): int
    {
        return floor($amount / 3) - 2;
    }

    private function getRecursiveFuel(int $total, int $amount): int
    {
        if (0 >= $additional = self::getReqFuel($amount)) {
            return $total;
        }

        $total += $additional;

        return self::getRecursiveFuel($total, $additional);
    }
}

//Fuel required to launch a given module is based on its mass. Specifically, to find the fuel required for a module, take its mass, divide by three, round down, and subtract 2.