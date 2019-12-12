<?php

$input = file_get_contents(__DIR__.'/../input/9.txt');

function intcode(int &$pc, int &$rb, array &$memory, array &$input, array &$output): int {
    $get = function (int $mode, int $offset, int $pc, int $rb, array $memory): int {
        $reg = $memory[$pc + $offset];
        switch ($mode) {
            case 0:
                return intval($memory[$reg]);
            case 1:
                return $reg;
            case 2:
                return intval($memory[$reg + $rb]);
            default:
                throw new Exception("unknown mode: $mode\n");
        }
    };
    $set = function (int $mode, int $offset, int $val, int $pc, int $rb, array &$memory) {
        $reg = $memory[$pc + $offset];
        switch ($mode) {
            case 0:
            case 1:
                $memory[$reg] = $val;
                break;
            case 2:
                $memory[$reg + $rb] = $val;
                break;
            default:
                throw new Exception("unknown mode: $mode\n");
        }
    };        
    
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
            $val = $get($mode1, 1, $pc, $rb, $memory) + $get($mode2, 2, $pc, $rb, $memory);
            $set($mode3, 3, $val, $pc, $rb, $memory);
            $pc += 4;
            break;
        case 2: // z = x * y
            $val = $get($mode1, 1, $pc, $rb, $memory) * $get($mode2, 2, $pc, $rb, $memory);
            $set($mode3, 3, $val, $pc, $rb, $memory);
            $pc += 4;
            break;
        case 3: // input x
            if (count($input) == 0) {
                return 1;
            }
            $set($mode1, 1, array_shift($input), $pc, $rb, $memory);
            $pc += 2;
            break;
        case 4: // output x
            $output[] = $get($mode1, 1, $pc, $rb, $memory);
            $pc += 2;
            break;
        case 5: // jump to y if x
            $x = $get($mode1, 1, $pc, $rb, $memory);
            $y = $get($mode2, 2, $pc, $rb, $memory);
            if ($x != 0) {
                $pc = $y;
            } else {
                $pc += 3;
            }
            break;
        case 6: // jump to y if not x
            $x = $get($mode1, 1, $pc, $rb, $memory);
            $y = $get($mode2, 2, $pc, $rb, $memory);
            if ($x == 0) {
                $pc = $y;
            } else {
                $pc += 3;
            }
            break;
        case 7: // z = x < y
            $x = $get($mode1, 1, $pc, $rb, $memory);
            $y = $get($mode2, 2, $pc, $rb, $memory);
            $val = ($x < $y) ? 1 : 0;
            $set($mode3, 3, $val, $pc, $rb, $memory);
            $pc += 4;
            break;
        case 8: // z = x == y
            $x = $get($mode1, 1, $pc, $rb, $memory);
            $y = $get($mode2, 2, $pc, $rb, $memory);
            $val = ($x == $y) ? 1 : 0;
            $set($mode3, 3, $val, $pc, $rb, $memory);
            $pc += 4;
            break;
        case 9:
            $rb += $get($mode1, 1, $pc, $rb, $memory);
            $pc += 2;
            break;
        default:
            echo "oops: pc = $pc; instruction = $memory[$pc]\n";
            return -1;
        }
    }
    return -2;
}

$pc = 0;
$rb = 0;
$memory = array_map('intval', explode(',', $input));
$in = [1];
$out = [];
$rv = intcode($pc, $rb, $memory, $in, $out);

echo "part1: $out[0]\n";

$pc = 0;
$rb = 0;
$memory = array_map('intval', explode(',', $input));
$in = [2];
$out = [];
$rv = intcode($pc, $rb, $memory, $in, $out);

echo "part2: $out[0]\n";