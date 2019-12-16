<?php

require_once __DIR__.'/lib/intcode.php';

$input = trim(file_get_contents(__DIR__.'/../input/16.txt'));

$base = [0, 1, 0, -1];
$digits = str_split($input);
$length = count($digits);
for ($p = 0; $p < 100; $p++) {
    for ($i = 0; $i < $length; $i++) {
        $total = 0;
        for ($j = 0; $j < $length; $j++) {
            // the overall pattern should be repeated ($i+1) times.
            // figure out which group of size ($i+1) we are currently in and
            // index into the base pattern array to get the multiplier.
            $pattern = $base[intval(($j + 1) / ($i + 1)) % 4];
            $total += $pattern * $digits[$j];
        }
        $digits[$i] = abs($total % 10);
    }
}
echo "day16 part1: ".implode('', array_slice($digits, 0, 8))."\n";

ini_set('memory_limit', '1G');
$digits = str_split(str_repeat($input, 10000));
$length = count($digits);

// the pattern for figuring out the new value at any $index is going
// to be ($index+1-1) 0s, then ($index+1) 1s, then ($index+1) 0s,
// then ($index+1) -1s, repeating.
//
// the 0s at the front mean that when we run a phase, the value
// calculated at $index for the new phase only depends the current
// value and other values after $index.
//
// we want to know what the digits at $offset in are going to be.
// so we don't need to do any work on digits before $offset.
$offset = intval(substr($input, 0, 7));

// furthermore if $index is in the second half of the pattern, we
// will also never get to that second group of zeros so we just need
// to do the 1s, which will be the sum from $index to then end.
assert($offset > $length / 2);

// if we walk backwards through the end of the array we can even
// calculate the cumulative sum and set the new digits in one pass
for ($p = 0; $p < 100; $p++) {
    $sum = 0;
    for ($i = $length-1; $i >= $offset; $i--) {
        $sum += $digits[$i];
        $digits[$i] = abs($sum % 10);
    }
}
echo "day16 part2: ".implode('', array_slice($digits, $offset, 8))."\n";
