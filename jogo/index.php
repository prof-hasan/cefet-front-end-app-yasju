<?php
$fluxo = json_decode(file_get_contents("fluxo.json"));
$save = json_decode(file_get_contents("save.json"));

function finaliza($save, $fluxo) {
    $caminho = $save->estado;
    $final = "";
    $retorno = [];

    if(str_ends_with($caminho, "1A"))
        $final = "final1";
    else if(str_ends_with($caminho, "1B"))
        $final = "final2";
    else if(str_ends_with($caminho, "2A"))
        $final = "final3";
    else if(str_ends_with($caminho, "2B"))
        $final = "final4";
    else if(str_ends_with($caminho, "2C"))
        $final = "final5";

    if(!isset($fluxo->$final)) {
        echo json_encode(["status" => false]);
        exit;
    }

    $retorno = [
        "status" => true,
        "texto" => $fluxo->$final->texto
    ];

    echo json_encode($retorno, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if(isset($_GET['opc']) ) {
    if($save->parte >= 2)
        finaliza($save, $fluxo);

    $estadoAtual = $save->estado . $_GET['opc'];
    $retorno = [];

    if(isset($fluxo->$estadoAtual)) {
        $retorno = [
            "status" => true,
            "texto" => $fluxo->$estadoAtual->texto,
            "opcoes" => $fluxo->$estadoAtual->opcoes
        ];
    } else $retorno = ["status" => false];

    $save->estado = $estadoAtual;
    $save->parte++;
    file_put_contents("save.json", json_encode($save));

    echo json_encode($retorno, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Joguinho Hasan</title>
        <link rel="stylesheet" href="estilos.css">
    </head>

    <body>
        <header>Cabeçalho maroto</header>

        <main>
            <div id="caixa-principal">
                <div id="texto"><?= $fluxo->introducao->texto ?></div>
                <div id="caixa-opcoes">
                <?php
                foreach($fluxo->introducao->opcoes as $textoOpcao) {
                ?>
                    <div class="opcao"><?= $textoOpcao ?></div>
                <?php
                }
                ?>
                </div>
            </div>
        </main>

        <footer>Alguma coisa aqui</footer>
    
        <script src="escolhas.js"></script>
    </body>
</html>