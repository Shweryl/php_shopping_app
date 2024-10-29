<?php
  session_start();
  require("../config/config.php");
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }
  $stmt = $pdo->prepare("DELETE FROM users WHERE id=".$_GET['id']);
  $result = $stmt->execute();
  if($result){
    echo "<script>window.location.href='user_list.php'</script>";
  }

?>
