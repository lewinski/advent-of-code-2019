<?php

require_once __DIR__.'/lib/intcode.php';

$input = file_get_contents(__DIR__.'/../input/21.txt');

$computer = new \Intcode\Computer($input);
$computer->run();
$output = $computer->asciiOutput();
echo "$output";

/*
part 1
    @ABCDEFGH      NOT A J
#####.###########

  @ABCDEFGH        NOT C J AND D J
       @ABCDEFGH   NOT A J
#####.##.########

  @ABCDEFGH        NOT C J AND D J
      @ABCDEFGH    NOT A J
#####.#..########
*/
$computer->asciiInput("NOT A J\nNOT C T\nAND D T\nOR T J\n");
$computer->asciiInput("WALK\n");
$computer->run();
$output = $computer->output(0);
$damage = $output[count($output)-1];
if ($damage > 128) {
    echo "day21 part1: $damage\n";
} else {
    $output = implode('', array_map('chr', $output));
    echo $output;
}

$computer = new \Intcode\Computer($input);
$computer->run();
$output = $computer->asciiOutput();
echo "$output";

/*
part 2
    @ABCDEFGH      NOT A J
#####.###########
  @ABCDEFGH        NOT C J AND D J
       @ABCDEFGH   NOT A J
#####.##.########
  @ABCDEFGH        NOT C J AND D J
      @ABCDEFGH    NOT A J
#####.#..########
    @ABCDEFGH      NOT C J AND D J AND H J
        @ABCDEFGH  NOT C J AND D J
            @ABCD  NOT A J
#####.#.#..##.###
   @ABCDEFGH       NOT B J AND D J AND H J
       @ABCDEFGH   NOT A J
#####.##.#.######
*/
$computer->asciiInput("NOT A J\nNOT C T\nAND D T\nAND H T\nOR T J\nNOT B T\nAND D T\nAND H T\nOR T J\n");
$computer->asciiInput("RUN\n");
$computer->run();
$output = $computer->output(0);
$damage = $output[count($output)-1];
if ($damage > 128) {
    echo "day21 part2: $damage\n";
} else {
    $output = implode('', array_map('chr', $output));
    echo $output;
}
