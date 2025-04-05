<?php
/**
 * Plugin Name:       Lista Sofisticada
 * Description:       Faz um bloco de lista sofisticada no qual pode-se colocar outras listas dentro.
 * Requires at least: 6.6
 * Requires PHP:      8.3
 * Version:           2025.5
 * Author:            Rodrigo Diniz da Silva
 * Copyright:         2024
 * License:           GPL-2.0
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lista-sofisticada
 *
 * @package CreateBlock
 */
if(!defined('ABSPATH')){
  exit; //Sai se acessado diretamente
}

// Ações:
register_activation_hook(__FILE__, 'pluginListaSofisticada_ativar');
add_action('init', 'pluginListaSofisticada_criar_bloco_lista_sofisticada');
add_action('admin_menu', 'pluginListaSofisticada_adicionar_item_para_o_menu_principal');
register_deactivation_hook(__FILE__, 'pluginListaSofisticada_desativar');

function pluginListaSofisticada_ativar(){
  pluginListaSofisticada_criar_bloco_lista_sofisticada();
  flush_rewrite_rules();
}

function pluginListaSofisticada_criar_bloco_lista_sofisticada(){
  wp_register_style('pluginListaSofisticada_estilos', plugins_url('estilos.css', __FILE__),
  array(), '2025-01-01 21h23');
  wp_enqueue_style('pluginListaSofisticada_estilos');

  $visual_da_lista_sofisticada = get_option('pluginListaSofisticada_visual_da_lista');
  if($visual_da_lista_sofisticada === false){
    $visual_da_lista_sofisticada = 'visual_sofisticado';
  }

  $parametros = array();
  $parametros['attributes']['lista']['default'] = null;
  $parametros['attributes']['lista']['type'] = 'object';
  $parametros['attributes']['conteudo_do_bloco']['default'] = array();
  $parametros['attributes']['conteudo_do_bloco']['type'] = 'array';
  $parametros['attributes']['visual']['default'] = null;
  $parametros['attributes']['visual']['type'] = 'string';
  $parametros['attributes']['visual_configurado']['default'] = $visual_da_lista_sofisticada;
  $parametros['attributes']['visual_configurado']['type'] = 'string';
  register_block_type(__DIR__.'/build', $parametros);
}

function pluginListaSofisticada_adicionar_item_para_o_menu_principal(){
  add_settings_section('pluginListaSofisticada_secao_opcoes', 'Opcões da Lista',
  'pluginListaSofisticada_html_da_secao_opcoes', 'pluginListaSofisticada_pagina_de_configuracoes');

  $parametros = array();
  $parametros['type'] = 'string';
  $parametros['sanitize_callback'] = 'pluginListaSofisticada_validar_visual_escolhido';
  $parametros['show_in_rest'] = true;
  $parametros['default'] = 'visual_sofisticado';

  register_setting('pluginListaSofisticada_configuracoes',
  'pluginListaSofisticada_visual_da_lista', $parametros);

  add_settings_field('pluginListaSofisticada_botoes_de_radio_do_visual', 'Visual',
  'pluginListaSofisticada_html_dos_botoes_de_radio_do_visual',
  'pluginListaSofisticada_pagina_de_configuracoes', 'pluginListaSofisticada_secao_opcoes');

  add_menu_page('Opções do Plugin Lista Sofisticada', 'Opções do Plugin Lista Sofisticada',
  'manage_options', 'pluginListaSofisticada_pagina_de_configuracoes',
  'pluginListaSofisticada_html_requisitado_pelo_item_do_menu_principal',
  plugin_dir_url(__FILE__).'imagens/icone_do_menu_20x20.png', 20);
}

function pluginListaSofisticada_validar_visual_escolhido($visual_escolhido){
  switch($visual_escolhido){
    case 'visual_sofisticado':
    case 'visual_sofisticado_azul_metalico':
    case 'visual_sofisticado_citrico':
    case 'visual_sofisticado_azul_suave':
    case 'visual_sofisticado_azul_destaque':
    case 'visual_sofisticado_purpura_destaque':
    case 'visual_sofisticado_escuro':
    case 'visual_padrao':
    case 'visual_azul_metalico':
    case 'visual_citrico':
    case 'visual_azul_suave':
    case 'visual_azul_destaque':
    case 'visual_purpura_destaque':
    case 'visual_escuro':
      break;
    default:
      $visual_escolhido = 'visual_sofisticado';
      break;
  }
  return sanitize_key($visual_escolhido);
}

function pluginListaSofisticada_desativar(){
  unregister_setting('pluginListaSofisticada_configuracoes', 'pluginListaSofisticada_visual_da_lista');
  flush_rewrite_rules();
}

// HTMLs:
function pluginListaSofisticada_html_da_secao_opcoes($parametros){
  //echo '<span>'.$parametros['title'].'</span>';
  echo '';
}

function pluginListaSofisticada_html_dos_botoes_de_radio_do_visual(){
  $visual_da_lista_sofisticada = get_option('pluginListaSofisticada_visual_da_lista');

  $estado_do_botao_de_radio_visual_sofisticado = '';
  $estado_do_botao_de_radio_visual_sofisticado_azul_metalico = '';
  $estado_do_botao_de_radio_visual_sofisticado_citrico = '';
  $estado_do_botao_de_radio_visual_sofisticado_azul_suave = '';
  $estado_do_botao_de_radio_visual_sofisticado_azul_destaque = '';
  $estado_do_botao_de_radio_visual_sofisticado_purpura_destaque = '';
  $estado_do_botao_de_radio_visual_sofisticado_escuro = '';
  $estado_do_botao_de_radio_visual_padrao = '';
  $estado_do_botao_de_radio_visual_azul_metalico = '';
  $estado_do_botao_de_radio_visual_citrico = '';
  $estado_do_botao_de_radio_visual_azul_suave = '';
  $estado_do_botao_de_radio_visual_azul_destaque = '';
  $estado_do_botao_de_radio_visual_purpura_destaque = '';
  $estado_do_botao_de_radio_visual_escuro = '';
  switch($visual_da_lista_sofisticada){
    case 'visual_sofisticado':
      $estado_do_botao_de_radio_visual_sofisticado = ' checked="checked"';
      break;
    case 'visual_sofisticado_azul_metalico':
      $estado_do_botao_de_radio_visual_sofisticado_azul_metalico = ' checked="checked"';
      break;
    case 'visual_sofisticado_citrico':
      $estado_do_botao_de_radio_visual_sofisticado_citrico = ' checked="checked"';
      break;
    case 'visual_sofisticado_azul_suave':
      $estado_do_botao_de_radio_visual_sofisticado_azul_suave = ' checked="checked"';
      break;
    case 'visual_sofisticado_azul_destaque':
      $estado_do_botao_de_radio_visual_sofisticado_azul_destaque = ' checked="checked"';
      break;
    case 'visual_sofisticado_purpura_destaque':
      $estado_do_botao_de_radio_visual_sofisticado_purpura_destaque = ' checked="checked"';
      break;
    case 'visual_sofisticado_escuro':
      $estado_do_botao_de_radio_visual_sofisticado_escuro = ' checked="checked"';
      break;
    case 'visual_padrao':
      $estado_do_botao_de_radio_visual_padrao = ' checked="checked"';
      break;
    case 'visual_azul_metalico':
      $estado_do_botao_de_radio_visual_azul_metalico = ' checked="checked"';
      break;
    case 'visual_citrico':
      $estado_do_botao_de_radio_visual_citrico = ' checked="checked"';
      break;
    case 'visual_azul_suave':
      $estado_do_botao_de_radio_visual_azul_suave = ' checked="checked"';
      break;
    case 'visual_azul_destaque':
      $estado_do_botao_de_radio_visual_azul_destaque = ' checked="checked"';
      break;
    case 'visual_purpura_destaque':
      $estado_do_botao_de_radio_visual_purpura_destaque = ' checked="checked"';
      break;
    case 'visual_escuro':
      $estado_do_botao_de_radio_visual_escuro = ' checked="checked"';
      break;
  }

  echo
  '<input id="botao_de_radio_visual_sofisticado" name="pluginListaSofisticada_visual_da_lista" 
          type="radio" value="visual_sofisticado"'
  .esc_attr($estado_do_botao_de_radio_visual_sofisticado).'/>'
  .'<label for="botao_de_radio_visual_sofisticado">Visual Sofisticado</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_sofisticado.png').'"
         alt="visual_sofisticado.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_sofisticado_azul_metalico" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_sofisticado_azul_metalico"'
  .esc_attr($estado_do_botao_de_radio_visual_sofisticado_azul_metalico).'/>'
  .'<label for="botao_de_radio_visual_sofisticado_azul_metalico">Visual Sofisticado Azul Metálico</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_sofisticado_azul_metalico.png').'"
         alt="visual_sofisticado_azul_metalico.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_sofisticado_citrico" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_sofisticado_citrico"'
  .esc_attr($estado_do_botao_de_radio_visual_sofisticado_citrico).'/>'
  .'<label for="botao_de_radio_visual_sofisticado_citrico">Visual Sofisticado Cítrico</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_sofisticado_citrico.png').'"
         alt="visual_sofisticado_citrico.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_sofisticado_azul_suave" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_sofisticado_azul_suave"'
  .esc_attr($estado_do_botao_de_radio_visual_sofisticado_azul_suave).'/>'
  .'<label for="botao_de_radio_visual_sofisticado_azul_suave">Visual Sofisticado Azul Suave</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_sofisticado_azul_suave.png').'"
         alt="visual_sofisticado_azul_suave.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_sofisticado_azul_destaque" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_sofisticado_azul_destaque"'
  .esc_attr($estado_do_botao_de_radio_visual_sofisticado_azul_destaque).'/>'
  .'<label for="botao_de_radio_visual_sofisticado_azul_destaque">Visual Sofisticado Azul Destaque</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_sofisticado_azul_destaque.png').'"
         alt="visual_sofisticado_azul_destaque.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_sofisticado_purpura_destaque" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_sofisticado_purpura_destaque"'
  .esc_attr($estado_do_botao_de_radio_visual_sofisticado_purpura_destaque).'/>'
  .'<label for="botao_de_radio_visual_sofisticado_purpura_destaque">Visual Sofisticado Púrpura Destaque</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_sofisticado_purpura_destaque.png').'"
         alt="visual_sofisticado_purpura_destaque.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_sofisticado_escuro" name="pluginListaSofisticada_visual_da_lista" 
          type="radio" value="visual_sofisticado_escuro"'
  .esc_attr($estado_do_botao_de_radio_visual_sofisticado_escuro).'/>'
  .'<label for="botao_de_radio_visual_sofisticado_escuro">Visual Sofisticado Escuro</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_sofisticado_escuro.png').'"
         alt="visual_sofisticado_escuro.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_padrao" name="pluginListaSofisticada_visual_da_lista" 
          type="radio" value="visual_padrao"'
  .esc_attr($estado_do_botao_de_radio_visual_padrao).'/>'
  .'<label for="botao_de_radio_visual_padrao">Visual Padrão</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_padrao.png').'"
         alt="visual_padrao.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_azul_metalico" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_azul_metalico"'
  .esc_attr($estado_do_botao_de_radio_visual_azul_metalico).'/>'
  .'<label for="botao_de_radio_visual_azul_metalico">Visual Azul Metálico</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_azul_metalico.png').'"
         alt="visual_azul_metalico.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_citrico" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_citrico"'
  .esc_attr($estado_do_botao_de_radio_visual_citrico).'/>'
  .'<label for="botao_de_radio_visual_citrico">Visual Cítrico</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_citrico.png').'"
         alt="visual_citrico.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_azul_suave" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_azul_suave"'
  .esc_attr($estado_do_botao_de_radio_visual_azul_suave).'/>'
  .'<label for="botao_de_radio_visual_azul_suave">Visual Azul Suave</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_azul_suave.png').'"
         alt="visual_azul_suave.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_azul_destaque" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_azul_destaque"'
  .esc_attr($estado_do_botao_de_radio_visual_azul_destaque).'/>'
  .'<label for="botao_de_radio_visual_azul_destaque">Visual Azul Destaque</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_azul_destaque.png').'"
         alt="visual_azul_destaque.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_purpura_destaque" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_purpura_destaque"'
  .esc_attr($estado_do_botao_de_radio_visual_purpura_destaque).'/>'
  .'<label for="botao_de_radio_visual_purpura_destaque">Visual Púrpura Destaque</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_purpura_destaque.png').'"
         alt="visual_purpura_destaque.png"/>'
  .'<br/>'
  .'<br/>'
  .'<input id="botao_de_radio_visual_escuro" name="pluginListaSofisticada_visual_da_lista" 
           type="radio" value="visual_escuro"'
  .esc_attr($estado_do_botao_de_radio_visual_escuro).'/>'
  .'<label for="botao_de_radio_visual_escuro">Visual Escuro</label>'
  .'<br/>'
  .'<img src="'.esc_url(plugin_dir_url(__FILE__).'imagens/visual_escuro.png').'"
         alt="visual_escuro.png"/>'
  ;
}

function pluginListaSofisticada_html_requisitado_pelo_item_do_menu_principal(){
  if(!current_user_can('manage_options')){
    return;
  }
  ?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
      <div style="position: fixed; margin-top: 20px; margin-left: calc(210px + 630px + 20px)">
        <?php
        submit_button(__('Salvar', 'lista-sofisticada'));
        ?>
      </div>
      <?php
      settings_fields('pluginListaSofisticada_configuracoes');
      do_settings_sections('pluginListaSofisticada_pagina_de_configuracoes');
      ?>
    </form>
  </div>
  <?php
}
