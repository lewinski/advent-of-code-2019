<?php

$input = file_get_contents(__DIR__.'/../input/5.txt');

function intcode(array &$memory, array $input): array {
    $output = [];
    $pc = 0;
    while (true) {
        $ins = sprintf("%05d", $memory[$pc]);
        $opcode = intval($ins[3] . $ins[4]);
        $mode1 = $ins[2];
        $mode2 = $ins[1];
        $mode3 = $ins[0];
        switch ($opcode) {
        case 99: // halt
            break 2;
        case 1: // z = x + y
            $x = $mode1 ? $memory[$pc + 1] : $memory[$memory[$pc + 1]];
            $y = $mode2 ? $memory[$pc + 2] : $memory[$memory[$pc + 2]];
            $memory[$memory[$pc + 3]] = $x + $y;
            $pc += 4;
            break;
        case 2: // z = x * y
            $x = $mode1 ? $memory[$pc + 1] : $memory[$memory[$pc + 1]];
            $y = $mode2 ? $memory[$pc + 2] : $memory[$memory[$pc + 2]];
            $memory[$memory[$pc + 3]] = $x * $y;
            $pc += 4;
            break;
        case 3: // input x
            $memory[$memory[$pc + 1]] = array_shift($input);
            $pc += 2;
            break;
        case 4: // output x
            $x = $mode1 ? $memory[$pc + 1] : $memory[$memory[$pc + 1]];
            $output[] = $x;
            $pc += 2;
            break;
        case 5: // jump to y if x
            $x = $mode1 ? $memory[$pc + 1] : $memory[$memory[$pc + 1]];
            $y = $mode2 ? $memory[$pc + 2] : $memory[$memory[$pc + 2]];
            if ($x != 0) {
                $pc = $y;
            } else {
                $pc += 3;
            }
            break;
        case 6: // jump to y if not x
            $x = $mode1 ? $memory[$pc + 1] : $memory[$memory[$pc + 1]];
            $y = $mode2 ? $memory[$pc + 2] : $memory[$memory[$pc + 2]];
            if ($x == 0) {
                $pc = $y;
            } else {
                $pc += 3;
            }
            break;
        case 7: // z = x < y
            $x = $mode1 ? $memory[$pc + 1] : $memory[$memory[$pc + 1]];
            $y = $mode2 ? $memory[$pc + 2] : $memory[$memory[$pc + 2]];
            $memory[$memory[$pc + 3]] = ($x < $y) ? 1 : 0;
            $pc += 4;
            break;
        case 8: // z = x == y
            $x = $mode1 ? $memory[$pc + 1] : $memory[$memory[$pc + 1]];
            $y = $mode2 ? $memory[$pc + 2] : $memory[$memory[$pc + 2]];
            $memory[$memory[$pc + 3]] = ($x == $y) ? 1 : 0;
            $pc += 4;
            break;
        default:
            echo "oops: pc = $pc; instruction = $memory[$pc]";
            break 2;
        }
    }
    return $output;
}

$memory = array_map('intval', explode(',', $input));
$part1 = intcode($memory, [1]);
echo "part1:\n".implode("\n", $part1)."\n\n";

$memory = array_map('intval', explode(',', $input));
$part2 = intcode($memory, [5]);
echo "part2:\n".implode("\n", $part2)."\n";
