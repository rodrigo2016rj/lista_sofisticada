import {__} from "@wordpress/i18n";
import {useSelect, useDispatch} from "@wordpress/data";
import {useBlockProps} from "@wordpress/block-editor";
import {Icon, TextControl, TextareaControl} from "@wordpress/components";
import {plusCircle, cancelCircleFilled, chevronUp, chevronDown} from "@wordpress/icons";
import "./editor.scss";

export default function Edit({className, attributes: attr, setAttributes, clientId}){
  
  attr.lista = attr.lista ?? {
    titulo: {
      texto: "Título da Lista"
    },
    opcao_de_remocao: {
      mostrar_menu_remocao: false
    },
    opcao_de_adicao: {
      mostrar_menu_adicao: false,
      texto_dos_itens: ""
    },
    conteudo: {
      itens: Array()
    }
  };
  
  const obter_id_root = useSelect("core/block-editor").getBlockRootClientId;
  const obter_posicao_do_bloco = useSelect("core/block-editor").getBlockIndex;
  const obter_blocos = useSelect("core/block-editor").getBlocks;
  const mover_bloco = useDispatch("core/block-editor").moveBlockToPosition;
  const remover_bloco = useDispatch("core/block-editor").removeBlock;
  
  let lista_superior = null;
  let posicao_na_lista_superior = null;
  
  attr.conteudo_do_bloco = criar_conteudo_do_bloco(attr.lista);
  
  function criar_conteudo_do_bloco(lista){
    const elementos_react = Array();
    
    elementos_react.push(
      <div className="opcoes_e_titulo_da_lista">
        {opcoes_mover(lista_superior, posicao_na_lista_superior)}
        <div className="local_do_titulo_da_lista">
          <TextControl value={lista.titulo.texto} 
                       onChange={(valor) => editar_entrada_de_texto(lista.titulo, valor)}/>
        </div>
        <div className="opcao_remover" title="Remover lista"
             onClick={() => mostrar_menu_remocao(lista.opcao_de_remocao)}>
          <Icon icon={cancelCircleFilled}/>
        </div>
        {menu_remocao(lista_superior, posicao_na_lista_superior, lista.opcao_de_remocao.mostrar_menu_remocao)}
      </div>
    );
    
    //A posição antes do conteudo_da_lista vale -1.
    elementos_react.push(
      <div className="opcao_adicionar_item">
        <div className="icone_e_texto_da_opcao" title="Adicionar" 
             onClick={() => mostrar_menu_adicao(lista.opcao_de_adicao)}>
          <Icon icon={plusCircle}/>
          <span>Adicione itens</span>
        </div>
        {menu_adicao(lista, -1, lista.opcao_de_adicao.mostrar_menu_adicao)}
      </div>
    );
    
    //Conteúdo da lista:
    const elementos_react_do_conteudo = Array();
    for(let posicao = 0; posicao < lista.conteudo.itens.length; posicao++){
      const item = lista.conteudo.itens[posicao];
      
      if("titulo" in item){
        //O item é uma lista
        lista_superior = lista;
        posicao_na_lista_superior = posicao;
        elementos_react_do_conteudo.push(criar_conteudo_do_bloco(item));
      }else if("mostrar_menu_adicao" in item){
        //O item é uma opção de adição
        elementos_react_do_conteudo.push(
          <div className="opcao_adicionar_item">
            <div className="icone_e_texto_da_opcao" title="Adicionar" 
                 onClick={() => mostrar_menu_adicao(item)}>
              <Icon icon={plusCircle}/>
              <span>Adicione itens</span>
            </div>
            {menu_adicao(lista, posicao, item.mostrar_menu_adicao)}
          </div>
        );
      }else{
        //O item é um item simples
        elementos_react_do_conteudo.push(
          <div className="item_simples">
            <div className="opcoes_e_texto_do_item_simples">
              {opcoes_mover(lista, posicao)}
              <div className="local_do_texto_do_item">
                <TextControl value={item.texto} 
                             onChange={(valor) => editar_entrada_de_texto(item, valor)}/>
              </div>
              <div className="opcao_remover" title="Remover texto"
                   onClick={() => mostrar_menu_remocao(item.opcao_de_remocao)}>
                <Icon icon={cancelCircleFilled}/>
              </div>
              {menu_remocao(lista, posicao, item.opcao_de_remocao.mostrar_menu_remocao)}
            </div>
          </div>
        );
      }
    }
    elementos_react.push(
      <div className="conteudo_da_lista">
        {elementos_react_do_conteudo}
      </div>
    );
    
    return(
      <div className="lista_editavel">
        {elementos_react}
      </div>
    );
  }
  
  function opcoes_mover(lista, posicao){
    return(
      <div className="opcoes_mover">
        <div className="opcao_mover_para_cima" title="Mover para cima"
             onClick={() => mover(lista, posicao, "cima")}>
          <Icon icon={chevronUp}/>
        </div>
        <div className="opcao_mover_para_baixo" title="Mover para baixo"
             onClick={() => mover(lista, posicao, "baixo")}>
          <Icon icon={chevronDown}/>
        </div>
      </div>
    );
  }
  
  const mover = (lista, posicao, direcao) => {
    if(lista !== null){
      switch(direcao){
        case "cima":
          if(posicao > 0){
            const backup = lista.conteudo.itens[posicao-2];
            lista.conteudo.itens[posicao-2] = lista.conteudo.itens[posicao];
            lista.conteudo.itens[posicao] = backup;
          }
        break;
        case "baixo":
          if(posicao < lista.conteudo.itens.length - 2){
            const backup = lista.conteudo.itens[posicao+2];
            lista.conteudo.itens[posicao+2] = lista.conteudo.itens[posicao];
            lista.conteudo.itens[posicao] = backup;
          }
        break;
      }
    }else{
      const id_root = obter_id_root(clientId);
      const quantidade_de_blocos = obter_blocos().length;
      posicao = obter_posicao_do_bloco(clientId);
      
      switch(direcao){
        case "cima":
          if(posicao > 0){
            mover_bloco(clientId, id_root, id_root, posicao-1);
          }
        break;
        case "baixo":
          if(posicao < quantidade_de_blocos - 1){
            mover_bloco(clientId, id_root, id_root, posicao+1);
          }
        break;
      }
    }
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  const editar_entrada_de_texto = (objeto, valor) => {
    objeto.texto = valor;
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  const mostrar_menu_remocao = (objeto) => {
    objeto.mostrar_menu_remocao = true;
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  function menu_remocao(lista, posicao, mostrar){
    if(mostrar){
      return(
        <div className="menu_remocao">
          <div className="botoes_da_remocao">
            <button className="botao_remover_item" title="Remover" 
                    onClick={() => remover_item(lista, posicao)}>Remover</button>
            <button className="botao_cancelar_remocao" title="Cancelar" 
                    onClick={() => cancelar_remocao(lista, posicao)}>Cancelar</button>
          </div>
        </div>
      );
    }else{
      return null;
    }
  }
  
  const remover_item = (lista, posicao) => {
    if(lista !== null){
      lista.conteudo.itens.splice(posicao, 2);
    }else{
      remover_bloco(clientId, obter_id_root(clientId));
    }
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  const cancelar_remocao = (lista, posicao) => {
    if(lista !== null){
      lista.conteudo.itens[posicao].opcao_de_remocao.mostrar_menu_remocao = false;
    }else{
      attr.lista.opcao_de_remocao.mostrar_menu_remocao = false;
    }
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  const mostrar_menu_adicao = (objeto) => {
    objeto.mostrar_menu_adicao = "Opções de adição";
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  function menu_adicao(lista, posicao, mostrar){
    switch(mostrar){
      case "Opções de adição":
        return(
          <div className="menu_adicao">
            <span className="pergunta_da_adicao">Adicionar lista, item ou itens?</span>
            <div className="botoes_da_adicao">
              <button className="botao_adicionar_lista" title="Adicionar lista" 
                      onClick={() => adicionar_lista(lista, posicao)}>Lista</button>
              <button className="botao_adicionar_item_simples" title="Adicionar item" 
                      onClick={() => adicionar_item_simples(lista, posicao)}>Item</button>
              <button className="botao_adicionar_itens_simples" title="Adicionar itens" 
                      onClick={() => adicionar_itens_simples(lista, posicao)}>Itens</button>
              <button className="botao_cancelar_adicao" title="Cancelar" 
                      onClick={() => cancelar_adicao(lista, posicao)}>Cancelar</button>
            </div>
          </div>
        );
      break;
      case "Área de texto para vários itens":
        return(
          <div className="menu_adicao_de_itens">
            <span className="pergunta_da_adicao_de_itens">Separe cada item por quebra de linha</span>
            <div className="area_de_texto_da_adicao_de_itens">
              {area_de_texto(lista, posicao)}
            </div>
            <div className="botoes_da_adicao_de_itens">
              <button className="botao_confirmar_adicao_de_itens_simples" title="Adicionar itens" 
                      onClick={() => confirmar_adicao_de_itens_simples(lista, posicao)}>Adicionar</button>
              <button className="botao_cancelar_adicao_de_itens_simples" title="Cancelar" 
                      onClick={() => cancelar_adicao_de_itens_simples(lista, posicao)}>Cancelar</button>
            </div>
          </div>
        );
      break;
      default:
        return null;
      break;
    }
  }
  
  function area_de_texto(lista, posicao){
    if(posicao === -1){
      return(
        <TextareaControl value={lista.opcao_de_adicao.texto_dos_itens} 
                         onChange={(valor) => editar_area_de_texto(lista.opcao_de_adicao, valor)}/>
      );
    }
    return(
      <TextareaControl value={lista.conteudo.itens[posicao].texto_dos_itens} 
                       onChange={(valor) => editar_area_de_texto(lista.conteudo.itens[posicao], valor)}/>
    );
  }
  const adicionar_lista = (lista, posicao) => {
    const item_lista = {
      titulo: {
        texto: "Título da Lista"
      },
      opcao_de_remocao: {
        mostrar_menu_remocao: false
      },
      opcao_de_adicao: {
        mostrar_menu_adicao: false,
        texto_dos_itens: ""
      },
      conteudo: {
        itens: Array()
      }
    };
    lista.conteudo.itens.splice(posicao+1, 0, item_lista);
    
    const item_opcao_de_adicao = {
      mostrar_menu_adicao: false,
      texto_dos_itens: ""
    };
    lista.conteudo.itens.splice(posicao+2, 0, item_opcao_de_adicao);
    
    if(posicao === -1){
      lista.opcao_de_adicao.mostrar_menu_adicao = false;
      lista.opcao_de_adicao.texto_dos_itens = "";
    }else{
      lista.conteudo.itens[posicao].mostrar_menu_adicao = false;
      lista.conteudo.itens[posicao].texto_dos_itens = "";
    }
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  const adicionar_item_simples = (lista, posicao) => {
    const item_simples = {
      texto: "Texto",
      opcao_de_remocao: {
        mostrar_menu_remocao: false
      }
    };
    lista.conteudo.itens.splice(posicao+1, 0, item_simples);
    
    const item_opcao_de_adicao = {
      mostrar_menu_adicao: false,
      texto_dos_itens: ""
    };
    lista.conteudo.itens.splice(posicao+2, 0, item_opcao_de_adicao);
    
    if(posicao === -1){
      lista.opcao_de_adicao.mostrar_menu_adicao = false;
      lista.opcao_de_adicao.texto_dos_itens = "";
    }else{
      lista.conteudo.itens[posicao].mostrar_menu_adicao = false;
      lista.conteudo.itens[posicao].texto_dos_itens = "";
    }
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  const adicionar_itens_simples = (lista, posicao) => {
    if(posicao === -1){
      lista.opcao_de_adicao.mostrar_menu_adicao = "Área de texto para vários itens";
      lista.opcao_de_adicao.texto_dos_itens = "";
    }else{
      lista.conteudo.itens[posicao].mostrar_menu_adicao = "Área de texto para vários itens";
      lista.conteudo.itens[posicao].texto_dos_itens = "";
    }
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  const cancelar_adicao = (lista, posicao) => {
    if(posicao === -1){
      lista.opcao_de_adicao.mostrar_menu_adicao = false;
      lista.opcao_de_adicao.texto_dos_itens = "";
    }else{
      lista.conteudo.itens[posicao].mostrar_menu_adicao = false;
      lista.conteudo.itens[posicao].texto_dos_itens = "";
    }
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  const editar_area_de_texto = (objeto, valor) => {
    objeto.texto_dos_itens = valor;
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  const confirmar_adicao_de_itens_simples = (lista, posicao) => {
    let texto_dos_itens = "";
    if(posicao === -1){
      texto_dos_itens = lista.opcao_de_adicao.texto_dos_itens.replace(/\r/g, "");
    }else{
      texto_dos_itens = lista.conteudo.itens[posicao].texto_dos_itens.replace(/\r/g, "");
    }
    
    const array_itens = texto_dos_itens.split(/\n/);
    
    let contador = 1;
    for(let i = 0; i < array_itens.length; i++){
      const item_simples = {
        texto: array_itens[i],
        opcao_de_remocao: {
          mostrar_menu_remocao: false
        }
      };
      lista.conteudo.itens.splice(posicao+contador, 0, item_simples);
      contador++;
      
      const item_opcao_de_adicao = {
        mostrar_menu_adicao: false,
        texto_dos_itens: ""
      };
      lista.conteudo.itens.splice(posicao+contador, 0, item_opcao_de_adicao);
      contador++;
      
      if(posicao === -1){
        lista.opcao_de_adicao.mostrar_menu_adicao = false;
        lista.opcao_de_adicao.texto_dos_itens = "";
      }else{
        lista.conteudo.itens[posicao].mostrar_menu_adicao = false;
        lista.conteudo.itens[posicao].texto_dos_itens = "";
      }
    }
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  const cancelar_adicao_de_itens_simples = (lista, posicao) => {
    if(posicao === -1){
      lista.opcao_de_adicao.mostrar_menu_adicao = "Opções de adição";
      lista.opcao_de_adicao.texto_dos_itens = "";
    }else{
      lista.conteudo.itens[posicao].mostrar_menu_adicao = "Opções de adição";
      lista.conteudo.itens[posicao].texto_dos_itens = "";
    }
    
    setAttributes({
      lista: Object.assign({}, attr.lista)
    });
  };
  
  return(
    <div {...useBlockProps()}>
      {attr.conteudo_do_bloco}
    </div>
  );
}
