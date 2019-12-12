<?php

$orbits = [];
function anc($x) {
    global $orbits;
    $anc = [];
    while (isset($orbits[$x])) {
        $x = $orbits[$x];
        $anc[] = $x;
    }
    return $anc;
}

$input = file(__DIR__.'/../input/6.txt');
foreach ($input as $line) {
    list ($a, $b) = explode(')', trim($line));
    $orbits[$b] = $a;
}

$count = 0;
foreach (array_keys($orbits) as $x) {
    $count += count(anc($x));
}
echo "day6 part1: $count\n";

$anc1 = anc('YOU');
$anc2 = anc('SAN');
while (end($anc1) == end($anc2)) {
    array_pop($anc1);
    array_pop($anc2);
}
echo "day6 part2: " . (count($anc1) + count($anc2)) . "\n";
