<?php

namespace Tests;

use App\LinearSystem;
use App\Matrix;
use Exception;
use PHPUnit\Framework\TestCase;

class LinearSystemTest extends TestCase
{
    public function test_solve_2x2(): void
    {
        $system = new LinearSystem(new Matrix([[1, 1], [1, -1]]), new Matrix([[3], [1]]));

        $this->assertEqualsWithDelta([2, 1], $system->solve(), 0.0001);
    }

    public function test_solve_3x3(): void
    {
        $a = new Matrix([[2, 1, -1], [-3, -1, 2], [-2, 1, 2]]);
        $b = new Matrix([[8], [-11], [-3]]);

        $this->assertEqualsWithDelta([2, 3, -1], (new LinearSystem($a, $b))->solve(), 0.0001);
    }

    public function test_solve_1x1(): void
    {
        $system = new LinearSystem(new Matrix([[4]]), new Matrix([[10]]));

        $this->assertEqualsWithDelta([2.5], $system->solve(), 0.0001);
    }

    public function test_solve_with_identity(): void
    {
        $system = new LinearSystem(new Matrix([[1, 0], [0, 1]]), new Matrix([[5], [7]]));

        $this->assertEqualsWithDelta([5, 7], $system->solve(), 0.0001);
    }

    public function test_solve_with_row_swap(): void
    {
        $system = new LinearSystem(new Matrix([[0, 2], [3, 1]]), new Matrix([[4], [5]]));

        $this->assertEqualsWithDelta([1, 2], $system->solve(), 0.0001);
    }

    public function test_solve_with_decimals(): void
    {
        $system = new LinearSystem(new Matrix([[0.5, 0.25], [1, 3]]), new Matrix([[1], [5]]));

        $this->assertEqualsWithDelta([1.4, 1.2], $system->solve(), 0.0001);
    }

    public function test_classify_determined(): void
    {
        $system = new LinearSystem(new Matrix([[1, 1], [1, -1]]), new Matrix([[3], [1]]));

        $this->assertSame("SPD", $system->classify());
    }

    public function test_classify_impossible(): void
    {
        $system = new LinearSystem(new Matrix([[1, 1], [1, 1]]), new Matrix([[1], [2]]));

        $this->assertSame("SI", $system->classify());
    }

    public function test_classify_indeterminate(): void
    {
        $system = new LinearSystem(new Matrix([[1, 1], [2, 2]]), new Matrix([[2], [4]]));

        $this->assertSame("SPI", $system->classify());
    }

    public function test_solve_impossible_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("impossível");
        (new LinearSystem(new Matrix([[1, 1], [1, 1]]), new Matrix([[1], [2]])))->solve();
    }

    public function test_solve_indeterminate_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("indeterminado");
        (new LinearSystem(new Matrix([[1, 1], [2, 2]]), new Matrix([[2], [4]])))->solve();
    }

    public function test_b_with_two_columns_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("coluna");
        new LinearSystem(new Matrix([[1, 2], [3, 4]]), new Matrix([[1, 2], [3, 4]]));
    }

    public function test_b_with_wrong_rows_gives_error(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("linhas");
        new LinearSystem(new Matrix([[1, 2], [3, 4]]), new Matrix([[1], [2], [3]]));
    }

    public function test_motors_problem(): void
    {
        $a = new Matrix([[1, 1, 1], [20, 25, 20], [20, 12, 10]]);
        $b = new Matrix([[17000], [355000], [226000]]);

        $this->assertEqualsWithDelta([5000, 3000, 9000], (new LinearSystem($a, $b))->solve(), 0.0001);
    }

    public function test_fruits_problem(): void
    {
        $a = new Matrix([[3, 0, 0], [1, 2, 0], [0, 1, -1]]);
        $b = new Matrix([[30], [18], [2]]);

        $x = (new LinearSystem($a, $b))->solve();

        $this->assertEqualsWithDelta([10, 4, 2], $x, 0.0001);
        $this->assertEqualsWithDelta(16, $x[2] + $x[0] + $x[1], 0.0001);
    }

    public function test_insurance_problem(): void
    {
        $a = new Matrix([[1, 1], [1200, 900]]);
        $b = new Matrix([[500], [546000]]);

        $this->assertEqualsWithDelta([320, 180], (new LinearSystem($a, $b))->solve(), 0.0001);
    }

    public function test_clothes_problem(): void
    {
        $a = new Matrix([[2, 3], [0.95, 0.95]]);
        $b = new Matrix([[520], [204.25]]);

        $this->assertEqualsWithDelta([125, 90], (new LinearSystem($a, $b))->solve(), 0.0001);
    }
}
