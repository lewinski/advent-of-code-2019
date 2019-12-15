<?php

$input = file_get_contents(__DIR__.'/../input/14.txt');

$reactions = [];
foreach (explode("\n", trim($input)) as $line) {
    list ($inputs, $output) = explode(' => ', $line, 2);
    $inputs = explode(', ', $inputs);
    list ($amt, $chem) = explode(" ", $output);
    $reactions[$chem] = [
        'inputs' => array_map(function ($x) { return explode(' ', $x); }, $inputs),
        'output' => [$amt, $chem]
    ];
}

function ore($fuel) {
    global $reactions;

    $ore = 0;
    $need = ['FUEL' => $fuel];
    $have = [];

    while (count($need)) {
        $chem = array_key_first($need);
        $reaction = $reactions[$chem];

        $have[$chem] ??= 0;
        $times = ceil(($need[$chem] - $have[$chem]) / $reaction['output'][0]);
        $have[$chem] += ($times * $reaction['output'][0]) - $need[$chem];
        unset($need[$chem]);

        foreach ($reaction['inputs'] as $input) {
            if ($input[1] == 'ORE') {
                $ore += $times * $input[0];
            } else {
                $need[$input[1]] ??= 0;
                $need[$input[1]] += $times * $input[0];    
            }
        }
    }

    return $ore;
}

echo "day14 part1: ".ore(1)."\n";

$target = 1000000000000;
$low = 1;
$high = $target;

while (true) {
    $mid = intval(($high + $low) / 2);
    $more = ore($mid);
    if ($more < $target) {
        $low = $mid;
    } else {
        $high = $mid;
    }	
    if ($high - $low == 1) {
        break;
    }
}

echo "day14 part2: $low\n";
