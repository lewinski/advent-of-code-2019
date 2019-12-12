<?php

$input = file_get_contents(__DIR__.'/../input/7.txt');

function intcode(int &$pc, array &$memory, array &$input, array &$output): int {
    while (true) {
        $ins = sprintf("%05d", $memory[$pc]);
        $opcode = intval($ins[3] . $ins[4]);
        $mode1 = $ins[2];
        $mode2 = $ins[1];
        $mode3 = $ins[0];
        switch ($opcode) {
        case 99: // halt
            return 0;
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
            if (count($input) == 0) {
                return 1;
            }
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
            return -1;
        }
    }
    return -2;
}

$perms = [
    [0,1,2,3,4],
    [0,1,2,4,3],
    [0,1,3,2,4],
    [0,1,3,4,2],
    [0,1,4,2,3],
    [0,1,4,3,2],
    [0,2,1,3,4],
    [0,2,1,4,3],
    [0,2,3,1,4],
    [0,2,3,4,1],
    [0,2,4,1,3],
    [0,2,4,3,1],
    [0,3,1,2,4],
    [0,3,1,4,2],
    [0,3,2,1,4],
    [0,3,2,4,1],
    [0,3,4,1,2],
    [0,3,4,2,1],
    [0,4,1,2,3],
    [0,4,1,3,2],
    [0,4,2,1,3],
    [0,4,2,3,1],
    [0,4,3,1,2],
    [0,4,3,2,1],
    [1,0,2,3,4],
    [1,0,2,4,3],
    [1,0,3,2,4],
    [1,0,3,4,2],
    [1,0,4,2,3],
    [1,0,4,3,2],
    [1,2,0,3,4],
    [1,2,0,4,3],
    [1,2,3,0,4],
    [1,2,3,4,0],
    [1,2,4,0,3],
    [1,2,4,3,0],
    [1,3,0,2,4],
    [1,3,0,4,2],
    [1,3,2,0,4],
    [1,3,2,4,0],
    [1,3,4,0,2],
    [1,3,4,2,0],
    [1,4,0,2,3],
    [1,4,0,3,2],
    [1,4,2,0,3],
    [1,4,2,3,0],
    [1,4,3,0,2],
    [1,4,3,2,0],
    [2,0,1,3,4],
    [2,0,1,4,3],
    [2,0,3,1,4],
    [2,0,3,4,1],
    [2,0,4,1,3],
    [2,0,4,3,1],
    [2,1,0,3,4],
    [2,1,0,4,3],
    [2,1,3,0,4],
    [2,1,3,4,0],
    [2,1,4,0,3],
    [2,1,4,3,0],
    [2,3,0,1,4],
    [2,3,0,4,1],
    [2,3,1,0,4],
    [2,3,1,4,0],
    [2,3,4,0,1],
    [2,3,4,1,0],
    [2,4,0,1,3],
    [2,4,0,3,1],
    [2,4,1,0,3],
    [2,4,1,3,0],
    [2,4,3,0,1],
    [2,4,3,1,0],
    [3,0,1,2,4],
    [3,0,1,4,2],
    [3,0,2,1,4],
    [3,0,2,4,1],
    [3,0,4,1,2],
    [3,0,4,2,1],
    [3,1,0,2,4],
    [3,1,0,4,2],
    [3,1,2,0,4],
    [3,1,2,4,0],
    [3,1,4,0,2],
    [3,1,4,2,0],
    [3,2,0,1,4],
    [3,2,0,4,1],
    [3,2,1,0,4],
    [3,2,1,4,0],
    [3,2,4,0,1],
    [3,2,4,1,0],
    [3,4,0,1,2],
    [3,4,0,2,1],
    [3,4,1,0,2],
    [3,4,1,2,0],
    [3,4,2,0,1],
    [3,4,2,1,0],
    [4,0,1,2,3],
    [4,0,1,3,2],
    [4,0,2,1,3],
    [4,0,2,3,1],
    [4,0,3,1,2],
    [4,0,3,2,1],
    [4,1,0,2,3],
    [4,1,0,3,2],
    [4,1,2,0,3],
    [4,1,2,3,0],
    [4,1,3,0,2],
    [4,1,3,2,0],
    [4,2,0,1,3],
    [4,2,0,3,1],
    [4,2,1,0,3],
    [4,2,1,3,0],
    [4,2,3,0,1],
    [4,2,3,1,0],
    [4,3,0,1,2],
    [4,3,0,2,1],
    [4,3,1,0,2],
    [4,3,1,2,0],
    [4,3,2,0,1],
    [4,3,2,1,0],
];

$max = 0;
foreach ($perms as $phase) {
    $thrust = 0;
    foreach ($phase as $p) {
        $pc = 0;
        $memory = array_map('intval', explode(',', $input));
        $in = [$p, $thrust];
        $out = [];
        $rv = intcode($pc, $memory, $in, $out);
        $thrust = $out[0];
    }
    $max = max($thrust, $max);
}

echo "part1: $max\n";

$max = 0;
foreach ($perms as $phase) {
    // init new bank of thrusters {prog counter, memory, input, output}
    $thrusters = [
        [0, array_map('intval', explode(',', $input)), [], []],
        [0, array_map('intval', explode(',', $input)), [], []],
        [0, array_map('intval', explode(',', $input)), [], []],
        [0, array_map('intval', explode(',', $input)), [], []],
        [0, array_map('intval', explode(',', $input)), [], []],
    ];
    // send phases
    for ($i = 0; $i < 5; $i++) {
        $thrusters[$i][2][] = $phase[$i] + 5;
    }
    // init first thruster
    $thrusters[0][2][] = 0;

    // keep track of output from last thruster
    $thrust = 0;

    // run programs on thrusters until last one halts
    $run = 0;
    while (true) {
        $next = ($run + 1) % 5;

        // run until halt or input is drained
        $rv = intcode($thrusters[$run][0], $thrusters[$run][1], $thrusters[$run][2], $thrusters[$run][3]);

        // copy output into input of next thruster
        while (count($thrusters[$run][3]) != 0) {
            $val = array_shift($thrusters[$run][3]);
            $thrusters[$next][2][] = $val;
            // update thrust value if this was output from last thruster
            if ($run == 4) {
                $thrust = $val;
            }
        }

        // last one halted
        if ($rv == 0 && $run == 4) {
            break;
        }

        $run = $next;
    }

    // update running maximum thrust
    $max = max($max, $thrust);
}

echo "part2: $max\n";
