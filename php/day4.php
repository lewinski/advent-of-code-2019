<?php

function ok($str) {
    $d = str_split("$str");
    if ($d[0] == $d[1] || $d[1] == $d[2] || $d[2] == $d[3] || $d[3] == $d[4] || $d[4] == $d[5]) {
        if ($d[0] <= $d[1] && $d[1] <= $d[2] && $d[2] <= $d[3] && $d[3] <= $d[4] && $d[4] <= $d[5]) {
            return true;
        }
    }
    return false;
}

function ok2($str) {
    for ($i = 0; $i < 10; $i++) {
        if (strstr($str, "$i$i") && !strstr($str, "$i$i$i")) {
            return true;
        }
    }
    return false;
}

list ($start, $stop) = file(__DIR__.'/../input/4.txt');

$part1 = 0;
$part2 = 0;
for ($i = intval($start); $i <= intval($stop); $i++) {
    if (ok($i)) {
        $part1++;
        if (ok2($i)) {
            $part2++;
        }
    }
}

echo "day4 part1: $part1\n";
echo "day4 part2: $part2\n";
