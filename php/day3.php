<?php

$input = file_get_contents(__DIR__.'/../input/3.txt');
$lines = explode("\n", $input);

$wires = [];
$steps = [];

foreach ([$lines[0], $lines[1]] as $line) {
    $x = 0;
    $y = 0;
    $s = 0;
    $pos = [];
    $spos = [];
    foreach (explode(',', $line) as $path) {
        $dx = 0;
        $dy = 0;
        switch ($path[0]) {
        case 'U':
            $dy = 1;
            break;
        case 'D':
            $dy = -1;
            break;
        case 'R':
            $dx = 1;
            break;
        case 'L':
            $dx = -1;
            break;
        }
        for ($i = 0; $i < substr($path, 1); $i++) {
            $s++;
            $x += $dx;
            $y += $dy;
            $p = "$x,$y";
            $pos[] = $p;
            $spos[$p] = $spos[$p] ?? $s;
        }
    }
    $wires[] = $pos;
    $steps[] = $spos;
}

$same = array_intersect($wires[0], $wires[1]);

$mindist = 1e10;
$minsteps = 1e10;
foreach ($same as $xy) {
    list ($x, $y) = explode(',', $xy);
    $mindist = min($mindist, abs($x) + abs($y));
    $minsteps = min($minsteps, $steps[0][$xy] + $steps[1][$xy]);
}

echo "day3 part1: $mindist\n";
echo "day3 part2: $minsteps\n";
