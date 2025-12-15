<?php
// Carrega os arquivos da história e do save
$fluxo = json_decode(file_get_contents("data/fluxo.json"));
$save = json_decode(file_get_contents("data/save.json"));

// Leva para o final do caminho escolhido
function finaliza($save, $fluxo) {
    $caminho = $save->estado;

    // Verifica qual caminho foi tomado para atribuir o final
    if($caminho == "caminho1")
        $final = "final-1";
    else if($caminho == "caminho2")
        $final = "final-2";
    else if($caminho == "caminho3")
        $final = "final-3";

    // Caso o final não exista
    if(!isset($final)) {
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
    // Une o estado do save com a próxima parte
    $parte = $save->estado . "-p" . ++$save->parte;

    // Se a próxima parte não existe
    if(!isset($fluxo->$parte)) {
        // Tenta finalizar a introdução
        if(str_starts_with($save->estado, "introducao")) {
            $save->estado = "caminho" . $_GET['opc'];
            $save->parte = 1;
        } else {
            // Tenta achar um final
            finaliza($save, $fluxo);
        }
    }

    // Atualiza a parte
    $parte = $save->estado . "-p" . $save->parte;

    // Salva no arquivo
    $json = json_encode($save, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents("data/save.json", $json);

    $retorno = [
        "status" => true,
        "texto" => $fluxo->$parte->texto,
        "opcoes" => $fluxo->$parte->opcoes
    ];

    // Retorna os dados da parte para o js
    echo json_encode($retorno, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Caso esteja criando um novo save
if(isset($_GET['novoSave'])) {
    // Reseta o save
    $save->estado = "introducao";
    $save->parte = 1;

    // Muda o arquivo
    $json = json_encode($save, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents("data/save.json", $json);

    exit;
}

// Carrega o save
$parte = $save->estado . "-p" . $save->parte;

// Se existe essa parte no fluxo
if(isset($fluxo->$parte)) {
    $texto = $fluxo->$parte->texto;
    $opcoes = $fluxo->$parte->opcoes;
} else {
    // Se não existe - cria do zero
    $introducao = "introducao-p1";

    $save->estado = "introducao";
    $save->parte = 1;

    $texto = $fluxo->$introducao->texto;
    $opcoes = $fluxo->$introducao->opcoes;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Aventura Invertida</title>
        <link rel="stylesheet" href="estilos/main.css">
        <link rel="icon" href="imgs/icone.png">
    </head>

    <body>
        <header>
            <h1 class="titulo">
                <span>STRANGER</span><br>THINGS
            </h1>
        </header>

        <main>
            <div id="caixa-principal" class="caixa">
                <!-- Cria o texto dinamicamente -->
                <div id="texto"><?= implode(" ", $texto) ?></div>

                <div id="container">
                    <div id="caixa-opcoes">
                        <!-- Cria as opções dinamicamente -->
                        <?php foreach($opcoes as $opcao): ?>
                            <button type="button" class="opcao"><?= $opcao ?></button>
                        <?php endforeach ?>
                    </div>
                    <a href="index.php">Voltar pro início</a>
                </div>
            </div>
        </main>

        <footer><span>©</span> 2025 Todos os direitos reservados. <a href="infos.php">sobre a página</a></footer>
    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="escolhas.js"></script>
    </body>
</html>