// Elementos da página
const textoEl = document.querySelector('#texto');
const caixaOpcoes = document.querySelector('#caixa-opcoes');

// Array de opcões
let opcoesEl = Array.from(document.querySelectorAll('.opcao'));

// índice da opção selecionada
let indice = 0;

// Elemento da opção selecionada
let opcaoFocada = opcoesEl[indice];

// Áudio
let musica = new Audio("audio/tema-principal.mp3");

// Foca na primeira opção ao carregar
window.onload = () => {
    focaOpcao(opcoesEl[indice]);
    musica.play();
}

// Foca na opção dada por parâmetro
function focaOpcao(opcao) {
    if(!opcao) return;

    opcaoFocada.classList.remove("marcada");
    opcaoFocada = opcao;
    opcaoFocada.classList.add("marcada");
}

// Seleciona a opção marcada
async function selecionaOpcao() {
    let resposta = await fetch("main.php?opc=" + (indice + 1));
    let resultado = await resposta.json();

    if(resultado.status === true)
        mudaConteudo(resultado.texto, resultado.opcoes);
}

// Muda o texto principal e as opcões
function mudaConteudo(texto, opcoes) {
    textoEl.textContent = texto;

    // Remove os antigos elementos
    opcoesEl.forEach(op => op.remove());
    opcoesEl.length = 0;

    // Cria os elementos e põe o texto neles
    opcoes.forEach(opcao => {
        const novoEl = document.createElement("div");
        novoEl.classList.add("opcao");
        novoEl.textContent = opcao;

        caixaOpcoes.appendChild(novoEl);
        opcoesEl.push(novoEl);
    });

    indice = 0;
    opcaoFocada = opcoesEl[indice];

    focaOpcao(opcaoFocada);
}

// Movimentação entre opções e seleção
document.addEventListener("keydown", async (e) => {
    if(e.key === 'Enter')
        await selecionaOpcao();

    if(e.key.toLowerCase() === 's') {
        indice++;
        if(indice > opcoesEl.length - 1) indice = 0;
    }

    if(e.key.toLowerCase() === 'w') {
        indice--;
        if(indice < 0) indice = opcoesEl.length - 1;
    }

    focaOpcao(opcoesEl[indice]);
});

musica.addEventListener("ended", () => {
    setTimeout(() => {
        musica.currentTime = 0;
        musica.play();
    }, 2000);
});

// Easter egg
cheet("z", () => {
    window.location.href = "main.php?easter-egg";
});