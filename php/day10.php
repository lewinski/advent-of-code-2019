<?php

$input = file_get_contents(__DIR__.'/../input/10.txt');

$asteroids = [];

$i = 0;
$y = 0;
foreach (explode("\n", $input) as $line) {
    $x = 0;
    foreach (str_split($line) as $char) {
        if ($char == '#') {
            $asteroids[] = [$i, $x, $y];
            $i++;
        }
        $x++;
    }
    $y++;
}

$detected = [];
$max = 0;
$maxi = 0;
$maxseen = [];
foreach ($asteroids as $a1) {
    $seen = [];
    $dist = [];
    foreach ($asteroids as $a2) {
        if ($a1[0] == $a2[0]) {
            continue;
        }
        $x = $a2[1] - $a1[1];
        $y = $a2[2] - $a1[2];
        $angle = sprintf("%.20g", atan2($y, $x));
        $dist2 = $x * $x + $y * $y;
        if (isset($dist[$angle])) {
            if ($dist2 < $dist[$angle]) {
                $dist[$angle] = $dist2;
                $seen[$angle] = $a2[0];
            }
        } else {
            $dist[$angle] = $dist2;
            $seen[$angle] = $a2[0];
        }
    }
    $detected[$a1[0]] = count($seen);
    if (count($seen) > $max) {
        $max = count($seen);
        $maxi = $a1[0];
        $maxseen = $seen;
    }
}

echo "day10 part1: $max\n";

$angles = array_keys($maxseen);
usort($angles, function ($a, $b) {
    $a = floatval($a);
    if ($a < -1.570797) {
        $a += M_PI * 2;
    }
    $b = floatval($b);
    if ($b < -1.570797) {
        $b += M_PI * 2;
    }
    if ($a > $b) {
        return 1;
    } else {
        return -1;
    }
});

$part2 = $asteroids[$maxseen[$angles[199]]];
echo "day10 part2: ".($part2[1] * 100 + $part2[2])."\n";