<?php
session_start();
if(!$_SESSION["login"]=="professor" || !$_SESSION["login"]=="alumne"){
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    if($_SESSION["login"]=="professor"){

    }
    if($_SESSION["login"]=="alumne"){

    }
    ?>
</body>
</html>