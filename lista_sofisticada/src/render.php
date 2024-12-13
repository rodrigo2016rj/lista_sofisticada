<?php

$visual = $attributes['visual'];
$visual_configurado = $attributes['visual_configurado'];

if($visual !== $visual_configurado){
  $content = str_replace("<div class=\"lista aninhamento_impar $visual\">", "<div class=\"lista aninhamento_impar $visual_configurado\">", $content);
  $content = str_replace("<div class=\"lista aninhamento_par $visual\">", "<div class=\"lista aninhamento_par $visual_configurado\">", $content);
}

echo $content;
