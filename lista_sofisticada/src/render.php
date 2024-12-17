<?php

$visual = $attributes['visual'];
$visual_configurado = $attributes['visual_configurado'];

if($visual !== $visual_configurado){
  $content = str_replace("<div class=\"lista aninhamento_impar $visual\">", "<div class=\"lista aninhamento_impar $visual_configurado\">", $content);
  $content = str_replace("<div class=\"lista aninhamento_par $visual\">", "<div class=\"lista aninhamento_par $visual_configurado\">", $content);
}

$icone_encolher = "<svg viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" aria-hidden=\"true\"><path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M6 5.5h12a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5H6a.5.5 0 0 1-.5-.5V6a.5.5 0 0 1 .5-.5ZM4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6Zm4 10h2v-1.5H8V16Zm5 0h-2v-1.5h2V16Zm1 0h2v-1.5h-2V16Z\"></path></svg>";
$icone_expandir = "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" width=\"24\" height=\"24\" aria-hidden=\"true\"><path d=\"M15.5 7.5h-7V9h7V7.5Zm-7 3.5h7v1.5h-7V11Zm7 3.5h-7V16h7v-1.5Z\"></path><path d=\"M17 4H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2ZM7 5.5h10a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5H7a.5.5 0 0 1-.5-.5V6a.5.5 0 0 1 .5-.5Z\"></path></svg>";
$content = str_replace($icone_encolher, esc_html($icone_encolher), $content);
$content = str_replace($icone_expandir, esc_html($icone_expandir), $content);

echo wp_kses_post($content);
