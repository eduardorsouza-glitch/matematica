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

COBERTURA DE TESTES:

Para executar a suíte de testes, utilize:

"vendor/bin/phpunit" ou "composer test"

Os testes estão localizados na pasta tests e são organizados de acordo com os algoritmos implementados:

MatrixTest: construção de matrizes, soma, subtração, multiplicação por escalar, multiplicação de matrizes e transposta.
DeterminantTest: cálculo do determinante.
InverseTest: cálculo da matriz inversa.
LinearSystemTest: resolução e classificação de sistemas lineares.

A suíte contempla casos normais, casos de borda (como matrizes 1×1, matriz identidade e matriz nula) e casos de erro (como dimensões incompatíveis, matriz singular e sistemas impossíveis ou indeterminados).

Nas comparações que envolvem números decimais, é utilizado o método assertEqualsWithDelta() para considerar pequenas diferenças de precisão numérica.

Resultado dos testes

A suíte possui 53 testes e 71 asserções, todos executados com sucesso:

OK (53 tests, 71 assertions)
Cobertura de código

A cobertura dos algoritmos foi gerada utilizando o Xdebug e o PHPUnit:

Classes: 100% (2/2)
Métodos: 100% (14/14)
Linhas: 100% (138/138)

O relatório completo de cobertura em HTML é gerado na pasta coverage/, no arquivo coverage/index.html.

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
