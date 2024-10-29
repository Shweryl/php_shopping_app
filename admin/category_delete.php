<?php
    require("config/config.php");

    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=".$_GET['id']);
    $result = $stmt->execute();
    if($result){
        echo "<script>alert('Deleted category');window.location.href='category.php';</script>";
    }
?>