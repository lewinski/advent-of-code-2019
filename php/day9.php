<?php

require_once __DIR__.'/lib/intcode.php';

$input = file_get_contents(__DIR__.'/../input/9.txt');

$computer = new \Intcode\Computer($input);
$computer->input(1);
$computer->run();
$output = implode(",", $computer->output(0));
echo "day9 part1: $output\n";

$computer = new \Intcode\Computer($input);
$computer->input(2);
$computer->run();
$output = implode(",", $computer->output(0));
echo "day9 part2: $output\n";
