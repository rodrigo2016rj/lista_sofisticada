window.addEventListener("load", function(){
  /* Opção Encolher */
  let opcoes_encolher = document.getElementsByClassName("opcao_encolher");
  
  for(let i = 0; i < opcoes_encolher.length; i++){
    opcoes_encolher[i].addEventListener("click", evento_da_opcao_encolher);
    opcoes_encolher[i].addEventListener("mousedown", prevenir_clique_duplo_selecionar_texto);
  }
  
  function evento_da_opcao_encolher(evento){
    const tag_que_disparou_o_evento = evento.currentTarget;
    let id = tag_que_disparou_o_evento.getAttribute("id");
    if(id.startsWith("opcao_encolher_")){
      let identificador = id.substring(15);
      
      const conteudo = document.getElementById("conteudo_"+identificador);
      conteudo.classList.add("tag_oculta");
      
      tag_que_disparou_o_evento.classList.add("tag_oculta");
      
      const opcao_expandir = document.getElementById("opcao_expandir_"+identificador);
      opcao_expandir.classList.remove("tag_oculta");
    }
  }
  
  function prevenir_clique_duplo_selecionar_texto(evento){
    evento.preventDefault();
  };
  
  /* Opção Expandir */
  let opcoes_expandir = document.getElementsByClassName("opcao_expandir");
  
  for(let i = 0; i < opcoes_expandir.length; i++){
    opcoes_expandir[i].addEventListener("click", evento_da_opcao_expandir);
    opcoes_expandir[i].addEventListener("mousedown", prevenir_clique_duplo_selecionar_texto);
  }
  
  function evento_da_opcao_expandir(evento){
    const tag_que_disparou_o_evento = evento.currentTarget;
    let id = tag_que_disparou_o_evento.getAttribute("id");
    if(id.startsWith("opcao_expandir_")){
      let identificador = id.substring(15);
      
      const conteudo = document.getElementById("conteudo_"+identificador);
      conteudo.classList.remove("tag_oculta");
      
      tag_que_disparou_o_evento.classList.add("tag_oculta");
      
      const opcao_encolher = document.getElementById("opcao_encolher_"+identificador);
      opcao_encolher.classList.remove("tag_oculta");
    }
  }
});
