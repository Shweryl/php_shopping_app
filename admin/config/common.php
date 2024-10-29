<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if(!hash_equals($_SESSION['_token'], $_POST['_token'])){
    echo "Invalid CSRF Token";
    die();
  }else{
    unset($_SESSION['_token']);
  }
}

if(empty($_SESSION['_token'])){
  if(function_exists('ramdom_bytes')){
    $_SESSION['_token'] = bin2hex(random_bytes(32));
  }else{
    $_SESSION['_token'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
}

// Escape html for output
function escape($html){
  return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

?>
