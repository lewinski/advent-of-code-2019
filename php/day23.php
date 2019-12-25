<?php

require_once __DIR__.'/lib/intcode.php';

$input = file_get_contents(__DIR__.'/../input/23.txt');

$computers = [];
$queues = [];
$nat = [];
$lastNat = [];
for ($i = 0; $i < 50; $i++) {
    $comp = new \Intcode\Computer($input);
    $comp->input($i);
    $computers[] = $comp;
    $queues[] = [];
}

$part1 = false;

while (true) {
    $idleCount = 0;
    foreach ($computers as $addr => $comp) {
        if (count($queues[$addr])) {
            $comp->input($queues[$addr]);
            $queues[$addr] = [];
        } else {
            $idleCount++;
            $comp->input(-1);
        }
        $comp->run();
        $output = $comp->output(0);
        for ($i = 0; $i < count($output); $i += 3) {
            $dest = $output[$i];
            $x = $output[$i + 1];
            $y = $output[$i + 2];
            if ($dest == 255) {
                if (!$part1) {
                    echo "day23 part1: $y\n";
                    $part1 = true;
                }
                $nat = [$x, $y];
            } else {
                $queues[$dest][] = $x;
                $queues[$dest][] = $y;
            }
        }
    }

    if ($idleCount == 50) {
        $queues[0][] = $nat[0];
        $queues[0][] = $nat[1];
        if (count($lastNat) == 2 && $lastNat[0] == $nat[0] && $lastNat[1] == $nat[1]) {
            echo "day23 part2: $nat[1]\n";
            break;
        }
        $lastNat = $nat;
    }
}
