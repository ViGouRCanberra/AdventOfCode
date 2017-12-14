<?php
/*
 * DISCLAIMER:
 * Yes I was frustrated after a while, please don't judge the code.
 * Yes, part 1 was broken in the attempt on part 2 and I didn't want to fix part 1
 * because Borderlands 2 was much more tempting..
 */
//ini_set('display_errors', '1');

//$input = 1; //0
//$input = 12; //3
//$input = 23; //2
$input = 361527;

$shortestPath = new ShortestPath($input);
echo "Part 1: " . $shortestPath->getManhatanShortestPath() . "<br/>";
echo "<br /> Part 2: " . $shortestPath->getSummedSquareLargerThan();

class ShortestPath
{
    protected $input;
    protected $oneCoords;
    protected $lastCoords;

    public function __construct($input)
    {
        $this->input = $input;
    }

    private function findOneCoords($map)
    {
        $startAtRow = floor((sizeof($map) / 2) - 1);

        for ($i = 0; $i < 3; $i++) {
            $count = 0;

            foreach ($map[$startAtRow] as $digit) {
                if (1 == $digit) {
                    if (sizeof($map[0]) == 0) {
                        --$startAtRow;
                    }

                    return [$startAtRow, $count];
                }
                ++$count;
            }

            ++$startAtRow;
        }

        return [0, 0];
    }

    public function getManhatanShortestPath()
    {
        $map = [
            [3],
            [1,2]
        ];
        $direction = 'left';
        $lastEntryCoords = [0, 0];

        for ($i = 4; $i <= $this->input; $i++) {
            $columns0 = sizeof($map[0]);
            $columns1 = sizeof($map[1]);
            $rows = sizeof($map);

            if ('up' == $direction) {
                $rowToUpdate = $this->getNextUpRow($map, $rows);

                array_push($map[$rowToUpdate], $i);
                $lastEntryCoords = [$rowToUpdate, sizeof($map[$rowToUpdate])-1];

                if (0 === $rowToUpdate && $columns0 == $columns1) {
                    $direction = 'left';
                }

                if (0 === $rowToUpdate) {
                    array_unshift($map, []);
                    $direction = 'left';
                }
            } elseif ('left' == $direction) {
                array_unshift($map[0], $i);
                $lastEntryCoords = [0, 0];

                if ($columns0+1 > $columns1) {
                    $direction = 'down';
                }
            } elseif ('down' == $direction) {
                $rowToUpdate = $this->getNextDownRow($map);

                if ($rowToUpdate > $rows-1) {
                    array_push($map, []);
                    $direction = 'right';
                }

                array_unshift($map[$rowToUpdate], $i);
                $lastEntryCoords = [$rowToUpdate, 0];
            } elseif ('right' == $direction) {
                $lastRowNo = $rows-1;
                array_push($map[$lastRowNo], $i);
                $lastEntryCoords = [$lastRowNo, sizeof($map[$lastRowNo])-1];

                if (sizeof($map[$lastRowNo]) > sizeof($map[$lastRowNo-1])) {
                    $direction = 'up';
                }
            }

        }

        $lastCoords = $this->getLastCoords($lastEntryCoords, $map);
        $oneCoords = $this->findOneCoords($map);

//        $this->drawMap($map);
//        echo "<br/>One: " . $oneCoords[0] . ", " . $oneCoords[1];
//        echo "<br/>Last: " . $lastCoords[0] . ", " . $lastCoords[1];

        return abs($oneCoords[0] - $lastCoords[0]) + abs($oneCoords[1] - $lastCoords[1]);
    }

    public function getSummedSquareLargerThan()
    {
        $map = [
            [4,2],
            [1,1]
        ];
        $direction = 'left';

        for ($i = 7; $i <= $this->input; $i++) {
            $columns0 = sizeof($map[0]);
            $columns1 = sizeof($map[1]);
            $rows = sizeof($map);

            if ('up' == $direction) {
                $rowToUpdate = $this->getNextUpRow($map, $rows);

                $y = sizeof($map[$rowToUpdate])-1;
                array_push($map[$rowToUpdate], 0);

                $sum = $this->getAdjacentSum([$rowToUpdate, $y+1], $map);
                $map[$rowToUpdate][$y+1] = $sum;

                if (0 === $rowToUpdate && $columns0 == $columns1) {
                    $direction = 'left';
                }

                if (0 === $rowToUpdate) {
                    $array = [];
                    for ($c = 0; $c <= $columns0; $c++) {
                        array_push($array, 0);
                    }
                    array_unshift($map, $array);

                    $direction = 'left';
                }
            } elseif ('left' == $direction) {
                $y = $this->getZeroFilledNext($map[0]);

                if (-1 == $y) {
                    array_unshift($map[0], 0);
                    $map = $this->addPrecedingZeros($map, $rows);
                    $sum = $this->getAdjacentSum([0, 0], $map);
                    $map[0][0] = $sum;

                    $direction = 'down';
                } else {
                    $sum = $this->getAdjacentSum([0, $y], $map);
                    $map[0][$y] = $sum;
                }
            } elseif ('down' == $direction) {
                $rowToUpdate = $this->getNextDownRow($map);
                $sum = $this->getAdjacentSum([$rowToUpdate, 0], $map);
                $map[$rowToUpdate][0] = $sum;

                if ($rowToUpdate == $rows-1) {
                    array_push($map, [0]);
                    $sum = $this->getAdjacentSum([$rows, 0], $map);
                    $map[$rows][0] = $sum;
                    $direction = 'right';
                }
            } elseif ('right' == $direction) {
                $lastRowNo = $rows-1;
                $y = sizeof($map[$lastRowNo])-1;
                array_push($map[$lastRowNo], 0);
                $sum = $this->getAdjacentSum([$lastRowNo, $y+1], $map);
                $map[$lastRowNo][$y+1] = $sum;

                if (sizeof($map[$lastRowNo]) > sizeof($map[$lastRowNo-1])) {
                    $direction = 'up';
                }
            }
        }
        $this->drawMap($map);

        return 0;
    }

    private function getAdjacentSum($coords, $map)
    {
        $sum = 0;

        $digit = $map[$coords[0]-1][$coords[1]-1];
        if (isset($digit)) {
            $sum += $digit;
        }

        $digit = $map[$coords[0]-1][$coords[1]];
        if (isset($digit)) {
            $sum += $digit;
        }

        $digit = $map[$coords[0]-1][$coords[1]+1];
        if (isset($digit)) {
            $sum += $digit;
        }

        $digit = $map[$coords[0]][$coords[1]-1];
        if (isset($digit)) {
            $sum += $digit;
        }

        $digit = $map[$coords[0]][$coords[1]+1];
        if (isset($digit)) {
            $sum += $digit;
        }

        $digit = $map[$coords[0]+1][$coords[1]-1];
        if (isset($digit)) {
            $sum += $digit;
        }

        $digit = $map[$coords[0]+1][$coords[1]];
        if (isset($digit)) {
            $sum += $digit;
        }

        $digit = $map[$coords[0]+1][$coords[1]+1];
        if (isset($digit)) {
            $sum += $digit;
        }

        if (361527 < $sum) {
            echo $sum;die;
        }

        return $sum;
    }

    private function drawMap($map)
    {
        echo "<br/>";
        foreach ($map as $row) {
            foreach ($row as $digit) {
                echo "<div style='width: 35px; float: left;'>" . $digit . "</div>";
            }
            echo "<br />";
        }
        echo "***************************************";
    }

    private function getNextUpRow($map, $rows)
    {
        $row = sizeof($map[$rows-1]);

        for ($i = $rows-1; $i >= 0; $i--) {
            $currentRow = sizeof($map[$i]);

            if ($currentRow < $row) {
                return $i;
            }
        }

        return 0;
    }

    private function getNextDownRow($map)
    {
        $rowNo = 0;

        foreach ($map as $row) {
            if (0 !== $rowNo) {
                if (0 == $map[$rowNo][0]) {
                    return $rowNo;
                } else {
                    ++$rowNo;
                }
            } else {
                ++$rowNo;
            }
        }

        return $rowNo;
    }

    private function getLastCoords($lastEntryCoords, $map)
    {
        if (0 == $lastEntryCoords[0] && sizeof($map[0]) !== 0) {
            $column = sizeof($map[1]) - sizeof($map[0]);

            return [0, $column];
        }

        return $lastEntryCoords;
    }

    private function getZeroFilledNext($array)
    {
        $y = -1;

        foreach ($array as $digit) {
            if (0 !== $digit) {
                return $y;
            }
            ++$y;
        }

        return $y;
    }

    private function addPrecedingZeros($map, $rows)
    {
        for ($i = 1; $i < $rows; $i++) {
            array_unshift($map[$i], 0);
        }

        return $map;
    }
}
