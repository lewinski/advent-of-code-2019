<?php

$input = file_get_contents(__DIR__.'/../input/2.txt');

function intcode(array $memory): array {
    $pc = 0;
    while (true) {
        switch ($memory[$pc]) {
        case 99:
            break 2;
        case 1:
            $memory[$memory[$pc + 3]] = $memory[$memory[$pc + 1]] + $memory[$memory[$pc + 2]];
            $pc += 4;
            break;
        case 2:
            $memory[$memory[$pc + 3]] = $memory[$memory[$pc + 1]] * $memory[$memory[$pc + 2]];
            $pc += 4;
            break;
        default:
            echo "oops: pc = $pc; opcode = $memory[$pc]";
            break 2;
        }
    }
    return $memory;
}

$memory = array_map('intval', explode(',', $input));
$memory[1] = 12;
$memory[2] = 2;
$memory = intcode($memory);

echo "part 1: $memory[0]\n";

for ($x = 0; $x < 100; $x++) {
    for ($y = 0; $y < 100; $y++) {
        $memory = array_map('intval', explode(',', $input));
        $memory[1] = $x;
        $memory[2] = $y;
        $memory = intcode($memory);
        if ($memory[0] == 19690720) {
            echo "part 2: ".(100 * $x + $y)."\n";
            break 2;
        }
    }
}
