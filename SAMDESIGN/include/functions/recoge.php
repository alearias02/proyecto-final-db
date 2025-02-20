<?php

function recogePost($var, $m = "")
{

  if (!isset($_POST[$var])) {
    $tmp = (is_array($m)) ? [] : "";
  } elseif (!is_array($_POST[$var])) {
 
    $tmp = trim(htmlspecialchars($_POST[$var], ENT_QUOTES, "UTF-8"));
  } else {
    $tmp = $_POST[$var];

      array_walk_recursive($tmp, function (&$valor) {
      $valor = trim(htmlspecialchars($valor, ENT_QUOTES, "UTF-8"));
    });
  }
  return $tmp;
}

function recogeGet($var, $m = "")
{
  if (!isset($_GET[$var])) {
    $tmp = (is_array($m)) ? [] : "";
  } elseif (!is_array($_GET[$var])) {
    $tmp = trim(htmlspecialchars($_GET[$var], ENT_QUOTES, "UTF-8"));
  } else {
    $tmp = $_GET[$var];
      array_walk_recursive($tmp, function (&$valor) {
      $valor = trim(htmlspecialchars($valor, ENT_QUOTES, "UTF-8"));
    });
  }
  return $tmp;
}