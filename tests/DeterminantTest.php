<?php

namespace Tests;

use App\Matrix;
use Exception;
use PHPUnit\Framework\TestCase;

class DeterminantTest extends TestCase
{
    public function test_determinant_2x2(): void
    {
        $this->assertEqualsWithDelta(-2, (new Matrix([[1, 2], [3, 4]]))->determinant(), 0.0001);
    }

    public function test_determinant_3x3(): void
    {
        $m = new Matrix([[6, 1, 1], [4, -2, 5], [2, 8, 7]]);

        $this->assertEqualsWithDelta(-306, $m->determinant(), 0.0001);
    }

    public function test_determinant_with_row_swap(): void
    {
        $this->assertEqualsWithDelta(-6, (new Matrix([[0, 2], [3, 4]]))->determinant(), 0.0001);
    }

    public function test_determinant_with_decimals(): void
    {
        $m = new Matrix([[0.5, 1.5], [2.5, 3.5]]);

        $this->assertEqualsWithDelta(-2, $m->determinant(), 0.0001);
    }

    public function test_determinant_1x1(): void
    {
        $this->assertEqualsWithDelta(7, (new Matrix([[7]]))->determinant(), 0.0001);
    }

    public function test_determinant_identity_is_one(): void
    {
        $identity = new Matrix([[1, 0], [0, 1]]);

        $this->assertEqualsWithDelta(1, $identity->determinant(), 0.0001);
    }

    public function test_determinant_zero_matrix_is_zero(): void
    {
        $zero = new Matrix([[0, 0], [0, 0]]);

        $this->assertEqualsWithDelta(0, $zero->determinant(), 0.0001);
    }

    public function test_determinant_singular_matrix_is_zero(): void
    {
        $this->assertEqualsWithDelta(0, (new Matrix([[1, 2], [2, 4]]))->determinant(), 0.0001);
    }

    public function test_determinant_non_square_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("quadrada");
        (new Matrix([[1, 2, 3], [4, 5, 6]]))->determinant();
    }
}
