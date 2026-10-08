<?php

namespace Tests;

use App\Matrix;
use Exception;
use PHPUnit\Framework\TestCase;

class MatrixTest extends TestCase
{
    public function test_create_matrix(): void
    {
        $m = new Matrix([[1, 2, 3], [4, 5, 6]]);

        $this->assertSame(2, $m->rows);
        $this->assertSame(3, $m->cols);
    }

    public function test_empty_matrix_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("vazia");
        new Matrix([]);
    }

    public function test_rows_with_different_sizes_give_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("mesmo tamanho");
        new Matrix([[1, 2], [3]]);
    }

    public function test_text_value_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("números");
        new Matrix([[1, "a"]]);
    }

    public function test_add(): void
    {
        $result = (new Matrix([[1, 2], [3, 4]]))->add(new Matrix([[5, 6], [7, 8]]));

        $this->assertEqualsWithDelta([[6, 8], [10, 12]], $result->data, 0.0001);
    }

    public function test_add_1x1(): void
    {
        $result = (new Matrix([[2]]))->add(new Matrix([[3]]));

        $this->assertEqualsWithDelta([[5]], $result->data, 0.0001);
    }

    public function test_add_different_sizes_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("tamanho");
        (new Matrix([[1, 2]]))->add(new Matrix([[1], [2]]));
    }

    public function test_subtract(): void
    {
        $result = (new Matrix([[5, 6], [7, 8]]))->subtract(new Matrix([[1, 2], [3, 4]]));

        $this->assertEqualsWithDelta([[4, 4], [4, 4]], $result->data, 0.0001);
    }

    public function test_subtract_different_sizes_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("tamanho");
        (new Matrix([[1, 2]]))->subtract(new Matrix([[1, 2, 3]]));
    }

    public function test_multiply_by_scalar(): void
    {
        $result = (new Matrix([[1, 2], [3, 4]]))->multiplyByScalar(0.5);

        $this->assertEqualsWithDelta([[0.5, 1], [1.5, 2]], $result->data, 0.0001);
    }

    public function test_multiply_by_zero_gives_zero_matrix(): void
    {
        $result = (new Matrix([[1, 2], [3, 4]]))->multiplyByScalar(0);

        $this->assertEqualsWithDelta([[0, 0], [0, 0]], $result->data, 0.0001);
    }

    public function test_multiply_matrices(): void
    {
        $a = new Matrix([[1, 2, 3], [4, 5, 6]]);
        $b = new Matrix([[7, 8], [9, 10], [11, 12]]);

        $this->assertEqualsWithDelta([[58, 64], [139, 154]], $a->multiply($b)->data, 0.0001);
    }

    public function test_multiply_by_identity_keeps_matrix(): void
    {
        $a = new Matrix([[1.5, -2], [3, 4.25]]);
        $identity = new Matrix([[1, 0], [0, 1]]);

        $this->assertEqualsWithDelta($a->data, $a->multiply($identity)->data, 0.0001);
    }

    public function test_multiply_by_zero_matrix(): void
    {
        $a = new Matrix([[1, 2], [3, 4]]);
        $zero = new Matrix([[0, 0], [0, 0]]);

        $this->assertEqualsWithDelta([[0, 0], [0, 0]], $a->multiply($zero)->data, 0.0001);
    }

    public function test_multiply_wrong_sizes_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("colunas");
        (new Matrix([[1, 2, 3]]))->multiply(new Matrix([[1, 2, 3]]));
    }

    public function test_transpose(): void
    {
        $m = new Matrix([[1, 2, 3], [4, 5, 6]]);

        $this->assertEqualsWithDelta([[1, 4], [2, 5], [3, 6]], $m->transpose()->data, 0.0001);
    }

    public function test_transpose_twice_gives_original(): void
    {
        $m = new Matrix([[1, 2, 3], [4, 5, 6]]);

        $this->assertEqualsWithDelta($m->data, $m->transpose()->transpose()->data, 0.0001);
    }

    public function test_opposite_matrix(): void
    {
        $result = (new Matrix([[2, -3], [1, 4]]))->multiplyByScalar(-1);

        $this->assertEqualsWithDelta([[-2, 3], [-1, -4]], $result->data, 0.0001);
    }

    public function test_class_examples(): void
    {
        $sum = (new Matrix([[1, 2]]))->add(new Matrix([[3, 4]]));
        $difference = (new Matrix([[5, 7]]))->subtract(new Matrix([[2, 3]]));
        $scalar = (new Matrix([[2, 4]]))->multiplyByScalar(3);

        $this->assertEqualsWithDelta([[4, 6]], $sum->data, 0.0001);
        $this->assertEqualsWithDelta([[3, 4]], $difference->data, 0.0001);
        $this->assertEqualsWithDelta([[6, 12]], $scalar->data, 0.0001);
    }
}
