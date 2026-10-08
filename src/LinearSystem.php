<?php

namespace App;

use Exception;

class LinearSystem
{
    public function __construct(private Matrix $a, private Matrix $b)
    {
        if ($b->cols !== 1) {
            throw new Exception("O vetor b precisa ter uma única coluna.");
        }

        if ($a->rows !== $b->rows) {
            throw new Exception("A e b precisam ter o mesmo número de linhas.");
        }
    }

    private function reduce(): array
    {
        $m = [];

        foreach ($this->a->data as $i => $row) {
            $m[$i] = array_merge($row, $this->b->data[$i]);
        }

        $rows = count($m);
        $cols = count($m[0]);
        $line = 0;
        $pivots = [];

        for ($c = 0; $c < $cols && $line < $rows; $c++) {
            $p = Matrix::findPivot($m, $c, $line);

            if (abs($m[$p][$c]) < Matrix::EPSILON) {
                continue;
            }

            [$m[$line], $m[$p]] = [$m[$p], $m[$line]];

            $divisor = $m[$line][$c];

            for ($j = 0; $j < $cols; $j++) {
                $m[$line][$j] /= $divisor;
            }

            for ($r = 0; $r < $rows; $r++) {
                if ($r === $line) {
                    continue;
                }

                $factor = $m[$r][$c];

                for ($j = 0; $j < $cols; $j++) {
                    $m[$r][$j] -= $factor * $m[$line][$j];
                }
            }

            $pivots[] = $c;
            $line++;
        }

        return [$m, $pivots];
    }

    public function classify(): string
    {
        [, $pivots] = $this->reduce();

        if (in_array($this->a->cols, $pivots)) {
            return "SI";
        }

        if (count($pivots) === $this->a->cols) {
            return "SPD";
        }

        return "SPI";
    }

    public function solve(): array
    {
        $type = $this->classify();

        if ($type === "SI") {
            throw new Exception("O sistema é impossível, não existe solução.");
        }

        if ($type === "SPI") {
            throw new Exception("O sistema é indeterminado, existem infinitas soluções.");
        }

        [$m] = $this->reduce();
        $solution = [];

        for ($i = 0; $i < $this->a->cols; $i++) {
            $solution[] = $m[$i][$this->a->cols];
        }

        return $solution;
    }
}
