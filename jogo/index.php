<?php
$fluxo = json_decode(file_get_contents("fluxo.json"));

if(isset($_GET['prt']) && isset($_GET['opc']) ) {
    $parte = "parte" . intval($_GET['prt']);
    $opcao = "opc" . intval($_GET['opc']);

    if(isset($fluxo->$parte->$opcao)) {
        $atual = $fluxo->$parte->$opcao;

        $retorno = [
            "status" => true,
            "texto" => $atual->texto,
            "opcoes" => $atual->opcoes
        ];
    } else $retorno = ["status" => false];

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
                <div id="texto">Texto introdutório</div>
                <div id="caixa-opcoes">
                    <div class="opcao marcada">Opção 1</div>
                    <div class="opcao">Opção 2</div>
                </div>
            </div>
        </main>

        <footer>Alguma coisa aqui</footer>
    
        <script src="escolhas.js"></script>
    </body>
</html>