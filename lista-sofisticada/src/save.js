import {useBlockProps} from "@wordpress/block-editor";
import {Icon} from "@wordpress/components";
import {caption, page} from "@wordpress/icons";

export default function save({className, attributes: attr, setAttributes}){
  attr.visual = attr.visual ?? attr.visual_configurado;
  
  const lista = attr.lista;
  if(!lista || "titulo" in lista === false){
    return;
  }
  
  function processamento_de_listas(lista, aninhamento){
    const elementos_react = Array();
    
    if(aninhamento === "impar"){
      aninhamento = "par";
    }else{
      aninhamento = "impar";
    }
    
    let titulo_da_lista = lista.titulo.texto;
    let url_do_link_do_titulo = null;
    var posicao_do_abre_chaves = titulo_da_lista.indexOf("{");
    var posicao_do_fecha_chaves = titulo_da_lista.indexOf("}");
    if(posicao_do_abre_chaves != -1 && posicao_do_fecha_chaves != -1 
       && posicao_do_abre_chaves < posicao_do_fecha_chaves){
      url_do_link_do_titulo = titulo_da_lista.substring(posicao_do_abre_chaves+1, posicao_do_fecha_chaves);
      titulo_da_lista = titulo_da_lista.substring(0, posicao_do_abre_chaves) + titulo_da_lista.substring(posicao_do_fecha_chaves+1);
    }
    
    let html_do_titulo_da_lista = <span>{titulo_da_lista}</span>;
    if(url_do_link_do_titulo !== null && url_do_link_do_titulo !== ""){
      html_do_titulo_da_lista = <a href={url_do_link_do_titulo}>{titulo_da_lista}</a>;
    }
    
    elementos_react.push(
      <div className="opcoes_e_titulo_da_lista">
        <div className="local_do_titulo_da_lista">
          {html_do_titulo_da_lista}
        </div>
        <div className="opcao_encolher tag_oculta" title="Encolher">
          <Icon icon={caption}/>
        </div>
        <div className="opcao_expandir tag_oculta" title="Expandir">
          <Icon icon={page}/>
        </div>
      </div>
    );
    
    const elementos_react_do_conteudo = Array();
    for(let i = 0; i < lista.conteudo.itens.length; i++){
      const item = lista.conteudo.itens[i];
      if("titulo" in item){
        //O item é uma lista
        elementos_react_do_conteudo.push(processamento_de_listas(item, aninhamento));
      }else if("texto" in item){
        //O item é um item simples
        
        let texto_do_item = item.texto;
        let url_do_link_do_texto = null;
        var posicao_do_abre_chaves = texto_do_item.indexOf("{");
        var posicao_do_fecha_chaves = texto_do_item.indexOf("}");
        if(posicao_do_abre_chaves != -1 && posicao_do_fecha_chaves != -1 
           && posicao_do_abre_chaves < posicao_do_fecha_chaves){
          url_do_link_do_texto = texto_do_item.substring(posicao_do_abre_chaves+1, posicao_do_fecha_chaves);
          texto_do_item = texto_do_item.substring(0, posicao_do_abre_chaves) + texto_do_item.substring(posicao_do_fecha_chaves+1);
        }
        
        let html_do_texto_do_item = <span>{texto_do_item}</span>;
        if(url_do_link_do_texto !== null && url_do_link_do_texto !== ""){
          html_do_texto_do_item = <a href={url_do_link_do_texto}>{texto_do_item}</a>;
        }
        
        elementos_react_do_conteudo.push(
          <div className="item_simples">
            <div className="local_do_texto_do_item">
              {html_do_texto_do_item}
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
      <div className={"lista aninhamento_"+aninhamento+" "+attr.visual}>
        {elementos_react}
      </div>
    );
  }
  
  return(
    <div {...useBlockProps.save()}>
      {processamento_de_listas(lista, "par")}
    </div>
  );
}
