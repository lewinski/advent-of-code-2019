<?php

require_once __DIR__.'/lib/intcode.php';

$input = file_get_contents(__DIR__.'/../input/19.txt');

$affected = 0;
for ($y = 0; $y < 50; $y++) {
    for ($x = 0; $x < 50; $x++) {
        $computer = new \Intcode\Computer($input);
        $computer->input([$x, $y]);
        $computer->run();
        $out = $computer->output();
        $affected += $out;
    }
}
echo "day19 part1: $affected\n";

function covered($x, $y, $extents) {
    if (isset($extents[$y])) {
        if ($extents[$y]['start'] <= $x && $extents[$y]['stop'] >= $x) {
            return true;
        }
    }
    return false;
}

$size = 100;
$extents = [];
for ($y = 5;; $y++) { // first few rows are weird
    $start = null;
    $stop = null;

    $startx = isset($extents[$y - 1]) ? $extents[$y - 1]['start'] : 0;
    for ($x = $startx;; $x++) {
        $computer = new \Intcode\Computer($input);
        $computer->input([$x, $y]);
        $computer->run();
        $out = $computer->output();
        if ($out == 1 && is_null($start)) {
            $start = $x;
        }
        if ($out == 0 && !is_null($start) && is_null($stop)) {
            $stop = $x - 1;
            break;
        }
    }
    $extents[$y] = ['start' => $start, 'stop' => $stop];

    if ($stop - $start >= $size) {
        $top = $y - ($size - 1);
        $bottom = $y;
        $left = $start;
        $right = $start + ($size - 1);
        if (covered($left, $top, $extents) &&
            covered($right, $top, $extents) &&
            covered($left, $bottom, $extents) &&
            covered($right, $bottom, $extents))
        {
            echo "day19 part2: ".(($left * 10000)+$top)."\n";
            exit();
        }
    }
}
