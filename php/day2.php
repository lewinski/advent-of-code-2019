<?php

require_once __DIR__.'/lib/intcode.php';

$input = file_get_contents(__DIR__.'/../input/2.txt');

$computer = new \Intcode\Computer($input);
$computer->poke(1, 12);
$computer->poke(2, 2);
$computer->run();
echo "day2 part1: ".$computer->peek(0)."\n";

for ($x = 0; $x < 100; $x++) {
    for ($y = 0; $y < 100; $y++) {
        $computer = new \Intcode\Computer($input);
        $computer->poke(1, $x);
        $computer->poke(2, $y);
        $computer->run();
        if ($computer->peek(0) == 19690720) {
            echo "day2 part2: ".(100 * $x + $y)."\n";
            break 2;
        }
    }
}
