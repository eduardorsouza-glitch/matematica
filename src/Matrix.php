<?php

namespace App;

use Exception;

class Matrix
{
    const EPSILON = 1e-10;

    public array $data;
    public int $rows;
    public int $cols;

    public function __construct(array $data)
    {
        if (empty($data) || empty($data[0])) {
            throw new Exception("A matriz não pode ser vazia.");
        }

        foreach ($data as $row) {
            if (count($row) !== count($data[0])) {
                throw new Exception("Todas as linhas precisam ter o mesmo tamanho.");
            }

            foreach ($row as $value) {
                if (!is_numeric($value)) {
                    throw new Exception("A matriz só aceita números.");
                }
            }
        }

        $this->data = $data;
        $this->rows = count($data);
        $this->cols = count($data[0]);
    }

    public static function findPivot(array $a, int $col, int $start): int
    {
        $best = $start;

        for ($r = $start + 1; $r < count($a); $r++) {
            if (abs($a[$r][$col]) > abs($a[$best][$col])) {
                $best = $r;
            }
        }

        return $best;
    }

    private function checkSize(Matrix $other): void
    {
        if ($this->rows !== $other->rows || $this->cols !== $other->cols) {
            throw new Exception("As matrizes precisam ter o mesmo tamanho.");
        }
    }

    public function add(Matrix $other): Matrix
    {
        $this->checkSize($other);
        $result = [];

        foreach ($this->data as $i => $row) {
            foreach ($row as $j => $value) {
                $result[$i][$j] = $value + $other->data[$i][$j];
            }
        }

        return new Matrix($result);
    }

    public function subtract(Matrix $other): Matrix
    {
        $this->checkSize($other);
        $result = [];

        foreach ($this->data as $i => $row) {
            foreach ($row as $j => $value) {
                $result[$i][$j] = $value - $other->data[$i][$j];
            }
        }

        return new Matrix($result);
    }

    public function multiplyByScalar(float $scalar): Matrix
    {
        $result = [];

        foreach ($this->data as $i => $row) {
            foreach ($row as $j => $value) {
                $result[$i][$j] = $value * $scalar;
            }
        }

        return new Matrix($result);
    }

    public function multiply(Matrix $other): Matrix
    {
        if ($this->cols !== $other->rows) {
            throw new Exception("O número de colunas de A deve ser igual ao número de linhas de B.");
        }

        $result = [];

        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $other->cols; $j++) {
                $sum = 0;

                for ($k = 0; $k < $this->cols; $k++) {
                    $sum += $this->data[$i][$k] * $other->data[$k][$j];
                }

                $result[$i][$j] = $sum;
            }
        }

        return new Matrix($result);
    }

    public function transpose(): Matrix
    {
        $result = [];

        foreach ($this->data as $i => $row) {
            foreach ($row as $j => $value) {
                $result[$j][$i] = $value;
            }
        }

        return new Matrix($result);
    }

    public function determinant(): float
    {
        if ($this->rows !== $this->cols) {
            throw new Exception("O determinante só existe para matriz quadrada.");
        }

        $n = $this->rows;
        $a = $this->data;
        $det = 1;

        for ($i = 0; $i < $n; $i++) {
            $p = self::findPivot($a, $i, $i);

            if (abs($a[$p][$i]) < self::EPSILON) {
                return 0.0;
            }

            if ($p !== $i) {
                [$a[$i], $a[$p]] = [$a[$p], $a[$i]];
                $det = -$det;
            }

            $det *= $a[$i][$i];

            for ($r = $i + 1; $r < $n; $r++) {
                $factor = $a[$r][$i] / $a[$i][$i];

                for ($c = $i; $c < $n; $c++) {
                    $a[$r][$c] -= $factor * $a[$i][$c];
                }
            }
        }

        return (float) $det;
    }

    public function inverse(): Matrix
    {
        if ($this->rows !== $this->cols) {
            throw new Exception("Só matriz quadrada tem inversa.");
        }

        $n = $this->rows;
        $a = $this->data;
        $inv = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $inv[$i][$j] = $i === $j ? 1 : 0;
            }
        }

        for ($i = 0; $i < $n; $i++) {
            $p = self::findPivot($a, $i, $i);

            if (abs($a[$p][$i]) < self::EPSILON) {
                throw new Exception("A matriz é singular (determinante zero) e não tem inversa.");
            }

            [$a[$i], $a[$p]] = [$a[$p], $a[$i]];
            [$inv[$i], $inv[$p]] = [$inv[$p], $inv[$i]];

            $divisor = $a[$i][$i];

            for ($c = 0; $c < $n; $c++) {
                $a[$i][$c] /= $divisor;
                $inv[$i][$c] /= $divisor;
            }

            for ($r = 0; $r < $n; $r++) {
                if ($r === $i) {
                    continue;
                }

                $factor = $a[$r][$i];

                for ($c = 0; $c < $n; $c++) {
                    $a[$r][$c] -= $factor * $a[$i][$c];
                    $inv[$r][$c] -= $factor * $inv[$i][$c];
                }
            }
        }

        return new Matrix($inv);
    }
}
