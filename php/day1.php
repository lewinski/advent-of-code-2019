<?php

function fuel1(int $x): int {
    return intdiv($x, 3) - 2;
}

function fuel2(int $x): int {
    $fuel = 0;
    while (true) {
        $f = fuel1($x);
        if ($f <= 0) {
            break;
        }
        $fuel += $f;
        $x = $f;
    }
    return $fuel;
}

$input = file(__DIR__.'/../input/1.txt');
$fuel1 = 0;
$fuel2 = 0;
foreach ($input as $line) {
    $line = intval($line);
    $fuel1 += fuel1($line);
    $fuel2 += fuel2($line);
}

echo "day1 part1: $fuel1\n";
echo "day1 part2: $fuel2\n";