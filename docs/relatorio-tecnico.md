Relatório Técnico (Calculadora de Álgebra Linear)
Integrantes: Ana Sandrine e Eduardo Rodrigues

1. Visão geral

O projeto é uma aplicação web em PHP com os algoritmos de álgebra linear implementados. A lógica fica na pasta "src" e a página em "index.php" só lê os dados do formulário, chama as classes e mostra o resultado. 

2. Lógica de cada algoritmo

SOMA E SUBTRAÇÃO:
As duas matrizes precisam ter o mesmo tamanho. O resultado é feito somando ou subtraindo elemento por elemento, na mesma posição.

MULTIPLICAÇÃO POR ESCALAR: 
Cada elemento da matriz é multiplicado pelo número informado.

MULTIPLICAÇÃO DE MATRIZES: 
O número de colunas de A tem que ser igual ao número de linhas de B. 
O elemento da posição (i, j) do resultado é a soma dos produtos da linha i de A com a coluna j de B, feita com três laços.

MATRIZ OPOSTA:
É o mesmo que multiplicar a matriz por -1, então reaproveita a multiplicação por escalar.

TRANSPOSTA:
Cria uma nova matriz em que o elemento (i, j) da original vai para a posição (j, i).

DETERMINANTE: 
Só vale para matriz quadrada. foi usada a eliminação de Gauss: para cada coluna é escolhida como pivô a linha com o maior valor absoluto, zero os elementos abaixo dele e vou multiplicando os pivôs. 
Cada troca de linhas inverte o sinal do resultado. Se não existir pivô diferente de zero, o determinante é 0. A complexidade é O(n³).

INVERSÃO: 
Gauss-Jordan: aplico nas linhas da matriz A as mesmas operações em uma matriz identidade. Quando A vira a identidade, a outra matriz virou a inversa. Se em alguma coluna não existir pivô, a matriz é singular e é lançada uma exceção.

SISTEMA LINEARES: 
O sistema Ax = b é montado como uma matriz aumentada [A | b] e levado à forma escalonada reduzida com Gauss-Jordan. Depois a classificação é feita olhando as colunas que tiveram pivô:

se a coluna do b tem pivô, existe uma linha do tipo 0 = c (com c diferente de zero), então o sistema é impossível (SI), como duas retas paralelas.

se todas as colunas de A têm pivô, a solução é única e fica na última coluna, então o sistema é possível e determinado (SPD), como duas retas concorrentes.

se sobrar coluna de A sem pivô, o sistema é possível e indeterminado (SPI), como duas retas coincidentes.

Isso é o mesmo que comparar o posto de A com o posto de [A | b], e funciona também quando o sistema não é quadrado.

3. Decisões de design

ESTRUTURA DE DADOS:
A matriz é guardada como um array de arrays do PHP, dentro da classe "Matrix". O construtor valida os dados (matriz vazia, linhas de tamanhos diferentes ou valores que não são números). As operações não alteram a matriz original, sempre devolvem uma nova. A busca pelo pivô (linha com maior valor absoluto na coluna) fica num método só, usado pelo determinante, pela inversa e pelo sistema linear.

TOLERÂNCIA: 
Comparar número decimal com "== 0" dá problema por causa dos erros de arredondamento (por exemplo, 0.1 + 0.2 não dá exatamente 0.3). Por isso existe a constante "Matrix::EPSILON" (1e-10), e um valor menor que ela é tratado como zero ao procurar pivôs. Nos testes, as comparações usam "assertEqualsWithDelta()".

PIVOTEMANETO PARCIAL: 
Em vez de pegar o primeiro elemento da coluna como pivô, o de maior valor absoluto é selecionado. Isso evita dividir por números muito pequenos e resolve o caso em que o pivô seria zero, mas existe outra linha que serve.

ERROS: 
Quando a operação não pode ser feita (dimensões incompatíveis, matriz singular, sistema impossível ou indeterminado), o código lança uma "Exception" do PHP com uma mensagem em português explicando o problema, em vez de retornar "false" ou "null". Os testes conferem a mensagem com "expectExceptionMessage()" e a página captura a exceção e mostra a mensagem para o usuário.

TESTES:
Cada grupo de algoritmo tem sua classe de testes, com casos normais, de borda e de erro.

#4. Dificuldades encontradas

NUMEROS DECIMAIS:
No começo alguns testes falhavam por diferenças muito pequenas (como 1.4000000000000004). foi usado "assertEqualsWithDelta()" e a constante de tolerância.

PIVÔ ZERO:
Matrizes como [[0, 2], [3, 4]] davam divisão por zero na primeira versão. Resolvido com a troca de linhas (pivoteamento).

CLASSIFICAR SISTEMA: 
Entender como diferenciar sistema impossível de indeterminado foi a parte mais complicada. A solução foi olhar em qual coluna ficam os pivôs depois do escalonamento.

COBERTURA: 
Para chegar na cobertura pedida foi preciso testar também os caminhos de erro (dimensões erradas, matriz singular, sistemas sem solução única).
