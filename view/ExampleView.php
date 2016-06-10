<?php
  session_start();
  if( !isset( $_SESSION['u_'] ) ){
      header('Location: login.php', true, 303);
      die();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Example of Page</title>
  </head>
  <body>

  </body>
</html>
