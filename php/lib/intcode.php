<?php

namespace Intcode;

class Computer {
    /** @var int */
    protected $programCounter = 0;

    /** @var int */
    protected $relativeBase = 0;

    /** @var bool */
    protected $blocked = false;

    /** @var bool */
    protected $halted = false;

    /** @var int[] */
    protected $memory = [];

    /** @var int[] */
    protected $input = [];

    /** @var int[] */
    protected $output = [];

    public function __construct(string $program) {
        $this->memory = array_map('intval', explode(',', $program));
    }

    public function poke(int $idx, int $val) {
        $this->memory[$idx] = $val;
    }

    public function peek(int $idx): int {
        return $this->memory[$idx] ?? 0;
    }

    public function isBlocked(): bool {
        return $this->blocked;
    }

    public function isHalted(): bool {
        return $this->halted;
    }

    public function isRunnable(): bool {
        return !$this->isBlocked() && !$this->isHalted();
    }

    /** @param int|int[] $val */
    public function input($val) {
        if (!is_array($val)) {
            $val = [$val];
        }
        while (count($val)) {
            $this->input[] = array_shift($val);
        }
        $this->blocked = false;
    }

    /** @return null|int|int[] */
    public function output(int $count = 1) {
        if ($count > count($this->output)) {
            return null;
        } elseif ($count == 1) {
            return array_shift($this->output);
        } elseif ($count == 0) {
            $count = count($this->output);
        }

        $output = [];
        for ($i = 0; $i < $count; $i++) {
            $output[] = array_shift($this->output);
        }
        return $output;
    }

    public function asciiInput(string $val) {
        $this->input(array_map('ord', str_split($val)));
    }

    /** @return string */
    public function asciiOutput(int $count = 0) {
        $output = $this->output($count);
        return implode('', array_map('chr', $output));
    }

    public function run() {
        while ($this->isRunnable()) {
            $this->step();
        }
    }

    public function step() {
        $ins = sprintf("%05d", $this->peek($this->programCounter));
        $opcode = intval($ins[3] . $ins[4]);
        $mode1 = $ins[2];
        $mode2 = $ins[1];
        $mode3 = $ins[0];

        switch ($opcode) {
        case 99: // halt
            $this->halted = true;
            break;
        case 1: // add: x + y -> z
            $this->setParam($mode3, 3, $this->getParam($mode1, 1) + $this->getParam($mode2, 2));
            $this->programCounter += 4;
            break;
        case 2: // mult: x * y -> z
            $this->setParam($mode3, 3, $this->getParam($mode1, 1) * $this->getParam($mode2, 2));
            $this->programCounter += 4;
            break;
        case 3: // input: -> x
            if (count($this->input) == 0) {
                $this->blocked = true;
                return;
            }
            $this->setParam($mode1, 1, array_shift($this->input));
            $this->programCounter += 2;
            break;
        case 4: // output: x ->
            $this->output[] = $this->getParam($mode1, 1);
            $this->programCounter += 2;
            break;
        case 5: // jnz: if(x) pc = y
            if ($this->getParam($mode1, 1) != 0) {
                $this->programCounter = $this->getParam($mode2, 2);
            } else {
                $this->programCounter += 3;
            }
            break;
        case 6: // jz: if(x == 0) pc = y
            if ($this->getParam($mode1, 1) == 0) {
                $this->programCounter = $this->getParam($mode2, 2);
            } else {
                $this->programCounter += 3;
            }
            break;
        case 7: // lt: x < y -> z
            $x = $this->getParam($mode1, 1);
            $y = $this->getParam($mode2, 2);
            $this->setParam($mode3, 3, ($x < $y) ? 1 : 0);
            $this->programCounter += 4;
            break;
        case 8: // eq: x == y -> z
            $x = $this->getParam($mode1, 1);
            $y = $this->getParam($mode2, 2);
            $this->setParam($mode3, 3, ($x == $y) ? 1 : 0);
            $this->programCounter += 4;
            break;
        case 9: // setrb: x -> rb
            $this->relativeBase += $this->getParam($mode1, 1);
            $this->programCounter += 2;
            break;
        default:
            throw new \Exception("unknown opcode ($opcode) at position " . $this->programCounter);
        }
    }

    protected function getParam(int $mode, int $offset): int {
        $immediate = $this->peek($this->programCounter + $offset);
        switch ($mode) {
            case 0: // position
                return $this->peek($immediate);
            case 1: // immediate
                return $immediate;
            case 2: // relative
                return $this->peek($immediate + $this->relativeBase);
            default:
                throw new \Exception("unknown mode: $mode");
        }
    }

    protected function setParam(int $mode, int $offset, int $value) {
        $immediate = $this->peek($this->programCounter + $offset);
        switch ($mode) {
            case 0: // position
            case 1: // immediate
                $this->poke($immediate, $value);
                break;
            case 2: // relative
                $this->poke($immediate + $this->relativeBase, $value);
                break;
            default:
                throw new \Exception("unknown mode: $mode");
        }
    }
}
