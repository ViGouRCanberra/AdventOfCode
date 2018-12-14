<?php

$tardis = new Tardis();

echo "Part 1: " . $tardis->part1() . PHP_EOL;

class Tardis
{
    private $highestMarble = 25;
    private $players = 9;
    private $scores = [];

    public function part1()
    {
        $circle = [0, 2, 1, 3];
        $currentPlayer = 3;
        $coord = 3;

        for ($i = 4; $i <= $this->highestMarble; ++$i) {
            $currentPlayer = $this->getNextPlayer($currentPlayer);

            if ($i % 23 === 0) {
                $adjustments = $this->placeSpecialMarble($i, $circle, $coord, $currentPlayer);
            } else {
                $adjustments = $this->placeMarble($i, $circle, $coord);
            }

            $circle = $adjustments['circle'];
            $coord = $adjustments['position'];
        }

        return max($this->scores);
    }

    private function placeMarble(int $marble, array $circle, int $oldCoord): array
    {
        $firstCoord = $oldCoord + 2;
        $sizeofCircle = sizeof($circle);
        $currentCoord = $firstCoord > $sizeofCircle ? $firstCoord - $sizeofCircle : $firstCoord;

        array_splice($circle, $currentCoord, 0, $marble);

        return [
            'circle' => $circle,
            'position' => $currentCoord,
        ];
    }

    private function placeSpecialMarble(int $marble, array $circle, int $oldCoord, int $currentPlayer): array
    {
        $currentCoord = $oldCoord - 7;
        $currentCoord = $currentCoord < 0 ? sizeof($circle) + $currentCoord : $currentCoord;

        $this->scores[$currentPlayer] = $this->scores[$currentPlayer] ?? 0;
        $this->scores[$currentPlayer] += $marble + $circle[$currentCoord];

        unset($circle[$currentCoord]);

        return [
            'circle' => array_values($circle),
            'position' => $currentCoord
        ];
    }

    private function getNextPlayer(int $player): int
    {
        ++$player;

        return $player > $this->players ? 1 : $player;
    }
}