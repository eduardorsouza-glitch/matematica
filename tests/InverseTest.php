<?php

namespace Tests;

use App\Matrix;
use Exception;
use PHPUnit\Framework\TestCase;

class InverseTest extends TestCase
{
    public function test_inverse_2x2(): void
    {
        $inverse = (new Matrix([[4, 7], [2, 6]]))->inverse();

        $this->assertEqualsWithDelta([[0.6, -0.7], [-0.2, 0.4]], $inverse->data, 0.0001);
    }

    public function test_matrix_times_inverse_is_identity(): void
    {
        $a = new Matrix([[1, 2, 3], [0, 1, 4], [5, 6, 0]]);

        $result = $a->multiply($a->inverse());

        $this->assertEqualsWithDelta([[1, 0, 0], [0, 1, 0], [0, 0, 1]], $result->data, 0.0001);
    }

    public function test_inverse_with_row_swap(): void
    {
        $inverse = (new Matrix([[0, 1], [1, 0]]))->inverse();

        $this->assertEqualsWithDelta([[0, 1], [1, 0]], $inverse->data, 0.0001);
    }

    public function test_inverse_1x1(): void
    {
        $this->assertEqualsWithDelta([[0.25]], (new Matrix([[4]]))->inverse()->data, 0.0001);
    }

    public function test_inverse_of_identity_is_identity(): void
    {
        $identity = new Matrix([[1, 0], [0, 1]]);

        $this->assertEqualsWithDelta($identity->data, $identity->inverse()->data, 0.0001);
    }

    public function test_singular_matrix_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("singular");
        (new Matrix([[1, 2], [2, 4]]))->inverse();
    }

    public function test_zero_matrix_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("singular");
        (new Matrix([[0, 0], [0, 0]]))->inverse();
    }

    public function test_non_square_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("quadrada");
        (new Matrix([[1, 2, 3], [4, 5, 6]]))->inverse();
    }
}
