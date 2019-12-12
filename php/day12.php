<?php

$input = file_get_contents(__DIR__.'/../input/12.txt');

function lcm($m, $n) {
    if ($m == 0 || $n == 0) return 0;
    $r = ($m * $n) / gcd($m, $n);
    return abs($r);
}

function gcd($a, $b) {
    while ($b != 0) {
        $t = $b;
        $b = $a % $b;
        $a = $t;
    }
    return $a;
}

class Moon {
    public $id;
    public $x;
    public $y;
    public $z;
    public $xv = 0;
    public $yv = 0;
    public $zv = 0;

    public function __toString(): string {
        return sprintf(
            "pos=<x=%d, y=%d, z=%d>, vel=<x=%d, y=%d, z=%d>",
            $this->x,
            $this->y,
            $this->z,
            $this->xv,
            $this->yv,
            $this->zv
        );
    }

    public function step() {
        $this->x += $this->xv;
        $this->y += $this->yv;
        $this->z += $this->zv;
    }

    public function grav(Moon $m) {
        if ($this->x < $m->x) {
            $this->xv += 1;
        } elseif ($this->x > $m->x) {
            $this->xv -= 1;
        }
        if ($this->y < $m->y) {
            $this->yv += 1;
        } elseif ($this->y > $m->y) {
            $this->yv -= 1;
        }
        if ($this->z < $m->z) {
            $this->zv += 1;
        } elseif ($this->z > $m->z) {
            $this->zv -= 1;
        }
    }

    public function energy(): int {
        $pot = abs($this->x) + abs($this->y) + abs($this->z);
        $kin = abs($this->xv) + abs($this->yv) + abs($this->zv);
        return $pot * $kin;
    }
}

$moons = [];
$id = 0;
foreach (explode("\n", trim($input)) as $line) {
    $args = preg_split("/[^\\d-]+/", $line);
    $moon = new Moon();
    $moon->id = $id;
    $moon->x = $args[1];
    $moon->y = $args[2];
    $moon->z = $args[3];
    $moons[] = $moon;
    $id++;
}

for ($t = 0; $t < 1000; $t++) {
    foreach ($moons as $a) {
        foreach ($moons as $b) {
            if ($a->id == $b->id) {
                continue;
            }
            $a->grav($b);
        }
    }
    foreach ($moons as $moon) {
        $moon->step();
    }
}

$energy = 0;
foreach ($moons as $moon) {
    $energy += $moon->energy();
}
echo "day12 part1: $energy\n";

$moons = [];
$id = 0;
foreach (explode("\n", trim($input)) as $line) {
    $args = preg_split("/[^\\d-]+/", $line);
    $moon = new Moon();
    $moon->id = $id;
    $moon->x = $args[1];
    $moon->y = $args[2];
    $moon->z = $args[3];
    $moons[] = $moon;
    $id++;
}

$seen = ['x'=>[], 'y'=>[], 'z'=>[]];
$periods = [];
$steps = 0;
while (true) {
    $states = ['x'=>'', 'y'=>'', 'z'=>''];
    foreach ($moons as $moon) {
        $states['x'] .= sprintf("%d:%d:", $moon->x, $moon->xv);
        $states['y'] .= sprintf("%d:%d:", $moon->y, $moon->yv);
        $states['z'] .= sprintf("%d:%d:", $moon->z, $moon->zv);
    }
    foreach ($states as $axis => $state) {
        if (isset($seen[$axis][$state]) && !isset($periods[$axis])) {
            $periods[$axis] = $steps;
        }
        $seen[$axis][$state] = true;
    }
    if (count($periods) == 3) {
        break;
    }

    foreach ($moons as $a) {
        foreach ($moons as $b) {
            if ($a->id == $b->id) {
                continue;
            }
            $a->grav($b);
        }
    }
    foreach ($moons as $moon) {
        $moon->step();
    }

    $steps++;
}

$period = lcm(lcm($periods['x'], $periods['y']), $periods['z']);
echo "day12 part2: $period\n";
