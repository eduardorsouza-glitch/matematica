Calculadora de Álgebra Linear

Aplicação web em PHP que faz operações com matrizes e resolve sistemas lineares. Cada algoritmo tem testes unitários feitos com PHPUnit.

Integrantes: Ana Sandrine e Eduardo Rodrigues

COMO INSTALAR E RODAR:

1. Clonagem do repositório dentro da pasta Herd.
2. Instalação do composer (composer install).
3. Abrir o endereço do Herd.

Para digitar uma matriz, escreva uma linha da matriz por linha do campo, com os números separados por espaço:

1 2
3 4

No sistema linear, a matriz A tem os coeficientes e o campo B recebe o vetor b, um número por linha. Os campos que a operação escolhida não usa (B ou escalar) podem ficar vazios.

ALGORITMOS IMPLEMENTADO:

Soma de matrizes
Subtração de matrizes
Multiplicação por escalar
Matriz oposta (-A)
Multiplicação de matrizes
Transposta
Determinante (eliminação de Gauss com pivoteamento)
Inversa (Gauss-Jordan)
Resolução de sistemas lineares (Gauss-Jordan)
Classificação de sistemas: SPD (possível e determinado), SPI (possível e indeterminado) ou SI (impossível)

TESTES:

Os testes ficam na pasta "tests", uma classe para cada grupo de algoritmo:

MatrixTest: construção, soma, subtração, escalar, multiplicação e transposta
DeterminantTest: determinante
InverseTest: inversa
LinearSystemTest: resolução e classificação de sistemas

Eles cobrem casos normais, casos de borda (matriz 1x1, identidade e nula), casos de erro (dimensões incompatíveis, matriz singular, sistema impossível e indeterminado) e usam "assertEqualsWithDelta()" nas comparações com números decimais.

ESTRUTURA:

```
src/                  Matrix e LinearSystem
tests/                testes PHPUnit
docs/                 relatório técnico e prints
index.php             interface web
style.css             estilo da página
phpunit.xml           configuração do PHPUnit
```

RELATÓRIO TECNICO
[docs/relatorio-tecnico.md]
