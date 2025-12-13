// Array de opcões
let opcoesEl = Array.from(document.querySelectorAll('.opcao'));

// Áudio
let musica = new Audio("audio/tema-principal.mp3");

// Toca a música ao iniciar
window.onload = () => {
    musica.play();
}

// Seleciona a opção marcada
async function selecionaOpcao(indice) {
    let resposta = await fetch("main.php?opc=" + (indice + 1));
    let resultado = await resposta.json();

    if(resultado.status === true)
        mudaConteudo(resultado.texto, resultado.opcoes);
}

// Muda o texto principal e as opcões
function mudaConteudo(texto, opcoes) {
    // Insere cada linha do texto
    $('#texto').text(texto.join(" "));

    // Remove os antigos elementos de opção
    $('.opcao').remove();
    opcoesEl = [];

    opcoes.forEach((opcao, indice) => {
        // Cria o elemento com a classe .opcao e insere o texto
        const novoEl = $('<button type="button" class="opcao"></button>').text(opcao);

        // Coloca o elemento na caixa e atribui um evento de clique
        $('#caixa-opcoes').append(novoEl);
        $(novoEl).click(async () => await selecionaOpcao(indice));

        // Põe o elemento no vetor
        opcoesEl.push(novoEl[0]);
    });
}

// Detecta se o usuário clicou em uma opção
opcoesEl.forEach((opcao, indice) => {
    $(opcao).click(async () => await selecionaOpcao(indice));
});

// Loop da música
musica.addEventListener("ended", () => {
    setTimeout(() => {
        musica.currentTime = 0;
        musica.play();
    }, 2000);
});