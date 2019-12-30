<?php

class Heap extends \SplMinHeap {
    protected function compare ($a, $b): int {
        return $b[0] <=> $a[0];
    }
}

function isStart($char) {
    return in_array($char, ['@', '$', '%', '&']);
}

function isKey($char) {
    return $char >= 'a' && $char <= 'z';
}

function isDoor($char) {
    return $char >= 'A' && $char <= 'Z';
}

function addKey($collected, $key) {
    $collected .= $key;
    $collected = str_split($collected);
    sort($collected);
    return implode('', $collected);
}

function around($p) {
    list ($x, $y) = explode(',', $p);
    return [
        sprintf("%d,%d", $x, $y - 1),
        sprintf("%d,%d", $x - 1, $y),
        sprintf("%d,%d", $x + 1, $y),
        sprintf("%d,%d", $x, $y + 1),
    ];
}

$cache = [];
function reachable(array $map, string $collected, string $position) {
    global $cache;

    if (isset($cache["$collected:$position"])) {
        return $cache["$collected:$position"];
    }

    $map = openDoors($map, $collected);

    $distances = [$position => 0];
    $candidates = [$position => true];

    while (count($candidates)) {
        $cur = array_key_first($candidates);
        unset($candidates[$cur]);

        foreach (around($cur) as $p) {
            if (isset($map[$p])) {
                if (isset($distances[$p])) {
                    $distances[$p] = min($distances[$cur] + 1, $distances[$p]);
                } elseif(isKey($map[$p])) {
                    $distances[$p] = $distances[$cur] + 1;
                } elseif (!isDoor($map[$p])) {
                    $candidates[$p] = true;
                    $distances[$p] = $distances[$cur] + 1;
                }
            }
        }
    }

    $keys = [];
    foreach ($distances as $p => $d) {
        if (isKey($map[$p]) && $p != $position) {
            $keys[$map[$p]] = $d;
        }
    }

    $cache["$collected:$position"] = $keys;

    return $keys;
}

function openDoors(array $map, string $collected) {
    $doors = str_split($collected.strtoupper($collected));
    foreach ($map as $p => $c) {
        if (in_array($c, $doors)) {
            $map[$p] = '.';
        }
    }
    return $map;
}

function solve($input) {
    $keys = 0;
    $start = [];
    $collected = '';
    $map = [];
    $positions = [];

    $y = 0;
    foreach (explode("\n", $input) as $line) {
        $x = 0;
        foreach (str_split($line) as $char) {
            $p = "$x,$y";
            if (isStart($char)) {
                $keys++;
                $start[] = $char;
                $collected = addKey($collected, $char);
            }
            if (isKey($char)) {
                $keys++;
            }
            if ($char != '#') {
                $map[$p] = $char;
                $positions[$char] = $p;
            }
            $x++;
        }
        $y++;
    }

    $heap = new Heap();
    $heap->insert([0, $start, $collected]);

    $distances = [];

    $best = PHP_INT_MAX;
    while (!$heap->isEmpty()) {
        $next = $heap->extract();
        list ($steps, $robots, $collected) = $next;
        if ($steps > $best) {
            continue;
        }

        for ($i = 0; $i < count($robots); $i++) {
            $cur = $positions[$robots[$i]];

            $reachable = reachable($map, $collected, $cur);
            foreach ($reachable as $key => $distance) {
                $newKeys = addKey($collected, $key);
                $newSteps = $steps + $distance;
                $newRobots = $robots;
                $newRobots[$i] = $key;

                $newPos = sprintf("%s:%s", implode('', $newRobots), $newKeys);
                $oldSteps = PHP_INT_MAX;
                if (isset($distances[$newPos])) {
                    $oldSteps = $distances[$newPos];
                }

                if ($oldSteps > $newSteps) {
                    $distances[$newPos] = $newSteps;
                    $state = [$newSteps, $newRobots, $newKeys];
                    $heap->insert($state);
                }

                if (strlen($newKeys) == $keys) {
                    $best = min($best, $newSteps);
                }
            }
        }
    }

    return $best;
}

$input = file_get_contents(__DIR__.'/../input/18.txt');
$part1 = solve($input);
echo "day18 part1 = $part1\n";

$input = trim(file_get_contents(__DIR__.'/../input/18-2.txt'));
$part2 = solve($input);
echo "day18 part2 = $part2\n";
