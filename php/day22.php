<?php

$input = file_get_contents(__DIR__.'/../input/22.txt');

function forwardShuffle($deckSize, $pos, $shuffleProgram) {
    foreach (explode("\n", trim($shuffleProgram)) as $line) {
        if (preg_match('/^deal into new stack$/', $line)) {
            $pos = ($deckSize - 1 - $pos);
        } else if (preg_match('/^cut ([-\d]+)$/', $line, $match)) {
            $cut = intval($match[1]);
            $pos = (($pos - $cut) % $deckSize);
        } else if (preg_match('/^deal with increment (\d+)$/', $line, $match)) {
            $increment = intval($match[1]);
            $pos = (($pos * $increment) % $deckSize);
        }
    }
    return $pos;
}

// unhelpful
function reverseShuffle($deckSize, $pos, $shuffleProgram) {
    foreach (array_reverse(explode("\n", trim($shuffleProgram))) as $line) {
        if (preg_match('/^deal into new stack$/', $line)) {
            $pos = ($deckSize - 1) - $pos;
        } else if (preg_match('/^cut ([-\d]+)$/', $line, $match)) {
            $cut = intval($match[1]);
            $pos = ($pos + $cut + $deckSize) % $deckSize;
        } else if (preg_match('/^deal with increment (\d+)$/', $line, $match)) {
            $increment = intval($match[1]);
            $pos = (invmod($increment, $deckSize) * $pos) % $deckSize;
        }
    }
    return $pos;
}

// https://rosettacode.org/wiki/Modular_inverse#PHP
function invmod($a, $n){
    if ($n < 0) $n = -$n;
    if ($a < 0) $a = $n - (-$a % $n);
    $t = 0; $nt = 1; $r = $n; $nr = $a % $n;
    while ($nr != 0) {
        $quot= intval($r/$nr);
        $tmp = $nt;  $nt = $t - $quot*$nt;  $t = $tmp;
        $tmp = $nr;  $nr = $r - $quot*$nr;  $r = $tmp;
    }
    if ($r > 1) return -1;
    if ($t < 0) $t += $n;
    return $t;
}

// part 1
$card = 2019;
$deckSize = 10007;
$position = forwardShuffle($deckSize, $card, $input);
echo "day22 part1: $position\n";

// part 2
$deckSize = 119315717514047; // prime
$repeats = 101741582076661; // prime
$position = 2020;

// shufflepos = A * startingpos + B
// shufflepos + Binv = A * startingpos + B + Binv
// shufflepos + Binv = A * startingpos
// shufflepos + Binv = Ainv * A * startingpos
// Ainv * (shufflepos + Binv) = startingpos
// Ainv * shufflepos + Ainv * Binv = startingpos
// Ainv * shufflepos + ABinv = startingpos

// pos0 = A*0+B
$pos0 = forwardShuffle($deckSize, 0, $input);
// pos1 = A*1+B
$pos1 = forwardShuffle($deckSize, 1, $input);

// A = (pos1 - pos0)
$a = bcmod(bcsub($pos1, $pos0), $deckSize);
if ($a < 0) {
    $a = bcadd($a, $deckSize);
}
// B = pos0
$b = $pos0;

// A * Ainv = 1
$ainv = invmod($a, $deckSize);

// ABinv = Ainv * Binv
$abinv = bcmod(bcmul($ainv, -$b), $deckSize);
if ($abinv < 0) {
    $abinv = bcadd($abinv, $deckSize);
}

// (ax+b)^n = (a^n)x + (((a^n)-1)/(a-1))*b
$position = 2020;
$card = bcmod(
            bcadd(
                bcmul(bcpowmod($ainv, $repeats, $deckSize), $position),
                bcmul(
                    bcmul(
                        bcsub(bcpowmod($ainv, $repeats, $deckSize), 1),
                        invmod($ainv - 1, $deckSize)
                    ),
                    $abinv
                )
            ),
            $deckSize
        );
if ($card < 0) {
    $card = bcadd($card, $deckSize);
}
echo "day22 part2: $card\n";
