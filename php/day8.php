<?php

$input = file_get_contents(__DIR__.'/../input/8.txt');
$input = trim($input);

$h = 6;
$w = 25;

$layers = str_split($input, $h * $w);

$min = $h * $w + 1;
$part1 = 0;
foreach ($layers as $layer) {
    $counts = [];
    foreach (str_split($layer) as $c) {
        $counts[$c]++;
    }
    if ($counts[0] < $min) {
        $min = $counts[0];
        $part1 = $counts[1] * $counts[2];
    }
}

echo "day8 part1: $part1\n";

$trans = '?';
$black = ' ';
$white = '#';

$picture = str_pad('', $h * $w, $trans);
foreach ($layers as $layer) {
    for ($i = 0; $i < $h * $w; $i++) {
        if ($picture[$i] == $trans) {
            if ($layer[$i] == 0) {
                $picture[$i] = $black;
            } elseif ($layer[$i] == 1) {
                $picture[$i] = $white;
            }
        }
    }
}

echo "day8 part2: \n";
$lines = str_split($picture, $w);
foreach ($lines as $line) {
    echo "$line\n";
}