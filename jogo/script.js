const personagemEl = document.querySelector('#personagem');
const caixaEl = document.querySelector('#caixa');

let caixa = {
    h: 500,
    w: 500
};

let personagem = {
    x: caixa.w / 2,
    y: caixa.h / 2,
    h: caixa.h / 10,
    w: caixa.w / 10,
    v: caixa.w / 20
}

function atualizaPosicao() {
    let posicaoX = personagem.x - personagem.w / 2;
    let posicaoY = personagem.y - personagem.h / 2;

    personagemEl.style.left = `${posicaoX}px`;
    personagemEl.style.bottom = `${posicaoY}px`;
}

async function enviaEscolha(escolha = "") {
    let resposta = await fetch("index.php?escolha=" + escolha);
    let resultado= resposta.text();

    return resultado;
}

document.addEventListener("keypress", e => {
    if(e.key === 'w' && personagem.y + personagem.h / 2 < caixa.h) personagem.y += personagem.v;
    if(e.key === 's' && personagem.y - personagem.h / 2 > 0) personagem.y -= personagem.v;
    if(e.key === 'd' && personagem.x + personagem.w / 2 < caixa.w) personagem.x += personagem.v;
    if(e.key === 'a' && personagem.x - personagem.w / 2 > 0) personagem.x -= personagem.v;

    atualizaPosicao();
});