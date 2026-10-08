<?php

require_once 'vendor/autoload.php';

use App\LinearSystem;
use App\Matrix;

function readMatrix(string $text): Matrix
{
    $rows = [];

    foreach (explode("\n", trim($text)) as $line) {
        if (trim($line) === '') {
            continue;
        }

        $row = [];

        foreach (preg_split('/\s+/', trim($line)) as $item) {
            $item = str_replace(',', '.', $item);

            if (!is_numeric($item)) {
                throw new Exception("Valor inválido: " . $item);
            }

            $row[] = (float) $item;
        }

        $rows[] = $row;
    }

    return new Matrix($rows);
}

function show($number): string
{
    return (string) (round($number, 4) + 0);
}

$operation = $_POST['operation'] ?? 'add';
$textA = $_POST['matrixA'] ?? '';
$textB = $_POST['matrixB'] ?? '';
$scalar = $_POST['scalar'] ?? '';

$typeNames = [
    'SPD' => 'possível e determinado (SPD): uma única solução',
    'SPI' => 'possível e indeterminado (SPI): infinitas soluções',
    'SI' => 'impossível (SI): não tem solução',
];

$matrix = null;
$number = null;
$type = null;
$solution = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $a = readMatrix($textA);

        if ($operation === 'add') {
            $matrix = $a->add(readMatrix($textB));
        } elseif ($operation === 'subtract') {
            $matrix = $a->subtract(readMatrix($textB));
        } elseif ($operation === 'scalar') {
            if (!is_numeric(str_replace(',', '.', $scalar))) {
                throw new Exception("Informe um número válido para o escalar.");
            }
            $matrix = $a->multiplyByScalar((float) str_replace(',', '.', $scalar));
        } elseif ($operation === 'opposite') {
            $matrix = $a->multiplyByScalar(-1);
        } elseif ($operation === 'multiply') {
            $matrix = $a->multiply(readMatrix($textB));
        } elseif ($operation === 'transpose') {
            $matrix = $a->transpose();
        } elseif ($operation === 'determinant') {
            $number = $a->determinant();
        } elseif ($operation === 'inverse') {
            $matrix = $a->inverse();
        } elseif ($operation === 'system') {
            $system = new LinearSystem($a, readMatrix($textB));
            $type = $system->classify();

            if ($type === 'SPD') {
                $solution = $system->solve();
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Álgebra Linear</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main class="container py-4">
        <h1 class="text-center mb-4">Calculadora de Álgebra Linear</h1>

        <div class="row g-4">
            <div class="col-lg-6">
                <form method="POST" class="card p-4">
                    <label class="form-label">Operação</label>
                    <select name="operation" class="form-select mb-3">
                        <option value="add" <?= $operation === 'add' ? 'selected' : '' ?>>Soma (A + B)</option>
                        <option value="subtract" <?= $operation === 'subtract' ? 'selected' : '' ?>>Subtração (A - B)</option>
                        <option value="scalar" <?= $operation === 'scalar' ? 'selected' : '' ?>>Multiplicação por escalar (A x k)</option>
                        <option value="opposite" <?= $operation === 'opposite' ? 'selected' : '' ?>>Oposta de A (-A)</option>
                        <option value="multiply" <?= $operation === 'multiply' ? 'selected' : '' ?>>Multiplicação (A x B)</option>
                        <option value="transpose" <?= $operation === 'transpose' ? 'selected' : '' ?>>Transposta de A</option>
                        <option value="determinant" <?= $operation === 'determinant' ? 'selected' : '' ?>>Determinante de A</option>
                        <option value="inverse" <?= $operation === 'inverse' ? 'selected' : '' ?>>Inversa de A</option>
                        <option value="system" <?= $operation === 'system' ? 'selected' : '' ?>>Sistema linear (A x = b)</option>
                    </select>

                    <label class="form-label">Matriz A</label>
                    <textarea name="matrixA" rows="4" class="form-control mb-1" placeholder="1 2&#10;3 4"><?= htmlspecialchars($textA) ?></textarea>
                    <small class="text-muted mb-3">Uma linha por linha da matriz, números separados por espaço.</small>

                    <label class="form-label">Matriz B (ou vetor b no sistema linear)</label>
                    <textarea name="matrixB" rows="4" class="form-control mb-3"><?= htmlspecialchars($textB) ?></textarea>

                    <label class="form-label">Escalar k</label>
                    <input type="text" name="scalar" class="form-control mb-3" value="<?= htmlspecialchars($scalar) ?>">

                    <button type="submit" class="btn btn-primary">Calcular</button>
                </form>
            </div>

            <div class="col-lg-6">
                <div class="card p-4">
                    <h2 class="h4">Resultado</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger mb-0"><?= htmlspecialchars($error) ?></div>
                    <?php elseif ($matrix): ?>
                        <table class="table table-bordered text-center w-auto mb-0">
                            <?php foreach ($matrix->data as $row): ?>
                                <tr>
                                    <?php foreach ($row as $value): ?>
                                        <td><?= show($value) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php elseif ($number !== null): ?>
                        <p class="fs-4 mb-0">det(A) = <?= show($number) ?></p>
                    <?php elseif ($type): ?>
                        <p>Sistema <strong><?= $typeNames[$type] ?></strong></p>
                        <?php if ($solution): ?>
                            <?php foreach ($solution as $i => $value): ?>
                                <p class="mb-1">x<?= $i + 1 ?> = <?= show($value) ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">Preencha os dados e clique em calcular.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
