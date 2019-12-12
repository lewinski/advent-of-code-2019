<?php

require_once __DIR__.'/lib/intcode.php';

function rotate($dir, $pointing) {
    if ($dir == 0) {
        return rotate(1, rotate(1, rotate(1, $pointing)));
    } else if ($pointing[0] == 1) {
        // left -> down
        return [0, -1];
    } elseif ($pointing[0] == -1) {
        // right -> up
        return [0, 1];
    } elseif ($pointing[1] == 1) {
        // up -> left
        return [1, 0];
    } else {
        // down -> right
        return [-1, 0];
    }
}

function paint($input, $initialColor) {
    $white = '#';
    $black = ' ';

    if ($initialColor != $white) {
        $initialColor = $black;
    }

    $painted = ['0,0' => $initialColor];
    $position = [0, 0]; // origin
    $pointing = [0, 1]; // up

    $computer = new \Intcode\Computer($input);
    while (true) {
        if ($computer->isHalted()) {
            break;
        }

        $p = "$position[0],$position[1]";

        $color = 0;
        if (isset($painted[$p]) && $painted[$p] == $white) {
            $color = 1;
        }

        $computer->input($color);
        $computer->run();

        $output = $computer->output(2);

        $painted[$p] = $output[0] == 1 ? $white : $black;

        $pointing = rotate($output[1], $pointing);
        $position[0] += $pointing[0];
        $position[1] += $pointing[1];
    }

    return $painted;
}

$input = file_get_contents(__DIR__.'/../input/11.txt');

$painted = paint($input, ' ');
echo "day11 part1: ".count($painted)."\n";

$painted = paint($input, '#');

$minx = $miny = 0;
foreach ($painted as $pos => $paint) {
    list ($x, $y) = explode(',', $pos);
    $minx = min($x, $minx);
    $miny = min($y, $miny);
}

$output = [];
foreach ($painted as $pos => $paint) {
    list ($x, $y) = explode(',', $pos);
    if (!isset($output[$y-$miny])) {
        $output[$y-$miny] = '';
    }
    $output[$y-$miny][$x-$minx] = $paint;
}

echo "day11 part2:\n";
foreach ($output as $line) {
    echo "$line\n";
}
