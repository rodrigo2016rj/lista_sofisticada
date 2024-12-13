<?php
/**
 * Plugin Name:       Lista Sofisticada
 * Description:       Faz um bloco de lista sofisticada no qual pode-se colocar outras listas dentro.
 * Requires at least: 6.6
 * Requires PHP:      8.3
 * Version:           2024.1
 * Author:            Rodrigo Diniz da Silva
 * Copyright:         2024
 * License:           GPL-2.0
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lista_sofisticada
 *
 * @package CreateBlock
 */
if(!defined('ABSPATH')){
  exit;
}

if(!function_exists('plugin_lista_sofisticada_ativar')
 and !function_exists('plugin_lista_sofisticada_criar_bloco_lista_sofisticada')
 and !function_exists('plugin_lista_sofisticada_adicionar_item_para_o_menu_principal')
 and !function_exists('plugin_lista_sofisticada_desativar')
 and !function_exists('plugin_lista_sofisticada_html_requisitado_pelo_item_do_menu_pricipal')){

  // Ações:
  register_activation_hook(__FILE__, 'plugin_lista_sofisticada_ativar');
  add_action('init', 'plugin_lista_sofisticada_criar_bloco_lista_sofisticada');
  add_action('admin_menu', 'plugin_lista_sofisticada_adicionar_item_para_o_menu_principal');
  register_deactivation_hook(__FILE__, 'plugin_lista_sofisticada_desativar');

  function plugin_lista_sofisticada_ativar(){
    plugin_lista_sofisticada_criar_bloco_lista_sofisticada();
    flush_rewrite_rules();
  }

  function plugin_lista_sofisticada_criar_bloco_lista_sofisticada(){
    wp_register_style('estilos_do_plugin_lista_sofisticada', plugins_url('estilos.css', __FILE__));
    wp_enqueue_style('estilos_do_plugin_lista_sofisticada');

    $visual_da_lista_sofisticada = get_option('visual_da_lista_sofisticada');
    if($visual_da_lista_sofisticada === false){
      $visual_da_lista_sofisticada = 'visual_padrao';
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

  function plugin_lista_sofisticada_adicionar_item_para_o_menu_principal(){
    add_settings_section('plugin_lista_sofisticada_secao_opcoes', 'Opcões da Lista',
    'plugin_lista_sofisticada_html_da_secao_opcoes',
    'pagina_de_configuracoes_do_plugin_lista_sofisticada');

    $parametros = array();
    $parametros['type'] = 'string';
    $parametros['show_in_rest'] = true;
    $parametros['default'] = 'visual_padrao';

    register_setting('configuracoes_do_plugin_lista_sofisticada', 'visual_da_lista_sofisticada', $parametros);

    add_settings_field('botoes_de_radio_do_visual', 'Visual',
    'plugin_lista_sofisticada_html_dos_botoes_de_radio_do_visual',
    'pagina_de_configuracoes_do_plugin_lista_sofisticada', 'plugin_lista_sofisticada_secao_opcoes');

    add_menu_page('Opções do Plugin Lista Sofisticada', 'Opções do Plugin Lista Sofisticada',
    'manage_options', 'pagina_de_configuracoes_do_plugin_lista_sofisticada',
    'plugin_lista_sofisticada_html_requisitado_pelo_item_do_menu_pricipal',
    plugin_dir_url(__FILE__).'Imagens/Ícone do Menu 20x20.png', 20);
  }

  function plugin_lista_sofisticada_desativar(){
    unregister_setting('configuracoes_do_plugin_lista_sofisticada', 'visual_da_lista_sofisticada');
    flush_rewrite_rules();
  }

  // HTMLs:
  function plugin_lista_sofisticada_html_da_secao_opcoes($parametros){
    //echo '<span>'.$parametros['title'].'</span>';
    echo '';
  }

  function plugin_lista_sofisticada_html_dos_botoes_de_radio_do_visual(){
    $visual_da_lista_sofisticada = get_option('visual_da_lista_sofisticada');

    $html = '';
    if($visual_da_lista_sofisticada === 'visual_padrao'){
      $html .= '<input id="botao_de_radio_visual_padrao" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_padrao" "autocomplete="off" checked="checked">
                <label for="botao_de_radio_visual_padrao">Visual Padrão</label>';
    }else{
      $html .= '<input id="botao_de_radio_visual_padrao" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_padrao" "autocomplete="off">
                <label for="botao_de_radio_visual_padrao">Visual Padrão</label>';
    }
    $html .= '<br/>';
    $html .= '<img src="../wp-content/plugins/lista_sofisticada/Imagens/Visual Padrão.png"/>';
    $html .= '<br/>';
    $html .= '<br/>';
    if($visual_da_lista_sofisticada === 'visual_azul_metalico'){
      $html .= '<input id="botao_de_radio_visual_azul_metalico" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_azul_metalico" "autocomplete="off" checked="checked">
                <label for="botao_de_radio_visual_azul_metalico">Visual Azul Metálico</label>';
    }else{
      $html .= '<input id="botao_de_radio_visual_azul_metalico" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_azul_metalico" "autocomplete="off">
                <label for="botao_de_radio_visual_azul_metalico">Visual Azul Metálico</label>';
    }
    $html .= '<br/>';
    $html .= '<img src="../wp-content/plugins/lista_sofisticada/Imagens/Visual Azul Metálico.png"/>';
    $html .= '<br/>';
    $html .= '<br/>';
    if($visual_da_lista_sofisticada === 'visual_citrico'){
      $html .= '<input id="botao_de_radio_visual_citrico" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_citrico" "autocomplete="off" checked="checked">
                <label for="botao_de_radio_visual_citrico">Visual Cítrico</label>';
    }else{
      $html .= '<input id="botao_de_radio_visual_citrico" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_citrico" "autocomplete="off">
                <label for="botao_de_radio_visual_citrico">Visual Cítrico</label>';
    }
    $html .= '<br/>';
    $html .= '<img src="../wp-content/plugins/lista_sofisticada/Imagens/Visual Cítrico.png"/>';
    $html .= '<br/>';
    $html .= '<br/>';
    if($visual_da_lista_sofisticada === 'visual_azul_suave'){
      $html .= '<input id="botao_de_radio_visual_azul_suave" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_azul_suave" "autocomplete="off" checked="checked">
                <label for="botao_de_radio_visual_azul_suave">Visual Azul Suave</label>';
    }else{
      $html .= '<input id="botao_de_radio_visual_azul_suave" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_azul_suave" "autocomplete="off">
                <label for="botao_de_radio_visual_azul_suave">Visual Azul Suave</label>';
    }
    $html .= '<br/>';
    $html .= '<img src="../wp-content/plugins/lista_sofisticada/Imagens/Visual Azul Suave.png"/>';
    $html .= '<br/>';
    $html .= '<br/>';
    if($visual_da_lista_sofisticada === 'visual_azul_destaque'){
      $html .= '<input id="botao_de_radio_visual_azul_destaque" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_azul_destaque" "autocomplete="off" checked="checked">
                <label for="botao_de_radio_visual_azul_destaque">Visual Azul Destaque</label>';
    }else{
      $html .= '<input id="botao_de_radio_visual_azul_destaque" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_azul_destaque" "autocomplete="off">
                <label for="botao_de_radio_visual_azul_destaque">Visual Azul Destaque</label>';
    }
    $html .= '<br/>';
    $html .= '<img src="../wp-content/plugins/lista_sofisticada/Imagens/Visual Azul Destaque.png"/>';
    $html .= '<br/>';
    $html .= '<br/>';
    if($visual_da_lista_sofisticada === 'visual_purpura_destaque'){
      $html .= '<input id="botao_de_radio_visual_purpura_destaque" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_purpura_destaque" "autocomplete="off" checked="checked">
                <label for="botao_de_radio_visual_purpura_destaque">Visual Púrpura Destaque</label>';
    }else{
      $html .= '<input id="botao_de_radio_visual_purpura_destaque" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_purpura_destaque" "autocomplete="off">
                <label for="botao_de_radio_visual_purpura_destaque">Visual Púrpura Destaque</label>';
    }
    $html .= '<br/>';
    $html .= '<img src="../wp-content/plugins/lista_sofisticada/Imagens/Visual Púrpura Destaque.png"/>';
    $html .= '<br/>';
    $html .= '<br/>';
    if($visual_da_lista_sofisticada === 'visual_escuro'){
      $html .= '<input id="botao_de_radio_visual_escuro" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_escuro" "autocomplete="off" checked="checked">
                <label for="botao_de_radio_visual_escuro">Visual Escuro</label>';
    }else{
      $html .= '<input id="botao_de_radio_visual_escuro" name="visual_da_lista_sofisticada" 
                type="radio" value="visual_escuro" "autocomplete="off">
                <label for="botao_de_radio_visual_escuro">Visual Escuro</label>';
    }
    $html .= '<br/>';
    $html .= '<img src="../wp-content/plugins/lista_sofisticada/Imagens/Visual Escuro.png"/>';
    echo $html;
  }

  function plugin_lista_sofisticada_html_requisitado_pelo_item_do_menu_pricipal(){
    if(!current_user_can('manage_options')){
      return;
    }
    ?>
    <div class="wrap">
      <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
      <form action="options.php" method="post">
        <div style="position: fixed; margin-top: 20px; margin-left: calc(210px + 630px + 20px)">
          <?php
          submit_button(__('Salvar', 'textdomain'));
          ?>
        </div>
        <?php
        settings_fields('configuracoes_do_plugin_lista_sofisticada');
        do_settings_sections('pagina_de_configuracoes_do_plugin_lista_sofisticada');
        ?>
      </form>
    </div>
    <?php
  }

}
