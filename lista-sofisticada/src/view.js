window.addEventListener("load", function(){
  /* Opção Encolher */
  let opcoes_encolher = document.getElementsByClassName("opcao_encolher");
  
  for(let i = 0; i < opcoes_encolher.length; i++){
    opcoes_encolher[i].innerHTML = opcoes_encolher[i].innerText;
    opcoes_encolher[i].classList.remove("tag_oculta");
    opcoes_encolher[i].identificador = i;
    opcoes_encolher[i].addEventListener("click", evento_da_opcao_encolher);
    opcoes_encolher[i].addEventListener("mousedown", prevenir_clique_duplo_selecionar_texto);
  }
  
  function evento_da_opcao_encolher(evento){
    const tag_que_disparou_o_evento = evento.currentTarget;
    const identificador = tag_que_disparou_o_evento.identificador;
    tag_que_disparou_o_evento.classList.add("tag_oculta");
    
    const conteudos = document.getElementsByClassName("conteudo_da_lista");
    for(let i = 0; i < conteudos.length; i++){
      if(identificador === i){
        conteudos[i].classList.add("tag_oculta");
        break;
      }
    }
    
    let opcoes_expandir = document.getElementsByClassName("opcao_expandir");
    for(let i = 0; i < opcoes_expandir.length; i++){
      if(identificador === i){
        opcoes_expandir[i].classList.remove("tag_oculta");
        break;
      }
    }
  }
  
  function prevenir_clique_duplo_selecionar_texto(evento){
    evento.preventDefault();
  };
  
  /* Opção Expandir */
  let opcoes_expandir = document.getElementsByClassName("opcao_expandir");
  
  for(let i = 0; i < opcoes_expandir.length; i++){
    opcoes_expandir[i].innerHTML = opcoes_expandir[i].innerText;
    opcoes_expandir[i].identificador = i;
    opcoes_expandir[i].addEventListener("click", evento_da_opcao_expandir);
    opcoes_expandir[i].addEventListener("mousedown", prevenir_clique_duplo_selecionar_texto);
  }
  
  function evento_da_opcao_expandir(evento){
    const tag_que_disparou_o_evento = evento.currentTarget;
    const identificador = tag_que_disparou_o_evento.identificador;
    tag_que_disparou_o_evento.classList.add("tag_oculta");
    
    const conteudos = document.getElementsByClassName("conteudo_da_lista");
    for(let i = 0; i < conteudos.length; i++){
      if(identificador === i){
        conteudos[i].classList.remove("tag_oculta");
        break;
      }
    }
    
    let opcoes_encolher = document.getElementsByClassName("opcao_encolher");
    for(let i = 0; i < opcoes_encolher.length; i++){
      if(identificador === i){
        opcoes_encolher[i].classList.remove("tag_oculta");
        break;
      }
    }
  }
});
