<?php
// Carrega os arquivos da história e do save
$fluxo = json_decode(file_get_contents("fluxo.json"));
$save = json_decode(file_get_contents("save.json"));

// Define o número de partes da história
$numPartes = 2;

// Leva para o final do caminho escolhido
function finaliza($save, $fluxo) {
    $caminho = $save->estado;

    // Verifica qual caminho foi tomado para atribuir o final
    if(str_ends_with($caminho, "1-1"))
        $final = "final-1";
    else if(str_ends_with($caminho, "1-2"))
        $final = "final-2";
    else if(str_ends_with($caminho, "2-1"))
        $final = "final-3";
    else if(str_ends_with($caminho, "2-2"))
        $final = "final-4";
    else if(str_ends_with($caminho, "2-3"))
        $final = "final-5";

    // Caso o final não exista
    if(!isset($fluxo->$final)) {
        echo json_encode(["status" => false]);
        exit;
    }

    $retorno = [
        "status" => true,
        "texto" => $fluxo->$final->texto
    ];

    // Retorna os dados do final para o js
    echo json_encode($retorno, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Caso uma opção tenha sido escolhida
if(isset($_GET['opc']) ) {
    // Caso seja o chamado para o final
    if($save->parte >= $numPartes)
        finaliza($save, $fluxo);

    // Une o texto do estado anterior com o da nova opção
    $estadoAtual = $save->estado . "-" . $_GET['opc'];

    // Cria um array de retorno com os dados da opção escolhida
    if(isset($fluxo->$estadoAtual)) {
        $retorno = [
            "status" => true,
            "texto" => $fluxo->$estadoAtual->texto,
            "opcoes" => $fluxo->$estadoAtual->opcoes
        ];
    } else $retorno = ["status" => false];

    // Atualiza o save
    $save->estado = $estadoAtual;
    $save->parte++;

    $json = json_encode($save, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // Muda o arquivo do save e retorna os dados para o js
    file_put_contents("save.json", $json);
    echo json_encode($retorno, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Caso esteja criando um novo save
if(isset($_GET['novoSave'])) {
    // Reseta o save
    $save->estado = "caminho";
    $save->parte = 0;

    // Muda o arquivo
    $json = json_encode($save, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents("save.json", $json);

    exit;
}

// Caso o save tenha sido carregado
if($save->parte > 0) {
    $estado = $save->estado;

    // Se existe esse estado no fluxo
    if(isset($fluxo->$estado)) {
        $texto = $fluxo->$estado->texto;
        $opcoes = $fluxo->$estado->opcoes;
    } else {
        // Caso algo dê errado
        $texto = $fluxo->introducao->texto;
        $opcoes = $fluxo->introducao->opcoes;
    }
} else {
    // Caso não tenha save
    $texto = $fluxo->introducao->texto;
    $opcoes = $fluxo->introducao->opcoes;
}

// Easter egg
if(isset($_GET['easter-egg'])) {
    $texto = $fluxo->EasterEgg->texto;
    $opcoes = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Joguinho Hasan</title>
        <link rel="stylesheet" href="estilos.css?v=<?= time(); ?>">
    </head>

    <body>
        <header>
            <h2 class="titulo">
                <span>STRANGER</span><br>THINGS
            </h2>
        </header>

        <main>
            <div id="caixa-principal" class="caixa">
                <!-- Cria o texto dinamicamente -->
                <div id="texto"><?= $texto ?></div>

                <div id="caixa-opcoes">
                    <!-- Cria as opções dinamicamente -->
                    <?php foreach($opcoes as $opcao): ?>
                        <div class="opcao"><?= $opcao ?></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="caixa-lateral">
                <div id="botao-retorno"><a href="index.php">Voltar pro início</a></div>
            </div>
        </main>

        <footer><span>©</span> 2025 Todos os direitos reservados. <a href="infos.php">sobre a página</a></footer>
    
        <script src="//cdn.rawgit.com/namuol/cheet.js/master/cheet.min.js" type="text/javascript"></script>
        <script src="escolhas.js"></script>
    </body>
</html>