<?php

function printGrid(string $grid) {
    echo chunk_split($grid, 5, "\n")."\n";
}

function biodiversity(string $grid) {
    $rv = 0;
    for ($i = 0, $j = 1; $i < 25; $i++, $j *= 2) {
        if ($grid[$i] == '#') {
            $rv += $j;
        }
    }
    return $rv;
}

$input = file_get_contents(__DIR__.'/../input/24.txt');
$initial = preg_replace('/[^.#]/', '', $input);

$seen = [];

$state = $initial;
while(!isset($seen[$state])) {
    $seen[$state] = true;

    $newState = $state;
    for ($i = 0; $i < 25; $i++) {
        $y = intdiv($i, 5);
        $x = $i % 5;
        $adjacent = 0;
        if ($y > 0 && $state[$i - 5] == '#') {
            $adjacent++;
        }
        if ($x > 0 && $state[$i - 1] == '#') {
            $adjacent++;
        }
        if ($x < 4 && $state[$i + 1] == '#') {
            $adjacent++;
        }
        if ($y < 4 && $state[$i + 5] == '#') {
            $adjacent++;
        }
        if ($state[$i] == '#' && $adjacent != 1) {
            $newState[$i] = '.';
        }
        if ($state[$i] == '.' && ($adjacent == 1 || $adjacent == 2)) {
            $newState[$i] = '#';
        }
    }
    $state = $newState;
}

echo "day24 part1: ".biodiversity($state)."\n";

function adjacent($x, $y, $z) {
    $adj = [];
    // above
    if ($y == 0) {
        $adj[] = [2, 1, $z - 1];
    } else if ($y == 3 && $x == 2) {
        $adj[] = [0, 4, $z + 1];
        $adj[] = [1, 4, $z + 1];
        $adj[] = [2, 4, $z + 1];
        $adj[] = [3, 4, $z + 1];
        $adj[] = [4, 4, $z + 1];
    } else {
        $adj[] = [$x, $y - 1, $z];
    }

    // below
    if ($y == 4) {
        $adj[] = [2, 3, $z - 1];
    } else if ($y == 1 && $x == 2) {
        $adj[] = [0, 0, $z + 1];
        $adj[] = [1, 0, $z + 1];
        $adj[] = [2, 0, $z + 1];
        $adj[] = [3, 0, $z + 1];
        $adj[] = [4, 0, $z + 1];
    } else {
        $adj[] = [$x, $y + 1, $z];
    }

    // left
    if ($x == 0) {
        $adj[] = [1, 2, $z - 1];
    } else if ($x == 3 && $y == 2) {
        $adj[] = [4, 0, $z + 1];
        $adj[] = [4, 1, $z + 1];
        $adj[] = [4, 2, $z + 1];
        $adj[] = [4, 3, $z + 1];
        $adj[] = [4, 4, $z + 1];
    } else {
        $adj[] = [$x - 1, $y, $z];
    }

    // right
    if ($x == 4) {
        $adj[] = [3, 2, $z - 1];
    } else if ($x == 1 && $y == 2) {
        $adj[] = [0, 0, $z + 1];
        $adj[] = [0, 1, $z + 1];
        $adj[] = [0, 2, $z + 1];
        $adj[] = [0, 3, $z + 1];
        $adj[] = [0, 4, $z + 1];
    } else {
        $adj[] = [$x + 1, $y, $z];
    }

    return $adj;
}

function get($grids, $x, $y, $z) {
    if ($x == 2 && $y == 2) {
        echo "invalid get: $x, $y, $z\n";
        exit();
    }
    if (isset($grids[$z])) {
        return $grids[$z][($y * 5) + $x];
    } else {
        return '.';
    }
}

$min = -1;
$max = 1;
$grids = [0 => $initial];

for ($gen = 0; $gen < 200; $gen++) {
    $newGrids = [];

    for ($z = $min; $z <= $max; $z++) {
        $newGrid = '............?............';
        for ($i = 0; $i < 25; $i++) {
            if ($i == 12) {
                continue;
            }
            $y = intdiv($i, 5);
            $x = $i % 5;
            $neighbors = adjacent($x, $y, $z);
            $adjacent = 0;
            foreach ($neighbors as $n) {
                if (get($grids, $n[0], $n[1], $n[2]) == '#') {
                    $adjacent++;
                }
            }
            $current = get($grids, $x, $y, $z);
            if ($current == '#' && $adjacent != 1) {
                $newGrid[$i] = '.';
            } else if ($current == '.' && ($adjacent == 1 || $adjacent == 2)) {
                $newGrid[$i] = '#';
            } else {
                $newGrid[$i] = $current;
            }
        }
        $newGrids[$z] = $newGrid;
    }

    $min--;
    $max++;
    $grids = $newGrids;
}

$bugs = 0;
foreach ($grids as $z => $grid) {
    $bugs += substr_count($grid, '#');
}
echo "day24 part2: $bugs\n";
