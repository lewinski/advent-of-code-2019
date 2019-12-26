<?php

$input = file_get_contents(__DIR__.'/../input/20.txt');

$grid = explode("\n", trim($input, "\n"));

$paths = [];
$portals = [];
$portals_rev = [];

$ymax = count($grid);
for ($y = 0; $y < $ymax; $y++) {
    $xmax = strlen($grid[$y]);
    for ($x = 0; $x < $xmax; $x++) {
        if ($grid[$y][$x] == '.') {
            $paths["$x,$y"] = true;
        } else if (ctype_alpha($grid[$y][$x])) {
            $name = $position = null;
            if (($x + 2) < $xmax && ctype_alpha($grid[$y][$x + 1]) && $grid[$y][$x + 2] == '.') {
                $name = $grid[$y][$x] . $grid[$y][$x + 1];
                $position = sprintf("%d,%d", $x + 2, $y);
            } else if (($x + 1) < $xmax && ($x - 1) >= 0 && ctype_alpha($grid[$y][$x + 1]) && $grid[$y][$x - 1] == '.') {
                $name = $grid[$y][$x] . $grid[$y][$x + 1];
                $position = sprintf("%d,%d", $x - 1, $y);
            } else if (($y + 2) < $ymax && $x < strlen($grid[$y + 1]) && ctype_alpha($grid[$y + 1][$x]) && $grid[$y + 2][$x] == '.') {
                $name = $grid[$y][$x] . $grid[$y + 1][$x];
                $position = sprintf("%d,%d", $x, $y + 2);
            } else if (($y + 1) < $ymax && ($y - 1) >= 0 && $x < strlen($grid[$y - 1]) && $x < strlen($grid[$y + 1]) && ctype_alpha($grid[$y + 1][$x]) && $grid[$y - 1][$x] == '.') {
                $name = $grid[$y][$x] . $grid[$y + 1][$x];
                $position = sprintf("%d,%d", $x, $y - 1);
            }
            if (!is_null($name) && !is_null($position)) {
                $portals[$position] = $name;
                if (!isset($portals_rev[$name])) {
                    $portals_rev[$name] = [];
                }
                $portals_rev[$name][] = $position;
            }
        }
    }
}

$start = $portals_rev['AA'][0];
unset($portals_rev['AA']);
$end = $portals_rev['ZZ'][0];
unset($portals_rev['ZZ']);

function around($p) {
    list ($x, $y) = explode(',', $p);
    return [
        sprintf("%d,%d", $x, $y - 1),
        sprintf("%d,%d", $x - 1, $y),
        sprintf("%d,%d", $x + 1, $y),
        sprintf("%d,%d", $x, $y + 1),
    ];
}

$distances = [];
$heads = [$start];
$steps = 0;

while (count($heads)) {
    $newHeads = [];
    foreach ($heads as $head) {
        $distances[$head] = $steps;
        $next = around($head);
        foreach ($next as $p) {
            if (isset($distances[$p])) {
                continue;
            }
            if (isset($paths[$p])) {
                $newHeads[] = $p;
            }
        }
        if (isset($portals[$head], $portals_rev[$portals[$head]])) {
            $otherEnd = $portals_rev[$portals[$head]][0];
            if ($otherEnd == $head) {
                $otherEnd = $portals_rev[$portals[$head]][1];
            }
            if (!isset($distances[$otherEnd])) {
                $newHeads[] = $otherEnd;
            }
        }
    }
    $heads = $newHeads;
    $steps++;

    if (isset($distances[$end])) {
        echo "day20 part1: $distances[$end]\n";
        break;
    }
}

$distances = [];
$heads = ["0:$start"];
$steps = 0;

while (count($heads)) {
    $newHeads = [];
    foreach ($heads as $head) {
        $distances[$head] = $steps;
        list ($level, $pos) = explode(":", $head);
        $next = around($pos);
        foreach ($next as $p) {
            if (isset($distances["$level:$p"])) {
                continue;
            }
            if (isset($paths[$p])) {
                $newHeads[] = "$level:$p";
            }
        }
        if (isset($portals[$pos], $portals_rev[$portals[$pos]])) {
            $otherEnd = $portals_rev[$portals[$pos]][0];
            if ($otherEnd == $pos) {
                $otherEnd = $portals_rev[$portals[$pos]][1];
            }
            list ($x, $y) = explode(',', $otherEnd);
            $newLevel = $level - 1;
            if ($x > 5 && $x < 131 && $y > 5 && $y < 131) {
                $newLevel = $level + 1;
            }
            if ($newLevel <= 0 && !isset($distances["$newLevel:$otherEnd"])) {
                $newHeads[] = "$newLevel:$otherEnd";
            }
        }
    }
    $heads = $newHeads;
    $steps++;

    if (isset($distances["0:$end"])) {
        $answer = $distances["0:$end"];
        echo "day20 part2: $answer\n";
        break;
    }
}
