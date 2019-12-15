<?php

require_once __DIR__.'/lib/intcode.php';

$input = file_get_contents(__DIR__.'/../input/15.txt');
$start = time();

function pathsAround($path) {
    return ["${path}1", "${path}2", "${path}3", "${path}4"];
}

function coord($path) {
    $x = substr_count($path, '3') - substr_count($path, '4');
    $y = substr_count($path, '1') - substr_count($path, '2');
    return [$x, $y];
}

$paths = [''];
$visited = [];
$map = [];

while (count($paths) > 0) {
    $path = array_shift($paths);

    list ($x, $y) = coord($path);
    if (isset($visited["$x,$y"])) {
        continue;
    }
    $visited["$x,$y"] = true;

    foreach (pathsAround($path) as $p) {
        list ($newx, $newy) = coord($p);
        $computer = new \Intcode\Computer($input);
        $computer->input(array_map('intval', str_split($p)));
        $computer->run();
        $output = $computer->output(0);

        switch (end($output)) {
            case 0:
                $map["$newx,$newy"] = '#';
                // wall
                break;
            case 1:
                $map["$newx,$newy"] = '.';
                $paths[] = $p;
                break;
            case 2:
                $map["$newx,$newy"] = '2';
                $paths[] = $p;
                $elapsed = time() - $start;
                echo "day15 part1: ".strlen($p)." ($elapsed seconds)\n";
                break;
        }    
    }    
}

function displayMap(array $map) {
    $map["0,0"] = 'D';

    $minx = 0;
    $miny = 0;
    foreach ($map as $p => $c) {
        list ($x, $y) = explode(',', $p);
        $minx = min($minx, $x);
        $miny = min($miny, $y);
    }

    $display = [];
    foreach ($map as $p => $c) {
        list ($x, $y) = explode(',', $p);
        $x = intval($x);
        $y = intval($y);
        if (!isset($display[$y-$miny])) {
            $display[$y-$miny] = "";
        }
        $display[$y-$miny][$x-$minx] = $c;
    }

    $s = "";
    for ($i = 0; $i < count($display); $i++) {
        $s .= "$display[$i]\n";
    }
    return $s;
}

$elapsed = time() - $start;
echo "mapping complete: ($elapsed seconds)\n";
echo displayMap($map);

$fill = [];
foreach ($map as $p => $c) {
    if ($c == '.') {
        $fill[$p] = -1;
    }
    if ($c == '2') {
        $fill[$p] = 0;
    }
}

function pointsAround($p) {
    list ($x, $y) = explode(",", $p);
    $xa = $x - 1;
    $xb = $x + 1;
    $ya = $y - 1;
    $yb = $y + 1;
    return ["$xa,$y", "$xb,$y", "$x,$ya", "$x,$yb"];
}

$step = 0;
while (true) {
    if (!in_array(-1, $fill)) {
        break;
    }

    $cur = [];
    foreach ($fill as $p => $c) {
        if ($step == $c) {
            $cur[] = $p;
        }
    }

    $step++;

    foreach ($cur as $p) {
        foreach (pointsAround($p) as $newp) {
            if (isset($fill[$newp]) && $fill[$newp] == -1) {
                $fill[$newp] = $step;
            }
        }
    }
}

$elapsed = time() - $start;
echo "day15 part2: $step ($elapsed seconds)\n";
