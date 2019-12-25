<?php

require_once __DIR__.'/lib/intcode.php';

$input = file_get_contents(__DIR__.'/../input/25.txt');

$solution = [
    "east",
    "take whirled peas",
    "north",
    "west",
    "south",
    "take antenna",
    "north",
    "east",
    "south",
    "east",
    "north",
    "take prime number",
    "south",
    "west",
    "west",
    "north",
    "take fixed point",
    "north",
    "east",
    "inv",
    // "south"
];

$computer = new \Intcode\Computer($input);
while(true) {
    $computer->run();
    echo $computer->asciiOutput();
    if (count($solution)) {
        $command = array_shift($solution) . "\n";
        echo "\u{001b}[35m$command\u{001b}[0m";
        $computer->asciiInput($command);
    } else {
        $computer->asciiInput(fgets(STDIN));
    }
}
