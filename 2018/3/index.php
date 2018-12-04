<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

//$input = [
//    '#1 @ 1,3: 4x4',
//    '#2 @ 3,1: 4x4',
//    '#3 @ 5,5: 2x2',
//];

$calc = new tardis($input);

echo "Part1: " . $calc->getOverlappingSqInches();
//echo "<br/>Part2: " . $calc->();

class tardis {
    private $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function getOverlappingSqInches(): int
    {
        $sqInches = 0;
        $canvas = $this->drawEmptyCanvas();

        foreach ($this->cleanUpInput($this->input) as $pattern) {
            for ($column = 0; $column < $pattern['width']; ++$column ) {
                for ($row = 0; $row < $pattern['height']; ++$row) {
                    if (1 === sizeof($canvas[$column + $pattern['leftEdge']][$row + $pattern['topEdge']])) {
                        ++$sqInches;
                    }

                    $canvas[$column + $pattern['leftEdge']][$row + $pattern['topEdge']][] = $pattern['id'];
                }
            }
        }

        return $sqInches;
    }

    private function drawEmptyCanvas(int $size = 1000): array
    {
        $row = array_fill(0, $size, []);
        $canvas = array_fill(0, $size, $row);

        return $canvas;
    }

    private function cleanUpInput($input)
    {
        for ($i = 0; $i < sizeof($input); ++$i) {
            $values = preg_split('/[#( @ ),(: )x]/', $input[$i], -1, PREG_SPLIT_NO_EMPTY);

            $input[$i] = [
                'id' => $values[0],
                'leftEdge' => $values[1],
                'topEdge' => $values[2],
                'width' => $values[3],
                'height' => $values[4],
            ];
        }

        return $input;
    }
}