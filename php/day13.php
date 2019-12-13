<?php

require_once __DIR__.'/lib/intcode.php';

$input = file_get_contents(__DIR__.'/../input/13.txt');

$computer = new \Intcode\Computer($input);
$computer->run();
$output = $computer->output(0);

$count = 0;
for ($i = 0; $i < count($output); $i += 3) {
    $tile = $output[$i + 2];
    if ($tile == 2) {
        $count++;
    }
}

$computer = new \Intcode\Computer($input);
$computer->poke(0, 2);

$clear = shell_exec("clear");
$tiles = [
    0 => ' ',
    1 => "\u{258a}",
    2 => "\u{001b}[35m\u{2583}\u{001b}[0m",
    3 => "\u{001b}[32m=\u{001b}[0m",
    4 => "\u{001b}[31m*\u{001b}[0m",
];

$score = 0;
$display = [];
$ball = 0;
$paddle = 0;

while (!$computer->isHalted()) {
    $computer->run();
    $output = $computer->output(0);
    for ($i = 0; $i < count($output); $i += 3) {
        $x = $output[$i];
        $y = $output[$i + 1];
        $tile = $output[$i + 2];
        if ($x == -1 && $y == 0) {
            $score = $tile;
            continue;
        }
        if (!isset($display[$y])) {
            $display[$y] = [];
        }
        if ($tile == 3) {
            $paddle = $x;
        }
        if ($tile == 4) {
            $ball = $x;
        }
        $display[$y][$x] = $tiles[$tile];
    }

    if (true) {
        $screen = implode("\n", array_map(function ($x) { return implode("", $x); }, $display));
        $blocks = substr_count($screen, $tiles[2]);
        $winner = $blocks ? '' : "\nWinner!";
        echo "$clear$screen\n\nScore: $score | Blocks: $blocks$winner\n";
        usleep(2500);
    }

    // artificial intelligence
    if ($ball > $paddle) {
        $computer->input(1);
    } elseif ($ball < $paddle) {
        $computer->input(-1);
    } else {
        $computer->input(0);
    }

}

echo "day13 part1: $count\n";
echo "day13 part2: $score\n";
